<?php
/**
 * $final_params is the perfect set of params after all combinations
 * here we don't parse params again, we don't process, filter..
 * we treat $final_params as perfect version of params
 * $layout is the perfect version of layout, has been validated
 *
 * @since 4.4
 */
function fox44_blog( $layout, $params, $query = null ) {
    
    // no posts found
    // don't waste my time
    if ( ! $query->have_posts() ) {
        wp_reset_query();
        return;
    }
    
    $loop = fox_get_loop_from_layout( $layout );
    $params[ 'layout' ] = $layout; // just in case
    $params[ 'loop' ] = $loop; // just in case
    $column = fox_get_column_from_layout( $layout );
    if ( ! isset( $params[ 'column' ] ) ) {
        $params[ 'column' ] = $column;
    }
    
    /**
     * $c_params is params from customizer with default
     * basically, c_params is enough. now params keys must be subset of c_params keys
     * has been normalized with canonical ids
     * $params is things can override $c_params
     */
    $c_params = fox44_default_params( $loop );
    $fn_params = wp_parse_args( $params, $c_params );
    
    /**
     * excerpt color problem
     */
    if ( $fn_params[ 'color' ] ) {
        $fn_params[ 'excerpt_color' ] = 'inherit';
    }
    
    $loop_to_function = [
        
        'grid' => 'fox44_blog_grid',
        'masonry' => 'fox44_blog_masonry',
        'list' => 'fox44_blog_list',
        'vertical' => 'fox44_blog_vertical',
        'big' => 'fox44_blog_big',
        
        'standard' => 'fox44_blog_standard',
        'newspaper' => 'fox44_blog_newspaper',
        
        'group-1' => 'fox44_blog_group1',
        'group-2' => 'fox44_blog_group2',
        
        'slider' => 'fox44_blog_slider',
        
        'slider-1' => 'fox44_blog_slider1',
        // 'carousel' => 'fox44_blog_carousel', @todo
    ];
    
    /**
     * @since 4.4.1
     */
    $fn_params = apply_filters( 'fox_final_params', $fn_params, $layout, $query );
    
    if ( isset( $loop_to_function[ $loop ] ) ) {
        
        call_user_func( $loop_to_function[ $loop ], $fn_params, $query );
        
    }
    
    wp_reset_query();
    
}

/**
 * Blog Grid
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_grid( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-grid'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-grid'
    ];
    
    $grid_line_class = [
        'fox-grid',
        'grid-lines',
    ];
    
    $class[] = 'fox-grid';
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
        
    /**
     * column
     */
    $class[] = 'column-' . $fn_params['column'];
    $grid_line_class[] = 'column-' . $fn_params['column'];

    /**
     * item spacing
     */
    $item_spacing = $fn_params[ 'item_spacing' ];
    if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'wide', 'wider' ] ) ) {
        $item_spacing = 'normal';
    }
    $class[] = 'spacing-' . $item_spacing;
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
        $class[] = 'blog-custom-color';
        $unique_id = uniqid( 'blog-' );
        $id_attr = ' id="' . esc_attr( $unique_id ) . '"';
        $css_str = '<style type="text/css">#' . $unique_id . '{color:' . esc_html( $color ). ';}</style>';
    }
    
    /**
     * border css
     * @since 4.4.2
     */
    $border_css = '';
    if ( $fn_params[ 'item_border_color' ] ) {
        $border_css = ' style="color:' . esc_attr( $fn_params[ 'item_border_color' ] ) . '"';
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
        
        fox43_item( $fn_params, $count );
        
        do_action( 'fox_after_render_post' );
        
    } ?>
        
        <?php if ( 'true' == $fn_params[ 'item_border' ] ) { ?>
        
        <div class="<?php echo esc_attr( join( ' ', $grid_line_class ) ); ?>"<?php echo $border_css; ?>>
            
            <?php for ( $i = 1; $i <= $fn_params['column']; $i++ ) { ?>
            
            <div class="grid-line fox-grid-item"></div>
            
            <?php } ?>
            
        </div><!-- .grid-lines -->
        
        <?php } ?>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog Masonry
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_masonry( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-masonry'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-masonry'
    ];
    
    $class[] = 'fox-grid fox-masonry';
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
    
    $grid_line_class = [
        'fox-grid',
        'grid-lines',
    ];
        
    /**
     * column
     */
    $class[] = 'column-' . $fn_params['column'];
    $grid_line_class[] = 'column-' . $fn_params['column'];

    /**
     * item spacing
     */
    $item_spacing = $fn_params[ 'item_spacing' ];
    if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'wide', 'wider' ] ) ) {
        $item_spacing = 'normal';
    }
    $class[] = 'spacing-' . $item_spacing;
    
    /**
     * big first post
     */
    if ( $fn_params[ 'big_first_post' ] ) {
        $class[] = 'fox-masonry-featured-first';
    }
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
        $class[] = 'blog-custom-color';
        $unique_id = uniqid( 'blog-' );
        $id_attr = ' id="' . esc_attr( $unique_id ) . '"';
        $css_str = '<style type="text/css">#' . $unique_id . '{color:' . esc_html( $color ). ';}</style>';
    }
    
    /**
     * border css
     * @since 4.4.2
     */
    $border_css = '';
    if ( $fn_params[ 'item_border_color' ] ) {
        $border_css = ' style="color:' . esc_attr( $fn_params[ 'item_border_color' ] ) . '"';
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
        
        fox43_item( $fn_params, $count );
        
        do_action( 'fox_after_render_post' );
        
    } ?>
        
        <div class="grid-sizer fox-grid-item"></div>
        
        <?php if ( 'true' == $fn_params[ 'item_border' ] ) { ?>
        
        <div class="<?php echo esc_attr( join( ' ', $grid_line_class ) ); ?>"<?php echo $border_css; ?>>
            
            <?php for ( $i = 1; $i <= $fn_params['column']; $i++ ) { ?>
            
            <div class="grid-line fox-grid-item"></div>
            
            <?php } ?>
            
        </div><!-- .grid-lines -->
        
        <?php } ?>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog List
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_list( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-list'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-list'
    ];
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
        
    $list_spacing = $fn_params[ 'list_spacing' ];
    if ( ! in_array( $list_spacing, [ 'none', 'tiny', 'small', 'normal', 'medium', 'large' ] ) ) {
        $list_spacing = 'normal';
    }

    $class[] = 'v-spacing-' . $list_spacing;
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
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
        
        fox43_item( $fn_params, $count );
        
        do_action( 'fox_after_render_post' );
        
    } ?>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog Vertical
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_vertical( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-vertical'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-vertical'
    ];
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
    
    // forced
    // unforced since 4.4.1. why forced?
    // $fn_params[ 'thumbnail_hover' ] = 'none';
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
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
        
        fox43_item( $fn_params, $count );
        
        do_action( 'fox_after_render_post' );
        
    } ?>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog Standard
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_standard( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-standard'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-standard'
    ];
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
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
        
        fox43_item_standard( $fn_params, $count );
        do_action( 'fox_after_render_post' );
        
    } ?>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog Newspaper
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_newspaper( $fn_params, $query ) {
    
    $container_class = [
        'blog-container',
        'blog-container-newspaper'
    ];
    
    $class = [
        'wi-blog',
        'fox-blog',
        'blog-newspaper'
    ];
    
    $class[] = 'fox-masonry fox-grid column-2 spacing-normal';
    
    if ( $fn_params[ 'extra_class' ] ) {
        $class[] = $fn_params[ 'extra_class' ];
    }
    
    /**
     * color
     */
    $css_str = '';
    $id_attr = '';
    $color = trim( $fn_params[ 'color' ] );
    if ( $color ) {
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
        
        fox43_item_newspaper( $fn_params, $count );
        do_action( 'fox_after_render_post' );
        
    } ?>
        
        <div class="grid-sizer fox-grid-item"></div>
    
    </div><!-- .fox-blog -->
    
    <?php if ( $fn_params[ 'pagination' ] ) { fox_pagination( $query ); } ?>
    
</div><!-- .fox-blog-container -->

    <?php
}

