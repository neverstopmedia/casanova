<?php

/**
 * Function to Register "list" custom post type.
 * 
 * @since 1.0.0
 */
function casanova_register_cpt_list(){
    $labels = array(
        'name' => esc_html__('Lists', 'casanova'),
        'singular_name' => esc_html__('List', 'casanova'),
        'menu_name' => esc_html__('Lists', 'casanova'),
        'name_admin_bar' => esc_html__('List', 'casanova'),
        'add_new' => esc_html__('Add New', 'casanova'),
        'add_new_item' => esc_html__('Add New List', 'casanova'),
        'new_item' => esc_html__('New List', 'casanova'),
        'edit_item' => esc_html__('Edit List', 'casanova'),
        'view_item' => esc_html__('View List', 'casanova'),
        'all_items' => esc_html__('All List', 'casanova'),
        'search_items' => esc_html__('Search List', 'casanova'),
        'parent_item_colon' => esc_html__('Parent List:', 'casanova'),
        'not_found' => esc_html__('No list found.', 'casanova'),
        'not_found_in_trash' => esc_html__('No list found in Trash.', 'casanova'),
        'featured_image' => esc_html__('List Image', 'casanova'),
        'set_featured_image' => esc_html__('Set List Image', 'casanova'),
        'remove_featured_image' => esc_html__('Remove List Image', 'casanova'),
        'use_featured_image' => esc_html__('Use List Image', 'casanova'),
    );

    $cpt_list_args = array(
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
        'menu_icon' => 'dashicons-editor-ul',
    );

    $cpt_list_args = apply_filters('casanova/filters/cpt_list_args', $cpt_list_args);

    register_post_type('list', $cpt_list_args);
}
add_action('init', 'casanova_register_cpt_list' );
