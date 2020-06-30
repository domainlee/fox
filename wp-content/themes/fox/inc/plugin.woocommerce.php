<?php
if ( ! function_exists('withemes_woocommerce_installed') ) :
function withemes_woocommerce_installed() {
    return class_exists( 'WooCommerce' );
}
endif;

if ( !class_exists( 'Withemes_WooCommerce' ) ) :
/**
 * WooCommerce class
 *
 * @since 2.4
 */
class Withemes_WooCommerce
{   
    
    /**
	 * Construct
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of class
	 *
	 * @since 2.4
	 */
	private static $instance;

	/**
	 * Instantiate or return the one class instance
	 *
	 * @since 2.4
	 *
	 * @return class
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
     * @since 2.4
     */
    public function init() {
        
        // .container wrapper
        add_action('woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10);
        add_action('woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10);
        
        add_action( 'woocommerce_before_shop_loop_item', array( $this, 'content_product_thumbnail_open' ), 9 );
        add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'content_product_thumbnail_close' ), 12 );
        
        // Add to cart button
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
        add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 11 );
        
        // Sale Flash
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
        add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 14 );
        
        // Custom title
        remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        add_action( 'woocommerce_shop_loop_item_title', array( $this, 'content_product_title' ), 10 );
        
        
        // single images markup
        add_filter( 'woocommerce_product_thumbnails_columns', function( $column ) {
            return 4;
        } ) ;
        add_filter( 'woocommerce_single_product_image_html', array( $this, 'single_product_image_html' ), 10, 2 );
        add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'single_product_image_thumbnail_html' ), 10, 2 );
        
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
        add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 14 );
        
        // WooCommerce Options
        add_filter( 'loop_shop_per_page', array( $this, 'products_per_page' ), 20 );
        add_filter('loop_shop_columns', array( $this, 'loop_columns' ), 999 );
        
        // Body Class
        if ( withemes_woocommerce_installed() ) {
            
            // WooCommerce Options
            // add_filter( 'fox_panels', array( $this, 'panels' ) );
            add_filter( 'fox_sections', array( $this, 'sections' ) );
            add_filter( 'fox_options', array( $this, 'options' ) );
            
            add_filter( 'fox_heading_selector', [ $this, 'heading_from_woocommerce' ] );
            
            add_action( 'widgets_init', [ $this, 'register_shop_sidebar' ], 1000 );
            
        }
        
        add_filter( 'body_class', array( $this, 'body_class' ) );
        
        // Header Cart
        add_action( 'wp_footer', array( $this, 'header_cart' ) ); // removed since 4.4 // restored since 4.4.0.1
        add_filter('woocommerce_add_to_cart_fragments', array( $this, 'header_add_to_cart_fragment' ) );
        
        // single thumbnail class
        // @since 4.0.2
        add_filter( 'woocommerce_single_product_image_gallery_classes', [ $this, 'woocommerce_single_product_image_gallery_classes' ] );
        add_filter( 'fox_sidebar_state', [ $this, 'sidebar_state' ] );
        
        // @since 4.0.2
        // while we have manual single title
        add_filter( 'woocommerce_show_page_title', '__return_false' );
        
        /**
         * since 4.3
         */
        add_filter( 'woocommerce_output_related_products_args', function( $args ) {
            $args['posts_per_page'] = get_theme_mod( 'wi_shop_column', 3 );
            $args['columns'] = get_theme_mod( 'wi_shop_column', 3 );
            return $args;
        }, 20 ) ;
        
    }
    
    function sidebar_state( $state ) {
        
        $shop_sidebar_state = get_theme_mod( 'wi_shop_sidebar_state', 'no-sidebar' );
        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            if ( is_single() ) {
                return 'no-sidebar';
            } else {
                return $shop_sidebar_state;
            }
        }
        
        return $state;
        
    }
    
    function register_shop_sidebar() {
    
        register_sidebar( array(
            'name'          => 'Shop Sidebar',
            'id'            => 'shop',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title"><span>',
            'after_title'   => '</span></h3>',
        ) );
        
    }
    
    function woocommerce_single_product_image_gallery_classes( $cl ) {
        
        $cl[] = 'fox-lightbox-gallery';
        return $cl;
        
    }
    
    function heading_from_woocommerce( $selector ) {
        
        $selector .= ',.woocommerce span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce a.added_to_cart, .woocommerce nav.woocommerce-pagination ul, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce #reviews #comments ol.commentlist li .comment-text p.meta, .woocommerce table.shop_table th, .woocommerce table.shop_table td.product-name a';
        
        return $selector;
        
    }
    
    function content_product_thumbnail_open() {
    
        echo '<div class="product-thumbnail"><div class="product-thumbnail-inner">';
        
    }
    
    function content_product_thumbnail_close() {
        
        echo '</div></div>';
        
    }
    
    function content_product_title() {
        
        echo '<h3 class="product-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h3>';
        
    }
    
    /**
     * Wrapper start
     *
     * @since 2.4
     */
    function wrapper_start() {
        
        echo '<div class="container">';
    
    }
    
    /**
     * Wrapper End
     *
     * @since 2.4
     */
    function wrapper_end() {
        
        echo '</div>';
    
    }
    
    /**
     * Single Product Image HTML
     *
     * We just wanna remove zoom class to replace it by iLightbox class
     *
     * @since 2.4
     */
    function single_product_image_html( $html, $post_id ) {
        
        global $post;
        
        $attachment_id    = get_post_thumbnail_id();
        $props            = wc_get_product_attachment_props( $attachment_id, $post );
        $image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
            'title'	 => $props['title'],
            'alt'    => $props['alt'],
        ) );
        
        // lightbox options
        $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
        $full_src = wp_get_attachment_image_src( $attachment_id, 'full' );
        $image_options = 'thumbnail:\'' . $thumbnail_src[0] . '\', width: ' . $full_src[1] . ', height:' . $full_src[2];
        
        $html = sprintf( 
            '<a href="%s" itemprop="image" class="woocommerce-main-image fox-lightbox-gallery-item" title="%s" data-options="%s" rel="shop-thumbnail">%s</a>', 
            $props['url'], 
            $props['caption'],
            $image_options,
            $image 
        );
        
        return $html;
    
    }
    
    /**
     * Single Thumbnails HTML
     *
     * We just wanna remove zoom class to replace it by iLightbox class
     *
     * @since 2.4
     */
    function single_product_image_thumbnail_html( $html, $attachment_id ) {
        
        $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
		$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
        $image_title     = get_post_field( 'post_excerpt', $attachment_id );
		$attributes = array(
			'title'                   => $image_title,
			'data-src'                => $full_size_image[0],
			'data-large_image'        => $full_size_image[0],
			'data-large_image_width'  => $full_size_image[1],
			'data-large_image_height' => $full_size_image[2]
		);
        
        $image_options = 'thumbnail:\'' . $thumbnail[0] . '\', width: ' . $full_size_image[1] . ', height:' . $full_size_image[2];
        
		$html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '" class="fox-lightbox-gallery-item" data-options=" ' . esc_attr( $image_options ) . '" rel="shop-thumbnail">';
		$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
 		$html .= '</a></div>';
		
        return $html;
    
    }
    
    /**
     * Custom number of products per page
     *
     * @since 2.4
     */
    function products_per_page( $ppp ) {
        
        $custom_ppp = absint( get_theme_mod( 'wi_products_per_page' ) );
        if ( $custom_ppp > 0 ) return $custom_ppp;
        return $ppp;
        
    }
    
    /**
     * Custom shop column
     *
     * @since 2.4
     */
    function loop_columns() {
        $column = get_theme_mod( 'wi_shop_column' );
        if ( '2' != $column && '4' != $column ) $column = '3';
		return absint( $column );
	}
    
    /**
     * Panels
     *
     * @since 2.4
     */
    function panels( $panels ) {
        
        return $panels;
    
    }
    
    /**
     * Options
     *
     * @since 2.4
     */
    function options( $options ) {
        
        $options[ 'products_per_page' ] = array(
            'name' => esc_html__( 'Custom number of products per page', 'wi' ),
            'type' => 'text',
            
            'section' => 'woocommerce',
            'section_title' => 'Fox Options',
            'panel' => 'woocommerce',
            'panel_prefix' => false,
        );
        
        $options[ 'shop_column' ] = array(
            'name' => esc_html__( 'Default Catalog Column Layout', 'wi' ),
            'type' => 'radio',
            'options' => array(
                '2' => esc_html__( '2 Columns', 'wi' ),
                '3' => esc_html__( '3 Columns', 'wi' ),
                '4' => esc_html__( '4 Columns', 'wi' ),
            ),
            'std' => '4',
            'section' => 'woocommerce',
            
            'hint' =>  'Shop column',
        );
        
        $options[ 'header_cart' ] = array(
            'name' => esc_html__( 'Cart icon', 'wi' ),
            'shorthand' => 'enable',
            'std' => 'true',
            'section' => 'woocommerce',
            
            'hint' =>  'Header cart',
        );
        
        $options[ 'header_cart_position' ] = array(
            'name' => 'Header cart position',
            'std' => 'header',
            'options' => [
                'header' => 'On header',
                'side' => 'On the side',
            ],
            'type' => 'select',
            'section' => 'woocommerce',
        );
        
        $options[ 'shop_sidebar_state' ] = array(
            'name' => 'Shop Sidebar',
            'type' => 'select',
            'options'   => [
                'sidebar-left'  => 'Sidebar Left',
                'sidebar-right' => 'Sidebar Right',
                'no-sidebar'    => 'No Sidebar',
            ],
            'std' => 'no-sidebar',
            'section' => 'woocommerce',
            
            'hint' =>  'Shop sidebar',
        );
        
        return $options;
    
    }
    
    /**
     * Classes
     *
     * @since 2.4
     */
    function body_class( $classes ) {
    
        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            $key = array_search( 'sidebar-left', $classes );
            unset( $classes[ $key ] );
            $key = array_search( 'sidebar-right', $classes );
            unset( $classes[ $key ] );
            
            $column = get_theme_mod( 'wi_shop_column' );
            if ( '2' != $column && '4' != $column ) $column = '3';
            $classes[] = 'columns-' . $column;
        }
        
        return $classes;
        
    }
    
    /**
     * Header Car
     *
     * @since 2.4
     * deprecated since 4.4
     */
    function header_cart() {
        
        if ( class_exists( 'WooCommerce' ) && 'false' != get_theme_mod( 'wi_header_cart' ) && 'side' == get_theme_mod( 'wi_header_cart_position', 'header' ) ) :
        
        global $woocommerce;
        
        fox_woocommerce_side_cart();
        
        endif;
        
    }
    
    function header_add_to_cart_fragment( $fragments ) {
    
        $fragments['.header-cart-icon'] = fox_woocommerce_header_cart();
        $fragments['.header-cart'] = fox_woocommerce_side_cart();
        
        return $fragments;
    
    }
    
}

