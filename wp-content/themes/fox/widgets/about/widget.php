<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'align' => '',
    'image' => '',
    'image_size' => 'medium',
    'image_width' => '',
    'image_shape' => '',
    'desc' => '',
    'signature' => '',
    'signature_width' => '',
) ) );
echo $before_widget;

if ( 'center' != $align && 'right' != $align ) {
    $align = 'left';
}

echo '<div class="about-wrapper align-' . $align . '">';

$image_id = 0;
$img_html = '';
$shape = $image_shape;
$caption_html = '';

if ( 'circle' != $shape && 'round' != $shape ) $shape = 'acute';
if ( $image ) {
    
    if ( is_numeric( $image ) ) {
        $image_id = $image;
    } else {
        $image_id = attachment_url_to_postid( $image );
    }
    if ( $image_id ) {
        
        $size = $image_size;
        $img_html = wp_get_attachment_image( $image_id, $size );
        if ( $img_html ) {
            
            $cl = [
                'fox-figure',
                'about-image',
            ];
            
            $cl[] = 'thumbnail-' . $image_shape;
            
            $image_width_css = '';
            if ( $image_width ) {
                if ( is_numeric( $image_width ) ) {
                    $image_width .= 'px';
                }
                $image_width_css = ' style="width:' . esc_attr( $image_width ) . '"';
            }
            
            $img_html = '<figure class="' . esc_attr( join( ' ', $cl ) ) . '"' . $image_width_css . '><span class="image-element thumbnail-inner">' . $img_html  . '</span></figure>';
            
        }
        
        if ( $img_html ) {
            echo $img_html;
        }
        
    }
}

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {
    echo $before_title . $title . $after_title;
}

echo '<div class="widget-about">';

if ( $desc ) {
    echo '<div class="desc">' . do_shortcode( $desc ) . '</div>';
}

if ( is_numeric( $signature ) ) {
    $signature_id = $signature;
} else {
    $signature_id = attachment_url_to_postid( $signature );
}
if ( $signature_id ) {

    $size = 'medium';
    $img_html = wp_get_attachment_image( $signature_id, $size );
    if ( $img_html ) {
        
        $signature_width_css = '';
        if ( $signature_width ) {
            if ( is_numeric( $signature_width ) ) {
                $signature_width .= 'px';
            }
            $signature_width_css = ' style="width:' . esc_attr( $signature_width ) . '"';
        }
        
        echo '<figure class="about-signature"' . $signature_width_css . '>' . $img_html  . '</figure>';
    }

}

echo '</div><!-- .about-widget -->';

echo '</div><!-- .about-wrapper -->';

echo $after_widget;