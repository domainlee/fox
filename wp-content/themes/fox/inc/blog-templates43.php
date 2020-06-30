<?php
/**
 * General class for displaying any post block
 * In this version, it supports all source of input data: customizer, elementor, shortcode
 *
 *
 * @package Fox
 * @since 4.3
 */

if ( ! function_exists( 'fox_post_customizer_default_params' ) ) :
/**
 * Adapter to convert settings from customizer => fox43_blog
 *
 * @since 4.3
 *
 * $loop: grid, masonry, list, newspaper..
 * it depends on loops because there're some same-canonical-name options
 * but they may have different loop default values
 */
function fox_post_customizer_default_params( $loop = '' ) {
    
    // legacy reason
    $pre = 'wi_blog_grid_';
    
    $params = [
        
        'column' => get_theme_mod( $pre . 'column', '3' ),
        'item_spacing' => get_theme_mod( $pre . 'item_spacing', 'normal' ),
        'big_first_post' => ( 'true' == get_theme_mod( $pre . 'big_first_post', 'true' ) ),
        'list_spacing' => get_theme_mod( $pre . 'list_spacing', 'normal' ),
        'live' => true,
        
        // general
        'align' => get_theme_mod( $pre . 'item_align', 'left' ),
        'item_template' => get_theme_mod( $pre . 'item_template', '1' ),
        
        // thumbnail
        'thumbnail_show' => ( 'true' == get_theme_mod( $pre . 'show_thumbnail', 'true' ) ),
        'thumbnail' => get_theme_mod( $pre . 'thumbnail', 'landscape' ),
        'thumbnail_custom' => get_theme_mod( $pre . 'thumbnail_custom' ),
        'thumbnail_placeholder' => ( 'true' == get_theme_mod( $pre . 'thumbnail_placeholder', 'true' ) ),
        'thumbnail_placeholder_id' => get_theme_mod( $pre . 'default_thumbnail' ),
        'thumbnail_shape' => get_theme_mod( $pre . 'thumbnail_shape', 'acute' ),
        'thumbnail_hover' => get_theme_mod( $pre . 'thumbnail_hover_effect', 'none' ),
        'thumbnail_hover_logo' => get_theme_mod( $pre . 'thumbnail_hover_logo' ),
        'thumbnail_hover_logo_width' => get_theme_mod( $pre . 'thumbnail_hover_logo_width' ),
        'thumbnail_showing_effect' => get_theme_mod( $pre . 'thumbnail_showing_effect', 'none' ),
        'thumbnail_format_indicator' => ( 'true' == get_theme_mod( $pre . 'format_indicator', 'true' ) ),
        'thumbnail_index' => ( 'true' == get_theme_mod( $pre . 'thumbnail_index' ) ),
        'thumbnail_view' => ( 'true' == get_theme_mod( $pre . 'thumbnail_view' ) ),
        'thumbnail_review_score' => ( 'true' == get_theme_mod( $pre . 'thumbnail_review_score' ) ),
        
        // title
        'title_show' => ( 'true' == get_theme_mod( $pre . 'show_title', 'true' ) ),
        'title_tag' => get_theme_mod( $pre . 'title_tag', 'h2' ),
        'title_size' => get_theme_mod( $pre . 'title_size' ),
        'title_weight' => get_theme_mod( $pre . 'title_weight' ),
        'title_text_transform' => get_theme_mod( $pre . 'title_text_transform' ),
        
        // excerpt
        'excerpt_show' => ( 'true' == get_theme_mod( $pre . 'show_excerpt', 'true' ) ),
        'excerpt_length' => get_theme_mod( $pre . 'excerpt_length', 22 ),
        'excerpt_size' => get_theme_mod( $pre . 'excerpt_size' ),
        'excerpt_color' => get_theme_mod( $pre . 'excerpt_color' ),
        'excerpt_more' => ( 'true' == get_theme_mod( $pre . 'excerpt_more', 'true' ) ),
        'excerpt_more_style' => get_theme_mod( $pre . 'excerpt_more_style' ),
        'excerpt_more_text' => get_theme_mod( $pre . 'excerpt_more_text' ),
        
        // date
        'date_show' => ( 'true' == get_theme_mod( $pre . 'show_date', 'true' ) ),
        
        // category
        'category_show' => ( 'true' == get_theme_mod( $pre . 'show_category', 'true' ) ),
        
        // author
        'author_show' => ( 'true' == get_theme_mod( $pre . 'show_author', 'false' ) ),
        'author_avatar_show' => ( 'true' == get_theme_mod( $pre . 'show_author_avatar', 'false' ) ),
        
        // view count
        'view_show' => ( 'true' == get_theme_mod( $pre . 'show_view', 'false' ) ),
        
        // comment link
        'comment_link_show' => ( 'true' == get_theme_mod( $pre . 'show_comment_link', 'false' ) ),
        
        // reading time
        'reading_time_show' => ( 'true' == get_theme_mod( $pre . 'show_reading_time', 'false' ) ),
        
        // list option
        'list_sep' => ( 'true' == get_theme_mod( $pre . 'list_sep', 'true' ) ),
        
        'thumbnail_width' => get_theme_mod( $pre . 'thumbnail_width' ),
        'list_mobile_layout' => get_theme_mod( $pre . 'list_mobile_layout', 'grid' ),
        'list_valign' => get_theme_mod( $pre . 'list_valign', 'top' ),
        
        'thumbnail_extra_class' => '',
    ];
    
    /**
     * custom params for custom loops
     */
    switch( $loop ) {
            
        case 'list' :
            
            $params[ 'thumbnail_position' ] = get_theme_mod( $pre . 'thumbnail_position', 'left' );
            break;
            
        case 'standard' :
            
            $params[ 'content_excerpt' ] = get_theme_mod( 'wi_blog_standard_content_excerpt', 'content' );
            $params[ 'thumbnail_type' ] = get_theme_mod( 'wi_blog_standard_thumbnail_type', 'simple' );
            $params[ 'header_align' ] = get_theme_mod( 'wi_blog_standard_header_align', 'left' );
            
            $params[ 'share_show' ] = ( 'true' == get_theme_mod( 'wi_blog_standard_show_share', 'true' ) );
            $params[ 'related_show' ] = ( 'true' == get_theme_mod( 'wi_blog_standard_show_related', 'true' ) );
            
            break;
            
        case 'newspaper' :
            
            $params[ 'content_excerpt' ] = get_theme_mod( 'wi_post_newspaper_content_excerpt', 'content' );
            $params[ 'thumbnail_type' ] = get_theme_mod( 'wi_post_newspaper_thumbnail_type', 'simple' );
            $params[ 'header_align' ] = get_theme_mod( 'wi_post_newspaper_header_align', 'left' );
            
            $params[ 'share_show' ] = ( 'true' == get_theme_mod( 'wi_post_newspaper_show_share', 'true' ) );
            $params[ 'related_show' ] = ( 'true' == get_theme_mod( 'wi_post_newspaper_show_related', 'true' ) );
            
            break;
            
        case 'vertical' :
            
            $params[ 'thumbnail_type' ] = get_theme_mod( 'wi_vertical_post_thumbnail_type', 'simple' );
            $params [ 'thumbnail_position' ] = get_theme_mod( 'wi_vertical_post_thumbnail_position', 'left' );
            
            // no hover yet
            $params[ 'thumbnail_hover' ] = '';
            
            break;
            
        case 'big' :
        case 'big-post' :
            
            $params[ 'content_excerpt' ] = get_theme_mod( 'wi_big_post_content_excerpt', 'excerpt' );
            
            // also other options that shouldn't be inherit from grid options
            $params[ 'date_show' ] = true;
            $params[ 'category_show' ] = true;
            $params[ 'author_show' ] = false;
            $params[ 'align' ] = 'left';
            $params[ 'excerpt_show' ] = true;
            $params[ 'excerpt_length' ] = -1;
            $params[ 'excerpt_more' ] = true;
            $params[ 'excerpt_size' ] = 'medium';
            $params[ 'title_size' ] = 'extra';
            
            // no hover yet
            $params[ 'thumbnail_hover' ] = '';
            
            break;
            
        case 'slider' :
            
            $params = array_merge( $params, [
                'effect' => get_theme_mod( 'wi_post_slider_effect', 'fade' ),
                'nav_style' => get_theme_mod( 'wi_post_slider_nav_style', 'text' ),
                'slider_size' => get_theme_mod( 'wi_post_slider_size', '1020x510' ),
                'title_background' => ( 'true' == get_theme_mod( 'wi_post_slider_title_background', 'false' ) ),
            ]);
            
            // also other options that shouldn't be inherit from grid options
            $params[ 'title_size' ] = 'large';
            $params[ 'excerpt_size' ] = 'normal';
            $params[ 'item_align' ] = 'left';
            
            break;
            
        case 'group-1' :
            
            $params[ 'big_post_position' ] = get_theme_mod( 'wi_post_group1_big_post_position', 'left' );
            $params[ 'big_post_ratio' ] = get_theme_mod( 'wi_post_group1_big_post_ratio', '2/3' );
            $params[ 'sep_border' ] = ( 'true' == get_theme_mod( 'wi_post_group1_sep_border', 'false' ) );
            $params[ 'sep_border_color' ] = get_theme_mod( 'wi_post_group1_sep_border_color' );
            
            // BIG POST
            $params[ 'big_post_components' ] = get_theme_mod( 'wi_post_group1_big_post_components', 'thumbnail,title,date,category,excerpt,excerpt_more' );
            $params[ 'big_post_align' ] = get_theme_mod( 'wi_post_group1_big_post_align', 'center' );
            $params[ 'big_post_item_template' ] = get_theme_mod( 'wi_post_group1_big_post_item_template', '2' );
            
            $params[ 'big_post_excerpt_length' ] = get_theme_mod( 'wi_post_group1_big_post_excerpt_length', 44 );    
            $params[ 'big_post_excerpt_more_text' ] = get_theme_mod( 'wi_post_group1_big_post_excerpt_more_text', '' );
            $params[ 'big_post_excerpt_more_style' ] = get_theme_mod( 'wi_post_group1_big_post_excerpt_more_style', 'btn' );
            
            // SMALL POSTS
            $params[ 'small_post_components' ] = get_theme_mod( 'wi_post_group1_small_post_components', 'thumbnail,title,date,excerpt' );
            $params[ 'small_post_item_template' ] = get_theme_mod( 'wi_post_group1_small_post_item_template', '2' );
            $params[ 'small_post_list_spacing' ] = get_theme_mod( 'wi_post_group1_small_post_list_spacing', 'normal' );
            
            break;
            
        case 'group-2' :
            
            $params[ 'columns_order' ] = get_theme_mod( 'wi_post_group2_columns_order', '1a-3-1b' );
            $params[ 'sep_border' ] = ( 'true' == get_theme_mod( 'wi_post_group2_sep_border', 'false' ) );
            $params[ 'sep_border_color' ] = get_theme_mod( 'wi_post_group2_sep_border_color' );
            
            // BIG POST
            $params[ 'big_post_components' ] = get_theme_mod( 'wi_post_group2_big_post_components', 'thumbnail,title,date,category,excerpt,excerpt_more' );
            $params[ 'big_post_align' ] = get_theme_mod( 'wi_post_group2_big_post_align', 'center' );
            $params[ 'big_post_item_template' ] = get_theme_mod( 'wi_post_group2_big_post_item_template', '2' );
            $params[ 'big_post_excerpt_length' ] = get_theme_mod( 'wi_post_group2_big_post_excerpt_length', '32' );
            $params[ 'big_post_excerpt_more_text' ] = get_theme_mod( 'wi_post_group2_big_post_excerpt_more_text', '' );
            $params[ 'big_post_excerpt_more_style' ] = get_theme_mod( 'wi_post_group2_big_post_excerpt_more_style', 'btn' );
            
            // MEDIUM POST
            $params[ 'medium_post_components' ] = get_theme_mod( 'wi_post_group2_medium_post_components', 'thumbnail,title,date,excerpt' );
            $params[ 'medium_post_item_template' ] = get_theme_mod( 'wi_post_group2_medium_post_item_template', '2' );
            $params[ 'medium_post_excerpt_length' ] = get_theme_mod( 'wi_post_group2_medium_post_excerpt_length', '40' );
            
            // SMALL POSTS
            $params[ 'small_post_components' ] = get_theme_mod( 'wi_post_group2_small_post_components', 'thumbnail,title,date' );
            $params[ 'small_post_item_template' ] = get_theme_mod( 'wi_post_group2_small_post_item_template', '2' );
            $params[ 'small_post_excerpt_length' ] = get_theme_mod( 'wi_post_group2_small_post_excerpt_length', '12' );
            
            break;    
            
            default :
            
            break;
            
    }
    
    return $params;
    
}
endif;

