<?php
if ( ! function_exists( 'fox_btn_params' ) ) :
/**
 * Button Params
 * This function helps implementing fox button in many places: widget, theme option or page builder
 * @since 4.0
 */
function fox_btn_params( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'include' => [],
        'exclude' => [],
        'override' => []
    ] ) );
    
    $params = [];
    
    $params[ 'text' ] = array(
        'type' => 'text',
        'title' => 'Button',
        'std' => 'Click here',

        'section' => 'button',
        'section_title' => 'Button',
    );

    $params[ 'url' ] = array(
        'type' => 'text',
        'title' => 'URL',
    );

    $params[ 'target' ] = array(
        'type' => 'select',
        'title' => 'Open link in',
        'options' => [
            '_self' => 'Current tab',
            '_blank' => 'New tab',
        ],
        'std' => '_self',
    );

    $params[ 'icon' ] = array(
        'name' => 'Icon',
        'type' => 'text',
        'desc' => 'Enter fontawesome icon from <a href="https://fontawesome.com/icons/" target="_blank">this list</a> or feather icon from <a href="https://feathericons.com/" target="_blank">this list</a>',
        'placeholder' => 'Eg. arrow-right',
    );
    
    $params[ 'size' ] = array(
        'name' => 'Size',
        'type' => 'select',
        'options' => array(
            'tiny' => 'Tiny',
            'small' => 'Small',
            'normal' => 'Normal',
            'medium' => 'Medium',
            'large' => 'Large',
        ),
        'std' => 'normal',
    );

    $params[ 'style' ] = array(
        'name' => 'Style',
        'type' => 'select',
        'options' => array(
            'primary' => 'Primary',
            'outline' => 'Outline',
            'fill' => 'Fill',
            'black' => 'Black',
        ),
        'std' => 'black',
    );
    
    $params[ 'border_width' ] = array(
        'name' => 'Border Width',
        'type' => 'select',
        'options' => array(
            '' => 'Default',
            '0' => 'None',
            '1px' => '1px',
            '2px' => '2px',
            '3px' => '3px',
            '4px' => '4px',
            '5px' => '5px',
        ),
        'std' => '',
    );
    
    $params[ 'shape' ] = array(
        'name' => 'Shape',
        'type' => 'select',
        'options' => array(
            'square' => 'Square',
            'round' => 'Round',
            'pill' => 'Pill',
        ),
        'std' => 'square',
    );

    $params[ 'align' ] = array(
        'name' => 'Align',
        'type' => 'select',
        'options' => array(
            'inline' => 'Inline',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ),
        'std' => 'inline',
    );
    
    $params[ 'block' ] = array(
        'name' => 'Block Button',
        'type' => 'select',
        'options' => array(
            'none' => 'None',
            'full' => 'Full-width',
            'half' => 'Half-width',
            'third' => 'Third-width',
        ),
        'std' => 'none',
    );
    
    $params[ 'extra_class' ] = array(
        'name' => 'Extra Class',
        'type' => 'text',
        'desc' => 'Enter your custom CSS class',
    );
    
    $params[ 'attr' ] = array(
        'name' => 'Additional Attributes',
        'type' => 'textarea',
        'desc' => 'Enter your custom attributes here. Make sure you know what you are doing.',
    );
    
    /**
     * Custom Color Options
     */
    $params[ 'text_color' ] = array(
        'name' => 'Text Color',
        'type' => 'color',
        
        'section' => 'color',
        'section_title' => 'Color',
    );

    $params[ 'bg_color' ] = array(
        'name' => 'Background Color',
        'type' => 'color',
    );
    
    $params[ 'border_color' ] = array(
        'name' => 'Border Color',
        'type' => 'color',
    );
    
    $params[ 'text_color_hover' ] = array(
        'name' => 'Hover Text Color',
        'type' => 'color',
    );

    $params[ 'bg_color_hover' ] = array(
        'name' => 'Hover Background Color',
        'type' => 'color',
    );
    
    $params[ 'border_color_hover' ] = array(
        'name' => 'Hover Border Color',
        'type' => 'color',
    );
    
    // only include
    if ( ! empty( $include ) ) {
        foreach ( $params as $id => $param ) {
            if ( ! in_array( $id, $include ) ) unset( $params[ $id ] );
        }
    }
    
    // exclude
    if ( ! empty( $exclude ) ) {
        foreach ( $params as $id => $param ) {
            if ( in_array( $id, $exclude ) ) unset( $params[ $id ] );
        }
    }
    
    // override
    if ( ! empty( $override ) ) {
        foreach ( $override as $id => $param ) {
            $params[ $id ] = $param;
        }
    }
    
    // name vs title
    // and id
    foreach ( $params as $id => $param ) {
        
        // to use in widget / metabox
        $param[ 'id' ] = $id;
        
        // name vs title
        if ( isset( $param[ 'title' ] ) ) $param[ 'name' ] = $param[ 'title' ];
        elseif ( isset( $param[ 'name' ] ) ) $param[ 'title' ] = $param[ 'name' ];
        
        $params[ $id ] = $param;
        
    }
    
    return apply_filters( 'fox_btn_params', $params );
    
}
endif;

