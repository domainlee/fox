<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'style' => '',
    'image' => '',
    'name' => '',
    'url' => '',
    'target' => '',
    'ratio' => '',
    'overlay' => '',
    'overlay_opacity' => '',
) ) );

echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

$class = [ 'fox-imagebox' ];

/**
 * style
 */
if ( ! in_array( $style, [ 2, 3 ] ) ) {
    $style = 1;
}
$class[] = 'imagebox-style-' . $style;

// target
if ( '_blank' != $target ) $target = '_self';
/**
 * Height, ratio
 */
$height_html = '';
$height_css = '';
if ( $ratio ) {
    $explode = explode( ':', $ratio );
    $w = isset( $explode[0] ) ? $explode[0] : ''; $w = trim( $w );
    $h = isset( $explode[1] ) ? $explode[1] : ''; $h = trim( $h );
    if ( is_numeric( $w ) && $w > 0 && is_numeric( $h ) && $h > 0 ) {
        $quotient = $h/$w * 100;
        if ( $quotient < 1000 && $quotient > 5 ) {
            $height_css = ' style="padding-bottom:' . $quotient . '%"';
        }
    }
}
$height_html = '<div class="imagebox-height"' . $height_css . '></div>';

/**
 * Overlay
 */
$overlay_html = '';
$overlay_css = [];
if ( $overlay ) {
    $overlay_css[] = 'background:' . $overlay;
}
if ( '' != $overlay_opacity ) {
    $overlay_opacity = floatval( $overlay_opacity );
    if ( $overlay_opacity <=1 && $overlay_opacity >= 0 ) {
        $overlay_css[] = 'opacity:' . $overlay_opacity;
    }
}
$overlay_css = join( ';', $overlay_css );
if ( ! empty( $overlay_css ) ) {
    $overlay_css = ' style="' . esc_attr( $overlay_css ) . '"';
}

$overlay_html = '<div class="imagebox-overlay"' . $overlay_css . '></div>';
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="imagebox-inner">
        
        <?php if ( $image && $img_html = wp_get_attachment_image( $image, 'full' ) ) { ?>
        <figure class="imagebox-image">
        
            <?php echo $img_html; ?>
        
        </figure>
        <?php } ?>
        
        <?php if ( $name ) { ?>
        <div class="imagebox-content">
            
            <h3 class="imagebox-name"><?php echo esc_html( $name ); ?></h3>
            
        </div>
        <?php } ?>
        
        <?php if ( $url ) { ?>
        
        <a href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>" class="imagebox-link"></a>
    
        <?php } ?>
        
        <?php echo $height_html . $overlay_html; ?>
        
        <?php if ( 2 == $style ) echo '<div class="imagebox-border"></div>'; ?>
        
    </div><!-- .imagebox-inner -->
    
</div><!-- .fox-imagebox -->

<?php
echo $after_widget;