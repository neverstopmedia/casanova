<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Casanova_List_Helper{

    /**
     * Get Lists
     *
     * Retrieve lists from the database.
     *
     * @since 1.0.0
     * @param array $args Arguments passed to get lists
     * @return Casanova_List[] $lists Lists retrieved from the database
     */
    public static function get_lists( $args = array() ) {

        // Ignore the arguments with null values
        $args = array_filter($args);

        $lists = new Casanova_List_Query( $args );
        return $lists->get_lists();
        
    }

    public static function update_lists_rest( $site, $post_id ){

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

}