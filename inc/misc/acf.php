<?php
if(function_exists('acf_add_options_page')){
    acf_add_options_page(array(
        'page_title' => __('Casanova Settings', 'casanova'),
        'menu_title' => __('Casanova Settings', 'casanova'),
        'menu_slug' => 'casanova-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

function casanova_acf_save_path( $path ) {
    $path = CASANOVA_DIR . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'casanova_acf_save_path');

function casanova_acf_load_path( $paths ){
    // remove original path (optional)
    unset($paths[0]);
    $paths[] = CASANOVA_DIR . '/acf-json';
    // return
    return $paths;
}
add_filter('acf/settings/load_json', 'casanova_acf_load_path');

function casanova_disable_acf_fields( $field ) {
    $field['readonly'] = true;
    return $field;
}
add_filter('acf/load_field/name=last_sync', 'casanova_disable_acf_fields');
add_filter('acf/load_field/name=affiliate_site_id', 'casanova_disable_acf_fields');
add_filter('acf/load_field/name=affiliate_site_nickname', 'casanova_disable_acf_fields');
add_filter('acf/load_field/name=affiliate_site', 'casanova_disable_acf_fields');
add_filter('acf/load_field/name=alias', 'casanova_disable_acf_fields');

/**
 * Generates an alias from the URL and sets it to the site
 *
 * @since 1.0.0
 */
function casanova_generate_site_alias( $value, $post_id, $field ){
    
    if ($value)
    return $value;

    if( $url = get_field('url', $post_id) ){
        $parse = parse_url($url);
        $value = $parse['host'];
    }

    return $value;

}
add_filter('acf/load_value/name=alias', 'casanova_generate_site_alias', 10, 3);