Withemes_WooCommerce::instance()->init();

endif;

if ( ! function_exists( 'fox_shop_header' ) ) :
/**
 * Shop Header
 * @since 4.0.2
 */
function fox_shop_header() {
    
    // since 4.3
    if ( is_product() ) {
        return;
    }
    
?>
    <header class="single-header post-header entry-header single-big-section" itemscope itemtype="https://schema.org/WPHeader">
    
        <div class="container">
            
            <div class="post-item-header align-center">

                <h1 class="post-item-title wi-post-title fox-post-title post-header-section post-title size-medium"><?php woocommerce_page_title(); ?></h1>
                
                <?php do_action( 'woocommerce_archive_description' ); ?>
                
            </div>

        </div><!-- .container -->

    </header><!-- .single-header -->
<?php
}
endif;

if ( ! function_exists( 'fox_woocommerce_header_cart' ) ) :
/**
 * Header Cart
 * @since 4.4
 */
function fox_woocommerce_header_cart() {
    
    if ( 'true' !== get_theme_mod( 'wi_header_cart', 'true' ) ) {
        return;
    }
    if ( 'side' == get_theme_mod( 'wi_header_cart_position', 'header' ) ) {
        return;
    }
    
    if ( ! withemes_woocommerce_installed() ) {
        return;
    }
    
    global $woocommerce;
    
    $count = $woocommerce->cart->get_cart_contents_count();
    $url = wc_get_cart_url();
    
    $cart = fox_format( 
        '<div class="header-cart-icon"><a href="{url}"><span class="num">{count}</span><i class="feather-shopping-cart"></i></a></div>',
        [
            'count' => $count,
            'url' => $url,
        ]
    );
    
    return apply_filters( 'fox_header_cart', $cart );
    
}
endif;

if ( ! function_exists( 'fox_woocommerce_side_cart' ) ) :
/**
 * Side Cart
 * @since 4.4.0.1
 */
function fox_woocommerce_side_cart() {
    
    global $woocommerce;
    
    $hide_class = ' header-cart-hidden';

    $count = $woocommerce->cart->get_cart_contents_count(); if ( $count > 0 ) : $hide_class = ''; endif; ?>

    <div class="header-cart<?php echo esc_attr( $hide_class ); ?>" id="header-cart">

        <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html_e( 'View your shopping cart', 'wi' ); ?>">

            <span class="items-count">

                <i class="fa fa-shopping-cart"></i> <?php echo $count; ?>

            </span>

            <span class="items-total">

                <?php echo $woocommerce->cart->get_cart_total(); ?>

            </span>

        </a>

    </div><!-- .header-cart -->
    <?php
    
}
endif;

/**
 * add the cart to mobile header
 * @since 4.4
 */
add_action( 'fox_header_mobile_right', 'fox_add_cart_to_mobile_header' );
function fox_add_cart_to_mobile_header () {
    
    echo fox_woocommerce_header_cart();
    
}