/**
 * Blog Big
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_big( $fn_params, $query ) {
    
    $post_css = $meta_css = [];
    $post_class = [
        'wi-post',
        'post-item',
        'post-big',
        'has-thumbnail', // has-thumbnail is a legacy
    ];
    
    if ( $fn_params[ 'extra_class' ] ) {
        $post_class[] = $fn_params[ 'extra_class' ];
    }

    if ( isset( $fn_params[ 'align' ] ) ) {
        if ( 'left' == $fn_params[ 'align' ] || 'center' == $fn_params[ 'align' ] || 'right' == $fn_params[ 'align' ] ) {
            $post_class[] = 'post-align-' . $fn_params[ 'align' ];
        }
    }

    // custom text color
    if ( isset( $fn_params[ 'color' ] ) && $fn_params[ 'color' ] ) {
        $post_class[] = 'post-custom-color';
        $post_css[] = 'color:' . $fn_params[ 'color' ];
    }

    $post_css = join( ';', $post_css );
    if ( ! empty( $post_css ) ) {
        $post_css = ' style="' . esc_attr( $post_css ). '"';
    }

    if ( isset( $fn_params[ 'meta_background' ] ) && $fn_params[ 'meta_background' ] ) {
        $post_class[] = 'post-has-meta-custom-bg';
        $meta_css[] = 'background:' . $fn_params[ 'meta_background' ];
    }
    $meta_css = join( ';', $meta_css );
    if ( ! empty( $meta_css ) ) {
        $meta_css = ' style="' . esc_attr( $meta_css ). '"';
    }

    $date_format = apply_filters( 'fox_big_date_format', 'd.m.Y' );
    
    $thumbnail_params = $fn_params;
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
    $show_category = $fn_params[ 'category_show' ];
    $show_date = $fn_params[ 'date_show' ];
    $show_author = $fn_params[ 'author_show' ];
    $show_author_avatar = $fn_params[ 'author_avatar_show' ];
    $show_view = $fn_params[ 'view_show' ];
    $show_reading_time = $fn_params[ 'reading_time_show' ];
    $show_comment_link = $fn_params[ 'comment_link_show' ];
    
    while( $query->have_posts() ) : $query->the_post();
    
    ?>

    <article <?php post_class( $post_class ); ?>  <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
        
        <?php 
    if ( $fn_params[ 'thumbnail_show' ] ) {
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
                    if ( $fn_params[ 'title_show' ] ) {
                        fox_post_title([
                            'extra_class' => 'big-title', 
                            'size' => $fn_params[ 'title_size' ] 
                        ]);
                        
                        fox43_live_indicator();
                        
                    } ?>

            </header><!-- .big-header -->

            <?php if ( $fn_params[ 'excerpt_show' ] ) { ?>

                <?php if ( isset( $fn_params[ 'content_excerpt' ] ) && $fn_params[ 'content_excerpt' ] == 'content' ) { ?>
            
                <div class="big-content" itemprop="text">

                    <?php the_content( '<span class="big-more">'. fox_word( 'more_link' ) .'</span>' ); ?>

                </div>
                <?php } else { ?>
                <div class="big-content" itemprop="text">

                    <?php fox_post_excerpt([ 'length' => $fn_params[ 'excerpt_length' ], 'more' => false, 'size' => $fn_params[ 'excerpt_size' ] ]); ?>

                    <?php if ( $fn_params[ 'excerpt_more' ] ) {
                    
                        $more_text = isset( $fn_params[ 'excerpt_more_text' ] ) && $fn_params[ 'excerpt_more_text' ] ? $fn_params[ 'excerpt_more_text' ] : fox_word( 'more_link' );
                        
                    ?>

                    <a href="<?php the_permalink(); ?>" class="more-link readmore minimal-link">
                        
                        <span class="big-more"><?php echo $more_text; ?></span>
                        
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
    
}

/**
 * Blog Group 1
 *
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_group1( $fn_params, $query ) {
    
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
    
    // big post position
    if ( 'right' != $fn_params[ 'big_post_position' ] ) {
        $fn_params[ 'big_post_position' ] = 'left';
    }
    $class[] = 'big-post-' . $fn_params[ 'big_post_position' ];
    
    // big_post_ratio
    if ( '3/4' != $fn_params[ 'big_post_ratio' ] ) $fn_params[ 'big_post_ratio' ] = '2/3';
    $class[] = 'big-post-ratio-' . str_replace( '/', '-', $fn_params[ 'big_post_ratio' ] );
    
    // sep border
    $sep_border_css = [];
    if ( $fn_params[ 'sep_border' ] ) {
        $class[] = 'has-border';
    }
    if ( $fn_params[ 'sep_border_color' ] ) {
        $sep_border_css[] = 'color:' . $fn_params[ 'sep_border_color' ];
    }
    $sep_border_css = join( ';', $sep_border_css );
    if ( ! empty( $sep_border_css ) ) {
        $sep_border_css = ' style="' . esc_attr( $sep_border_css ) . '"';
    }
    
    // vertical spacing
    $small_post_list_spacing = $fn_params[ 'small_post_list_spacing' ];
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
    
                $params = fox44_default_params( 'group1-big' );
                $prefix = 'big_post_';
                
                // fixed params due to desgin
                $params[ 'post_extra_class' ] = 'article-big';
                $params[ 'thumbnail_placeholder' ] = false;
                $params[ 'loop' ] = 'grid';
                $params[ 'title_size' ] = 'medium';
                $params[ 'thumbnail' ] = 'thumbnail-large'; // original size
                $params[ 'thumbnail_index' ] = false;
                $params[ 'thumbnail_format_indicator' ] = true;
                
                // changable params
                $params[ 'item_template' ] = $fn_params[ $prefix . 'item_template' ];
                $params[ 'align' ] = $fn_params[ $prefix . 'align' ];
                $params[ 'excerpt_length' ] = $fn_params[ $prefix . 'excerpt_length' ];
                $params[ 'excerpt_more_style' ] = $fn_params[ $prefix . 'excerpt_more_style' ];
                $params[ 'excerpt_more_text' ] = $fn_params[ $prefix . 'excerpt_more_text' ];
                
                // show/hide components
                $params = wp_parse_args( fox44_component_to_show( $fn_params[ 'big_post_components' ] ), $params );
    
        ?>
        
        <div class="post-group-col post-group-col-big article-big-wrapper">
            
            <?php fox43_item( $params, $count ); ?>
            
        </div><!-- .post-group-col -->
        
        <div class="post-group-col post-group-col-small article-small-wrapper">
        
        <?php
    
            /* SMALL POST
            -------------------- */
            } else { // small posts
                
                $params = fox44_default_params( 'group1-small' );
                $prefix = 'small_post_';
                
                // fixed params due to desgin
                $params[ 'post_extra_class' ] = 'article-small-list';
                $params[ 'loop' ] = 'list';
                $params[ 'live' ] = false;
                $params[ 'thumbnail' ] = 'landscape';
                $params[ 'thumbnail_type' ] = 'simple';
                $params[ 'thumbnail_position' ] = 'right';
                $params[ 'thumbnail_placeholder' ] = false;
                $params[ 'thumbnail_index' ] = false;
                $params[ 'thumbnail_review_score' ] = false;
                $params[ 'thumbnail_hover' ] = '';
                $params[ 'thumbnail_format_indicator' ] = true;
                
                $params[ 'title_size' ] = 'small';
                
                $params[ 'excerpt_size' ] = 'small';
                $params[ 'excerpt_more_style' ] = 'simple';
                $params[ 'excerpt_length' ] = 12;
                
                $params[ 'list_mobile_layout' ] = 'list';
                $params[ 'list_sep' ] = $fn_params[ 'sep_border' ];
                if ( $fn_params[ 'sep_border_color' ] ) {
                    $params[ 'list_sep_color' ] = $fn_params[ 'sep_border_color' ];
                }
                
                // changable params
                $params[ 'item_template' ] = $fn_params[ $prefix . 'item_template' ];
                
                // show/hide components
                $params = wp_parse_args( fox44_component_to_show( $fn_params[ 'small_post_components' ] ), $params );
                
                fox43_item( $params, $count );
        
            } // big or small post ?>
            
            <?php 
            do_action( 'fox_after_render_post' );
            endwhile; ?>
            
        </div><!-- .article-small-wrapper -->
        
        <?php if ( $fn_params[ 'sep_border' ] ) { ?>
        <div class="sep-border"<?php echo $sep_border_css; ?>></div>
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->

<?php
    
}

