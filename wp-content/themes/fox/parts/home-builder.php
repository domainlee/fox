<?php
// this hook since 4.0
$show = true;

if ( 'false' == get_theme_mod( 'wi_builder_paged', 'true' ) ) {
    if ( is_front_page() ) {
        if ( get_query_var( 'paged' ) ) { $show = false; }
    } else {
        if ( get_query_var( 'page' ) ) { $show = false; }
    }
}

$show = apply_filters( 'wi_display_homepage_builder', $show );

$loaded_main_stream = false;

$section_info = fox_builder_section_info();
$sections_order_without_main = $section_info[ 'sections_order_without_main' ];

if ( ! $show ) $sections_order_without_main = [];

// case it's the first section
if ( ! $section_info[ 'main_after' ] ) {
    
    if ( ! $loaded_main_stream ) {
        get_template_part( 'parts/main-stream' );
        $loaded_main_stream = true;
    }
    
}

/* Start The For Loop
-------------------------------------------- */
foreach ( $sections_order_without_main as $key => $i ) :

$prefix = "bf_{$i}_";
$source = get_theme_mod( "{$prefix}cat" );

$params = [];
$layout = $loop = '';

/* ------------------------ QUERY ------------------------ */
if ( 'shortcode' == $source ) {
        
    $shortcode = trim( get_theme_mod( "{$prefix}shortcode" ) );
    if ( ! $shortcode ) {
        continue;
    }
    
} elseif ( 'sidebar' == $source ) { // since 4.4
    
    $sidebar_html = '';
    $sidebar = get_theme_mod( "{$prefix}main_sidebar" );
    if ( ! $sidebar ) {
        
        $sidebar_html = fox_err( 'Please choose a sidebar to display' );
        
    } else {
        
        if ( ! is_active_sidebar( $sidebar ) ) {
            
            $sidebar_html = fox_err( 'Your sidebar is currently empty. Please go to <strong>Appearance > Widgets</strong> to drop widgets there!' );
            
        } else {
            
            ob_start();
            
            $sidebar_cl = [ 'main-section-sidebar' ];
            
            $sidebar_layout = get_theme_mod( "{$prefix}sidebar_layout", '3' );
            if ( ! in_array( $sidebar_layout, [ '1', '2', '3', '4' ] ) ) {
                $sidebar_layout = '3';
            }
            $sidebar_cl[] = 'main-section-sidebar-' . $sidebar_layout;
            
            echo '<div class="' . esc_attr( join( ' ', $sidebar_cl ) ) . '"><div class="section-sidebar-inner">';
            
            dynamic_sidebar( $sidebar );
            
            echo '</div></div>';
            
            $sidebar_html = ob_get_clean();
            
        }
        
        
    }
    
} else {

    // custom query, since 4.3
    $custom_query = trim( get_theme_mod( "{$prefix}custom_query" ) );
    if ( $custom_query ) {

        $args = $custom_query;

    } else {
        
        if ( ! $source || 'none' == $source ) continue;

        /**
         * old cat value before v3.0
         */
        if ( is_numeric( $source ) ) {
            $cat_term = get_term( $source, 'category' );
            if ( ! $cat_term ) continue;
            $source = 'cat_' . $cat_term->slug;
        }

        // query args
        $args = [
            'ignore_sticky_posts'   =>  true,
            'no_found_rows' => true,
        ];

        /**
         * Category
         */
        if ( $source == 'featured' ) {

            $args[ 'featured' ] = true;

        } elseif ( $source == 'sticky' ) {

            $sticky = get_option( 'sticky_posts' );
            if ( ! empty($sticky) ) {
                $args[ 'post__in' ] = $sticky;
                // $args[ 'order' ] = 'ASC';
                // $args[ 'orderby' ] = 'post__in';
            } else {
                continue;
            }

            $args[ 'post_type' ] = 'any';

        } elseif ( $source == 'video' || $source == 'gallery' || $source == 'audio' || $source == 'link' ) {

            $args[ 'tax_query' ] = array(
                array(
                    'taxonomy' => 'post_format',
                    'field'    => 'slug',
                    'terms'    => array( 'post-format-' . $source ),
                ),
            );

        } elseif ( 'post_type_' === substr( $source, 0, 10 ) ) {

            $post_type = substr( $source, 10 );
            $args[ 'post_type' ] = $post_type;

            /**
             * custom taxonomy
             * @since 4.3
             */
            $taxes = get_object_taxonomies( $post_type );
            $tax_query = [];

            foreach ( $taxes as $tax ) {

                $terms = trim( get_theme_mod( "{$prefix}tax_{$tax}" ) );
                if ( ! $terms ) continue;
                $terms = explode( ',', $terms );
                $terms = array_map( 'trim', $terms );
                $tax_query[] = [
                    'taxonomy' => $tax,
                    'field'    => 'name',
                    'terms'    => $terms,
                ];

            }
            if ( ! empty( $tax_query ) ) {
                $args[ 'tax_query' ] = $tax_query;
            }

        } elseif ( $source != 'all' ) {

            $source = str_replace( 'cat_', '', $source );
            $args[ 'category_name' ] = $source;

        }

        // extra parameters are only needed for non-sticky posts
        if ( $source != 'sticky' ) {

            // only count cat include & exclude for normal posts
            if ( 'post_type_' !== substr( $source, 0, 10 ) ) {

                /**
                 * include categories
                 * since 4.3
                 */
                $cat_include = get_theme_mod( "{$prefix}cat_include" );
                if ( is_string( $cat_include ) ) {
                    $cat_include = explode( ',', $cat_include );
                }
                if ( $cat_include ) {
                    $collect_cat_ids = [];
                    foreach( $cat_include as $catslug ) {
                        $catslug = trim( $catslug );
                        $cat_term = get_term_by( 'slug', $catslug, 'category' );
                        if ( ! $cat_term ) continue;
                        $collect_cat_ids[] = $cat_term->term_id;
                    }
                    if ( ! empty( $collect_cat_ids ) ) {
                        $args[ 'category__in' ] = $collect_cat_ids;
                    }
                }

                /**
                 * exclude categories
                 * since 4.3
                 */
                $cat_exclude = get_theme_mod( "{$prefix}cat_exclude" );
                if ( is_string( $cat_exclude ) ) {
                    $cat_exclude = explode( ',', $cat_exclude );
                }
                if ( $cat_exclude ) {
                    $collect_cat_ids = [];
                    foreach( $cat_exclude as $catslug ) {
                        $catslug = trim( $catslug );
                        $cat_term = get_term_by( 'slug', $catslug, 'category' );
                        if ( ! $cat_term ) continue;
                        $collect_cat_ids[] = $cat_term->term_id;
                    }
                    if ( ! empty( $collect_cat_ids ) ) {
                        $args[ 'category__not_in' ] = $collect_cat_ids;
                    }
                }

            }

            /**
             * authors
             * since 4.3
             */
            $authors = get_theme_mod( "{$prefix}authors" );
            if ( is_string( $authors ) ) {
                $authors = explode( ',', $authors );
            }
            if ( $authors ) {
                $collect_author_ids = [];
                foreach( $authors as $user_nicename ) {
                    $user = get_user_by( 'slug', $user_nicename );
                    if ( ! $user ) continue; 
                    $collect_author_ids[] = $user->ID;
                }
                if ( ! empty( $collect_author_ids ) ) {
                    $args[ 'author__in' ] = $collect_author_ids;
                }
            }

            /**
             * Number
             */
            $number = get_theme_mod( "{$prefix}number" );
            if ( '' == $number ) $number = 4;
            $args[ 'posts_per_page' ] = $number;

            /**
             * Offset
             */
            $offset = absint( get_theme_mod( "{$prefix}offset" ) );
            if ( $offset > 0 ) {
                $args[ 'offset' ] = $offset;
            }

            /**
             * Orderby
             */
            $orderby = get_theme_mod( "{$prefix}orderby" );

            if ( 'date' == $orderby ) {
                $args['orderby'] = 'date';
            } elseif ( 'comment' == $orderby ) {
                $args['orderby'] = 'comment_count';
            } elseif ( 'random' == $orderby ) {
                $args[ 'orderby' ] = 'rand';
            } elseif ( 'view' == $orderby ) {

                /**
                 * this has been modified since v 3.0
                 * due to replacement of new view count plugin
                 */
                $args[ 'orderby' ] = 'post_views';
            }

            /**
             * Order
             * @since 4.3
             */
            $order = get_theme_mod( "{$prefix}order" );
            if ( 'asc' != $order ) {
                $order = 'desc';
            }
            $args[ 'order' ] = strtoupper( $order );

        }
        
        // unique_reading
        // since 4.3
        if ( 'true' == get_theme_mod( 'wi_unique_reading', 'false' ) ) {
            global $rendered_articles;
            
            if ( ! empty( $rendered_articles ) ) {
                $args[ 'post__not_in' ] = $rendered_articles;
            }
            
        }

    }

    /* ------------------------ POST LAYOUT ------------------------ */
    /**
     * how it works?
     * there're few layers of settings:
     *
     * $layout of this section is decisive to decide which options to be needed
     * we should code in a clear way. don't merge things together to complicate the code logic
     *
     * some options are customized but some options are stick to the design
     */
    $params = [];
    $layout = get_theme_mod( "{$prefix}layout", 'slider' );
    $layout = fox_validate_layout( $layout );
    
    $params[ 'layout' ] = $layout;
    $params[ 'query' ] = $args;

    if ( 'grid-2' == $layout || 'grid-3' == $layout || 'grid-4' == $layout || 'grid-5' == $layout ) {

        $loop = 'grid';

    } elseif ( 'masonry-2' == $layout || 'masonry-3' == $layout || 'masonry-4' == $layout || 'masonry-5' == $layout ) {

        $loop = 'masonry';

    } else {

        $loop = $layout;

    }
    
    $local_params = [
        'loop' => $loop,
        'layout' => $layout,
    ];
    
    /**
     * 01 - custom components
     * only some layouts can use custom components
     */
    
    $use_customize_components = ( 'true' == get_theme_mod( "{$prefix}customize_components", 'false' ) );
    if ( ! in_array( $loop, [ 'grid', 'list', 'masonry', 'standard', 'newspaper', 'big', 'vertical', 'slider' ] ) ) {
        $use_customize_components = false;
    }
    if ( $use_customize_components ) {
        $components = get_theme_mod( "{$prefix}components", 'thumbnail,title,date,category,excerpt' );
        $components_to_show = fox44_component_to_show( $components );
        $local_params = array_merge( $local_params, $components_to_show );
    }
    
    $local_options = [
        'big_post_position', // group-1
        
        'columns_order', // group-2
        
        'slide_content_color', // slider-1
        'slide_content_background', // slider-1
        'slide_content_background_opacity', // slider-1
        
        'item_spacing',
        'item_template',
        'item_align',
        'item_border',
        'item_border_color',
        'thumbnail',
        'thumbnail_custom',
        'thumbnail_shape',
        'color', 
        'title_size',
        'content_excerpt',
        'excerpt_length',
        'excerpt_more_style',
        'excerpt_more_text',
        'excerpt_size',
    ];
    
    // only this needs adapter
    $prop_adapter = [
        'item_align' => 'align'
    ];
    
    foreach ( $local_options as $opt ) {
        
        $prop = isset( $prop_adapter[ $opt ] ) ? $prop_adapter[ $opt ] : $opt;
        $get = get_theme_mod( "{$prefix}{$opt}", '' );
        if ( '' === $get ) {
            continue;
        }
        
        $local_params[ $prop ] = $get;
        
    }
    
    /**
     * forced options for builder
     */
    if ( 'standard' == $layout ) {
        $local_params[ 'share_show' ] = false;
        $local_params[ 'related_show' ] = false;
    }
    
    // QUERY
    $query = new WP_Query( $args );
    
} // shortcode or not

