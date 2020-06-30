<?php
if ( ! function_exists( 'fox_ad' ) ) :
/**
 * Displays a responsive ad
 * @since 4.0
 */
function fox_ad( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        // they are all attachment IDs, not url
        'img' => '',
        'image' => '', // alias
        'tablet' => '',
        'phone' => '',
        
        'width' => '',
        'tablet_width' => '',
        'phone_width' => '',
        
        'url' => '',
        'target' => '_blank',
        'code' => '',
        
        'extra_class' => '',
        'attrs' => '',
        
    ] ) );
    
    $class = [ 'fox-ad', 'responsive-ad', 'ad-container' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    $ad_html = '';
    
    if ( $image && ! $img ) $img = $image;
    
    if ( ! empty( $code ) ) {
        $ad_html = do_shortcode( $code );
        $class[] = 'ad-code';
    } else {
        
        $class[] = 'ad-banner';
        $open = $close = $img_html = $alt = '';
        $srcset = [];
        
        if ( $url ) {
            
            if ( '_self' != $target ) $target = '_blank';
            $open = '<a href="' . esc_url( $url ). '" target="' . $target . '"' . $attrs . '>';
            $close = '</a>';
            
        }

        if ( $phone ) {
            
            $phone_id = 0;
            
            if ( is_array( $phone ) ) {
                $phone = $phone[ 'url' ];
                $phone_id = attachment_url_to_postid( $phone );
            } elseif ( is_numeric( $phone ) ) {
                $phone_id = $phone;           
            } elseif ( is_string( $phone ) ) {
                $phone_id = attachment_url_to_postid( $phone );
            }
            
            if ( $phone_id ) {
                
                $phone_src = wp_get_attachment_url( $phone_id );
                
                if ( $phone_src ) {
                    // was 568px before version 4.3
                    $img_html .= '<source srcset="' . esc_url( $phone_src ) . '" media="(max-width: 600px)" />';
                }
            }

        }
        
        if ( $tablet ) {
            $tablet_id = 0;
            if ( is_array( $tablet ) ) {
                $tablet = $tablet[ 'url' ];
                $tablet_id = attachment_url_to_postid( $tablet );
            } elseif ( is_numeric( $tablet ) ) {
                $tablet_id = $tablet;
            } elseif ( is_string( $tablet ) ) {
                $tablet_id = attachment_url_to_postid( $tablet );
            }
            
            if ( $tablet_id ) {
                
                $tablet_src = wp_get_attachment_url( $tablet_id );
                
                if ( $tablet_src ) {
                    // was 768px before version 4.3
                    // was 782px before version 4.4
                    $img_html .= '<source srcset="' . esc_url( $tablet_src ) . '" media="(max-width: 840px)" />';
                }
            }

        }
        
        if ( $img ) {
            
            $img_id = 0;
            if ( is_array( $img ) ) {
                
                $img = $img[ 'url' ];
                $img_id = attachment_url_to_postid( $img );
                
            } elseif ( is_numeric( $img ) ) {
                
                $img_id = $img;
                
            } elseif ( is_string( $img ) ) {
                
                $img_id = attachment_url_to_postid( $img );
                
            }
            
            if ( $img_id ) {
                $img_src = wp_get_attachment_url( $img_id );
                if ( $img_src ) {
                    $img_html .= '<img src="' . esc_url( $img_src ) . '" />';
                }
            }
        }

        if ( $img_html ) {
            $id = uniqid( 'fox-ad-' );
            $img_html = '<picture id="' . esc_attr( $id ) . '">' . $img_html . '</picture>';
            
            // custom css
            $css = [];
            if ( $width ) {
                if ( is_numeric( $width ) ) $width .= 'px';
                $css[] = "#{$id}{width:{$width}}";
            }
            if ( $tablet_width ) {
                if ( is_numeric( $tablet_width ) ) $tablet_width .= 'px';
                $css[] = fox_get_query_screen_string_from_text( 'ipad1' ) . "{#{$id}{width:{$tablet_width}}}";
            }
            if ( $phone_width ) {
                if ( is_numeric( $phone_width ) ) $phone_width .= 'px';
                $css[] = fox_get_query_screen_string_from_text( 'iphone1' ) . "{#{$id}{width:{$phone_width}}}";
            }
            if ( $css ) {
                $img_html = '<style>' . join( '', $css ) . '</style>' . $img_html;
            }
            
        }
        
        if ( $img_html ) {
            $ad_html = $open . $img_html . $close;
        }
    
    }
    
    if ( ! $ad_html ) return;
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="banner-inner">

        <?php echo $ad_html; ?>
        
    </div><!-- .banner-inner -->
    
</div>
<?php
    
}
endif;

