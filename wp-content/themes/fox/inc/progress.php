<?php
add_action( 'wp_footer', 'fox_single_progress_bar' );
if ( ! function_exists( 'fox_single_progress_bar' ) ) :
/**
 * add reading progress bar to single posts
 * @since 4.0
 */
function fox_single_progress_bar() {
    
    if ( ! fox_show( 'reading_progress', 'single' ) ) return;
    if ( ! is_single() ) return;
    
    $cl = [ 'reading-progress-wrapper' ];
    
    // position
    $position = get_theme_mod( 'wi_reading_progress_position', 'top' );
    if ( 'bottom' != $position ) $position = 'top';
    $cl[] = 'position-' . $position;
    
    //
    
    ?>

<progress value="0" class="<?php echo esc_attr( join( ' ', $cl ) ); ?>">
    
    <div class="progress-container">
        <span class="reading-progress-bar"></span>
    </div>
    
</progress>

    <?php
}
endif;

add_filter( 'fox_css', 'fox_single_progress_bar_color' );
function fox_single_progress_bar_color( $css ) {
    
    $color = get_theme_mod( 'wi_reading_progress_color' );
    if ( $color ) {
        $css .= '.reading-progress-wrapper::-webkit-progress-value {background-color:' . $color . '}';
        $css .= '.reading-progress-wrapper::-moz-progress-bar {background-color:' . $color . '}';
    }
    
    return $css;
    
}