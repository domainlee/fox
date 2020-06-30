<?php
/**
 * Here we have functions concerning blog
 * hooks, filter, options etc
 */
if ( ! function_exists( 'fox_current' ) ) :
/**
 * Tell us about current archive page: title, description etc
 * @since 4.0
 */
function fox_current() {
    
    $title = $subtitle = $label = $page = '';
    
    if ( is_home() && ! is_front_page() ) {
        
        $page = 'home';
        $page_for_post = get_option( 'page_for_posts' );
        if ( $page_for_post ) {
            $post = get_post( $page_for_post );
            if ( $post ) {
                $title = $post->post_title;
            }
        }
        
    } elseif ( is_category() ) {
    
        $obj = get_queried_object();
        
        $page = 'category';
        $label = fox_word( 'browse_category' );
        $title = $obj->name;
        $subtitle = do_shortcode( $obj->description );
        
    } elseif ( is_search() ) {
        
        global $wp_query;
        
        $page = 'search';
        $label = fox_word( 'search_result' );
        $title  = get_search_query();
        $subtitle = sprintf( fox_word( 'result_found' ), $wp_query->found_posts);
        
    } elseif ( is_day() ) {
        
        $page = 'day';
        $label = esc_html__( 'Daily archive','wi' );
        $title = get_the_time( 'F d, Y' );
        
    } elseif ( is_month() ) {
        
        $page = 'month';
        $label = esc_html__('Monthly archive','wi');
        $title = get_the_time('F Y');
        
    } elseif ( is_year() ) {
        
        $page = 'year';
        $label = esc_html__('Yearly archive','wi');
        $title = get_the_time('Y');
        
    } elseif ( is_tag() ) {
        
        $page = 'tag';
        $label = fox_word( 'browse_tag' );
        
        $obj = get_queried_object();
        $title = $obj->name;
        $subtitle = do_shortcode( $obj->description );
        
    } elseif ( is_author() ) {
        
        $page = 'author';
        $label = fox_word( 'browse_author' );
        
        global $author;
        $userdata = get_userdata( $author );
        $title = $userdata->display_name;
        $subtitle = $userdata->description;
        
    } elseif ( is_404() ) {
        
        $page = '404';
        $label = esc_html__( 'Not found','wi' );
        $title = get_theme_mod( 'wi_404_title' );
        if ( ! $title ) {
            $title = esc_html__( 'Page Not Found', 'wi' );
        } 
        
    } elseif ( is_archive() ) {
        
        $page = 'archive';
        $label = esc_html__( 'Archive', 'wi' );
        $title = get_the_archive_title();
        
    }

    if ( is_front_page() && ! is_home() ) {
        
        $paged = get_query_var( 'page' );
    
    } else {
        
        $paged = get_query_var( 'paged' );
        
    }
        
    if ( $paged ) {

        $page_text = '<span class="paged-label">' . sprintf( fox_word( 'paged' ), $paged ) . '</span>';
        
        if ( $title ) {

            $title = $title . $page_text;

        }

    }
    
    $return = [
        'label' => $label,
        'title' => $title,
        'subtitle' => $subtitle,
        'page' => $page,
    ];
    
    // for complicated things to be displayed
    $return = apply_filters( 'fox_current', $return );
    
    return $return;
    
}
endif;

if ( ! function_exists( 'fox_page_id' ) ) :
/**
 * Returns the page id in case we're in special pages like: home, page template, shop etc
 * @since 4.0
 */
function fox_page_id() {
    
    if ( is_singular() ) return get_the_ID();
    if ( is_page() ) return get_the_ID();
    
    $id = null;
    
    if ( is_home() && is_front_page() ) {
    
        $id = get_option( 'page_for_posts' );
    
    }
    
    return apply_filters( 'fox_page_id', $id );
    
}
endif;

if ( ! function_exists( 'fox_layout' ) ) :
/**
 * return the current page layout
 * @since 4.0
 */