/**
 * Blog Group 2
 *
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_group2( $fn_params, $query ) {
    
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
    $class[] = 'post-group-row-' . $fn_params[ 'columns_order' ];
    
    $explode = explode( '-', $fn_params[ 'columns_order' ] );
    $big_order = 1 + array_search( '1a', $explode );
    $small_order = 1 + array_search( '3', $explode );
    $tall_order = 1 + array_search( '1b', $explode );
    
    $class[] = 'big-order-' . $big_order;
    $class[] = 'small-order-' . $small_order;
    $class[] = 'tall-order-' . $tall_order;
    
    // sep border
    $sep_border_css = [];
    if ( $fn_params[ 'sep_border' ] ) {
        $class[] = 'has-border';
    }
    if ( $fn_params[ 'sep_border_color' ] ) {
        $sep_border_css[] = 'color:' . $fn_params[ 'sep_border_color' ];
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
    
                $params = fox44_default_params( 'group2-big' );
                $prefix = 'big_post_';
                
                $params[ 'post_extra_class' ] = 'article-big';
                $params[ 'loop' ] = 'grid';
                $params[ 'thumbnail_placeholder' ] = false;
                $params[ 'thumbnail' ] = 'thumbnail-large'; // original size
                $params[ 'thumbnail_index' ] = false;
                $params[ 'title_size' ] = 'medium';
                
                $params[ 'item_template' ] = $fn_params[ $prefix . 'item_template' ];
                $params[ 'align' ] = $fn_params[ $prefix . 'align' ];
                $params[ 'excerpt_length' ] = $fn_params[ $prefix. 'excerpt_length' ];
                $params[ 'excerpt_more_style' ] = $fn_params[ $prefix . 'excerpt_more_style' ];
                $params[ 'excerpt_more_text' ] = $fn_params[ $prefix . 'excerpt_more_text' ];
                
                // show/hide components
                $params = wp_parse_args( fox44_component_to_show( $fn_params[ 'big_post_components' ] ), $params );
    
        ?>
        
        <div class="post-group-col post-group-col-big article-col-big">
            
            <?php fox43_item( $params ); ?>
            
        </div><!-- .post-group-col -->
        
        <?php
    
    /* TALL POST / OR WE CALL MEDIUM POST
    -------------------- */
            } elseif ( 2 == $count ) {
                
                $params = fox44_default_params( 'group2-medium' );
                $prefix = 'medium_post_';
                
                $params[ 'loop' ] = 'grid';
                $params[ 'post_extra_class' ] = 'article-tall article-medium';
                $params[ 'thumbnail_placeholder' ] = false;
                $params[ 'title_size' ] = 'normal';
                $params[ 'align' ] = 'left';
                $params[ 'thumbnail' ] = $fn_params[ 'medium_post_thumbnail' ];
                $params[ 'thumbnail_index' ] = false;
                $params[ 'thumbnail_review_score' ] = false;
                $params[ 'excerpt_more_style' ] = 'simple';
                
                $params[ 'item_template' ] = $fn_params[ 'medium_post_item_template' ];
                $params[ 'excerpt_length' ] = $fn_params[ 'medium_post_excerpt_length' ];;
                
                // show/hide components
                $params = wp_parse_args( fox44_component_to_show( $fn_params[ 'medium_post_components' ] ), $params );
        ?>
        
        <div class="post-group-col post-group-col-tall article-col-tall">
    
            <?php fox43_item( $params ); ?>
            
        </div><!-- .post-group-col-tall -->
        
        <div class="post-group-col post-group-col-small article-col-small">
    
    <?php /* 3 POSTS
    -------------------- */
            } else { // small posts 
    
                $params = fox44_default_params( 'group2-small' );
                $prefix = 'small_post_';
                
                $params[ 'thumbnail_placeholder' ] = false;
                
                $params[ 'loop' ] = 'grid';
                $params[ 'post_extra_class' ] = 'article-small article-small-grid';
                $params[ 'title_size' ] = 'small';
                $params[ 'align' ] = 'left';
                $params[ 'thumbnail' ] = 'landscape';
                $params[ 'thumbnail_index' ] = false;
                $params[ 'excerpt_more_style' ] = 'simple';
                $params[ 'excerpt_size' ] = 'small';
                $params[ 'thumbnail_review_score' ] = false;
                
                $params[ 'item_template' ] = $fn_params[ $prefix . 'item_template' ];
                $params[ 'excerpt_length' ] = $fn_params[ $prefix . 'excerpt_length' ];
                
                // show/hide components
                $params = wp_parse_args( fox44_component_to_show( $fn_params[ 'small_post_components' ] ), $params );
            
            ?>
            
            <?php fox43_item( $params ); ?>
        
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
        
        <?php if ( $fn_params[ 'sep_border' ] ) { ?>
        
        <div class="sep-border line1"<?php echo $sep_border_css; ?>></div>
        <div class="sep-border line2"<?php echo $sep_border_css; ?>></div>
        
        <?php } ?>

    </div><!-- .wi-newsblock -->
    
</div><!-- .blog-container-group -->
<?php
    
}

