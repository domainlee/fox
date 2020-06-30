<?php
if ( ! function_exists( 'fox_header_logo' ) ) :
/**
 * Header Logo
 * since 4.0
 */
function fox_header_logo() {
    
    /* h1, h2 tag for the logo - since 4.0 */
    $htag = 'h2';
    if ( is_home() ) $htag = 'h1';
    if ( is_page() ) {
        if ( ! fox_show( 'post_header', 'page' ) ) $htag = 'h1';
    } elseif ( is_single() ) {
        
        if ( ! fox_show( 'post_header' ) ) $htag = 'h1';
        
    }
    
    $class = [
        'wi-logo-main',
        'fox-logo'
    ];
    
    $logo_type = get_theme_mod( 'wi_logo_type', 'text' );
    if ( 'image' != $logo_type ) $logo_type = 'text';
    $class[] = 'logo-type-' . $logo_type;
    
    // since 4.0
    $url = get_theme_mod( 'wi_logo_custom_link' );
    if ( ! $url ) {
        $url = home_url( '/' );
    }
    
    ?>

    <div id="logo-area" class="fox-logo-area fox-header-logo">
        
        <div id="wi-logo" class="fox-logo-container">
            
            <?php echo '<' . $htag . ' class="' . esc_attr( join( ' ',  $class ) ) . '" id="site-logo">'; ?>
                
                <a href="<?php echo esc_url( $url ); ?>" rel="home">
                    
                    <?php if ( 'text' == $logo_type ) { ?>
                    
                    <span class="text-logo"><?php bloginfo( 'title' ); ?></span>
                    
                    <?php } else { 
        
                        $logo = get_theme_mod( 'wi_logo' );
                        if ( ! $logo ) {
                            $logo = get_template_directory_uri() . '/images/logo.png';
                            $logo_retina = get_template_directory_uri() . '/images/logo@2x.png';
                        } else {
                            $logo_retina = get_theme_mod( 'wi_logo_retina' );
                        }
        
                    ?>
                    
                    <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_html( 'Logo', 'wi' ); ?>" <?php if ( $logo_retina ) echo ' data-retina="' . $logo_retina . '"'; ?> />
                    
                    <?php } // logo type ?>
                    
                </a>
                
            <?php echo '</' . $htag . '>'; ?>

        </div><!-- .fox-logo-container -->

        <?php fox_site_description(); ?>

    </div><!-- #logo-area -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox_min_logo' ) ) :
/**
 * Minimal Logo
 * since 4.0
 */
function fox_min_logo() {
    
    if ( 'true' != get_theme_mod( 'wi_min_logo', 'true' ) ) return;
    
    $class = [ 'minimal-logo', 'min-logo' ];
    $html = '';
    $type = get_theme_mod( 'wi_min_logo_type', 'text' );
    
    if ( 'text' == $type ) {
        
        $class[] = 'min-logo-text';
        $html = '<span class="text-logo min-text-logo">' . get_bloginfo( 'name' ) . '</span>';
        
    } else {
        
        $class[] = 'min-logo-image';
        
        $logo_minimal = get_theme_mod( 'wi_logo_minimal' );
        $logo_white = get_theme_mod( 'wi_logo_minimal_white' );
        
        if ( $logo_minimal ) {
            $html .= '<img src="' . esc_attr( $logo_minimal ) . '" alt="Minimal Logo" class="minimal-logo-img" />';
        }
        if ( $logo_white ) {
            $html .= '<img src="' . esc_attr( $logo_white ) . '" alt="Minimal White Logo" class="minimal-logo-img-white" />';
        }
        
    }
    ?>

    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">

            <?php echo $html; ?>

        </a>

    </div><!-- .minimal-logo -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox_site_description' ) ) :
/**
 * Site Slogan
 * since 4.0
 */
function fox_site_description() {
    
    if ( 'true' != get_theme_mod( 'wi_header_slogan', 'false' ) ) return;
    
    $class = [ 'slogan', 'site-description' ];
    ?>  
    <h3 class="<?php echo esc_attr( join( ' ', $class ) ); ?>"><?php bloginfo('description');?></h3>

<?php
    
}
endif;

if ( ! function_exists( 'fox_header_nav' ) ) :
/**
 * Header Nav
 * since 4.0
 */
function fox_header_nav() {
    
    if ( 'true' != get_theme_mod( 'wi_header_nav', 'true' ) ) return;
    
    $indicator_content = get_theme_mod( 'wi_nav_has_children_indicator_content', 'angle-down' );
    $container_class = [ 'menu', 'style-indicator-' . $indicator_content ];
    
    if ( has_nav_menu('primary') ): ?>

    <nav id="wi-mainnav" class="navigation-ele wi-mainnav" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        
        <?php wp_nav_menu(array(
            'theme_location'	=>	'primary',
            'depth'				=>	3,
            'container_class'	=>	join( ' ', $container_class ),
        ));?>
        
    </nav><!-- #wi-mainnav -->

    <?php else: ?>

    <?php echo '<div id="wi-mainnav"><em class="no-menu">'.sprintf(__('Go to <a href="%s">Appearance > Menu</a> to set "Primary Menu"','wi'),get_admin_url('','nav-menus.php')).'</em></div>'; ?>

    <?php endif;
    
}
endif;

if ( ! function_exists( 'fox_header_social' ) ) :
/**
 * Header Social Icons
 * since 4.0
 */
function fox_header_social() {

    // size since 4.3
    $size = get_theme_mod( 'wi_header_social_size', 'medium' );
    
    // style since 4.3
    $style = get_theme_mod( 'wi_header_social_style', 'plain' );
    
    fox_social_icons([
        'extra_class' => 'header-social',
        'style' => $style,
        'size'  => $size,
    ]);

}
endif;

if ( ! function_exists( 'fox_header_search' ) ) :
/**
 * Header Search
 * since 4.0
 */
function fox_header_search() {
    
    $style = get_theme_mod( 'wi_header_search_style', 'classic' );
    if ( 'modal' != $style ) $style = 'classic';
    
    $class = [
        'header-search-wrapper',
        'header-search-' . $style
    ];
    
if ( 'classic' == $style ) :
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <span class="search-btn-classic search-btn">
        <i class="feather-search"></i>
    </span>
    
    <div class="header-search-form header-search-form-template">
        
        <div class="container">
    
            <?php get_search_form(); ?>
            
        </div><!-- .header-search-form -->
    
    </div><!-- #header-search -->
    
</div><!-- .header-search-wrapper -->

<?php else : // MODAL SEARCH ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <span class="search-btn search-btn-modal">
        <i class="feather-search"></i>
    </span>
    
    <div class="modal-search-wrapper">
        
        <div class="container">
            
            <div class="modal-search-container">
    
                <?php get_search_form(); ?>
                
                <?php fox_search_nav(); ?>
                
            </div><!-- .modal-search-container -->
            
        </div><!-- .header-search-form -->
        
        <span class="close-modal"><i class="feather-x"></i></span>
    
    </div><!-- .modal-search-wrapper -->
    
</div><!-- .header-search-wrapper -->

<?php
    
    endif; // header search style

}
endif;

if ( ! function_exists( 'fox_search_nav' ) ) :
/**
 * Search Navigation
 * @since 4.0
 */
function fox_search_nav() {
    
    if ( has_nav_menu( 'search-menu' ) ) { ?>

    <h3 class="search-nav-heading small-heading"><?php echo esc_html__( 'Suggestions', 'wi' ); ?></h3>

    <nav id="search-menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        
        <?php wp_nav_menu( array (
            'theme_location'	=>	'search-menu',
            'depth'				=>	1,
            'container_class'	=>	'menu',
        ) ); ?>
        
    </nav><!-- #search-menu -->

    <?php }
    
}
endif;

add_action( 'wp_ajax_nav_mega', 'fox_fetch_tax_mega_items' );
add_action( 'wp_ajax_nopriv_nav_mega', 'fox_fetch_tax_mega_items' );

if ( ! function_exists( 'fox_fetch_tax_mega_items' ) ) :
/**
 * Fetch Tax Mega Items
 * @since 4.0
 */
function fox_fetch_tax_mega_items() {
    
    $nonce = isset( $_POST[ 'nonce' ] ) ? $_POST[ 'nonce' ] : '';
    
    // Verify nonce field passed from javascript code
    if ( ! wp_verify_nonce( $nonce, 'nav_mega_nonce' ) )
        die ( 'Busted!');
    
    $items = isset( $_POST[ 'items' ] ) ? $_POST[ 'items' ] : [];
    $results = [];
    foreach ( $items as $key => $item_id ) {
    
        if ( ! $item_id ) {
            $results[ $key ] = '';
            continue;
        }
        $tax_id = get_post_meta( $item_id, '_menu_item_object_id', true );
        if ( ! $tax_id ) {
            $results[ $key ] = '';
            continue;
        }
        
        $args = array(
            'post_type'             => 'post',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => 3,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'category',
                    'field'         => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms'         => $tax_id,
                    'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                ),
            )
        );
        
        $query = new WP_Query( $args );
        
        ob_start();
        
        if ( $query->have_posts() ) : ?>

            <ul class="sub-menu submenu-display-items">
            
            <?php while( $query->have_posts() ) : $query->the_post(); ?>
                
                <li class="menu-item post-nav-item">
                    
                    <article <?php post_class(['wi-post', 'post-item', 'post-nav-item-inner' ]); ?> itemscope itemtype="https://schema.org/CreativeWork">
                        
                        <?php fox_thumbnail([
                                'thumbnail' => 'landscape',
                                'thumbnail_custom' => true,
                                'extra_class' => 'post-nav-item-thumbnail',
                                'disable_lazyload' => true,
                            ]); ?>
                        
                        <div class="post-nav-item-text">
                            
                            <?php fox_post_title([ 'extra_class' => 'post-nav-item-title', 'tag' => 'h3', ]); ?>
                            
                        </div><!-- .post-nav-item-text -->
                    
                    </article><!-- .post-nav-item-inner -->
                    
                </li><!-- .post-nav-item.menu-item -->
            
            <?php endwhile; ?>
                
                <span class="caret"></span>
                
            </ul><!-- .sub-menu -->
            
        <?php endif; // have_posts
        
        wp_reset_query();
        
        $results[ $key ] = ob_get_clean();
    
    }
    
    echo json_encode( $results );
    die();
    
}

endif;

if ( ! function_exists( 'fox_classic_main_header' ) ) :
/**
 * Classic Main Header
 * @since 4.0
 */
function fox_classic_main_header( $extra_class = '' ) {
    ?>

<div class="main-header classic-main-header header-row header-sticky-element <?php echo esc_attr( $extra_class ); ?>">
    
    <div id="topbar-wrapper">
        
        <div id="wi-topbar" class="wi-topbar">
        
            <div class="container">
                
                <?php if ( 'false' != get_theme_mod( 'wi_header_hamburger', 'false' ) ) { ?>

                <div class="header-element header-element-hamburger-btn">

                    <?php fox_hamburger_btn(); ?>

                </div><!-- .header-element-search -->

                <?php } ?>

                <?php if ( 'false' != get_theme_mod( 'wi_header_nav', 'true' ) ) { ?>
                
                <div class="header-element header-element-nav">

                    <?php fox_header_nav(); ?>

                </div><!-- .header-element-nav -->
                
                <?php } ?>

                <?php if ( 'false' != get_theme_mod( 'wi_header_social', 'true' ) ) { ?>

                <div class="header-element header-element-social">

                    <?php fox_header_social(); ?>

                </div><!-- .header-element-social -->

                <?php } ?>

                <?php if ( 'false' != get_theme_mod( 'wi_header_search', 'true' ) ) { ?>

                <div class="header-element header-element-search">

                    <?php fox_header_search(); ?>

                </div><!-- .header-element-search -->

                <?php } ?>

            </div><!-- .container -->
            
        </div><!-- #wi-topbar -->
        
    </div><!-- #topbar-wrapper -->

</div><!-- .main-header -->
<?php
    
}
endif;

/**
 * Above header
 *
 * @since 4.0
 */
add_action( 'fox_before_header', 'fox_before_header_sidebar' );
function fox_before_header_sidebar() {
    
    $show_on = get_theme_mod( 'wi_before_header_sidebar', 'all' );
    $show_on = explode( ',', $show_on );
    
    $show = ( in_array( 'all', $show_on ) ) ||
        ( in_array( 'home', $show_on ) && is_home() ) ||
        ( in_array( 'archive', $show_on ) && is_archive() ) ||
        ( in_array( 'post', $show_on ) && is_singular( 'post' ) ) ||
        ( in_array( 'page', $show_on ) && is_page() );
    
    $show = apply_filters( 'show_before_header_sidebar', $show );
    if ( ! $show ) return;
    
    $container = get_theme_mod( 'wi_before_header_container', 'true' );
    
    $align = get_theme_mod( 'wi_before_header_align', 'center' );
    if ( 'left' != $align && 'right' != $align ) $align = 'center';
    
    $class = [
        'widget-area',
        'header-sidebar',
        'wide-sidebar',
        'header-row',
        'before-header'
    ];
    $class[] = 'align-' . $align;
    
    if ( 'before-header' == fox_get_sticky_header_element() ) $class[] = 'header-sticky-element';
    
    /**
     * Before header sidebar
     */
    if ( is_active_sidebar( 'before-header' ) ) { ?>

    <div id="before-header" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <?php if ( 'true' == $container ) echo '<div class="container">' ?>

        <?php dynamic_sidebar( 'before-header' ); ?>
        
        <?php if ( 'true' == $container ) echo '</div><!-- .container -->' ?>

    </div><!-- .widget-area -->

    <?php }
    
}

/**
 * Below header
 *
 * @since 4.0
 */
add_action( 'fox_after_header', 'fox_after_header_sidebar' );
function fox_after_header_sidebar() {
    
    $show_on = get_theme_mod( 'wi_after_header_sidebar', 'all' );
    $show_on = explode( ',', $show_on );
    
    $show = ( in_array( 'all', $show_on ) ) ||
        ( in_array( 'home', $show_on ) && is_home() ) ||
        ( in_array( 'archive', $show_on ) && is_archive() ) ||
        ( in_array( 'post', $show_on ) && is_singular( 'post' ) ) ||
        ( in_array( 'page', $show_on ) && is_page() );
    
    $show = apply_filters( 'show_after_header_sidebar', $show );
    if ( ! $show ) return;
    
    $container = get_theme_mod( 'wi_after_header_container', 'true' );
    
    $align = get_theme_mod( 'wi_after_header_align', 'center' );
    if ( 'left' != $align && 'right' != $align ) $align = 'center';
    
    $class = [
        'widget-area',
        'header-sidebar',
        'wide-sidebar',
        'header-row',
        'after-header',
    ];
    $class[] = 'align-' . $align;
    
    if ( 'after-header' == fox_get_sticky_header_element() ) $class[] = 'header-sticky-element';
    
    /**
     * Below header sidebar
     */
    if ( is_active_sidebar( 'after-header' ) ) { ?>

    <div id="after-header" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php if ( 'true' == $container ) echo '<div class="container">' ?>

        <?php dynamic_sidebar( 'after-header' ); ?>
        
        <?php if ( 'true' == $container ) echo '</div><!-- .container -->' ?>

    </div><!-- .widget-area -->

    <?php }
    
}

add_filter( 'fox_show_header', 'fox_single_show_hide_header' );
/**
 * Show/Hide Header Control
 * For some purpose
 * @since 4.0
 */
function fox_single_show_hide_header( $show ) {
    
    $postid = fox_page_id();
    if ( ! $postid ) return $show;
        
    $single_show = get_post_meta( $postid, '_wi_show_header', true );
    if ( 'true' == $single_show ) {
        $show = true;
    } elseif ( 'false' == $single_show ) {
        $show = false;
    }
    
    return $show;
    
}

add_filter( 'fox_css', 'fox_header_builder_float_right_from' );
/**
 * since 4.0
 */
function fox_header_builder_float_right_from( $css ) {
    
    $float_right = intval( get_theme_mod( 'wi_header_builder_float_right_from', '-1' ) );
    if ( $float_right > 0 ) {
        $css .= '.main-header .widget:nth-child(' . $float_right . '){margin-left: auto;}';
    }
    
    return $css;
    
}

/**
 * return the sticky header element
 * @since 4.0
 */
function fox_get_sticky_header_element() {
    
    $element = get_theme_mod( 'wi_sticky_header_element', 'main-header' );
    return apply_filters( 'fox_sticky_header_element', $element );
    
}

/**
 * Display a minimal header
 * @since 4.0
 */
function fox_min_header() {
    
    get_template_part( 'parts/header', 'min' );
    
}