/**
 * parse the params and return all necessary keys
 * @since 4.3
 */
function fox43_parse_params( $params ) {
    
    /**
     * query args
     */
    $query_defaults = [
        
        'source',
        
        'number',
        
        'post_type',
        
        'orderby',
        'order',
        
        'categories',
        'exclude_categories',
        
        'tags',
        'exclude_tags',
        
        'format',
        
        'author',
        'authors',

        'include',
        'exclude',
        
        'featured',
        
        'custom_query',
        
        'unique_posts',
        
        'pagination',
        
    ];
        
    /**
     * layout args
     */
    $layout_defaults = [
        
        'layout',
        'column',
        'item_spacing',
        'color',
        'big_first_post' => true, // for masonry
        'list_spacing',
        
        'extra_class',
        
    ];
      
    /**
     * each item args
     */
    $item_defaults = [
        
        // general
        'align',
        'item_template',
        'live' => true,
        
        // thumbnail
        'thumbnail_show' => true,
        'thumbnail' => 'landscape',
        'thumbnail_custom',
        'thumbnail_placeholder' => true,
        'thumbnail_placeholder_id',
        'thumbnail_shape',
        'thumbnail_hover',
        'thumbnail_hover_logo',
        'thumbnail_hover_logo_width',
        'thumbnail_showing_effect',
        'thumbnail_format_indicator',
        'thumbnail_index',
        'thumbnail_view',
        'thumbnail_review_score',
        'thumbnail_extra_class',
        
        // title
        'title_show' => true,
        'title_tag',
        'title_size',
        'title_weight',
        'title_text_transform',
        
        // excerpt
        'excerpt_show' => true,
        'excerpt_length' => 22,
        'excerpt_size',
        'excerpt_color',
        'excerpt_more',
        'excerpt_more_style',
        'excerpt_more_text',
        
        // date
        'date_show' => true,
        
        // category
        'category_show' => true,
        
        // author
        'author_show',
        'author_avatar_show',
        
        // view count
        'view_show',
        
        // comment link
        'comment_link_show',
        
        // reading time
        'reading_time_show',
        
        // masonry option
        'big_first_post' => true,
        
        // list option
        'list_sep' => true,
        'list_sep_color',
        'thumbnail_position' => 'left',
        'thumbnail_width',
        'list_mobile_layout' => 'grid',
        'list_valign' => 'top',
        
        // slider options
        'effect' => 'slide',
        'nav_style' => 'text',
        'slider_size' => '1020x510',
        'title_background' => false,
        
        // vertical post options
        'thumbnail_type' => 'simple',
        
    ];
    
    $query_defaults = fox_array_fill( $query_defaults, '' );
    $layout_defaults = fox_array_fill( $layout_defaults, '' );
    $item_defaults = fox_array_fill( $item_defaults, '' );
    
    $all_defaults = array_merge( $query_defaults, $layout_defaults, $item_defaults );
    
    $params = wp_parse_args( $params, $all_defaults );
    
    return $params;
    
}