/**
 * Blog Slider
 *
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_slider( $fn_params, $query ) {
    
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
    if ( 'fade' != $fn_params[ 'effect' ] ) $fn_params[ 'effect' ] = 'slide';
    $slider_options[ 'animation' ] = $fn_params[ 'effect' ];
    
    // nav style
    if ( 'arrow' != $fn_params[ 'nav_style' ] ) $fn_params[ 'nav_style' ] = 'text';
    $class[] = 'style--slider-nav' . $fn_params[ 'nav_style' ];
    
    if ( 'arrow' == $fn_params[ 'nav_style' ] ) {
        
        $slider_options[ 'prevText' ] = '<span class="slider-nav-square"><i class="fa fa-angle-left"></i></span>';
        $slider_options[ 'nextText' ] = '<span class="slider-nav-square"><i class="fa fa-angle-right"></i></span>';
        
    } else {
        $slider_options[ 'prevText' ] = '<i class="fa fa-chevron-left"></i>' . '<span>' . fox_word( 'previous' ) . '</span>';
        $slider_options[ 'nextText' ] = '<span>' . fox_word( 'next' ) . '</span>' . '<i class="fa fa-chevron-right"></i>';
    }
    
    /**
     * adjust params a bit
     */
    if ( ! isset( $fn_params[ 'title_size'] ) || ! $fn_params[ 'title_size' ] ) {
        $fn_params[ 'title_size' ] = 'large';
    }
    if ( ! isset( $fn_params[ 'align' ] ) || ! $fn_params[ 'align' ] ) {
        $fn_params[ 'align' ] = 'left';
    }
    
    $thumbnail_params = $fn_params;
    $thumbnail_params[ 'thumbnail_extra_class' ] .= ' slider-thumbnail';
    $thumbnail_params[ 'thumbnail' ] = 'custom';
    $thumbnail_params[ 'thumbnail_custom' ] = $fn_params[ 'slider_size' ];
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
                if ( isset( $fn_params[ 'align'] ) ) {
                    $post_class[] = 'post-slide-align-' . $fn_params[ 'align'];
                }
    
                if ( isset( $fn_params[ 'title_background' ] ) && $fn_params[ 'title_background' ] ) {
                    $post_class[] = 'style--title-has-background';
                }
    
                $meta_params = $fn_params;
                $meta_params[ 'extra_class' ] = 'slider-meta';

                ?>
                <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">

                    <?php fox43_thumbnail( $thumbnail_params ); ?>
                    
                    <div class="slider-body">

                        <div class="slider-table">

                            <div class="slider-cell">

                                <div class="post-content">

                                    <?php if ( $fn_params[ 'title_show' ] ) { ?>

                                    <div class="slider-header">
                                        
                                        <?php fox_post_title([
                                            'extra_class' => 'slider-title',
                                            'tag' => $fn_params[ 'title_tag' ],
                                            'size' => $fn_params[ 'title_size' ],
                                            'weight' => $fn_params[ 'title_weight' ],
                                            'text_transform' => $fn_params[ 'title_text_transform' ],
                                        ]); ?>

                                    </div><!-- .slider-header -->

                                    <?php } ?>

                                    <?php if ( $fn_params[ 'excerpt_show' ] ) { ?>

                                    <div class="slider-excerpt">

                                        <?php fox_post_meta( $meta_params ); ?>

                                        <?php fox_post_excerpt([
                                                'extra_class' => 'slider-excerpt-text',
                                                'exclude_class' => [ 'entry-excerpt' ],
                                                'length' => $fn_params[ 'excerpt_length' ],
                                                'color' => '#fff',
                                                'more' => $fn_params[ 'excerpt_more' ] ]); ?>

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
} 

