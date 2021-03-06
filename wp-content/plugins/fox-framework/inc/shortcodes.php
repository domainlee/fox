<?php
add_shortcode( 'button', 'fox_button_shortcode' );
add_shortcode( 'btn', 'fox_button_shortcode' );
add_shortcode( 'fox_button', 'fox_button_shortcode' );
add_shortcode( 'fox_btn', 'fox_button_shortcode' );

if ( ! function_exists( 'fox_button_shortcode' ) ) :
/**
 * Button Shortcode
 * @since 4.0
 */
function fox_button_shortcode( $atts, $content = null ) {
    
    ob_start();
    
    fox_btn( $atts );
    
    return ob_get_clean();
    
}
endif;

add_shortcode( 'dropcap', 'fox_dropcap_shortcode' );
add_shortcode( 'fox_dropcap', 'fox_dropcap_shortcode' );
add_shortcode( 'wi_dropcap', 'fox_dropcap_shortcode' );

if ( ! function_exists( 'fox_dropcap_shortcode' ) ) :
/**
 * Dropcap Shortcode
 * @since 4.0
 */
function fox_dropcap_shortcode( $atts, $content = null ) {
    
    extract( wp_parse_args( $atts, [
        'style' => ''
    ] ) );
    
    $class = [
        'wi-dropcap',
        'fox-dropcap',
    ];
    
    if ( $style != 'dark' && $style != 'color' ) $style = 'default';
    $class[] = 'dropcap-' . $style;
    
    $html = '<span class="' . esc_attr( join( ' ', $class ) ) . '">' . trim( $content ) . '</span>';
    
    return $html;
    
}
endif;
add_shortcode( 'blockquote', 'fox_blockquote_shortcode' );
add_shortcode( 'fox_blockquote', 'fox_blockquote_shortcode' );
add_shortcode( 'wi_blockquote', 'fox_blockquote_shortcode' );

if ( ! function_exists( 'fox_blockquote_shortcode' ) ) :
/**
 * Blockquote Shortcode
 * @since 4.0
 */
function fox_blockquote_shortcode( $atts, $content = null ) {
    
    extract( shortcode_atts( array(
        'align' => 'center',
        'author' => '',
    ), $atts ) );
    
    if ( $align != 'left' && $align != 'right' ) $align = 'center';
    
    if ( $author ) $author = '<cite>' . $author . '</cite>';
    
    return '<blockquote class="wi-blockquote align-' . esc_attr( $align ) . '">' . trim( $content ) .  $author . '</blockquote>';
    
}
endif;