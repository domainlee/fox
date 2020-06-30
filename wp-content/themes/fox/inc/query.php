<?php

if ( ! function_exists( 'fox_query' ) ) :
/**
 * Returns new WP_Query
 *
 * @since 4.0
 */
function fox_query( $args ) {
    
    global $rendered_articles;
    
    extract( wp_parse_args( $args, array(
        
        'number' => null,
        
        'orderby' => 'date',
        'order' => 'desc',
        
        'categories' => null,
        'exclude_categories' => null,
        
        'tags' => null,
        'exclude_tags' => null,
        
        'format' => 'all',
        
        'author' => '',

        'include' => null,
        'exclude' => null,
        
        'featured' => null,
        
        'custom_query' => null,
        
        'unique_posts' => false,
        
        'pagination' => false,
        
        'paged_disable' => false,
        
    ) ) );
    
    if ( $custom_query && is_string( $custom_query ) && trim( $custom_query ) ) {
        
        $query = new WP_Query( esc_sql( $custom_query ) );
        
    } else {
        
        $query_args = array(
            
            'ignore_sticky_posts' => true,
            'post_status' => 'publish',
            
        );
    
        // ----------- posts per page
        $query_args[ 'posts_per_page' ] = $number;

        // ----------- orderby
        $orderby = strtolower( $orderby );
        if ( ! in_array( $orderby, array( 'date', 'modified', 'title', 'comment_count', 'rand', 'view', 'view_week', 'view_month', 'view_year', 'review_score', 'review_date' ) ) ) {
            $orderby = 'date';
        }
        $order = strtolower( $order );
        if ( 'asc' !== $order ) $order = 'desc';
        
        if ( 'view' === $orderby ) {
            
            $query_args[ 'orderby' ] = 'post_views';
            $query_args[ 'order' ] = $order;
            
        } elseif ( 'view_week' == $orderby ) {
            
            $query_args[ 'orderby' ] = 'post_views';
            $query_args[ 'views_query' ] = [
                'year' => date('Y'),
                'week' => date('W'),
            ];
            $query_args[ 'order' ] = $order;
            
        } elseif ( 'view_month' == $orderby ) {
            
            $query_args[ 'orderby' ] = 'post_views';
            $query_args[ 'views_query' ] = [
                'year' => date('Y'),
                'month' => date('n'),
            ];
            $query_args[ 'order' ] = $order;
            
        } elseif ( 'view_year' == $orderby ) {
            
            $query_args[ 'orderby' ] = 'post_views';
            $query_args[ 'views_query' ] = [
                'year' => date('Y'),
            ];
            $query_args[ 'order' ] = $order;
            
        } elseif ( 'review_score' == $orderby || 'review_date' == $orderby ) {
            
            $query_args[ 'orderby' ] = 'meta_value_num';
            $query_args[ 'meta_key' ] = '_wi_review_average';
            $query_args[ 'meta_value_num' ] = 0;
            $query_args[ 'meta_compare' ] = '>';
            
            if ( 'review_date' == $orderby ) {
                $query_args[ 'orderby' ] = 'date';
            }
            
            $query_args[ 'order' ] = $order;
            
        } else {
            
            $query_args[ 'orderby' ] = $orderby;
            $query_args[ 'order' ] = $order;
            
        }

        // ----------- featured
        if ( 'yes' === $featured || 'true' === $featured ) {
            
            $query_args[ 'featured' ] = true;
            
        }
        
        // ----------- author
        if ( $author && 'all' !== $author ) {
            $author = str_replace( 'author_', '', $author );
            $author = strval( $author );
            if ( ! is_numeric( $author ) ) {
                $query_args[ 'author_name' ] = $author;
            } else {
                $query_args[ 'author' ] = $author;
            }
            
        }
        
        // ----------- categories
        if ( $categories ) {
            $cat_ids = array();
            foreach ( $categories as $id ) {
                
                $term_id = 0;
                
                if ( is_numeric( $id ) ) {
                    $term_id = $id;
                }
                
                if ( ! $term_id && strpos( $id, 'cat_' ) !== 0 ) {
                    continue;
                }
                
                $id = substr( $id, 4 );
                // ie. slug
                if ( ! is_numeric( $id ) ) {
                    $term = get_term_by( 'slug', $id, 'category' );
                    if ( $term ) {
                        $term_id = $term->term_id;
                    }
                } else {
                    $term_id = $id;
                }
                
                if ( $term_id ) {
                    $cat_ids[] = $term_id;
                }
                
            }
            if ( 1 == count( $cat_ids ) ) {
                $query_args[ 'cat' ] = $cat_ids[0];
            } elseif ( ! empty( $cat_ids ) ) $query_args[ 'category__in' ] = $cat_ids;
        }
        
        // ----------- exclude categories
        if ( $exclude_categories ) {
            $cat_ids = array();
            foreach ( $exclude_categories as $id ) {
                
                $term_id = 0;
                
                if ( is_numeric( $id ) ) {
                    $term_id = $id;
                }
                
                if ( ! $term_id && strpos( $id, 'cat_' ) !== 0 ) {
                    continue;
                }
                
                $id = substr( $id, 4 );
                // ie. slug
                if ( ! is_numeric( $id ) ) {
                    $term = get_term_by( 'slug', $id, 'category' );
                    if ( $term ) {
                        $term_id = $term->term_id;
                    }
                } else {
                    $term_id = $id;
                }
                
                if ( $term_id ) {
                    $cat_ids[] = $term_id;
                }
                
            }
            
            if ( ! empty( $cat_ids ) ) $query_args[ 'category__not_in' ] = $cat_ids;
        }
        
        // ----------- tags
        if ( $tags ) {
            if ( is_string( $tags ) ) {
                $tags = explode( ',', $tags );
                $tags = array_map( 'trim', $tags );
            }
            $cat_ids = array();
            foreach ( $tags as $id ) {
                
                $term_id = 0;
                // case 1: numeric
                if ( is_numeric( $id ) ) {
                    $term_id = $id;
                
                // case 2: it has form tag_number, then number will be the ID
                } elseif ( preg_match( '/^tag_(\d+)$/', $id, $match ) ) {
                    $term_id = $match[1];
                    
                // case 3: what we enter is tag name
                } else {
                    $term = get_term_by( 'name', $id, 'post_tag' );
                    if ( $term ) {
                        $term_id = $term->term_id;
                    }
                }
                
                if ( $term_id ) {
                    $cat_ids[] = $term_id;
                }
            }
            if ( 1 == count( $cat_ids ) ) {
                $query_args[ 'tag_id' ] = $cat_ids[0];   
            } elseif ( ! empty( $cat_ids ) ) $query_args[ 'tag__in' ] = $cat_ids;
        }
        
        // ----------- exclude tags
        if ( $exclude_tags ) {
            if ( is_string( $exclude_tags ) ) {
                $exclude_tags = explode( ',', $exclude_tags );
                $exclude_tags = array_map( 'trim', $exclude_tags );
            }
            $cat_ids = array();
            foreach ( $exclude_tags as $id ) {
                
                $term_id = 0;
                // case 1: numeric
                if ( is_numeric( $id ) ) {
                    $term_id = $id;
                
                // case 2: it has form tag_number, then number will be the ID
                } elseif ( preg_match( '/^tag_(\d+)$/', $id, $match ) ) {
                    $term_id = $match[1];
                    
                // case 3: what we enter is tag name
                } else {
                    $term = get_term_by( 'name', $id, 'post_tag' );
                    if ( $term ) {
                        $term_id = $term->term_id;
                    }
                }
                
                if ( $term_id ) {
                    $cat_ids[] = $term_id;
                }
                
            }
            if ( ! empty( $cat_ids ) ) $query_args[ 'tag__not_in' ] = $cat_ids;
        }
        
        // ----------- format
        if ( in_array( $format, [ 'video', 'audio', 'gallery', 'link' ] ) ) {
            
            $query_args[ 'tax_query' ] = array(
                array(
                    'taxonomy' => 'post_format',
                    'field'    => 'slug',
                    'terms'    => array( 'post-format-' . $format ),
                ),
            );
            
        }
        
        // ----------- pagination
        if ( 'yes' == $pagination ) {
            
            if ( is_front_page() ) {
                $query_args[ 'paged' ] = get_query_var( 'page' );
            } else {
                $query_args[ 'paged' ] = get_query_var( 'paged' );
            }
            
        } else {
        
            $query_args[ 'no_found_rows' ] = true;
        
        }
        
        // ----------- posts in
        if ( is_array( $include ) ) $include = join( ',', $include );
        if ( $include ) {
            $ids = explode( ',', $include );
            $ids = array_map( 'trim', $ids );
            $ids = array_map( 'intval', $ids );
            if ( ! empty( $ids ) ) $query_args[ 'post__in' ] = $ids;
        }
        
        // ----------- posts not in
        $excluded_ids = array();
        if ( $unique_posts ) {
            $excluded_ids = $rendered_articles;
        }
        
        if ( is_array( $exclude ) ) $exclude = join( ',', $exclude );
        if ( $exclude ) {
            $ids = explode( ',', $exclude );
            $ids = array_map( 'trim', $ids );
            $ids = array_map( 'intval', $ids );
            $excluded_ids = array_merge( $excluded_ids, $ids );
        }
        if ( ! empty( $excluded_ids ) ) {
            
            $query_args[ 'post__not_in' ] = $excluded_ids;
            
        }
        
        $query = new WP_Query( $query_args );
        
    }
    
    return $query;

}

