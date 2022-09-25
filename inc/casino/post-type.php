<?php

/**
 * Function to Register "casino" custom post type.
 * 
 * @since 1.0.0
 */
function casanova_register_cpt_casino(){
    $labels = array(
        'name' => esc_html__('Casinos', 'casanova'),
        'singular_name' => esc_html__('Casino', 'casanova'),
        'menu_name' => esc_html__('Casinos', 'casanova'),
        'name_admin_bar' => esc_html__('Casino', 'casanova'),
        'add_new' => esc_html__('Add New', 'casanova'),
        'add_new_item' => esc_html__('Add New Casino', 'casanova'),
        'new_item' => esc_html__('New Casino', 'casanova'),
        'edit_item' => esc_html__('Edit Casino', 'casanova'),
        'view_item' => esc_html__('View Casino', 'casanova'),
        'all_items' => esc_html__('All Casinos', 'casanova'),
        'search_items' => esc_html__('Search Casinos', 'casanova'),
        'parent_item_colon' => esc_html__('Parent Casino:', 'casanova'),
        'not_found' => esc_html__('No casinos found.', 'casanova'),
        'not_found_in_trash' => esc_html__('No casinos found in Trash.', 'casanova'),
        'featured_image' => esc_html__('Casino Image', 'casanova'),
        'set_featured_image' => esc_html__('Set Casino Image', 'casanova'),
        'remove_featured_image' => esc_html__('Remove Casino Image', 'casanova'),
        'use_featured_image' => esc_html__('Use Casino Image', 'casanova'),
    );

    $cpt_casino_args = array(
        'labels' => $labels,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'casinos' ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail', 'revisions'),
        'menu_icon' => 'dashicons-games',
    );

    $cpt_casino_args = apply_filters('casanova/filters/cpt_casino_args', $cpt_casino_args);

    register_post_type('casino', $cpt_casino_args);
}
add_action('init', 'casanova_register_cpt_casino' );
