<?php
/**
 * A class just for rendering image
 * @since 4.0
 */
class Fox_Image {
    
    function __construct( $args = [] ) {
        
        $this->args = $args;
        
    }
    
    // render output
    function output( $echo = true ) {
        
        $args = $this->args;
        
        extract( wp_parse_args( $args, [
        
            // image id
            'id' => 0,
            'image_post' => null,
            
            // force disable lazyload
            'disable_lazyload' => false,

            'thumbnail' => '',
            'thumbnail_custom' => '',
            'thumbnail_placeholder' => '',
            'hover_effect' => 'none',
            'letter' => '', // additional info for hover effect
            
            'logo' => '', // must be <img /> in html form, not ID or URL
            'logo_width' => '',
            
            'shape' => 'acute',

            // try to get from postid
            'postid' => 0,
            
            // link
            // possible values: lightbox, single, none
            // default: none
            'link' => '',
            
            // display caption or not
            // default is true
            'caption' => true,

            'attr' => [], // extra img attr [ 'data-foo' => 'blah' ]

            'extra_class' => '', // container class
            'figure_class' => '', // extra figclass
            'link_class' => '',
            'caption_class' => '', // extra caption class
            
            /**
             * special options of post thumbnail id
             */
            'extra_css' => '',
            
            'format_indicator' => true,
            'inside' => '',
            'view' => false,
            'review_score' => false,

        ] ) );
        
        /**
         * lazyload
         */
        $lazyload = ( ! $disable_lazyload && fox_lazyload() );
        
        /**
         * TRY TO GET FULL URL
         */
        $full_url = '';
        // if image id provided
        if ( $id ) {
            
            $full_url = wp_get_attachment_url( $id );
            
            // media doesn't exist
            if ( ! $full_url ) return;
            
        } elseif ( $postid ) {
            
            // attempt 1: custom blog thumbnail
            $id = get_post_meta( $postid, '_wi_blog_thumbnail', true );
            if ( $id ) {
                $full_url = wp_get_attachment_url( $id );
            }
            
            // attempt 2: post thumbnail
            if ( ! $full_url ) {
                $id = get_post_thumbnail_id( $postid );
                if ( $id ) {
                    $full_url = wp_get_attachment_url( $id );
                }
            }
            
            // attempt 3: any attachment to the post
            if ( ! $full_url ) {
                $attachments = get_posts( array(
                    'post_type' => 'attachment',
                    'posts_per_page' => 1,
                    'post_parent' => $postid,
                ) );
                if ( ! empty( $attachments ) ) {
                    $id = $attachments[0]->ID;
                    $full_url = wp_get_attachment_url( $id );
                }
                
            }
            
            // media doesn't exist
            if ( ! $full_url ) {
                if ( ! $thumbnail_placeholder ) {
                    return;
                } else {
                    
                    $full_url = get_theme_mod( 'wi_default_thumbnail' );
                    if ( ! $full_url ) {
                        $full_url = get_template_directory_uri() . '/images/placeholder.jpg';
                        $id = 0;
                    } else {
                        $id = attachment_url_to_postid( $full_url );
                    }
                
                }
            }
        
        }
        
        /**
         * TRY TO GET IMG
         */
        $padding = $height_element = '';
        if ( 'landscape' ==  $thumbnail ) {
            $size = 'thumbnail-medium';
            $thumbnail_custom = '480x384';
        } elseif ( 'square' == $thumbnail ) {
            $size = 'thumbnail-square';
            $thumbnail_custom = '480x480';
        } elseif ( 'portrait' == $thumbnail ) {
            $size = 'thumbnail-portrait';
            $thumbnail_custom = '480x600';
        } elseif ( 'original' == $thumbnail ) {
            $size = 'full';
        } elseif ( 'thumbnail-large' == $thumbnail ) {
            $size = $thumbnail;
            $thumbnail_custom = '720x480';
        } elseif ( 'custom' == $thumbnail ) {
            // now it depends to get an appropriate thumbnail size
            $size = '';
            $thumbnail_custom = strtolower( $thumbnail_custom );
            $explode = explode( 'x', $thumbnail_custom );
            if ( count( $explode ) < 2 ) $explode = explode( ':', $thumbnail_custom ); // another attempt
            if ( count( $explode ) < 2 ) $explode = [ 600, 600 ]; // just a random default number
            
            $w = absint( $explode[0] ); $h = absint( $explode[1] );
            if ( $w < 1 || $h < 1 ) $w = $h = 600; // default fallback value
            
            $padding = $h/$w * 100;
            if ( $padding < 10 || $padding > 1000 ) $padding = 100; // in case wrong value
            
            if ( $w < 120 ) {
                if ( $h < 150 ) {
                    $size = 'thumbnail';
                } else {
                    $size = 'medium';
                }
            } elseif ( $w < 300 ) {
                $size = 'medium';
            } elseif ( $w < 440 ) {
                if ( $h < 380 ) {
                    $size = 'thumbnail-medium';
                } elseif ( $h < 440 ) {
                    $size = 'thumbnail-square';
                } else {
                    $size = 'thumbnail-portrait';
                }
            } elseif ( $w < 720 ) {
                if ( $h < 440 ) {
                    $size = 'thumbnail-large';
                } else {
                    $size = 'large';
                }
            } elseif ( $w < 900 ) {
                $size = 'large';
            } else {
                $size = 'full';
            }
            
        } else {
            $size = $thumbnail;
        }
        
        /**
         * try to have thumbnail_custom to calculate width, height
         * in case placeholder thumbnail
         */
        if ( $thumbnail_custom ) {
            $thumbnail_custom = strtolower( $thumbnail_custom );
            $explode = explode( 'x', $thumbnail_custom );
            if ( count( $explode ) < 2 ) $explode = explode( ':', $thumbnail_custom ); // another attempt
            if ( count( $explode ) < 2 ) $explode = [ 600, 600 ]; // just a random default number

            $w = absint( $explode[0] ); $h = absint( $explode[1] );
            if ( $w < 1 || $h < 1 ) $w = $h = 600; // default fallback value

            $padding = $h/$w * 100;
            if ( $padding < 10 || $padding > 1000 ) $padding = 100; // in case wrong value
        }
        
        $attrs = [];
        $attr = ( array ) $attr;
        $attrs = array_merge( $attrs, $attr );
        
        if ( $lazyload ) {
            // $attrs[ 'data-lazy' ] = 'true';
        } else {
            // $attrs[ 'data-lazy' ] = 'false'; // don't touch me :))
        }
        
        // has id
        if ( $id ) {
            
            $img_html = wp_get_attachment_image( $id, $size, false, $attrs );
            
        // placeholder thumbnail    
        } else {
            
            $clone_attrs = $attrs;
            if ( isset( $clone_attrs[ 'src' ] ) ) unset( $clone_attrs[ 'src' ] );
            if ( $lazyload ) {
                // because we don't have another chance to set it up
                $clone_attrs[ 'data-src' ] = $full_url;
                $clone_attrs[ 'data-srcset' ] = '';
            }
            
            $img_attrs = [];
            foreach ( $clone_attrs as $k => $v ) {
                $img_attrs[] = $k . '="' . esc_attr( $v ) .'"';
            }
            $img_html = '<img src="' . esc_url( $full_url ) . '" alt="' . esc_html__( 'Default thumbnail', 'wi' ) . '" ' . join( ' ', $img_attrs ). ' />';
            
        }
        
        /**
         * lazyload
         * set up <noscript>
         */
        if ( $lazyload ) {
            
            // dont' touch me :))
            // $attrs[ 'data-lazy' ] = 'false';
            
            // if padding not set yet
            if ( ! $padding ) {
                $img_src = wp_get_attachment_image_src( $id, $size );
                $w = $img_src[1]; $h = $img_src[2];
                if ( $w > 0 && $h > 0 ) {
                    $padding = $h/$w * 100;
                    if ( $padding < 10 || $padding > 1000 ) $padding = 100; // in case wrong value
                }
            }
            
            // use later so that we won't change src of this
            // because it's in <noscript> tag
            if ( $id ) {
                
                $src = wp_get_attachment_image_src( $id, $size );
                $attrs[ 'data-src' ] = $src[0];
                $img_html .= '<noscript>' . wp_get_attachment_image( $id, $size, false, $attrs ) . '</noscript>';
                
            } else {
                $clone_attrs = $attrs;
                if ( isset( $clone_attrs[ 'src' ] ) ) unset( $clone_attrs[ 'src' ] );
                $img_attrs = [];
                foreach ( $clone_attrs as $k => $v ) {
                    $img_attrs[] = $k . '="' . esc_attr( $v ) .'"';
                }
                $img_html .= '<noscript>' . '<img src="' . esc_url( $full_url ) . '" alt="' . esc_html__( 'Default thumbnail', 'wi' ) . '" ' . join( ' ', $img_attrs ). ' />' . '</noscript>';
            }
            
            $figure_class .= ' lazyload-figure';
            
        }
        
        if ( $padding ) {
            
            $height_element = '<span class="height-element" style="padding-bottom:' . $padding . '%;"></span>';
            $figure_class .= ' custom-thumbnail';
            
        }
        
        /**
         * LINK PROBLEM
         */
        $url = $url_class = '';
        if ( $link == 'lightbox' ) {
            $url = $full_url;
            $url_class = [ 'fox-lightbox-gallery-item' ];
        } elseif ( $link == 'single' ) {
            $url_class = [ 'post-link' ];
            $url = get_permalink( $postid );
        }
        if ( $url ) {
            if ( $link_class ) {
                $url_class[] = $link_class;
            }
            $open = '<a href="' . esc_url( $url ) . '" class="' . esc_attr( join( ' ', $url_class ) ). '">';
            $close = '</a>';
        } else {
            $open = $close = '';
        }
        
        /**
         * CAPTION PROBLEM
         */
        $caption_html = '';
        if ( $caption ) {
            if ( $id ) {
                $get = wp_get_attachment_caption( $id );
                if ( ! empty( $get ) ) {
                    $cl = [ 'fox-figcaption' ];
                    if ( $caption_class ) {
                        $cl[] = $caption_class;
                    }
                    $caption_html = '<figcaption class="' . esc_attr( join( ' ', $cl ) ) . '">' . $get . '</figcaption>';
                }
            }
        }
        
        /**
         * INSIDE
         * custom html inside the figure element
         */
        if ( $postid ) {
            
            // format indicator
            if ( $format_indicator ) {
                $inside .= fox_format_indicator( $postid );
            }
            
            // view count
            if ( $view ) {
                $viewcount = fox_get_view();
                if ( $viewcount > 0 ) {
                    $inside .= '<span class="thumbnail-view">' . sprintf( fox_word( 'views' ), fox_number( $viewcount ) ) . '</span>';
                }
            }
            
            // review score
            if ( $review_score ) {
                $score = fox_get_review_score();
                $inside .= '<span class="thumbnail-score">' . $score . '</span>';
            }
            
        }
        
        /**
         * HOVER EFFECT
         */
        if ( ! in_array( $hover_effect, [ 'fade', 'dark', 'letter', 'zoomin', 'logo' ] ) ) {
            $hover_effect = 'none';
        }
        $figure_class .= ' hover-' . $hover_effect;
        if ( 'dark' == $hover_effect || 'letter' == $hover_effect || 'logo' == $hover_effect ) {
            $figure_class .= ' hover-dark';
            $inside .= '<span class="image-overlay"></span>';
        }
        if ( 'letter' == $hover_effect && $letter != '' ) {
            
            $inside .= '<span class="image-letter font-heading"><span class="main-letter">' . $letter . '</span><span class="l-cross l-left"></span><span class="l-cross l-right"></span></span>';
            
        } elseif ( 'logo' == $hover_effect && $logo != '' ) {
            
            $image_logo_style = '';
            if ( isset( $logo_width ) ) {
                $logo_width = absint( $logo_width );
                if ( $logo_width <= 100 && $logo_width > 0 ) {
                    $image_logo_style = ' style="width:' . $logo_width . '%"';
                }
            }
            
            $inside .= '<span class="image-logo"' . $image_logo_style . '>' . $logo . '</span>';
            
        }
        
        /**
         * CSS
         */
        $css = [];
        if ( ! empty( $extra_css ) ) {
            if ( is_array( $extra_css ) ) $css = $extra_css;
            else $css[] = $extra_css;
        }
        $css = join( ';', $css );
        if ( ! empty( $css ) ) {
            $css = ' style="' . esc_attr( $css ) . '"';
        }
        
        // shape
        if ( 'circle' != $shape && 'round' != $shape ) {
            $shape = 'acute';
        }
        $figure_class .= ' thumbnail-' . $shape;
        
        /**
         * OUTER FIGURE
         */
        $cl = [ 'fox-figure' ];
        if ( $figure_class ) {
            $cl[] = $figure_class;
        }
        $figure_open = '<figure class="' . esc_attr( join( ' ', $cl ) ). '" ' . $css . ' itemscope itemtype="https://schema.org/ImageObject">';
        $figure_close = '</figure>';
        
        $output = $figure_open . '<span class="image-element thumbnail-inner">' . $open . $img_html . $height_element . $inside . $close . '</span>' . $caption_html . $figure_close;
        
        if ( $echo ) echo $output;
        return $output;
        
    }
    
}

if ( ! function_exists( 'fox_image' ) ) :
/**
 * display a general image
 * @since 4.0
 */
function fox_image( $args = [], $echo = true ) {
    
    $new = new Fox_Image( $args );
    return $new->output( $echo );
    
}
endif;