/**
 * Blog Slider 1
 *
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_slider1( $fn_params, $query ) {
    
    $class = [
        'fox-flexslider',
        'modern-slider1',
        'style--slider-navcircle',
    ];
    
    $slider_options = [
        'slideshow' => true,
        'animationSpeed' => 1000,
        'slideshowSpeed' =>	5000,
        'easing' => 'easeOutCubic',
        'effect' => 'fade',
        'prevText' => '<span class="slider-nav-circle"><i class="feather-chevron-left"></i></span>',
        'nextText' => '<span class="slider-nav-circle"><i class="feather-chevron-right"></i></span>',
    ];
    
    $post_class = [ 'post-slide1' ];
    
    $thumbnail_params = [
        'thumbnail' => 'original',
        'thumbnail_extra_class' => 'post-slide1-thumbnail',
    ];
    
    /**
     * Text Box CSS
     */
    $text_class = [ 'post-slide1-text' ];
    $text_css = $background_css = [];
    if ( $fn_params[ 'slide_content_color' ] ) {
        $class[] = 'has-custom-color';
        $text_css[] = 'color:' . $fn_params[ 'slide_content_color' ];
    }
    if ( $fn_params[ 'slide_content_background' ] ) {
        $class[] = 'has-custom-background';
        $background_css[] = 'background-color:' . $fn_params[ 'slide_content_background' ];
        
        if ( '' !== $fn_params[ 'slide_content_background_opacity' ] ) {
            $background_css[] = 'opacity:' . $fn_params[ 'slide_content_background_opacity' ];
        }
    }
    
    $text_css = join( ';', $text_css );
    if ( $text_css ) {
        $text_css = ' style="' . esc_attr( $text_css ) . '"';
    }
    
    $background_css = join( ';', $background_css );
    if ( $background_css ) {
        $background_css = ' style="' . esc_attr( $background_css ) . '"';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $slider_options ); ?>'>
            
    <div class="flexslider">
        
        <ul class="slides">
            
            <?php while( $query->have_posts()) { $query->the_post(); ?>
            
            <li class="slide">
                
                <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">

                    <?php fox43_thumbnail( $thumbnail_params ); ?>
                    
                    <div class="post-slide1-text"<?php echo $text_css; ?>>
                        
                        <div class="post-slide1-content">
                            
                            <div class="post-slide1-main-content">
                            
                                <?php fox_post_categories([ 
                                    'extra_class' => 'standalone-categories post-slide1-categories'
                                ]); ?>

                                <?php fox_post_title([
                                    'extra_class' => 'post-slide1-title',
                                    'tag' => 'h2',
                                    'size' => 'medium',
                                    'weight' => '400',
                                ]); ?>

                                <?php fox_post_meta([
                                    'show_category' => false,
                                    'show_date' => true,
                                    'show_author' => false,
                                    'extra_class' => 'post-slide1-meta',
                                ]); ?>

                                <div class="post-slide1-button">
                                    <?php fox_btn([
                                        'url' => get_permalink(),
                                        'text' => fox_word( 'read_more' ),
                                        'style' => 'fill',
                                        'size' => 'small',
                                    ]); ?>
                                </div>
                                
                            </div><!-- .post-slide1-main-content -->
                            
                            <div class="post-slide1-background"<?php echo $background_css; ?>></div>
                            
                        </div><!-- .post-slide1-content -->
                    
                    </div><!-- .post-slide1-text -->
                    
                    <div class="post-slide1-overlay"></div>
                    <div class="post-slide1-height"></div>

                </article><!-- .post-slide1 -->
                
                <?php do_action( 'fox_after_render_post' ); // since 4.0 ?>
                
            </li>
            
            <?php } ?>
            
        </ul><!-- .slides -->
        
    </div><!-- .flexslider -->
    
    <div class="post-slide1-height"></div>
    
