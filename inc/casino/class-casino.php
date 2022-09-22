<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class responsible for casino custom post type functionality
 * 
 * @since 1.0.0
 *
 */
class Casanova_Casino{

    /**
     * The casino ID.
     *
     * @since 1.0.0
     */
    public $ID = 0;

    /**
     * Post Status.
     *
     * @since 1.0.0
     */
    public $post_status;

    /**
     * Post Title.
     *
     * @since 1.0.0
     */
    public $post_title;

    /**
     * Post date.
     *
     * @since 1.0.0
     */
    public $date;
    
    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct( $casino_id ){
        $this->setup_casino( $casino_id );
    }

    /**
     * Set all the casino data.
     *
     * @since 1.0.0
     */
    private function setup_casino( $casino_id ){

        if ( empty( $casino_id ) )
        return false;

        $casino = get_post( $casino_id );

        // casino ID
        $this->ID               = absint( $casino_id );

		$this->post_status      = $casino->post_status;
		$this->date             = $casino->post_date;
        $this->post_title       = $casino->post_title;

    }
    
}
