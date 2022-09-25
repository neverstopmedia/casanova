<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Casanova_Casino_Query{

	/**
	 * The args to pass to the casanova_get_casinos() query
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $args = array();

	/**
	 * The args as they came into the class.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $initial_args = array();

	/**
	 * The casinos found based on the criteria set
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $casinos = array();

	/**
	 * Holds a boolean to determine if there is an existing $wp_query global.
	 *
	 * @var bool
	 * @access private
	 * @since 1.0.0
	 */
	private $existing_query;

	/**
	 * If an existing global $post item exists before we start our query, maintain it for later 'reset'.
	 *
	 * @var WP_Post|null
	 * @access private
	 * @since 1.0.0
	 */
	private $existing_post;

	public function __construct( $args = array() ){
	
		$defaults = array(
			'post_type'         	=> 'casino',
			'number'   		    	=> 20,
			'page'              	=> null,
			'orderby'           	=> 'ID',
			'order'             	=> 'DESC',
			'status'            	=> ['publish'],
			's'                 	=> null,
			'children'          	=> false,
			'fields'            	=> null,
			'post__in'          	=> null,
		);

		// We need to store an array of the args used to instantiate the class, so that we can use it in later hooks.
		$this->initial_args = wp_parse_args( $args, $defaults );
		$this->args 		= $this->initial_args;

		// Update the arguments with the global query if we are in the archive page.
		if( isset( $GLOBALS['wp_query'] ) && isset( $GLOBALS['wp_query']->query ) && is_post_type_archive( 'casino' ) ){
			$this->args = array_merge( $this->args, $GLOBALS['wp_query']->query );
		}

		$this->init();

	}

	/**
	 * Set a query variable.
	 *
	 * @since 1.0.0
	 */
	public function __set( $query_var, $value ) {
		if ( in_array( $query_var, array( 'meta_query', 'tax_query' ) ) )
			$this->args[ $query_var ][] = $value;
		else
			$this->args[ $query_var ] = $value;
	}

	/**
	 * Unset a query variable.
	 *
	 * @since 1.0.0
	 */
	public function __unset( $query_var ) {
		unset( $this->args[ $query_var ] );
	}

	/**
	 * Nothing here at the moment.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init() {

		// Before we start setting up queries, let's store any existing queries that might be in globals.
		$this->existing_query = isset( $GLOBALS['wp_query'] ) && isset( $GLOBALS['wp_query']->post );
		$this->existing_post  = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

	}

	/**
	 * Retrieve casinos.
	 *
	 * The query can be modified in two ways; either the action before the
	 * query is run, or the filter on the arguments (existing mainly for backwards
	 * compatibility).
	 *
	 * @since 1.0.0
	 * @return Casanova_Casino[]
	 */
	public function get_casinos() {

		// Modify the query/query arguments before we retrieve payments.
		$this->orderby();
		$this->status();
		$this->per_page();
		$this->page();
		$this->search();
		$this->children();
		$this->post__in();

		$query = new WP_Query( $this->args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {

				$query->the_post();

				$this->casinos[] = new Casanova_Casino( get_the_ID() );
			}
		}

		$this->maybe_reset_globals();

		return $this->casinos;
	}

	/**
	 * Post Status
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function status() {
		if ( ! isset ( $this->args['status'] ) ) {
			return;
		}

		$this->__set( 'post_status', $this->args['status'] );
		$this->__unset( 'status' );
	}

	/**
	 * Current Page
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function page() {
		if ( ! isset ( $this->args['page'] ) ) {
			return;
		}

		$this->__set( 'paged', $this->args['page'] );
		$this->__unset( 'page' );
	}

	/**
	 * Posts Per Page
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function per_page() {

		if( ! isset( $this->args['number'] ) ){
			return;
		}

		if ( $this->args['number'] == -1 ) {
			$this->__set( 'nopaging', true );
		}
		else{
			$this->__set( 'posts_per_page', $this->args['number'] );
		}

		$this->__unset( 'number' );
	}

	/**
	 * Order by
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function orderby() {
		if ( ! isset ( $this->args['order_by'] ) ) {
			return;
		}

		$this->__set( 'orderby', $this->args['order_by'] );
		$this->__unset( 'order_by' );
	}

	/**
	 * Specific casinos
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	private function post__in() {
		if ( is_null( $this->args['post__in'] ) ) {
			return;
		}

		// Convert the string to an array if string was given.
		if(!is_array($this->args['post__in']) && !empty($this->args['post__in']) ){
			$this->args['post__in'] = explode(",", $this->args['post__in']);
		}

		$this->__set( 'post__in', $this->args['post__in'] );
	}

	/**
	 * Search
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function search() {

		if( ! isset( $this->args['s'] ) ) {
			return;
		}

		$search = trim( $this->args['s'] );

		if( empty( $search ) ) {
			return;
		}

		$this->__set( 's', $search );

	}

	/**
	 * Children
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function children() {
		if ( empty( $this->args['children'] ) ) {
			$this->__set( 'post_parent', 0 );
		}
		$this->__unset( 'children' );
	}

	/**
	 * Based off the current global variables for $wp_query and $post, we may need to reset some data or just restore it.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function maybe_reset_globals() {
		// Based off our pre-iteration, let's reset the globals.
		if ( $this->existing_query ) {
			wp_reset_postdata();
		} elseif ( $this->existing_post ) {
			$GLOBALS['post'] = $this->existing_post;
		} else {
			unset( $GLOBALS['post'] );
		}
	}

}