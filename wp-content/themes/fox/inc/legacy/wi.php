<?php
/**
 * Helper functions
 */

/* -------------------------------------------------------------------- */
/* SUBWORD
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_subword') ) {
function wi_subword($str = '',$int = 0, $length = NULL){
    
	if (!$str) return;
	$words = explode(" ",$str); if (!is_array($words)) return;
	$return = array_slice($words,$int,$length); if (!is_array($return)) return;
	return implode(" ",$return);
    
}
}

if ( ! function_exists( 'wi_entry_share' ) ) :
/**
 * Entry Share
 *
 * @since 2.8
 */
function wi_entry_share() {
    
    $title = trim( get_the_title() );
    $title = strip_tags( $title );
    $url = get_permalink();
    $image = '';
    $via = trim( get_theme_mod( 'wi_twitter_username' ) );
    if ( has_post_thumbnail() ) {
        $image = wp_get_attachment_thumb_url();
    }

?>

<div class="entry-share">
    
    <span class="share-label"><?php echo esc_html__( 'Share', 'wi' ); ?></span>
    
    <div class="share-list">

        <ul>
            
            <li class="li-facebook">

                <?php
                $href = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
                if ( $image ) {
                    $href .= '&amp;p[images][0]=' . urlencode( $image );
                }
                ?>

                <a data-href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_html__( 'Facebook','wi' ); ?>" class="share share-facebook">
                    <span><?php echo esc_html__( 'Share', 'wi' ); ?></span>
                    <i class="fab fa-facebook"></i>
                </a>

            </li>

            <li class="li-twitter">

                <?php
                $href = 'https://twitter.com/intent/tweet?url=' . urlencode($url) .'&amp;text=' . $title;
                if ( $via ) {
                    $href .= '&amp;via=' . urlencode( $via );
                }
                ?>

                <a data-href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_html__( 'Twitter','wi' ); ?>" class="share share-twitter">
                    <span><?php echo esc_html__( 'Tweet', 'wi' ); ?></span>
                    <i class="fab fa-twitter"></i>
                </a>

            </li>

            <li class="li-pinterest">

                <?php
                $href = 'http://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;description=' . $title;
                if ( $image ) {
                    $href .= '&amp;media=' . urlencode($image);
                }
                ?>

                <a data-href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_html__( 'Pinterest','wi' ); ?>" class="share share-pinterest">
                    <span><?php echo esc_html__( 'Pin', 'wi' ); ?></span>
                    <i class="fab fa-pinterest"></i>
                </a>

            </li>

            <li class="li-email">

                <?php
                $href = 'mailto:?subject=' . urlencode($title) . '&amp;body=' . rawurlencode($url);
                ?>

                <a href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_html__( 'Email','wi' ); ?>" class="email-share">

                    <span><?php echo esc_html__( 'Email', 'wi' ); ?></span>
                    <i class="fa fa-envelope"></i>

                </a>

            </li>

        </ul>
        
    </div>

</div><!-- .entry-share -->

<?php

}
endif;

/* -------------------------------------------------------------------- */
/* SOCIAL ARRAY
/* -------------------------------------------------------------------- */
if (!function_exists('wi_social_array')){
function wi_social_array() {
    return apply_filters( 'wi_social_array', array(
		'facebook-square'      =>	__('Facebook','wi'),
		'twitter'              =>	__('Twitter','wi'),
        'instagram'                   =>	__('Instagram','wi'),
        'youtube'              =>	__('YouTube','wi'),
        'pinterest'            =>	__('Pinterest','wi'),
		'linkedin'             =>	__('LinkedIn','wi'),
        'reddit'               =>	__('Reddit','wi'),
        'snapchat'               =>	__('Snapchat','wi'),
        'vk'                          =>	__('VKontakte','wi'),
		'tumblr'               =>	__('Tumblr','wi'),
        'whatsapp'              => __('WhatsApp','wi'),
        'soundcloud'                  =>	__('SoundCloud','wi'),
        'spotify'                     =>	__('Spotify','wi'),
        'lastfm'               =>	__('Last.fm','wi'),
		'skype'                       =>	__('Skype','wi'),
		'digg'                        =>	__('Digg','wi'),
		'stumbleupon'          =>	__('StumbleUpon','wi'),
        'medium'                      =>	__('Medium','wi'),
		'vimeo-square'                =>	__('Vimeo','wi'),
        'telegram'                      => __('Telegram','wi'),
		'github'               =>	__('GitHub','wi'),
		'stack-overflow'              =>	__('StackOverFlow','wi'),
        'stack-exchange'              =>	__('Stack Exchange','wi'),
        'bitbucket'            =>	__('Bitbucket','wi'),
		'xing'                 =>	__('Xing','wi'),
		'foursquare'                  =>	__('Foursquare','wi'),
		'paypal'                      =>	__('Paypal','wi'),
		'yelp'                        =>	__('Yelp','wi'),
        'slideshare'                  =>	__('Slideshare','wi'),
		'dribbble'                    =>	__('Dribbble','wi'),
		'steam'                =>	__('Steam','wi'),
		'behance'              =>	__('Behance','wi'),
		'weibo'                       =>	__('Weibo','wi'),
		'trello'                      =>	__('Trello','wi'),
		'yahoo'                       =>	__('Yahoo!','wi'),
		'flickr'                      =>	__('Flickr','wi'),
		'deviantart'                  =>	__('DeviantArt','wi'),
		'home'                        =>	__('Homepage','wi'),
		'envelope'             =>	__('Email','wi'),
        'delicious'                   =>	__('Delicious','wi'),
        '500px'             =>	__('500px','wi'),
        'google-plus'          =>	__('Google+','wi'),
		'rss'                 =>	__('Feed','wi'),
	) );
}
}

if (!function_exists('wi_social_list')){
    function wi_social_list($search = false){
        $social_array = wi_social_array();
        foreach ( $social_array as $k => $v ){
            if ( get_theme_mod('fox_social_'.$k) ){
if ( 'facebook-square' == $k ) {
    $i = 'facebook';
} else {
    $i = $k;
}
?>
                <li class="li-<?php echo str_replace('','',$k);?>"><a href="<?php echo esc_url(get_theme_mod('fox_social_'.$k));?>" target="_blank" rel="alternate" title="<?php echo esc_attr($v);?>"><i class="fa fa-<?php echo esc_attr($i);?>"></i> <span><?php echo esc_html($v);?></span></a></li>
            <?php }
        }?>
        <?php if ($search){ ?>
        <li class="li-search"><a><i class="fa fa-search"></i> <span>Search</span></a></li>
        <?php }
    }
}

if ( ! function_exists('wi_layout') ) :
/**
 * return current archive page layout
 * @since 2.0
 */
function wi_layout(){
    
    if ( is_category() ) {
        
        $this_cat = get_category( get_query_var( 'cat' ), false );
        $term_meta = get_theme_mod( "taxonomy_$this_cat->term_id" );
        $layout = isset( $term_meta['layout'] ) ? $term_meta['layout'] : '';
        if ( ! $layout ) {
            $layout = get_theme_mod( 'wi_category_layout' );
        }
        
    } elseif ( is_search() ) {
        
        $layout = get_theme_mod( 'wi_search_layout' );
        
    } elseif ( is_day() || is_month() || is_year() ) {
        
        $layout = get_theme_mod('fox_archive_layout');
        
    } elseif ( is_tag() ) {
        
        $tag_id = get_queried_object()->term_id;
        $term_meta = get_theme_mod( "taxonomy_$tag_id" );
        $layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';
        if (!$layout) {
            $layout = get_theme_mod('fox_tag_layout');
        }
        
    } elseif ( is_author() ) {
        
        $layout = get_theme_mod( 'wi_author_layout' );
        
    } elseif ( is_404() ) {
        
        $layout = 'standard';
        
    } elseif ( is_single() ) {
        
        $layout = 'standard';
        
    } elseif ( is_page() && is_page_template( 'page-featured.php' ) ) {
        
        $layout = get_theme_mod( 'wi_all-featured_layout' ) ? get_theme_mod( 'wi_all-featured_layout' ) : '';
        
    // default layout    
    } else {
        
        $layout = 'standard';
        
    }
    
    if ( ! $layout ) $layout = '';
    
    // final validate
    if ( ! array_key_exists( $layout , fox_archive_layout_support() ) ) $layout = 'standard';

    // final result
    return apply_filters( 'wi_layout' , $layout );
}
endif;

/* -------------------------------------------------------------------- */
/* BLOCK ARRAY
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_block_array' ) ) {
function wi_block_array() {
    $block_arr = array(
        'slider'                    =>  'Slider',
        'big-post'                  =>  'Big post',
        'grid-2'                    =>  'Grid 2 columns',
        'grid-3'                    =>  'Grid 3 columns',
        'grid-4'                    =>  'Grid 4 columns',
        
        'list'                      =>  'List style',
        'vertical'                  =>  'Post Vertical',
        'group-1'                   =>  'Post Group 1',
        'group-2'                   =>  'Post Group 2',
    );
    
    return $block_arr;
}
}

if ( ! function_exists( 'wi_layout_array' ) ) :
/**
 * list of all possible layouts
 * @since 2.0
 */
function wi_layout_array() {
    
    $layout_arr = [
        'standard'              =>  'Standard',
        'grid-2'                =>  'Grid 2 columns',
        'grid-3'                =>  'Grid 3 columns',
        'grid-4'                =>  'Grid 4 columns',
        'masonry-2'             =>  'Pinterest-like 2 columns',
        'masonry-3'             =>  'Pinterest-like 3 columns',
        'masonry-4'             =>  'Pinterest-like 4 columns',
        'newspaper'             =>  'Newspaper',
        'list'                  =>  'List',
        
        'vertical' => 'Vertical post',
        'big' => 'Big Post',
        'slider' => 'Slider',
        'group-1' => 'Post Group 1',
        'group-2' => 'Post Group 2',
    ];
    
    // since 4.0
    $layout_arr = apply_filters( 'fox_layout_array', $layout_arr );
    
    return $layout_arr;
    
}

endif;

