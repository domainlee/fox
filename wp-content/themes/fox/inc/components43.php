<?php
if ( ! function_exists( 'fox43_thumbnail' ) ) :
/**
 * Render post thumbnail
 *
 * @since 4.3
 *
 * $params: list of params
 * $count: order of this post in the loop
 */
function fox43_thumbnail( $params = [], $count = 1 ) {
    
    $params = wp_parse_args( $params, [
        'thumbnail_type' => 'simple',
        'thumbnail_review_score' => false,
        'thumbnail_index' => false,
        'thumbnail_shape' => 'acute',
        'thumbnail_extra_class' => '',
        'thumbnail_view' => false,
        'thumbnail_format_indicator' => false,
        'thumbnail' => 'landscape',
        'thumbnail_custom' => '',
        'thumbnail_placeholder' => '',
        'thumbnail_placeholder_id' => '',
        'thumbnail_hover' => '',
        'thumbnail_hover_logo' => '',
        'thumbnail_hover_logo_width' => '',
        'thumbnail_showing_effect' => '',
        'thumbnail_extra_css' => '',
    ]);
    
    /**
     * @since 4.4.1
     */
    $params = apply_filters( 'fox_thumbnail_final_params', $params );
    
    $extra_class = '';
    if ( isset( $params['thumbnail_extra_class'] ) ) {
        $extra_class = $params['thumbnail_extra_class'];
    }
    
    if ( isset( $params[ 'thumbnail_type'] ) && 'advanced' == $params[ 'thumbnail_type'] ) {
        
        fox_advanced_thumbnail([ 'extra_class' => $extra_class ]);
        return;
        
    }
    
    $class = [
        'wi-thumbnail',
        'fox-thumbnail',
        'post-item-thumbnail',
        'fox-figure',
        $extra_class,
    ];
    
    if ( $params[ 'thumbnail_review_score' ] ) {
        $params[ 'thumbnail_index' ] = false;
    }
    
    /**
     * shape
     */
    $shape = $params[ 'thumbnail_shape' ];
    if ( 'circle' != $shape && 'round' != $shape ) {
        $shape = 'acute';
    }
    $class[] = 'thumbnail-' . $shape;
    
    /**
     * thumbnail
     */
    $name_to_size_adapter = [
        'landscape' => 'thumbnail-medium',
        'square' => 'thumbnail-square',
        'portrait' => 'thumbnail-portrait',
        'original' => 'large',
        'original_fixed' => 'large',
    ];
    $size = '';
    $thumbnail = $params[ 'thumbnail' ];
    if ( isset( $name_to_size_adapter[ $thumbnail ] ) ) {
        $size = $name_to_size_adapter[ $thumbnail ];
    } else {
        $size = $thumbnail;
    }
    
    /**
     * custom size
     */
    $height_element = '';
    if ( 'custom' == $thumbnail ) {
        
        $thumbnail_custom = $params[ 'thumbnail_custom' ];
        
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
        
        $class[] = 'custom-thumbnail thumbnail-custom';
        $height_element = '<span class="height-element" style="padding-bottom:' . $padding . '%;"></span>';
        
    }
    
    /**
     * original fixed
     */
    if ( 'original_fixed' == $thumbnail ) {
        $padding = 100;
        $class[] = 'custom-thumbnail thumbnail-custom custom-thumbnail-contain';
        $height_element = '<span class="height-element" style="padding-bottom:' . $padding . '%;"></span>';
    }
    
    $img_html = '';
    /**
     * get the img_html
     */
    
    // first attempt: custom blog thumbnail
    $custom_blog_thumbnail_id = get_post_meta( get_the_ID(), '_wi_blog_thumbnail', true );
    if ( $custom_blog_thumbnail_id ) {
        $img_html = wp_get_attachment_image( $custom_blog_thumbnail_id, $size );
    }
    
    // second attempt: the post thumbnail
    if ( ! $img_html && has_post_thumbnail() ) {
        $img_html = wp_get_attachment_image( get_post_thumbnail_id(), $size );
    }
    
    if ( ! $img_html ) {
        // third attempt: attachments attached to the post
        $attachments = get_posts( array(
            'post_type' => 'attachment',
            'posts_per_page' => 1,
            'post_parent' => get_the_ID(),
            'post_status' => 'publish',
            'now_found_row' => true,
        ) );
        if ( ! empty( $attachments ) ) {
            $img_html = wp_get_attachment_image( $attachments[0]->ID, $size );
        }
    }
    
    // forth attempt: in case we use placeholder
    if ( ! $img_html && $params[ 'thumbnail_placeholder' ] ) {
        
        $thumbnail_placeholder_img_html = '';
        $thumbnail_placeholder_id = isset( $params[ 'thumbnail_placeholder_id' ] ) ? $params[ 'thumbnail_placeholder_id' ] : null;
        if ( $thumbnail_placeholder_id ) {
            if ( ! is_numeric( $thumbnail_placeholder_id ) ) {
                $get_id = attachment_url_to_postid( $thumbnail_placeholder_id );
                if ( $get_id ) {
                    $thumbnail_placeholder_img_html = wp_get_attachment_image( $get_id, $size );
                } else {
                    $thumbnail_placeholder_img_html = '<img src="' . esc_url( $thumbnail_placeholder_id ) . '" alt="Placeholder Photo" />';
                }
            } else {
                $thumbnail_placeholder_img_html = wp_get_attachment_image( $thumbnail_placeholder_id, $size );
            }
            
        }
        if ( ! $thumbnail_placeholder_img_html ) {
            $thumbnail_placeholder_img_html = '<img src="' . get_template_directory_uri() . '/images/placeholder.jpg' . '" alt="Placeholder Photo" />';
        }
        
        $img_html = $thumbnail_placeholder_img_html;
        
    }
    
    // no image found, render nothing
    if ( ! $img_html ) return;
    
    /**
     * hover effect
     */
    $hover_markup = '';
    $hover_effect = $params[ 'thumbnail_hover' ];
    if ( ! in_array( $hover_effect, [ 'fade', 'dark', 'letter', 'zoomin', 'logo' ] ) ) {
        $hover_effect = 'none';
    }
    $class[] = ' hover-' . $hover_effect;
    
    if ( 'dark' == $hover_effect || 'letter' == $hover_effect || 'logo' == $hover_effect ) {
        $class[] = ' hover-dark';
        $hover_markup .= '<span class="image-overlay"></span>';
    }
    if ( 'letter' == $hover_effect ) {
        
        $title = strip_tags( get_the_title() );
        $letter = substr( $title, 0, 1 );

        if ( '' != $letter ) {
            $hover_markup .= '<span class="image-letter font-heading"><span class="main-letter">' . $letter . '</span><span class="l-cross l-left"></span><span class="l-cross l-right"></span></span>';
        }

    } elseif ( 'logo' == $hover_effect ) {
        
        $logo = $params[ 'thumbnail_hover_logo' ]; // logo ID
        $logo_html = '';
        if ( $logo ) {
            
            if ( is_numeric( $logo ) ) {
                $logo_id = $logo;    
            } else {
                $logo_id = attachment_url_to_postid( $logo );
            }
            if ( $logo_id ) {
                $logo_html = wp_get_attachment_image( $logo_id, 'large' );
            } else {
                $logo_html = '<img src="' . esc_url( $logo ). '" alt="' . esc_html__( 'Hover Logo', 'wi' ). '" />';
            }
        }
        
        if ( $logo_html ) {
            
            $logo_width = absint( $params[ 'thumbnail_hover_logo_width' ] ); // in percent

            $image_logo_style = '';
            $logo_width = absint( $logo_width );
            if ( $logo_width <= 100 && $logo_width > 0 ) {
                $image_logo_style = ' style="width:' . $logo_width . '%"';
            }

            $hover_markup .= '<span class="image-logo"' . $image_logo_style . '>' . $logo_html . '</span>';
            
        }

    }
    
    /**
     * Thumbnail Showing Effect
     * @since 4.3
     */
    $showing_effect = $params[ 'thumbnail_showing_effect' ];
    if ( in_array( $showing_effect, [ 'fade', 'slide', 'popup', 'zoomin' ] ) ) {
        $class[] = 'thumbnail-loading';
        $class[] = 'effect-' . $showing_effect;
    }
    
    /**
     * extra CSS
     */
    $css = [];
    $extra_css = isset( $params[ 'thumbnail_extra_css' ] ) ? $params[ 'thumbnail_extra_css' ] : '';
    if ( ! empty( $extra_css ) ) {
        if ( is_array( $extra_css ) ) $css = $extra_css;
        else $css[] = $extra_css;
    }
    $css = join( ';', $css );
    if ( ! empty( $css ) ) {
        $css = ' style="' . esc_attr( $css ) . '"';
    }
    
    ?>
    
<figure class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemscope itemtype="https://schema.org/ImageObject"<?php echo $css; ?>>
    
    <div class="thumbnail-inner">
    
        <?php if ( ! isset( $params[ 'link' ] ) || $params[ 'link' ] ) { ?>
        
        <a href="<?php the_permalink(); ?>" class="post-link">
            
        <?php } ?>

            <span class="image-element">

                <?php echo $img_html . $height_element; ?>

            </span><!-- .image-element -->

            <?php echo $hover_markup; ?>

            <?php /* other HTML stuffs inside thumbnail */ 
            if ( $params[ 'thumbnail_index' ] ) {
                echo '<span class="thumbnail-index">' . sprintf( '%02d' , $count ) . '</span>';
            }

            if ( $params[ 'thumbnail_format_indicator' ] ) {
                echo fox_format_indicator();
            }

            if ( $params[ 'thumbnail_view' ] ) {
                $viewcount = fox_get_view();
                if ( $viewcount > 0 ) {
                    echo '<span class="thumbnail-view">' . sprintf( fox_word( 'views' ), fox_number( $viewcount ) ) . '</span>';
                }
            }

            if ( $params[ 'thumbnail_review_score' ] ) {
                if ( fox_get_review_score_number() ) {
                    $score = fox_get_review_score();
                    echo '<span class="thumbnail-score">' . $score . '</span>';
                }
            }

            ?>

        <?php if ( ! isset( $params[ 'link' ] ) || $params[ 'link' ] ) { ?>
            
        </a>
        
        <?php } ?>
        
    </div><!-- .thumbnail-inner -->

</figure><!-- .fox-thumbnail -->

<?php
    
}
endif;

