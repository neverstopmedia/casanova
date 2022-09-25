<?php

class Casanova_Casino_Endpoints extends WP_REST_Controller{

    private $base;

    public function __construct(){

        $this->base = 'casinos';

        add_action( 'rest_api_init', [$this, 'register_routes'] );

    }

    public function register_routes(){

        register_rest_route( CASANOVA_API_ROUTE, '/' . $this->base , array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [ $this, 'get_casinos' ],
        ) );

        register_rest_route( CASANOVA_API_ROUTE, '/' . $this->base . '/(?P<id>[\d]+)', array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [ $this, 'get_casino' ],
            'args'                => [
                'context' => [
                    'default' => 'view',
                ],
            ]
        ) );

    }

    public function get_casinos( $request ){

        $args = [ 'number' => -1, 'status' => [ 'publish', 'draft' ] ];
        $data = [];

        if( $casinos = Casanova_Casino_Helper::get_casinos($args) ){
            foreach( $casinos as $key => $casino ){
                $data[] = $this->prepare_response_for_collection( $this->prepare_item_for_response( $casino, $request ) );
            }
        }

        return new WP_REST_Response( $data, 200 );
        
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_casino( $request ) {

        //get parameters from request
        $params = $request->get_params();
        $item = Casanova_Casino_Helper::get_casinos( [ 'number' => 1, 'post__in' => $params['id'], 'status' => [ 'publish', 'draft' ] ] );
        $data = $item ? $this->prepare_item_for_response( $item[0], $request ) : null;
    
        // Let's return the resopnse
        return $item ? new WP_REST_Response( $data, 200 ) : new WP_Error( 401, __( 'No casino with this ID found', 'casanova' ) );

    }

    /**
     * Check if a given request has access to get a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
    }
    
    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }
    
    /**
     * Check if a given request has access to update a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check( $request ) {
        return $this->create_item_permissions_check( $request );
    }
    
    /**
     * Check if a given request has access to delete a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check( $request ) {
        return $this->create_item_permissions_check( $request );
    }
    
    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database( $request ) {
        return array();
    }
    
    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_item_for_response( $item, $request ) {
        return $item;
    }

}