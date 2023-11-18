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

        add_action('admin_menu', array($this, 'admin_menus'));
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

    /**
    * Create the admin back-end menus.
    *
    * @since 1.0.0
    */
    public function admin_menus(){

        //Main Dashboard Page
        add_menu_page( '',  'Casanova',  'manage_options',  'casanova-dashboard', array( $this, 'dashboard__content' ) , 'dashicons-clock', 100 );
        add_submenu_page( 'casanova-dashboard', 'Casanova', esc_html__('Dashboard', 'casanova'), 'manage_options', 'casanova-dashboard', array( $this, 'dashboard__content' ) );
        
        // Logs
        add_submenu_page( 'casanova-dashboard', esc_html__('Logs', 'casanova'), esc_html__('Logs', 'casanova'), 'manage_options', 'casanova-logs', array( $this, 'dashboard__content' ) );
        
    }

    /**
    * Populate the content for each admin menu page.
    *
    * @since 1.0.0
    */
    public function dashboard__content(){

        $current_page = isset($_GET['page']) ? $_GET['page'] : '';

        include CASANOVA_DIR. '/template-parts/views/partial-tabs.php';

        /* Page Content */
        include CASANOVA_DIR . '/template-parts/views/page-' . str_replace('casanova-', '', $current_page ) .'.php';

        /* Footer */
        include CASANOVA_DIR . '/template-parts/views/partial-footer.php';

    }
    
}