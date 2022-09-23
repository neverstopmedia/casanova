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

}