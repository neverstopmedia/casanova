<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class responsible for casino actions
 * 
 * @since 1.0.0
 *
 */
class Casanova_Casino_Actions{

    public function __construct(){
		add_action( 'acf/init', [$this, 'acf_casino_affiliate_links'] );
		add_filter('acf/load_value/name=casino_affiliate_links', [$this, 'acf_casino_default_links'], 10, 3);

		add_action( 'add_meta_boxes', [$this, 'timeline_metabox'] );

		add_action( 'manage_casino_posts_custom_column' , [$this, 'admin_table_columns_data'], 10, 2 );
		add_filter( 'manage_casino_posts_columns', [$this, 'admin_table_columns'] );

        add_action( 'wp_ajax_get_casinos_with_apps', [ $this, 'get_casinos_with_apps' ] );
        add_action( 'wp_ajax_get_casino_apps', [ $this, 'get_casino_apps' ] );
        add_action( 'wp_ajax_check_and_update_domain', [ $this, 'check_and_update_domain' ] );

        add_action( 'wp_ajax_nopriv_get_casinos_with_apps', [ $this, 'get_casinos_with_apps' ] );
        add_action( 'wp_ajax_nopriv_get_casino_apps', [ $this, 'get_casino_apps' ] );
        add_action( 'wp_ajax_nopriv_check_and_update_domain', [ $this, 'check_and_update_domain' ] );

    }

    public function get_casinos_with_apps(){

        // Check timer > 24 hours
        if( !Casanova_Casino_Helper::last_domain_check_run() )
        wp_send_json_error( array( 'message' => 'Its been less than 24 hours since the last domain check, exiting.' ) );

        // Only get the casinos that have a valid APK link
        if( $casinos = Casanova_Casino_Helper::get_casinos( [ 'application_domains' => true, 'number' => -1 ] ) ){

            wp_send_json_success( array( 'message' => 'Casinos found', 'casinos' => $casinos ) );

        }

        wp_send_json_error( array( 'message' => 'No casinos that contain application APKs' ) );

    }

    // This function will update the application domains when they get flagged
    public function get_casino_apps(){

        $casino_id = isset($_POST['casino_id']) ? $_POST['casino_id'] : null;

        if( empty($casino_id) ){
            wp_send_json_error( array( 'message' => 'No proper casino ID provided' ) );
        }
        
        if( $app_domains = get_field( 'application_domains', $casino_id ) ){

            // This will be used to update the affiliate link with this domain
            $domain_list = [];

            foreach( $app_domains as $domain ){

                // If the domain is already filtered, lets continue to the next domain.
                if( $domain['application_domain_status'] == 'filtered' ){
                    continue;
                }

                $domain_list[] = $domain;

            }

            wp_send_json_success( array( 'message' => 'Latest domain checked', 'domain_list' => $domain_list ) );
        }else{
            wp_send_json_error( array( 'message' => 'No domains set for the casino application' ) );
        }

    }

    // This function checks the filter status, and updates accordingly
    public function check_and_update_domain(){

        $domain         = isset($_POST['domain']) ? $_POST['domain'] : null;
        $casino_id      = isset($_POST['casino_id']) ? $_POST['casino_id'] : null;

        if( empty($domain) ){
            wp_send_json_error( array( 'message' => 'Invalid domain' ) );
        }

        $app_domains    = get_field( 'application_domains', $casino_id );
        $filter_status  = Casanova_Casino_Helper::check_domain_status( $domain );

        // Current index of the domain we are checking
        $index = array_search( $domain['application_domain'], array_column( $app_domains, 'application_domain' ) );

        // Let's update the time for last checked for the domain
        $dt = new DateTime("now", new DateTimeZone('Asia/Dubai'));
        $dt->setTimestamp(time()); 
        $app_domains[$index]['last_checked'] = $dt->format('Y/m/d H:i:s');

        if( isset($filter_status['ir1.node.check-host.net'][0][0][0]) 
        && $filter_status['ir1.node.check-host.net'][0][0][0] == 'OK' ){

            // LETS UPDATE THE THIRSTY AFFILIATE LINK USING THE ID FIELD IN THE CASINO
            update_post_meta( get_field( 'app_tf_cloaked_id', $casino_id ) , '_ta_destination_url', $domain['application_domain'] );
            update_field('application_domains', $app_domains, $casino_id);

            wp_send_json_success( [ 
                'domain'    => $domain['application_domain'], 
                'continue'  => false, 
                'casino_id' => $casino_id, 
                'index'     => $index 
            ] );

        }else{

            // Let's set the domain as filtered with update_field, and then proceed
            $app_domains[$index]['application_domain_status'] = 'filtered';
            update_field('application_domains', $app_domains, $casino_id);

            wp_send_json_success( [ 
                'domain'    => $domain['application_domain'], 
                'continue'  => true, 
                'casino_id' => $casino_id, 
                'index'     => $index 
            ] );

        }


    }

	// Add the custom columns to the car post type:
	public function admin_table_columns($columns) {
		$columns['missing_links'] = __( 'Missing Links', 'casanova' );

		return $columns;
	}

	// Add the data to the custom columns for the car post type:
	public function admin_table_columns_data( $column, $post_id ) {

		switch ( $column ) {

            case 'missing_links':

			$links = get_field( 'casino_affiliate_links', $post_id );
			$links = is_array($links) ? array_column( $links, 'affiliate_link' ) : null;

			if( $links == null ){
				echo 'No links available';
				break;
			}

			$links = array_filter( $links, function( $link ){
				return empty($link);
			} );
			
			$links = $links ? count($links) : 0;
			$class = $links > 0 ? "tc-danger" : 'tc-success';

			echo '<span class="fw-600 '.$class.'">' . $links . ' sites are missing links</span>';
			break;

		}
	}