</div><!-- .fox-slider -->
    
<?php
}

/**
 * Blog Carousel
 * @since 4.4
 * @todo
 * ------------------------------------------------------------------------------------------------
 */
function fox44_blog_carousel( $fn_params, $query ) {
    
    $class = [
        'wi-carousel',
        'fox-carousel',
        'blog-carousel',
    ];
    
    $carousel_options = [
        'slideshow' => true,
        'animationSpeed' => 1000,
        'slideshowSpeed' =>	5000,
        'easing' => 'easeOutCubic',
        'effect' => 'fade',
        'prevText' => '<span class="slider-nav-circle"><i class="feather-chevron-left"></i></span>',
        'nextText' => '<span class="slider-nav-circle"><i class="feather-chevron-right"></i></span>',
    ];
    
    $post_class = [ 'post-carousel' ];
    
    $thumbnail_params = [
        'thumbnail' => 'large',
    ];
    
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $carousel_options ); ?>'>
    
    <div class="wi-slick fox-slick">
        
        <?php while( $query->have_posts() ) { $query->the_post(); ?>
            
        <div class="carousel-item">
            
            <article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">

                <?php fox43_thumbnail( $thumbnail_params ); ?>

            </article><!-- .post-slider -->
                
            <?php do_action( 'fox_after_render_post' ); // since 4.0 ?>
            
        </div><!-- .carousel-item -->

        <?php } // endwhile ?>

    </div><!-- .wi-slick -->
    
</div>

<?php
    
}

/**
 * $components is an array of components by multicheckbox control
 * @since 4.4
 * ------------------------------------------------------------------------------------------------
 */
function fox44_component_to_show( $components ) {
    
    $show_params = [];
    
    $coms = explode( ',', $components );
    $coms = array_map( 'trim', $coms );
    $possible_components = [
        'thumbnail',
        'title',
        'date',
        'category',
        'author',
        'author_avatar',
        'excerpt', 
        'excerpt_more', 
        'reading_time',
        'comment_link',
        'view',
    ];
    foreach ( $possible_components as $com ) {
        $show_params[ $com . '_show' ] = in_array( $com, $coms );
    }
    $show_params[ 'excerpt_more' ] = in_array( 'excerpt_more', $coms );
    
    return $show_params;
    
}

/**
 * returns array param_name => std value
 * all possible params from all possible layouts
 *
 * this is kinda version
 */
function fox44_customizer_param_defaults() {
    
    $std = [];
    $options = [];
    
    include get_template_directory() . '/inc/customizer/blog.php';
    
    $section = '';
    foreach ( $options as $id => $opt ) {
        
        if ( isset ( $opt[ 'section' ] ) ) $section = $opt[ 'section' ];
        if ( is_numeric( $id ) ) {
            continue;
        }
        
        if ( in_array( $section, [ 
            'blog_grid',
            'blog_masonry',
            'blog_list',
            'blog_standard',
            'blog_newspaper',
            'blog_vertical',
            'blog_big',
            'blog_slider',
            'blog_group_1',
            'blog_group_2',
        ] ) ) {
            
            $std[ $id ] = isset( $opt[ 'std' ] ) ? $opt[ 'std' ] : '';
            
        }
        
    }
    
    /**
     * additional for standard case
     */
    $std[ 'single_meta_template' ] = '1';
    
    return $std;
    
}

/**
 * this is the default for the worst case
 * when all logic falls, it just lists all necessary keys
 * it's kinda list all possible keys
 * it's often redundant
 */
function fox44_worst_defaults() {
    
    $worst_defaults = [
        
        'pagination' => '',
        
        'layout' => '',
        'column' => '',
        'item_spacing' => '',
        'color' => '',
        'big_first_post' => true, // for masonry
        'list_spacing' => '',
        
        'extra_class' => '',
        
        // general
        'align' => '',
        'item_template' => '',
        'live' => true,
        
        // thumbnail
        'thumbnail_show' => true,
        'thumbnail' => 'landscape',
        'thumbnail_custom' => '',
        'thumbnail_placeholder' => true,
        'thumbnail_placeholder_id' => '',
        'thumbnail_shape' => '',
        'thumbnail_hover' => '',
        'thumbnail_hover_logo' => '',
        'thumbnail_hover_logo_width' => '',
        'thumbnail_showing_effect' => '',
        'thumbnail_format_indicator' => '',
        'thumbnail_index' => '',
        'thumbnail_view' => '',
        'thumbnail_review_score' => '',
        'thumbnail_extra_class' => '',
        
        // title
        'title_show' => true,
        'title_tag' => '',
        'title_size' => '',
        'title_weight' => '',
        'title_text_transform' => '',
        
        // excerpt
        'excerpt_show' => true,
        'excerpt_length' => 22,
        'excerpt_size' => '',
        'excerpt_color' => '',
        'excerpt_more' => '',
        'excerpt_more_style' => '',
        'excerpt_more_text' => '',
        
        // date
        'date_show' => true,
        
        // category
        'category_show' => true,
        
        // author
        'author_show' => '',
        'author_avatar_show' => '',
        
        // view count
        'view_show' => '',
        
        // comment link
        'comment_link_show' => '',
        
        // reading time
        'reading_time_show' => '',
        
        // masonry option
        'big_first_post' => true,
        
        // list option
        'list_sep' => true,
        'list_sep_color' => '',
        'thumbnail_position' => 'left',
        'thumbnail_width' => '',
        'list_mobile_layout' => 'grid',
        'list_valign' => 'top',
        
        // slider options
        'effect' => 'slide',
        'nav_style' => 'text',
        'slider_size' => '1020x510',
        'title_background' => false,
        
        // vertical post options
        'thumbnail_type' => 'simple',
        
        // standard
        'header_align' => '',
        'share_show' => '',
        'related_show' => '',
        'content_excerpt' => '',
        'thumbnail_header_order' => '',
        
        // slider-1
        'slide_content_color' => '',
        'slide_content_background' => '',
        'slide_content_background_opacity' => '',
    ];
    
    return $worst_defaults;
    
}

