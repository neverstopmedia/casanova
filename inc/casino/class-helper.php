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

}