/* ------------------------ SECTION LAYOUT/DESIGN ------------------------ */
/**
 * Class
 */
$section_class = array(
    'wi-section', 
    'fox-section',
    'section-'. $i 
);
if ( $layout ) {
    $section_class[] = 'section-layout-' . $layout;
}
if ( $loop ) {
    $section_class[] = 'section-loop-' . $loop;
}
$section_id = 'fox-section-' . $i;
$section_css = [];

/**
 * section visibility
 * @since 4.4
 */
$section_visibility = get_theme_mod( "{$prefix}section_visibility", 'desktop,tablet,mobile' );
$section_visibility_classes = fox_visibility_class( $section_visibility );
$section_class = array_merge( $section_class, $section_visibility_classes );

/**
 * CSS
 */
$background = get_theme_mod( "{$prefix}background" );
if ( $background ) {
    $section_css[] = 'background-color:' . $background;
    $section_class[] = 'has-background';
}
$text_color = get_theme_mod( "{$prefix}text_color" );
if ( $text_color ) {
    $section_css[] = 'color:' . $text_color;
    $section_class[] = 'custom-color';
}

/**
 * Heading
 */
$heading = trim( get_theme_mod( "{$prefix}heading" ) );
if ( '' != $heading ) {
    $section_class[] = 'section-has-heading';
}