if ( ! function_exists( 'fox_ad_params' ) ) :
/**
 * Ad Params
 * @since 4.0
 */
function fox_ad_params( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'include' => [],
        'exclude' => [],
        'override' => []
    ] ) );
    
    $params = [];
    
    $params[ 'code' ] = array(
        'name' => esc_html__( 'Custom Ad Code', 'wi' ),
        'type' => 'textarea',
        
        'section' => 'settings',
        'section_title' => 'Settings',
    );
    
    $params[ 'image' ] = array(
        'id' => 'image',
        'name' => esc_html__( 'Image', 'wi' ),
        'type' => 'image',
    );
    
    $params[ 'width' ] = array(
        'id' => 'width',
        'name' => 'Image width',
        'type' => 'text',
    );
    
    $params[ 'tablet' ] = array(
        'id' => 'tablet',
        'name' => 'Tablet Image',
        'type' => 'image',
    );
    
    $params[ 'tablet_width' ] = array(
        'id' => 'tablet_width',
        'name' => 'Width tablet screen',
        'type' => 'text',
    );
    
    
    $params[ 'phone' ] = array(
        'id' => 'phone',
        'name' => 'Phone Image',
        'type' => 'image',
    );
    
    $params[ 'phone_width' ] = array(
        'id' => 'phone_width',
        'name' => 'Width phone screen',
        'type' => 'text',
    );
    
    $params[ 'url' ] = array(
        'id' => 'url',
        'name' => esc_html__( 'Link', 'wi' ),
        'type' => 'text',
    );
    
    $params[ 'target' ] = array(
        'name' => 'Open link in',
        'type' => 'select',
        'options' => [
            '_self' => 'Same tab',
            '_blank' => 'New tab',
        ],
        'std' => '_blank',
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
    
    return apply_filters( 'fox_ad_params', $params );
    
}
endif;

add_action( 'fox_before_entry_content', 'fox_append_single_ad_before', 10 );
/**
 * Append ad into single post
 * @since 4.0
 */
function fox_append_single_ad_before() {
    
    $args = [
        'code' => get_theme_mod( 'wi_single_before_code' ),
        'image' => get_theme_mod( 'wi_single_before_banner' ),
        'width' => get_theme_mod( 'wi_single_top_before_width' ),
        'tablet' => get_theme_mod( 'wi_single_before_banner_tablet' ),
        'phone' => get_theme_mod( 'wi_single_before_banner_phone' ),
        'url' => get_theme_mod( 'wi_single_before_banner_url' ),
        'target' => get_theme_mod( 'wi_single_before_banner_url_target', '_blank' ),
        
        'extra_class' => 'fox-ad-before single-component'
    ];
    
    fox_ad( $args );
    
}

add_action( 'fox_after_entry_content', 'fox_append_single_ad_after', 10 );
/**
 * Append ad into single post
 * @since 4.0
 */
function fox_append_single_ad_after() {
    
    $args = [
        'code' => get_theme_mod( 'wi_single_after_code' ),
        'image' => get_theme_mod( 'wi_single_after_banner' ),
        'width' => get_theme_mod( 'wi_single_after_banner_width' ),
        'tablet' => get_theme_mod( 'wi_single_after_banner_tablet' ),
        'phone' => get_theme_mod( 'wi_single_after_banner_phone' ),
        'url' => get_theme_mod( 'wi_single_after_banner_url' ),
        'target' => get_theme_mod( 'wi_single_after_banner_url_target', '_blank' ),
        
        'extra_class' => 'fox-ad-after single-component'
    ];
    
    fox_ad( $args );
    
}

add_action( 'fox_single_top', 'fox_single_top_ad', 20 );
/**
 * Ad at the very top of post
 * @since 4.3
 */
function fox_single_top_ad( $params ) {
    
    $args = [
        'code' => get_theme_mod( 'wi_single_top_code' ),
        'image' => get_theme_mod( 'wi_single_top_banner' ),
        'width' => get_theme_mod( 'wi_single_top_banner_width' ),
        'tablet' => get_theme_mod( 'wi_single_top_banner_tablet' ),
        'phone' => get_theme_mod( 'wi_single_top_banner_phone' ),
        'url' => get_theme_mod( 'wi_single_top_banner_url' ),
        'target' => get_theme_mod( 'wi_single_top_banner_url_target', '_blank' ),
        
        'extra_class' => 'fox-ad-top'
    ];
    
    ob_start();
    fox_ad( $args );
    $ad = ob_get_clean();
    
    if ( $ad ) {
        echo fox_format( '<div class="single-big-section single-big-section-ad single-big-section-top-ad"><div class="container">{ad}</div></div>', [
            'ad' => $ad,
        ] );
    }
    
}