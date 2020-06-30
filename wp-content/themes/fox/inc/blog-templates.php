<?php
/**
 * Reusable blog templates
 *
 * @package Fox
 * @since 4.0
 */

/**
 * This class renders output regardless options from customizer
 * @since 4.0
 */
class Fox_Blog {
    
    /**
     * Construct
     */
    function __construct( $args = [] ) {
        
        extract( wp_parse_args( $args, [
            'query'     => null,
            'options'   => [],
        ] ) );
        
        // supported layouts
        $layout = isset( $options[ 'layout' ] ) ? $options[ 'layout' ] : '';
        if ( 'big-post' == $layout ) $layout = 'big'; // =.=
        
        if ( ! in_array( $layout, [
            'standard',
            'grid',
            'list', 
            'masonry',
            'newspaper',
            'slider', 
            'big',
            'vertical' 
        ] ) ) {
            $layout = 'standard';
        }
        
        $this->layout = $layout;
        $this->query = $query;
        $this->options = $options;
        $this->output = '';
        
        $this->render_output();
        
        
    }
    
    public function have_posts() {
        
        return $this->query && $this->query->have_posts();
        
    }
    
    /**
     * process to render output
     * @since 4.0
     */
    function render_output() {
        
        if ( ! $this->query ) return;
        
        /**
         * Options
         */
        $options = $this->options;
        
        // turn all true, yes, no, false things into boolean for easing working process
        foreach ( $options as $k => $v ) {
            
            if ( 'yes' == $v || 'true' == $v ) $options[ $k ] = true;
            if ( 'no' == $v || 'false' == $v ) $options[ $k ] = false;
            
        }
        
        $params = fox_blog_params( $this->layout );
        if ( empty( $params ) ) {
            echo 'Empty default params for ' . $this->layout;
            return;
        }
        
        $default_options = [];
        foreach ( $params as $id => $param ) {
            $default_options[ $id ] = isset( $param[ 'std' ] ) ? $param[ 'std' ] : '';
        }
        
        $options = wp_parse_args( $options, $default_options );
        
        extract( $options );
        
        // if review score enabled, index off
        if ( isset( $thumbnail_review_score ) && $thumbnail_review_score ) {
            $thumbnail_index = false;
        }
        
        /**
         * Container Class
         */
        $this->container_class = [
            'blog-container',
            'blog-container-' . $this->layout
        ];
        
        /**
         * Blog Class
         */
        $this->blog_class = [
            'wi-blog',
            'fox-blog',
            'blog-' . $this->layout
        ];
        
        if ( isset( $extra_class ) && $extra_class ) {
            $this->blog_class[] = $extra_class;
        }
        
        // backward compatibility, a legacy
        if ( 'big' == $this->layout ) {
            $this->blog_class[] = 'wi-big';
        }
        
        /**
         * Column & Item Spacing
         * Grid / Masonry Layout
         * masonry and grid layout requires column and item_spacing
         */
        if ( 'masonry' == $this->layout || 'grid' == $this->layout ) {
            
            $this->blog_class[] = 'fox-grid';
            
            // column
            if ( $column < 1 || $column > 5 ) $column = 4;
            $this->blog_class[] = 'column-' . $column;
            
            // spacing
            if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'wide', 'wider' ] ) ) {
                $item_spacing = 'normal';
            }
            $this->blog_class[] = 'spacing-' . $item_spacing;
            
        }
        
        // list spacing
        if ( 'list' == $this->layout ) {
            if ( ! isset( $list_spacing ) ) $list_spacing = 'normal';
            if ( ! in_array( $list_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) {
                $list_spacing = 'normal';
            }
            
            $this->blog_class[] = 'v-spacing-' . $list_spacing;
        }
        
        // for masonry to trigger
        if ( 'masonry' == $this->layout || 'newspaper' == $this->layout ) {
            $this->blog_class[] = 'fox-masonry';
        }
        
        // big post featured
        if (  'masonry' == $this->layout && isset( $big_first_post ) && $big_first_post ) {
            $this->blog_class[] = 'fox-masonry-featured-first';
        } 
        
        // newspaper
        if ( 'newspaper' == $this->layout ) {
            $this->blog_class[] = 'fox-grid';
            $this->blog_class[] = 'column-2';
            $this->blog_class[] = 'spacing-normal';
        }
        
        /**
         * After Blog
         */
        $this->afterblog = '';
        if ( 'masonry' == $this->layout || 'newspaper' == $this->layout ) {
            $this->afterblog .= '<div class="grid-sizer fox-grid-item"></div>' . fox_loading_element();
        }
        
        ob_start();
        
        if ( ! $this->query->have_posts() ) {
            $this->output = ob_get_clean();
            return;
        }
        
        $count = 0;
        
        // create a backup
        $thumbnail_inside_backup = '';
        if ( isset( $thumbnail_inside ) ) {
            $thumbnail_inside_backup = $thumbnail_inside;
        }
        ?>

<div class="<?php echo esc_attr( join( ' ', $this->container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $this->blog_class ) ); ?>">
        
        <?php while( $this->query->have_posts() ) : $this->query->the_post(); $count++;
        
        $thumbnail_inside = $thumbnail_inside_backup;
        if ( isset( $thumbnail_index ) && $thumbnail_index ) $thumbnail_inside .= '<span class="thumbnail-index">' . sprintf( '%02d' , $count ) . '</span>';
        
        /**
         * masonry big post first
         */
        if ( isset( $big_first_post ) && $big_first_post && $count == 1 ) {
            $first_post_featured = true;
        } else {
            $first_post_featured = false;
        }
            
        include get_parent_theme_file_path( 'parts/content-' . $this->layout . '.php' );
        
        do_action( 'fox_after_render_post' ); // since 4.0
        
        endwhile;
        
        echo $this->afterblog; ?>
        
    </div><!-- .fox-blog -->
    
    <?php if ( isset( $pagination ) && $pagination ) { fox_pagination( $this->query ); } ?>
    
</div><!-- .blog-container -->

        <?php
        $this->output = ob_get_contents();
        ob_end_clean();
        
    }
    
    /**
     * return or echo final output
     * @since 4.0
     */
    function output( $echo = true ) {
        
        if ( $echo ) echo $this->output;
        else return $this->output;
        
    }
    
}

