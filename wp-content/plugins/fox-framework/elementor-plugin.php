<?php
namespace Fox_Elementor;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function widget_scripts() {
        
        // Register widget scripts
		wp_enqueue_script( 'fox-elementor', plugins_url( '/js/fox-elementor.js', __FILE__ ), array( 'jquery' ), false, true );
        
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.0
	 * @access private
	 */
	private function include_widgets_files() {
        
        require_once FOX_FRAMEWORK_PATH . 'inc/widget-base.php';
        
        require_once( __DIR__ . '/addons/heading/index.php' );
        require_once( __DIR__ . '/addons/button/index.php' );
        require_once( __DIR__ . '/addons/ad/index.php' );
        require_once( __DIR__ . '/addons/subscribe_form/index.php' );
        require_once( __DIR__ . '/addons/instagram/index.php' );
		require_once( __DIR__ . '/addons/post_group1/index.php' );
        require_once( __DIR__ . '/addons/post_group2/index.php' );
        require_once( __DIR__ . '/addons/post_standard/index.php' );
        require_once( __DIR__ . '/addons/post_big/index.php' );
        require_once( __DIR__ . '/addons/post_slider/index.php' );
        require_once( __DIR__ . '/addons/post_grid/index.php' );
        require_once( __DIR__ . '/addons/post_masonry/index.php' );
        require_once( __DIR__ . '/addons/post_newspaper/index.php' );
        require_once( __DIR__ . '/addons/post_list/index.php' );
        require_once( __DIR__ . '/addons/post_vertical/index.php' );
        require_once( __DIR__ . '/addons/authors/index.php' );
        
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function register_widgets() {
        
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Heading() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Ad() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Button() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Subscribe_Form() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Instagram() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Group1() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Group2() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Standard() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Big() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Slider() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Grid() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Masonry() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Newspaper() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_List() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Post_Vertical() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Authors() );
        
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
        
        // Register widgets
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
        
        
            
	}
    
}

// Instantiate Plugin Class
Plugin::instance();