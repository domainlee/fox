<?php
/**
 * Plugin Name: Fox Framework
 * Plugin URI: https://themeforest.net/user/withemes/portfolio/
 * Description: Functional Framework used along with The Fox WP theme
 * Version: 1.2
 * Author: WiThemes
 * Author URI: https://themeforest.net/user/withemes
 * Copyright: (c) 2019 WiThemes
 * Text Domain: fox
 */

// Do not load directly
if ( !defined ( 'ABSPATH' ) ) die ( '-1' ) ;

// Define things
define ( 'FOX_FRAMEWORK_VERSION', '1.0' ) ;
define ( 'FOX_FRAMEWORK_FILE', __FILE__ );
define ( 'FOX_FRAMEWORK_PATH', plugin_dir_path( FOX_FRAMEWORK_FILE ) );
define ( 'FOX_FRAMEWORK_URL', plugins_url ( '/', FOX_FRAMEWORK_FILE ) );

require_once FOX_FRAMEWORK_PATH . 'elementor-framework.php';
require_once FOX_FRAMEWORK_PATH . 'theme-functions.php';

add_filter( 'customize_loaded_components', 'fox_remove_customize_panels' );
function fox_remove_customize_panels( $components ) {
    
    $i = array_search( 'nav_menus', $components );
    if ( false !== $i ) {
        unset( $components[ $i ] );
    }
    
    return $components;

}