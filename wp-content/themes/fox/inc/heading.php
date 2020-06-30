<?php
if ( ! function_exists( 'fox_heading_params' ) ) :
/**
 * Fox Heading Params
 * @since 4.0
 */
function fox_heading_params( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'include' => [],
        'exclude' => [],
        'override' => []
    ] ) );
    
    $params = [];
    
    $params[ 'title' ] = array(
        'type' => 'text',
        'title' => 'Title',
        'std' => 'Enter title here',

        'section' => 'heading',
        'section_title' => 'Heading',
    );
    
    $params[ 'title_tag' ] = array(
        'type' => 'select',
        'title' => 'Title tag',
        'options' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'p' => 'p',
        ],
        'std' => 'h2',
    );
    
    $params[ 'title_color' ] = array(
        'type' => 'color',
        'title' => 'Custom title color',
    );
    
    $params[ 'title_size' ] = array(
        'type' => 'select',
        'options' => [
            'large' => 'Large',
            'medium' => 'Medium',
            'normal' => 'Normal',
            'small' => 'Small',
            'tiny' => 'Tiny',
            'supertiny' => 'Supertiny',
        ],
        'std' => 'large',
        'title' => 'Title size',
    );
    
    $params[ 'title_text_transform' ] = array(
        'type' => 'select',
        'options' => [
            '' => 'Default',
            'none' => 'None',
            'uppercase' => 'UPPERCASE',
            'lowercase' => 'lowercase',
            'capitalize' => 'Capitalize',
        ],
        'std' => '',
        'title' => 'Text transform',
    );
    
    $params[ 'line' ] = array(
        'type' => 'select',
        'options' => [
            '' => 'None',
            '1px' => 'Solid 1px',
            '2px' => 'Solid 2px',
            '3px' => 'Solid 3px',
            '4px' => 'Solid 4px',
            'dashed' => 'Dashed',
            'double' => 'Double',
            'wave' => 'Wave',
        ],
        'std' => '',
        'title' => 'Horizontal Line',
    );

    $params[ 'subtitle' ] = array(
        'type' => 'text',
        'title' => 'Subtitle',
    );
    
    $params[ 'subtitle_tag' ] = array(
        'options' => [
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'p' => 'p',
        ],
        'std' => 'h3',
        'type' => 'select',
        'title' => 'Subtitle tag',
    );
    
    $params[ 'subtitle_color' ] = array(
        'type' => 'color',
        'title' => 'Custom subtitle color',
    );
    
    $params[ 'image' ] = array(
        'type' => 'media',
        'title' => 'Heading image',
    );
    
    $params[ 'image_width' ] = array(
        'type' => 'text',
        'title' => 'Image Width',
    );
    
    $params[ 'align' ] = array(
        'name' => 'Align',
        'type' => 'select',
        'options' => array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ),
        'std' => 'center',
    );

    $params[ 'url' ] = array(
        'type' => 'text',
        'title' => 'URL',
    );
    
    $params[ 'url_target' ] = array(
        'type' => 'select',
        'title' => 'Open link in',
        'options' => [
            '_self' => 'Current tab',
            '_blank' => 'New tab',
        ],
        'std' => '_self',
    );

    $params[ 'url_text' ] = array(
        'type' => 'text',
        'title' => 'Link text',
    );
    
    $params[ 'url_position' ] = array(
        'type' => 'select',
        'options' => [
            'next'  => 'Next to title',
            'right' => 'Right side',
        ],
        'std' => 'right',
        'title' => 'Link position',
    );
    
    $params[ 'extra_class' ] = array(
        'name' => 'Extra Class',
        'type' => 'text',
        'desc' => 'Enter your custom CSS class',
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
    
    return apply_filters( 'fox_heading_params', $params );
    
}
endif;

if ( ! function_exists( 'fox_heading' ) ) :
/**
 * Fox Heading
 * @since 4.0
 */
