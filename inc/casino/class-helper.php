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

    public static function get_casinos_from_list( $casino_id = null ){

        $sites = [];
        $new_sites = [];

        if( $lists = Casanova_List_Helper::get_lists() ){
            foreach( $lists as $list ){
                if( isset($list->order) && sizeof( $list->order ) )
                $casinos = array_column( $list->order, 'list_order_item' );

                if( !in_array( $casino_id, $casinos ) )
                continue;

                $sites[] = $list->sites;
    
            }
        }

        if( $sites ){
            foreach( $sites as $list_sites ){
                foreach( $list_sites as $site ){
                    $new_sites[] = $site;
                }
            }
        }
        
        return array_unique($new_sites);

    }

}