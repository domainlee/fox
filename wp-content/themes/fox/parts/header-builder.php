<?php
$class = [
    'main-header',
    'header-builder',
    'header-row',
];

if ( 'true' == get_theme_mod( 'wi_header_builder_center_logo', 'false' ) ) {
    $class[] = 'has-logo-center';
}

$valign = get_theme_mod( 'wi_header_builder_valign', 'center' );
if ( 'top' != $valign && 'bottom' != $valign ) $valign = 'center';
$class[] = 'valign-' . $valign;

$class = apply_filters( 'fox_header_class', $class );

if ( 'true' == get_theme_mod( 'wi_header_builder_stretch_container', 'false' ) ) {
    $class[] = 'header-builder-stretch-container';
}

// sticky element
if ( 'main-header' == fox_get_sticky_header_element() ) {
    $class[] = 'header-sticky-element';
}

?>

<div class="<?php echo esc_attr( join( ' ',  $class ) ); ?>" id="main-header">

    <div class="container">

        <?php if ( is_active_sidebar( 'header-builder' ) ) {
    
            dynamic_sidebar( 'header-builder' );
    
            } else {
    
                $widgets_link = get_admin_url( '','widgets.php' );

                echo '<div class="fox-error">Please go to <a href="' . $widgets_link . '">Appearance > Widgets > Header Builder</a> to drag header widgets into the header builder sidebar.</div>';
            } ?>

    </div><!-- .container -->

</div><!-- .main-header -->