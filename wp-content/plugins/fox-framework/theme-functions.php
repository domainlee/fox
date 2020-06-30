<?php

// Shortcodes
require_once FOX_FRAMEWORK_PATH . 'inc/shortcodes.php';

if ( !class_exists ( 'Fox_Framework ' )  ) :
/*
 * Main class
 * @since 1.0
 */
class Fox_Framework 
{
    /**
	 * construct
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of class
	 *
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * Instantiate or return the one class instance
	 *
	 * @since 1.0
	 * @return $instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
    
    /**
     * Initiate the class
     * contains action & filters
     *
     * @since 1.0
     */
    public function init() {
        
        // Register lately so that everything has been registered
        add_action( 'init', array( $this, 'plugin_init' ), 100 );
        
    }
    
    /**
     * Plugin Init
     *
     * @since 1.0
     */
    function plugin_init() {
        
        load_plugin_textdomain( 'wi', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
        add_filter( 'upload_mimes', array( $this, 'add_custom_upload_mimes' ) );
        
    }
    
    function add_custom_upload_mimes($existing_mimes) {
        
        $existing_mimes['otf'] = 'application/x-font-otf';
        $existing_mimes['woff'] = 'application/x-font-woff';
        $existing_mimes['woff2'] = 'application/x-font-woff2';
        $existing_mimes['ttf'] = 'application/x-font-ttf';
        $existing_mimes['svg'] = 'image/svg+xml';
        $existing_mimes['eot'] = 'application/vnd.ms-fontobject';
        return $existing_mimes;
        
    }
    
}	// class

Fox_Framework::instance()->init();

endif;