endif;

if ( ! function_exists( 'fox_query_params' ) ) :
/**
 * Return query params used commonly
 * $exclude is the array of ids to exclude
 *
 *
 * @since 4.0
 */
function fox_query_params( $exclude = array(), $override = [] ) {
    
    $params = array();
    
    $params[ 'number' ] = array(
        'title' => 'Number of posts:',
        'type' => 'text',
        'std' => '4',
        
        'section' => 'query',
        'section_title' => 'Query',
    );
    
    $params[ 'orderby' ] = array(
        'title' => 'Order by',
        'type' => 'select',
        'options' => fox_orderby_support(),
        'std' => 'date',
    );
    
    $params[ 'order' ] = array(
        'title' => 'Order?',
        'type' => 'select',
        'options' => fox_order_support(),
        'std' => 'desc',
    );
    
    $cat_options = array();
    $all_cats = get_terms( array( 'hide_empty' => false, 'taxonomy' => 'category', 'orderby' => 'name', 'order' => 'ASC' ) );
    foreach ( $all_cats as $cat ) {
        $cat_options[ 'cat_' . strval( $cat->slug ) ] = $cat->name;
    }
    
    $author_options = array( 'all' => 'All' );
    
    $all_authors = get_users( array( 'who' => 'authors', 'orderby' => 'display_name', 'order' => 'ASC' ) );
    foreach ( $all_authors as $at ) {
        $author_options [ 'author_' . strval( $at->user_nicename ) ] = $at->display_name;
    }
    
    $params[ 'categories' ] = array(
        'title' => 'Only from categories:',
        'type' => 'select2',
        'multiple' => true,
        'options' => $cat_options,
    );
    
    $params[ 'exclude_categories' ] = array(
        'title' => 'Exclude categories:',
        'type' => 'select2',
        'multiple' => true,
        'options' => $cat_options,
    );
    
    $params[ 'tags' ] = array(
        'title' => 'Only from tags:',
        'type' => 'text',
        'desc' => 'Enter tag IDs or names, separate them by commas.',
    );
    
    $params[ 'exclude_tags' ] = array(
        'title' => 'Exclude tags:',
        'type' => 'text',
        'desc' => 'Enter tag IDs or names, separate them by commas.',
    );
    
    $params[ 'format' ] = array(
        'title' => 'Only post format:',
        'type' => 'select',
        'options' => [
            '' => 'All',
            'video' => 'Video',
            'audio' => 'Audio',
            'gallery' => 'Gallery',
            'link' => 'Link',
        ],
        'std' => '',
    );
    
    $params[ 'author' ] = array(
        'title' => 'Only posts from author:',
        'type' => 'select',
        'std' => 'all',
        'options' => $author_options,
    );
    
    $params[ 'include' ] = array(
        'title' => 'Display only posts from IDs:',
        'desc' => 'Enter post IDs, separated by commas',
        'type' => 'text',
    );
    
    $params[ 'exclude' ] = array(
        'title' => 'Exclude post IDs:',
        'desc' => 'Enter post IDs, separated by commas',
        'type' => 'text',
    );
    
    $params[ 'featured' ] = array(
        'title' => 'Show only featured posts?',
        'type' => 'switcher',
        'std' => '',
    );
    
    $params[ 'pagination' ] = array(
        'title' => 'Pagination?',
        'type' => 'switcher',
        'std' => '',
    );
    
    $params[ 'paged_disable' ] = array(
        'title' => 'Disable on page 2, 3..',
        'type' => 'switcher',
        'std' => '',
    );
    
    $params[ 'unique_posts' ] = array(
        'title' => 'Unique posts?',
        'desc' => 'If enabled, it prevents a post appears twice.',
        'type' => 'switcher',
        'std' => '',
    );
    
    $params[ 'custom_query' ] = array(
        'title' => 'Your Custom Query',
        'type' => 'code',
        'desc' => 'Please make sure you understand what you\'re writing when using this option',
    );
    
    // exclude
    // and set section 'query'
    $first = true;
    foreach ( $params as $id => $param ) {
        if ( in_array( $id, $exclude ) ) {
            unset( $params[ $id ] );
        } elseif ( $first ) {
            $params[ $id ][ 'section' ] = 'query';
            $params[ $id ][ 'section_title' ] = 'Query';
            $first = false;
        }
    }
    
    // OVERRIDE
    if ( ! empty( $override ) ) {
        foreach ( $override as $id => $arr ) {
            if ( isset( $params[ $id ] ) ) $params[ $id ] = $arr;
        }
    }
    
    return apply_filters( 'fox_default_query_params', $params );

}
endif;

