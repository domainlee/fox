<?php
if ( ! function_exists( 'fox_get_advanced_thumbnail' ) ) :
/**
 * Advanced Thumbnail
 * return value
 * @since 4.0
 */
function fox_get_advanced_thumbnail( $args = [] ) {
    
    ob_start();
    fox_advanced_thumbnail( $args );
    return ob_get_clean();
    
}
endif;

if ( ! function_exists( 'fox_advanced_thumbnail' ) ) :
/**
 * Advanced Thumbnail (with caption, video, gallery etc)
 * @since 4.0
 */
function fox_advanced_thumbnail( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'postid' => null,
        'extra_class' => '',
    ] ) );
    
    $class = [];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    if ( ! $postid ) $postid = get_the_ID();
    
    $format = get_post_format( $postid );
    
    global $content_width;
    
    /**
     * Video Thumbnail
     * @since 4.0
     * ------------------------------------------------------ */
    if ( 'video' === $format ) {
        
        $class[] = 'post-thumbnail thumbnail-video';
        
        // the self-hosted video
        $video = get_post_meta( $postid, '_format_video', true );
        $video_url = '';
        $media_attempt = '';
        $caption = '';
        
        // still can't have a result
        // try to embed code
        if ( ! $video_url ) {
            
            $media_code = get_post_meta( $postid, '_format_video_embed', true );
            
            // if we have iframe, take it
            if ( stripos( $media_code,'<iframe') > -1 ) {
                
                $media_attempt = $media_code;
            
            // otherwise, it's a URL    
            } else {
                
                $url = $media_code;
                $parse = parse_url( home_url( '/' ) );
                $host = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
                
                // it's not a self-hosted video
                // just a backward compatibility
                if ( strpos( $url, $host ) === false ) {
                    
                    global $wp_embed;
                    $media_attempt = $wp_embed->run_shortcode( '[embed]' . $url . '[/embed]' );
                    
                } else {
                    
                    $video_url = $url;
                    
                }
            
            }
            
        }
        
        if ( $video ) {
            $video_url = wp_get_attachment_url( $video );
        }
        
        // atempt when we have self-hosted URL
        if ( $video_url ) {
            
            $height = $content_width * 9 / 16;
                
            $args = [
                'src' => $video_url,
                'loop' => 'on',
                'autoplay' => true,
                'width' => $content_width,
                'height' => $height,
            ];

            if ( has_post_thumbnail( $postid ) ) {
                $args[ 'poster' ] = wp_get_attachment_url( get_post_thumbnail_id( $postid ) );
            }

            $media_attempt = wp_video_shortcode( $args );
            
            // try to get video ID from its URL
            if ( ! $video ) $video = attachment_url_to_postid( $video_url );
            if ( $video ) {
                $get_caption = wp_get_attachment_caption( $video );
                if ( $get_caption ) {
                    
                    $caption .= '<figcaption class="post-thumbnail-caption video-caption">';
                    $caption .= wp_kses( $get_caption, fox_allowed_html() );
                    $caption .= '</figcaption>';
                    
                }
            }
            
        }
        
        if ( $media_attempt ) {
        
            echo '<figure class="' . esc_attr( join( ' ', $class ) ) . '"><div class="media-container">' . $media_attempt . $caption . '</div></figure>';
        
        } else {
        
            echo '<div class="fox-error">Please go to your post editor > Post Settings > Post Formats tab below your editor to enter video URL.</div>';    
        
        }
        
    /**
     * Audio Thumbnail
     * @since 4.0
     * ------------------------------------------------------ */
    } elseif ( 'audio' === $format ) {
        
        $class[] = 'post-thumbnail thumbnail-audio';
        
        // the self-hosted audio
        $audio = get_post_meta( $postid, '_format_audio', true );
        $audio_url = '';
        $media_attempt = '';
        $cover_img = '';
        $caption = '';
        
        // still can't have a result
        // try to embed code
        if ( ! $audio_url ) {
            
            $media_code = get_post_meta( $postid, '_format_audio_embed', true );
            
            // if we have iframe, take it
            if ( stripos( $media_code,'<iframe') > -1 ) {
                
                $media_attempt = $media_code;
            
            // otherwise, it's a URL    
            } else {
                
                $url = $media_code;
                $parse = parse_url( home_url( '/' ) );
                $host = preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']);
                
                // it's not a self-hosted audio
                // just a backward compatibility
                if ( strpos( $url, $host ) === false ) {
                    
                    global $wp_embed;
                    $media_attempt = $wp_embed->run_shortcode( '[embed]' . $url . '[/embed]' );
                    
                } else {
                    
                    $audio_url = $url;
                    
                }
            
            }
            
        }
        
        if ( $audio ) {
            $audio_url = wp_get_attachment_url( $audio );
        }
        
        // atempt when we have self-hosted URL
        if ( $audio_url ) {
            
            $args = [
                'src' => $audio_url,
                'loop' => 'on',
                'autoplay' => true,
            ];
            
            if ( has_post_thumbnail( $postid ) ) {
                $figclass = [
                    'wi-self-hosted-audio-poster self-hosted-audio-poster'
                ];
                if ( fox_lazyload() ) {
                    $figclass[] = 'lazyload-figure';
                }
                $cover_img = '<div class="' . esc_attr( join( ' ', $figclass ) ) . '">' . fox_get_attachment_image( get_post_thumbnail_id( $postid ), 'full' ). '</div>';
            }

            $media_attempt = wp_audio_shortcode( $args );
            
            // try to get audio ID from its URL
            if ( ! $audio ) $audio = attachment_url_to_postid( $audio_url );
            if ( $audio ) {
                $get_caption = wp_get_attachment_caption( $audio );
                if ( $get_caption ) {
                    
                    $caption .= '<figcaption class="post-thumbnail-caption audio-caption">';
                    $caption .= wp_kses( $get_caption, fox_allowed_html() );
                    $caption .= '</figcaption>';
                    
                }
            }
            
        }
        
        if ( $media_attempt ) {
        
            echo '<div class="' . esc_attr( join( ' ', $class ) ) . '"><div class="media-container">' . $cover_img . $media_attempt . $caption . '</div></div>';
        
        } else {
        
            echo '<div class="fox-error">Please go to your post editor > Post Settings > Post Formats tab below your editor to enter audio URL.</div>';    
        
        }
        
    /**
     * Gallery Thumbnail
     * @since 4.0
     * ------------------------------------------------------ */    
    } elseif ( 'gallery' === $format ) {
        
        /**
         * get image
         */
        $images = get_post_meta( $postid , '_format_gallery_images', true );
        if ( empty( $images ) ) return;
        if ( ! is_array( $images ) ) {
            $images = explode( ',', $images );
            $images = array_map( 'trim', $images );
        }
        
        /**
         * get style
         */
        $args = [
            'images' => $images,
            'lightbox' => ( 'true' == fox_single_option( 'format_gallery_lightbox' ) )
        ];
        
        $class[] = 'post-thumbnail';
        
        $style = fox_single_option( 'format_gallery_style' );
        
        $args[ 'style' ] = $style;
        
        if ( 'slider' == $style ) {
            
            $args[ 'mode' ] = 'img';
            
            $args[ 'effect' ] = fox_single_option( 'format_gallery_slider_effect' );
            $size = fox_single_option( 'format_gallery_slider_size' );
            
            // cropped 2:1
            if ( 'original' != $size ) {
                
                $args[ 'thumbnail' ] = 'custom';
                $args[ 'thumbnail_custom' ] = '2000x1000';
                
            } else {
                
                $args[ 'thumbnail' ] = 'original';
                
            }
            
            $args[ 'extra_class' ] = join( ' ', $class );
            
        } elseif ( 'stack' == $style ) {
            
            $args[ 'mode' ] = 'img';
            
            $args[ 'extra_class' ] = join( ' ', $class );
        
        } elseif ( 'carousel' == $style ) {
            
            $args[ 'size' ] = 'large';
            $args[ 'mode' ] = 'img';
            
            $args[ 'extra_class' ] = join( ' ', $class );
        
        } elseif ( 'slider-rich' == $style ) {
            
            $args[ 'size' ] = 'large';
            $args[ 'mode' ] = 'img';
            
            $args[ 'extra_class' ] = join( ' ', $class );
        
        } elseif ( 'grid' == $style ) {
            
            $args[ 'column' ] = fox_single_option( 'format_gallery_grid_column' );
            $args[ 'extra_outer_class' ] = join( ' ', $class );
        
        } elseif ( 'masonry' == $style ) {
            
            $column = fox_single_option( 'format_gallery_grid_column' );
            $args[ 'column' ] = $column;
            
            $args[ 'extra_outer_class' ] = join( ' ', $class );
        
        } elseif ( 'metro' == $style ) {
            
            $args[ 'extra_outer_class' ] = join( ' ', $class );
            
        }
        
        fox_gallery( $args );
    
    /**
     * Standard Thumbnail
     * @since 4.0
     * ------------------------------------------------------ */    
    } else {
        
        $class[] = 'post-thumbnail post-thumbnail-standard';
        $id = get_post_thumbnail_id( $postid );
        if ( ! $id ) return;
        
        $imagedata = [
            'id' => $id,
        ];
        
        fox_image([
            'id' => $id,
            'link' => is_singular() ? '' : 'single',
            'postid' => $postid,
            'figure_class' => join( ' ', $class ),
            'thumbnail' => 'original',
        ]);

    }
    
}
endif;

