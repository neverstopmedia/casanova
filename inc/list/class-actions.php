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