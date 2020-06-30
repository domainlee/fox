<?php

/**
 * @since 4.0
 */
if ( ! defined( 'FOX_VERSION' ) ) {
    define( 'FOX_VERSION', '4.4.3.1' );
}

// ADMIN
require_once get_parent_theme_file_path( '/inc/admin/admin.php' );
require_once get_parent_theme_file_path( '/inc/admin/import.php' ); // import demo data

// FUNCTIONS
require_once get_parent_theme_file_path( '/inc/support.php' ); // array of things we support to validate
require_once get_parent_theme_file_path( '/inc/font.php' ); // font problems
require_once get_parent_theme_file_path( '/inc/header.php' );
require_once get_parent_theme_file_path( '/inc/footer.php' );
require_once get_parent_theme_file_path( '/inc/sidebar.php' );
require_once get_parent_theme_file_path( '/inc/mobile.php' ); // mobile
require_once get_parent_theme_file_path( '/inc/nav.php' );
require_once get_parent_theme_file_path( '/inc/brands.php' );
require_once get_parent_theme_file_path( '/inc/social.php' );
require_once get_parent_theme_file_path( '/inc/query.php' ); // query functions
require_once get_parent_theme_file_path( '/inc/blog.php' ); // blog functions
require_once get_parent_theme_file_path( '/inc/blog-templates.php' ); // blog templates
require_once get_parent_theme_file_path( '/inc/image.php' ); // everything about image
require_once get_parent_theme_file_path( '/inc/params.php' );
require_once get_parent_theme_file_path( '/inc/component.php' ); // components: thumbnails, date, author etc
require_once get_parent_theme_file_path( '/inc/single.php' ); // single problems
require_once get_parent_theme_file_path( '/inc/hero.php' ); // post hero
require_once get_parent_theme_file_path( '/inc/review.php' ); // review system

// FUNCTIONS SINCE FOX 4.3
require_once get_parent_theme_file_path( '/inc/header43.php' ); // components since Fox 4.3
require_once get_parent_theme_file_path( '/inc/components43.php' ); // components since Fox 4.3
require_once get_parent_theme_file_path( '/inc/blog-templates43.php' ); // blog templates since Fox 4.3
require_once get_parent_theme_file_path( '/inc/shortcodes43.php' ); // shortcodes since Fox 4.3
require_once get_parent_theme_file_path( '/inc/single43.php' ); // functions about single post since Fox 4.3

// FUNCTIONS SINCE FOX 4.4
require_once get_parent_theme_file_path( '/inc/blog-templates44.php' ); // blog templates since Fox 4.4

// PIECES
require_once get_parent_theme_file_path( '/inc/banner.php' ); // ad
require_once get_parent_theme_file_path( '/inc/button.php' ); // button
require_once get_parent_theme_file_path( '/inc/subscribe_form.php' ); // subscribe form, since 4.2
require_once get_parent_theme_file_path( '/inc/heading.php' ); // heading
require_once get_parent_theme_file_path( '/inc/section-heading.php' ); // builder section heading
require_once get_parent_theme_file_path( '/inc/gallery.php' ); // gallery
require_once get_parent_theme_file_path( '/inc/instagram.php' ); // instagram
require_once get_parent_theme_file_path( '/inc/user.php' ); // user templates
require_once get_parent_theme_file_path( '/inc/styling.php' ); // all about site styling
require_once get_parent_theme_file_path( '/inc/autoloadpost.php' ); // autoload next post single post
require_once get_parent_theme_file_path( '/inc/progress.php' ); // reading progress bar

// MISC
require_once get_parent_theme_file_path( '/inc/helpers.php' ); // small helper functions
require_once get_parent_theme_file_path( '/inc/auto-thumbnail-from-videos.php' ); // download video thumbnails automatically
require_once get_parent_theme_file_path( '/inc/featured-post.php' ); // featured post
require_once get_parent_theme_file_path( '/inc/lazyload.php' ); // lazy load
require_once get_parent_theme_file_path( '/inc/misc.php' ); // various functions
require_once get_parent_theme_file_path( '/inc/backcompat.php' ); // backward compatibility