add_action( 'fox_after_render_post', 'fox_add_rendered_article' );
/**
 * @since 4.0
 */
function fox_add_rendered_article() {
    
    global $post, $rendered_articles;
    $rendered_articles[] = $post->ID;
    
}

/**
 * return a WP_Query instance for "related posts"
 * only used in loop
 * this function will be used for:
 *
 * related posts after main content in single post
 * related posts in standard blog layout
 * related posts in newspapers layout
 * bottom posts in single post
 * slide up posts in the footer of single post
 * top posts in single post
 *
 * $prefix is the position we want to take from the customizer, eg. single_related, single_bottom
 * $defaults is the default array of values: number => 5, source => category, ...
 *
 * @since 4.0
 * @generalized since 4.3
 */
function fox_related_query( $prefix = '', $defaults = [], $number = null ) {
    
    $args = [
        
        'number' => get_theme_mod( 'wi_' . $prefix . '_number', $defaults[ 'number' ] ),
        
        'orderby' => get_theme_mod( 'wi_' . $prefix . '_orderby', $defaults[ 'orderby' ] ),
        'order' => get_theme_mod( 'wi_' . $prefix . '_order', $defaults[ 'order' ] ),
        
        'exclude' => get_the_ID(),
        
        'pagination' => false,
        'unique_posts'=> false
    ];
    
    // force number
    if ( $number ) {
        $args[ 'number' ] = $number;
    }
    
    $related_source = get_theme_mod( 'wi_' . $prefix . '_source', $defaults[ 'source' ] );
    
    if ( 'author' == $related_source ) {
        
        $args[ 'author' ] = get_the_author_meta( 'ID' );
        
    } elseif ( 'category' == $related_source ) {
        
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
        
    } elseif ( 'date' == $related_source ) {
        
        // just nothing
        
    } elseif ( 'featured' == $related_source ) {
        
        $args[ 'featured' ] = 'true';
        
    // tag    
    } else {
        
        $terms = wp_get_post_terms( get_the_ID(), 'post_tag', array( 'fields' => 'ids' ) );
        if ( empty( $terms ) ) return;
        
        $args[ 'tags' ] = $terms;
    
    }
    
    return fox_query( $args );
    
}