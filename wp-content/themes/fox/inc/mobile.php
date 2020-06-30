<?php
/**
 * We still use old version of off canvas menu
 * @since 4.0
 */
add_action( 'wp_footer', 'fox_offcanvas', 0 );
function fox_offcanvas() {
    
    // if ( ! apply_filters( 'fox_show_header', true ) ) return;
    $class = [
        'offcanvas',
    ];
    
    // skin
    $skin = get_theme_mod( 'wi_offcanvas_skin', 'light' );
    if ( 'dark' != $skin ) $skin = 'light';
    $class[] = 'offcanvas-' . $skin;
    
    // border
    // since 4.3
    $has_border = 'true' == get_theme_mod( 'wi_offcanvas_nav_border', 'false' );
    if ( $has_border ) {
        $class[] = 'offcanvas-style-has-border';
    }
?>

<div id="offcanvas" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <div class="offcanvas-inner">
        
        <?php do_action( 'fox_offcanvas_start' ); // since 4.0 ?>
        
        <?php /* ----------------------------   SEARCH ---------------------------- */ ?>
        <?php if ( 'true' == get_theme_mod( 'wi_offcanvas_search', 'true' ) ) { ?>
        
        <div class="offcanvas-search offcanvas-element">
            <?php get_search_form(); ?>
        </div>
        
        <?php } ?>
        
        <?php /* ----------------------------   MOBILE NAV ---------------------------- */ ?>
        <?php if ( has_nav_menu( 'mobile' ) ) { $location = 'mobile'; } elseif ( has_nav_menu( 'primary' ) ) { $location = 'primary'; } else { $location = ''; } ?>

        <?php if ( $location ) { ?>
        <nav id="mobilenav" class="offcanvas-nav offcanvas-element">

            <?php wp_nav_menu(array(
                'theme_location'	=>	$location,
                'depth'				=>	4,
                'container_class'	=>	'menu',
                'after' => '<span class="indicator"><i class="indicator-ic"></i></span>',
            ));?>

        </nav><!-- #mobilenav -->
        <?php } ?>
        
        <?php /* ----------------------------   SOCIAL ---------------------------- */ ?>
        <?php if ( 'true' == get_theme_mod( 'wi_offcanvas_social', 'true' ) ) { ?>
        
        <?php fox_social_icons([
        
            'style' => get_theme_mod( 'wi_offcanvas_social_style', 'plain' ),
            'shape' => get_theme_mod( 'wi_offcanvas_social_shape', 'circle' ),
            'size' => get_theme_mod( 'wi_offcanvas_social_size', 'bigger' ),
        
            'align' => 'left',
        
            'extra_class' => 'offcanvas-element',
        ]); ?>
        
        <?php } ?>
        
        <?php do_action( 'fox_offcanvas_end' ); // since 4.0 ?>
    
    </div><!-- .offcanvas-inner -->

</div><!-- #offcanvas -->

<div id="offcanvas-bg" class="offcanvas-bg"></div>
<div class="offcanvas-overlay" id="offcanvas-overlay"></div>

<?php
    
}

if ( ! function_exists( 'fox_mobile_logo' ) ) :
/**
 * Mobile Logo
 * @since 4.0
 */
function fox_mobile_logo() {
    
    $logo_type = get_theme_mod( 'wi_logo_type', 'text' );
    if ( 'image' != $logo_type ) $logo_type = 'text';
    
    $url = get_theme_mod( 'wi_logo_custom_link' );
    if ( ! $url ) {
        $url = home_url( '/' );
    }
    ?>

<h4 id="mobile-logo" class="mobile-logo mobile-logo-<?php echo esc_attr( $logo_type ); ?>">
    
    <a href="<?php echo esc_url( $url ); ?>" rel="home">

        <?php if ( 'text' == $logo_type ) { ?>

        <span class="text-logo"><?php bloginfo( 'title' ); ?></span>

        <?php } else {
        
        $src = get_theme_mod( 'wi_mobile_logo' );
        if ( ! $src ) {
            $src = get_theme_mod( 'wi_logo' );
        }
        if ( ! $src ) {
            $src = get_template_directory_uri() . '/images/logo.png';
        }
        ?>

        <img src="<?php echo esc_attr( $src ); ?>" alt="<?php echo esc_html__( 'Mobile Logo', 'wi' ); ?>" />

        <?php } ?>

    </a>
    
</h4><!-- .mobile-logo -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox_hamburger_btn' ) ) :
/**
 * Hamburger Button
 * @since 4.0
 */
function fox_hamburger_btn() { ?>

    <a class="toggle-menu hamburger hamburger-btn">
        <i class="feather-menu"></i>
        <i class="feather-x"></i>
    </a>

<?php    
}
endif;

add_action( 'fox_after_masthead', 'fox_header_mobile', 0 );
if ( ! function_exists( 'fox_header_mobile' ) ) :
/**
 * Header Mobile
 * @since 4.0
 */
function fox_header_mobile() {
    
    $class = [ 'masthead-mobile' ];
    
    ?>

<div id="masthead-mobile" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="container">
        
        <div class="masthead-mobile-left masthead-mobile-part">
            
            <?php fox_hamburger_btn(); ?>
            
            <?php do_action( 'fox_header_mobile_left' ); // since 4.0 ?>
            
        </div><!-- .masthead-mobile-part -->
    
        <?php fox_mobile_logo(); ?>
        
        <div class="masthead-mobile-right masthead-mobile-part">
        
            <?php do_action( 'fox_header_mobile_right' ); // since 4.0 ?>
            
        </div><!-- .masthead-mobile-part -->
    
    </div><!-- .container -->
    
    <div class="masthead-mobile-bg"></div>

</div><!-- #masthead-mobile -->

<div id="masthead-mobile-height"></div>
    
    <?php
}
endif;