if ( ! function_exists( 'fox_default_thumbnail' ) ) :
/**
 * Return prefered thumbnail size
 * @since 4.0
 */
function fox_default_thumbnail() {
    
    return get_theme_mod( 'wi_thumbnail_size', 'landscape' );
    
}
endif;
if ( ! function_exists( 'fox_default_thumbnail_custom' ) ) :
/**
 * Return prefered thumbnail custom size
 * @since 4.0
 */
function fox_default_thumbnail_custom() {
    
    return get_theme_mod( 'wi_thumbnail_size_custom' );
    
}
endif;

if ( ! function_exists( 'fox_thumbnail' ) ) :
/**
 * Display image thumbnail
 * Used mostly in blog
 * @since 4.0
 */
function fox_thumbnail( $args = [] ) {
    
    $args = wp_parse_args( $args, [
        
        'postid' => 0,
        'custom' => '',
        'shape' => 'acute',
        'placeholder' => true,
        'extra_class' => '',
        
        'hover_effect' => 'none',
        'thumbnail_hover_logo' => '', // custom hover logo
        'thumbnail_hover_logo_width' => '', // custom logo width
        
    ] );
    
    /**
     * deal with convention about thumbnail_ prefix
     */
    if ( isset( $args[ 'thumbnail_custom' ] ) ) $args[ 'custom' ] = $args[ 'thumbnail_custom' ];
    if ( isset( $args[ 'thumbnail_shape' ] ) ) $args[ 'shape' ] = $args[ 'thumbnail_shape' ];
    if ( isset( $args[ 'thumbnail_placeholder' ] ) ) $args[ 'placeholder' ] = $args[ 'thumbnail_placeholder' ];
    if ( isset( $args[ 'thumbnail_hover_effect' ] ) ) $args[ 'hover_effect' ] = $args[ 'thumbnail_hover_effect' ];
    if ( isset( $args[ 'thumbnail_hover_logo_width' ] ) ) $args[ 'logo_width' ] = $args[ 'thumbnail_hover_logo_width' ];
    
    if ( isset( $args[ 'thumbnail_inside' ] ) ) $args[ 'inside' ] = $args[ 'thumbnail_inside' ];
    if ( isset( $args[ 'thumbnail_review_score' ] ) ) $args[ 'review_score' ] = $args[ 'thumbnail_review_score' ];
    if ( isset( $args[ 'thumbnail_view' ] ) ) $args[ 'view' ] = $args[ 'thumbnail_view' ];
    
    if ( ! $args[ 'postid' ] ) {
        $args[ 'postid' ] = get_the_ID();
    }
    
    if ( 'letter' == $args[ 'hover_effect' ] ) {
        $title = trim( get_the_title( $args[ 'postid' ] ) );
        $args[ 'letter' ] = substr( $title, 0, 1 );
    }
    
    if ( 'logo' == $args[ 'hover_effect' ] ) {
        
        // if logo is set (often from Elementor)
        $logo_id = $logo_url = '';
        $logo = $args[ 'thumbnail_hover_logo' ];
        if ( is_array( $logo ) ) {
            $logo_url = $logo[ 'url' ];
            $logo_id = attachment_url_to_postid( $logo_url );
        }
        if ( ! $logo_url ) {
            
            $logo_url = get_theme_mod( 'wi_blog_grid_thumbnail_hover_logo' );
            $logo_id = attachment_url_to_postid( $logo_url );
            
        }
        
        if ( $logo_id ) {
            $args[ 'logo' ] = wp_get_attachment_image( $logo_id, 'full' );
        } elseif ( $logo_url ) {
            $args[ 'logo' ] = '<img src="' . esc_url( $logo_url ) . '" alt="Thumbnail Logo" />';
        }
        
        // logo width
        if ( $args[ 'logo_width' ] == '' ) {
            $args[ 'logo_width' ] = get_theme_mod( 'wi_blog_grid_thumbnail_hover_logo_width' );
        }
        
    }
    
    $args[ 'thumbnail_custom' ] = $args[ 'custom' ];
    $args[ 'thumbnail_placeholder' ] = $args[ 'placeholder' ];
    $args[ 'figure_class' ] = 'wi-thumbnail fox-thumbnail post-item-thumbnail ' . $args[ 'extra_class' ];
    if ( ! isset( $args[ 'link' ] ) ) {
        $args[ 'link' ] = 'single';
    }
    $args[ 'caption' ] = false;
    
    fox_image( $args );
    
}
endif;

if ( ! function_exists( 'fox_format_indicator' ) ) :
/**
 * since 4.0
 */
function fox_format_indicator( $postid = 0 ) {
    
    if ( ! $postid ) $postid = get_the_ID();
    
    $format = get_post_format( $postid );
    
    if ( 'video' === $format ) {
        $video_format_indicator_style = get_theme_mod( 'wi_video_indicator_style', 'outline' );
        
        $cl = [ 'video-format-indicator' ];
        $cl[] = 'video-indicator-' . $video_format_indicator_style;
        
        return '<span class="' . esc_attr( join( ' ', $cl )). '"><i class="fa fa-play"></i></span>';
    }
    
    if ( 'gallery' === $format ) {
        return '<span class="post-format-indicator gallery-format-indicator"><i class="fa fa-clone"></i></span>';
    }
    
    if ( 'link' === $format ) {
        return '<span class="post-format-indicator link-format-indicator"><i class="fa fa-share"></i></span>';
    }
    
    if ( 'audio' === $format ) {
        return '<span class="post-format-indicator audio-format-indicator"><i class="fa fa-music"></i></span>';
    }
    
    return '';
}

endif;

/* POST COMPONENTS
------------------------------------------------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'fox_post_body' ) ) :
/**
 * $item_template
    '1' => 'Meta > Title > Excerpt',
    '2' => 'Title > Meta > Excerpt',
    '3' => 'Title > Excerpt > Meta',
    '4' => 'Category > Title > Meta > Excerpt',
    '5' => 'Category > Title > Excerpt > Meta',
    
 * $options = array of items
 * since 4.0
 */