if ( ! function_exists( 'fox_btn' ) ) :
/**
 * Fox Button
 * @since 4.0
 */
function fox_btn( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'text' => 'Click Me',
        'url' => '',
        'target' => '',
        'icon' => '',
        'style' => '',
        'border_width' => '',
        'size' => '',
        
        'align' => '',
        'block' => '',
        'shape' => '',
        
        // custom attr
        'attr' => '',
        'extra_class' => '',
        
        // color
        'text_color' => '',
        'bg_color' => '',
        'border_color' => '',
        'text_color_hover' => '',
        'bg_color_hover' => '',
        'border_color_hover' => '',
        
        'id' => '',
        
    ] ) );
    
    if ( ! $text || ! $url ) return;
    
    $text_html = '<span class="btn-main-text">' . $text . '</span>';
    
    $attrs = []; $class = [ 'fox-btn' ]; $outer_class = [ 'fox-button' ];
    // custom attr
    if ( $attr ) {
        $attrs[] = $attr;
    }
    
    if ( $extra_class ) $class[] = $extra_class;
    
    // Style
    if ( ! in_array( $style, [ 'primary', 'black', 'outline', 'fill' ] ) ) $style = 'black';
    $class[] = 'btn-' . $style;
    
    // Size
    if ( ! in_array( $size, [ 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) $size = 'normal';
    $class[] = 'btn-' . $size;
    
    // icon
    $icon_html = '';
    if ( $icon ) {
        
        $icon = trim( strtolower( $icon ) );
        if ( 'feather-' == substr( $icon, 0, 8 ) ) {
            $ic = $icon;
        } elseif ( 'fa fa-' == substr( $icon, 0, 6 ) ) {
            $ic = $icon;
        } else {
            $ic = 'fa fa-' . $icon;
        }
        $icon_html = '<i class="' . esc_attr( $ic ) . '"></i>';
        
    }
    
    // target
    if ( '_blank' != $target ) $target = '_self';
    $attrs[] = 'target="' . $target . '"';
    
    // align
    if ( 'left' == $align || 'center' == $align || 'right' == $align ) $outer_class[] = 'button-align button-' . $align;
    else $outer_class[] = 'btn-inline';
    
    // shape
    if ( 'pill' != $shape && 'round' != $shape ) $shape = 'square';
    $class[] = 'btn-' . $shape;
    
    // block
    if ( 'full' == $block || 'half' == $block || 'third' == $block ) {
        $outer_class[] = 'button-block';
        $outer_class[] = 'button-block-' . $block;
    }
    
    // url
    $attrs[] = 'href="' . esc_attr( $url ) . '"';
    
    // class
    $attrs[] = 'class="' . esc_attr( join( ' ', $class ) ) . '"';
    
    // id
    if ( ! $id ) {
        $id = uniqid( 'button-id-' );
    }
    $attrs[] = 'id="' . esc_attr( $id ) . '"';
    
    // CUSTOM COLOR
    $css = [];
    if ( $text_color ) {
        $css[] = 'color:' . $text_color;
    }
    if ( $bg_color ) {
        $css[] = 'background:' . $bg_color;
    }
    if ( $border_color ) {
        $css[] = 'border-color:' . $border_color;
    }
    
    // CUSTOM COLOR
    $hover_css = [];
    if ( $text_color_hover ) {
        $hover_css[] = 'color:' . $text_color_hover;
    }
    if ( $bg_color_hover ) {
        $hover_css[] = 'background:' . $bg_color_hover;
    }
    if ( $border_color_hover ) {
        $hover_css[] = 'border-color:' . $border_color_hover;
    }
    
    // BORDER WIDTH
    if ( '' !== $border_width ) {
        $css[] = 'border-width:' . $border_width;
    }
    
    $style = [];
    if ( $css ) $style[] = '#' . $id . '{' . join( ';', $css ). '}';
    if ( $hover_css ) $style[] = '#' . $id . ':hover{' . join( ';', $hover_css ) . '}';
    
    if ( $style ) {
        echo '<style type="text/css">';
        echo join( '', $style );
        echo '</style>';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $outer_class ) ); ?>">

    <a <?php echo join( ' ', $attrs ); ?>><?php echo $text_html . $icon_html; ?></a>
    
</div>

<?php
    
}
endif;