// CUSTOMIZER
require_once get_parent_theme_file_path( '/inc/customizer/fonts.php' );
require_once get_parent_theme_file_path( '/inc/customizer/customizer.php' );
require_once get_parent_theme_file_path( '/inc/customizer/register.php' );

// WIDGETS
require_once get_parent_theme_file_path ( '/widgets/about/register.php' );
require_once get_parent_theme_file_path ( '/widgets/button/register.php' );
require_once get_parent_theme_file_path ( '/widgets/latest-posts/register.php' );
require_once get_parent_theme_file_path ( '/widgets/social/register.php' );
require_once get_parent_theme_file_path ( '/widgets/media/register.php' );
require_once get_parent_theme_file_path ( '/widgets/facebook/register.php' );
require_once get_parent_theme_file_path ( '/widgets/instagram/register.php' );
require_once get_parent_theme_file_path ( '/widgets/pinterest/register.php' );
require_once get_parent_theme_file_path ( '/widgets/ad/register.php' );
require_once get_parent_theme_file_path ( '/widgets/best-rated/register.php' );
require_once get_parent_theme_file_path ( '/widgets/authorlist/register.php' );
require_once get_parent_theme_file_path ( '/widgets/imagebox/register.php' );
require_once get_parent_theme_file_path ( '/widgets/coronavirus/register.php' );
require_once get_parent_theme_file_path( '/inc/post-view.php' ); // post view widget html, just for backward compatibility

// HEADER BUILDER WIDGETS
require_once get_parent_theme_file_path ( '/widgets/header-logo/register.php' );
require_once get_parent_theme_file_path ( '/widgets/header-nav/register.php' );
require_once get_parent_theme_file_path ( '/widgets/header-search/register.php' );

// FOOTER WIDGETS
require_once get_parent_theme_file_path ( '/widgets/footer-logo/register.php' );
require_once get_parent_theme_file_path ( '/widgets/copyright/register.php' );
require_once get_parent_theme_file_path ( '/widgets/footer-nav/register.php' );

// PLUGIN COMPATIBILITY
require_once get_parent_theme_file_path( '/inc/plugin.woocommerce.php' );
require_once get_parent_theme_file_path( '/inc/plugin.polylang.php' );

// LEGACY
require_once get_parent_theme_file_path( '/inc/legacy/shortcodes.php' );
require_once get_parent_theme_file_path( '/inc/legacy/wi.php' );

/**
 * Content Width
 * @since 1.0
 */
global $content_width;
if ( ! isset( $content_width ) ) {
	$content_width = absint( get_theme_mod( 'wi_content_width' ) ) ? absint( get_theme_mod( 'wi_content_width' ) ) : 1080;
}

/**
 * After Setup Theme
 * @since 4.0
 */
add_action( 'after_setup_theme', 'fox_setup' );
function fox_setup() {
    
    // translation
	load_theme_textdomain( 'wi', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

    // title tag
    add_theme_support( 'title-tag' );

    // post thumbnail
    add_theme_support( 'post-thumbnails' );
    
    // add_image_size( 'tiny', 60, 9999, false );  // for lazyload
	add_image_size( 'thumbnail-medium', 480, 384, true );  // medium landscape
    add_image_size( 'thumbnail-square', 480, 480, true );  // medium square
    add_image_size( 'thumbnail-portrait', 480, 600, true );  // medium portrait
    add_image_size( 'thumbnail-large', 720, 480, true );  // large landscape
    add_image_size( 'thumbnail-medium-nocrop', 480, 9999, false );  // medium thumbnail no crop
    
    // deprecated since 4.0
    // add_image_size( 'thumbnail-big', 1020, 510, true );  // big thumbnail (ratio 2:1)
    // add_image_size( 'thumbnail-vertical', 9999, 500, false );  // vertical image used for gallery
    
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => 'Primary Menu',
        'mobile' => 'Off-Canvas Menu',
        'footer' => 'Footer Menu',
        'search-menu' => 'Modal Search Suggestion',
	) );
    
	// html5
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	// post formats
	add_theme_support( 'post-formats', array(
		'video', 'gallery', 'audio', 'link',
	) );
    
    // since 2.4
    add_theme_support( 'woocommerce' );
    
    // since 4.0
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    // align wide
    // since 4.3
    add_theme_support( 'align-wide' );

}