function fox_post_body( $options ) {
    
    extract( wp_parse_args( $options, [
            
        // general
        // elements order
        'item_template' => '1',
        'header_class' => '',
        
        // title
        'show_title' => true,
        'title_size' => '',
        'title_weight' => '',
        'title_text_transform' => '',
        'title_tag' => '',
        'title_link' => true,
        'title_class' => '',
        
        // subtitle
        'subtitle' => false,
        
        // excerpt
        'show_excerpt' => true,
        'excerpt_length' => 22,
        'excerpt_size' => 'normal',
        'excerpt_color' => '',
        'excerpt_more' => true,
        'excerpt_more_style' => 'simple',
        'excerpt_more_text' => '',
        'excerpt_class' => '',
        
        // category
        'show_category' => true,
        
        // date
        'show_date' => true,
        'date_fashion' => 'short',
        
        // author
        'show_author' => false,
        'show_author_avatar' => false,
        
        // view count
        'show_view' => false,
        
        // comment link
        'show_comment_link' => false,
        
        // reading time
        'show_reading_time' => false,
        
    ] ) );
    
    $header_class = [
        'post-item-header',
        $header_class
    ];
    
    // get the title
    $title_html = '';
    if ( $show_title ) {
        ob_start();
        
        fox_post_title([
            'extra_class' => $title_class,
            'size' => $title_size, 
            'weight' => $title_weight,
            'text_transform' => $title_text_transform,
            'tag' => $title_tag,
            'link' => $title_link 
        ]);
        
        if ( $subtitle ) fox_post_subtitle();
        
        $title_html = ob_get_clean();
    }
    
    // get the excerpt
    $excerpt_html = '';
    if ( $show_excerpt ) {
        ob_start();
        
        fox_post_excerpt([
            'length' => $excerpt_length,
            'more' => $excerpt_more,
            'style' => $excerpt_more_style,
            'text' => $excerpt_more_text,
            'size' => $excerpt_size,
            'color' => $excerpt_color,
            'extra_class' => $excerpt_class
        ]);
        
        $excerpt_html = ob_get_clean();
    }
    
    // meta
    if ( 4 == $item_template || 5 == $item_template ) {
        
        ob_start();
        $options[ 'show_category' ] = false;
        fox_post_meta( $options );
        $meta_html = ob_get_clean();
        
        $cat_html = '';
        
        if ( $show_category ) {
            ob_start();
            
            fox_post_categories([ 
                'extra_class' => 'standalone-categories post-header-section'
            ]);
            
            $cat_html = ob_get_clean();
        }
        
    } else {
        
        ob_start();
        $meta_html = fox_post_meta( $options );
        $meta_html = ob_get_clean();
        
    }
    
    // title > meta > excerpt
    if ( '1' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $title_html . $meta_html;
        echo '</div>';
        
        echo  $excerpt_html;
    
    }
    
    // meta > title > excerpt
    if ( '2' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $meta_html . $title_html;
        echo '</div>';
        
        echo  $excerpt_html;
    
    }
    
    // title > excerpt > meta
    if ( '3' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $title_html;
        echo '</div>';
        
        echo  $excerpt_html . $meta_html;
    
    }
    
    // cateogry > title > meta > excerpt
    if ( '4' == $item_template ) {
    
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $cat_html . $title_html . $meta_html;
        echo '</div>';
        
        echo $excerpt_html;
    
    }
    
    // cateogry > title > excerpt > meta
    if ( '5' == $item_template ) {
        
        echo '<div class="' . esc_attr( join( ' ', $header_class ) ) . '">';
        echo $cat_html . $title_html;
        echo '</div>';
        
        echo $excerpt_html . $meta_html;
    
    }
    
}
endif;

if ( ! function_exists( 'fox_blog_header' ) ) :
/**
 * Still display the blog post body, but we take options from customizer
 * @since 4.0
 */
function fox_blog_header( $post_type = 'post' ) {
    
    $prefix = 'post' == $post_type ? 'wi_single_' : 'wi_' . $post_type . '_';
    $meta_template = get_theme_mod( $prefix . 'meta_template', '4' );
    
    $is_single = is_singular( $post_type );
    
    $args = [
        'show_title' => true,
        'title_link' => ! $is_single,
        'title_tag' => ( $is_single ? 'h1' : 'h2' ),
        'title_class' => 'post-title',
        
        'show_excerpt' => false,
        'subtitle' => $is_single,
        'item_template' => $meta_template,
        
        'header_class' => 'align-' . get_theme_mod( $prefix . 'meta_align', 'center' ),
    ];
    
    $components = [ 'date', 'category', 'author', 'author_avatar', 'view', 'comment_link', 'reading_time' ];
    
    foreach ( $components as $com ) {
        
        $std = 'false';
        if ( 'post' == $post_type ) {
            if ( 'date' == $com || 'category' == $com ) $std = 'true';
        }
        $args[ 'show_' . $com ] = (bool) ( 'true' == get_theme_mod( $prefix  . 'meta_' . $com , $std ) );
        
    }
    
    fox_post_body( $args );
    
    // since 4.2
    do_action( 'after_fox_blog_header', $post_type );
    
}
endif;

if ( ! function_exists( 'fox_post_title' ) ) :
/**
 * Post Title
 * @since 4.0
 */