function fox_layout() {
    
    $layout = '';
    
    if ( is_home() ) {
        
        $layout = get_theme_mod( 'wi_home_layout' );
        
    } elseif ( is_category() || is_tag() ) {
        
        $obj = get_queried_object();
        $layout = get_term_meta( $obj->term_id, '_wi_layout', true );
        
        // try to get from option for backward compatibility
        if ( ! $layout ) {
            $term_meta = get_theme_mod( "taxonomy_{$obj->term_id}" );
            if ( isset( $term_meta['layout'] ) ) {
                $layout = $term_meta['layout'];
            }
        }
        
        if ( ! $layout ) {
            if ( is_category() ) {
                $layout = get_theme_mod( 'wi_category_layout' );
            } else {
                $layout = get_theme_mod( 'wi_tag_layout' );
            }
            
        }
        
    } elseif ( is_search() ) {
        
        $layout = get_theme_mod( 'wi_search_layout' );
        
    } elseif ( is_day() || is_month() || is_year() ) {
        
        $layout = get_theme_mod( 'wi_archive_layout' );
        
    } elseif ( is_author() ) {
        
        $layout = get_theme_mod( 'wi_author_layout' );
        
    }
    
    $layout = strval( $layout );
    
    // final validate
    if ( ! array_key_exists( $layout , fox_archive_layout_support() ) ) $layout = 'list';

    return apply_filters( 'fox_layout' , $layout );
    
}
endif;

/**
 * return a valid layout that theme supports
 * @since 4.4
 */
function fox_validate_layout( $layout ) {
    
    // back compat
    if ( 'big-post' == $layout ) {
        $layout = 'big';
    }
    
    $supported_layouts = fox_builder_layout_support();
    
    if ( ! isset( $supported_layouts[$layout] ) ) {
        $layout = 'slider';
    }
    
    return $layout;
    
}

if ( ! function_exists( 'fox_get_loop_from_layout' ) ) :
/**
 * $layout is grid-2, grid-3
 * return grid
 *
 * @since 4.0
 */
function fox_get_loop_from_layout( $layout ) {
    
    if ( 'grid-2' == $layout || 'grid-3' == $layout || 'grid-4' == $layout || 'grid-5' == $layout ) {
        
        $loop = 'grid';
        $column = absint( str_replace( 'grid-', '', $layout ) );

    } elseif ( 'masonry-2' == $layout || 'masonry-3' == $layout || 'masonry-4' == $layout || 'masonry-5' == $layout ) {

        $loop = 'masonry';
        $column = absint( str_replace( 'masonry-', '', $layout ) );

    } else {

        $loop = $layout;

    }
    
    return $loop;
    
}
endif;

if ( ! function_exists( 'fox_get_column_from_layout' ) ) :
/**
 * from grid-2 return 2
 * @since 4.3
 */
function fox_get_column_from_layout( $layout ) {
    
    $column = '';
    if ( 'grid-2' == $layout || 'grid-3' == $layout || 'grid-4' == $layout || 'grid-5' == $layout ) {
        
        $loop = 'grid';
        $column = absint( str_replace( 'grid-', '', $layout ) );

    } elseif ( 'masonry-2' == $layout || 'masonry-3' == $layout || 'masonry-4' == $layout || 'masonry-5' == $layout ) {

        $loop = 'masonry';
        $column = absint( str_replace( 'masonry-', '', $layout ) );

    } else {

        $loop = $layout;

    }
    
    return $column;
    
}
endif;

/* FILTERS AND ACTIONS
------------------------------------------------------------------------------------------------------------------------ */
/**
 * Post Format Link
 * @since 2.4
 */
add_filter( 'post_link', 'wi_post_format_link', 10, 2 );
function wi_post_format_link( $url, $post ) {
    
    if ( ! is_admin() ) {
        
        $link = '';

        if ( 'link' === get_post_format( $post->ID ) ) {

            $link = trim( get_post_meta( $post->ID, '_format_link_url', true ) );

        }

        if ( $link ) $url = $link;
        
    }
    
    return $url;

}

add_filter( 'found_posts', 'wi_adjust_offset_pagination', 1, 2 );
if ( ! function_exists( 'wi_adjust_offset_pagination' ) ) :
/**
 * Homepage offset - such a stupid option
 * to implement it into a theme
 * @since 2.3
 */
