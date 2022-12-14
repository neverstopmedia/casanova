<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Casanova{
    
    /**
     * Class singleton property
     *
     * @since 1.0.0
     */
    private static $_instance = null;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct(){
        $this->define_constants();
    }

    /**
	 * Define all the required constants for the theme to run.
     *
     * @since 1.6.1
     */
    private function define_constants(){
        define( 'CASANOVA_DIR', get_template_directory() );
        define( 'CASANOVA_URI', get_template_directory_uri() );
        define( 'CASANOVA_VERSION', '1.1.1' );
        define( 'CASANOVA_API_VERSION', '1' );
        define( 'CASANOVA_API_ROUTE', 'casanova/v'.CASANOVA_API_VERSION );
    }

    /**
	 * Insures that only one instance of Casanova exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
     *
     * @since 1.0.0
     */
    public static function get_instance(){

        if ( ! isset( self::$_instance ) && ! ( self::$_instance instanceof Casanova ) ) {
            self::$_instance = new self();

			self::$_instance->includes();

            new Casanova_Casino_Endpoints;
            new Casanova_List_Endpoints;

            new Casanova_Casino_Actions;
            new Casanova_List_Actions;

		}

        return self::$_instance;
    }

    /**
     * Call all required files.
     *
     * @since 1.0.0
     */
    public function includes(){

        require CASANOVA_DIR . '/inc/misc/scripts.php';
        require CASANOVA_DIR . '/inc/misc/acf.php';
        require CASANOVA_DIR . '/inc/misc/functions.php';
        require CASANOVA_DIR . '/inc/class-helper.php';

        require CASANOVA_DIR . '/inc/list/post-type.php';
        require CASANOVA_DIR . '/inc/list/class-actions.php';
        require CASANOVA_DIR . '/inc/list/class-list.php';
        require CASANOVA_DIR . '/inc/list/class-query.php';
        require CASANOVA_DIR . '/inc/list/class-helper.php';

        require CASANOVA_DIR . '/inc/site/post-type.php';
        require CASANOVA_DIR . '/inc/site/class-actions.php';

        require CASANOVA_DIR . '/inc/casino/post-type.php';
        require CASANOVA_DIR . '/inc/casino/taxonomies.php';
        require CASANOVA_DIR . '/inc/casino/class-casino.php';
        require CASANOVA_DIR . '/inc/casino/class-query.php';
        require CASANOVA_DIR . '/inc/casino/class-helper.php';
        require CASANOVA_DIR . '/inc/casino/class-actions.php';

        require CASANOVA_DIR . '/inc/rest/casino.php';
        require CASANOVA_DIR . '/inc/rest/list.php';

    }

}

/**
 * Instantiate Casanova class.
 *
 * @since 1.0.0
 */
function casanova(){
    return Casanova::get_instance();
}
casanova();

require CASANOVA_DIR . '/plugin-update-checker/plugin-update-checker.php';
$update_checker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/neverstopmedia/casanova',
    __FILE__,
    get_template()
);
$update_checker->setAuthentication('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');
$update_checker->setBranch('main');

if(is_admin() && strpos($_SERVER['PHP_SELF'], 'themes.php') !== false){
    $update_checker->checkForUpdates();
}