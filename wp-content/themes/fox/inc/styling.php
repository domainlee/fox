<?php
if ( ! function_exists( 'fox_accent_color_css' ) ) :
/**
 * Generates Accent Color CSS
 * @since 4.0
 */
function fox_accent_color_css() {
    
    $css = '';
    
    $color = '.social-list.style-plain:not(.style-text_color) a:hover, .wi-pagination a.page-numbers:hover, .wi-mainnav ul.menu ul > li:hover > a, .wi-mainnav ul.menu ul li.current-menu-item > a, .wi-mainnav ul.menu ul li.current-menu-ancestor > a, .related-title a:hover, .page-links > a:hover, .widget_archive a:hover, .widget_nav_menu a:hover, .widget_meta a:hover, .widget_recent_entries a:hover, .widget_categories a:hover, .tagcloud a:hover, .header-cart a:hover, .woocommerce .star-rating span:before, .null-instagram-feed .clear a:hover, .widget a.readmore:hover';
    
    $bg = 'html .mejs-controls .mejs-time-rail .mejs-time-current, .dropcap-color, .style--dropcap-color .enable-dropcap .dropcap-content > p:first-of-type:first-letter, .style--dropcap-color p.has-drop-cap:not(:focus):first-letter, .fox-btn.btn-primary, button.btn-primary, input.btn-primary[type="button"], input.btn-primary[type="reset"], input.btn-primary[type="submit"], .social-list.style-black a:hover, .post-item-thumbnail:hover .video-indicator-solid, a.more-link:hover, .post-newspaper .related-thumbnail, .style--slider-navtext .flex-direction-nav a:hover, .review-item.overall .review-score, #respond #submit:hover, .wpcf7-submit:hover, #footer-search .submit:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce span.onsale, .woocommerce ul.products li.product .onsale, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce a.add_to_cart_button:hover, .woocommerce #review_form #respond .form-submit input:hover, .header-cart-icon a .num, .blog-widget-small .thumbnail-index';
    
    $border = '.review-item.overall .review-score, .partial-content, .null-instagram-feed .clear a:hover';
    
    $accent = get_theme_mod( 'wi_accent' );
    if ( $accent ) {
        $css .= "$color{color:$accent}";
        $css .= "$bg{background-color:$accent}";
        $css .= "$border{border-color:$accent}";
    }
    
    // progress color
    $css .= '.reading-progress-wrapper::-webkit-progress-value{background-color:' . $accent . '}';
    $css .= '.reading-progress-wrapper::-moz-progress-value{background-color:' . $accent . '}';
    
    return $css;
    
}
endif;

add_filter( 'fox_css', 'fox_add_accent_color_css' );
function fox_add_accent_color_css( $css ) {
    
    return fox_accent_color_css() . $css;
    
}

if ( ! function_exists( 'fox_css_font_output' ) ) :
/*
 * Font Output
 * return [
    'css' => 'the final CSS to add after CSS string',
    '@font-face' => 'CSS RULE string TO add to before of CSS string',
 ]
 * if we don't use local fonts, fontface will be empty
 * @since 4.0
 */