/* -------------------------------------------------------------------- */
/* SIDEBAR ARRAY
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_sidebar_array' ) ) {
function wi_sidebar_array() {
    return array(
        'sidebar-right'     =>  'Sidebar Right',
        'sidebar-left'      =>  'Sidebar Left',
        'no-sidebar'        =>  'No Sidebar',
    );
}
}

if (!function_exists('wi_body_class')){
function wi_body_class($classes){
    
    // one-column template fallback
    if ( is_page_template( 'page-one-column.php' ) ) {
    
        $classes[] = 'disable-2-columns';
        
    } elseif ( is_single() || is_page() ) {
        
        $column = wi_content_column();
        $column_class = ( $column == '1' ) ? 'disable-2-columns' : 'enable-2-columns';
        
        // for cool post
        if ( wi_is_cool_post() ) {
            $column_class = 'disable-2-columns';
        }
        
        $classes[] = $column_class;
    
    }
    
    // Sidebar
    $sidebar_state = wi_sidebar_state();
    if ($sidebar_state=='sidebar-right') {
        $classes[] = 'has-sidebar sidebar-right';
    } elseif ($sidebar_state=='sidebar-left') {
        $classes[] = 'has-sidebar sidebar-left';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // site border
    if ( 'true' === get_theme_mod( 'wi_site_border', 'false' ) ) {
        
        $classes[] = 'site-has-border';
        
    } else {
        
        $classes[] = 'site-no-border';
        
    }
    
    // hand-drawn lines
    if (get_theme_mod('fox_enable_hand_lines')) {
        $classes[] = 'enable-hand-lines';
    } else {
        $classes[] = 'disable-hand-lines';
    }
    
    // menu style
    if (get_theme_mod('fox_submenu_style') == 'dark') {
        $classes[] = 'submenu-dark';
    } else {
        $classes[] = 'submenu-light';
    }
    
    // dropcap style
    $dropcap_style = get_theme_mod( 'wi_dropcap_style' );
    if ( 'color' != $dropcap_style && 'dark' != $dropcap_style ) $dropcap_style = 'default';
    $classes[] = 'dropcap-style-' . $dropcap_style;
    
    // blockquote style
    $style = get_theme_mod( 'wi_blockquote_style' );
    if ( 'minimal' != $style && 'left-line' != $style ) $style = 'default';
    $classes[] = 'blockquote-style-' . $dropcap_style;
    
    // stretch level
    // @since 3.0
    $stretch_option = get_theme_mod( 'wi_cool_post_stretch', 'bit' );
    if ( 'full' !== $stretch_option ) $stretch_option = 'bit';
    $classes[] = 'coolpost-image-stretch-' . $stretch_option;
    
    /**
     * Header Sticky Style
     */
    if ( $header_sticky_style = get_theme_mod( 'wi_sticky_header_element_style', 'border' ) ) {
        $classes[] = 'style--header-sticky-element-' . $header_sticky_style;
    }
    
	return $classes;
}
}

/* -------------------------------------------------------------------- */
/* POST CLASS BECAUSE THIS APPLIES FOR INDIVIDUAL POSTS
/* -------------------------------------------------------------------- */
function wi_post_class( $classes ) {
    
    $dropcap = get_post_meta( get_the_ID(), '_wi_dropcap', true );
    if ( ! $dropcap ) $dropcap = ! get_theme_mod( 'wi_disable_blog_dropcap' );
    if ( 'true' == $dropcap ) { $dropcap = true; }
    elseif ( 'false' == $dropcap ) { $dropcap = false; }
    
    if ( $dropcap ) $classes[] = 'enable-dropcap';
    else $classes[] = 'disable-dropcap';
    
    return $classes;
    
}

