<?php
/**
 * Register the Category taxonomy
 * 
 * @since 1.0.0
 */
function casanova_casino_category_tax(){
    $category_labels = array(
        'name'              => sprintf( _x( '%s Categories', 'taxonomy general name', 'casanova' ), 'Casino' ),
        'singular_name'     => sprintf( _x( '%s Category', 'taxonomy singular name', 'casanova' ), 'Casino' ),
        'search_items'      => sprintf( __( 'Search %s Categories', 'casanova' ), 'Casino' ),
        'all_items'         => sprintf( __( 'All %s Categories', 'casanova' ), 'Casino' ),
        'parent_item'       => sprintf( __( 'Parent %s Category', 'casanova' ), 'Casino' ),
        'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'casanova' ), 'Casino' ),
        'edit_item'         => sprintf( __( 'Edit %s Category', 'casanova' ), 'Casino' ),
        'update_item'       => sprintf( __( 'Update %s Category', 'casanova' ), 'Casino' ),
        'add_new_item'      => sprintf( __( 'Add New %s Category', 'casanova' ), 'Casino' ),
        'new_item_name'     => sprintf( __( 'New %s Category Name', 'casanova' ), 'Casino' ),
        'menu_name'         => __( 'Categories', 'casanova' ),
    );
    
    $category_args = array(
        'hierarchical' => true,
        'labels'       => $category_labels,
        'show_ui'      => true,
        'query_var'    => 'casino_category',
    );
    register_taxonomy( 'casino_category', ['casino'], $category_args );
}
add_action('init',  'casanova_casino_category_tax' );

/**
 * Register the Tag taxonomy
 * 
 * @since 1.0.0
 */
function casanova_casino_tag_tax(){
    $tag_labels = array(
        'name'              => sprintf( _x( '%s Tags', 'taxonomy general name', 'casanova' ), 'Casino' ),
        'singular_name'     => sprintf( _x( '%s Tag', 'taxonomy singular name', 'casanova' ), 'Casino' ),
        'search_items'      => sprintf( __( 'Search %s Tags', 'casanova' ), 'Casino' ),
        'all_items'         => sprintf( __( 'All %s Tags', 'casanova' ), 'Casino' ),
        'parent_item'       => sprintf( __( 'Parent %s Tag', 'casanova' ), 'Casino' ),
        'parent_item_colon' => sprintf( __( 'Parent %s Tag:', 'casanova' ), 'Casino' ),
        'edit_item'         => sprintf( __( 'Edit %s Tag', 'casanova' ), 'Casino' ),
        'update_item'       => sprintf( __( 'Update %s Tag', 'casanova' ), 'Casino' ),
        'add_new_item'      => sprintf( __( 'Add New %s Tag', 'casanova' ), 'Casino' ),
        'new_item_name'     => sprintf( __( 'New %s Tag Name', 'casanova' ), 'Casino' ),
        'menu_name'         => __( 'Tags', 'casanova' ),
    );
    
    $tag_args = array(
        'hierarchical' => true,
        'labels'       => $tag_labels,
        'show_ui'      => true,
        'query_var'    => 'casino_tag',
    );
    register_taxonomy( 'casino_tag', ['casino'], $tag_args );
}
add_action('init',  'casanova_casino_tag_tax' );

/**
 * Register the Payments taxonomy
 * 
 * @since 1.0.0
 */
function casanova_casino_payments_tax(){
    $payment_labels = array(
        'name'              => sprintf( _x( '%s Payments', 'taxonomy general name', 'casanova' ), 'Casino' ),
        'singular_name'     => sprintf( _x( '%s Payment', 'taxonomy singular name', 'casanova' ), 'Casino' ),
        'search_items'      => sprintf( __( 'Search %s Payments', 'casanova' ), 'Casino' ),
        'all_items'         => sprintf( __( 'All %s Payments', 'casanova' ), 'Casino' ),
        'parent_item'       => sprintf( __( 'Parent %s Payment', 'casanova' ), 'Casino' ),
        'parent_item_colon' => sprintf( __( 'Parent %s Payment:', 'casanova' ), 'Casino' ),
        'edit_item'         => sprintf( __( 'Edit %s Payment', 'casanova' ), 'Casino' ),
        'update_item'       => sprintf( __( 'Update %s Payment', 'casanova' ), 'Casino' ),
        'add_new_item'      => sprintf( __( 'Add New %s Payment', 'casanova' ), 'Casino' ),
        'new_item_name'     => sprintf( __( 'New %s Payment Name', 'casanova' ), 'Casino' ),
        'menu_name'         => __( 'Payments', 'casanova' ),
    );
    
    $payment_args = array(
        'hierarchical' => true,
        'labels'       => $payment_labels,
        'show_ui'      => true,
        'public'       => false,
        'query_var'    => 'casino_payment',
    );
    register_taxonomy( 'casino_payment', ['casino'], $payment_args );
}
add_action('init',  'casanova_casino_payments_tax' );

/**
 * Register the Banking Options taxonomy
 * 
 * @since 2.1.0
 */
function casanova_casino_banking_options_tax(){
    $banking_option_labels = array(
        'name'              => sprintf( _x( '%s Banking Options', 'taxonomy general name', 'casanova' ), 'Casino' ),
        'singular_name'     => sprintf( _x( '%s Banking Option', 'taxonomy singular name', 'casanova' ), 'Casino' ),
        'search_items'      => sprintf( __( 'Search %s Banking Options', 'casanova' ), 'Casino' ),
        'all_items'         => sprintf( __( 'All %s Banking Options', 'casanova' ), 'Casino' ),
        'parent_item'       => sprintf( __( 'Parent %s Banking Option', 'casanova' ), 'Casino' ),
        'parent_item_colon' => sprintf( __( 'Parent %s Banking Option:', 'casanova' ), 'Casino' ),
        'edit_item'         => sprintf( __( 'Edit %s Banking Option', 'casanova' ), 'Casino' ),
        'update_item'       => sprintf( __( 'Update %s Banking Option', 'casanova' ), 'Casino' ),
        'add_new_item'      => sprintf( __( 'Add New %s Banking Option', 'casanova' ), 'Casino' ),
        'new_item_name'     => sprintf( __( 'New %s Banking Option Name', 'casanova' ), 'Casino' ),
        'menu_name'         => __( 'Banking Options', 'casanova' ),
    );
    
    $banking_option_args = array(
        'hierarchical' => true,
        'labels'       => $banking_option_labels,
        'show_ui'      => true,
        'public'       => false,
        'query_var'    => 'casino_banking_option',
    );
    register_taxonomy( 'casino_banking_option', ['casino'], $banking_option_args );
}
add_action('init',  'casanova_casino_banking_options_tax' );


/**
 * Register the default payment methods
 * 
 * @since 1.0.0
 */
function casanova_register_payment_methods() {
    $payments = [ 'Skrill', 'Paypal', 'Mastercard', 'Paysafe', 'Siru', 'Visa' ];
    foreach($payments as $payment){
        $term = wp_insert_term( $payment,'casino_payment' );
    }
}
add_action('casanova/actions/setup', 'casanova_register_payment_methods', 10);