<?php 
if ( 'false' == get_theme_mod( 'wi_footer_sidebar', 'true' ) ) return;

if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) return;

$layout = get_theme_mod( 'wi_footer_sidebar_layout', '1-1-1-1' );
$layout_support = footer_sidebar_layout_support();
if ( ! isset( $layout_support[ $layout ] ) ) $layout = '1-1-1-1';

$explode = explode( '-', $layout );
$max_col = count( $explode );

if ( $max_col > 4 || $max_col < 1 ) $max_col = 4;
$cols = 0;
foreach ( $explode as $col ) {
    $cols += absint( $col );
}
$class = [
    'footer-widgets',
    'footer-sidebar',
];
$class[] = 'footer-sidebar-' . $layout;

$count = 0;

// skin
$skin = get_theme_mod( 'wi_footer_sidebar_skin', 'light' );
if ( 'dark' != $skin ) $skin = 'light';
$class[] = 'skin-' . $skin;

// stretch
$stretch = get_theme_mod( 'wi_footer_sidebar_stretch', 'content' );
if ( 'full' != $stretch ) $stretch = 'content';
$class[] = 'stretch-' . $stretch;

// valign
// since 4.3
$valign = get_theme_mod( 'wi_footer_sidebar_valign', 'stretch' );
if ( 'top' != $valign && 'middle' != $valign && 'bottom' != $valign ) {
    $valign = 'stretch';
}
$class[] = 'valign-' . $valign;

$footer_sidebar_sep = ( 'true' == get_theme_mod( 'wi_footer_sidebar_sep', 'true' ) );
?>

<div id="footer-widgets" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <div class="container">

        <div class="footer-widgets-inner footer-widgets-row">

            <?php for ( $i = 1; $i <= $max_col; $i ++ ) : $col_class = [ 'widget-area', 'footer-col' ]; $count++;

                if ( $cols == 4 && $explode[ $i - 1 ] == 2 ) {
                    $col = '1-2';
                } else {
                    $col = $explode[ $i - 1 ] . '-' . $cols;
                }
                $col_class[] = 'col-' . $col;
            
                // align, since 4.4
            $align = get_theme_mod( 'wi_footer_' . $i . '_align' );
            if ( in_array( $align, [ 'left', 'right', 'center' ] ) ) {
                $col_class[] = 'footer-col-' . $align;
            }
            
            ?>

            <aside class="<?php echo esc_attr( join( ' ', $col_class ) ); ?>" itemscope itemptype="https://schema.org/WPSideBar">

                <?php 
                if ( is_active_sidebar( 'footer-' . $i ) ) {
                    
                    echo '<div class="footer-col-inner">';

                    dynamic_sidebar( 'footer-' . $i );
                    
                    echo '</div>';

                } ?>

                <?php if ( $footer_sidebar_sep ) { ?>
                <div class="footer-col-sep"></div>
                <?php } ?>

            </aside><!-- .footer-col -->

            <?php endfor; // for $i ?>

        </div><!-- .footer-widgets-inner -->

    </div><!-- .container -->

</div><!-- #footer-widgets -->