// check if is this a live post
function fox43_is_live() {
    
    return 'true' == get_post_meta( get_the_ID(), '_is_live', true );
    
}

if ( ! function_exists( 'fox43_live_indicator' ) ) :
/**
 * The live indicator
 * @since 4.3
 */
function fox43_live_indicator() {
    
    if ( ! fox43_is_live() ) {
        return;
    }
    
    $diff = (int) abs( get_post_modified_time( 'U' ) - current_time( 'timestamp' ) );
    
    if ( $diff < 60 ) {
        
        $time = esc_html__( 'Just now', 'wi' );
        
    } else {
    
        $time = sprintf( esc_html_x( '%s ago', '%s = human-readable time difference', 'wi' ), human_time_diff( get_post_modified_time( 'U' ), current_time( 'timestamp' ) ) );
        
    }
    
    ?>

<span class="live-indicator">
    
    <span class="live-circle"></span>
    
    <span class="live-word"><?php echo esc_html__( 'Live', 'wi' ); ?></span>
    
    <span class="live-updated">
    
        <time class="published" itemprop="dateModified" datetime="<?php echo get_the_modified_date( DATE_W3C ); ?>">
            
            <?php echo $time ;?>
    
        </time>
        
    </span>

</span>
      
    <?php
    
}
endif;