function fox_post_title( $args =[] ) {
    
    if ( is_string( $args ) ) $args = [ 'extra_class' => $args ];
    
    extract( wp_parse_args( $args, [
        'extra_class' => '', 
        'tag' => 'h2', 
        'size' => '',
        'weight' => '',
        'text_transform' => '',
        'link' => true,
    ] ) );
    
    $class = [ 'post-item-title', 'wi-post-title', 'fox-post-title', 'post-header-section' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    // size
    if ( ! in_array( $size, [ 'extra', 'large', 'medium', 'small', 'tiny', 'supertiny' ] ) ) $size = 'normal';
    $class[] = 'size-' . $size;
    
    // weight
    if ( $weight == 300 || $weight == 400 || $weight == 700 ) {
        $class[] = 'weight-' . $weight;
    }
    
    // text transform
    if ( $text_transform == 'none' || $text_transform == 'uppercase' || $text_transform == 'lowercase' || $text_transform == 'capitalize' ) {
        $class[] = 'text-transform-' . $text_transform;
    }
    
    if ( 'h1' != $tag && 'h3' != $tag && 'h4' != $tag ) $tag = 'h2';
    
    ?>

<?php echo '<' . $tag . ' class="' . esc_attr( join( ' ', $class ) ) . '" itemprop="headline">'; ?>

    <?php if ( $link ) { ?><a href="<?php the_permalink();?>" rel="bookmark"><?php } ?>
        
        <?php the_title();?>

    <?php if ( $link ) { ?></a><?php } ?>

<?php echo '</' . $tag . '>';

}
endif;

if ( ! function_exists( 'fox_post_subtitle' ) ) :
/**
 * Post Subtitle
 * @since 4.0
 */
function fox_post_subtitle() {
    
    echo fox_get_subtitle();
    
}
endif;

if ( ! function_exists( 'fox_get_subtitle' ) ) :
/**
 * Get Subtitle
 *
 * @since 4.3
 */
function fox_get_subtitle() {
    
    $subtitle = trim( get_post_meta( get_the_ID(), '_wi_subtitle', true ) );
    if ( ! $subtitle ) return;

    return '<div class="post-item-subtitle post-header-section"><p>' . $subtitle . '</p></div>';
    
}
endif;

if ( ! function_exists( 'fox_post_meta' ) ) :
/**
 * Post Meta
 * @since 4.0
 */
function fox_post_meta( $args = [] ) {
    
    /**
     * in previous versions, we use show_category
     * since 4.3, we use category_show to make it consistent with other parameters like thumbnail_show, title_show..
     */
    $field_adapter = [
        'category_show' => 'show_category',
        'author_show' => 'show_author',
        'author_avatar_show' => 'show_author_avatar',
        'date_show' => 'show_date',
        'view_show' => 'show_view',
        'comment_link_show' => 'show_comment_link',
        'reading_time_show' => 'show_reading_time',
    ];
    foreach ( $field_adapter as $k => $v ) {
        if ( isset( $args[ $k ] ) ) {
            $args[ $v ] = $args[ $k ];
        }
    }
    
    extract( wp_parse_args( $args, [
            
        'show_category' => true,
        'show_date' => true,
        
        'show_author' => false,
        'show_author_avatar' => false,
        'show_view' => false,
        'show_comment_link' => false,
        'show_reading_time' => false,
        
        'extra_class' => '',
        'exclude_class' => [],
        'date_fashion' => 'short',
        
    ] ) );
    
    if ( ! $show_category && ! $show_date && ! $show_author && ! $show_author_avatar && ! $show_view && ! $show_comment_link && ! $show_reading_time ) return;
    
    $class = [
        'post-item-meta',
        'wi-meta',
        'fox-meta',
        'post-header-section'
    ];
    
    if ( is_array( $extra_class ) ) $class = array_merge( $class, $extra_class );
    elseif ( is_string( $extra_class ) ) $class[] = $extra_class;
    
    if ( is_array( $exclude_class ) && ! empty( $exclude_class ) ) {
        $class = array_diff( $class, $exclude_class );
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <?php if ( $show_author ) fox_post_author( $show_author_avatar ); ?>
    <?php if ( $show_date ) fox_post_date([ 'fashion' => $date_fashion ]); ?>
    <?php if ( $show_category ) fox_post_categories(); ?>
    <?php if ( $show_view ) { fox_post_view(); } ?>
    <?php if ( $show_reading_time ) fox_reading_time(); ?>
    <?php if ( $show_comment_link ) fox_comment_link(); ?>
    
</div>

<?php
    
}
endif;

if ( ! function_exists( 'fox_post_categories' ) ) :
/**
 * Post Categories
 * "In word" is redundant & deprecated since 4.0
 * @since 4.0
 */
function fox_post_categories( $args = [] ) {
    
    extract( wp_parse_args( $args, [ 'tax' => 'category', 'extra_class' => '' ] ) );
    
    $class = [
        'entry-categories',
        'meta-categories'
    ];
    if ( $extra_class ) $class[] = $extra_class;
    
    if ( 'category' == $tax && 'post' !== get_post_type() ) return;
    
    $separate_meta = '<span class="sep">' . esc_html__( '/', 'wi' ) . '</span>';
    ?>

    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php echo get_the_term_list( get_the_ID(), $tax, '', $separate_meta, '' ); ?>

    </div>

    <?php
    
}
endif;

if ( ! function_exists( 'fox_post_date' ) ) :
/**
 * Post Date
 * @since 4.0
 */
function fox_post_date( $args = [] ) {
    
    // since 4.3
    if ( fox43_is_live() ) {
        return;
    }
    
    extract( wp_parse_args( $args, [
        'time_style' => '',
        'fashion' => 'long', // only for standard time style
        'format' => '',
    ] ) );
    
    if ( ! $time_style ) $time_style = get_theme_mod( 'wi_time_style', 'standard' );
    
    if ( 'human' === $time_style ) :
    
        echo '<div class="entry-date meta-time human-time">';
    
        printf( esc_html_x( '%s ago', '%s = human-readable time difference', 'wi' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
    
        echo '</div>';
    
    else :
    
        $time_string = '<time class="published updated" itemprop="datePublished" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="published" itemprop="datePublished" datetime="%1$s">%2$s</time><time class="updated" itemprop="dateModified" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            get_the_date( DATE_W3C ),
            get_the_date( $format ),
            get_the_modified_date( DATE_W3C ),
            get_the_modified_date( $format )
        );
    
    if ( 'short' != $fashion ) $fashion = 'long'; 

        // Wrap the time string in a link, and preface it with 'Posted on'.
        echo '<div class="entry-date meta-time machine-time time-' . esc_attr( $fashion ) . '">';
    
        if ( 'short' == $fashion ) {
            
            echo $time_string;

        } else {
            printf(
                /* translators: %s: post date */
                wp_kses( '<span class="published-label">' . fox_word( 'date' ) . '</span> %s', fox_allowed_html() ),
                $time_string
            );

        }
    
        echo '</div>';
    
    endif;
    
}
endif;

if ( ! function_exists( 'fox_post_author' ) ) :
/**
 * Post Author Link
 * @since 4.0
 */
function fox_post_author( $show_avatar = false ) {
    
    global $post;
	$author_id = $post->post_author;
    
    // Finally, let's write all of this to the page.
	echo '<div class="fox-meta-author entry-author meta-author" itemprop="author" itemscope itemtype="https://schema.org/Person">';
    
    if ( $show_avatar ) {
        echo '<a class="meta-author-avatar" itemprop="url" rel="author" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">';
        echo get_avatar( $author_id, 80 );
        echo '</a>';
    }
    
    echo '<span class="byline"> ' . sprintf(
		/* translators: %s: post author */
		fox_word( 'author' ),
		'<span class="author vcard"><a class="url fn" itemprop="url" rel="author" href="' . esc_url( get_author_posts_url( $author_id ) ) . '"><span itemprop="name">' . get_the_author_meta( 'display_name', $author_id ) . '</span></a></span>'
	) . '</span>';
    
    echo '</div>';
    
}
endif;

if ( ! function_exists( 'fox_get_view' ) ) :
/**
 * Return number of views
 * @since 4.0
 */
function fox_get_view( $post_id = null ) {
    
    if ( ! function_exists( 'pvc_get_post_views' ) ) return null;
    
    if ( ! $post_id ) {
        global $post;
        $post_id =  $post->ID;
    }
    
    return pvc_get_post_views( $post_id );
    
}
endif;    

if ( ! function_exists( 'fox_post_view' ) ) :
/**
 * Post View
 * @since 4.0
 */
function fox_post_view() {
    
    $count = fox_get_view();
    if ( is_null( $count ) ) return;
    
    echo '<div class="fox-view-count wi-view-count entry-view-count" title="' . sprintf( fox_word( 'views' ), $count ) . '"><span>' . sprintf( fox_word( 'views' ), $count ) . '</span></div>';
    
}
endif;

if ( ! function_exists( 'fox_comment_link' ) ) :
/**
 * Comment Link
 * @since 4.0
 */
function fox_comment_link() {
    
    $icon = 'fa fa-comment-alt';
    // $icon = 'feather-message-square';
    
    comments_popup_link( 
        '<span class="comment-icon"><i class="' . $icon . '"></i></span>',
        
        '<span class="comment-num">1</span> <span class="comment-icon"><i class="' . $icon . '"></i></span>', 
        '<span class="comment-num">%</span> <span class="comment-icon"><i class="' . $icon . '"></i></span>', 
        'comment-link',
        
        '<span class="comment-icon off"><i class="' . $icon . '"></i></span>'
    );
    
}
endif;

if ( ! function_exists( 'fox_reading_time' ) ) :
/**
 * Estimated Reading Time
 * Note: it's not always true because it only counts words
 * @since 4.0
 */
function fox_reading_time() {
    
    global $post;

    $words = str_word_count( strip_tags( $post->post_content ) );
    $mins = floor( $words / 120 );

    if ( 1 < $mins ) {
        $estimated_time = sprintf( fox_word( 'mins_read' ), $mins );
    } else {
        $estimated_time = fox_word( 'min_read' );
    }

    echo '<div class="reading-time">' . $estimated_time . '</div>';
    
}
endif;

if ( ! function_exists( 'fox_post_excerpt' ) ) :
/**
 * Post Excerpt
 * @since 4.0
 */
function fox_post_excerpt( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'length' => 22,
        'size' => '',
        'color' => '',
        'extra_class' => '',
        'exclude_class' => [],
        'base' => '',
        'more' => false,
        'style' => 'simple',
        'text' => '', // custom text
    ] ) );
    
    $class = [
        'post-item-excerpt',
        'entry-excerpt',
    ];
    
    // size
    if ( 'small' != $size && 'medium' != $size ) $size = 'normal';
    $class[] = 'excerpt-size-' . $size;
    
    // color
    $excerpt_css = [];
    $color = trim( $color );
    if ( $color ) {
        $class[] = 'excerpt-custom-color';
        $excerpt_css[] = 'color:' . $color;
    }
    
    if ( ! empty( $extra_class ) ) {
        
        if ( is_string( $extra_class ) ) $extra_class = [ $extra_class ];
        $class = array_merge( $class, $extra_class );
        
    }
    
    if ( ! empty( $exclude_class ) && is_array( $exclude_class ) ) {
        $class = array_diff( $class, $exclude_class );
    }
    
    if ( ! $base ) {
        $base = get_theme_mod( 'wi_sentence_base', 'word' );
    }
    if ( 'char' != $base ) $base = 'word';
    
    $excerpt_css = join( ';', $excerpt_css );
    if ( ! empty( $excerpt_css ) ) {
        $excerpt_css = ' style="' . esc_attr( $excerpt_css ) . '"';
    }
    
    ?>
<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemprop="text"<?php echo $excerpt_css; ?>>
    
    <?php 
    $excerpt = strip_tags( get_the_excerpt() );
    $excerpt = fox_substr( $excerpt, 0, $length, $base );
    
    if ( 'true' == get_theme_mod( 'wi_blog_grid_excerpt_hellip', 'false' ) ) {
        $excerpt .= '&hellip;';
    }
    
    echo wpautop( $excerpt );
    
    $text = trim( $text );
    if ( ! $text ) {
        $text = fox_word( 'more' );
    }
    $more_class = [ 'readmore' ];
    
    if ( in_array( $style, [ 'btn-black', 'btn', 'btn-primary', 'btn-outline' ] ) ) {
        
        $more_class[] = 'fox-btn btn-tiny';
        
        if ( 'btn' == $style ) {
            $more_class[] = 'btn-fill';
        } else {
            $more_class[] = $style;
        }
        
    }
    
    if ( 'simple-btn' == $style ) {
        $more_class[] = 'minimal-link';
    }
    
    if ( $more ) echo '<a href="' . get_permalink() . '" class="' . esc_attr( join( ' ', $more_class ) ) .'">' . $text . '</a>';
    ?>
    
</div>
    
<?php
}
endif;