/**
 * return array of default params for $loop
 * this is default params both by design and from customizer
 */
function fox44_default_params( $loop ) {
    
    $customizer_defaults = fox44_customizer_param_defaults( $loop );
    $std = fox44_worst_defaults();
    
    /**
     * $adapter is an array of canonical_property_name => customizer_id
     */
    $adapter = [];
    
    $thumbnail_adapter = [
        'thumbnail' => 'blog_grid_thumbnail',
        'thumbnail_custom' => 'blog_grid_thumbnail_custom',
        'thumbnail_placeholder' => 'blog_grid_thumbnail_placeholder',
        'thumbnail_placeholder_id' => 'blog_grid_default_thumbnail',
        'thumbnail_shape' => 'blog_grid_thumbnail_shape',
        'thumbnail_hover' => 'blog_grid_thumbnail_hover_effect',
        'thumbnail_hover_logo' => 'blog_grid_thumbnail_hover_logo',
        'thumbnail_hover_logo_width' => 'blog_grid_thumbnail_hover_logo_width',
        'thumbnail_showing_effect' => 'blog_grid_thumbnail_showing_effect',
        'thumbnail_format_indicator' => 'blog_grid_format_indicator',
        'thumbnail_index' => 'blog_grid_thumbnail_index',
        'thumbnail_view' => 'blog_grid_thumbnail_view',
        'thumbnail_review_score' => 'blog_grid_thumbnail_review_score',
    ];
    
    $excerpt_adapter = [
        'excerpt_show' => 'blog_grid_show_excerpt',
        'excerpt_length' => 'blog_grid_excerpt_length',
        'excerpt_size' => 'blog_grid_excerpt_size',
        'excerpt_color' => 'blog_grid_excerpt_color',
        'excerpt_more' => 'blog_grid_excerpt_more',
        'excerpt_more_style' => 'blog_grid_excerpt_more_style',
        'excerpt_more_text' => 'blog_grid_excerpt_more_text',
    ];
    
    $meta_adapter = [
        'date_show' => 'blog_grid_show_date',
        'category_show' => 'blog_grid_show_category',
        'author_show' => 'blog_grid_show_author',
        'author_avatar_show' => 'blog_grid_show_author_avatar',
        'view_show' => 'blog_grid_show_view',
        'comment_link_show' => 'blog_grid_show_comment_link',
        'reading_time_show' => 'blog_grid_reading_time',  
    ];
    
    $title_adapter = [
        'title_show' => 'blog_grid_show_title',
        'title_tag' => 'blog_grid_title_tag',
        'title_size' => 'blog_grid_title_size',
        'title_weight' => 'blog_grid_title_weight',
        'title_text_transform' => 'blog_grid_title_text_transform',
    ];
    
    switch( $loop ) {
            
        case 'grid' :
        case 'masonry' :
        case 'list' :
            
            $adapter = [
                
                'item_spacing' => 'blog_grid_item_spacing',
                
                'item_template' => 'blog_grid_item_template',
                
                'align' => 'blog_grid_item_align',
                
                'thumbnail_show' => 'blog_grid_show_thumbnail',
                
                'item_border' => 'blog_grid_item_border',
                'item_border_color' => 'blog_grid_item_border_color',
                
            ];
            
            $adapter = array_merge( $adapter, $thumbnail_adapter, $title_adapter, $excerpt_adapter, $meta_adapter );
            
            if ( 'masonry' == $loop ) {
                $adapter[ 'big_first_post' ] = 'blog_grid_big_first_post';
                unset( $adapter[ 'thumbnail' ] ); // who needs
                unset( $adapter[ 'thumbnail_custom' ] ); // who needs
            }
            
            if ( 'list' == $loop ) {
                $list_adapter = [
                    'thumbnail_position' => 'blog_grid_thumbnail_position',
                    'list_spacing' => 'blog_grid_list_spacing',
                    'list_sep' => 'blog_grid_list_sep',
                    'list_sep_color' => 'blog_grid_list_sep_color',
                    'thumbnail_width' => 'blog_grid_thumbnail_width',
                    'list_mobile_layout' => 'blog_grid_list_mobile_layout',
                    'list_valign' => 'blog_grid_list_valign',
                ];
                $adapter = array_merge( $adapter, $list_adapter );
            }
            
            break;
            
        case 'standard':
            
            $adapter = [
                'content_excerpt' => 'blog_standard_content_excerpt',
                'thumbnail_type' => 'blog_standard_thumbnail_type',
                'header_align' => 'blog_standard_header_align',
                'share_show' => 'blog_standard_show_share',
                'related_show' => 'blog_standard_show_related',
                'thumbnail_header_order' => 'blog_standard_thumbnail_header_order',
            ];
            
            $adapter = array_merge( $adapter, $thumbnail_adapter, $excerpt_adapter, $meta_adapter );
            $adapter[ 'excerpt_length' ] = 'excerpt_length';
            
            $adapter[ 'item_template' ] = 'single_meta_template';
            
            break;
            
        case 'newspaper':
            
            $adapter = [
                'content_excerpt' => 'post_newspaper_content_excerpt',
                'thumbnail_type' => 'post_newspaper_thumbnail_type',
                'header_align' => 'post_newspaper_header_align',
                'share_show' => 'post_newspaper_show_share',
                'related_show' => 'post_newspaper_show_related',
            ];
            
            $adapter = array_merge( $adapter, $thumbnail_adapter, $excerpt_adapter, $meta_adapter );
            
            $adapter[ 'excerpt_length' ] = 'excerpt_length';
            
            $std[ 'item_template' ] = 1; // fixed
            
            break;    
            
        case 'vertical':
            
            $adapter = [
                'thumbnail_type' => 'vertical_post_thumbnail_type',
                'thumbnail_position' => 'vertical_post_thumbnail_position',
                'item_template' => 'blog_grid_item_template',
            ];
            
            $adapter = array_merge( $adapter, $thumbnail_adapter, $title_adapter, $excerpt_adapter, $meta_adapter );
            
            $adapter[ 'excerpt_size' ] = 'vertical_post_excerpt_size';
            
            // redundant
            unset( $adapter[ 'thumbnail' ] );
            unset( $adapter[ 'thumbnail_custom' ] );
            unset( $adapter[ 'thumbnail_placeholder' ] );
            // unset( $adapter[ 'thumbnail_shape' ] ); // why redundant?s
            unset( $adapter[ 'title_size' ] );
            
            break;
            
        case 'big':
            
            $adapter = [
                'content_excerpt' => 'big_post_content_excerpt',
            ];
            
            // also other options that shouldn't be inherit from grid options
            // ie. those options can be changed by overriding them, not by customizer defaults
            $std[ 'date_show' ] = true;
            $std[ 'category_show' ] = true;
            $std[ 'author_show' ] = false;
            $std[ 'align' ] = 'left';
            $std[ 'excerpt_show' ] = true;
            $std[ 'excerpt_length' ] = -1;
            $std[ 'excerpt_more' ] = true;
            $std[ 'excerpt_size' ] = 'medium';
            $std[ 'title_size' ] = 'extra';
            
            // no hover yet
            $std[ 'thumbnail_hover' ] = '';
            
            break;
            
        case 'slider':
            
            $adapter = [
                'effect' => 'post_slider_effect',
                'nav_style' => 'post_slider_nav_style',
                'slider_size' => 'post_slider_size',
                'title_background' => 'post_slider_title_background',
            ];
            
            $adapter = array_merge( $adapter, $title_adapter, $excerpt_adapter, $meta_adapter );
            
            $std[ 'title_size' ] = 'large';
            $std[ 'excerpt_size' ] = 'normal';
            $std[ 'align' ] = 'left';
            
            break;
            
        case 'slider-1' :
            
            $adapter = [
                // 'slide_content_background' => 'slide_content_background',
            ];
            
            break;
            
        case 'group-1' :
            
            $adapter = [
                'big_post_position' => 'post_group1_big_post_position',
                'big_post_ratio' => 'post_group1_big_post_ratio',
                'sep_border' => 'post_group1_sep_border',
                'sep_border_color' => 'post_group1_sep_border_color',
                
                'big_post_components' => 'post_group1_big_post_components',
                'big_post_align' => 'post_group1_big_post_align',
                'big_post_item_template' => 'post_group1_big_post_item_template',
                'big_post_excerpt_length' => 'post_group1_big_post_excerpt_length',
                'big_post_excerpt_more_text' => 'post_group1_big_post_excerpt_more_text',
                'big_post_excerpt_more_style' => 'post_group1_big_post_excerpt_more_style',
                
                'small_post_components' => 'post_group1_small_post_components',
                'small_post_item_template' => 'post_group1_small_post_item_template',
                'small_post_list_spacing' => 'post_group1_small_post_list_spacing',
            ];
            
            break;
            
        case 'group-2' :
            
            $adapter = [
                'columns_order' => 'post_group2_columns_order',
                'sep_border' => 'post_group2_sep_border',
                'sep_border_color' => 'post_group2_sep_border_color',
                
                'big_post_components' => 'post_group2_big_post_components',
                'big_post_align' => 'post_group2_big_post_align',
                'big_post_item_template' => 'post_group2_big_post_item_template',
                'big_post_excerpt_length' => 'post_group2_big_post_excerpt_length',
                'big_post_excerpt_more_text' => 'post_group2_big_post_excerpt_more_text',
                'big_post_excerpt_more_style' => 'post_group2_big_post_excerpt_more_style',
                
                'medium_post_components' => 'post_group2_medium_post_components',
                'medium_post_item_template' => 'post_group2_medium_post_item_template',
                'medium_post_thumbnail' => 'post_group2_medium_post_thumbnail',
                'medium_post_excerpt_length' => 'post_group2_medium_post_excerpt_length',
                
                'small_post_components' => 'post_group2_small_post_components',
                'small_post_item_template' => 'post_group2_small_post_item_template',
                'small_post_excerpt_length' => 'post_group2_small_post_excerpt_length',
            ];
            
            break;
            
        case 'group1-big':
        case 'group1-small':
        case 'group2-big':
        case 'group2-medium':
        case 'group2-small':    
            
            $adapter = $thumbnail_adapter;
            break;
            
        default :
            
            break;
            
    }
    
    foreach ( $adapter as $canonical_id => $customizer_id ) {
        $std[ $canonical_id ] = get_theme_mod( 'wi_' . $customizer_id, $customizer_defaults[ $customizer_id ] );

        if ( 'true' == $std[ $canonical_id ] ) {
            $std[ $canonical_id ] = true;
        } elseif ( 'false' == $std[ $canonical_id ] ) {
            $std[ $canonical_id ] = false;
        }

    }
    
    return $std;
    
}