function fox_css_font_output() {
    
    $google_fonts = fox_google_fonts();
    $normal_fonts = fox_normal_fonts();
    $return = [];
    $css = [];
    $fontface = [];
    $font_css_array = [];
    
    $selector_to_value = [];
    
    $all_positions = fox_all_font_support();
    $primary_positions = fox_primary_font_support();
    $primary_array = [];
    foreach ( $primary_positions as $key => $val ) {
        $primary_array[] = 'font_' . $key;
    }
    
    $font_face_rules = '';
    $local_fonts = [];
    
    $font_faces = [];
    
    foreach ( $all_positions as $id => $fontdata ) {
        
        extract( wp_parse_args( $fontdata, [
            'std' => '',
            'selector' => '',
        ] ) );
        
        $selector_arr = explode( ',', $selector );
        $selector_arr = array_map( 'trim', $selector_arr );
        
        $source = 'standard';
        
        // this means it's not a font face element, may be font size element
        if ( ! $std ) continue;
        
        /**
         * for important positions: body, heading, nav, logo
         * we allow to upload custom font
         */
        if ( isset( $fontdata[ 'primary' ] ) && $fontdata[ 'primary' ] ) {
            
            $source = get_theme_mod( "wi_{$id}_font_source", 'standard' );
            
            /* Local Font
            ------------------------------------ */
            if ( 'local' == $source ) {
            
                $woff2 = trim( get_theme_mod( "wi_{$id}_font_upload_woff2" ) );
                $woff = trim( get_theme_mod( "wi_{$id}_font_upload_woff" ) );
                $fontface = '';
                
                if ( $woff2 && $woff ) {

                    $fontface = trim( get_theme_mod( "wi_{$id}_custom_font" ) );
                    
                    if ( ! $fontface ) {
                        
                        $pathinfo = pathinfo( $woff2 );
                        $fontface = sanitize_title_with_dashes ( $pathinfo[ 'filename' ] );
                        if ( ! $fontface ) {
                            $pathinfo = pathinfo( $woff );
                            $font = sanitize_title_with_dashes ( $pathinfo[ 'filename' ] );
                        }
                    }
                    
                    // we create a random name
                    if ( ! $fontface ) {
                        $fontface = 'fox-' . $id . '-font';
                    }

                    $fallback = get_theme_mod( "wi_{$id}_fallback_font" );
                    if ( 'serif' != $fallback && 'cursive' != $fallback && 'monospace' != $fallback ) $fallback = 'sans-serif';

                    /* prevent duplication from loading font twice */
                    if ( ! in_array( $fontface, $local_fonts ) ) {
                        $font_face_rules .= "@font-face {font-family: {$fontface}; src: url({$woff2}) format('woff2'), url({$woff}) format('woff');}";
                    }
                    
                    // add to local font array
                    $local_fonts[] = $fontface;
                    
                    $fontface = '"' . $fontface . '"' . ", {$fallback}";
                    
                }
            
            }
            
        }
        
        if ( 'standard' == $source ) {
            
            // if google font
            $value = trim( get_theme_mod( "wi_{$id}_font", $std ) );
            
            // if it's font_heading, font_body then just leave it
            // to treat it later
            if ( in_array( $value, $primary_array ) ) {
                if ( ! isset( $font_css_array[ $value ] ) ) {
                    $font_css_array[ $value ] = [];
                }
                $font_css_array[ $value ][] = $selector;
                
                foreach ( $selector_arr as $selector_item ) {
                    $selector_to_value[ $selector_item ] = $value;
                }
                
                continue;
            }

            // now start analyze it,
            // value is like: Open Sans:400, 400i
            $explode = explode( ':', $value );
            $face = $explode[0];
            $styles = isset( $explode[1] ) ? $explode[1] : '';

            $face = str_replace('+', ' ', $face );
            $face = preg_replace( '/\s/', ' ', $face ); // replace all white spaces by simple white space
            $face = ucwords( $face ); // open sans ==> Open Sans
            
            // now we get font face
            $fontface = $face;
            
            if ( isset( $normal_fonts[ $fontface ] ) ) {
                
                $fontface = '"' . $normal_fonts[ $fontface ][ 'face' ] . '",' . $normal_fonts[ $fontface ][ 'category' ] ;
                
            } elseif ( isset( $google_fonts[ $fontface ] ) ) {
                
                $cat = $google_fonts[ $fontface ][ 'category' ];
                if ( 'handwriting' == $cat || 'display' == $cat ) {
                    $fallback = 'cursive';
                } else {
                    $fallback = $cat;
                }
                
                $fontface = '"' . $fontface . '",' . $fallback;
                
            }
        
        }
        
        // now assign it for reuse later
        $font_faces[ $id ] = $fontface;
        
        if ( ! isset( $font_css_array ) ) {
            $font_css_array[ $fontface ] = [];
        }
        $font_css_array[ $fontface ][] = $selector;
        
        foreach ( $selector_arr as $selector_item ) {
            $selector_to_value[ $selector_item ] = $fontface;
        }
        
        
    } // each ID
    
    // print_r ( $selector_to_value );
    $final_css_array = [];
    foreach ( $selector_to_value as $selector_item => $value ) {
        $face = '';
        if ( 'font_body' == $value ) {
            $face = isset( $selector_to_value[ '.font-body' ] ) ? $selector_to_value[ '.font-body' ] : '';
        } elseif ( 'font_heading' == $value ) {
            $face = isset( $selector_to_value[ '.font-heading' ] ) ? $selector_to_value[ '.font-heading' ] : '';
        } elseif ( 'font_nav' == $value ) {
            $face = isset( $selector_to_value[ '.wi-mainnav ul.menu > li > a' ] ) ? $selector_to_value[ '.wi-mainnav ul.menu > li > a' ] : '';
        } else {
            $face = $value;
        }
        
        if ( ! isset( $final_css_array[ $face ] ) ) $final_css_array[ $face ] = [];
        $final_css_array[ $face ][] = $selector_item;
        
    }
    
    $css = '';
    foreach ( $final_css_array as $face => $selectors ) {
        $selectors = join( ',', $selectors );
        $css .= $selectors . '{font-family:' . $face . '}';
    }
    
    return [
        'css' => $css,
        '@font-face' => $font_face_rules,
    ];
    
}
endif;

add_filter( 'fox_css', 'fox_add_typography_output', 0 );
function fox_add_typography_output( $css ) {
    
    $css .= fox_typography_output();
    return $css;
    
}

/**
 * Typography Engine to generate typography correctly
 * this generates all typography values but font family
 * @since 4.0
 */
