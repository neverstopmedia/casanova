<?php
/**
 * Enqueue all admin scripts and styles
 * 
 * @since 1.0.0
 */
function casanova_enqueue_admin_scripts(){
	wp_enqueue_style( 'casanova-admin', CASANOVA_URI . '/assets/css/admin.css', [], '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'casanova_enqueue_admin_scripts' );