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
							'width' => '45'
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
							'width' => '45'
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

			if( is_array($value) ){

				$existing_sites = array_column( $value, 'casino_affiliate_site_id_01' );

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
							'casino_affiliate_site_id_01' => $site,
							'casino_affiliate_site_01' => get_field( 'alias', $site ),
							'casino_affiliate_link_01' => '',
						);
					}

					return $value;

				}

			}else{

				foreach( $connected_sites as $site ){
					$value[] = array(
						'casino_affiliate_site_id_01' => $site,
						'casino_affiliate_site_01' => get_field( 'alias', $site ),
						'casino_affiliate_link_01' => '',
					);
				}

				return $value;

			}
			
		}

		return null;

	}

}