if ( ! function_exists( 'fox43_blog' ) ) :
/**
 * The main post block function
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_blog( $params = [] ) {
    
    $params = fox43_parse_params( $params );
    
    if ( isset( $params[ 'query_obj'] ) ) {
        $query = $params[ 'query_obj'];
    } else {
        if ( isset( $params[ 'query' ] ) && $params[ 'query' ] ) {
            $query = new WP_Query( $params[ 'query' ] );
        } else {
            $query = fox_query( $params );
        }
    }
    
    // fix v3 issue
    if ( 'big-post' == $params[ 'layout' ] ) {
        $params[ 'layout' ] = 'big';
    }
    
    $layout = $params[ 'layout' ];
    $params['loop'] = fox_get_loop_from_layout( $layout );
    $params[ 'column' ] = fox_get_column_from_layout( $layout );
    
    // no posts found
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    $container_class = [
        'blog-container',
        'blog-container-' . $params['loop']
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-' . $params['loop']
    ];
    
    if ( $params[ 'extra_class' ] ) {
        $class[] = $params[ 'extra_class' ];
    }
    
    /**
     * Grid + Masonry
     */
    if ( 'grid' == $params['loop'] || 'masonry' == $params['loop'] ) {
        
        $class[] = 'fox-grid';
        
        /**
         * column
         */
        if ( isset( $params['column'] ) && $params['column'] >= 1 ) {
            $class[] = 'column-' . $params['column'];
        }
        
        /**
         * item spacing
         */
        $item_spacing = $params[ 'item_spacing' ];
        if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'wide', 'wider' ] ) ) {
            $item_spacing = 'normal';
        }
        $class[] = 'spacing-' . $item_spacing;
        
    }
    
    /**
     * Masonry + Newspaper
     */
    if ( 'masonry' == $params['loop'] || 'newspaper' == $params['loop'] ) {
        $class[] = 'fox-masonry';
    }

    // big post featured
    if (  'masonry' == $params['loop'] && $params[ 'big_first_post' ] ) {
        $class[] = 'fox-masonry-featured-first';
    } 
    
    // list spacing
    if ( 'list' == $params['loop'] ) {
        
        $list_spacing = $params[ 'list_spacing' ];
        if ( ! in_array( $list_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) {
            $list_spacing = 'normal';
        }

        $class[] = 'v-spacing-' . $list_spacing;
        
    }
    
    /**
     * Standard
     */
    if ( 'standard' == $params['loop']) {
        
        // settings for standard layout
        $params[ 'thumbnail' ] = 'original';
        
    }
    
    // newspaper
    if ( 'newspaper' == $params['loop'] ) {
        $class[] = 'fox-grid';
        $class[] = 'column-2';
        $class[] = 'spacing-normal';
    }
    
    if ( 'newspaper' != $params['loop'] && 'standard' != $params['loop'] ) {
        // just for safety, haha
        $params[ 'content_excerpt' ] = 'excerpt';
    }
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $params[ 'color' ] );
    if ( $params[ 'color' ] ) {
        $class[] = 'blog-custom-color';
        $unique_id = uniqid( 'blog-' );
        $id_attr = ' id="' . esc_attr( $unique_id ) . '"';
        $css_str = '<style type="text/css">#' . $unique_id . '{color:' . esc_html( $color ). ';}</style>';
    }
    
    ?>

<?php echo $css_str; ?>

<div class="<?php echo esc_attr( join( ' ', $container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>"<?php echo $id_attr; ?>>
    
    <?php 
    $count = 0;
    while ( $query->have_posts() ) {
        $query->the_post();
        
        $count++;
        
        switch( $params['loop'] ) {
                
            case 'standard' :
                
                fox43_item_standard( $params, $count );
                
                break;
                
            case 'newspaper' :
                
                fox43_item_newspaper( $params, $count );
                
                break;    
                
            default :
                
                fox43_item( $params, $count );
                
                break;   
                
        }
        
        do_action( 'fox_after_render_post' );
    
    } // endwhile ?>
        
        <?php if ( 'masonry' == $params[ 'loop' ] || 'newspaper' == $params[ 'loop' ] ) echo '<div class="grid-sizer fox-grid-item"></div>'; ?>
        
    </div><!-- .fox-blog -->
    
    <?php if ( $params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .blog-container -->

<?php
    
    wp_reset_query();
    
}
endif;

if ( ! function_exists( 'fox43_item_standard' ) ) :
/**
 * Render standard post item
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_item_standard( $params = [], $count = 1 ) {
    
    if ( isset( $params[ 'skip_rendered' ] ) && $params[ 'skip_rendered' ] ) {
        global $rendered_articles;
        if ( in_array( get_the_ID() , $rendered_articles ) ) {
            return;
        }
    }
    
    $post_class = [ 'wi-post', 'post-item', 'post-standard' ];
    if ( isset( $params[ 'header_align' ] ) ) {
        if ( 'left' == $params[ 'header_align' ] || 'center' == $params[ 'header_align' ] || 'right' == $params[ 'header_align' ] ) {
            $post_class[] = 'post-header-align-' . $params[ 'header_align' ];
        }
    }

    // header class
    $header_class = [ 'post-header' ];
    if ( isset( $params[ 'header_align' ] ) && $params[ 'header_align' ] ) {
        $header_class[] = 'align-' . $params[ 'header_align' ];
    }

    // options customized only for standard blog
    $meta_params = $params;
    $meta_params[ 'date_fashion' ] = 'long';
    $meta_params[ 'extra_class' ] = [  ];

    // thumbnail args
    $thumbnail_params = $params;
    $thumbnail_params[ 'thumbnail_extra_class' ] .= ' post-thumbnail';
    $thumbnail_params[ 'thumbnail' ] = 'original';
    $thumbnail_params[ 'thumbnail_placeholder' ] = false;

    // excerpt args
    $excerpt_args = [
        'length' => $params[ 'excerpt_length' ],
        'more' => $params[ 'excerpt_more' ],
        'style' => $params[ 'excerpt_more_style' ],
        'text' => $params[ 'excerpt_more_text' ],
        'size' => $params[ 'excerpt_size' ],
        'color' => $params[ 'excerpt_color' ],
    ];
    
    /**
     * title, meta params
     */
    $body_params = $params;
    $body_params[ 'title_extra_class' ] = 'post-title';
    $body_params[ 'date_fashion' ] = 'long';
    $body_params[ 'meta_extra_class' ] = 'post-header-meta post-standard-meta';
    $body_params[ 'excerpt_show' ] = false;
    
    /**
     * drop cap
     */
    $drop_cap = get_post_meta( get_the_ID(), '_wi_blog_dropcap', true );
    if ( ! $drop_cap ) {
        $drop_cap = get_theme_mod( 'wi_blog_dropcap', 'false' );
    }
    if ( 'true' == $drop_cap ) {
        $post_class[] = 'enable-dropcap';
    } else {
        $post_class[] = 'disable-dropcap';
    }
    
    /**
     * column
     */
    $text_column = get_theme_mod( 'wi_blog_column_layout', 1 );
    if ( 2 == $text_column ) {
        $post_class[] = 'enable-2-columns';
    }
    
    /**
     * Order problem
     * @since 4.4
     */
    $thumbnail_header_order = $params[ 'thumbnail_header_order' ];
    if ( 'thumbnail' != $thumbnail_header_order ) {
        $thumbnail_header_order = 'header';
    }
    ob_start();
    ?>
    <header class="<?php echo esc_attr( join( ' ', $header_class ) ); ?>">
            
        <?php fox43_post_body( $body_params ); ?>
        <?php // fox43_live_indicator(); ?>

    </header><!-- .post-header -->
    <?php
    $header_html = ob_get_clean();
    
    ob_start();
    if ( $params[ 'thumbnail_show' ] ) {
        fox43_thumbnail( $thumbnail_params );
    }
    $thumbnail_html = ob_get_clean();