function fox_typography_output() {
    
    $all = fox_all_font_support();
    
    $ipad1 = fox_get_query_screen_string_from_text( 'ipad1' );
    $iphone1 = fox_get_query_screen_string_from_text( 'iphone1' );
    
    $media_query_arr = [
        'all' => [],
        $ipad1 => [],
        $iphone1 => [],
    ];
    
    foreach ( $all as $id => $fontdata ) {
        
        extract( wp_parse_args( $fontdata, [
            'selector' => '',
            'typo_selector' => '', // the selector only for typo, if set the selector will be skipped
            'typo' => '',
            'fields' => []
        ]  ) );
        
        if ( $typo_selector ) {
            $selector = $typo_selector;
        }
        if ( ! $selector ) continue;
        $typography = get_theme_mod( 'wi_' . $id . '_typography', $typo );
        
        if ( ! $typography ) continue;
        
        try {
            $typography = json_decode( $typography, true );
        } catch ( Exception $err ) {
            $typography = [];
        }
        
        if ( ! $typography ) continue;
        
        $typography = wp_parse_args( $typography, [
            'font-size' => '',
            'font-size-tablet' => '',
            'font-size-phone' => '',
            
            'font-weight' => '',
            'font-style' => '',
            'text-transform' => '',
            'letter-spacing' => '',
            'line-height' => '',
        ] );
        
        foreach ( $typography as $prop => $val ) {
            
            $val = trim( $val );
            if ( '' === $val ) continue;
            
            // unit
            if ( in_array( $prop, [ 'font-size', 'font-size-tablet', 'font-size-phone', 'letter-spacing' ] ) ) {
                if ( is_numeric( $val ) ) $val .= 'px';
            }
            
            if ( 'font-size-tablet' == $prop ) {
                
                if ( ! isset( $media_query_arr[ $ipad1 ][ $selector ] ) ) {
                    $media_query_arr[ $ipad1 ][ $selector ] = [];
                }
                $media_query_arr[ $ipad1 ][ $selector ][] = 'font-size:' . $val;
                
            } elseif ( 'font-size-phone' == $prop ) {
                
                if ( ! isset( $media_query_arr[ $iphone1 ][ $selector ] ) ) {
                    $media_query_arr[ $iphone1 ][ $selector ] = [];
                }
                
                $media_query_arr[ $iphone1 ][ $selector ][] = 'font-size:' . $val;
            } else {
                
                if ( ! isset( $media_query_arr[ 'all' ][ $selector ] ) ) {
                    $media_query_arr[ 'all' ][ $selector ] = [];
                }
                
                $media_query_arr[ 'all' ][ $selector ][] = "{$prop}:{$val}";
            }
            
        }
    
    }
    
    $return = '';
    foreach ( $media_query_arr as $query => $css_pieces ) {
        $inner = '';
        foreach ( $css_pieces as $selector => $props ) {
            $inner .= $selector . '{' . join( ';', $props ) . '}';
        }
        if ( 'all' == $query ) {
            $return .= $inner;
        } else {
            $return .= $query . '{' . $inner . '}';
        }
    }
    
    return $return;
    
}

add_filter( 'fox_css', 'fox_add_box_css_output', 0 );
function fox_add_box_css_output( $css ) {
    
    $css .= fox_box_css_output();
    return $css;
    
}

/**
 * Box CSS Engine to generate padding, margin, border CSS correctly
 * @since 4.0
 */
function fox_box_css_output() {
    
    $all = fox_all_box_elements_support();
    
    $ipad1 = fox_get_query_screen_string_from_text( 'ipad1' );
    $iphone1 = fox_get_query_screen_string_from_text( 'iphone1' );
    
    $media_query_arr = [
        'all' => [],
        $ipad1 => [],
        $iphone1 => [],
    ];
    
    foreach ( $all as $id => $boxdata ) {
        
        extract( wp_parse_args( $boxdata, [
            'selector' => '',
            'std' => [],
        ]  ) );
        
        if ( ! $selector ) continue;
        $std = (array) $std;
        $std = json_encode( $std );
        
        $box = get_theme_mod( 'wi_' . $id . '_box', $std );
        if ( ! $box ) continue;
        
        try {
            $box = json_decode( $box, true );
        } catch ( Exception $err ) {
            $box = [];
        }
        
        if ( ! $box ) continue;
        
        // $box now is an array: margin-top => .. margin-left => .., border-left-width => .. etc
        //
        foreach ( $box as $prop => $val ) {
            
            $val = trim( $val );
            if ( '' === $val ) continue;
            
            // unit
            if ( 'border-style' != $prop && 'border-color' != $prop ) {
                if ( is_numeric( $val ) ) $val .= 'px';
            }
            
            if ( false !== strpos( $prop, 'tablet-' ) ) {
                $query = $ipad1;
                $prop = str_replace( 'tablet-', '', $prop );
            } elseif ( false !== strpos( $prop, 'phone-' ) ) {
                $query = $iphone1;
                $prop = str_replace( 'phone-', '', $prop );
            } else {
                $query = 'all';
            }
            
            if ( ! isset( $media_query_arr[ $query ][ $selector ] ) ) {
                $media_query_arr[ $query ][ $selector ] = [];
            }
            $media_query_arr[ $query ][ $selector ][] = $prop . ':' . $val;
            
        }
    
    }
    
    $return = '';
    foreach ( $media_query_arr as $query => $css_pieces ) {
        $inner = '';
        foreach ( $css_pieces as $selector => $props ) {
            $inner .= $selector . '{' . join( ';', $props ) . '}';
        }
        if ( 'all' == $query ) {
            $return .= $inner;
        } else {
            $return .= $query . '{' . $inner . '}';
        }
    }
    
    return $return;
    
}

