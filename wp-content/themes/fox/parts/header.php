<?php
// since 4.0 - if you wanna disable header for some purpose
if ( ! apply_filters( 'fox_show_header', true ) ) return;

$use_header_builder = ( 'true' == get_theme_mod( 'wi_header_builder', 'false' ) );
$header_class = [
    'site-header',
];

/**
 * header type
 */
if ( $use_header_builder ) {
    $header_class[] = 'header-builder';
} else {
    $header_class[] = 'header-classic';
}

/**
 * sticky header bottom style
 */
if ( $header_sticky_style = get_theme_mod( 'wi_sticky_header_element_style', 'shadow' ) ) {
    $header_class[] = 'header-sticky-style-' . $header_sticky_style;
}

/**
 * Submenu Style
 */
$submenu_style = get_theme_mod( 'wi_submenu_style', 'light' );
if ( 'dark' != $submenu_style ) $submenu_style = 'light';
$header_class[] = 'submenu-' . $submenu_style;

$header_class = apply_filters( 'fox_header_class', $header_class );
?>

<header id="masthead" class="<?php echo esc_attr( join( ' ', $header_class ) ); ?>" itemscope itemtype="https://schema.org/WPHeader">

    <?php
    // since 4.0
    do_action( 'fox_before_header' );

    if ( ! $use_header_builder ) {

        get_template_part( 'parts/header', 'classic' );

    } else {

        get_template_part( 'parts/header', 'builder' );

    }

    // since 4.0
    do_action( 'fox_after_header' ); ?>
    
</header><!-- #masthead -->