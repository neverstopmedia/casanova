<?php

/**
 * Function to Register "site" custom post type.
 * 
 * @since 1.0.0
 */
function casanova_register_cpt_site(){
    $labels = array(
        'name' => esc_html__('Sites', 'casanova'),
        'singular_name' => esc_html__('Site', 'casanova'),
        'menu_name' => esc_html__('Sites', 'casanova'),
        'name_admin_bar' => esc_html__('Site', 'casanova'),
        'add_new' => esc_html__('Add New', 'casanova'),
        'add_new_item' => esc_html__('Add New Site', 'casanova'),
        'new_item' => esc_html__('New Site', 'casanova'),
        'edit_item' => esc_html__('Edit Site', 'casanova'),
        'view_item' => esc_html__('View Site', 'casanova'),
        'all_items' => esc_html__('All Site', 'casanova'),
        'search_items' => esc_html__('Search Site', 'casanova'),
        'parent_item_colon' => esc_html__('Parent Site:', 'casanova'),
        'not_found' => esc_html__('No site found.', 'casanova'),
        'not_found_in_trash' => esc_html__('No site found in Trash.', 'casanova'),
        'featured_image' => esc_html__('Site Image', 'casanova'),
        'set_featured_image' => esc_html__('Set Site Image', 'casanova'),
        'remove_featured_image' => esc_html__('Remove Site Image', 'casanova'),
        'use_featured_image' => esc_html__('Use Site Image', 'casanova'),
    );

    $cpt_site_args = array(
        'labels' => $labels,
        'description' => null,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'show_in_rest' => true,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-admin-site',
    );

    $cpt_site_args = apply_filters('casanova/filters/cpt_site_args', $cpt_site_args);

    register_post_type('site', $cpt_site_args);
}
add_action('init', 'casanova_register_cpt_site' );