function fox_heading( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'title' => 'Enter title here',
        'title_tag' => '',
        'title_color' => '',
        'title_size' => '',
        'title_text_transform' => '',
        
        'subtitle' => '',
        'subtitle_tag' => '',
        'subtitle_color' => '',
        
        'url' => '',
        'url_target' => '',
        'url_text' => '',
        'url_position' => 'right',
        
        'image' => '',
        'image_width' => '',
        
        'line' => false,
        
        'align' => '',
        'extra_class' => '',
    ]) );
    
    $title = trim( $title );
    if ( $title == '' ) return;
    
    $class = [ 'fox-heading' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $title_attrs = $subtitle_attrs = [];
    $title_css = $subtitle_css = [];
    $title_class = [ 'heading-title-main' ];
    $subtitle_class = [ 'heading-subtitle-main' ];
    
    /* LINK
    -------------------- */
    $link_html = '';
    if ( $url && $url_text ) {
        
        if ( '_blank' != $url_target ) $url_target = '_self';
        $link_html = '<a href="' . esc_url( $url ) . '" target="' . esc_attr( $url_target ) . '">' . esc_html( $url_text ) . '</a>';
        $class[] = 'has-link';
        
        if ( 'next' != $url_position ) $url_position = 'right';
        $class[] = 'link-position-' . $url_position;
        
    }
    
    /* TITLE
    -------------------- */
    // color
    if ( $title_color ) {
        $title_css[] = 'color:' . $title_color;
        $title_class[] = 'custom-color';
    }
    
    // size
    if ( ! in_array( $title_size, [ 'large', 'medium', 'normal', 'small', 'tiny', 'supertiny' ] ) ) $title_size = 'large';
    $title_class[] = 'size-' . $title_size;
    
    // transform
    if ( $title_text_transform ) {
        $title_class[] = 'text-' . $title_text_transform;
    }
    
    // attrs
    if ( $title_css ) {
        $title_attrs[] = 'style="' . esc_attr( join( ';', $title_css ) ). '"';
    }
    $title_attrs[] = 'class="' . esc_attr( join( ' ', $title_class ) ) . '"';
    $title_attrs = join( ' ', $title_attrs );
    
    // title
    if ( ! in_array( $title_tag, [ 'h1', 'h2', 'h3', 'h4', 'p' ] ) ) $title_tag = 'h2';
    $line_html = '';
    if ( $line ) {
        $class[] = 'heading-line-' . $line;
        $line_html = '<span class="line line-left"></span><span class="line line-right"></span>';
    }
    $title_html = "<{$title_tag} {$title_attrs}>{$title}{$line_html}</{$title_tag}>";
    
    /* SUBTITLE
    -------------------- */
    // color
    if ( $subtitle_color ) {
        $subtitle_css[] = 'color:' . $subtitle_color;
        $subtitle_class[] = 'custom-color';
    }
    
    // attrs
    if ( $subtitle_css ) {
        $subtitle_attrs[] = 'style="' . esc_attr( join( ';', $subtitle_css ) ). '"';
    }
    $subtitle_attrs[] = 'class="' . esc_attr( join( ' ', $subtitle_class ) ) . '"';
    $subtitle_attrs = join( ' ', $subtitle_attrs );
    
    // subtitle
    $subtitle_html = '';
    $subtitle = trim( $subtitle );
    
    if ( $subtitle ) {
        
        if ( ! in_array( $subtitle_tag, [ 'h1', 'h2', 'h3', 'h4', 'p' ] ) ) $subtitle_tag = 'h3';
        $subtitle_html = "<{$subtitle_tag} {$subtitle_attrs}>{$subtitle}</{$subtitle_tag}>";
        
    }
    
    /* ALIGN
    -------------------- */
    if ( 'left' != $align && 'right' != $align ) $align = 'center';
    $class[] = 'align-' . $align;
    
    /* IMAGE
    -------------------- */
    $image_style = '';
    $img_html = '';
    if ( $image && isset( $image[ 'id' ] ) ) {
        $img_html = wp_get_attachment_image( $image[ 'id' ], 'full' );
        if ( $image_width ) {
            if ( is_numeric( $image_width ) ) {
                $image_width .= 'px';
            }
            $image_style = ' style="width:' . $image_width . '"';
        }
        if ( $img_html ) {
            $img_html = '<div class="heading-image-main"' . $image_style . '>' . $img_html . '</div>';
        }
    }
    
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <?php if ( $img_html ) { ?>
    <div class="heading-section heading-image">
        
        <?php echo $img_html; ?>
        
    </div>
    <?php } ?>
    
    <div class="heading-section heading-title">
        
        <?php echo $title_html; ?>
        <?php echo $link_html; ?>
    
    </div><!-- .heading-title -->
    
    <?php if ( $subtitle_html ) { ?>
    <div class="heading-section heading-subtitle">
        
        <?php echo $subtitle_html; ?>
    
    </div><!-- .heading-subtitle -->
    <?php } ?>

</div><!-- .fox-heading -->

<?php
    
}
endif;