?>

<article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="post-sep"></div>
    
    <div class="post-body post-item-inner post-standard-inner">
        
        <?php
    
    if ( 'header' == $thumbnail_header_order ) {
        echo $header_html . $thumbnail_html;
    } else {
        echo $thumbnail_html . $header_html;
    } ?>
        
        <div class="post-content">
            
        <?php /* ---------      Content       --------- */ ?>
        <?php if ( 'excerpt' == $params[ 'content_excerpt' ] ) { ?>
            
            <div class="entry-excerpt">
                
                <?php fox_post_excerpt( $excerpt_args ); ?>

            </div><!-- .entry-excerpt -->
        
        <?php } else { // content ?>
        
            <div class="entry-content dropcap-content columnable-content" itemprop="text">

                <?php 
                      // .post-more class is just a legacy
                      the_content( '<span class="post-more">' . fox_word( 'more_link' ) . '</span>' );
                      fox_page_links();
                ?>

            </div><!-- .entry-content -->
        
        <?php } // content excerpt ?>
            
        </div><!-- .post-content -->
        
        <?php if ( $params[ 'share_show' ] ) fox_share(); ?>
        
        <?php if ( $params[ 'related_show' ] ) fox_blog_related(); ?>
        
    </div><!-- .post-body -->

</article><!-- .post-standard -->

<?php
    
}
endif;

if ( ! function_exists( 'fox43_item_newspaper' ) ) :
/**
 * Render newspaper post item
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_item_newspaper( $params = [] ) {
    
    if ( isset( $params[ 'skip_rendered' ] ) && $params[ 'skip_rendered' ] ) {
        global $rendered_articles;
        if ( in_array( get_the_ID() , $rendered_articles ) ) {
            return;
        }
    }
    
    // classes
    $post_class = [ 'wi-post', 'post-item', 'post-newspaper', 'fox-grid-item', 'fox-masonry-item' ];

    // header class
    $header_class = [ 'post-header', 'newspaper-header' ];
    if ( isset( $params[ 'header_align' ] ) && $params[ 'header_align' ] ) {
        $header_class[] = 'align-' . $params[ 'header_align' ];
    }

    // options customized only for standard blog
    $meta_params = $params;
    $meta_params[ 'date_fashion' ] = 'short';
    $meta_params[ 'extra_class' ] = [ 'newspaper-meta' ];

    // thumbnail args
    $thumbnail_params = $params;
    $thumbnail_params[ 'thumbnail_extra_class' ] .= ' post-thumbnail newspaper-thumbnail';
    $thumbnail_params[ 'thumbnail' ] = 'original';
    $thumbnail_params[ 'thumbnail_placeholder' ] = false;

    // excerpt args
    $excerpt_args = [
        'extra_class' => 'small-dropcap-content dropcap-content columnable-content columnable-content-small',
        'length' => -1,
        'more' => false,
    ];
    
    /**
     * drop cap
     */
    $drop_cap = get_post_meta( get_the_ID(), '_wi_blog_dropcap', true );
    if ( ! $drop_cap ) {
        $drop_cap = get_theme_mod( 'wi_blog_dropcap', 'false' );
    }
    if ( 'true' == $drop_cap ) {
        $post_class[] = 'enable-dropcap';
    } else {
        $post_class[] = 'disable-dropcap';
    }
    
    /**
     * column
     */
    $text_column = get_theme_mod( 'wi_blog_column_layout', 1 );
    if ( 2 == $text_column ) {
        $post_class[] = 'enable-2-columns';
    }
    
    ?>

    <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">

        <div class="post-sep"></div>

        <div class="post-body post-item-inner post-newspaper-inner masonry-animation-element">

            <header class="<?php echo esc_attr( join( ' ', $header_class ) ); ?>">

                <?php if ( $params[ 'title_show' ] ) { fox_post_title([ 'extra_class' => 'newspaper-title', 'size' => 'medium' ]); } ?>
                <?php fox_post_meta( $meta_params ); ?>
                
                <?php fox43_live_indicator(); ?>

            </header><!-- .post-header -->

            <?php /* ---------      Thumbnail       --------- */ ?>
            <?php if ( $params[ 'thumbnail_show' ] ) {

                fox43_thumbnail( $thumbnail_params );

            } // show thumbnail ?>

            <div class="post-content newspaper-content">

            <?php /* ---------      Content       --------- */ ?>
            <?php if ( 'excerpt' == $params[ 'content_excerpt' ] ) { ?>

                <div class="entry-excerpt">

                    <?php fox_post_excerpt( $excerpt_args ); ?>

                    <?php if ( $params[ 'excerpt_more' ] ) { ?>
                    <p class="p-readmore">
                        <a href="<?php the_permalink();?>" class="more-link">
                            <span class="post-more"><?php echo fox_word( 'read_more' ); ?></span>
                        </a>
                    </p><!-- .p-readmore -->
                    <?php } ?>

                </div><!-- .entry-excerpt -->

            <?php } else { // content ?>

                <div class="entry-content small-dropcap-content dropcap-content columnable-content columnable-content-small" itemprop="text">

                    <?php 
                          // .post-more class is just a legacy
                          the_content( '<span class="post-more">' . fox_word( 'more_link' ) . '</span>' );
                          fox_page_links();
                    ?>

                </div><!-- .entry-content -->

            <?php } // content excerpt ?>

            </div><!-- .post-content -->

            <?php if ( $params[ 'share_show' ] ) fox_share(); ?>

            <?php if ( $params[ 'related_show' ] ) fox_blog_related( 'newspaper' ); ?>

        </div><!-- .post-body -->

    </article><!-- .post-newspaper -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox43_item' ) ) :
