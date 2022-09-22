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
		add_action('save_post', [$this, 'on_save_casino'], 10, 3);
		add_action( 'manage_casino_posts_custom_column' , [$this, 'casino_admin_table_columns_data'], 10, 2 );
		add_filter( 'manage_casino_posts_columns', [$this, 'casino_admin_table_columns'] );
    }

    /**
	 * When a casino is saved, lets update the sync key
	 *
	 * @since 1.0.0
	 */
	public function on_save_casino($post_id, $post){

		if( $post != null && ($post->post_type !== 'casino' || 'auto-draft' == $post->post_status) )
        return false;

		if( get_field( 'no_sync_global', 'options' ) )
		return false;

		if( get_field( 'no_sync', $post_id ) )
		return false;

		if( $connected_sites = get_field( 'connected_sites', 'options' ) ){

			foreach( $connected_sites as $site ){

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $site['url'].'/wp-json/police/v1/casinos');
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json',
					'Authorization: Basic ' . base64_encode($site['username'] . ':' . $site['password']),
				]);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id' => $post_id]) );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch);
				curl_close($ch);

			}

			// Let's update the last sync
			update_field( 'last_sync' , date("d-m-Y"), $post_id);

		}

	}

	/**
	 * Add the custom columns to the casino post type
	 *
	 * @since 1.0.0
	 */
	public function casino_admin_table_columns($columns) {
		$columns['insurance'] = __( 'Insurance', 'casanova' );
		$columns['delivery'] = __( 'Delivery', 'casanova' );
		$columns['type'] = __( 'Type', 'casanova' );
		$columns['brand'] = __( 'Brand', 'casanova' );
		$columns['security_deposit'] = __( 'Deposit', 'casanova' );
		$columns['vat'] = __( 'VAT', 'casanova' );
		$columns['sync'] = __( 'Sync', 'casanova' );

		return $columns;
	}

	/**
	 * Add the data to the custom columns for the casino post type
	 *
	 * @since 1.0.0
	 */
	public function casino_admin_table_columns_data( $column, $post_id ) {

		switch ( $column ) {

            case 'type' :
                if($types = Casanova_Helper::get_tax_terms( $post_id, 'casino_type' )){
                    foreach( $types as $type ){
                        echo '<span class="d-block">'.$type->name.'</span>';
                    }
                }else{
                    echo '-';
                }
                break;
            case 'brand' :
                if($brands = Casanova_Helper::get_tax_terms( $post_id, 'casino_brand' )){
                    foreach( $brands as $brand ){
                        echo '<span class="d-block">'.$brand->name.'</span>';
                    }
                }else{
                    echo '-';
                }
                break;
			case 'delivery' :
				if($delivery_class = get_field('delivery_class', $post_id)){
					echo '<span class="d-block">'.get_the_title($delivery_class).'</span>';
				}else{
					echo '-';
				}
				break;
            case 'insurance' :
                if($insurance_types = get_field('insurance_types', $post_id)){
                    foreach( $insurance_types as $insurance_type ){
                        echo '<span class="d-block">'.get_the_title($insurance_type).'</span>';
                    }
                }else{
                    echo '-';
                }
                break;
            case 'security_deposit' :
				if( $security_deposit = get_field('security_deposit', $post_id ) ){
					echo 'AED ' . number_format( $security_deposit, 0 ); 
				}else{
                    echo '-';
				}
                break;
            case 'vat' :
                echo get_field('vat', $post_id ) . '%';
                break;
			case 'sync' :
				$sync_status = get_field('no_sync', $post_id ) ? 'danger' : 'success';
				echo '<span class="d-block casanova-dot bg-'.$sync_status.'"></span>';
				break;
		}
	}
   
}