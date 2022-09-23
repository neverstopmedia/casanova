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
		// add_action('save_post', [$this, 'on_save_casino'], 10, 3);
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
				curl_setopt($ch, CURLOPT_URL, $site['url'].'/wp-json/casanova/v1/casinos');
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

}