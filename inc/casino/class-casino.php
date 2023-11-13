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

    public $casino_category;

    public $casino_tag;

    public $casino_payment;

    public $casino_banking_option;

    public $is_most_recommended;

    public $casino_rating;
    
    public $casino_logo;
    
    public $casino_name;
    
    public $casino_perks;
    
    public $casino_excerpt;
    
    public $mega_offer;
    
    public $casino_color;
    
    public $game_forms;

    public $license;
    
    public $release_year;
    
    public $bonuses;
    
    public $mobile_app;
    
    public $live_betting;
    
    public $tax_free_winnings;
    
    public $live_chat;
    
    public $bank_id;
    
    public $number_of_bets;
    
    public $currencies;
    
    public $min_deposit;
    
    public $withdrawal_time;

    public $affiliates;
    
    public $apk_link;
    
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

        $this->casino_category  = Casanova_Helper::get_tax_terms( $casino_id, 'casino_category' );

        $this->casino_tag       = Casanova_Helper::get_tax_terms( $casino_id, 'casino_tag' );

        $this->casino_payment   = Casanova_Helper::get_tax_terms( $casino_id, 'casino_payment' );

        $this->casino_banking_option = Casanova_Helper::get_tax_terms( $casino_id, 'casino_banking_option' );

        $this->is_most_recommended = get_field( 'is_most_recommended', $casino_id );

        $this->casino_rating = get_field( 'casino_rating', $casino_id );

        $this->casino_logo = get_field( 'casino_logo', $casino_id );

        $this->casino_name = get_field( 'casino_name', $casino_id );

        $this->casino_perks = get_field( 'casino_perks', $casino_id );

        $this->casino_excerpt = get_field( 'casino_excerpt', $casino_id );

        $this->mega_offer = get_field( 'mega_offer', $casino_id );

        $this->casino_color = get_field( 'casino_color', $casino_id );

        $this->game_forms = get_field( 'game_forms', $casino_id );

        $this->license = get_field( 'license', $casino_id );

        $this->release_year = get_field( 'release_year', $casino_id );

        $this->bonuses = get_field( 'bonuses', $casino_id );

        $this->mobile_app = get_field( 'mobile_app', $casino_id );

        $this->live_betting = get_field( 'live_betting', $casino_id );

        $this->tax_free_winnings = get_field( 'tax_free_winnings', $casino_id );

        $this->live_chat = get_field( 'live_chat', $casino_id );

        $this->bank_id = get_field( 'bank_id', $casino_id );

        $this->number_of_bets = get_field( 'number_of_bets', $casino_id );

        $this->currencies = get_field( 'currencies', $casino_id );

        $this->min_deposit = get_field( 'min_deposit', $casino_id );

        $this->withdrawal_time = get_field( 'withdrawal_time', $casino_id );

        $this->affiliates = get_field( 'casino_affiliate_links', $casino_id );

        $this->apk_link = get_field( 'apk_link', $casino_id );

        $this->casino_labels = get_field( 'casino_labels', $casino_id );

    }
    
}