/**
 * Sidebar
 * @since 4.3
 */
$secondary_sidebar = get_theme_mod( "{$prefix}sidebar" );
if ( $secondary_sidebar ) {
    
    $section_class[] = 'section-has-sidebar';
    
    // sidebar position
    $sidebar_position = get_theme_mod( "{$prefix}sidebar_position", 'right' );
    if ( 'left' != $sidebar_position ) {
        $sidebar_position = 'right';
    }
    $section_class[] = 'section-sidebar-' . $sidebar_position;
    
    // sidebar sticky
    if ( 'true' == get_theme_mod( "{$prefix}sidebar_sticky", 'false' ) ) {
        
        $section_class[] = 'section-sidebar-sticky';
        
    }
    
} else {
    
    $section_class[] = 'section-fullwidth';
    
}

/**
 * stretch
 */
$stretch = get_theme_mod( "{$prefix}stretch" );
if ( 'full' != $stretch && 'narrow' != $stretch ) {
    $stretch = 'content';
}
$section_class[] = 'section-stretch-' . $stretch;

/**
 * Border
 */
$border = get_theme_mod( "{$prefix}border" );
if ( $border ) {
    $section_class[] = 'has-border';
    $section_class[] = 'section-border-' . $border;
}

/**
 * section CSS
 * @since 4.3
 */
