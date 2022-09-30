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

/**
 * Enqueue Admin script.
 * 
 * @since 1.0.0
 */
function casanova_conditional_admin_queue( $hook ) {
    global $post; 

    if ( $hook == 'post.php' ) {

        if ( $post->post_type == 'list' || $post->post_type == 'casino' )
		wp_enqueue_script( 'casanova', CASANOVA_URI . '/assets/js/casanova.js', ['jquery', 'acf-input'] , '', true );
        
    }

}
add_action( 'admin_enqueue_scripts', 'casanova_conditional_admin_queue' );