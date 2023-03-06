<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Casanova_Casino_Helper{

    /**
     * Get Casinos
     *
     * Retrieve casinos from the database.
     *
     * @since 1.0.0
     * @param array $args Arguments passed to get casinos
     * @return Casanova_Casino[] $casinos Casinos retrieved from the database
     */
    public static function get_casinos( $args = array() ) {

        // Ignore the arguments with null values
        $args = array_filter($args);

        $casinos = new Casanova_Casino_Query( $args );
        return $casinos->get_casinos();
    }

    public static function update_history( $post_id, $date ){

        $data = get_field('casino_affiliate_links', $post_id);
        add_row('sync_history', 
        [
            'data' => json_encode([
                'data' => $data, 
                'date' => $date,
                'user' => wp_get_current_user()->user_login
            ])
        ], $post_id);

    }

    public static function update_casino_rest( $site, $post_id ){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, get_field( 'url', $site ).'/wp-json/casanova/v1/casinos');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode(get_field( 'username', $site ) . ':' . get_field( 'password', $site )),
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id' => $post_id]) );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

    }

    public static function get_casinos_from_list( $casino_id = null ){

        $sites = [];
        $new_sites = [];

        if( $lists = Casanova_List_Helper::get_lists( ['number' => -1] ) ){
            foreach( $lists as $list ){
                if( isset($list->order) && is_array( $list->order ) && sizeof( $list->order ) )
                $casinos = array_column( $list->order, 'list_order_item' );

                if( !in_array( $casino_id, $casinos ) )
                continue;

                $sites[] = $list->sites;
    
            }
        }

        if( $sites ){
            foreach( $sites as $list_sites ){
                if( $list_sites ){
                    foreach( $list_sites as $site ){
                        $new_sites[] = $site;
                    }
                }
            }
        }
        
        return array_unique($new_sites);

    }

}