add_filter( 'fox_css', 'fox_add_background_css_output', 0 );
function fox_add_background_css_output( $css ) {
    
    $css .= fox_background_css_output();
    return $css;
    
}

/**
 * Background CSS Engine to generate background css properties: color, image etc
 * @since 4.0
 */
function fox_background_css_output() {
    
    $all = fox_all_background_elements_support();
    $media_query_arr = [
        'all' => []
    ];
    
    foreach ( $all as $id => $boxdata ) {
        
        extract( wp_parse_args( $boxdata, [
            'selector' => '',
            'std' => [],
        ]  ) );
        
        if ( ! $selector ) continue;
        $std = (array) $std;
        $std = json_encode( $std );
        
        $box = get_theme_mod( 'wi_' . $id . '_background', $std );
        if ( ! $box ) continue;
        
        try {
            $box = json_decode( $box, true );
        } catch ( Exception $err ) {
            $box = [];
        }
        
        if ( ! $box ) continue;
        
        foreach ( $box as $prop => $val ) {
            
            $val = trim( $val );
            if ( '' === $val ) continue;
            
            $query = 'all';
            if ( 'background-image' == $prop ) {
                if ( is_numeric( $val ) ) {
                    $val = wp_get_attachment_url( $val ); // try to get image URL from ID
                }
                if ( ! $val ) continue;
                $val = 'url(' . esc_url( $val ) . ')';
            }
            
            if ( 'background-size' == $prop && 'custom' == $val ) {
                continue;
            }
            if ( 'background-size-custom' == $prop ) {
                $prop = 'background-size';
            }
            
            if ( ! isset( $media_query_arr[ $query ][ $selector ] ) ) {
                $media_query_arr[ $query ][ $selector ] = [];
            }
            $media_query_arr[ $query ][ $selector ][] = $prop . ':' . $val;
            
        }
    
    }
    
    $return = '';
    foreach ( $media_query_arr as $query => $css_pieces ) {
        $inner = '';
        foreach ( $css_pieces as $selector => $props ) {
            $inner .= $selector . '{' . join( ';', $props ) . '}';
        }
        if ( 'all' == $query ) {
            $return .= $inner;
        } else {
            $return .= $query . '{' . $inner . '}';
        }
    }
    
    return $return;
    
}

add_action( 'wp_enqueue_scripts', 'fox_customizer_style', 20 );
if ( ! function_exists( 'fox_customizer_style' ) ) :
/**
 * Prints inline style from Customizer
 * @since 1.0
 */