if ( ! function_exists( 'fox_blog_standard' ) ) :
/*
 * Display standard blog
 * @since 4.0
 */
function fox_blog_standard( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'standard',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_newspaper' ) ) :
/*
 * Displays newspaper blog
 * @since 4.0
 */
function fox_blog_newspaper( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'newspaper',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_grid' ) ) :
/*
 * Displays blog grid layout
 * @since 4.0
 */
function fox_blog_grid( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'grid',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_masonry' ) ) :
/*
 * Displays blog masonry
 * @since 4.0
 */
function fox_blog_masonry( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'masonry',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_list' ) ) :
/*
 * Displays blog list
 * @since 4.0
 */
function fox_blog_list( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'list',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_big' ) ) :
/*
 * Displays the big post
 * @since 4.0
 */
function fox_blog_big( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'big',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_vertical' ) ) :
/**
 * $query is a WP_Query instance
 * $layout_args is option to pass to blog class
 *
 * @since 4.0
 */
function fox_blog_vertical( $query = null, $options = [] ) {
    
    $new_blog = new Fox_Blog([
        'layout'    => 'vertical',
        'query'     => $query,
        'options'   => $options,
    ]);
    
    $new_blog->output();
    
}
endif;

if ( ! function_exists( 'fox_blog_slider' ) ) :
/**
 * $query is a WP_Query instance
 * $options is option to pass to blog class
 *
 * @since 4.0
 */
function fox_blog_slider( $query = null, $options = [] ) {
    
    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }
    
    $default_options = fox_default_blog_options( 'slider' );
    $options = wp_parse_args( $options, $default_options );
    
    // turn all true, yes, no, false things into boolean for easing working process
    foreach ( $options as $k => $v ) {

        if ( 'yes' == $v || 'true' == $v ) $options[ $k ] = true;
        if ( 'no' == $v || 'false' == $v ) $options[ $k ] = false;

    }
    
    $options_backup = $options;
    
    extract( $options );
    
    if ( ! $query->have_posts() ) return;
    
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
    if ( 'fade' != $slider_effect ) $slider_effect = 'slide';
    $slider_options[ 'animation' ] = $slider_effect;
    
    // nav style
    if ( 'arrow' != $nav_style ) $nav_style = 'text';
    $class[] = 'style--slider-nav' . $nav_style;
    
    if ( 'arrow' == $nav_style ) {
        $slider_options[ 'prevText' ] = '<i class="fa fa-angle-left"></i>';
        $slider_options[ 'nextText' ] = '<i class="fa fa-angle-right"></i>';
    } else {
        $slider_options[ 'prevText' ] = '<i class="fa fa-chevron-left"></i>' . '<span>' . fox_word( 'previous' ) . '</span>';
        $slider_options[ 'nextText' ] = '<span>' . fox_word( 'next' ) . '</span>' . '<i class="fa fa-chevron-right"></i>';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $slider_options ); ?>'>
            
    <div class="flexslider">
        
        <ul class="slides">
            
            <?php while( $query->have_posts()): $query->the_post(); ?>
            
            <li class="slide">
                
                <?php include get_parent_theme_file_path( 'parts/content-slider.php' ); ?>
                
                <?php do_action( 'fox_after_render_post' ); // since 4.0 ?>
                
            </li>
            
            <?php endwhile;?>
            
        </ul>
        
    </div><!-- .flexslider -->
    
    <?php echo fox_loading_element(); ?>

</div><!-- .wi-flexslider -->

<?php
    
}
endif;

/* ---------------------            BLOG GROUP 1           --------------------- */
if ( ! function_exists( 'fox_blog_group1' ) ) :
/**
 * $query is a WP_Query instance
 * @since 4.0
 */
function fox_blog_group1( $query = null, $options = [] ) {
    
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
    
    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }
    
    if ( ! $query->have_posts() ) return;
    
    $default_options = fox_default_blog_options( 'group-1' );
    $options = wp_parse_args( $options, $default_options );
    
    // turn all true, yes, no, false things into boolean for easing working process
    foreach ( $options as $k => $v ) {

        if ( 'yes' == $v || 'true' == $v ) $options[ $k ] = true;
        if ( 'no' == $v || 'false' == $v ) $options[ $k ] = false;

    }
    
    // clone it for future use
    $options_backup = $options;
    
    extract( $options );
    
    // big post position
    if ( 'right' != $bigpost_position ) {
        $bigpost_position = 'left';
    }
    $class[] = 'big-post-' . $bigpost_position;
    
    // bigpost_ratio
    if ( '3/4' != $bigpost_ratio ) $bigpost_ratio = '2/3';
    $class[] = 'big-post-ratio-' . str_replace( '/', '-', $bigpost_ratio );
    
    // sep border
    $sep_border_css = [];
    if ( $sep_border ) {
        $class[] = 'has-border';
    }
    if ( $sep_border_color ) {
        $sep_border_css[] = 'color:' . $sep_border_color;
    }
    $sep_border_css = join( ';', $sep_border_css );
    if ( ! empty( $sep_border_css ) ) {
        $sep_border_css = ' style="' . esc_attr( $sep_border_css ) . '"';
    }
    
    // vertical spacing
    if ( ! isset( $small_posts_list_spacing ) ) $small_posts_list_spacing = 'normal';
    if ( ! in_array( $small_posts_list_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) {
        $small_posts_list_spacing = 'normal';
    }

    $class[] = 'v-spacing-' . $small_posts_list_spacing;
    
    $count = 0; 
?>

<div class="<?php echo esc_attr( join( ' ', $container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <?php while ( $query->have_posts() ) : $query->the_post(); $count++; $bigpost = ( 1 == $count ); ?>
        
        <?php 
            /* BIG POST
            -------------------- */
            if ( $bigpost ) :
    
            foreach ( $options as $k => $v ) {
                if ( 'bigpost_' == substr( $k, 0, 8 ) ) {
                    $options[ substr( $k, 8 ) ] = $v;
                }
            }
            $options[ 'thumbnail_placeholder' ] = false;
            extract( $options );
    
        // legacy
        $extra_post_class = 'article-big';
    
        ?>
        
        <div class="post-group-col post-group-col-big article-big-wrapper">
            
            <?php include get_parent_theme_file_path( 'parts/content-grid.php' ); ?>
            
            <?php do_action( 'fox_after_render_post' ); ?>
            
        </div><!-- .post-group-col -->
        
        <div class="post-group-col post-group-col-small article-small-wrapper">
        
        <?php
    
        /* SMALL POST
        -------------------- */
        else : // small posts

        // restore options
        $options = $options_backup;
    
        foreach ( $options as $k => $v ) {
            
            if ( 0 === strpos( $k, 'small_posts_' ) ) {
                $options[ str_replace( 'small_posts_', '', $k ) ] = $v;
            }
            
        }
    
        extract( $options );
    
        $thumbnail_type = 'simple';
    
        // legacy
        $extra_post_class = 'article-small-list';

        ?>
            
            <?php include get_parent_theme_file_path( 'parts/content-list.php' ); ?>
            
            <?php do_action( 'fox_after_render_post' ); ?>
        
            <?php endif; // big or small post ?>
            
            <?php endwhile; ?>
            
        </div><!-- .article-small-wrapper -->
        
        <?php if ( $sep_border ) { ?>
        <div class="sep-border"<?php echo $sep_border_css; ?>></div>
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->

<?php

}
endif;

/* ---------------------            BLOG GROUP 2           --------------------- */
if ( ! function_exists( 'fox_blog_group2' ) ) :
/**
 * $query is a WP_Query instance
 * @since 4.0
 */
function fox_blog_group2( $query = null, $options = [] ) {
    
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
    
    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }
    
    if ( ! $query->have_posts() ) return;
    
    $default_options = fox_default_blog_options( 'group-2' );
    $options = wp_parse_args( $options, $default_options );
    
    // turn all true, yes, no, false things into boolean for easing working process
    foreach ( $options as $k => $v ) {

        if ( 'yes' == $v || 'true' == $v ) $options[ $k ] = true;
        if ( 'no' == $v || 'false' == $v ) $options[ $k ] = false;

    }
    
    $options_backup = $options;
    
    extract( $options );
    
    // big post position
    $class[] = 'post-group-row-' . $columns_order;
    
    $explode = explode( '-', $columns_order );
    $big_order = 1 + array_search( '1a', $explode );
    $small_order = 1 + array_search( '3', $explode );
    $tall_order = 1 + array_search( '1b', $explode );
    
    $class[] = 'big-order-' . $big_order;
    $class[] = 'small-order-' . $small_order;
    $class[] = 'tall-order-' . $tall_order;
    
    // sep border
    $sep_border_css = [];
    if ( $sep_border ) {
        $class[] = 'has-border';
    }
    if ( $sep_border_color ) {
        $sep_border_css[] = 'color:' . $sep_border_color;
    }
    $sep_border_css = join( ';', $sep_border_css );
    if ( ! empty( $sep_border_css ) ) {
        $sep_border_css = ' style="' . esc_attr( $sep_border_css ) . '"';
    }
    
    $count = 0;
    $thumbnail_inside = '';
    $thumbnail_view = '';
    $thumbnail_review_score = '';
?>

<div class="<?php echo esc_attr( join( ' ', $container_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <?php while ( $query->have_posts() ) : $query->the_post(); $count++; ?>
    

        <?php 
            /* BIG POST
            -------------------- */
            if ( 1 == $count ) :
    
            foreach ( $options as $k => $v ) {
                if ( 0 === strpos( $k, 'bigpost_' ) ) {
                    $options[ str_replace( 'bigpost_', '', $k ) ] = $v;
                }
            }
    
            $options[ 'thumbnail_placeholder' ] = false;
            extract( $options );
    
            // legacy
            $extra_post_class = 'article-big';
    
        ?>
        
        <div class="post-group-col post-group-col-big article-col-big">
            
            <?php include get_parent_theme_file_path( 'parts/content-grid.php' ); ?>
            
            <?php do_action( 'fox_after_render_post' ); ?>
            
        </div><!-- .post-group-col -->
        
        <?php
    
    /* TALL POST / OR WE CALL MEDIUM POST
    -------------------- */
    elseif ( 2 == $count ) :
    
    $options = $options_backup;
    
    foreach ( $options as $k => $v ) {
        if ( 'medium_post_' == substr( $k, 0, 12 ) ) {
            $options[ substr( $k, 12 ) ] = $v;
        }
    }
    
    $options[ 'thumbnail_placeholder' ] = false;
    $thumbnail_type = 'simple';
    
    extract( $options );
    
    $extra_post_class = 'article-tall article-medium';
        
        ?>
        
        <div class="post-group-col post-group-col-tall article-col-tall">
    
            <?php include get_parent_theme_file_path( 'parts/content-grid.php' ); ?>
            
            <?php do_action( 'fox_after_render_post' ); ?>
            
        </div><!-- .post-group-col-tall -->
        
        <div class="post-group-col post-group-col-small article-col-small">
    
    <?php /* 3 POSTS
    -------------------- */
    else : // small posts 
    
    $options = $options_backup;
    
    foreach ( $options as $k => $v ) {
        if ( 'small_posts_' == substr( $k, 0, 12 ) ) {
            $options[ substr( $k, 12 ) ] = $v;
        }
    }
    
    $options[ 'thumbnail_placeholder' ] = false;
    $thumbnail_type = 'simple';
    extract( $options );
    
    $extra_post_class = 'article-small article-small-grid';
            
            ?>
            
            <?php include get_parent_theme_file_path( 'parts/content-grid.php' ); ?>
            
            <?php do_action( 'fox_after_render_post' ); ?>
        
            <?php endif; // big or small post ?>
            
            <?php endwhile; // have_posts ?>
        
            <?php if ( $count >=2 ) { ?>
        </div><!-- .post-group-col-small -->
            <?php } ?>
        
        <?php if ( 'yes' == $sep_border ) { ?>
        
        <div class="sep-border line1"<?php echo $sep_border_css; ?>></div>
        <div class="sep-border line2"<?php echo $sep_border_css; ?>></div>
        
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->
<?php
}
endif;