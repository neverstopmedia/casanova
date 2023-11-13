<?php

class Casanova_Actions{

    /**
	* Class constructor.
	*
	* @since 2.0.0
	*/
	public function __construct(){
        add_filter( 'admin_body_class', [$this, 'add_user_id_class'] );
        add_action('admin_footer-casino.php', [$this, 'disable_browser_autosave']);
        add_action('admin_footer-casino-new.php', [$this, 'disable_browser_autosave']);
        add_action('admin_footer-list.php', [$this, 'disable_browser_autosave']);
        add_action('admin_footer-list-new.php', [$this, 'disable_browser_autosave']);
	}

	public function add_user_id_class( $classes ){

        $classes .= ' casanova-user-'.get_current_user_id();

        return $classes;
    }

    public function disable_browser_autosave() {
        // Check if we're on the post edit page for the specified post types
        if (is_admin()) {
            // Disable browser autosave
            echo '<input type="hidden" name="autosave" id="autosave" value="off">';
        }
    }
   
}