function fox_customizer_style() {
    
    $css = '';
    $unit_arr = fox_unit_array();
    $style_arr = array();
    
    // this will be the most outer wrapper of array
    // like: standard => CSS rules, max-1024 => CSS rules...
    $media_query_arr = array();
    $css = '';
    
    $options = fox_css_options();
    
    $defaults = array(
        'selector'  => '',
        'property'  => '',
        'unit'      => '',
        'conditional' => null,
        'screen'    => '',
        'max_screen'=> '',
        'std'       => null,
    );
    
    $font_output = fox_css_font_output();
    
    // add font face first
    $css .= $font_output[ '@font-face' ];
    
    /* Normal Options
    --------------------- */
    foreach ( $options as $id => $css_arr ) {
    
        if ( is_numeric( $id ) ) continue;
    
        // array detect
        if ( isset( $css_arr[ 'property' ] ) ) {
            $css_arr = array( $css_arr );
        }
    
        foreach ( $css_arr as $option ) {

            extract( wp_parse_args( $option, $defaults ) );

            // Conditional CSS
            if ( is_callable( $conditional ) && ! call_user_func( $conditional ) ) continue;

            if ( in_array( $property, $unit_arr ) && '' == $unit )
                $unit = 'px';

            // just a convenstion
            // id with custom at its tag has been processed
            // adjust value accordingly
            $value = null;

            if ( null === $value ) {
                if ( null !== $std ) {
                    $value = trim( get_theme_mod( $id, $std ) );
                } else {
                    $value = trim( get_theme_mod( $id ) );
                }
            }

            if ( '' === $value ) continue;

            if ( ! $selector || ! $property ) continue;
            if ( '' != $unit && is_numeric( $value ) ) {
                $value .= $unit;
            }
            if ( 'background-image' == $property ) {
                $value = "url({$value})";
            }

            if ( 'content' == $property ) {
                $value = str_replace( '"', '', $value );
                $value = str_replace( "'", '', $value );
                $value = '"' . $value . '"';
            }

            // css3
            $properties = array( $property );
            switch( $property ) {
                case 'background-size':
                    $properties = array( '-webkit-background-size', 'background-size' );
                break;
                case 'transition':
                    $properties = array( '-webkit-transition', 'transition' );
                break;
                case 'transform':
                    $properties = array( '-webkit-transform', 'transform' );
                break;
                default:
                break;
            }
            
            // screen
            $query = 'all';
            if ( $screen && $max_screen ) {
                $query = "@media only screen and (min-width: {$screen}) and (max-width: {$max_screen})";
            } elseif ( $screen ) {
                $query = "@media only screen and (min-width: {$screen})";
            } elseif ( $max_screen ) {
                $query = "@media only screen and (max-width: {$max_screen})";
            }

            if ( ! isset( $media_query_arr[ $query ] ) ) {
                $media_query_arr[ $query ] = array();
            }
            if ( ! isset( $media_query_arr[ $query ][ $selector ] ) ) {
                $media_query_arr[ $query ][ $selector ] = array();
            }

            foreach ( $properties as $property ) {
                $media_query_arr[ $query ][ $selector ][] = "{$property}:{$value}";
            }

        }
    
    } // foreach $option
    
    /* Slogan letter spacing
    --------------------- */
    $slogan_spacing = get_theme_mod( 'wi_slogan_spacing' );
    if ( '' != $slogan_spacing ) {
        $slogan_spacing = floatval( $slogan_spacing );
        $media_query_arr[ fox_get_query_screen_string_from_text( 'ipad1' ) ][ '.slogan' ][] = 'letter-spacing:' . ( $slogan_spacing * .9 ) . 'px';
        $media_query_arr[ fox_get_query_screen_string_from_text( 'ipad2' ) ][ '.slogan' ][] = 'letter-spacing:' . ( $slogan_spacing * .5 ) . 'px';
    }
    
    /* Content BG Opacity
    --------------------- */
    $opacity = trim( get_theme_mod( 'wi_content_background_opacity' ) );
    if ( $opacity != '' ) {
        $media_query_arr[ 'all' ][ '.wrapper-bg-element' ][] = 'opacity:' . absint( $opacity ) / 100 . ';';
    }
    
    /* Content Width
    --------------------- */
    $content_width = get_theme_mod( 'wi_content_width', 1080 );
    $content_width = absint( $content_width );
    if ( $content_width < 1020 ) $content_width = 1020;
    if ( $content_width > 1280 ) $content_width = 1280;
    
    if ( $content_width > 1100 ) {
        $media_query_arr[ 'all' ][ 'body .elementor-section.elementor-section-boxed>.elementor-container' ] = [
            'max-width:' . ( $content_width + 40 ) . 'px'
        ];
    }
    
    $media_query_arr[ '@media (min-width: 1200px)' ][ '.container,.cool-thumbnail-size-big .post-thumbnail' ][] = 'width:' . $content_width . 'px';
    $media_query_arr[ '@media (min-width: 1200px)' ][ 'body.layout-boxed .wi-wrapper' ][] = 'width:' . ( $content_width + 60 ) . 'px';
    
    /*
    $media_query_arr[ '@media (min-width: 1200px)' ][ '.thumbnail-stretch-bigger .thumbnail-container' ][] = 'width:' . ( $content_width + 120 ) . 'px';
    */
    
    $sidebar_w = get_theme_mod( 'wi_sidebar_width', 265 );
    if ( $sidebar_w ) {
        
        $sidebar_w = absint( $sidebar_w );
        $media_query_arr[ '@media (min-width:1024px)' ][ '.secondary, .section-secondary' ][] = 'width:' . ( 100* $sidebar_w /$content_width ) . '%';
        $media_query_arr[ '@media (min-width:1024px)' ][ '.has-sidebar .primary, .section-has-sidebar .section-primary, .section-sep' ][] = 'width:' . ( 100 * ( 1 - $sidebar_w /$content_width ) ) . '%';
        
    }
    
    /* Join CSS pieces
    --------------------- */
    foreach ( $media_query_arr as $query => $style_arr ) {
        
        if ( 'all' === $query ) {
            $open = $close = '';
        } else {
            $open = "{$query} {";
            $close = "}";
        }
        
        $css .= $open;
        
        foreach ( $style_arr as $selector => $pairs ) {
            $inside = join( ';', $pairs );
            $css .= "{$selector}{{$inside}}";
        }
        
        $css .= $close;
        
    }
    
    /* FONT PROBLEM
     * just add font css string
    --------------------- */
    $css .= $font_output[ 'css' ];
    
    /* Selection Color
    ----------------------- */
    $selection_color = trim ( get_theme_mod( 'wi_selection_background' ) );
    if ( $selection_color ) {
        
        $selection_text_color = trim( get_theme_mod( 'wi_selection_text_color' ) );
        
        $css .= '::-moz-selection {';
        $css .= "background:{$selection_color};";
        if ( $selection_text_color ) $css .= "color:{$selection_text_color};";
        $css .= '}';
        
        $css .= '::selection {';
        $css .= "background:{$selection_color};";
        if ( $selection_text_color ) $css .= "color:{$selection_text_color};";
        $css .= '}';
        
    }
    
    /* CSS Hook
    --------------------- */
    $css = apply_filters( 'fox_css', $css );

    // attach it to <head />
    wp_add_inline_style( 'style', $css );

}
endif;