/**
 * The main post block function
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_item( $params = [], $count = 1 ) {
    
    if ( isset( $params[ 'skip_rendered' ] ) && $params[ 'skip_rendered' ] ) {
        global $rendered_articles;
        if ( in_array( get_the_ID() , $rendered_articles ) ) {
            return;
        }
    }
    
    $post_class = [
        'wi-post',
        'post-item',
    ];
    
    if ( isset( $params['post_extra_class'] ) ) {
        $post_class[] = $params['post_extra_class' ];
    }
    
    // post body class
    $post_body_class = [ 'post-body', 'post-item-body' ];
    $post_body_class[] = $params['loop'] . '-body';
    $post_body_class[] = 'post-' . $params['loop'] . '-body';
    
    // post class
    $post_class[] = 'post-' . $params['loop'];
    if ( 'grid' == $params['loop'] || 'masonry' == $params['loop'] ) {
        $post_class[] = 'fox-grid-item';
        
        // align
        if ( isset( $params['align'] ) ) {
            $post_class[] = 'post-align-' . $params['align'];
        }
    }
    
    if ( 'masonry' == $params['loop'] || 'newspaper' == $params['loop'] ) {
        
        $post_class[] = 'fox-masonry-item';
        
    }
    
    /**
     * Masonry
     */
    if ( 'masonry' == $params['loop'] ) {
        
        if ( $params['column'] >=3 ) {
            
            $params[ 'thumbnail' ] = 'medium';
            
        } else {
            
            $params[ 'thumbnail' ] = 'large';
            
        }
 
        if ( 1 == $count && $params[ 'big_first_post' ] ) {
            $params[ 'thumbnail' ] = 'large';
            $post_class[] = 'masonry-featured-post';
        }
        
        // custom thumbnail is set to be false
        $params[ 'thumbnail_placeholder' ] = false;
        
        // disable thumbnail showing effect
        $params[ 'thumbnail_showing_effect' ] = false;
        
        // extra class
        $params[ 'thumbnail_extra_class' ] .= ' masonry-animation-element';
        
        $params[ 'excerpt_extra_class' ] = 'masonry-content dropcap-content small-dropcap-content';
        
        // set them to animation element
        $post_body_class[] = 'masonry-animation-element';
        
        /**
         * drop cap
         */
        $drop_cap = get_post_meta( get_the_ID(), '_wi_blog_dropcap', true );
        if ( ! $drop_cap ) {
            $drop_cap = get_theme_mod( 'wi_blog_dropcap', 'false' );
        }
        if ( 'true' == $drop_cap ) {
            $post_class[] = 'enable-dropcap';
        } else {
            $post_class[] = 'disable-dropcap';
        }
        
    }
    
    if ( 'list' == $params['loop'] || 'vertical' == $params['loop'] ) {
        
        // thumbnail position
        $thumbnail_position = isset( $params[ 'thumbnail_position' ] ) ? $params[ 'thumbnail_position' ] : '';
        if ( 'right' != $thumbnail_position ) $thumbnail_position = 'left';
        $post_class[] = 'post-thumbnail-align-' . $thumbnail_position;
        
    }
    
    // list align
    $sep_css = '';
    if ( 'list' == $params['loop'] ) {
        
        // extra class
        $params[ 'thumbnail_extra_class' ] .= ' list-thumbnail';
        
        // valign
        $list_valign = $params[ 'list_valign' ];
        if ( 'middle' != $list_valign && 'bottom' != $list_valign ) $list_valign = 'top';
        $post_class[] = 'post-valign-' . $list_valign;

        // list mobile layout
        if ( 'list' != $params[ 'list_mobile_layout' ] ) $params[ 'list_mobile_layout' ] = 'grid';
        $post_class[] = 'list-mobile-layout-' . $params[ 'list_mobile_layout' ];
         
        /*
        $thumbnail_width = trim( $params[ 'thumbnail_width' ] );
        if ( $thumbnail_width ) {
            if ( is_numeric( $thumbnail_width ) ) $thumbnail_width .= 'px';
            if ( $thumbnail_width ) {
                $params[ 'thumbnail_extra_css' ] = 'width:' . $thumbnail_width;
            }
        }
        */
        
        if ( $params[ 'list_sep_color' ] ) {
            $sep_css = ' style="border-color:' . esc_attr( $params[ 'list_sep_color' ] ) . '"';
        }
        
    }
    
    if ( 'vertical' == $params['loop'] ) {
        
        // hardcode
        $params[ 'title_size' ] = 'large';
        $params[ 'title_extra_class' ] = 'post-vertical-title';
        $params[ 'header_class' ] = 'post-vertical-header';
        $params[ 'excerpt_extra_class' ] = 'post-vertical-content';
        $params[ 'date_fashion' ] = 'short';

        $params[ 'thumbnail_placeholder' ] = false;
        $params[ 'thumbnail_extra_class' ] .= ' vertical-thumbnail';
        $params[ 'thumbnail_shape' ] = 'acute';
        $params[ 'thumbnail' ] = 'large';
        
    }
    
    ?>
    
    <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">
        
        <?php if ( isset( $params[ 'list_sep'] ) && $params[ 'list_sep'] && 'list' == $params['loop'] ) { ?>
        <div class="post-list-sep"<?php echo $sep_css; ?>></div>
        <?php } ?>
    
        <div class="post-item-inner <?php echo esc_attr( $params['loop'] ); ?>-inner post-<?php echo esc_attr( $params['loop'] ); ?>-inner">
            
            <?php if ( $params[ 'thumbnail_show' ] ) {
            
                fox43_thumbnail( $params, $count );
            
            } ?>

            <div class="<?php echo esc_attr( join( ' ', $post_body_class ) ); ?>">

                <?php fox43_post_body( $params ); ?>

            </div><!-- .post-item-body -->

        </div><!-- .post-item-inner -->

    </article><!-- .post-item -->

    <?php
    
}
endif;

if ( ! function_exists( 'fox43_blog_slider' ) ) :
/**
 * The post slider
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_blog_slider( $params ) {
    
    $params = fox43_parse_params( $params );
    
    if ( isset( $params[ 'query' ] ) && $params[ 'query' ] ) {
        $query = new WP_Query( $params[ 'query' ] );
    } else {
        $query = fox_query( $params );
    }
    
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    $class = [
        'wi-flexslider',
        'fox-flexslider',
        'blog-slider',
    ];
    
    $slider_options = [
        'slideshow' => true,
        'animationSpeed' => 1000,
        'slideshowSpeed' =>	5000,
        'easing' => 'easeOutCubic',
    ];
    
    // effect
    if ( 'fade' != $params[ 'effect' ] ) $params[ 'effect' ] = 'slide';
    $slider_options[ 'animation' ] = $params[ 'effect' ];
    
    // nav style
    if ( 'arrow' != $params[ 'nav_style' ] ) $params[ 'nav_style' ] = 'text';
    $class[] = 'style--slider-nav' . $params[ 'nav_style' ];
    
    if ( 'arrow' == $params[ 'nav_style' ] ) {
        $slider_options[ 'prevText' ] = '<i class="fa fa-angle-left"></i>';
        $slider_options[ 'nextText' ] = '<i class="fa fa-angle-right"></i>';
    } else {
        $slider_options[ 'prevText' ] = '<i class="fa fa-chevron-left"></i>' . '<span>' . fox_word( 'previous' ) . '</span>';
        $slider_options[ 'nextText' ] = '<span>' . fox_word( 'next' ) . '</span>' . '<i class="fa fa-chevron-right"></i>';
    }
    
    /**
     * adjust params a bit
     */
    if ( ! isset( $params[ 'title_size'] ) || ! $params[ 'title_size' ] ) {
        $params[ 'title_size' ] = 'large';
    }
    if ( ! isset( $params[ 'align' ] ) || ! $params[ 'align' ] ) {
        $params[ 'align' ] = 'left';
    }
    
    $thumbnail_params = $params;
    $thumbnail_params[ 'thumbnail_extra_class' ] .= ' slider-thumbnail';
    $thumbnail_params[ 'thumbnail' ] = 'custom';
    $thumbnail_params[ 'thumbnail_custom' ] = $params[ 'slider_size' ];
    $thumbnail_params[ 'thumbnail_format_indicator' ] = false;
    $thumbnail_params[ 'thumbnail_index' ] = false;
    $thumbnail_params[ 'thumbnail_review_score' ] = false;
    $thumbnail_params[ 'thumbnail_view' ] = false;
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $slider_options ); ?>'>
            
    <div class="flexslider">
        
        <ul class="slides">
            
            <?php while( $query->have_posts()): $query->the_post(); ?>
            
            <li class="slide">
                
                <?php
                $post_class = [ 'post-item', 'post-slider' ];

                // align
                if ( isset( $params[ 'align'] ) ) {
                    $post_class[] = 'post-slide-align-' . $params[ 'align'];
                }
    
                if ( isset( $params[ 'title_background' ] ) && $params[ 'title_background' ] ) {
                    $post_class[] = 'style--title-has-background';
                }
    
                $meta_params = $params;
                $meta_params[ 'extra_class' ] = 'slider-meta';

                ?>
                <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">

                    <?php fox43_thumbnail( $thumbnail_params ); ?>
                    
                    <div class="slider-body">

                        <div class="slider-table">

                            <div class="slider-cell">

                                <div class="post-content">

                                    <?php if ( $params[ 'title_show' ] ) { ?>

                                    <div class="slider-header">

                                        <?php fox_post_title([
                                            'extra_class' => 'slider-title',
                                            'tag' => $params[ 'title_tag' ],
                                            'size' => $params[ 'title_size' ],
                                            'weight' => $params[ 'title_weight' ],
                                            'text_transform' => $params[ 'title_text_transform' ],
                                        ]); ?>

                                    </div><!-- .slider-header -->

                                    <?php } ?>

                                    <?php if ( $params[ 'excerpt_show' ] ) { ?>

                                    <div class="slider-excerpt">

                                        <?php fox_post_meta( $meta_params ); ?>

                                        <?php fox_post_excerpt([
                                                'extra_class' => 'slider-excerpt-text',
                                                'exclude_class' => [ 'entry-excerpt' ],
                                                'length' => $params[ 'excerpt_length' ], 
                                                'more' => $params[ 'excerpt_more' ] ]); ?>

                                    </div><!-- .slider-excerpt -->

                                    <?php } ?>

                                </div><!-- .post-content -->

                            </div><!-- .slider-cell -->

                        </div><!-- .slider-table -->

                    </div><!-- .slider-body -->

                </article><!-- .post-slider -->
                
                <?php do_action( 'fox_after_render_post' ); // since 4.0 ?>
                
            </li><!-- .slide -->
            
            <?php endwhile;?>
            
        </ul>
        
    </div><!-- .flexslider -->
    
    <?php echo fox_loading_element(); ?>