if ( ! function_exists( 'fox43_post_body' ) ) :
/**
 * Render post body
 *
 * @since 4.3
 *
 * $params: list of params
 * $count: order of this post in the loop
 */
function fox43_post_body( $params = [], $count = 1 ) {
    
    $is_live = fox43_is_live();
    
    $header_class = [
        'post-item-header',
    ];
    
    $item_template = absint( $params[ 'item_template' ] );
    if ( $item_template < 1 || $item_template > 5 ) $item_template = 1;
    
    // get the live HTML
    // since 4.3
    $live_html = '';
    if ( isset( $params[ 'live' ] ) && $params[ 'live' ] ) {
        ob_start();
        fox43_live_indicator();
        $live_html = ob_get_clean();
    }
    
    // get the title
    $title_html = '';
    if ( $params[ 'title_show' ] ) {
        
        $title_html = isset( $params[ 'title_html' ] ) ? $params[ 'title_html' ] : '';
        
        if ( ! $title_html ) {
            
            ob_start();

            $args = [
                'size' => $params[ 'title_size' ],
                'weight' => $params[ 'title_weight' ],
                'text_transform' => $params[ 'title_text_transform' ],
                'tag' => $params[ 'title_tag' ]
            ];
            if ( isset( $params[ 'title_extra_class'] ) ) {
                $args[ 'extra_class' ] = $params[ 'title_extra_class'];
            }
            fox_post_title( $args );

            $title_html = ob_get_clean();
            
        }
    }
    
    // get the excerpt
    $excerpt_html = '';
    
    if ( $params[ 'excerpt_show' ] ) {
        
        $excerpt_html = isset( $params[ 'excerpt_html' ] ) ? $params[ 'excerpt_html' ] : '';
        
        if ( ! $excerpt_html ) {
            
            ob_start();

            $args = [
                'length' => $params[ 'excerpt_length' ],
                'more' => $params[ 'excerpt_more' ],
                'style' => $params[ 'excerpt_more_style' ],
                'text' => $params[ 'excerpt_more_text' ],
                'size' => $params[ 'excerpt_size' ],
                // 'color' => $params[ 'excerpt_color' ],
            ];

            if ( isset( $params[ 'excerpt_extra_class'] ) ) {
                $args[ 'extra_class' ] = $params[ 'excerpt_extra_class'];
            }

            fox_post_excerpt( $args );

            $excerpt_html = ob_get_clean();
            
        }
        
    }
    
    // meta
    if ( 4 == $item_template || 5 == $item_template ) {
        
        ob_start();
        
        $params_copy = $params;
        $params_copy[ 'category_show' ] = false;
        
        fox_post_meta( $params_copy );
        
        $meta_html = ob_get_clean();
        
        $cat_html = '';
        
        if ( $params[ 'category_show' ] ) {
            ob_start();
            
            fox_post_categories([ 
                'extra_class' => 'standalone-categories post-header-section'
            ]);
            
            $cat_html = ob_get_clean();
        }
        
    } else {
        
        ob_start();
        $meta_html = fox_post_meta( $params );
        $meta_html = ob_get_clean();
        
    }
    
    // title > meta > excerpt
    if ( '1' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $live_html . $title_html . $meta_html;
        echo '</div>';
        
        echo  $excerpt_html;
    
    }
    
    // meta > title > excerpt
    if ( '2' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $meta_html . $title_html . $live_html;
        echo '</div>';
        
        echo  $excerpt_html;
    
    }
    
    // title > excerpt > meta
    if ( '3' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $live_html . $title_html;
        echo '</div>';
        
        echo  $excerpt_html . $meta_html;
    
    }
    
    // cateogry > title > meta > excerpt
    if ( '4' == $item_template ) {
    
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $cat_html . $live_html . $title_html . $meta_html;
        echo '</div>';
        
        echo $excerpt_html;
    
    }
    
    // cateogry > title > excerpt > meta
    if ( '5' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $cat_html . $live_html . $title_html;
        echo '</div>';
        
        echo $excerpt_html . $meta_html;
    
    }
    
}
endif;