if ( ! function_exists( 'fox_blog_related' ) ) :
/**
 * Blog Related Posts
 * $layout can be 'standard' or 'newspaper'
 * @since 4.0
 */
function fox_blog_related( $layout = 'standard' ) {
    
    if ( empty( $defaults ) ) {
        $defaults = [
            'number' => 3,
            'source' => 'tag',
            'order' => 'desc',
            'orderby' => 'date',
            'layout' => 'grid-3',
        ];
    }
    
    $related_query = fox_related_query( 'single_related', $defaults, 3 );
    
    $prefix = 'blog';
    
    if ( 'newspaper' == $layout ) $prefix = 'newspaper';
    
    if ( $related_query && $related_query->have_posts() ) {
        
        ?>
        <div class="related-area">

            <h3 class="<?php echo $prefix; ?>-related-heading single-heading">
                <span><?php echo fox_word( 'related' ); ?></span>
            </h3>

            <div class="<?php echo $prefix; ?>-related">
                
                <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>

                    <?php 
                    if ( 'newspaper' == $layout ) { get_template_part( 'parts/content', 'related-newspaper' ); } else {
                    get_template_part( 'parts/content', 'related' ); } ?>

                <?php endwhile; ?>

                <div class="clearfix"></div>

                <div class="line line1"></div>
                <div class="line line2"></div>

            </div><!-- .<?php echo $prefix; ?>-related -->

        </div><!-- .related-area -->

        <?php	

    }
    
    wp_reset_query();
    
}
endif;

/* SINGLE COMPONENTS
------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'fox_page_links' ) ) :
/**
 * Page Links
 * @since 4.0
 */
function fox_page_links() {
    
    wp_link_pages( array(
        'before'      => '<div class="page-links-container"><div class="page-links"><span class="page-links-label">' . esc_html__( 'Pages:', 'wi' ) . '</span>',
        'after'       => '</div></div>',
        'link_before' => '<span class="page-number">',
        'link_after'  => '</span>',
    ) );
    
}
endif;

if ( ! function_exists( 'fox_post_tags' ) ) :
/**
 * Post Tags
 * @since 4.0
 */
function fox_post_tags() {
    
    $tags = get_the_tag_list( '<ul><li>','</li><li>','</li></ul>' );
    if ( ! $tags ) return;
    
    ?>
<div class="single-section single-component single-tags-section">
    
    <div class="single-tags entry-tags post-tags">

        <span class="single-heading tag-label">
            <i class="feather-tag"></i>
            <?php echo fox_word( 'tag_label' ); ?>
        </span>
        
        <div class="fox-term-list">

            <?php echo $tags; ?>

        </div><!-- .fox-term-list -->

    </div><!-- .single-tags -->
    
</div>
    <?php
}
endif;

if ( ! function_exists( 'fox_post_related' ) ) :
/**
 * Related Posts
 * @since 4.0
 */
function fox_post_related() {
    
    $query = fox_related_query();
    
    $blog_args = [
        'query' => $query,
    ];
    
    $options = [
        
        'pagination' => 'false',
        
        'item_template' => '1', // date after
        
        'show_thumbnail' => 'true',
        'thumbnail_placeholder' => 'true',
        
        'thumbnail' => fox_default_thumbnail(),
        'thumbnail_custom' => fox_default_thumbnail_custom(),
        
        'thumbnail_hover_effect' => get_theme_mod( 'wi_blog_grid_thumbnail_hover_effect', 'none' ),
        
        'format_indicator' => 'true',
        
        'show_title' => 'true',
        'title_tag' => 'h3',
        'title_size' => 'tiny',
        
        'show_category' => 'false',
        'show_author' => 'false',
        'show_reading_time' => 'false',
        'show_view' => 'false',
        
        'show_excerpt' => 'false',
        'extra_class' => 'related-posts',
        
        'item_spacing' => 'normal',
        
    ];
    
    $loop = '';
    $layout = get_theme_mod( 'wi_single_related_layout', 'grid-3' );
    
    if ( 'grid-2' == $layout ) {
        
        $loop = 'grid';
        $options[ 'column' ] = 2;
        
        $options[ 'title_size' ] = 'tiny';
        
    } elseif ( 'grid-4' == $layout ) {
        
        $loop = 'grid';
        $options[ 'column' ] = 4;
        $options[ 'title_size' ] = 'tiny';
        
    } elseif ( 'list' == $layout ) {
        
        $loop = 'list';
        $options[ 'title_size' ] = 'normal';
        $options[ 'show_excerpt' ] = 'true';
        $options[ 'excerpt_more' ] = 'false';
        
    } else {
    
        $loop = 'grid';
        $options[ 'column' ] = 3;
    
    }
    
    $options[ 'layout' ] = $loop;
    $blog_args[ 'options' ] = $options;
    
    $blog = new Fox_Blog( $blog_args );
    
    if ( $blog->have_posts() ) {
    
    ?>

<div class="single-section single-component single-related-section">

    <div class="fox-related-posts">

        <h3 class="single-heading related-label related-heading">
            <i class="feather-file-plus"></i>
            <span><?php echo fox_word( 'related' ); ?></span>
        </h3>

        <?php $blog->output(); ?>

    </div><!-- .fox-related-posts -->
    
</div><!-- .single-section -->

<?php
        
    } // have posts
    wp_reset_query();
    
}
endif;

