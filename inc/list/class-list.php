<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class responsible for list custom post type functionality
 * 
 * @since 1.0.0
 *
 */
class Casanova_List{

    /**
     * The list ID.
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

    public $sites;

    public $type;

    public $order;

    public $sync_disabled;
    
    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct( $list_id ){
        $this->setup_list( $list_id );
    }

    /**
     * Set all the list data.
     *
     * @since 1.0.0
     */
    private function setup_list( $list_id ){

        if ( empty( $list_id ) )
        return false;

        $list = get_post( $list_id );

        // list ID
        $this->ID               = absint( $list_id );

		$this->post_status      = $list->post_status;
		$this->date             = $list->post_date;
        $this->post_title       = $list->post_title;

        $this->sites            = get_field( 'connected_sites', $this->ID );
        $this->type             = get_field( 'list_type', $this->ID );
        $this->order            = get_field( 'list_order', $this->ID );
        $this->sync_disabled    = get_field( 'no_sync', $this->ID );

    }
    
}