	/**
     * Adds the meta box container for timeline
	 * 
	 * @since 1.1.0
     */
    public function timeline_metabox(){
        add_meta_box( 
            'casino_timeline',
			'Timeline',
			array( $this, 'render_timeline_metabox' ),
			'casino',
			'advanced',
			'high'
        );
    }

    /**
     * Render Meta Box content for timeline
	 * 
	 * @since 1.1.0
     */
    public function render_timeline_metabox() {

		if( $timeline = get_field( 'sync_history' ) ){
			get_template_part( 'template-parts/timeline/casino', null, ['timeline' => $timeline] );
		}else{
			echo 'No timeline for this Casino';
		}
    }

    /**
	 * When a casino is saved, lets update the sync key
	 *
	 * @deprecated
	 * @since 1.0.0
	 */
	public function deprecated_on_save_casino($post_id, $post){

		if( $post != null && ($post->post_type !== 'casino' || 'auto-draft' == $post->post_status) )
        return false;

		if( get_field( 'no_sync_global', 'options' ) )
		return false;

		if( get_field( 'no_sync', $post_id ) )
		return false;

		if( $connected_sites = Casanova_Casino_Helper::get_casinos_from_list($post_id) ){

			foreach( $connected_sites as $site ){
				Casanova_Casino_Helper::update_casino_rest( $site, $post_id );
			}

			// Let's update the last sync
			$dt = new DateTime("now", new DateTimeZone('Asia/Dubai'));
			$dt->setTimestamp(time());
			update_field( 'last_sync' , $dt->format('Y/m/d H:i:s'), $post_id);
			
		}
		
	}
	
	/**
	 * Add the acf fields for the casino
	 *
	 * @since 1.0.0
	 */
	public function acf_casino_affiliate_links(){

		$location = array( array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'casino',
			),
		) );

		$fields = array (
            array(
				'key'               => 'casino_affiliates_01',
				'label'             => 'Links',
				'name'              => 'casino_affiliate_links',
				'type'              => 'repeater',
				'layout'            => 'block',
				'button_label'      => 'Add new Link',
				'sub_fields' 		=> array(
					array (
						'key'            => 'casino_affiliate_site_id_01',
						'label'          => 'Site ID',
						'name'           => 'affiliate_site_id',
						'parent'         => 'casino_affiliates_01',
						'type'           => 'text',
						'required'       => 1,
						'wrapper' => array(
							'width' => '10'
						)
					),
					array (
						'key'            => 'casino_affiliate_site_01',
						'label'          => 'Site Alias',
						'name'           => 'affiliate_site',
						'parent'         => 'casino_affiliates_01',
						'type'           => 'text',
						'required'       => 1,
						'wrapper' => array(
							'width' => '20'
						)
					),
					array (
						'key'            => 'casino_affiliate_site_nickname_01',
						'label'          => 'Nickname',
						'name'           => 'affiliate_site_nickname',
						'parent'         => 'casino_affiliates_01',
						'type'           => 'text',
						'required'       => 0,
						'wrapper' => array(
							'width' => '20'
						)
					),
					array (
						'key'            => 'casino_affiliate_link_01',
						'label'          => 'Affiliate Link',
						'name'           => 'affiliate_link',
						'parent'         => 'casino_affiliates_01',
						'type'           => 'text',
						'required'       => 1,
						'wrapper' => array(
							'width' => '50'
						)
					)
				)
			)
        );

		acf_add_local_field_group(array(
			'key' => 'casanova_casino_links',
			'title' => 'Affiliate Links',
			'fields' => $fields,
			'location' => $location,
		));
		
	}

	/**
	 * Sets the connected sites as default values for the casino
	 *
	 * @since 1.0.0
	 */
	public function acf_casino_default_links( $value, $post_id, $field ){

		if( $connected_sites = Casanova_Casino_Helper::get_casinos_from_list( $post_id ) ){

			$sites = [];
			$similar = [];

			if( is_array($value) ){

				$existing_sites = array_column( $value, 'casino_affiliate_site_id_01' );

				// If the nickname is empty, lets fill it up
				foreach( $value as $key => $site ){

					if( !isset($value[$key]['casino_affiliate_site_nickname_01']) || empty( $value[$key]['casino_affiliate_site_nickname_01'] ) ){
						
						if( in_array( $site['casino_affiliate_site_id_01'], $existing_sites ) )
						$value[$key]['casino_affiliate_site_nickname_01'] = get_field( 'nickname', $site['casino_affiliate_site_id_01'] );

					}
				}

				if( sizeof( $existing_sites ) == sizeof($connected_sites) ){
					
					return $value;

				}else{

					foreach( $value as $key => $site ){
						if( !in_array( $site['casino_affiliate_site_id_01'], $connected_sites ) ){
							unset($value[$key]);
						}else{
							$similar[] = $site['casino_affiliate_site_id_01'];
						}
					}
					
					foreach( array_diff( $connected_sites, $similar ) as $site ){
						$value[] = array(
							'casino_affiliate_site_id_01' 		=> $site,
							'casino_affiliate_site_01' 			=> get_field( 'alias', $site ),
							'casino_affiliate_site_nickname_01' => get_field( 'nickname', $site ),
							'casino_affiliate_link_01' 			=> '',
						);
					}

					return $value;

				}

			}else{

				$value = [];

				foreach( $connected_sites as $site ){
					$value[] = array(
						'casino_affiliate_site_id_01' 		=> $site,
						'casino_affiliate_site_01' 			=> get_field( 'alias', $site ),
						'casino_affiliate_site_nickname_01' => get_field( 'nickname', $site ),
						'casino_affiliate_link_01' 			=> '',
					);
				}

				return $value;

			}
			
		}

		return null;

	}

}