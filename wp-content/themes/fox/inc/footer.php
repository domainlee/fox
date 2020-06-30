<?php
add_filter( 'fox_show_footer', 'fox_single_show_hide_footer' );
/**
 * Show/Hide Header Control
 * For some purpose
 */
function fox_single_show_hide_footer( $show ) {
    
    if ( is_singular() ) {
        
        $single_show = get_post_meta( get_the_ID(), '_wi_show_footer', true );
        if ( 'true' == $single_show ) {
            $show = true;
        } elseif ( 'false' == $single_show ) {
            $show = false;
        }
        
    }
    return $show;
    
}

if ( ! function_exists( 'fox_footer_logo' ) ) :
/**
 * Footer Logo
 * @since 4.0
 */
function fox_footer_logo() {
    
    $footer_logo = get_theme_mod( 'wi_footer_logo' );
    if ( ! $footer_logo ) return;
    
    $footer_logo_html = '';
    $footer_logo_id = attachment_url_to_postid( $footer_logo );
    if ( $footer_logo_id ) {
        $footer_logo_html = wp_get_attachment_image( $footer_logo_id, 'full' );
    } else {
        $footer_logo_html = '<img src="' . esc_url( $footer_logo ) .'" alt="' . esc_html__( 'Footer Logo', 'wi' ) . '" />';
    }
    
    $url = get_theme_mod( 'wi_footer_logo_custom_link' );
    if ( ! $url ) {
        $url = home_url( '/' );
    }

?>
    
    <div id="footer-logo" class="footer-bottom-element">
        
        <a href="<?php echo esc_url( $url ); ?>" rel="home">
            
            <?php echo $footer_logo_html; ?>
            
        </a>
        
    </div><!-- #footer-logo -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_footer_social' ) ) :
/**
 * Footer Social Icons
 * @since 4.0
 */
function fox_footer_social() {
    
    $footer_social_skin = get_theme_mod( 'wi_footer_social_skin', 'black' );
    fox_social_icons([
        'style' => $footer_social_skin,
        'extra_class' => 'footer-social-list footer-bottom-element',
    ]);
    
}
endif;

if ( ! function_exists( 'fox_footer_search' ) ) :
/**
 * Footer Search Form
 * @since 4.0
 */
function fox_footer_search() {
    
    ?>

<div class="footer-search-container footer-bottom-element">

    <div class="footer-search" id="footer-search">
        
        <?php get_search_form(); ?>
        
    </div><!-- #footer-search -->
    
</div><!-- .footer-search-container -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_footer_copyright' ) ) :
/**
 * Footer Copyright
 * @since 4.0
 */
function fox_footer_copyright() {
    
    $copyright = trim( get_theme_mod( 'wi_copyright' ) );
    if ( ! $copyright ) {
        $copyright = 'Designed by The Fox &mdash; <a href="https://themeforest.net/item/the-fox-contemporary-magazine-theme-for-creators/11103012/" target="_blank">Magazine WordPress Theme</a>.';
    }
    
    if ( function_exists( 'pll__' ) ) {
        $copyright = pll__( $copyright );
    }
    
    ?>

<div class="footer-copyright copyright footer-bottom-element">

    <?php echo wpautop( wp_kses( $copyright, fox_allowed_html() ) ); ?>
    
</div><!-- .footer-copyright -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_footer_nav' ) ) :
/**
 * Footer Nav
 * @since 4.0
 */
function fox_footer_nav() {
    
    if ( ! has_nav_menu( 'footer' ) ) return; ?>

    <nav id="footernav" class="footernav footer-bottom-element" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
        
        <?php wp_nav_menu( array(
        
            'theme_location'	=>	'footer',
            'depth'				=>	1,
            'container_class'	=>	'menu',
        
        ) ) ; ?>
        
    </nav><!-- #footernav -->

    <?php

}
endif;

add_action( 'wp_footer', 'fox_backtotop' );

if ( ! function_exists( 'fox_backtotop' ) ) :
/**
 * Back To Top Button
 * @since 4.0
 */
function fox_backtotop() {
    
    if ( ! apply_filters( 'fox_show_footer', true ) ) return;
    
    if ( 'false' == get_theme_mod( 'wi_backtotop', 'true' ) ) return;
    
    $class = [
        'backtotop',
        'fox-backtotop',
        'scrollup',
    ];
    
    $type = get_theme_mod( 'wi_backtotop_type', 'icon-chevron-up' );
    
    // since 4.1
    $shape = get_theme_mod( 'wi_backtotop_shape', 'circle' );
    if ( 'text' == $type ) {
        $shape = 'square';
    }
    
    $class[] = 'backtotop-' . $shape;
    
    if ( 'text' == $type ) {
        $html = '<span class="go">' . fox_word( 'go' ) . '</span>';
        $html .= '<span class="top">' . fox_word( 'top' ) . '</span>';
        
        $class[] = 'backtotop-text';
        
    } else {
        
        $icon = str_replace( 'icon-', '', $type );
        
        $html = '<span class="btt-icon"><i class="feather-' . esc_html( $icon ) . '"></i></span>';
        
        $class[] = 'backtotop-icon';
        
    }
    
    ?>
    <div id="backtotop" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <?php echo $html; ?>
        
    </div><!-- #backtotop -->

<?php
}
endif;