$section_css = join( ';', $section_css );
if ( ! empty( $section_css ) ) {
    $section_css = ' style="' . esc_attr( $section_css ) . '"';
}

/**
 * Heading Params
 */
$heading_params = [
    'heading' => $heading,
    
    'url' => get_theme_mod( "{$prefix}viewall_link" ),
    'target' => '_self',
    
    'color' => get_theme_mod( "{$prefix}heading_color" ),
];

$heading_props = [
    'align' => 'center', 
    'style' => '1a',
    'line_stretch' => 'content',
    'size' => 'large', 
];
foreach ( $heading_props as $prop => $std ) {
    $get = get_theme_mod( "{$prefix}heading_{$prop}", '' );
    if ( ! $get ) {
        $get = get_theme_mod( "wi_builder_heading_{$prop}", $std );
    }
    $heading_params[ $prop ] = $get;
}

?>

<div class="<?php echo esc_attr( join( ' ', $section_class ) );?>" id="<?php echo esc_attr( $section_id ); ?>"<?php echo $section_css; ?>>
    
    <?php 
    
    $ad_visibility = get_theme_mod( "{$prefix}ad_visibility", 'desktop,tablet,mobile' );
    $visibility_class = fox_visibility_class( $ad_visibility );
    
    fox_ad([
            'code' => get_theme_mod( "{$prefix}ad_code" ),
            'image' => get_theme_mod( "{$prefix}banner" ),
            'width' => get_theme_mod( "{$prefix}banner_width" ),
    
            'tablet' => get_theme_mod( "{$prefix}banner_tablet" ),
            'tablet_width' => get_theme_mod( "{$prefix}banner_tablet_width" ),
    
            'phone' => get_theme_mod( "{$prefix}banner_mobile" ),
            'phone_width' => get_theme_mod( "{$prefix}banner_mobile_width" ),
    
            'url' => get_theme_mod( "{$prefix}banner_url" ),
            'extra_class' => 'section-ad ' . join( ' ', $visibility_class ),
        ]); ?>
    
    <?php fox_section_heading( $heading_params ); ?>
    
    <div class="container">
        
        <div class="section-container">
            
            <div class="section-inner">

                <div class="section-primary">

                    <div class="theiaStickySidebar">

                        <div class="section-main">
                            
                            <?php
                            if ( 'shortcode' == $source ) {
                                
                                echo do_shortcode( $shortcode );
                                
                            } elseif ( 'sidebar' == $source ) {
                                
                                echo $sidebar_html;
                                
                            } else {
                                
                                fox44_blog( $layout, $local_params, $query );
                                
                            } ?>

                        </div><!-- .section-main -->

                        <div class="clearfix"></div>

                    </div><!-- .theiaStickySidebar -->

                </div><!-- .section-primary -->

                <?php if ( $secondary_sidebar ) { ?>

                <aside class="section-secondary section-sidebar" role="complementary" itemscope itemptype="https://schema.org/WPSideBar">

                    <div class="theiaStickySidebar">

                        <div class="widget-area">
                            
                            <?php dynamic_sidebar( $secondary_sidebar ); ?>
                            
                            <div class="gutter-sidebar"></div>
                            
                        </div><!-- .widget-area -->

                    </div><!-- .theiaStickySidebar -->

                </aside><!-- .section-secondary -->
                
                <?php if ( 'true' == get_theme_mod( $prefix . 'sidebar_sep' ) ) {
                    $section_sep_css = '';
                    $section_sep_color = get_theme_mod( $prefix . 'sidebar_sep_color' );
                    if ( $section_sep_color ) {
                        $section_sep_css = ' style="color:' . esc_attr( $section_sep_color ) . '"';
                    }
                ?>
                
                <div class="section-sep"<?php echo $section_sep_css; ?>></div>
                
                <?php } ?>

                <?php } ?>

            </div><!-- .section-inner -->
            
        </div><!-- .section-container -->
        
    </div><!-- .container -->
    
</div><!-- .fox-section -->

<?php

/**
 * main stream order, since 3.0
 */
if ( $section_info[ 'main_after' ] === $i ) {
    
    if ( ! $loaded_main_stream ) {
        get_template_part( 'parts/main-stream' );
        $loaded_main_stream = true;
    }
    
}

endforeach; // each section

// and finally in worse case
if ( ! $loaded_main_stream ) {
    get_template_part( 'parts/main-stream' );
    $loaded_main_stream = true;
}