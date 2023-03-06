<?php

class Casanova_List_Actions{

    /**
	* Class constructor.
	*
	* @since 2.0.0
	*/
	public function __construct(){
        add_action( 'manage_list_posts_custom_column' , [$this, 'admin_table_columns_data'], 10, 2 );
		add_filter( 'manage_list_posts_columns', [$this, 'admin_table_columns'] );

		add_action( 'add_meta_boxes', [$this, 'timeline_metabox'] );

		add_filter('acf/load_field/key=field_6401e5203b058', [$this, 'acf_list_excluded_sites'], 10 );

	}

	/**
     * Adds excluded sites to dropdown.
	 * 
	 * @since 1.1.5
     */
	public function acf_list_excluded_sites( $field ){
		
		$field['choices'] = [];

		$args = array(
			'post_type' => 'site',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);

		if( $posts = get_posts( $args ) ){
			foreach( $posts as $post ){

            	$field['choices'][get_field( 'url', $post->ID )] = get_the_title( $post->ID );
			}
		}

		return $field;

	}

	/**
     * Adds the meta box container for timeline
	 * 
	 * @since 1.1.0
     */
    public function timeline_metabox(){
        add_meta_box( 
            'list_timeline',
			'Timeline',
			array( $this, 'render_timeline_metabox' ),
			'list',
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
			get_template_part( 'template-parts/timeline/list', null, ['timeline' => $timeline] );
		}else{
			echo 'No timeline for this List';
		}
		
    }

	// Add the custom columns to the car post type:
	public function admin_table_columns($columns) {
		$columns['list_type'] = __( 'List Type', 'casanova' );
		$columns['connected_sites'] = __( 'Connected Sites', 'casanova' );
		$columns['list_order'] = __( 'Casinos', 'casanova' );
		$columns['last_sync'] = __( 'Last Sync', 'casanova' );

		return $columns;
	}

	// Add the data to the custom columns for the car post type:
	public function admin_table_columns_data( $column, $post_id ) {

		switch ( $column ) {

            case 'list_type':
				if( $list_type = get_field( 'list_type', $post_id ) ){
					echo $list_type == 1 ? 'Regular List' : 'Top Four';
				}else{
					echo '-';
				}
                break;
            case 'connected_sites':
				if( $connected_sites = get_field( 'connected_sites', $post_id ) ){
					echo '<div class="expandable-section">';
					foreach( $connected_sites as $site ){
						echo '<span class="d-block">'.get_the_title($site).'</span>';
					}
					echo '</div>';
				}else{
					echo '-';
				}
                break;
            case 'list_order':
				if( $casinos = get_field( 'list_order', $post_id ) ){
					echo '<div class="expandable-section">';
					foreach( $casinos as $casino ){
						echo '<span class="d-block">'.get_the_title($casino['list_order_item']).'</span>';
					}
					echo '</div>';
				}else{
					echo '-';
				}
				break;
            case 'last_sync':
                if( $last_sync = get_field( 'last_sync', $post_id ) ){
					echo $last_sync;
				}else{
					echo '-';
				}
                break;
		}
	}
  
}