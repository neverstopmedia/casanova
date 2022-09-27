<?php

class Casanova_List_Actions{

    /**
	* Class constructor.
	*
	* @since 2.0.0
	*/
	public function __construct(){
		// add_action('save_post', [$this, 'on_save_list'], 10, 3);
	}

	/**
	 * When a list is saved, lets update the sync key
	 *
	 * @since 1.0.0
	 */
	public function on_save_list($post_id, $post){

		if( $post != null && ($post->post_type !== 'list' || 'auto-draft' == $post->post_status) )
        return false;

		if( get_field( 'no_sync_global', 'options' ) )
		return false;

		if( get_field( 'no_sync', $post_id ) )
		return false;

		if( $connected_sites = get_field( 'connected_sites', $post_id ) ){

			foreach( $connected_sites as $site ){

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, get_field( 'url', $site ).'/wp-json/casanova/v1/lists');
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json',
					'Authorization: Basic ' . base64_encode( get_field('username', $site)  . ':' . get_field( 'password', $site ) ),
				]);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id' => $post_id]) );

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$result = curl_exec($ch);
				curl_close($ch);

			}

			// Let's update the last sync
			$dt = new DateTime("now", new DateTimeZone('Asia/Dubai'));
			$dt->setTimestamp(time());
			update_field( 'last_sync' , $dt->format('Y/m/d H:i:s'), $post_id);

		}

	}
  
}