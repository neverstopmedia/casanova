<?php

class Casanova_List_Actions{

    /**
	* Class constructor.
	*
	* @since 2.0.0
	*/
	public function __construct(){
        add_action( 'manage_list_posts_custom_column' , [$this, 'list_admin_table_columns_data'], 10, 2 );
		add_filter( 'manage_list_posts_columns', [$this, 'list_admin_table_columns'] );
	}

    // Add the custom columns to the list post type:
	public function list_admin_table_columns($columns) {
		$columns['source'] = __( 'Source', 'casanova' );
		return $columns;
	}

	// Add the data to the custom columns for the list post type:
	public function list_admin_table_columns_data( $column, $post_id ) {
		switch ( $column ) {
			case 'source' :
                $source = get_post_meta($post_id, 'list_source', true );
				echo $source ? $source : 'Site';
				break;
		}
	}

}