if ( ! function_exists( 'fox_authorbox' ) ) :
/**
 * Author Box
 * @since 4.0
 */
function fox_authorbox() {
    
    $style = get_theme_mod( 'wi_authorbox_style', 'simple' );
    if ( 'box' != $style ) $style = 'simple';
    
    $user = get_userdata( get_the_author_meta( 'ID' ) );
    $link = get_author_posts_url( $user->ID, $user->nicename );
    
    $class = [ 'fox-authorbox' ];
    $class[] = 'authorbox-' . $style;
    
    $tabs = ( 'box' == $style );
    $avatar_shape = get_theme_mod( 'wi_single_authorbox_avatar_shape', 'circle' );
    if ( 'acute' != $avatar_shape && 'round' != $avatar_shape ) $avatar_shape = 'circle';
    
    if ( $tabs ) {
        $class[] = 'has-tabs';
    }
    
?>
<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="authorbox-inner">
        
        <?php /* ---------      AVATAR      -------------- */ ?>
        <div class="user-item-avatar authorbox-avatar avatar-<?php echo esc_attr( $avatar_shape ); ?>">

            <a href="<?php echo $link; ?>">

                <?php echo get_avatar( $user->ID, 'thumbnail' ); ?>

            </a>

        </div><!-- .user-item-avatar -->
        
        <div class="authorbox-text">
            
            <?php /* ---------      NAV TABS      -------------- */ ?>
    
            <?php if ( $tabs ) { ?>

            <div class="authorbox-nav">

                <ul>

                    <li class="active">
                        <a class="authorbox-nav-author" data-tab="author"><?php echo get_the_author(); ?></a>
                    </li><!-- .active -->
                    <li>
                        <a class="authorbox-nav-posts" data-tab="latest"><?php echo fox_word( 'latest_posts' );?></a>
                    </li>

                </ul>

            </div><!-- .authorbox-nav -->

            <?php } ?>
            
            <?php /* ---------      MAIN CONTENT      -------------- */ ?>
    
            <div class="fox-user-item authorbox-tab active authorbox-content" data-tab="author">

                <div class="user-item-body">

                    <?php if ( ! $tabs ) { ?>

                    <h3 class="user-item-name">

                        <a href="<?php echo $link; ?>"><?php echo $user->display_name; ?></a>

                    </h3>
                    
                    <?php } ?>

                    <?php if ( $user->description ) { ?>

                    <div class="user-item-description">

                        <?php echo wpautop( $user->description ); ?>

                    </div><!-- .user-item-description -->

                    <?php } ?>

                    <?php fox_user_social([ 'user' => $user->ID, 'style' => 'plain' ] ); ?>

                </div><!-- .user-item-body -->

            </div><!-- .fox-user-item -->
    
            <?php if ( $tabs ) {

                $args = array(
                    'posts_per_page'    => 4,
                    'author'            => get_the_author_meta( 'ID' ),
                    'no_found_rows'     => true, // no need for pagination
                );

                $get_posts = get_posts( $args );

                if ( ! empty( $get_posts ) ) : ?>

                <div class="authorbox-tab same-author-posts fox-post-list" data-tab="latest">

                    <ul class="same-author-list">

                        <?php foreach ( $get_posts as $post ): ?>

                        <li>
                            <a href="<?php echo get_the_permalink( $post->ID );?>"><?php echo $post->post_title;?></a>
                        </li>

                        <?php endforeach; ?>

                    </ul><!-- .same-author-list -->

                    <?php fox_btn([
                        'text' => fox_word( 'viewall' ),
                        'style' => 'fill',
                        'size'  => 'small',
                        'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
                        'attrs' => 'rel="author"',
                        'extra_class' => 'viewall',
                    ]); ?>

                </div><!-- .same-author-posts -->

                <?php endif; // get_posts

            } // if tabs ?>
            
        </div><!-- .authorbox-text -->
        
    </div><!-- .authorbox-inner -->
    
</div><!-- .fox-authorbox -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_comment_nav' ) ) :
/**
 * Comment Navigation
 * @since 4.0
 */
function fox_comment_nav( $pos ) {
    
    if ( get_comment_pages_count() > 1 && get_theme_mod( 'page_comments' ) ) : // Are there comments to navigate through? ?>

    <nav id="comment-nav-<?php echo esc_attr( $pos ); ?>" class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wi' ); ?></h2>
        <div class="nav-links">

            <div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'wi' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'wi' ) ); ?></div>

        </div><!-- .nav-links -->
    </nav><!-- #comment-nav-# -->

    <?php endif; // Check for comment navigation.

}
endif;

if ( ! function_exists( 'fox_post_comment' ) ) :
/**
 * Displays comment and few options to implement other comment systems
 * @since 4.0
 */
function fox_post_comment() {
    
    // to implement via PHP
    do_action( 'fox_commment', 'post' );

}
endif;

if ( ! function_exists( 'fox_page_comment' ) ) :
/**
 * Displays comment and few options to implement other comment systems
 * @since 4.0
 */
function fox_page_comment() {
    
    // to implement via PHP
    do_action( 'fox_commment', 'page' );

}
endif;

add_action( 'fox_commment', 'fox_implement_comment_shortcode' );

/**
 * Implement custom comment plugin via shortcode
 * @since 4.0
 */
function fox_implement_comment_shortcode( $page ) {
    
    $shortcode = trim( get_theme_mod( 'wi_comment_shortcode' ) );
    if ( $shortcode ) {
        $shortcode = do_shortcode( $shortcode );
    }
    
    // if shortcode not empty, standard comment will be replaced
    if ( $shortcode ) {
        
        echo '<div class="single-component single-section">' . $shortcode  . '</div>';
        
    } else {
        
        if ( 'post' == $page ) {
            
            if ( ! fox_autoload() ) {
                fox_comment();
            } else {
                fox_comment_hidden();
            }
            
        } elseif ( 'page' == $page ) {
            
            fox_comment();
            
        }
    
    }
    
}

if ( ! function_exists( 'fox_comment' ) ) :
/**
 * Displays WP Comment
 * @since 4.0
 */
function fox_comment() {
    
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
    
        comments_template();
    
    endif;
    
}
endif;

if ( ! function_exists( 'fox_comment_hidden' ) ) :
/**
 * Still comment but for autoload mode
 * @since 4.0
 */
