<?php
if ( ! function_exists( 'fox43_get_sticky_logo_html' ) ) :
/**
 * Get sticky header logo html
 * 
 * since 4.3
 */
function fox43_get_sticky_logo_html() {
    
    $sticky_logo_html = '';
    $sticky_logo_url = get_theme_mod( 'wi_header_sticky_logo' );

    if ( $sticky_logo_url ) {

        $sticky_logo_id = attachment_url_to_postid( $sticky_logo_url );
        if ( $sticky_logo_id ) {
            $sticky_logo_html = wp_get_attachment_image( $sticky_logo_id, 'full', false, [ 'class' => 'sticky-img-logo' ] );
        } else {
            $sticky_logo_html = '<img src="' . esc_url( $sticky_logo_url ) .'" alt="' . esc_html__( 'Sticky Logo', 'wi' ) . '" class="sticky-img-logo" />';
        }

        $sticky_logo_html;

    }
    
    return $sticky_logo_html;
    
}
endif;

if ( ! function_exists( 'fox43_site_branding' ) ) :
/**
 * Site Branding
 * problem 1: display or not the tagline
 * problem 2: <h1> tag
 * problem 3: text/image logo
 * problem 4: sticky logo
 * 
 * since 4.3
 */
function fox43_site_branding( $params = [] ) {
    
    $params = wp_parse_args( $params, [
        'layout' => 'stack1',
        'header_sticky' => true,
    ]);
    
    $layout = $params['layout'];
    
    $class = [
        'wi-logo-main',
        'fox-logo'
    ];
    
    /**
     * logo tag
     * h1 or h2
     * @since 4.0
     */
    $htag = 'h2';
    if ( is_home() ) $htag = 'h1';
    if ( is_page() ) {
        if ( ! fox_show( 'post_header', 'page' ) ) $htag = 'h1';
    } elseif ( is_single() ) {
        if ( ! fox_show( 'post_header' ) ) $htag = 'h1';
    }
    
    /**
     * show description
     */
    $show_description = ( 'true' == get_theme_mod( 'wi_header_slogan', 'false' ) );
    if ( $layout == 'inline' ) {
        $show_description = false;
    }
    
    /**
     * logo type
     */
    $logo_type = get_theme_mod( 'wi_logo_type', 'text' );
    if ( 'image' != $logo_type ) $logo_type = 'text';
    $class[] = 'logo-type-' . $logo_type;
    
    $logo_html = '';
    if ( 'text' == $logo_type ) {
        
        $logo_html = fox_format( '<span class="text-logo">{}</span>', get_bloginfo( 'title' ) );
        
    } else {
        
        $logo_html = '';
        $logo_url = get_theme_mod( 'wi_logo' );
        if ( $logo_url ) {
            $logo_id = attachment_url_to_postid( $logo_url );
            if ( $logo_id ) {
                $logo_html = wp_get_attachment_image( $logo_id, 'full', false, [ 'class' => 'main-img-logo' ] );
            }
        } else {
            $logo_url = get_template_directory_uri() . '/images/logo.png';
        }
        
        if ( ! $logo_html ) {
            
            $logo_html = '<img src="' . esc_url( $logo_url ) .'" alt="' . esc_html__( 'Logo', 'wi' ) . '" class="main-img-logo" />';
            
        }
        
        // sticky header logo
        if ( $params[ 'header_sticky' ] && 'inline' == $layout ) {
            
            $logo_html = fox43_get_sticky_logo_html() . $logo_html;
            
        }
        
    }
    
    /**
     * custom logo url
     * @since 4.0
     */
    $url = get_theme_mod( 'wi_logo_custom_link' );
    if ( ! $url ) {
        $url = home_url( '/' );
    }
    
    ?>

    <div id="logo-area" class="fox-logo-area fox-header-logo site-branding">
        
        <div id="wi-logo" class="fox-logo-container">
            
            <?php echo '<' . $htag . ' class="' . esc_attr( join( ' ',  $class ) ) . '" id="site-logo">'; ?>
                
                <a href="<?php echo esc_url( $url ); ?>" rel="home">
                    
                    <?php echo $logo_html; ?>
                    
                </a>
                
            <?php echo '</' . $htag . '>'; ?>

        </div><!-- .fox-logo-container -->

        <?php if ( $show_description ) fox43_site_description(); ?>

    </div><!-- #logo-area -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox43_site_description' ) ) :
/**
 * Site Tagline
 * 
 * since 4.3
 */
function fox43_site_description() {
    
    $class = [ 'slogan', 'site-description' ];
    ?>  
    <h3 class="<?php echo esc_attr( join( ' ', $class ) ); ?>"><?php bloginfo('description');?></h3>
    <?php
}
endif;

/**
 * Navigation Mega Item
 *
 * @since 4.3
 */
add_action( 'wp_ajax_nav_item_mega', 'fox43_fetch_tax_item_data' );
add_action( 'wp_ajax_nopriv_nav_item_mega', 'fox43_fetch_tax_item_data' );

function fox43_fetch_tax_item_data( ) {
    
    $nonce = isset( $_POST[ 'nonce' ] ) ? $_POST[ 'nonce' ] : '';
    
    // Verify nonce field passed from javascript code
    if ( ! wp_verify_nonce( $nonce, 'nav_mega_nonce' ) )
        die ( 'Busted!');
    
    $item_id = isset( $_POST[ 'item_id' ] ) ? $_POST[ 'item_id' ] : [];
    
    $tax_id = get_post_meta( $item_id, '_menu_item_object_id', true );
    if ( ! $tax_id ) {
        return;
    }
    
    $args = [
        'post_type'             => 'post',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page'        => 3,
        'no_found_row'          => true,
        'cat'                   => $tax_id,
    ];
    
    $query = new WP_Query( $args );
    
    $json = [];
    
    if ( $query->have_posts() ) {
        
        // echo '<ul class="sub-menu submenu-display-items">';
        
        while( $query->have_posts() ) {
            
            $query->the_post();
            
            $item_data = [
                'thumbnail' => '',
                'title' => '',
            ];
            
            ob_start();
            fox43_thumbnail([
                'show_thumbnail' => true,
                'thumbnail' => get_theme_mod( 'wi_blog_grid_thumbnail', 'landscape' ),
                'thumbnail_custom' => false,
                'thumbnail_extra_class' => 'post-nav-item-thumbnail',
                'thumbnail_format_indicator' => true,
                'thumbnail_view' => false,
                'thumbnail_index' => false,
                'thumbnail_review_score' => false,
                'thumbnail_hover' => 'none',
                'thumbnail_placeholder' => true,
                'thumbnail_showing_effect' => 'none',
                'thumbnail_shape' => 'acute',
            ]);
            
            $item_data[ 'thumbnail' ] = ob_get_clean();
            
            ob_start();
            
            fox_post_title([ 'extra_class' => 'post-nav-item-title', 'tag' => 'h3', ]);
            
            $item_data[ 'title' ] = ob_get_clean();
            
            $json[] = $item_data;
            
        }
        
    }
    
    wp_reset_query();
    
    echo json_encode( $json );
    die();
    
}