</div><!-- .wi-flexslider -->

<?php
    
    wp_reset_query();
    
}
endif;

if ( ! function_exists( 'fox43_blog_big' ) ) :
/**
 * The post big
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_blog_big( $params ) {
    
    $params = fox43_parse_params( $params );
    
    if ( isset( $params[ 'query' ] ) && $params[ 'query' ] ) {
        $query = new WP_Query( $params[ 'query' ] );
    } else {
        $query = fox_query( $params );
    }
    
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    $post_css = $meta_css = [];
    $post_class = [
        'wi-post',
        'post-item',
        'post-big',
        'has-thumbnail', // has-thumbnail is a legacy
    ];

    if ( isset( $params[ 'align' ] ) ) {
        if ( 'left' == $params[ 'align' ] || 'center' == $params[ 'align' ] || 'right' == $params[ 'align' ] ) {
            $post_class[] = 'post-align-' . $params[ 'align' ];
        }
    }

    // custom text color
    if ( isset( $params[ 'color' ] ) && $params[ 'color' ] ) {
        $post_class[] = 'post-custom-color';
        $post_css[] = 'color:' . $params[ 'color' ];
    }

    $post_css = join( ';', $post_css );
    if ( ! empty( $post_css ) ) {
        $post_css = ' style="' . esc_attr( $post_css ). '"';
    }

    if ( isset( $params[ 'meta_background' ] ) && $params[ 'meta_background' ] ) {
        $post_class[] = 'post-has-meta-custom-bg';
        $meta_css[] = 'background:' . $params[ 'meta_background' ];
    }
    $meta_css = join( ';', $meta_css );
    if ( ! empty( $meta_css ) ) {
        $meta_css = ' style="' . esc_attr( $meta_css ). '"';
    }

    $date_format = apply_filters( 'fox_big_date_format', 'd.m.Y' );
    
    $thumbnail_params = $params;
    $thumbnail_params[ 'thumbnail_index' ] = false;
    $thumbnail_params[ 'thumbnail_view' ] = false;
    $thumbnail_params[ 'thumbnail_review_score' ] = false;
    $thumbnail_params[ 'thumbnail_format_indicator' ] = false;
    $thumbnail_params[ 'thumbnail' ] = 'original';
    $thumbnail_params[ 'thumbnail_placeholder' ] = false;
    $thumbnail_params[ 'thumbnail_extra_class' ] .= ' post-thumbnail post-big-thumbnail';
    
    /**
     * components
     */
    $show_category = $params[ 'category_show' ];
    $show_date = $params[ 'date_show' ];
    $show_author = $params[ 'author_show' ];
    $show_author_avatar = $params[ 'author_avatar_show' ];
    $show_view = $params[ 'view_show' ];
    $show_reading_time = $params[ 'reading_time_show' ];
    $show_comment_link = $params[ 'comment_link_show' ];
    
    while( $query->have_posts() ) : $query->the_post();
    
    ?>

    <article <?php post_class( $post_class ); ?>  <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
        
        <?php 
    if ( $params[ 'thumbnail_show' ] ) {
        fox43_thumbnail( $thumbnail_params );
    } ?>

        <div class="big-body container">

            <header class="big-header post-item-header">
                
                <?php if ( $show_category || $show_date || $show_author || $show_view || $show_reading_time || $show_comment_link ) { ?>

                <div class="post-item-meta big-meta"<?php echo $meta_css; ?>>

                    <?php if ( $show_category ) fox_post_categories([ 'extra_class' => 'big-cats' ]); ?>
                    <?php if ( $show_date ) fox_post_date([ 'extra_class' => 'big-date', 'format' => $date_format, 'style' => 'standard', 'fashion' => 'short' ]); ?>
                    <?php if ( $show_author ) fox_post_author( $show_author_avatar ); ?>
                    <?php if ( $show_view ) fox_post_view(); ?>
                    <?php if ( $show_reading_time ) fox_reading_time(); ?>
                    <?php if ( $show_comment_link ) fox_comment_link(); ?>

                </div><!-- .big-meta -->

                <?php } ?>

                <?php 
                    if ( $params[ 'title_show' ] ) {
                        fox_post_title([
                            'extra_class' => 'big-title', 
                            'size' => $params[ 'title_size' ] 
                        ]);
                        
                        fox43_live_indicator();
                        
                    } ?>

            </header><!-- .big-header -->

            <?php if ( $params[ 'excerpt_show' ] ) { ?>

                <?php if ( isset( $params[ 'content_excerpt' ] ) && $params[ 'content_excerpt' ] == 'content' ) { ?>
            
                <div class="big-content" itemprop="text">

                    <?php the_content( '<span class="big-more">'. fox_word( 'more_link' ) .'</span>' ); ?>

                </div>
                <?php } else { ?>
                <div class="big-content" itemprop="text">

                    <?php fox_post_excerpt([ 'length' => $params[ 'excerpt_length' ], 'more' => false, 'size' => $params[ 'excerpt_size' ] ]); ?>

                    <?php if ( $params[ 'excerpt_more' ] ) { ?>

                    <a href="<?php the_permalink(); ?>" class="more-link readmore minimal-link">
                        
                        <span class="big-more"><?php echo fox_word( 'more_link' ); ?></span>
                        
                    </a>

                    <?php } ?>

                </div>
                <?php } ?>

            <?php } // show excerpt ?>

        </div><!-- .big-body -->

    </article><!-- .post-big -->
    <?php
    
    do_action( 'fox_after_render_post' );
    
    endwhile;
    
    wp_reset_query();
}
endif;

