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

    // Let's check the filter status of the domain
    public static function check_domain_status( $domain ){

        $status = null;

        /* 
        *  This accepts the domain name as parameter, and will return a request_id 
        *  which will be used in the second call.
        */
        $check_1_url = 'https://check-host.net/check-ping?host='.$domain["application_domain"].'/max_nodes=1&node=ir1.node.check-host.net';

        // Initialize cURL session
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $check_1_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']); // Set the Accept header

        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }else{

            $response = json_decode($response);
            if( isset($response->error) || $response->ok != 1 ){
                return null;
            }

            // Let's sleep so we can wait for the results
            sleep(15);

            // Let's go for the second call
            $check_2_url = 'https://check-host.net/check-result/'.$response->request_id;

            $ch_2 = curl_init();

            curl_setopt($ch_2, CURLOPT_URL, $check_2_url);
            curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch_2, CURLOPT_HTTPHEADER, ['Accept: application/json']); // Set the Accept header

            $filter_response = curl_exec($ch_2);

            if(curl_errno($ch_2)) {
                echo 'Curl error: ' . curl_error($ch_2);
            }

            $status = json_decode($filter_response, true);

            // Close the second cURL session
            curl_close($ch_2);

        }

        // Close the first cURL session
        curl_close($ch);

        return $status;

    }

    // Check if the last time we ran a check on app domains is greater than 24 hours
    public static function last_domain_check_run(){

        $last_app_check = get_field( 'last_app_domain_check', 'option' );

        $dt = new DateTime("now", new DateTimeZone('Asia/Dubai'));
        $dt->setTimestamp(time()); 
        $today = $dt->format('Y/m/d H:i:s');
        $time_difference = strtotime($today) - strtotime($last_app_check);

        if( empty( $last_app_check ) || $time_difference > 86400 ){

            update_field( 'last_app_domain_check', $today, 'option' );
            return true;

        }

        return false;
        
    }

}