function wi_adjust_offset_pagination( $found_posts, $query ) {
    
    if ( $query->is_home() && $query->is_main_query() && ! is_admin() ) {
        
        // Offset should be available when infinite scroll module not enabled
        $offset = absint( get_theme_mod( 'wi_offset' ) );
        if ( $offset > 0 ) {

            //Reduce WordPress's found_posts count by the offset... 
            return $found_posts - $offset;

        }
        
    }
    
    return $found_posts;
    
}
endif;

if ( !function_exists( 'wi_limit_posts_per_page' ) ) :
add_action('pre_get_posts', 'wi_limit_posts_per_page' );
/**
 * Custom number of posts to display on front page
 * But not other archive pages
 */
function wi_limit_posts_per_page(&$query) {
    
    if ( !is_admin() && $query->is_main_query() && is_home() ) {
        
        $number = trim( get_theme_mod( 'wi_home_number' ) );
        if ( ! empty( $number) ) {
            $query->set( 'posts_per_page', $number );
        }
        
    }
    
}
endif;

add_action( 'pre_get_posts', 'wi_pre_get_posts', 300 );
if ( ! function_exists( 'wi_pre_get_posts' ) ) :
/**
 * Custom Main Bog Query Options
 *
 * Offset, include, exclude categories
 * @since 2.3
 * @include added in 4.0
 */
function wi_pre_get_posts( $query ) {
    
    if ( ! is_admin() && $query->is_home() && $query->is_main_query() ) {
        
        $include = trim( get_theme_mod( 'wi_include_categories', '' ) );
        $exclude = trim( get_theme_mod( 'wi_exclude_categories', '' ) );
        
        // INCLUDE
        if ( ! empty( $include ) ) {
            $include = explode( ',', $include );
            $include = array_map( 'absint', $include );
            if ( ! empty( $include ) ) {
                $query->set( 'category__in', $include );
            }
        }
        
        // EXCLUDE
        if ( ! empty( $exclude ) ) {
            $exclude = explode( ',', $exclude );
            $exclude = array_map( 'absint', $exclude );
            if ( ! empty( $exclude ) ) {
                $query->set( 'category__not_in', $exclude );
            }
        }
        
        // OFFSET
        $offset = absint( get_theme_mod( 'wi_offset' ) );
        if ( $offset > 0 ) {

            $home_ppp = trim( get_theme_mod( 'wi_home_number' ) );
    
            if ( ! empty( $home_ppp) ) $ppp = $home_ppp;
            else $ppp = get_theme_mod( 'posts_per_page' );

            // Detect and handle pagination...
            if ( $query->is_paged ) {

                //Manually determine page query offset (offset + current page (minus one) x posts per page)
                $page_offset = $offset + ( ($query->query_vars['paged']-1) * $ppp );

                //Apply adjust page offset
                $query->set('offset', $page_offset );

            } else {

                //This is the first page. Just use the offset...
                $query->set('offset',$offset);

            }

        }
        
	}
    
}
endif;

if ( ! function_exists( 'wi_remove_more_link_scroll' ) ) :
add_filter( 'the_content_more_link', 'wi_remove_more_link_scroll' );

/**
 * Remove #more-link scroll
 * Just another stupid thing
 *
 * @since 1.0
 */
function wi_remove_more_link_scroll( $link ) {
    
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
    
}
endif;

if ( ! function_exists( 'fox_page_in_search_result' ) ) :
/**
 * Include/Exclude pages from search results
 * @since 4.0
 */
function fox_page_in_search_result( $args, $post_type ) {
    
    if ( 'page' === $post_type ) {
        
        if ( 'true' === get_theme_mod( 'wi_exclude_pages_from_search', 'false' ) ) {
            
            $args[ 'exclude_from_search' ] = true;
            
        }
        
    }
    
    
    return $args;
    
}
endif;
add_filter( 'register_post_type_args', 'fox_page_in_search_result', 10, 2 );

if ( ! function_exists( 'wi_remove_bracket_in_excerpt' ) ) :
/**
 * Remove the ugly bracket [...] in the excerpt
 * @since 1.0
 *
 * @edited in 4.0
 */
add_filter( 'excerpt_more','wi_remove_bracket_in_excerpt' );
function wi_remove_bracket_in_excerpt( $excerpt ) {
    
    // since 4.0, we return empty string
    // because excerpt will be sliced by other function
    return '';
    
	//return '&hellip;';
}
endif;