if ( ! function_exists( 'fox43_blog_group1' ) ) :
/**
 * Post Group 1
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_blog_group1( $params ) {
    
    $params = fox43_parse_params( $params );
    
    if ( isset( $params[ 'query_obj'] ) ) {
        $query = $params[ 'query_obj'];
    } else {
        if ( isset( $params[ 'query' ] ) && $params[ 'query' ] ) {
            $query = new WP_Query( $params[ 'query' ] );
        } else {
            $query = fox_query( $query_params );
        }
    }
    
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    $container_class = [
        'blog-container',
        'blog-container-group',
        'blog-container-group-1'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-group',
        'blog-group-1',
        'post-group-row',
        
        // legacy
        'wi-newsblock',
        'newsblock-1',
    ];
    
    /**
     * more params, just in big post
     */
    $params = wp_parse_args( $params, [
        'big_post_position' => 'left',
        'big_post_ratio' => '2/3',
        'sep_border' => false,
        'sep_border_color' => '',
        
        // big post
        'big_post_components' => 'thumbnail,title,date,category,excerpt,excerpt_more',
        'big_post_align' => 'center',
        'big_post_item_template' => '2',
        'big_post_excerpt_more_text' => '',
        'big_post_excerpt_length' => 44,
        'big_post_excerpt_more_style' => 'btn',
        
        // small posts
        'small_post_components' => 'thumbnail,title,date,excerpt',
        'small_post_item_template' => '2',
        'small_post_list_spacing' => 'normal',
        
    ] );
    
    // big post position
    if ( 'right' != $params[ 'big_post_position' ] ) {
        $params[ 'big_post_position' ] = 'left';
    }
    $class[] = 'big-post-' . $params[ 'big_post_position' ];
    
    // big_post_ratio
    if ( '3/4' != $params[ 'big_post_ratio' ] ) $params[ 'big_post_ratio' ] = '2/3';
    $class[] = 'big-post-ratio-' . str_replace( '/', '-', $params[ 'big_post_ratio' ] );
    
    // sep border
    $sep_border_css = [];
    if ( $params[ 'sep_border' ] ) {
        $class[] = 'has-border';
    }
    if ( $params[ 'sep_border_color' ] ) {
        $sep_border_css[] = 'color:' . $params[ 'sep_border_color' ];
    }
    $sep_border_css = join( ';', $sep_border_css );
    if ( ! empty( $sep_border_css ) ) {
        $sep_border_css = ' style="' . esc_attr( $sep_border_css ) . '"';
    }
    
    // vertical spacing
    $small_post_list_spacing = $params[ 'small_post_list_spacing' ];
    if ( ! in_array( $small_post_list_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) {
        $small_post_list_spacing = 'normal';
    }

    $class[] = 'v-spacing-' . $small_post_list_spacing;
    
    $count = 0; 
?>

<div class="<?php echo esc_attr( join( ' ', $container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <?php while ( $query->have_posts() ) : $query->the_post(); $count++; $big_post = ( 1 == $count ); ?>
        
        <?php
    
            /* BIG POST
            -------------------- */
            if ( $big_post ) {
    
                $big_params = $params;
                $big_params[ 'post_extra_class' ] = 'article-big';
                $big_params[ 'thumbnail_placeholder' ] = false;
                $big_params[ 'loop' ] = 'grid';
                $big_params[ 'item_template' ] = $params[ 'big_post_item_template' ];
                $big_params[ 'title_size' ] = 'medium';
                $big_params[ 'align' ] = $params[ 'big_post_align' ];
                $big_params[ 'thumbnail' ] = 'thumbnail-large'; // original size
                $big_params[ 'thumbnail_index' ] = false;
                $big_params[ 'excerpt_length' ] = $params[ 'big_post_excerpt_length' ];
                $big_params[ 'excerpt_more_style' ] = $params[ 'big_post_excerpt_more_style' ];
                $big_params[ 'excerpt_more_text' ] = $params[ 'big_post_excerpt_more_text' ];
                
                // components
                $coms = explode( ',', $params[ 'big_post_components' ] );
                $coms = array_map( 'trim', $coms );
                $possible_components = [
                    'thumbnail', 'title', 'date', 'category', 'author', 'author_avatar', 'excerpt', 'excerpt_more', 'reading_time', 'comment_link', 'view'
                ];
                foreach ( $possible_components as $com ) {
                    $big_params[ $com . '_show' ] = in_array( $com, $coms );
                }
                $big_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
    
        ?>
        
        <div class="post-group-col post-group-col-big article-big-wrapper">
            
            <?php fox43_item( $big_params, $count ); ?>
            
        </div><!-- .post-group-col -->
        
        <div class="post-group-col post-group-col-small article-small-wrapper">
        
        <?php
    
            /* SMALL POST
            -------------------- */
            } else { // small posts
                
                $small_params = $params;
                $small_params[ 'loop' ] = 'list';
                $small_params[ 'live' ] = false;
                $small_params[ 'post_extra_class' ] = 'article-small-list';
                
                $small_params[ 'item_template' ] = $params[ 'small_post_item_template' ];
                
                $small_params[ 'thumbnail' ] = 'landscape';
                $small_params[ 'thumbnail_type' ] = 'simple';
                $small_params[ 'thumbnail_position' ] = 'right';
                $small_params[ 'thumbnail_placeholder' ] = false;
                $small_params[ 'thumbnail_index' ] = false;
                $small_params[ 'thumbnail_review_score' ] = false;
                $small_params[ 'thumbnail_hover' ] = '';
                
                $small_params[ 'title_size' ] = 'small';
                
                $small_params[ 'excerpt_size' ] = 'small';
                $small_params[ 'excerpt_more_style' ] = 'simple';
                $small_params[ 'excerpt_length' ] = 12;
                
                $small_params[ 'list_mobile_layout' ] = 'list';
                $small_params[ 'list_sep' ] = $params[ 'sep_border' ];
                
                // components
                $coms = explode( ',', $params[ 'small_post_components' ] );
                $coms = array_map( 'trim', $coms );
                $possible_components = [
                    'thumbnail', 'title', 'date', 'category', 'author', 'author_avatar', 'excerpt', 'excerpt_more', 'reading_time', 'comment_link', 'view'
                ];
                foreach ( $possible_components as $com ) {
                    $small_params[ $com . '_show' ] = in_array( $com, $coms );
                }
                $small_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
                
                fox43_item( $small_params, $count );
        
            } // big or small post ?>
            
            <?php 
            do_action( 'fox_after_render_post' );
            endwhile; ?>
            
        </div><!-- .article-small-wrapper -->
        
        <?php if ( $params[ 'sep_border' ] ) { ?>
        <div class="sep-border"<?php echo $sep_border_css; ?>></div>
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->

<?php
    
}
endif;

if ( ! function_exists( 'fox43_blog_group2' ) ) :
/**
 * Post Group 2
 *
 * @since 4.3
 *
 * $params: list of params
 */
function fox43_blog_group2( $params ) {
    
    $params = fox43_parse_params( $params );
    
    if ( isset( $params[ 'query_obj'] ) ) {
        $query = $params[ 'query_obj'];
    } else {
        if ( isset( $params[ 'query' ] ) && $params[ 'query' ] ) {
            $query = new WP_Query( $params[ 'query' ] );
        } else {
            $query = fox_query( $query_params );
        }
    }
    
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    /**
     * extra params for this
     */
    $params = wp_parse_args( $params, [
        'columns_order' => '1a-3-1b',
        'sep_border' => false,
        'sep_border_color' => '',
        
        'big_post_item_template' => 2,
        'big_post_align' => 'center',
        'big_post_components' => 'thumbnail,title,date,category,excerpt,excerpt_more',
        'big_post_excerpt_length' => 32,
        'big_post_excerpt_more_text' => '',
        'big_post_excerpt_more_style' => 'btn',
        
        'medium_post_item_template' => 2,
        'medium_post_excerpt_length' => 40,
        'medium_post_components' => 'thumbnail,title,date,excerpt,excerpt_more',
        
        'small_post_item_template' => 2,
        'small_post_excerpt_length' => 12,
        'small_post_components' => 'thumbnail,title,date',
    ]);
    
    $container_class = [
        'blog-container',
        'blog-container-group',
        'blog-container-group-2'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-group',
        'blog-group-2',
        'post-group-row',
        
        // legacy
        'newsblock-2',
    ];
    
    // big post position
    $class[] = 'post-group-row-' . $params[ 'columns_order' ];
    
    $explode = explode( '-', $params[ 'columns_order' ] );
    $big_order = 1 + array_search( '1a', $explode );
    $small_order = 1 + array_search( '3', $explode );
    $tall_order = 1 + array_search( '1b', $explode );
    
    $class[] = 'big-order-' . $big_order;
    $class[] = 'small-order-' . $small_order;
    $class[] = 'tall-order-' . $tall_order;
    
    // sep border
    $sep_border_css = [];
    if ( $params[ 'sep_border' ] ) {
        $class[] = 'has-border';
    }
    if ( $params[ 'sep_border_color' ] ) {
        $sep_border_css[] = 'color:' . $params[ 'sep_border_color' ];
    }
    $sep_border_css = join( ';', $sep_border_css );
    if ( ! empty( $sep_border_css ) ) {
        $sep_border_css = ' style="' . esc_attr( $sep_border_css ) . '"';
    }
    
    $count = 0;
?>

<div class="<?php echo esc_attr( join( ' ', $container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <?php while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
    

        <?php 
            /* BIG POST
            -------------------- */
            if ( 1 == $count ) {
    
                $big_params = $params;
                
                $big_params[ 'loop' ] = 'grid';
                $big_params[ 'thumbnail_placeholder' ] = false;
                $big_params[ 'post_extra_class' ] = 'article-big';
                
                $big_params[ 'item_template' ] = $params[ 'big_post_item_template' ];
                $big_params[ 'title_size' ] = 'medium';
                $big_params[ 'align' ] = $params[ 'big_post_align' ];
                $big_params[ 'thumbnail' ] = 'thumbnail-large'; // original size
                $big_params[ 'thumbnail_index' ] = false;
                $big_params[ 'excerpt_length' ] = $params[ 'big_post_excerpt_length' ];
                $big_params[ 'excerpt_more_style' ] = $params[ 'big_post_excerpt_more_style' ];
                $big_params[ 'excerpt_more_text' ] = $params[ 'big_post_excerpt_more_text' ];
                
                // components
                $coms = explode( ',', $params[ 'big_post_components' ] );
                $coms = array_map( 'trim', $coms );
                $possible_components = [
                    'thumbnail', 'title', 'date', 'category', 'author', 'author_avatar', 'excerpt', 'excerpt_more', 'reading_time', 'comment_link', 'view'
                ];
                foreach ( $possible_components as $com ) {
                    $big_params[ $com . '_show' ] = in_array( $com, $coms );
                }
                $big_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
    
        ?>
        
        <div class="post-group-col post-group-col-big article-col-big">
            
            <?php fox43_item( $big_params ); ?>
            
        </div><!-- .post-group-col -->
        
        <?php
    
    /* TALL POST / OR WE CALL MEDIUM POST
    -------------------- */
            } elseif ( 2 == $count ) {
    
                $medium_params = $params;
                $medium_params[ 'thumbnail_placeholder' ] = false;
                
                $medium_params[ 'loop' ] = 'grid';
                $medium_params[ 'post_extra_class' ] = 'article-tall article-medium';
                
                $medium_params[ 'item_template' ] = $params[ 'medium_post_item_template' ];
                $medium_params[ 'title_size' ] = 'normal';
                $medium_params[ 'excerpt_length' ] = $params[ 'medium_post_excerpt_length' ];;
                $medium_params[ 'align' ] = 'left';
                $medium_params[ 'thumbnail' ] = 'medium';
                $medium_params[ 'thumbnail_index' ] = false;
                $medium_params[ 'thumbnail_review_score' ] = false;
                $medium_params[ 'excerpt_more_style' ] = 'simple';
                
                // components
                $coms = explode( ',', $params[ 'medium_post_components' ] );
                $coms = array_map( 'trim', $coms );
                $possible_components = [
                    'thumbnail', 'title', 'date', 'category', 'author', 'author_avatar', 'excerpt', 'excerpt_more', 'reading_time', 'comment_link', 'view'
                ];
                foreach ( $possible_components as $com ) {
                    $medium_params[ $com . '_show' ] = in_array( $com, $coms );
                }
                $medium_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
        ?>
        
        <div class="post-group-col post-group-col-tall article-col-tall">
    
            <?php fox43_item( $medium_params ); ?>
            
        </div><!-- .post-group-col-tall -->
        
        <div class="post-group-col post-group-col-small article-col-small">
    
    <?php /* 3 POSTS
    -------------------- */
            } else { // small posts 
    
                $small_params = $params;
                $small_params[ 'thumbnail_placeholder' ] = false;
                
                $small_params[ 'loop' ] = 'grid';
                $small_params[ 'post_extra_class' ] = 'article-small article-small-grid';
                
                $small_params[ 'item_template' ] = $params[ 'small_post_item_template' ];
                $small_params[ 'title_size' ] = 'small';
                $small_params[ 'excerpt_length' ] = $params[ 'small_post_excerpt_length' ];
                $small_params[ 'align' ] = 'left';
                $small_params[ 'thumbnail' ] = 'landscape';
                $small_params[ 'thumbnail_index' ] = false;
                $small_params[ 'excerpt_more_style' ] = 'simple';
                $small_params[ 'excerpt_size' ] = 'small';
                $small_params[ 'thumbnail_review_score' ] = false;
                
                // components
                $coms = explode( ',', $params[ 'small_post_components' ] );
                $coms = array_map( 'trim', $coms );
                $possible_components = [
                    'thumbnail', 'title', 'date', 'category', 'author', 'author_avatar', 'excerpt', 'excerpt_more', 'reading_time', 'comment_link', 'view'
                ];
                foreach ( $possible_components as $com ) {
                    $small_params[ $com . '_show' ] = in_array( $com, $coms );
                }
                $small_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
            
            ?>
            
            <?php fox43_item( $small_params ); ?>
        
            <?php } // big or small post ?>
            
            <?php 
            do_action( 'fox_after_render_post' );
            endwhile; // have_posts ?>
        
            <?php 
        /**
         * case more than 2 posts
         */
        if ( $count >=2 ) { ?>
            
        </div><!-- .post-group-col-small -->
        
        <?php } 
    /**
     * fallback for case there's only 1 post
     */
    else { ?>
        
        <div class="post-group-col post-group-col-tall article-col-tall">
        </div>
        <div class="post-group-col post-group-col-small article-col-small">
        </div>
        
        <?php } ?>
        
        <?php if ( $params[ 'sep_border' ] ) { ?>
        
        <div class="sep-border line1"<?php echo $sep_border_css; ?>></div>
        <div class="sep-border line2"<?php echo $sep_border_css; ?>></div>
        
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->
<?php
    
}
endif;