/**
 * Register Widgets
 * @since 4.0
 */
add_action( 'widgets_init', 'fox_widgets_init' );
function fox_widgets_init() {
    
    $sidebars = fox_sidebar_support();
    foreach ( $sidebars as $id => $sb ) {
        
        register_sidebar( array(
            'name'          => $sb[ 'name' ],
            'id'            => $id,
            'description'   => $sb[ 'desc' ],
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>',
        ) );
        
    }

}

/**
 * Enqueue CSS & JS
 * @since 4.0
 */
add_action( 'wp_enqueue_scripts', 'fox_enqueue_scripts' );
function fox_enqueue_scripts() {
    
    // loads google fonts
    wp_enqueue_style( 'wi-fonts', fox_fonts(), array(), null );
    
    if ( defined( 'FOX_DEBUG' ) && FOX_DEBUG ) $compress = false;
    else  $compress = ( 'true' == get_theme_mod( 'wi_compress_files', 'true' ) );
    
    // Load our main stylesheet.
    if ( ! $compress || is_child_theme() ) {
        wp_enqueue_style( 'style', get_stylesheet_uri(), [ 'wp-mediaelement' ], wp_get_theme()->get( 'Version' ) );
    } else {
        wp_enqueue_style( 'style', get_theme_file_uri( 'style.min.css' ), [ 'wp-mediaelement' ], wp_get_theme()->get( 'Version' ) );
    }
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    // facebook
    wp_register_script( 'wi-facebook', 'https://connect.facebook.net/en_US/all.js#xfbml=1', false, '1.0', true );
    
    // main
    if ( ! $compress ) {
        
        // deprecated since 4.0
        //wp_enqueue_script( 'colorbox', get_theme_file_uri( '/js/jquery.colorbox-min.js' ), array( 'jquery' ), '1.6.0' , true );
        
        wp_enqueue_script( 'imagesloaded', get_theme_file_uri( '/js/imagesloaded.pkgd.min.js' ), array( 'jquery' ), '3.1.8' , true );
        wp_enqueue_script( 'wi-magnific-popup', get_theme_file_uri( '/js/jquery.magnific-popup.js' ), array( 'jquery' ), '1.1.0' , true ); // since 4.0
        wp_enqueue_script( 'tooltipster', get_theme_file_uri( '/js/tooltipster.bundle.min.js' ), array( 'jquery' ), '4.2.6' , true ); // since 4.0
        wp_enqueue_script( 'easing', get_theme_file_uri( '/js/jquery.easing.1.3.js' ), array( 'jquery' ), '1.3' , true );
        wp_enqueue_script( 'fitvids', get_theme_file_uri( '/js/jquery.fitvids.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'flexslider', get_theme_file_uri( '/js/jquery.flexslider-min.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'inview', get_theme_file_uri( '/js/jquery.inview.min.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'fox-masonry', get_theme_file_uri( '/js/masonry.pkgd.min.js' ), array( 'jquery' ), '4.2.2' , true );
        wp_enqueue_script( 'matchMedia', get_theme_file_uri( '/js/matchMedia.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'wi-slick', get_theme_file_uri( '/js/slick.min.js' ), array( 'jquery' ), '1.8.0' , true );
        wp_enqueue_script( 'theia-sticky-sidebar', get_theme_file_uri( '/js/theia-sticky-sidebar.js' ), array( 'jquery' ), '1.3.1' , true );
        
        // since 4.0
        wp_enqueue_script( 'superfish', get_theme_file_uri( '/js/superfish.js' ), array( 'jquery' ), '1.7.9' , true );
        // wp_enqueue_script( 'wi-lazyload', get_theme_file_uri( '/js/jquery.lazy.min.js' ), array( 'jquery' ), '1.7.10' , true );
        
        wp_enqueue_script( 'wi-main', get_theme_file_uri( '/js/main.js' ), array( 'jquery', 'wp-mediaelement' ), FOX_VERSION , true );
        
    } else {
        
        wp_enqueue_script( 'wi-main', get_theme_file_uri( '/js/theme.min.js' ), array( 'jquery', 'wp-mediaelement' ), FOX_VERSION , true );
        
    }
    
    // Create a filter to add global JS data to <head />
    // @since Fox 2.2
    $jsdata = array(
        'l10n' => array( 
            'prev' => fox_word( 'previous' ), 
            'next' => fox_word( 'next' ),
        ),
        'enable_sticky_sidebar'=> ( 'true' == get_theme_mod( 'wi_sticky_sidebar', 'false' ) ),
        
        // @since 2.8
        'enable_sticky_header' => ( 'false' != get_theme_mod( 'wi_header_sticky', 'true' ) ),
        
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce( 'nav_mega_nonce' ),
        
        'resturl_v2' => get_rest_url( null, '/wp/v2/', 'rest' ),
        'resturl_v2_posts' => get_rest_url( null, '/wp/v2/posts/', 'rest' ),
        
        'tablet_breakpoint' => 840,
    );
    
    if ( fox_autoload() && ! is_customize_preview() ) {
        
        wp_enqueue_script( 'scrollspy', get_theme_file_uri( '/js/scrollspy.js' ), array('jquery'), null, true );
        wp_enqueue_script( 'autoloadpost', get_theme_file_uri( '/js/autoloadpost.js' ), array('jquery', 'scrollspy'), null, true );
        wp_enqueue_script( 'history', get_theme_file_uri( '/js/jquery.history.js' ), array('jquery'), null, true );
        $jsdata[ 'enable_autoload' ] = true;
        
    }
    
    $jsdata = apply_filters( 'jsdata', $jsdata );
	wp_localize_script( 'wi-main', 'WITHEMES', $jsdata );

}

/**
 * remove font awesome lib from elementor completely
 * @since 4.0
 */
function fox_remove_fontawesome_elementor() {
    wp_deregister_style( 'font-awesome'); 
    wp_dequeue_style( 'font-awesome' );
}
add_action( 'wp_enqueue_scripts', 'fox_remove_fontawesome_elementor', 50 );
add_action( 'elementor/frontend/after_enqueue_styles', 'fox_remove_fontawesome_elementor' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since 2.8
 */
function wi_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'wi_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 * @since 2.8
 */
function wi_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'wi_pingback_header' );

/**
 * Add preconnect for Google Fonts.
 *
 * @since 2.8
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function wi_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'wi-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'wi_resource_hints', 10, 2 );
if ( ! function_exists( 'wi_add_head_code' ) ) :
/**
 * Head Code
 * You can enter custom code into <head /> tag
 * Just a legacy
 * @since 1.0
 */
add_action( 'wp_head' , 'wi_add_head_code' );
function wi_add_head_code() {
	echo trim( get_theme_mod( 'wi_header_code' ) );
}
endif;

add_filter( 'fox_sidebar_state', function( $state ) {
    if ( is_singular( 'acadp_listings' ) ) return 'no-sidebar'; return $state;
}, 1000 );

/**
 * add necessary classes to body
 * @since 4.3
 */
add_action( 'body_class', 'fox_body_class' );
function fox_body_class( $class ) {
    
    if ( 'true' == get_theme_mod( 'wi_sticky_sidebar', 'false' ) ) {
        $class[] = 'body-sticky-sidebar';
    }
        
    return $class;
    
}

add_filter( 'get_the_excerpt', 'fox43_trim_excerpt_whitespace', 1 );
/**
 * make sure no stupid errors when excerpt is empty
 *
 * @since 4.3
 */
function fox43_trim_excerpt_whitespace( $excerpt ) {
    return trim( $excerpt );
}

add_filter( 'excerpt_length', function($length) {
    return 100;
} );