function fox_comment_hidden() {
?>

<div class="comment-hidden">
    
    <button class="show-comment-btn fox-btn btn-small btn-fill"><?php echo esc_html__( 'Show comments', 'wi' ); ?></button>
    
    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>
    
</div><!-- .comment-hidden -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_post_navigation' ) ) :
/**
 * Post Navigation
 * @since 4.0
 */
function fox_post_navigation() {
    
    $style = get_theme_mod( 'wi_single_post_navigation_style', 'advanced' );
    if ( 'advanced' != $style ) $style = 'simple';
    
    $same_term = ( 'true' == get_theme_mod( 'wi_single_post_navigation_same_term', 'false' ) );
    
    $class = [ 'fox-post-nav', 'post-nav-' . $style ];
    
    if ( 'simple' == $style ) {
    ?>
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <div class="container">
            
            <?php
                // Previous/next post navigation.
                the_post_navigation( array(
                    'next_text' => '<span class="meta-nav" aria-hidden="true">' . fox_word( 'next_story' ) . '<i class="fa fa-caret-right"></i></span> ' .
                        '<span class="screen-reader-text">' . __( 'Next post:', 'wi' ) . '</span> ' .
                        '<span class="post-title font-heading">%title</span>',
                    'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fa fa-caret-left"></i>' . fox_word( 'previous_story' ) . '</span> ' .
                        '<span class="screen-reader-text">' . __( 'Previous post:', 'wi' ) . '</span> ' .
                        '<span class="post-title font-heading">%title</span>',
                    'in_same_term' => $same_term,
                ) );
            ?>
        </div><!-- .container -->
        
    </div><!-- .fox-post-nav -->

<?php } else {

              $prev_post = get_previous_post( $same_term );
              $next_post = get_next_post( $same_term );
              
              $column = 0;
              if ( $prev_post ) $column++;
              if ( $next_post ) $column++;
              
              if ( ! $column ) return;
              
              $class[] = 'column-' . $column;
        
        if ( $column == 2 ) {
            $size = '2000x900';
        } else {
            $size = '2000x500';
        }
              
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="post-nav-wrapper">
        
        <?php if ( $prev_post ) : ?>
        
        <article class="post-nav-item post-nav-item-previous" itemscope itemtype="https://schema.org/CreativeWork">
            
            <div class="post-nav-item-inner">
            
                <?php fox_thumbnail([
                  'postid' => $prev_post->ID,
                  'thumbnail' => 'custom',
                  'custom' => $size,
                  'etra_class' => 'post-nav-item-thumbnail',
                  'format_indicator' => false,
              ]); ?>

                <div class="post-nav-item-body">

                    <div class="post-nav-item-label"><?php echo fox_word( 'previous_story' ); ?></div>
                    <h3 class="post-item-title post-nav-item-title" itemprop="headline"><?php echo $prev_post->post_title; ?></h3>

                </div><!-- .post-nav-item-body -->

                <div class="post-nav-item-overlay"></div>

                <a class="wrap-link" href="<?php echo get_permalink( $prev_post->ID ); ?>"></a>
                
            </div>
        
        </article><!-- .post-nav-item -->
        
        <?php endif; // prev_post ?>
        
        <?php if ( $next_post ) : ?>
        
        <article class="post-nav-item post-nav-item-previous" itemscope itemtype="https://schema.org/CreativeWork">
            
            <div class="post-nav-item-inner">
            
                <?php fox_thumbnail([
                      'postid' => $next_post->ID,
                      'thumbnail' => 'custom',
                      'custom' => $size,
                      'etra_class' => 'post-nav-item-thumbnail',
                      'format_indicator' => false,
                  ]); ?>

                <div class="post-nav-item-body">

                    <div class="post-nav-item-label"><?php echo fox_word( 'next_story' ); ?></div>
                    <h3 class="post-item-title post-nav-item-title" itemprop="headline"><?php echo $next_post->post_title; ?></h3>

                </div><!-- .post-nav-item-body -->

                <div class="post-nav-item-overlay"></div>

                <a class="wrap-link" href="<?php echo get_permalink( $next_post->ID ); ?>"></a>
                
            </div>
        
        </article><!-- .post-nav-item -->
        
        <?php endif; // prev_post ?>
    
    </div><!-- .post-nav-wrapper -->
    
</div><!-- .fox-post-nav -->

<?php    
        
    } // style
}
endif;

if ( ! function_exists( 'fox_bottom_posts' ) ) :
/**
 * Bottom Posts
 * @since 4.0
 */
function fox_bottom_posts() {
    
    $args = [
        
        'number' => get_theme_mod( 'wi_single_bottom_posts_number', 5 ),
        'orderby' => get_theme_mod( 'wi_single_bottom_posts_orderby', 'date' ),
        'order' => get_theme_mod( 'wi_single_bottom_posts_order', 'desc' ),
        
        'pagination' => false,
        'unique_posts'=> false,
        'exclude' => get_the_ID(),
        
    ];
    
    $source = get_theme_mod( 'wi_single_bottom_posts_source', 'category' );
    
    if ( 'author' == $source ) {
        
        $args[ 'author' ] = get_the_author_meta( 'ID' );
        $name = get_the_author();
        
    } elseif ( 'tag' == $source ) {
        
        $terms = wp_get_post_terms( get_the_ID(), 'post_tag', array( 'fields' => 'ids' ) );
        if ( ! $terms ) {
            return;
        }
        $args[ 'tags' ] = $terms;
        $name = esc_html__( 'Same Tags', 'wi' );
        
    } elseif ( 'date' == $source ) {
        
        // just nothing
        $name = esc_html__( 'Blog', 'wi' );
        
    // category by default    
    } else {
        
        $terms = wp_get_post_terms( get_the_ID(), 'category', array( 'fields' => 'ids' ) );
        if ( ! $terms ) {
            return;
        }
        
        $primary_cat = get_post_meta( get_the_ID(), '_wi_primary_cat', true );
        if ( in_array( $primary_cat, $terms ) ) {
            $cat = $primary_cat;
        } else {
            $cat = $terms[0];
        }
        
        $args[ 'categories' ] = [ $cat ];
        $name = get_cat_name( $cat );
    
    }
    
    $query = fox_query( $args );
    
    $blog_args = [
        'query' => $query,
    ];
    
    $options = [
        'pagination' => 'false',
        
        'show_category' => 'false',
        'show_date' => 'false',
        
        'show_excerpt' => get_theme_mod( 'wi_single_bottom_posts_excerpt', 'true' ),
        'excerpt_length' => 12,
        'excerpt_more' => 'false',
        
        'column' => 5,
        
        'show_title' => 'true',
        'title_size' => 'tiny',
        'title_tag' => 'h3',
        
        'item_spacing' => 'small',
        
        'layout' => 'grid',
        
        'show_thumbnail' => 'true',
        'thumbnail_placeholder' => 'true',
        'thumbnail' => fox_default_thumbnail(),
        'thumbnail_custom' => fox_default_thumbnail_custom(),
        'thumbnail_hover_effect' => get_theme_mod( 'wi_blog_grid_thumbnail_hover_effect', 'none' ),
        'format_indicator' => 'true',
    ];
    
    $blog_args[ 'options' ] = $options;
    
    $blog = new Fox_Blog( $blog_args );
    
    if ( $blog->have_posts() ) {
    
    ?>

<div class="fox-bottom-posts">
    
    <div class="container">
    
        <h3 id="posts-small-heading" class="bottom-posts-heading single-heading">

            <span><?php printf( fox_word( 'latest' ), $name ); ?></span>

        </h3>

        <?php $blog->output(); ?>
        
    </div><!-- .container -->

</div><!-- .fox-bottom-posts -->

<?php
        
    }
    
    wp_reset_query();
    
}
endif;

/* MISC COMPONENTS
------------------------------------------------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'fox_page_header' ) ) :
/**
 * Page Header
 * @since 4.0
 */
function fox_page_header() { ?>

<?php if ( fox_show( 'post_header', 'page' ) ) { ?>

<header id="titlebar" class="single-header page-header">

    <div class="container">

        <h1 class="single-title page-title" itemprop="headline">
            
            <span><?php the_title();?></span>
            
        </h1>

    </div><!-- .container -->

</header><!-- .single-header -->

<?php } ?>

<?php
}
endif;


if ( ! function_exists( 'fox_share' ) ) :
/**
 * Post / Page Share
 * $comment arg has been deprecated since 4.0
 * @since 4.0
 * $custom class args since 4.2
 *
 * possible service: facebook, twitter, pinterest, linkedin, tumblr, reddit, email, whatsapp
 */
function fox_share( $args = [] ) {
    
    $extra_class = isset( $args[ 'extra_class' ] ) ? $args[ 'extra_class' ] : '';
    $style = isset( $args[ 'style' ] ) ? $args[ 'style' ] : '';
    
    $url = get_permalink();
    $title = trim( get_the_title() );
    $title = strip_tags( $title );
    
    $image = '';
    if ( has_post_thumbnail() ) {
        
        $image = wp_get_attachment_thumb_url();
        
    }
    
    $via = trim( get_theme_mod( 'wi_twitter_username' ) );
    
    $share_icons = get_theme_mod( 'wi_share_icons', 'facebook,messenger,twitter,pinterest,whatsapp,email' );
    $share_icons = explode( ',',$share_icons );
    $share_icons = array_map( 'trim', $share_icons );
    
    // get style and shape
    if ( ! $style ) {
        $style = get_theme_mod( 'wi_share_icon_style', 'default' );
    }
    if ( 'custom' != $style ) {
        $style = 'default';
    }
    
    $class = [
        'fox-share',
    ];
    if ( 'default' == $style ) {
        $class[] = 'share-style-2b'; // backward compat reason
    }
    $class[]  = 'share-style-' . $style;
    $class[] = $extra_class;
    
    $share_layout = 'stack';
    if ( 'custom' == $style ) {
        
        /**
         * share layout
         */
        $share_layout = get_theme_mod( 'wi_share_layout', 'inline' );
        if ( 'stack' != $share_layout ) {
            $share_layout = 'inline';
        }
        
        /**
         * COLOR PROBLEM
         */
        $share_icon_color = get_theme_mod( 'wi_share_icon_color' );
        if ( 'brand' == $share_icon_color ) {
            $class[] = 'color-brand';
        } else {
            $class[] = 'color-custom';
        }
        
        $share_icon_background = get_theme_mod( 'wi_share_icon_background' );
        if ( 'brand' == $share_icon_background ) {
            $class[] = 'background-brand';
        } else {
            $class[] = 'background-custom';
        }
        
        $share_icon_hover_color = get_theme_mod( 'wi_share_icon_hover_color' );
        if ( 'brand' == $share_icon_hover_color ) {
            $class[] = 'hover-color-brand';
        } else {
            $class[] = 'hover-color-custom';
        }
        
        $share_icon_hover_background = get_theme_mod( 'wi_share_icon_hover_background' );
        if ( 'brand' == $share_icon_hover_background ) {
            $class[] = 'hover-background-brand';
        } else {
            $class[] = 'hover-background-custom';
        }
        
        /**
         * SHAPE
         */
        $shape = get_theme_mod( 'wi_share_icon_shape' );
        if ( 'acute' != $shape && 'round' != $shape ) $shape = 'circle';
        $class[] = 'share-icons-shape-' . $shape;
        
        /**
         * SIZE
         */
        $size = absint( get_theme_mod( 'wi_share_icon_size', 40 ) );
        if ( $size >= 48 ) {
            $class[] = 'size-medium';
        } elseif ( $size >= 36 ) {
            $class[] = 'size-normal';
        } else {
            $class[] = 'size-small';
        }
        
    } else {
    
        $class[] = 'background-brand';
    
    }
    
    // since 4.3
    $class[] = 'share-layout-' . $share_layout;

?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <?php if ( fox_word( 'share_label' ) ) { ?>
    
    <span class="share-label"><i class="fa fa-share-alt"></i><?php echo fox_word( 'share_label' ); ?></span>
    
    <?php } ?>
    
    <ul>
        
        <?php foreach ( $share_icons as $icon ) :
    
            $href = '#';
    
            if ( 'facebook' == $icon ) {
                
                $href = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
                if ( $image ) {
                    $href .= '&amp;p[images][0]=' . urlencode( $image );
                }
                
            } elseif ( 'twitter' == $icon ) {
                
                $href = 'https://twitter.com/intent/tweet?url=' . urlencode($url) .'&amp;text=' . urlencode( html_entity_decode( $title ) );
                
                if ( $via ) {
                    $href .= '&amp;via=' . urlencode( $via );
                }
                
            } elseif ( 'messenger' == $icon ) {
                
                $href = 'https://www.facebook.com/dialog/send?app_id=794927004237856&amp;link=' . urlencode($url) . '&amp;redirect_uri=' . urlencode( home_url( '/' ) );
                
            } elseif ( 'pinterest' == $icon ) {
                
                $href = 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;description=' . urlencode( html_entity_decode( $title ) );
                if ( $image ) {
                    $href .= '&amp;media=' . urlencode($image);
                }
                
            } elseif ( 'linkedin' == $icon ) {
                
                $href = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $url ) . '&amp;title=' . urlencode( html_entity_decode( $title ) );
            
            } elseif ( 'whatsapp' == $icon ) {
            
                $href = 'https://api.whatsapp.com/send?phone=&text=' . urlencode( $url );
                
            } elseif ( 'reddit' == $icon ) {
            
                $href = 'https://www.reddit.com/submit?url=' . urlencode( $url ) . '&title=' . urlencode( html_entity_decode( $title ) );
            
            } elseif ( 'email' == $icon ) {
            
                $href = 'mailto:?subject=' . rawurlencode( html_entity_decode( $title ) )  . '&amp;body=' . rawurlencode($url);
            
            }
    
            $icon_map = [
                'facebook' => 'fab fa-facebook-f',
                'messenger' => 'fab fa-facebook-messenger',
                'twitter'   => 'fab fa-twitter',
                'pinterest' => 'fab fa-pinterest-p',
                'linkedin' => 'fab fa-linkedin-in',
                'whatsapp' => 'fab fa-whatsapp',
                'reddit'   => 'fab fa-reddit-alien',
                'email' => 'feather-mail',
            ];
    
            if ( 'email' == $icon ) {
                $a_class = 'email-share';
            } else {
                $a_class = 'share share-' . $icon;
            }
            $ic = $icon_map[ $icon ];
            $li_class = 'li-share-' . $icon;
            $label = ucfirst( $icon );
            ?>
        
        <li class="<?php echo esc_attr( $li_class ); ?>">
            
            <a href="<?php echo esc_url( $href ); ?>" title="<?php echo esc_attr( $label ); ?>" class="<?php echo esc_attr( $a_class ); ?>">
                
                <i class="<?php echo esc_attr( $ic ); ?>"></i>
                <span><?php echo esc_html( $label ); ?></span>
                
            </a>
            
        </li>
        
        <?php endforeach; ?>
        
    </ul>
    
</div><!-- .fox-share -->
<?php 
    
}
endif;

if ( ! function_exists( 'fox_pagination' ) ) :
/**
 * Pagination
 * @since 4.0
 */
function fox_pagination( $query = false ) {
    
    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }
    
    $prev_label = fox_word( 'previous' );
    $next_label = fox_word( 'next' );
    
    $big = 9999; // need an unlikely integer
    
    $paged = ( is_front_page() && ! is_home() ) ? get_query_var( 'page' ) : get_query_var( 'paged' );
    
	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, $paged ),
		'total' => $query->max_num_pages,
		'type'			=> 'plain',
		'before_page_number'	=>	'<span>',
		'after_page_number'	=>	'</span>',
		'prev_text'    => '<span>' . $prev_label . '</span>',
		'next_text'    => '<span>' . $next_label . '</span>',
	) );
    
    if ( $pagination ) {
        
        echo '<div class="wi-pagination fox-pagination font-heading"><div class="pagination-inner">' . $pagination  . '</div></div>';
        
	}

}
endif;