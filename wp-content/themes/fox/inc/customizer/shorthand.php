<?php
/**
 * Shorthands
 * 
 * @since 1.0
 */

// font weights
if ( ! isset( $font_weights ) ) {
    for ( $i = 1; $i<=9; $i++ ) {
        
        if ( 4 == $i ) {
            $label = '400 (Regular )';
        } elseif ( 7 == $i ) {
            $label = '700 (Bold)';
        } elseif ( 3 == $i ) {
            $label = '300 (Light)';
        } else {
            $label = $i*100;
        }
        
        $font_weights[ (string) ($i*100) ] = $label;
    }
}

// font list
if ( ! isset( $fontlist ) ) {
    
    foreach( fox_normal_fonts() as $font => $fontdata ) {
        $fontlist[ $font ] = $font;
    }
    
    $all_fonts = fox_google_fonts();
    foreach ( $all_fonts as $font => $fontdata ) {
        $fontlist[ $font ] = $font;
    }
    
}

if ( ! isset( $option[ 'shorthand' ] ) )
    return;
    
$shorthand = $option[ 'shorthand' ];

switch ( $shorthand ) {
    
    case 'truefalse' :
        $append = array(
            'type'      => 'select',
            'options'   => array(
                'true'  => esc_html__( 'Yes', 'wi' ),
                'false' => esc_html__( 'No', 'wi' ),
            ),
        );
    break;
    
    case 'enable' :
        $append = array(
            'type'      => 'select',
            'options'   => array(
                'true'  => esc_html__( 'Enable', 'wi' ),
                'false' => esc_html__( 'Disable', 'wi' ),
            ),
            'std'       => 'true',
        );
    break;
    
    case 'target' :
        $append = array(
            'type'      => 'select',
            'options'   => array(
                '_self'  => esc_html__( 'Same Tab', 'wi' ),
                '_blank' => esc_html__( 'New Tab', 'wi' ),
            ),
            'std' => '_self',
        );
    break;

    case 'color' :
        $append = array(
            'name'      => esc_html__( 'Color', 'wi' ),
            'type'      => 'color',
            'css'       => 'color',
            'transport' => 'postMessage',
        );
    break;
    
    /**
     * Background
     * Note: this is a custom control, a multiple control
     * @since 4.0
     */
    case 'background' :
        $append = array(
            'name'      => 'Background',
            'type'      => 'fox_background',
        );
    break;

    /**
     * Background Color
     */
    case 'background-color' :
        $append = array(
            'name'      => esc_html__( 'Background', 'wi' ),
            'type'      => 'color',
            'css'   => 'background-color',
            'transport' => 'postMessage',
        );
    break;
    
    /**
     * Background Image
     */
    case 'background-image' :
        $append = array(
            'name'      => esc_html__( 'Background Image', 'wi' ),
            'type'      => 'image',
            'css'   => 'background-image',
        );
    break;
    
    /**
     * Background Size
     */
    case 'background-size' :
        $append = array(
            'name'      => esc_html__( 'Background Size', 'wi' ),
            'type'      => 'select',
            'options'   => fox_background_size(),
            'std'       => 'cover',
            'css'       => 'background-size',
        );
    break;
    
    /**
     * Background Position
     */
    case 'background-position' :
        $append = array(
            'name'      => esc_html__( 'Background Position', 'wi' ),
            'type'      => 'select',
            'options'   => fox_background_position(),
            'std'       => 'center center',
            'css'       => 'background-position',
        );
    break;
    
    /**
     * Background Repeat
     */
    case 'background-repeat' :
        $append = array(
            'name'      => esc_html__( 'Background Repeat', 'wi' ),
            'type'      => 'select',
            'options'   => fox_background_repeat(),
            'std'       => 'no-repeat',
            'css'       => 'background-repeat',
        );
    break;
    
    /**
     * Background Attachment
     */
    case 'background-attachment' :
        $append = array(
            'name'      => esc_html__( 'Background Attachment', 'wi' ),
            'type'      => 'select',
            'options'   => fox_background_attachment(),
            'std'       => 'scroll',
            'css'       => 'background-attachment',
        );
    break;
    
    /**
     * Border Color
     */
    case 'border-color' :
        $append = array(
            'name'      => esc_html__( 'Border Color', 'wi' ),
            'type'      => 'color',
            'css'   => 'border-color',
            'transport' => 'postMessage',
        );
    break;
    
    /**
     * Border Top Color
     */
    case 'border-top-color' :
        $append = array(
            'name'      => 'Border top color',
            'type'      => 'color',
            'css'   => 'border-top-color',
            'transport' => 'postMessage',
        );
    break;
    
    /**
     * Border Bottom Color
     */
    case 'border-bottom-color' :
        $append = array(
            'name'      => 'Border bottom color',
            'type'      => 'color',
            'css'   => 'border-bottom-color',
            'transport' => 'postMessage',
        );
    break;
    
    /**
     * Border Style
     */
    case 'border-style' :
        $append = array(
            'name'      => esc_html__( 'Border Style', 'wi' ),
            'type'      => 'select',
            'options'   => fox_border_style(),
            'std'       => 'none',
            'css'       => 'border-style',
        );
    break;
    
    /**
     * Border Width
     */
    case 'border-width' :
        $append = array(
            'name'      => esc_html__( 'Border Width', 'wi' ),
            'type'      => 'text',
            'placeholder'=> 'Eg. 5px 0 0',
            'css'       => 'border-width',
        );
    break;
    
    /**
     * Border Top Width
     */
    case 'border-top-width' :
        $append = array(
            'name'      => 'Border top width',
            'type'      => 'select',
            'options'   => [
                '0' => 'None',
                '1' => '1px',
                '2' => '2px',
                '3' => '3px',
                '4' => '4px',
                '5' => '5px',
                '6' => '6px',
            ],
            'std'       => '0',
            'unit'      => 'px',
            'css'       => 'border-top-width',
        );
    break;
    
    /**
     * Border Bottom Width
     */
    case 'border-bottom-width' :
        $append = array(
            'name'      => 'Border bottom width',
            'type'      => 'select',
            'options'   => [
                '0' => 'None',
                '1' => '1px',
                '2' => '2px',
                '3' => '3px',
                '4' => '4px',
                '5' => '5px',
                '6' => '6px',
            ],
            'std'       => '0',
            'unit'      => 'px',
            'css'       => 'border-bottom-width',
        );
    break;
    
    /**
     * Border Width Slide Form
     */
    case 'border-width-slide' :
        $append = array(
            'name'      => esc_html__( 'Border Thickness', 'wi' ),
            'type'      => 'slide',
            'min'       => '0',
            'max'       => '10',
            'step'      => '1',
            'unit'      => 'px',
            'std'       => '1',
            'css'       => 'border-width',
        );
    break;
    
    /**
     * Border Radius
     */
    case 'border-radius' :
        $append = array(
            'name'      => 'Border radius',
            'type'      => 'text',
            'placeholder'=> 'Eg. 3px',
            'css'       => 'border-radius',
        );
    break;
    
    /**
     * Border Radius Slide
     */
    case 'border-radius-slide' :
        $append = array(
            'name'      => esc_html__( 'Border Radius', 'wi' ),
            'type'      => 'slide',
            'std'       => '0',
            'min'       => '0',
            'max'       => '30',
            'step'      => '1',
            'unit'      => 'px',
            'css'       => 'border-radius',
        );
    break;
    
    /**
     * Border Radius Select
     */
    case 'border-radius-select' :
        $append = array(
            'name'      => esc_html__( 'Border Radius', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                '0' => esc_html__( 'None', 'wi' ),
                '1px' => '1px',
                '2px' => '2px',
                '3px' => '3px',
                '4px' => '4px',
                '5px' => '5px',
                '6px' => '6px',
                '7px' => '7px',
                '8px' => '8px',
                '50%' => esc_html__( 'Round', 'wi' ),
            ),
            'std'       => '0',
            'css'       => 'border-radius',
        );
    break;

    /**
     * Alignment
     */
    case 'align' :
    case 'text-align' :
        $append = array(
            'name'      => esc_html__( 'Align', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                'left'  => esc_html__( 'Left', 'wi' ),
                'center'  => esc_html__( 'Center', 'wi' ),
                'right'  => esc_html__( 'Right', 'wi' ),
            ),
            'std'       => 'center',
            'css'   => 'text-align',
        );
    break;

    /**
     * Box Shadow
     */
    case 'box-shadow' :
        $append = array (
            'name'      => esc_html__( 'Box Shadow', 'wi' ),
            'desc'      => esc_html__( 'You need CSS Knowledge to change this.', 'wi' ),
            'type'      => 'text',
            'placeholder'=> 'Eg. 1px 2px 6px rgba(0,0,0,.3)',
            'css'   => 'box-shadow',
        );
    break;

    /**
     * Transition
     */
    case 'transition' :

        $transition_arr = array (
            '0ms' => esc_html__( 'None', 'wi' ),
        );
        for ( $i = 1; $i <= 10; $i++ ) {
            $transition_arr[ (50*$i) . 'ms' ] = (50*$i) . 'ms';
        }

        $append = array (
            'type'      => 'select',
            'options'   => $transition_arr,
            'std'       => '0ms',
            'name'      => esc_html__( 'Transition', 'wi' ),
            'css'   => 'transition',
        );
    break;

    /**
     * PADDING
     */
    case 'padding' :
        $append = array (
            'name'      => 'Padding',
            'type'      => 'text',
            'css'       => 'padding',
            'placeholder' => 'Eg. 10px 5px 20px',
            'desc'      => 'Enter padding in syntax: top right bottom left, eg: 10px 5px 20px 5px. <a href="https://www.w3schools.com/cssref/pr_padding.asp" target="_blank">See the details.</a>.',
            'unit'      => 'px',
        );
    break;
    
    /**
     * PADDING TOP
     */
    case 'padding-top' :
        $append = array (
            'name'      => esc_html__( 'Padding Top', 'wi' ),
            'type'      => 'text',
            'css'       => 'padding-top',
            'unit'      => 'px',
        );
    break;
    
    /**
     * PADDING BOTTOM
     */
    case 'padding-bottom' :
        $append = array (
            'name'      => esc_html__( 'Padding Bottom', 'wi' ),
            'type'      => 'text',
            'css'       => 'padding-bottom',
            'unit'      => 'px',
        );
    break;

    /**
     * MARGIN
     */
    case 'margin' :
        $append = array (
            'name'      => esc_html__( 'Margin', 'wi' ),
            'placeholder'=> '10px 20px',
            'type'      => 'fox_text',
            'css'   => 'margin',
        );
    break;
    
    /**
     * MARGIN EM
     */
    case 'margin-em' :
        $append = array (
            'name'      => esc_html__( 'Margin', 'wi' ),
            'type'      => 'slide',
            'css'       => 'margin',
            'min'       => '0',
            'max'       => '10',
            'step'      => '0.1',
            'std'       => '1',
            'unit'      => 'em',
        );
    break;
    
    /**
     * MARGIN TOP
     */
    case 'margin-top' :
        $append = array (
            'name'      => esc_html__( 'Margin Top', 'wi' ),
            'type'      => 'text',
            'css'       => 'margin-top',
            'unit'      => 'px',
        );
    break;
    
    /**
     * MARGIN TOP EM
     */
    case 'margin-top-em' :
        $append = array (
            'name'      => esc_html__( 'Margin Top', 'wi' ),
            'type'      => 'slide',
            'css'       => 'margin-top',
            'min'       => '0',
            'max'       => '10',
            'step'      => '0.1',
            'std'       => '1',
            'unit'      => 'em',
        );
    break;
    
    /**
     * MARGIN BOTTOM
     */
    case 'margin-bottom' :
        $append = array (
            'name'      => esc_html__( 'Margin Bottom', 'wi' ),
            'type'      => 'text',
            'css'       => 'margin-bottom',
            'unit'      => 'px',
        );
    break;
    
    /**
     * MARGIN LEFT
     */
    case 'margin-left' :
        $append = array (
            'name'      => esc_html__( 'Margin Left', 'wi' ),
            'type'      => 'slide',
            'css'       => 'margin-left',
            'unit'      => 'px',
            'min'       => '0',
            'max'       => '100',
        );
    break;

    /**
     * WIDTH
     */
    case 'width' :
        $append = array (
            'name'      => esc_html__( 'Width', 'wi' ),
            'type'      => 'text',
            'css'       => 'width',
            'unit'      => 'px',
        );
    break;
    
    /**
     * HEIGHT
     */
    case 'height' :
        $append = array (
            'name'      => esc_html__( 'Height', 'wi' ),
            'type'      => 'text',
            'unit'      => 'px',
            'css'       => 'height',
        );
    break;

    /**
     * Opacity
     */
    case 'opacity' :
        $append = array (
            'name'      => 'Opacity',
            'type'      => 'text',
            'placeholder' => 'Eg. 0.6',
            'css'       => 'opacity',
            'desc'      => 'Enter a number between 0 - 1, eg. 0.6',
        );
    break;
    
    /*
     * text decoration
     */
    case 'text-decoration' :
        $append = array (
            'name'      => 'Text decoraction',
            'type'      => 'select',
            'options'   => [
                'none' => 'None',
                'underline' => 'Underline',
                'line-through' => 'Line through',
                'overline' => 'Overline',
                'underline overline' => 'Underline Overline',
            ],
            'std'       => '',
            'css'       => 'text-decoration',
        );
    break;
    
    /*
     * text decoration color
     */
    case 'text-decoration-color' :
        $append = array (
            'name'      => 'Text Decoraction Color',
            'type'      => 'color',
            'css'       => 'text-decoration-color',
        );
    break;
    
    /**
     * Font Size
     */
    case 'font-size' :
        $append = array (
            'name'      => esc_html__( 'Font Size', 'wi' ),
            'type'      => 'text',
            'unit'  => 'px',
            'css'   => 'font-size',
        );
    break;
    
    /**
     * Font Size
     */
    case 'font-size-em' :
        $append = array (
            'name'      => esc_html__( 'Font Size', 'wi' ),
            'type'      => 'slide',
            'unit'  => 'em',
            'step'  => '0.05',
            'min'   => '0.65',
            'max'   => '2.2',
            'std'   => '1',
            'css'   => 'font-size',
        );
    break;
    
    /**
     * Font Weigth
     */
    case 'font-weight' :
        $append = array (
            'name'      => esc_html__( 'Font Weight', 'wi' ),
            'type'      => 'select',
            'options'   => $font_weights,
            'css'   => 'font-weight',
            'std'       => '400',
        );
    break;
    
    /**
     * Font Weigth with default blank option
     */
    case 'font-weight-default' :
        $append = array (
            'name'      => esc_html__( 'Font Weight', 'wi' ),
            'type'      => 'select',
            'options'   => array( '' => esc_html__( 'Default', 'wi' ) ) + $font_weights,
            'css'   => 'font-weight',
            'std'       => '',
        );
    break;
    
    /**
     * Line Height
     */
    case 'line-height' :
        $append = array (
            'name'      => esc_html__( 'Line Height', 'wi' ),
            'type'      => 'text',
            'css'       => 'line-height',
            'placeholder'=> 'Eg. 1.4',
        );
    break;
    
    /**
     * Line Height px
     */
    case 'line-height-px' :
        $append = array (
            'name'      => esc_html__( 'Line Height', 'wi' ),
            'type'      => 'slide',
            'min'       => '20',
            'max'       => '60',
            'step'      => '1',
            'std'       => '40',
            'css'       => 'line-height',
            'unit'      => 'px',
        );
    break;
    
    /**
     * Letter Spacing
     */
    case 'letter-spacing' :
        $append = array (
            'name'      => esc_html__( 'Letter Spacing', 'wi' ),
            'type'      => 'text',
            'css'       => 'letter-spacing',
            'unit'      => 'px',
            'placeholder' => 'Eg. 1px',
        );
    break;
    
    /**
     * Text Transform
     */
    case 'text-transform' :
        $append = array (
            'name'      => esc_html__( 'Text Transform', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                'none'  => esc_html__( 'None', 'wi' ),
                'uppercase'  => esc_html__( 'UPPERCASE', 'wi' ),
                'lowercase'  => esc_html__( 'lowercase', 'wi' ),
                'capitalize'  => esc_html__( 'Capitalize', 'wi' ),
            ),
            'std'       => 'none',
            'css'   => 'text-transform',
        );
    break;
    
    /**
     * Text Transform with blank option
     */
    case 'text-transform-default' :
        $append = array (
            'name'      => esc_html__( 'Text Transform', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                ''  => esc_html__( 'Default', 'wi' ),
                'none'  => esc_html__( 'None', 'wi' ),
                'uppercase'  => esc_html__( 'UPPERCASE', 'wi' ),
                'lowercase'  => esc_html__( 'lowercase', 'wi' ),
                'capitalize'  => esc_html__( 'Capitalize', 'wi' ),
            ),
            'std'       => '',
            'css'   => 'text-transform',
        );
    break;
    
    /**
     * Font Style
     */
    case 'font-style' :
        $append = array (
            'name'      => esc_html__( 'Font Style', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                'normal'  => esc_html__( 'Normal', 'wi' ),
                'italic'  => esc_html__( 'Italic', 'wi' ),
            ),
            'std'       => 'normal',
            'css'   => 'font-style',
        );
    break;
    
    /**
     * Font Style with blank option
     */
    case 'font-style-default' :
        $append = array (
            'name'      => esc_html__( 'Font Style', 'wi' ),
            'type'      => 'select',
            'options'   => array(
                ''  => esc_html__( 'Default', 'wi' ),
                'normal'  => esc_html__( 'Normal', 'wi' ),
                'italic'  => esc_html__( 'Italic', 'wi' ),
            ),
            'std'       => '',
            'css'   => 'font-style',
        );
    break;
    
    /**
     * Box Property
     */
    case 'box' :
        $append = array (
            'name'      => 'Margin/Padding/Border',
            'type'      => 'box',
        );
    break;
    
    /**
     * Font Family
     */
    case 'font-family' :
        $append = array (
            'name'      => 'Select font',
            'type'      => 'select',
            'options'   => $fontlist,
            'css'       => 'font-family',
        );
    break;
    
    /**
     * Typography
     */
    case 'typography' :
        $append = array (
            'name'      => 'Typography',
            'type'      => 'typography',
        );
    break;
    
    /**
     * Select Font
     */
    case 'select-font' :
        $append = array (
            'name'      => 'Select font',
            'type'      => 'select_font',
            'inherit_options' => true,
        );
    break;

    default :

    break;

} // switch $shorthand

$option = array_merge( $append, $option );