/* -------------------------------------------------------------------- */
/* SETUP
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'wi_setup' ) ) :
function wi_setup() {
    
    // translation
	load_theme_textdomain( 'wi', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

    // title tag
    add_theme_support( 'title-tag' );

    // post thumbnail
    add_theme_support( 'post-thumbnails' );
    
	add_image_size( 'thumbnail-medium', 480, 384, true );  // medium landscape
    add_image_size( 'thumbnail-square', 480, 480, true );  // medium square
    add_image_size( 'thumbnail-portrait', 480, 600, true );  // medium portrait
    add_image_size( 'thumbnail-medium-nocrop', 480, 9999, false );  // medium thumbnail no crop
    
    // deprecated since 4.0
    // add_image_size( 'thumbnail-big', 1020, 510, true );  // big thumbnail (ratio 2:1)
    // add_image_size( 'thumbnail-vertical', 9999, 500, false );  // vertical image used for gallery
    
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wi' ),
        'footer' => __( 'Footer Menu', 'wi' ),
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

}
endif; // wi_setup

/* -------------------------------------------------------------------- */
/* WIDGETS
/* -------------------------------------------------------------------- */
if (!function_exists('wi_widgets_init')) {
function wi_widgets_init() {
	
    register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'wi' ),
		'id'            => 'sidebar',
		'description'   => __('Add widgets here to appear in your sidebar.', 'wi' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Page Sidebar', 'wi' ),
		'id'            => 'page-sidebar',
		'description'   => __('Add widgets here to appear in your page\'s sidebar.', 'wi' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'After Logo', 'wi' ),
		'id'            => 'header',
		'description'   => __('Add widgets here to appear below site logo', 'wi' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
    
    for ($i=1; $i<=4; $i++) {
    register_sidebar( array(
		'name'          => sprintf(__( 'Footer %s', 'wi' ), $i),
		'id'            => 'footer-'.$i,
		'description'   => __('Add widgets here to appear in your footer sidebar.', 'wi' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
    }
    
    register_sidebar( array(
		'name'          => 'Before Main Header',
		'id'            => 'before-header',
		'description'   => '<strong>Above Main Header</strong> > Main Header > After Main Header',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
    
    // tks https://gist.github.com/slushman/6f08885853d4a7ef31ebceafd9e0c180
    $query[ 'autofocus[section]' ] = 'sidebar-widgets-header-builder';
    $section_link = add_query_arg( $query, admin_url( 'customize.php' ) );
    
    register_sidebar( array(
		'name'          => 'MAIN HEADER BUILDER',
		'id'            => 'header-builder',
		'description'   => 'Drag header widgets (ie. header elements) here to build your own header. You can set it live and have more settings in <a href="' . $section_link . '">Customize > Widgets > Header Builder</a>',
		'before_widget' => '<div id="%1$s" class="header-builder-element %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="header-builder-element-title"><span>',
		'after_title'   => '</span></h4>',
	) );
    
    register_sidebar( array(
		'name'          => 'After Main Header',
		'id'            => 'after-header',
		'description'   => 'Above Main Header > Main Header > <strong>After Main Header</strong>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
    
}
}

/* -------------------------------------------------------------------- */
/* ENQUEUE SCRIPTS
/* -------------------------------------------------------------------- */
function wi_scripts() {
    
    // loads google fonts
    wp_enqueue_style( 'wi-fonts', wi_fonts(), array(), null );

	// awesome font
    // include to CSS file since 4.0
	// wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/css/font-awesome-4.7.0/css/font-awesome.min.css' ), array(), '4.7' );

    // Load our main stylesheet.
    if ( is_child_theme() || ( defined('WP_DEBUG') && true === WP_DEBUG ) ) {
	   wp_enqueue_style( 'style', get_stylesheet_uri() );
    } else {
        wp_enqueue_style( 'style', get_theme_file_uri( 'style.min.css' ) );
    }
    
    if ( withemes_woocommerce_installed() ) {
        wp_enqueue_style( 'woocommerce', get_theme_file_uri( '/css/woocommerce.css' ) );
    }
    
    // Responsive
    // deprecated since 2.9
    // we merged it with style.css
	// wp_enqueue_style( 'wi-responsive', get_theme_file_uri( '/css/responsive.css' ) );

	if ( is_singular() && comments_open() && get_theme_mod( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    // facebook
    wp_register_script( 'wi-facebook', 'https://connect.facebook.net/en_US/all.js#xfbml=1', false, '1.0', true );
    
    // main
    if ( defined('WP_DEBUG') && true === WP_DEBUG ) {
        
        wp_enqueue_script( 'imagesloaded', get_theme_file_uri( '/js/imagesloaded.pkgd.min.js' ), array( 'jquery' ), '3.1.8' , true );
        wp_enqueue_script( 'colorbox', get_theme_file_uri( '/js/jquery.colorbox-min.js' ), array( 'jquery' ), '1.6.0' , true );
        wp_enqueue_script( 'easing', get_theme_file_uri( '/js/jquery.easing.1.3.js' ), array( 'jquery' ), '1.3' , true );
        wp_enqueue_script( 'fitvids', get_theme_file_uri( '/js/jquery.fitvids.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'flexslider', get_theme_file_uri( '/js/jquery.flexslider-min.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'inview', get_theme_file_uri( '/js/jquery.inview.min.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'retina', get_theme_file_uri( '/js/jquery.retina.min.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'masonry', get_theme_file_uri( '/js/masonry.pkgd.min.js' ), array( 'jquery' ), '3.2.2' , true );
        wp_enqueue_script( 'matchMedia', get_theme_file_uri( '/js/matchMedia.js' ), array( 'jquery' ), '1.0' , true );
        wp_enqueue_script( 'wi-slick', get_theme_file_uri( '/js/slick.min.js' ), array( 'jquery' ), '1.4.1' , true );
        wp_enqueue_script( 'theia-sticky-sidebar', get_theme_file_uri( '/js/theia-sticky-sidebar.js' ), array( 'jquery' ), '1.3.1' , true );
        
        // since 4.0
        wp_enqueue_script( 'superfish', get_theme_file_uri( '/js/superfish.js' ), array( 'jquery' ), '1.7.9' , true );
        
        wp_enqueue_script( 'wi-main', get_theme_file_uri( '/js/main.js' ), array( 'jquery', 'wp-api' ), FOX_VERSION , true );
        
    } else {
        
        wp_enqueue_script( 'wi-main', get_theme_file_uri( '/js/theme.min.js' ), array( 'jquery', 'wp-api' ), FOX_VERSION , true );
        
    }
    
    // Create a filter to add global JS data to <head />
    // @since Fox 2.2
    $jsdata = array(
        'l10n' => array( 
            'prev' => esc_html__( 'Previous', 'wi' ), 
            'next' => esc_html__( 'Next', 'wi' ),
        ),
        'enable_sticky_sidebar'=> get_theme_mod( 'wi_sticky_sidebar' ),
        
        // @since 2.8
        'enable_sticky_header' => ( 'false' != get_theme_mod( 'wi_header_sticky' ) ),
        
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce( 'nav_mega_nonce' ),
        
        'resturl_v2' => get_rest_url( null, '/wp/v2/', 'rest' ),
        'resturl_v2_posts' => get_rest_url( null, '/wp/v2/posts/', 'rest' ),
    );
    
    if ( is_single() && wi_autoload() && !is_customize_preview() ) {
        
        wp_enqueue_script( 'scrollspy', get_theme_file_uri( '/js/scrollspy.js' ), array('jquery'), null, true );
        wp_enqueue_script( 'autoloadpost', get_theme_file_uri( '/js/autoloadpost.js' ), array('jquery', 'scrollspy'), null, true );
        wp_enqueue_script( 'history', get_theme_file_uri( '/js/jquery.history.js' ), array('jquery'), null, true );
        $jsdata[ 'enable_autoload' ] = true;
        
    }
    
    $jsdata = apply_filters( 'jsdata', $jsdata );
	wp_localize_script( 'wi-main', 'WITHEMES', $jsdata );
    
}

if ( ! function_exists( 'wi_comment' ) ) :
/**
 * Displays Comment in single
 *
 * @since 2.8
 */
function wi_comment() {
    
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;

}
endif;

if ( ! function_exists( 'wi_comment_hidden' ) ) :
/**
 * Displays comment in single
 *
 * @since 2.9
 */
function wi_comment_hidden() {
    ?>

<div class="comment-hidden">
    
    <button class="show-comment-btn wi-btn"><?php echo esc_html__( 'Show comments', 'wi' ); ?></button>
    
    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>
    
</div><!-- .comment-hidden -->
    <?php
}
endif;

if ( ! function_exists( 'wi_comment_nav' ) ) :
/**
 * Comment Nav
 *
 * @since 2.8
 */
function wi_comment_nav( $pos ) {

    if ( get_comment_pages_count() > 1 && get_theme_mod( 'page_comments' ) ) : // Are there comments to navigate through? ?>
    <nav id="comment-nav-<?php echo esc_attr( $pos ); ?>" class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wi' ); ?></h2>
        <div class="nav-links">

            <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'wi' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'wi' ) ); ?></div>

        </div><!-- .nav-links -->
    </nav><!-- #comment-nav-# -->
    <?php endif; // Check for comment navigation.
    
}
endif;

if ( ! function_exists( 'wi_navigation' ) ) :
/**
 * Navigation Items
 *
 * @since 2.9
 */
function wi_navigation() {
    
    if (has_nav_menu('primary')):?>

    <nav id="wi-mainnav" class="navigation-ele wi-mainnav" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <?php wp_nav_menu(array(
            'theme_location'	=>	'primary',
            'depth'				=>	3,
            'container_class'	=>	'menu',
        ));?>
    </nav><!-- #wi-mainnav -->

    <?php else: ?>

    <?php echo '<div id="wi-mainnav"><em class="no-menu">'.sprintf(__('Go to <a href="%s">Appearance > Menu</a> to set "Primary Menu"','wi'),get_admin_url('','nav-menus.php')).'</em></div>'; ?>

    <?php endif; ?>

    <?php if (!get_theme_mod('fox_disable_header_social')):?>
    <div id="header-social" class="header-social social-list">
        <ul>
            <?php wi_social_list(!get_theme_mod('fox_disable_header_search')); ?>
        </ul>
    </div><!-- .header-social -->
    <?php endif; // header-social
    
}
endif;

if ( ! function_exists( 'wi_toggle_btn' ) ) :
/**
 * Toggle Button
 *
 * @since 2.9
 */
function wi_toggle_btn() { ?>

    <a class="toggle-menu">
        <span></span>
        <span></span>
        <span></span>
    </a>

<?php    
}
endif;

if ( ! function_exists( 'wi_site_branding' ) ) :
/**
 * Site Branding
 *
 * @since 2.9
 */
function wi_site_branding() {
    
    $htag = is_home() ? 'h1' : 'h2';
    
    ?>
    <div id="logo-area">
        
        <div id="wi-logo">
            
            <?php wi_toggle_btn(); ?>
            
            <?php echo '<' . $htag . ' class="wi-logo-main" id="site-logo">'; ?>
                
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <?php if (!get_theme_mod('fox_logo')):?>

                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" data-retina="<?php echo get_template_directory_uri(); ?>/images/logo@2x.png" />

                    <?php else: ?>

                        <img src="<?php echo get_theme_mod('fox_logo');?>" alt="Logo"<?php echo get_theme_mod('fox_logo_retina') ? ' data-retina="'.get_theme_mod('fox_logo_retina').'"' : '';?> />

                    <?php endif; // logo ?>
                </a>
                
            <?php echo '</' . $htag . '>'; ?>

        </div><!-- #wi-logo -->

        <?php if (!get_theme_mod('fox_disable_header_slogan') ): $hide_mobile_class = get_theme_mod( 'wi_disable_header_slogan_mobile' ) ? ' hide_on_mobile' : '' ?>
        
        <h3 class="slogan<?php echo $hide_mobile_class; ?>"><?php bloginfo('description');?></h3>
        
        <?php endif; ?>

    </div><!-- #logo-area -->
    <?php
}
endif;

if ( ! function_exists( 'wi_header_searchbox' ) ) :
/**
 * Header Search Box
 *
 * @since 2.9
 */
function wi_header_searchbox() {
    
    if (!get_theme_mod('fox_disable_header_search')):?>
    <div class="header-search" id="header-search">
        
        <div class="container">
        
            <form role="search" method="get" action="<?php echo home_url();?>" itemprop="potentialAction" itemscope itemtype="https://schema.org/SearchAction">
                <input type="text" name="s" class="s" value="<?php echo get_search_query();?>" placeholder="<?php _e('Type & hit enter...','wi');?>" />
                <button class="submit" role="button" title="<?php _e('Go','wi');?>"><span><?php _e('Go','wi');?></span></button>
            </form>
            
        </div>
            
    </div><!-- .header-search -->
    <?php endif;
    
}
endif;

if ( ! function_exists( 'wi_main_header' ) ) :
/**
 * Site Branding
 *
 * @since 2.9
 */
function wi_main_header() {
    ?>

    <div id="wi-header" class="wi-header">
        
        <div class="container">

            <?php wi_site_branding(); ?>

            <div class="clearfix"></div>

            <?php 
            /**
             * Header Area
             *
             * @since 2.1.4
             *
             * Place ad widgets here
             */
            if ( is_active_sidebar( 'header' ) ) : ?>

            <aside id="header-area" class="widget-area wide-sidebar" role="complementary" itemscope itemptype="https://schema.org/WPSideBar">

                <?php dynamic_sidebar( 'header' ); ?>

            </aside><!-- .widget-area -->

            <?php endif; ?>

        </div><!-- .container -->

    </div><!-- #wi-header -->
    
    <?php
}
endif;

if ( !function_exists('wi_backtotop') ) {
function wi_backtotop() {
    
    if ( ! apply_filters( 'fox_show_footer', true ) ) return;
    
    if (!get_theme_mod('fox_disable_backtotop')){
    ?>
    <div id="backtotop" class="backtotop">
        <span class="go"><?php _e('Go to','wi');?></span>
        <span class="top"><?php _e('Top','wi');?></span>
    </div><!-- #backtotop -->
<?php 
    } // endif
}   
}

/* -------------------------------------------------------------------- */
/* SHARE BUTTONS
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_share') ) {
function wi_share($comment = false) {
    
    global $wp_query;
	if (in_the_loop() || is_single() || is_page()) {$url = get_permalink();}
    elseif (is_category() || is_tag()) {
        $url = get_term_link(get_queried_object());
    } else {
        return;
    }
    
    $title = trim( get_the_title() );
    $title = strip_tags( $title );
    
    $image = '';
    if ( has_post_thumbnail() ) {
        $image = wp_get_attachment_thumb_url();
    }
    $via = trim( get_theme_mod( 'wi_twitter_username' ) );
    
    $share_icons = get_theme_mod( 'wi_share_icons', 'facebook,twitter,pinterest,linkedin,email' );
    $share_icons = explode( ',',$share_icons );
    $share_icons = array_map( 'trim', $share_icons );
    $share_icons = array_slice( $share_icons, 0, 5 );
    
    if ($comment && !get_theme_mod('fox_disable_blog_comment') ) {
        $column = count( $share_icons ) + 1;
    } else {
        $column = count( $share_icons ); 
    }

?>

<div class="post-share share-<?php echo $column; ?>">
    
    <h4 class="share-label"><?php echo esc_html__( 'Share This', 'wi' ); ?></h4>
    
    <ul>
        <?php if ($comment && !get_theme_mod('fox_disable_blog_comment')):?>
        <li class="li-comment">
            <?php
        comments_popup_link( 
            '<i class="fa fa-comment"></i><span>' . __('No comments','wi') . '</span>', 
            '<i class="fa fa-comment"></i><span>' . __('1 comment','wi') . '</span>', 
            '<i class="fa fa-comment"></i><span>' . __('% comments','wi') . '</span>', 
            '',
            '<i class="fa fa-comment"></i><span>' . __('Off','wi') . '</span>'
        ); ?>
        </li>
        <?php endif; ?>
        
        <?php foreach ( $share_icons as $icon ) {
            if ( 'google' == $icon ) {
                $ic = 'google-plus';
                $label = 'Google+';
            } else {
                $ic = $icon;
                $label = ucfirst( $icon );
            }
        ?>
        
        <li class="li-<?php echo $ic; ?>">
            
            <?php 
            
            if ( 'facebook' == $icon ) {
                $href = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
                if ( $image ) {
                    $href .= '&amp;p[images][0]=' . urlencode( $image );
                }
            } elseif ( 'twitter' == $icon ) {
                $href = 'https://twitter.com/intent/tweet?url=' . urlencode($url) .'&amp;text=' . urlencode( html_entity_decode( $title ) );
                if ( $via ) {
                    $href .= '&amp;via=' . urlencode( $via );
                }
            } elseif ( 'google' == $icon ) {
                
                $href = 'https://plus.google.com/share?url=' . urlencode( $url );
                
            } elseif ( 'pinterest' == $icon ) {
                
                $href = 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;description=' . urlencode( html_entity_decode( $title ) );
                if ( $image ) {
                    $href .= '&amp;media=' . urlencode($image);
                }
                
            } elseif ( 'linkedin' == $icon ) {
                
                $href = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $url ) . '&amp;title=' . urlencode( html_entity_decode( $title ) );
            
            } elseif ( 'whatsapp' == $icon ) {
            
                $href = 'https://api.whatsapp.com/send?phone=&text=' . urlencode( $url );
            
            } elseif ( 'email' == $icon ) {
            
                $href = 'mailto:?subject=' . urlencode($title) . '&amp;body=' . rawurlencode($url);
            
            }
            
            ?>
            
            <?php if ( 'email' == $icon ) { ?>
            
            <a href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_html__( 'Email','wi' ); ?>" class="email-share">

                <i class="fa fa-envelope"></i>
                <span><?php echo esc_html__( 'Email', 'wi' ); ?></span>

            </a>
            
            <?php } else { ?>
            
            <a data-href="<?php echo esc_url( $href ); ?>" title="<?php echo $label;?>" class="share share-<?php echo $icon; ?>">
                
                <i class="fa fa-<?php echo $ic; ?>"></i>
                <span><?php echo $label; ?></span>
            
            </a>
            
            <?php } ?>
        
        </li>
        
        <?php } ?>
        
    </ul>
    
</div>
<?php  
}
}

if ( ! function_exists( 'wi_page_links' ) ) :
/**
 * Page Links
 *
 * @since 2.8
 */
function wi_page_links() {
    
    wp_link_pages( array(
        'before'      => '<div class="page-links-container"><div class="page-links"><span class="page-links-label">' . esc_html__( 'Pages:', 'wi' ) . '</span>',
        'after'       => '</div></div>',
        'link_before' => '<span class="page-number">',
        'link_after'  => '</span>',
    ) );
    
}
endif;

if ( ! function_exists( 'wi_comment_link' ) ) :
/**
 * Comment Link
 *
 * @since 2.8
 */
function wi_comment_link() {
    
    comments_popup_link( 
        '<span class="ic-comment"><span class="line-inside"></span></span>',
        
        
        '<u>1</u> <span class="ic-comment"><span class="line-inside"></span></span>',
        
        
        '<u>%s</u> <span class="ic-comment"><span class="line-inside"></span></span>',
        
        'comment-link',
        
        '<span class="ic-comment off"><span class="line-inside"></span></span>'
    ); 

}
endif;

if ( ! function_exists( 'wi_pagination' ) ) :
/**
 * Pagination
 *
 * @since 1.0
 */
function wi_pagination( $query = false ) {
    
    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }
    
    $prev_label = esc_html__( 'Previous', 'wi' );
    $next_label = esc_html__( 'Next &raquo;', 'wi' );
    
    $big = 9999; // need an unlikely integer
	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => ( is_front_page() ) ? max( 1, get_query_var( 'page' ) ) : max( 1, get_query_var( 'paged' ) ),
		'total' => $query->max_num_pages,
		'type'			=> 'plain',
		'before_page_number'	=>	'<span>',
		'after_page_number'	=>	'</span>',
		'prev_text'    => '<span>' . $prev_label . '</span>',
		'next_text'    => '<span>' . $next_label . '</span>',
	) );
    
    if ( $pagination ) {
        echo '<div class="wi-pagination"><div class="pagination-inner">' . $pagination  . '<div class="clearfix"></div></div></div>';
	}

}
endif;

if ( ! function_exists( 'wi_comment_text_link' ) ) :
/**
 * Comment Text Link
 *
 * @since 2.8
 */
function wi_comment_text_link() {
    
    comments_popup_link(); 
    
}
endif;

if ( ! function_exists( 'wi_get_view' ) ) :
/**
 * return number of view
 * since 3.0
 */
function wi_get_view( $post_id = null ) {
    if ( ! $post_id ) {
        global $post;
        $post_id =  $post->ID;
    }
    
    if ( ! function_exists( 'pvc_get_post_views' ) ) return;
    
    return number_format_i18n( pvc_get_post_views( $post_id ) );
}
endif;

if ( ! function_exists( 'wi_view_count' ) ) :
/**
 * Displays view count
 * @since 2.8
 */
function wi_view_count() {
    
    $count = wi_get_view();
    echo '<span class="entry-view-count" title="' . sprintf( esc_html__( '%s views', 'wi' ), $count ) . '"><span>' . sprintf( esc_html__( '%s views', 'wi' ), $count ) . '</span></span>';
    
}
endif;

if ( ! function_exists( 'wi_format_indicator' ) ) :
/**
 * Format Indicator
 *
 * @since 2.8
 */
function wi_format_indicator() {
    
    $format = get_post_format();
    
    if ( 'video' === $format ) {
        echo '<span class="video-format-indicator"></span>';
    }
    
    if ( 'gallery' === $format ) {
        echo '<span class="post-format-indicator gallery-format-indicator"><span class="ic-gallery"></span></span>';
    }
    
    if ( 'link' === $format ) {
        echo '<span class="post-format-indicator link-format-indicator"><i class="fa fa-external-link-alt"></i></span>';
    }
    
    if ( 'audio' === $format ) {
        echo '<span class="post-format-indicator audio-format-indicator"><i class="fa fa-volume-up"></i></span>';
    }
    
    
}
endif;

/* -------------------------------------------------------------------- */
/* GET THUMBNAIL WHEN HAS NO THUMBNAIL
 * @since 2.0
 * thumbnail
 * class (grid, masonry...)
 * link (link to single post
 * placeholder image when there's no image
 * $view_count to show or not (since 2.8)
/* -------------------------------------------------------------------- */
if ( !function_exists('wi_display_thumbnail') ) {
function wi_display_thumbnail( $thumbnail = 'thumbnail', $class = '', $link = true, $placeholder = false, $view_count = false, $echo = true ){
    
    if ( ! $echo ) {
        ob_start();
    }
    
    if ( ! $class ) {
        $class = 'post-item-thumbnail';
    }
    
    if (has_post_thumbnail()) {?>
        <figure class="<?php echo esc_attr($class);?>" itemscope itemtype="https://schema.org/ImageObject">
            
            <?php $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
            
            <meta itemprop="url" content="<?php echo esc_url( $full_img[0] ); ?>">
            <meta itemprop="width" content="<?php echo absint( $full_img[1] ); ?>">
            <meta itemprop="height" content="<?php echo absint( $full_img[2] ); ?>">
            
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>
                <?php the_post_thumbnail( $thumbnail ); ?>
            
                <?php echo get_post_format( ) ? '<span class="format-sign sign-' . get_post_format() . '"><i class="fa fa-'.wi_format_icon().'"></i></span>' : ''; ?>
            
            <?php if ( $view_count && get_theme_mod('fox_blog_view_count')):?>
            <?php wi_view_count(); ?>
            <?php endif; ?>
            
            <?php if ($link) echo '</a>';?>
            
        </figure>
    <?php
                              } 
    elseif ( $attachments = get_posts( array(
    'post_type' => 'attachment',
    'posts_per_page' => 1,
    'post_parent' => get_the_ID(),
    ) ) ) {
        $image = wp_get_attachment_image_src($attachments[0]->ID, $thumbnail);?>

        <figure class="<?php echo esc_attr($class . ' thumbnail-type-secondary');?>" itemscope itemtype="https://schema.org/ImageObject">
            
            <?php $full_img = wp_get_attachment_image_src( $attachments[0]->ID, 'full' ); ?>
            
            <meta itemprop="url" content="<?php echo esc_url( $full_img[0] ); ?>">
            <meta itemprop="width" content="<?php echo absint( $full_img[1] ); ?>">
            <meta itemprop="height" content="<?php echo absint( $full_img[2] ); ?>">
            
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>

                <img src="<?php echo esc_url($image[0]);?>" width="<?php echo esc_attr($image[1]);?>" height="<?php echo esc_attr($image[2]);?>" alt="<?php echo esc_attr(get_post_meta($attachments[0]->ID, '_wp_attachment_image_alt', true));?>" />
            
                <?php echo get_post_format() ? '<span class="format-sign sign-'.get_post_format().'"><i class="fa fa-'.wi_format_icon().'"></i></span>' : ''; ?>
            
            <?php if ($link) echo '</a>';?>
        </figure>
    <?php
    } elseif ($placeholder) {
        ?>
        <figure class="<?php echo esc_attr($class . ' thumbnail-pseudo');?>">
            <?php if ($link) echo '<a href="'.get_permalink().'">';?>
        
                <img src="<?php echo get_template_directory_uri();?>/images/thumbnail-medium.png" width="400" height="320" alt="Placeholder" />
                <span class="format-indicator"><i class="fa fa-<?php echo wi_format_icon(get_post_format());?>"></i></span>
            
            <?php if ($link) echo '</a>';?>
        </figure>
    <?php
    }
    
    if ( ! $echo ) {
        return ob_get_clean();
    }
    
}
}

/* -------------------------------------------------------------------- */
/* FORMAT ICON
/* -------------------------------------------------------------------- */
if (!function_exists('wi_format_icon')) {
    function wi_format_icon($format = '') {
        if (!$format) $format = get_post_format();
        if ($format=='quote') return 'quote-left';
        elseif ($format=='gallery') return 'camera';
        elseif ($format=='audio') return 'music';
        elseif ($format=='video') return 'play';
        else return 'file-text-o';
    }
}

if ( ! function_exists( 'wi_entry_thumbnail' ) ) :
/**
 * Display post thumbnail for various post formats
 *
 * @since 2.8
 */
function wi_entry_thumbnail() {
    
    $format = get_post_format();
    
    if ( 'video' === $format ) {
        
        echo '<div class="post-thumbnail thumbnail-video"><div class="media-container">' . wi_get_media_result() . '</div></div>';
        
    } elseif ( 'audio' === $format ) {
        
        echo '<div class="post-thumbnail thumbnail-audio"><div class="media-container">' . wi_get_media_result() . '</div></div>';
        
    } elseif ( 'gallery' === $format ) {
        
        $effect = get_post_meta( get_the_ID(), '_format_gallery_effect', true );
        if ( is_single() && $effect=='carousel' ) return;
        if ($effect =='carousel') {
            wi_thumbnail_carousel();
            return;
        }
        
        if ( $effect!='fade' ) $effect = 'slide';
        
        // attachments
        $attachments = get_post_meta( get_the_ID() , '_format_gallery_images', true );
        if ( ! is_array( $attachments ) ) {
            $attachments = explode( ',', $attachments );
            $attachments = array_map( 'trim', $attachments );
        }
        
        if (  count($attachments) == 0 )	// nothing at all
                return;
        
        $options = array(
            'animation' => $effect,
        );
        ?>

        <div class="post-thumbnail thumbnail-gallery thumbnail-<?php echo esc_attr($effect);?>">
            
            <div class="wi-flexslider" data-options='<?php echo json_encode( $options ); ?>' data-effect="<?php echo esc_attr($effect);?>">
                <div class="flexslider">
                    <ul class="slides">
                        
                        <?php
                        foreach ( $attachments as $attachment):
                            $attachment_src = wp_get_attachment_image_src( $attachment, 'full' );
                            $attachment_post = get_post( $attachment );
                            ?>
                            <li class="slide">
                                
                                <figure itemscope itemtype="https://schema.org/ImageObject">
                                    
                                    <meta itemprop="url" content="<?php echo esc_url( $attachment_src[0] ); ?>">
                                    <meta itemprop="width" content="<?php echo absint( $attachment_src[1] ); ?>">
                                    <meta itemprop="height" content="<?php echo absint( $attachment_src[2] ); ?>">
                                    
                                    <img src="<?php echo esc_url ( $attachment_src[0] );?>" width="<?php echo esc_attr($attachment_src[1]);?>" height="<?php echo esc_attr($attachment_src[2]);?>" alt="<?php echo basename( $attachment_src[0] );?>" />
                                
                                </figure>
                                <?php if ($caption = $attachment_post->post_excerpt){?>
                                <span class="slide-caption"><?php echo strip_tags( $caption );?></span>
                                <?php } ?>
                                
                            </li>
                        <?php
                        endforeach;
                        ?>
                    </ul><!-- .slides -->
                </div><!-- .flexslider -->
            </div><!-- .wi-flexslider -->

        </div><!-- .post-thumbnail -->

    <?php
    
    } else {
    
        if ( '' !== get_the_post_thumbnail() ) { ?>

        <figure class="post-thumbnail" itemscope itemtype="https://schema.org/ImageObject">
            
            <?php $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>

            <meta itemprop="url" content="<?php echo esc_url( $full_img[0] ); ?>">
            <meta itemprop="width" content="<?php echo absint( $full_img[1] ); ?>">
            <meta itemprop="height" content="<?php echo absint( $full_img[2] ); ?>">
            
            <div class="post-thumbnail-inner">
            
                <?php if ( ! is_single() ) { ?>

                <a href="<?php the_permalink(); ?>">

                    <?php the_post_thumbnail( 'full' ); ?>

                </a>

                <?php } else { ?>

                <?php the_post_thumbnail( 'full' ); ?>

                <?php } ?>
                
            </div><!-- .post-thumbnail-inner -->
            
            <?php $caption = get_the_post_thumbnail_caption(); if ( $caption ) { ?>
            <figcaption class="post-thumbnail-caption wp-caption-text">
                <?php echo wp_kses( $caption, fox_allowed_html() ) ;?>
            </figcaption>
            <?php } ?>

        </figure><!-- .post-thumbnail -->

        <?php } else {
        
            echo '<div class="no-thumbnail-line"></div>';
        
        }

    }
    
}
endif;

/* -------------------------------------------------------------------- */
/* MEDIA RESULT
/* -------------------------------------------------------------------- */
if (!function_exists('wi_get_media_result')) {
function wi_get_media_result($size = 'full') {
    
	// get data
	$type = get_post_format();	
	if ($type=='audio') $media_code = trim( get_post_meta( get_the_ID(), '_format_audio_embed' , true ) );
	elseif ($type=='video') $media_code = trim( get_post_meta( get_the_ID(), '_format_video_embed' , true ) );
	else $media_code = '';
	
	// return none
	if (!$media_code) return;
	
	// iframe
	if ( stripos($media_code,'<iframe') > -1) return $media_code;

	// case url	
	// detect if self-hosted
	$url = $media_code;
	$parse = parse_url(home_url());
	$host = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
	$media_result = '';
	
	// not self-hosted
	if (strpos($url,$host)===false) {
		global $wp_embed;
		return $wp_embed->run_shortcode('[embed]' . $media_code . '[/embed]');
	
	// self-hosted	
	} else {
		if ($type=='video') {
			$args = array('src' => esc_url($url), 'width' => '643' );
			if ( has_post_thumbnail() ) {
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id() , $size );
				$args['poster'] = $full_src[0];
			}
			$media_result = '<div class="wi-self-hosted-sc">'.wp_video_shortcode($args).'</div>';
            $video_id = attachment_url_to_postid( $url );
            if ( $video_id ) {
                $caption = wp_get_attachment_caption( $video_id ); 
                if ( $caption ) {
                    $media_result .= '<figcaption class="post-thumbnail-caption video-caption wp-caption-text">';
                    $media_result .= wp_kses( $caption, fox_allowed_html() );
                    $media_result .= '</figcaption>';
                }
            }
		} elseif ($type=='audio') {
            
            if ( has_post_thumbnail() ) {
				$full_src = wp_get_attachment_image_src( get_post_thumbnail_id() , $size );
			}
            
			$media_result = '<figure class="wi-self-hosted-audio-poster"><img src="'.esc_url($full_src[0]).'" width="'.$full_src[1].'" height="'.$full_src[2].'" alt="'.esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)) .'" /></figure>' . wp_audio_shortcode(array('src' => esc_url($url)));
		}
	}
	
	return $media_result;
	
}
}

if ( ! function_exists( 'wi_thumbnail_carousel' ) ) :
/**
 * Display carousel gallery format
 *
 * @since 2.8
 */
function wi_thumbnail_carousel() {
    
    if (get_post_format()!='gallery') return;
    
    $effect = get_post_meta( get_the_ID(), '_format_gallery_effect', true);
    
    if ($effect!='carousel') return;
    
    // attachments
    $attachments = get_post_meta( get_the_ID() , '_format_gallery_images', true );
    if ( ! is_array( $attachments ) ) {
        $attachments = explode( ',', $attachments );
        $attachments = array_map( 'trim', $attachments );
    }

    if (  count($attachments) == 0 )	// nothing at all
            return;
?>

    <div class="wi-carousel">

        <div class="wi-slick">

            <?php
            foreach ( $attachments as $attachment):
                $attachment_src = wp_get_attachment_image_src( $attachment, 'thumbnail-vertical' );
                $full_src = wp_get_attachment_image_src( $attachment, 'full' );
                $attachment_post = get_post($attachment);
                ?>
                    <figure class="slick-item slide" itemscope itemtype="https://schema.org/ImageObject">
                        
                        <meta itemprop="url" content="<?php echo esc_url( $full_src[0] ); ?>">
                        <meta itemprop="width" content="<?php echo absint( $full_src[1] ); ?>">
                        <meta itemprop="height" content="<?php echo absint( $full_src[2] ); ?>">
                        
                        <a href="<?php echo esc_url($full_src[0]);?>" class="wi-colorbox" rel="carouselPhotos">
                            <img src="<?php echo esc_url ( $attachment_src[0] );?>" width="<?php echo esc_attr($attachment_src[1]);?>" height="<?php echo esc_attr($attachment_src[2]);?>" alt="<?php echo basename( $attachment_src[0] );?>" />

                            <?php if ($caption = $attachment_post->post_excerpt){?>
                            <span class="slide-caption"><?php echo strip_tags( $caption );?></span>
                            <?php } ?>
                        </a><!-- .wi-colorbox -->
                        
                    </figure>

            <?php
            endforeach;
            ?>

        </div><!-- .wi-slick -->
        
    </div><!-- .wi-carousel -->

<?php return;
    
}
endif;

if ( ! function_exists( 'wi_related_posts' ) ) :
/**
 * Display related posts of current post
 *
 * @since 3.0
 */
function wi_related_posts() {
    
    $related_query = wi_related_query( 3 );
    if ( $related_query && $related_query->have_posts() ) { ?>

    <div class="related-posts" id="related-posts">

        <h3 class="related-heading"><span><?php _e('You might be interested in','wi');?></span></h3>

        <div class="related-list blog-grid column-3">
            <?php while ( $related_query->have_posts() ): $related_query->the_post();?>

                <?php get_template_part('loop/content-related', 'single' ); ?>

            <?php endwhile; ?>

            <div class="clearfix"></div>

        </div><!-- .related-list -->

    </div><!-- #related-posts -->

    <?php
    } // related_posts

    wp_reset_query();

}
endif;

if ( ! function_exists( 'wi_excerpt' ) ) :
/**
 * Prints post excerpt
 *
 * @since 2.8
 */
function wi_excerpt( $excerpt_length, $args = array() ) {
    
    extract( wp_parse_args( $args, array(
        'basis' => array( 'grid' ),
    ) ) );
    
    $excerpt_class = '';
    foreach ( $basis as $base ) {
        $excerpt_class .= ' post-' . $base . '-excerpt';
    }
    
    $ex = get_the_excerpt();
    echo '<div class="' . esc_attr( $excerpt_class ) . '" itemprop="text">' . wpautop( wi_word_substr( $ex, 0, $excerpt_length ) ) . '</div>';

}
endif;

if ( ! function_exists( 'wi_related_query' ) ) :
/**
 * Returns a query of related posts
 *
 * @since 3.0
 */
function wi_related_query( $number = 3 ) {
    
    global $post;
    $current_ID = $post->ID;
    $tags = wp_get_post_tags( $current_ID, array( 'fields' => 'ids' ) );
    // $tags = [];
    $args = array(

        'post_type' => 'post',
        'posts_per_page' => $number,

        'ignore_sticky_posts'   =>  true,
        'no_found_rows' => true,
        'cache_results' => false,
        'post__not_in' => array( $current_ID ),

    );
    if ( empty( $tags ) ) return;

    $args[ 'tag__in' ] = $tags;

    $related_query = new WP_Query( $args );

    return $related_query;
    
}
endif;

if ( ! function_exists( 'wi_post_title' ) ) :
/**
 * Displays the post title
 * @since 3.0
 */
function wi_post_title( $extra_class = ''  ) {
    
    $class = [ 'post-item-title' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $class = join( ' ', $class );
    
    ?>

<h2 class="<?php echo esc_attr( $class ); ?>" itemprop="headline">

    <a href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a>

</h2>

    <?php
}
endif;

if ( ! function_exists( 'wi_short_meta' ) ) :
/**
 * Displays the post meta
 * @since 3.0
 */
function wi_short_meta( $extra_class = ''  ) {
    
    $class = [ 'post-item-meta' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $class = join( ' ', $class );
    
    ?>

<div class="<?php echo esc_attr( $class ); ?>">

    <?php if (!get_theme_mod('fox_disable_blog_date')):?>
    <?php wi_short_date(); ?>
    <?php endif; ?>

    <?php if (!get_theme_mod('fox_disable_blog_categories')):?>
    <?php wi_entry_categories(); ?>
    <?php endif; ?>

</div><!-- .post-item-meta -->

    <?php
}
endif;

if ( ! function_exists( 'wi_entry_excerpt' ) ) :
/**
 * Displays the excerpt
 * @since 3.0
 */
function wi_entry_excerpt( $length = -1, $more = null, $extra_class = '' ) {
    
    $class = [ 'post-item-excerpt' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $class = join( ' ', $class );
    
    $excerpt = get_the_excerpt();
    if ( $length > 0 ) {
        $excerpt = wi_subword( $excerpt , 0 , $length ) . '&hellip;';
    }
    ?>

    <div class="<?php echo esc_attr( $class ); ?>" itemprop="text">
        <p> 
            <?php echo $excerpt; ?>
            
            <?php if ( null === $more ) $more = ! get_theme_mod( 'wi_disable_blog_readmore' ); ?>

            <?php if ( $more ): ?>
            <a href="<?php the_permalink();?>" class="readmore"><?php _e('Keep Reading','wi');?></a>
            <?php endif; ?>
        </p>
    </div><!-- .post-item-excerpt -->

    <?php
    
}
endif;

if ( ! function_exists( 'wi_entry_author' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 2.8
 */
function wi_entry_author() {

	// Get the author name; wrap it in a link.
	$byline = sprintf(
		/* translators: %s: post author */
		esc_html__( 'by %s', 'wi' ),
		'<span class="author vcard"><a class="url fn" itemprop="url" rel="author" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><span itemprop="name">' . get_the_author() . '</span></a></span>'
	);

	// Finally, let's write all of this to the page.
	echo '<span class="entry-author meta-author" itemprop="author" itemscope itemtype="https://schema.org/Person">';
    
    echo '<span class="byline"> ' . $byline . '</span>';
    
    echo '</span>';
}
endif;

if ( ! function_exists( 'wi_entry_date' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 * @since 2.8
 
 * add human difference time
 * @since 3.0
 */
function wi_entry_date() {
    
    $time_style = get_theme_mod( 'wi_time_style', 'human' );
    
    if ( 'human' === $time_style ) :
    
        echo '<span class="entry-date meta-time human-time">';
    
        printf( esc_html_x( '%s ago', '%s = human-readable time difference', 'wi' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
    
        echo '</span>';
    
    else :
    
        $time_string = '<time class="published updated" itemprop="datePublished" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="published" itemprop="datePublished" datetime="%1$s">%2$s</time><time class="updated" itemprop="dateModified" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            get_the_date( DATE_W3C ),
            get_the_date(),
            get_the_modified_date( DATE_W3C ),
            get_the_modified_date()
        );

        // Wrap the time string in a link, and preface it with 'Posted on'.
        echo '<span class="entry-date meta-time machine-time">';
    
        printf(
            /* translators: %s: post date */
            wp_kses( '<span class="published-label">' . esc_html__( 'Published on', 'wi' ) . '</span> %s', fox_allowed_html() ),
            $time_string
        );
    
        echo '</span>';
    
    endif;
    
}
endif;

if ( ! function_exists( 'wi_short_date' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function wi_short_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	echo '<span class="grid-date">' . sprintf(
		/* translators: %s: post date */
		wp_kses( '<span class="screen-reader-text">' . esc_html__( 'Posted on', 'wi' ) . '</span> %s', fox_allowed_html() ),
		$time_string
	) . '</span>';
}
endif;

if ( ! function_exists( 'wi_entry_categories' ) ) :
/**
 * Prints post categories
 */
function wi_entry_categories() {
    
    if ( 'post' !== get_post_type() ) return;
    
    $separate_meta = '<span class="sep">' . esc_html__( '/', 'wi' ) . '</span>';
    ?>

    <span class="entry-categories meta-categories">

        <?php printf( esc_html__( '%s %s', 'wi' ), '<span class="in-word">' . esc_html__( 'in', 'wi' ) . '</span>', get_the_category_list( $separate_meta ) ); ?>

    </span>

    <?php
    
}
endif;

if ( ! function_exists( 'wi_sidebar' ) ) :
/**
 * Display sidebar if sidebar_state returns yes
 */
function wi_sidebar( $sidebar = 'main' ) {

    if ( wi_sidebar_state() != 'no-sidebar' ) get_sidebar( $sidebar );
    
}
endif;

if (!function_exists('wi_sidebar_state')){
function wi_sidebar_state(){
    $sidebar_state = '';
    if (is_page()) {
        if (
            is_page_template('page-fullwidth.php') || is_page_template('page-one-column.php')
        ) {
            $sidebar_state = 'no-sidebar';
        } else {
            $sidebar_state = get_post_meta( get_the_ID(), '_wi_sidebar_layout', true );
            if ( ! $sidebar_state ) $sidebar_state = get_theme_mod( 'wi_page_sidebar_state' );
        }
    } elseif (is_single()) {
        $sidebar_state = get_post_meta( get_the_ID(), '_wi_sidebar_layout', true );
        if ( ! $sidebar_state ) $sidebar_state = get_theme_mod('fox_single_sidebar_state');
    } elseif (is_home()) {
        $sidebar_state = get_theme_mod('fox_home_sidebar_state');
    } elseif (is_category()) {
        
        $t_id = get_queried_object_id();
        $term_meta = get_theme_mod( "taxonomy_$t_id" );
        $sidebar_state = isset($term_meta['sidebar_state']) ? $term_meta['sidebar_state'] : '';
        if ( ! $sidebar_state ) $sidebar_state = get_theme_mod('fox_category_sidebar_state');
    } elseif (is_tag()) {
        $sidebar_state = get_theme_mod('fox_tag_sidebar_state');
    } elseif (is_archive()) {
        $sidebar_state = get_theme_mod('fox_archive_sidebar_state');
    } elseif (is_search()) {
        $sidebar_state = get_theme_mod('fox_search_sidebar_state');
    } elseif (is_author()) {
        $sidebar_state = get_theme_mod('fox_author_sidebar_state');
    }
    
    $sidebar_state = apply_filters( 'wi_sidebar_state', $sidebar_state );
    
    if ($sidebar_state!='sidebar-left' && $sidebar_state!='no-sidebar') $sidebar_state = 'sidebar-right';
    return $sidebar_state;
}
}

if ( ! function_exists( 'fox_featured_image_style' ) ) :
/**
 * Style: hero full / half / standard?
 * return hero-full / hero-half / standard
 * @since 4.0
 */
function fox_featured_image_style() {
    
    $style = get_post_meta( get_the_ID(), '_wi_featured_image_style', true );
    
    // $hero for backward
    if ( ! $style ) {
        
        $hero = get_post_meta( get_the_ID(), '_wi_hero', true );
        
        if ( 'full' == $hero || 'half' == $hero ) {
            $style = 'hero-' . $hero;
        } elseif ( 'none' == $hero ) {
            $style = 'standard';
        }
            
    }
    
    if ( ! $style ) {
    
        $style = get_theme_mod( 'wi_featured_image_style', 'standard' );
    
    }
    
    // hook since 4.0
    $style = apply_filters( 'fox_featured_image_style', $style );
    
    // final check by logic
    if ( 'hero-full' != $style && 'hero-half' != $style ) $style = 'standard';
    
    return $style;
    
}
endif;

/**
 * HERO HEADER OF SINGLE POST
 *
 * @since 3.0
 */
if ( ! function_exists( 'wi_hero' ) ) :
/**
 * Check if we are displaying a hero featured image
 * @since 3.0
 *
 * return full / half / false
 */
function wi_hero() {
    
    $featured_image_style = fox_featured_image_style();
    
    if ( 'hero-full' == $featured_image_style ) return 'full';
    elseif ( 'hero-half' == $featured_image_style ) return 'half';
    else return false;

}

endif;

function wi_single_sidedock() {
    
    if ( ! apply_filters( 'fox_show_footer', true ) ) return;
    
    $hide = get_theme_mod( 'wi_disable_side_dock' ) || ! is_single() || wi_autoload();
    
    if ( ! apply_filters( 'fox_show_content_dock', ! $hide ) ) return;
    
    $related_posts = wi_related_query( 2 );
    if ( $related_posts && $related_posts->have_posts() ) :
    
    ?>

<aside id="content-dock">
    
    <h3 class="dock-title"><?php _e('You might be interested in','wi');?></h3>
    
    <div class="dock-posts">
        
        <?php while ( $related_posts->have_posts() ): $related_posts->the_post(); ?>

        <article <?php post_class('post-dock'); ?> itemscope itemtype="https://schema.org/CreativeWork">
            
            <div class="post-inner">

                <?php wi_display_thumbnail('thumbnail','post-dock-thumbnail',true,true);?>

                <section class="post-dock-body">

                    <div class="post-dock-content">

                        <header class="post-dock-header">

                            <h3 class="post-dock-title" itemprop="headline">
                                <a href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a>
                            </h3>

                        </header><!-- .post-dock-header -->
                        
                        <div class="post-dock-excerpt" itemprop="text">
                            <p><?php echo wi_subword(get_the_excerpt(),0,10); ?></p>
                        </div><!-- .post-dock-excerpt -->

                        <div class="clearfix"></div>

                    </div><!-- .post-dock-content -->

                </section><!-- .post-dock-body -->

                <div class="clearfix"></div>

            </div><!-- .post -->
            
        </article><!-- .post-dock -->
    
    <?php endwhile; ?>
        
    </div><!-- .dock-posts -->

    <button class="close">
        <i class="fa fa-close"></i>
    </button>

</aside><!-- #content-dock -->
    
<?php
    
    endif; // have posts
    
    wp_reset_query();
    
}

if ( ! function_exists( 'wi_single_ad' ) ) :
/**
 * Single Ad
 *
 * @since 2.5
 */
function wi_single_ad( $pos = 'before' ) {

    if ( 'after' != $pos ) $pos = 'before';
    $code = trim( get_theme_mod( 'wi_single_' . $pos . '_code' ) );
    if ( $code ) { ?>
    <div class="single-ad ad-code ad-<?php echo esc_attr( $pos ); ?>">
        <?php echo do_shortcode( $code ); ?>
    </div><!-- .single-ad -->
<?php } elseif ( $banner = get_theme_mod( 'wi_single_' . $pos . '_banner' ) ) {
        $url = trim( get_theme_mod( 'wi_single_' . $pos . '_banner_url' ) );
    if ( $url ) {
        $open = '<a href="' . esc_url( $url ) . '" target="_blank">';
        $close = '</a>';
    } else {
        $open = $close = '';
    }
?>
    
    <div class="single-ad ad-code ad-<?php echo esc_attr( $pos ); ?>">
        <?php echo $open; ?>
        <img src="<?php echo esc_url( $banner ); ?>" alt="<?php echo esc_html__( 'Banner', 'wi' ); ?>" />
        <?php echo $close; ?>
    </div>
<?php
    }
}
endif;

if ( ! function_exists( 'wi_single_featured_image_state' ) ) :
/**
 * return true/false
 * @since 4.0
 */
function wi_single_featured_image_state() {
    
    // post meta, what ever reason
    // legacy
    if ( 'true' == get_post_meta( get_the_ID(), '_wi_hide_featured_image', true ) ) $state = 'false';
    
    $meta = get_post_meta( get_the_ID(), '_wi_featured_image', true );
    
    if ( 'true' == $meta || 'false' == $meta ) {
        $state = $meta;
    } else {
        $state = get_theme_mod( 'wi_single_image', 'true' );
    }
    
    $state = apply_filters( 'wi_single_featured_image_state', $state );
    if ( 'false' != $state ) $state = 'true';
    
    return $state;
    
}
endif;

if ( ! function_exists( 'wi_single_share' ) ) :
/**
 * Displays or not share icons based on options
 * @since 4.0
 */
function wi_single_share( $post_type = 'post' ) {
    
    if ( 'true' == get_post_meta( get_the_ID(), '_wi_disable_share', true ) ) $state = 'false';
    
    $meta = get_post_meta( get_the_ID(), '_wi_share', true );
    
    if ( 'true' == $meta || 'false' == $meta ) {
        $state = $meta;
    } else {
        
        if ( 'page' == $post_type ) {
            $state = get_theme_mod( 'wi_page_share', 'true' );
        } else {
            $state = get_theme_mod( 'wi_single_share', 'true' );
        }
        
    }
    
    $state = apply_filters( 'wi_share_state', $state, $post_type );
    if ( 'false' != $state ) $state = 'true';
    
    if ( 'true' == $state ) wi_share();
    
}
endif;

if ( !function_exists( 'wi_ignore_sticky') ) :
// add_filter( 'pre_get_posts', 'wi_ignore_sticky' );
/**
 * Ignore sticky posts
 * Just another stupid thing
 * @since 1.0
 *
 * Deprecated since 4.0
 *
 */
function wi_ignore_sticky( $query ) {
    
    if ( is_home() && $query->is_main_query())  {
        
        $query->set('ignore_sticky_posts', true);  
        $query->set('post__not_in', get_option('sticky_posts'));
        
    }
    
    return $query;
}
endif;

/**
 * Excerpt Length
 * @since 1.0
 *
 * Deprecated since 4.0 while we use slice method to get the excerpt
 */
if ( !function_exists('wi_custom_excerpt_length') ) {
function wi_custom_excerpt_length( $length ) {
	$excerpt_length = absint(get_theme_mod('fox_excerpt_length')) ? absint(get_theme_mod('fox_excerpt_length')) : 55;
    return $excerpt_length;
}
}
// deprecated since 4.0
// add_filter( 'excerpt_length', 'wi_custom_excerpt_length', 999 );

if (!function_exists('wi_search_filter')) {
function wi_search_filter($query) {
    if (get_theme_mod('fox_exclude_pages_from_search')){
        if ( $query->is_search && is_search() ) {
            $query->set('post_type', 'post');
        }
    }
    return $query;
    }
}

if ( ! function_exists( 'wi_autoload_post_navigation' ) ) :
/**
 * The Post Navigation in autoload mode
 *
 * @since 2.9
 */
function wi_autoload_post_navigation() {
    
    if ( ! wi_autoload() ) return; ?>
<div class="autoload-nav">
    <div class="container">
        
        <?php the_post_navigation(); ?>
        
    </div>
</div><!-- .autoload-nav -->
<?php }
endif;
    
if ( ! function_exists( 'wi_post_navigation' ) ) :
/**
 * The Post Navigation
 *
 * @since 2.9
 */
function wi_post_navigation() {
    
    if ( wi_autoload() ) return;
    
    $show_nav = get_post_meta( get_the_ID(), '_wi_post_navigation', true );
    if ( ! $show_nav ) $show_nav = get_theme_mod( 'wi_single_post_navigation', 'true' );
    if ( 'true' !== $show_nav ) return;
                                 
?>

<div class="wi-post-navigation">

    <?php the_post_navigation( array(
            'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Post', 'wi' ) . '<i class="fa fa-caret-right"></i></span> ' .
                '<span class="screen-reader-text">' . __( 'Next Post:', 'wi' ) . '</span> ' .
                '<span class="post-title">%title</span>',
            'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fa fa-caret-left"></i>' . __( 'Previous Post', 'wi' ) . '</span> ' .
                '<span class="screen-reader-text">' . __( 'Previous Post:', 'wi' ) . '</span> ' .
                '<span class="post-title">%title</span>',
        ) ); ?>

</div><!-- .wi-post-navigation -->
    <?php
}
endif;

if ( ! function_exists( 'wi_ad_spot' ) ) :
/**
 * Ad Spot
 * @since 2.8
 */
function wi_ad_spot( $spot = 'header' ) {
    
    $ad_type = get_theme_mod( 'wi_' . $spot . '_ad_type', 'image' );
    if ( 'code' === $ad_type ) {
        $ad_code = trim( get_theme_mod( 'wi_' . $spot . '_ad_code' ) );
        if ( $ad_code ) {
            echo '<div class="wi-' . $spot . '-ad wi-ad wi-ad-code">' . $header_ad_code . '</div>';
        }
    } else {

        $ad_desktop = get_theme_mod( 'wi_' . $spot . '_ad_desktop' );
        $ad_tablet = get_theme_mod( 'wi_' . $spot . '_ad_tablet' );
        if ( ! $ad_tablet ) $ad_tablet = $ad_desktop;
        $ad_phone = get_theme_mod( 'wi_' . $spot . '_ad_phone' );
        if ( ! $ad_phone ) $ad_phone = $ad_tablet;
        
        $ad_url = trim( get_theme_mod( 'wi_' . $spot . '_ad_url' ) );
        $ad_url_target = get_theme_mod( 'wi_' . $spot . '_ad_url_target', '_blank' );
        if ( '_self' !== $ad_url_target ) $ad_url_target = '_blank';
        
        if ( $ad_desktop ) {
        ?>

<div class="<?php echo esc_attr( 'wi-ad-banner ' . $spot . '-ad ' . $spot . '-ad-banner' ); ?>">
    
    <?php if ( $ad_url ) { ?>
    <a href="<?php echo esc_url( $ad_url ); ?>" target="<?php echo esc_attr( $ad_url_target ); ?>">
    <?php } ?>
        
        <div class="show_on_desktop">
            <img src="<?php echo esc_url( $ad_desktop ); ?>" alt="<?php echo esc_html__( 'Banner', 'wi' ); ?>" />
        </div>
        
        <div class="show_on_tablet_landscape">
            <img src="<?php echo esc_url( $ad_desktop ); ?>" alt="<?php echo esc_html__( 'Banner', 'wi' ); ?>" />
        </div>
        
        <div class="show_on_tablet_portrait">
            <img src="<?php echo esc_url( $ad_tablet ); ?>" alt="<?php echo esc_html__( 'Banner Tablet', 'wi' ); ?>" />
        </div>
        
        <div class="show_on_phone">
            <img src="<?php echo esc_url( $ad_phone ); ?>" alt="<?php echo esc_html__( 'Banner Phone', 'wi' ); ?>" />
        </div>
        
    <?php if ( $ad_url ) { ?>
    </a>
    <?php } ?>    
        
</div>
        
        <?php
        }
    
    }
    
}
endif;

if ( ! function_exists( 'wi_content_column' ) ) :
/**
 * Returns 1 or 2
 *
 * @since 3.0
 */
function wi_content_column() {
    
    // changed since 3.0
    $column = get_post_meta( get_the_ID(), '_wi_column_layout', true );

    if ( 'single-column' == $column ) {
        $column = '1';
    } elseif ( 'two-column' == $column ) {
        $column = '2';
    } else {
        $column = get_theme_mod( 'wi_blog_content_column', '1' );
    }
    if ( '2' != $column ) $column = '1';
    
    return $column;
    
}
endif;

/* COOL POST
------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'wi_is_cool_post' ) ) :
/**
 * Check if we are displaying a cool post
 * @since 2.9
 */
function wi_is_cool_post() {
    
    return get_theme_mod( 'wi_cool_post_all' ) || ( 'true' == get_post_meta( get_the_ID(), '_wi_cool', true ) );
    
}
endif;

// since 2.9
// add_filter( 'body_class', 'wi_single_body_class' );
function wi_single_body_class( $class ) {

    if ( is_singular() ) {
    
        if ( wi_is_cool_post() ) {
            $class[] = 'cool-post';
            
            $cool_thumbnail_size = get_post_meta( get_the_ID(), '_wi_cool_thumbnail_size', true );
            if ( ! $cool_thumbnail_size ) {
                $cool_thumbnail_size = get_theme_mod( 'wi_cool_thumbnail_size', 'big' );
            }
            
            if ( 'full' != $cool_thumbnail_size ) $cool_thumbnail_size = 'big';
            $class[] = 'cool-thumbnail-size-' . $cool_thumbnail_size ;
        
        }
        
        // hero header
        // @since 3.0
        $hero = wi_hero();
        if ( 'full' == $hero || 'half' == $hero ) {
            $class[] = 'post-hero';
        }
        if ( 'full' == $hero ) {
            $class[] = 'post-hero-full';
        } elseif ( 'half' == $hero ) {
            $class[] = 'post-hero-half';
        }
    
    }
    
    return $class;
    
}

// add_filter( 'fox_sidebar_state', 'wi_cool_post_sidebar_state' );
function wi_cool_post_sidebar_state( $state ) {
    
    if ( is_singular() && wi_is_cool_post() ) {
        
        return 'no-sidebar';
            
    }
    
    return $state;
    
}

/* AUTOLOAD NEXT POST
------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'wi_autoload' ) ) :
/**
 * Check if autoload option enabled
 * @since 2.9
 */
function wi_autoload() {
    
    return get_theme_mod( 'wi_autoload_post' );
    
}
endif;

/* RELATED JETPACK
 * deprecated since 4.0
------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'wi_related_jetpack' ) ) :
/**
 * Check if related posts source from Jetpack
 * @since 2.9
 */
function wi_related_jetpack() {
    
    return 'jetpack' === get_theme_mod( 'wi_related_source' ) && class_exists( 'Jetpack_RelatedPosts' ) && method_exists( 'Jetpack_RelatedPosts', 'init_raw' );
    
}
endif;

if ( ! function_exists( 'wi_jetpackme_remove_rp' ) ):
/**
 * Remove Jetpack Related Posts
 *
 * @since 2.9
 */
function wi_jetpackme_remove_rp() {
    
    if ( wi_related_jetpack() ) {
    
        $jprp = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
        remove_filter( 'the_content', $callback, 40 );
        
    }
    
}
// add_filter( 'wp', 'wi_jetpackme_remove_rp', 20 );
endif;

/* -------------------------------------------------------------------- */
/* FEATURED CLASS
 * deprecated
 * this is for what?
/* -------------------------------------------------------------------- */
// add_filter('post_class','wi_post_featured_class');
if (!function_exists('wi_post_featured_class')){
function wi_post_featured_class( $classes ) {
	if (get_post_meta(get_the_ID(),'_is_featured',true) == 'yes'):
        $classes[] = 'post-featured';
    endif;
    return $classes;
}
}

/**
 * Post View Plugin Concerning
 * We don't need this anymore while we now can edit directly the post view plugin
 */
// add_filter( 'pvc_most_viewed_posts_html', 'wi_custom_most_viewed_posts_html', 10, 2 );

/**
 * @since 2.9
 */
function wi_custom_most_viewed_posts_html( $html, $args ) {
    
    $defaults = array(
        'number_of_posts'		 => 5,
        'post_type'				 => array( 'post' ),
        'order'					 => 'desc',
        'thumbnail_size'		 => 'thumbnail',
        'show_post_views'		 => true,
        'show_post_thumbnail'	 => false,
        'show_post_excerpt'		 => false,
        'no_posts_message'		 => esc_html__( 'No Posts', 'wi' ),
        'item_before'			 => '',
        'item_after'			 => ''
    );

    $args = apply_filters( 'pvc_most_viewed_posts_args', wp_parse_args( $args, $defaults ) );

    $args['show_post_views'] = (bool) $args['show_post_views'];
    $args['show_post_thumbnail'] = (bool) $args['show_post_thumbnail'];
    $args['show_post_excerpt'] = (bool) $args['show_post_excerpt'];

    $posts = pvc_get_most_viewed_posts(
    array(
        'posts_per_page' => (isset( $args['number_of_posts'] ) ? (int) $args['number_of_posts'] : $defaults['number_of_posts']),
        'order'			 => (isset( $args['order'] ) ? $args['order'] : $defaults['order']),
        'post_type'		 => (isset( $args['post_type'] ) ? $args['post_type'] : $defaults['post_type'])
    )
    );
    
    $html = '';
    
    global $post;

    if ( ! empty( $posts ) ) {
        $html = '
    <ul>';
        
        $count = 0;

        foreach ( $posts as $post ) {
            setup_postdata( $post );
            $count++;

            $html .= '
        <li>';

            $html .= apply_filters( 'pvc_most_viewed_posts_item_before', $args['item_before'], $post );

            if ( $args['show_post_thumbnail'] ) {
                $html .= '
                <div class="popular-thumbnail-container">' . wi_display_thumbnail( $args['thumbnail_size'] ,'popular-thumbnail',true,true, false, false ) . 
                
                ($args['show_post_views'] ? ' <span class="view-count">' . sprintf( esc_html__( '%s views', 'wi' ), number_format_i18n( pvc_get_post_views( $post->ID ) ) ) . '</span>' : '') .
                
                '<span class="popular-counter">' . sprintf('%02d',$count) . '</span></div>';
            }

            $html .= '
                <h3 class="popular-title"><a href="' . get_permalink( $post->ID ) . '">' . get_the_title( $post->ID ) . '</a></h3>';

            $excerpt = '';

            if ( $args['show_post_excerpt'] ) {
                
                $html .= '

            <div class="popular-excerpt"><p>' . wi_subword(get_the_excerpt(),0,20) . ' &hellip; <a href="' . get_permalink() . '" class="readmore">' . esc_html__( 'More','wi' ) . '</a></p></div>';
                
            }

            $html .= apply_filters( 'pvc_most_viewed_posts_item_after', $args['item_after'], $post );

            $html .= '
        </li>';
        }

        wp_reset_postdata();

        $html .= '
    </ul>';
    } else
        $html = $args['no_posts_message'];

    return $html;

}

function wi_mobile_nav() {
?>
<div id="offcanvas">

    <?php if ( has_nav_menu( 'primary' ) ) { ?>
            
        <nav id="mobilenav" class="offcanvas-nav">

            <?php wp_nav_menu(array(
                'theme_location'	=>	'primary',
                'depth'				=>	4,
                'container_class'	=>	'menu',
                'after' => '<span class="indicator"></span>',
            ));?>

        </nav><!-- #mobilenav -->
    
    <?php } // primary menu
            
    // social icons                          
    if (!get_theme_mod('fox_disable_header_social')): ?>
    <div class="offcanvas-social header-social social-list">
        <ul>
            <?php wi_social_list( false ); ?>
        </ul>
    </div><!-- .social-list -->
    <?php endif; // header-social
                               
    // header search
    if ( ! get_theme_mod( 'wi_disable_header_search' ) ) {
        get_search_form();
    }
    ?>
    
</div><!-- #offcanvas -->

<div id="offcanvas-overlay"></div>
<?php
}

if ( ! function_exists( 'wi_review' ) ) :
/**
 * Single Post Review
 * @since 2.4
 */
function wi_review() {
    
    $review = get_post_meta( get_the_ID(), '_wi_review', true ); if ( ! $review || ! is_array( $review ) ) return;
    $items = '';
    ob_start();
    
    foreach ( $review as $item ) : if ( ! isset( $item[ 'criterion' ] ) || ! isset( $item[ 'score' ] ) || ! $item[ 'criterion' ] || ! $item[ 'score' ] ) continue; ?>

<div class="review-item">

    <div class="review-criterion"><?php echo $item[ 'criterion' ]; ?></div>
    <div class="review-score"><?php echo $item[ 'score' ]; ?><span class="unit">/10</span></div>

</div>

<?php endforeach; ?>

<?php $average = get_post_meta( get_the_ID(), '_wi_review_average', true ); ?>

<?php if ( $average && is_numeric( $average ) ) : ?>

<div class="review-item overall">

    <div class="review-criterion"><?php echo esc_html__( 'Overall', 'wi' ); ?></div>
    <div class="review-score"><?php echo number_format((float)$average, 1, '.', ''); ?><span class="unit">/10</span></div>

</div>

<?php endif; ?>

<?php
    
    $items = trim ( ob_get_clean() );
    if ( ! $items ) return;
    
?>

<div id="review-wrapper">
    
    <h2 id="review-heading"><?php echo esc_html__( 'Review', 'wi' ); ?></h2>
    
    <div id="review">
        
        <?php echo $items ; ?>
        
    </div>
    
    <?php if ( $review_text = get_post_meta( get_the_ID(), '_wi_review_text', true ) ) { ?>
    
    <div class="review-text">
        
        <div class="review-text-inner">
    
            <?php echo do_shortcode( $review_text ); ?>
            
        </div>
    
    </div><!-- .review-text -->
    
    <?php } ?>
    
    <?php 
    $btn1 = get_post_meta( get_the_ID(), '_wi_review_btn1_url', true );
    $btn1_text = trim( get_post_meta( get_the_ID(), '_wi_review_btn1_text', true ) ); if ( ! $btn1_text ) $btn1_text = 'Click Me';
    $btn2 = get_post_meta( get_the_ID(), '_wi_review_btn2_url', true );
    $btn2_text = trim( get_post_meta( get_the_ID(), '_wi_review_btn2_text', true ) ); if ( ! $btn2_text ) $btn2_text = 'Click Me';
    
    if ( $btn1 || $btn2 ) {
    ?>
    <div class="review-buttons">
        
        <?php if ( $btn1 ) { ?>
        <a href="<?php echo esc_url( $btn1 ); ?>" target="_blank" class="wi-btn btn-1"><?php echo $btn1_text; ?></a>
        <?php } ?>
        
        <?php if ( $btn2 ) { ?>
        <a href="<?php echo esc_url( $btn2 ); ?>" target="_blank" class="wi-btn btn-2"><?php echo $btn2_text; ?></a>
        <?php } ?>
    
    </div><!-- .review-buttons -->
    
    <?php } // if btn ?>
    
</div>

<?php
}
endif;

/**
 * Misc Functions
 */
if ( ! function_exists( 'fox_quick_translate' ) ) :
// add_filter( 'gettext','fox_quick_translate', 20 , 3 );
/**
 * Quick Translation Module
 * @since 4.0
 */
function fox_quick_translate( $string, $text,$domain ) {
    
    $options = fox_quick_translation_support();
    
    foreach ( $options as $k => $v ) {
        
        if ( $string == $v ) {
            
            $get = get_theme_mod( 'wi_translate_' .$k );
            if ( '' != $get ) {
                $string = $get;
            }
            
        }
        
    }
    
    return $string;
    
}

endif;

if (!function_exists('wi_facebook_share_picture')) {
function wi_facebook_share_picture(){
    if (is_singular()) {
        global $post;
        if (has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
?>
<meta property="og:image" content="<?php echo esc_url($thumbnail[0]);?>"/>
<meta property="og:image:secure_url" content="<?php echo esc_url($thumbnail[0]);?>" />
<?php }
    }
}
}

if ( ! function_exists( 'wi_get_instagram_photos' ) ) :
/**
 * retrieve instagram photos
 *
 * @since 2.8
 */
function wi_get_instagram_photos( $username, $number, $cache_time ) {

    /**
     * Get Instagram Photos
     * @Scott Evans
     */
    $username = trim( strtolower( $username ) );
    $number = absint( $number );
    $cache_time = absint( $cache_time );

    if ( ! $username ) return;

    if ( $number < 1 || $number > 12 ) $number = 6;

    if ( false === ( $instagram = get_transient( 'wi-instagram-' . sanitize_title_with_dashes( $username . '-' . $number ) ) ) ) {

        switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;

			default:
				$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}

		if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wi' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wi' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'wi' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', $cache_time ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			$instagram = unserialize( base64_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) );

		}
    }

    if ( ! empty( $instagram ) ) {

        return array_slice( $instagram, 0, $number );

    } else {

        return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) );

    }

}
endif;