if ( ! function_exists( 'fox_css_properties' ) ) :
/**
 * Returns array of css properties may we'll need to check it
 *
 * @since 1.0
 *
 * @return array of css properties 
 */
function fox_css_properties() {

    return array( 'color', 'background', 'background-color', 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-attachment', 'border', 'border-style', 'border-color', 'border-width', 'border-radius', 'margin', 'padding', 'width', 'height', 'font-size', 'font-family', 'font-weight', 'font-style', 'text-transform', 'letter-spacing', 'text-decoration', 'text-align', 'line-height', 'box-shadow', 'opacity', 'transition', 'content', 'top', 'right', 'bottom', 'left' );
    
}
endif;

if ( ! function_exists( 'fox_unit_array' ) ) :
/**
 * Returns array of css properties having px as default unit
 *
 * @since 1.0
 */
function fox_unit_array() {
    
    return array( 'font-size', 'background-size', 'border-width', 'border-radius', 'border-top-right-radius', 'border-top-left-radius', 'border-bottom-right-radius', 'border-bottom-left-radius', 'margin', 'margin-top', 'margin-right','margin-bottom', 'margin-left', 'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left', 'width', 'height', 'letter-spacing' );
    
}
endif;

if ( ! function_exists( 'fox_css_options' ) ) :
/**
 * Lists of css properties
 *
 * We'll render this function by tool 
 * so plz do not edit this function in your child theme
 *
 * @since 1.0
 */
function fox_css_options() {
    
    include get_template_directory() . '/inc/customizer/css-options.php';
    include get_template_directory() . '/inc/customizer/toggles.php';
    
    // list of elements will be ignored by toggle conditional
    $ignores = array();
    $options = array();
    
    foreach ( $toggles as $id => $option ) {
        
        $toggle = $option[ 'toggle' ];
        $choices = $option[ 'options' ];
        
        $real_value = get_theme_mod( $id );
        if ( '' == $real_value && isset( $option[ 'std' ] ) ) $real_value = $option[ 'std' ];
        
        $not_exclude = array();
        if ( isset( $toggle[ $real_value ] ) ) {
            $not_exclude = $toggle[ $real_value ];
            if ( is_string( $not_exclude ) ) $not_exclude = array( $not_exclude );
        }

        foreach ( $toggle as $val => $dependent_elements ) {

            // don't care about real value
            if ( $val === $real_value ) continue;

            if ( is_string( $dependent_elements ) ) $dependent_elements = array( $dependent_elements );
            foreach ( $dependent_elements as $dependent_element ) {

                // not intersect with the real value
                if ( ! in_array( $dependent_element, $not_exclude ) ) {
                    $ignores[] = $dependent_element;
                }

            }
        }
    
    }
    
    foreach ( $reg_options as $id => $option ) {
        
        if ( in_array( $id, $ignores ) ) continue;
        $options[ $id ] = $option;
        
    }
    
    return $options;
    
}
endif;

add_filter( 'fox_css', 'fox_page_css' );
if ( ! function_exists( 'fox_page_css' ) ) : 
/**
 * Single Post/Page CSS
 * @since 4.0
 */
function fox_page_css( $css ) {

    $postid = fox_page_id();
    
    if ( ! $postid ) return $css;
    
    $properties = [
        'padding_top' => [
            'selector' => 'body .wi-content',
            'property' => 'padding-top',
        ],
        'padding_bottom' => [
            'selector' => 'body .wi-content',
            'property' => 'padding-bottom',
        ],
    ];
    
    $css_arr = [];
    
    foreach ( $properties as $id => $data ) {
        
        extract( wp_parse_args( $data, [
            'selector' => '',
            'property' => '',
        ] ) );
        
        if ( ! $selector || ! $property ) continue;
        
        $value = trim( get_post_meta( $postid, '_wi_' . $id, true ) );
        if ( null === $value || '' === $value ) continue;
        
        if ( ! isset( $css_arr[ $selector ] ) ) {
            $css_arr[ $selector ] = [];
        }
        
        if ( is_numeric( $value ) ) {
            $value .= 'px';
        }
        
        $css_arr[ $selector ][] = "{$property}:{$value}";
        
    }
    
    foreach ( $css_arr as $selector => $pieces ) {
        if ( empty( $pieces ) ) continue;
        $css .= $selector . '{' . join( ';', $pieces ) . '}';
    }
    
    // $css .= 'body{color:red;}';
    
    return $css;
    
}
endif;

if ( ! function_exists( 'fox_body_selector' )  ) :
/**
 * Body Selector
 * @since 4.0
 */
function fox_body_selector() {
    
    return apply_filters( 'fox_body_selector', 'body, .font-body' );
    
}
endif;

if ( ! function_exists( 'fox_heading_selector' )  ) :
/**
 * Heading Selector
 * @since 4.0
 */
function fox_heading_selector() {
    
    return apply_filters( 'fox_heading_selector', '.font-heading, h1, h2, h3, h4, h5, h6, .wp-block-quote.is-large cite, .wp-block-quote.is-style-large cite, .fox-btn, button, input[type="button"], input[type="reset"], input[type="submit"], .fox-term-list, .wp-block-cover-text, .title-label, .thumbnail-view, .post-item-meta, .standalone-categories, .readmore, a.more-link, .post-big a.more-link, .style--slider-navtext .flex-direction-nav a, .min-logo-text, .page-links-container, .authorbox-nav, .post-navigation .post-title, .review-criterion, .review-score, .review-text, .commentlist .fn, .reply a, .widget_archive, .widget_nav_menu, .widget_meta, .widget_recent_entries, .widget_categories, .widget_rss > ul a.rsswidget, .widget_rss > ul > li > cite, .widget_recent_comments, .widget_recent_entries, #backtotop, .view-count, .tagcloud' );

}
endif;

if ( ! function_exists( 'fox_nav_selector' )  ) :
/**
 * Navigation Selector
 * @since 4.0
 */
function fox_nav_selector() {
    
    return apply_filters( 'fox_nav_selector', '.wi-mainnav ul.menu > li > a, .footer-bottom .widget_nav_menu, #footernav, .offcanvas-nav' );

}
endif;

function fox_nav_submenu_selector() {
    
    return apply_filters( 'fox_nav_submenu_selector', '.wi-mainnav ul.menu ul, .header-builder .widget_nav_menu  ul.menu ul' );
    
}

if ( ! function_exists( 'fox_logo_selector' )  ) :
/**
 * Logo Selector
 * @since 4.0
 */
function fox_logo_selector() {
    
    return apply_filters( 'fox_logo_selector', '.fox-logo, .min-logo-text, .mobile-logo-text' );

}
endif;

function fox_btn_selector() {
    
    return apply_filters( 'fox_btn_selector', '.fox-btn, button, input[type="button"], input[type="reset"], input[type="submit"], .article-big .readmore' );

}

function fox_input_selector() {
    
    return apply_filters( 'fox_input_selector', '.fox-input, input[type="color"], input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"], input:not([type]), textarea' );

}

/**
 * since 4.3
 */
function fox_dropcap_selector() {
    
    return apply_filters( 'fox_dropcap_selector', '.wi-dropcap,.enable-dropcap .dropcap-content > p:first-of-type:first-letter, p.has-drop-cap:not(:focus):first-letter' );
    
}

function fox_input_focus_selector() {
    
    $return = [];
    $input = fox_input_selector();
    $inputs = explode( ',', $input );
    foreach ( $inputs as $input ) {
        $return[] = trim( $input ) . ':focus';
    }
    
    return join( ', ', $return );

}

/**
 * Border elements
 * @since 4.0
 */
function fox_border_selector() {
    
    return apply_filters( 'fox_border_selector', 'table, td, th, .fox-input, input[type="color"], input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"], input:not([type]), textarea, textarea, select, .style--tag-block .fox-term-list a, .fox-slider-rich, .pagination-inner, .post-sep, .blog-related, .blog-related .line, .post-newspaper .related-area, .authorbox-simple, .post-nav-simple, #footer-widgets, #footer-bottom, .commentlist ul.children, .hero-half, .commentlist li + li > .comment-body, .classic-main-header-top .container, .related-heading, .comments-title, .comment-reply-title, .header-sticky-style-border .header-sticky-element.before-sticky, .widget_archive ul, .widget_nav_menu ul, .widget_meta ul, .widget_recent_entries ul, .widget_categories ul,
    .widget_archive li, .widget_nav_menu li, .widget_meta li, .widget_recent_entries li, .widget_categories li, .offcanvas-search .s, .offcanvas-nav li + li > a, .hero-meta .header-main, .single-authorbox-section, .related-heading, .comments-title, .comment-reply-title, #posts-small-heading, .article-small, .article-tall, .toparea > .container' );
    
}

function fox_submenu_selector() {

    return apply_filters( 'fox_susbmenu_selector', '.wi-mainnav ul.menu ul a, .header-builder .widget_nav_menu ul.menu ul a' );

}

if ( ! function_exists( 'fox_body_style_class' ) ) :
add_action( 'body_class','fox_body_style_class' );
/**
 * Style Body Classes
 * @since 4.0
 @notyet
 */
function fox_body_style_class( $classes ) {
    
    /**
     * Body Layout
     */
    $layout = get_theme_mod( 'wi_body_layout', 'wide' );
    if ( 'boxed' != $layout ) $layout = 'wide';
    $classes[] = 'layout-' . $layout;
    
    /**
     * Dropcap Style
     */
    $dropcap_style = get_theme_mod( 'wi_dropcap_style', 'default' );
    if ( 'color' != $dropcap_style && 'dark' != $dropcap_style ) $dropcap_style = 'default';
    $classes[] = 'style--dropcap-' . $dropcap_style;
    
    // backward compatibility
    $classes[] = 'dropcap-style-' . $dropcap_style;
    
    // FONT
    $dropcap_font = get_theme_mod( 'wi_dropcap_font', 'font_body' );
    $dropcap_font = str_replace( 'font_', '', $dropcap_font );
    $classes[] = 'style--dropcap-font-' . $dropcap_font;
    
    /**
     * Tag Style
     */
    $tag_style = get_theme_mod( 'wi_tag_style', 'block' );
    if ( 'plain' != $tag_style && 'block-2' != $tag_style && 'block-3' != $tag_style ) $tag_style = 'block';
    $classes[] = 'style--tag-' . $tag_style;
    if ( 'block-2' == $tag_style || 'block-3' == $tag_style ) {
        $classes[] = 'style--tag-block';
    }
    
    /**
     * Blockquote
     */
    if ( 'false' == get_theme_mod( 'wi_blockquote_quote_icon', 'true' ) ) {
        $classes[] = 'style--blockquote-no-icon';
    } else {
        $classes[] = 'style--blockquote-has-icon';
        
        $quote_icon = get_theme_mod( 'wi_blockquote_quote_icon_icon', '1' );
        if ( 2 != $quote_icon && 3 != $quote_icon ) {
            $quote_icon = '1';
        }
        $classes[] = 'style--blockquote-icon-' . $quote_icon;
        
    }
    
    /**
     * Content Link Style
     * @since 4.1
     */
    $content_link_style = get_theme_mod( 'wi_content_link_style', 1 );
    if ( $content_link_style < 1 || $content_link_style > 4 ) $content_link_style = 1;
    $classes[] = 'style--link-' . $content_link_style;
    
    return $classes;
    
}
endif;

// add_filter( 'post_class', 'fox_post_style_classes' );
/**
 * Post Style Classes
 * @since 4.0
 @notyet
 */
function fox_post_style_classes( $classes ) {
    
    /**
     * Dropcap Class
     */
    if ( is_page() ) {
        $dropcap = get_post_meta( get_the_ID(), '_wi_dropcap', true );
        if ( ! $dropcap ) {
            $dropcap = get_theme_mod( 'wi_page_dropcap', 'false' );
        }
    }
    
    if ( 'true' == $dropcap  ) {
        $classes[] = 'enable-dropcap';
    } else {
        $classes[] = 'disable-dropcap';
    }
    
    /**
     * Text Column Layout Class
     */
    $column = '';
    if ( is_single() ) {
        $column = get_post_meta( get_the_ID(), '_wi_column_layout', true );
        if ( '1' != $column && '2' != $column  ) {
            $column = get_theme_mod( 'wi_single_column_layout', '1' );
        }
    } elseif ( is_page() ) {
        
        // page template has highest priority
        if ( is_page_template( 'page-one-column.php' ) ) {
            $column = 1;
        } else {
            $column = get_post_meta( get_the_ID(), '_wi_column_layout', true );
            if ( '1' != $column && '2' != $column  ) {
                $column = get_theme_mod( 'wi_page_column_layout', '1' );
            }
        }
    } else {
        $column = get_post_meta( get_the_ID(), '_wi_blog_layout', true );
        if ( ! $column ) {
            $column = get_theme_mod( 'wi_blog_column_layout', '1' );
        }
    }
    
    // because we really have few restrictions on this option
    $column = apply_filters( 'fox_column_layout', $column );
    
    if ( '2' == $column  ) {
        $classes[] = 'enable-2-columns';
    } else {
        $classes[] = 'disable-2-columns';
    }
    
    return $classes;

}

/**
 * if narrow content + column
 * column layout is forced to be 1
 */
add_filter( 'fox_column_layout', 'fox_column_layout_restriction' );
function fox_column_layout_restriction( $column ) {
    
    $content_width = fox_single_option( 'content_width' );
    if ( 'narrow' == $content_width ) {
        $column = 1;
    }
    
    return $column;
    
}