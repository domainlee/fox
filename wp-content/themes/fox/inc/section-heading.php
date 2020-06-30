<?php
if ( ! function_exists( 'fox_section_heading' ) ) :
/**
 * Builder Section Heading
 *
 * @since 4.3
 */
function fox_section_heading( $params = [] ) {
    
    extract( wp_parse_args( $params, [
        
        'heading' => '',
        'color' => '',
        'size' => 'large',
        'align' => 'center',
        'style' => '1a',
        'line_stretch' => 'content',
        
        'url' => '',
        'target' => '',
        
        'extra_class' => '',
        
    ]) );
    
    $class = [ 'section-heading' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $heading = trim( $heading );
    if ( ! $heading ) {
        return;
    }
    
    if ( function_exists( 'pll__' ) ) {
        $heading = pll__( $heading );
    }
    
    /**
     * style
     */
    if ( ! in_array( $style, [ 'plain', '1a', '1b', '2a', '2b', '3a', '3b', '4a', '4b', '5', '6', '7a', '8a' ] ) ) {
        $style = '1a';
    }
    $number_index = substr( $style, 0, 1 );
    if ( in_array( $number_index, [ 2, 3, 4, 7, 8 ] ) ) {
        $main_style = 'line';
        $line_pos = substr( $style, 1, 1 ); // a or b, a means middle, b means bottom
        $line_pos = ( 'a' == $line_pos ) ? 'middle' : 'bottom';
    } else {
        $main_style = $style;
    }
    if ( $main_style == 'line' ) {
        $class[] = 'heading-line';
        $class[] = 'heading-line-' . $number_index;
        $class[] = 'heading-line-' . $line_pos;
        
        if ( 'full' != $line_stretch && 'content-half' != $line_stretch ) {
            $line_stretch = 'content';
        }
        
        $class[] = 'heading-line-stretch-' . $line_stretch;
            
    } else {
        $class[] = 'heading-' . $style;
    }
    
    /**
     * size
     */
    if ( ! in_array( $size, [ 'tiny', 'small', 'normal', 'medium', 'large', 'extra', 'ultra' ] ) ) {
        $size = 'large';
    }
    $class[] = 'heading-' . $size;
    
    /**
     * URL
     */
    $url = trim( $url );
    $open = $close = '';
    if ( $url ) {
        if ( '_blank' != $target ) $target = '_self';
        $open = '<a href="' . esc_url( $url ).'" target="' . esc_attr( $target ). '">';
        $close = '</a>';
    }
    
    /**
     * align
     */
    if ( 'left' != $align && 'right' != $align ) {
        $align = 'center';
    }
    $class[] = 'align-' . $align;
    
    /**
     * color
     */
    $css = [];
    if ( $color ) {
        $css[] = 'color:' . $color;
        $class[] = 'custom-color';
    }
    
    $css = join( ';', $css );
    if ( $css ) {
        $css = ' style="' . esc_attr( $css ). '"';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>"<?php echo $css; ?>>
    
    <div class="container">
        
        <div class="heading-inner">
        
            <h2 class="heading-text"><?php echo $open . $heading . $close; ?></h2>
            
            <div class="line line-l"></div>
            <div class="line line-r"></div>
        
        </div><!-- .heading-inner -->
    
    </div><!-- .container -->
    
</div><!-- .section-heading -->

<?php
    
}
endif;