<?php
if ( ! function_exists( 'fox_single_thumbnail' ) ) :
/**
 * Single Thumbnail
 * @since 4.0
 */
function fox_single_thumbnail( $post_type = 'post' ) {
    
    if ( ! fox_show( 'thumbnail', $post_type ) ) return;
    
    $thumbnail = fox_get_advanced_thumbnail();
    
    if ( ! $thumbnail ) return;
    ?>

<div class="thumbnail-wrapper single-big-section single-big-section-thumbnail">
    
    <div class="thumbnail-container">
        
        <div class="container">
            
            <div class="thumbnail-main">

                <?php echo $thumbnail; ?>
                
            </div><!-- .thumbnail-main -->

        </div><!-- .container -->
        
    </div><!-- .thumbnail-container -->
    
</div><!-- .thumbnail-wrapper -->

<?php
}
endif;

if ( ! function_exists( 'fox_single_header' ) ) :
/**
 * Single Header
 * @since 4.0
 */
function fox_single_header() {
    
    $post_type = get_post_type();
    
    if ( ! fox_show( 'post_header', $post_type ) ) return;
    
    ?>
<header class="single-header post-header entry-header single-big-section" itemscope itemtype="https://schema.org/WPHeader">
    
    <div class="container">
        
        <?php fox_blog_header( $post_type ); ?>
    
    </div><!-- .container -->
    
</header><!-- .single-header -->
    <?php
}
endif;

// deprecated since 4.3
// add_action( 'after_fox_blog_header', 'fox_append_social_share_blog_header', 10 );
/**
 * append social share to post header if possible
 * @since 4.2
 */
function fox_append_social_share_blog_header( $post_type ) {

    if ( 'page' == $post_type ) {
        $share_positions = get_theme_mod( 'wi_page_share_positions', 'after' );
    } else {
        $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
    }
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    $prefix = 'post' == $post_type ? 'wi_single_' : 'wi_' . $post_type . '_';
    $align= get_theme_mod( $prefix . 'meta_align', 'center' );
    
    if ( in_array( 'before', $share_positions ) ) {
        fox_share([
            'extra_class' => 'align-' . $align
        ]);
    } elseif( in_array( 'side', $share_positions ) ) {
        fox_share([
            'extra_class' => 'show_on_mobile align-' . $align,
        ]);
    }
    
}

if ( ! function_exists( 'fox_single_title' ) ) :
/**
 * Single Title
 * @since 4.0
 */
function fox_single_title( $post_type = 'post' ) {
    ?>
    <h1 class="post-title single-title post-header-section" itemprop="header"><?php the_title(); ?></h1>
<?php
    }
endif;

if ( ! function_exists( 'fox_single_body' ) ) :
/**
 * Single Body
 * $post_type is post type, it is often 'post', 'page'
 * @since 4.0
 */
function fox_single_body( $post_type = 'post' ) {
    
    // side share
    $cl = [ 'single-section single-main-content' ];
    
    if ( 'page' == $post_type ) {
        $share_positions = get_theme_mod( 'wi_page_share_positions', 'after' );
    } else {
        $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
    }
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    if ( in_array( 'side', $share_positions ) ) {
        $cl[] = 'side-share';
    }
    ?>

<div class="single-body">
    
    <div class="<?php echo esc_attr( join( ' ', $cl ) ); ?>">
        
        <?php if ( in_array( 'side', $share_positions ) ) {
            fox_share([
                'extra_class' => 'vshare',
                'style' => 'custom',
            ]);
        } ?>
        
        <div class="container entry-container">
            
            <?php do_action( 'fox_before_entry_content', $post_type ); // since 4.0 ?>
            
            <div class="dropcap-content columnable-content entry-content">
                
                <?php the_content(); fox_page_links(); ?>

            </div><!-- .entry-content -->
            
            <?php do_action( 'fox_after_entry_content', $post_type ); // since 4.0 ?>
            
        </div><!-- .container -->
    
    </div><!-- .single-section -->
    
    <?php do_action( 'fox_after_single_content', $post_type ); ?>

</div><!-- .single-body -->

    <?php
}
endif;

// add_filter( 'post_class', 'fox_single_post_class' );
// deprecated since 4.3
/**
 * Add Post Class for single post
 * @since 4.0
 */
function fox_single_post_class( $classes ) {
    
    $post_type = get_post_type();
    
    if ( is_singular( $post_type ) ) {
        
        $prefix = 'post' == $post_type ? 'single_' : $post_type . '_';
        
        $classes[] = 'wi-content';
        
        // post style
        $style = fox_get_option( 'style', $prefix );
        $classes[] = 'single-style-' . $style;
        if ( 4 == $style || 5 == $style ) {
            $classes[] = 'single-style-hero';
        }

        // thumbnail stretch
        $stretch = fox_get_option( 'thumbnail_stretch', $prefix );
        $classes[] = 'thumbnail-' . $stretch;

        // content width
        $content_width = fox_get_option( 'content_width', $prefix );
        $classes[] = 'main-content-' . $content_width;

        // content image stretch
        // only narrow content and no sidebar
        if ( 'narrow' == $content_width && 'no-sidebar' == fox_sidebar_state() ) {

            $stretch = fox_get_option( 'content_image_stretch', $prefix );

            if ( 'stretch-bigger' == $stretch || 'stretch-full' == $stretch ) {
                $classes[] = 'content-image-stretch';
            }

            $classes[] = 'content-image-' . $stretch;

        }
        
    }
    
    return $classes;
    
}

// add_action( 'fox_after_single_content', 'fox_after_single_content' );
/**
 * After single post content / sections and organizing
 * @since 4.0
 */

function fox_after_single_content( $post_type = 'post' ) {
    
    $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    if ( in_array( 'after', $share_positions ) ) {
        fox_single_share( $post_type );
    }
    
    if ( 'page' != $post_type ) {
        fox_single_tags( $post_type );
        fox_single_related( $post_type );
        fox_single_authorbox( $post_type );
    }
    
    fox_single_comment( $post_type );
     
}

add_action( 'fox_single_bottom', 'fox_single_bottom' );
/**
 * After single post content / sections and organizing
 * @since 4.0
 */
function fox_single_bottom( $params ) {
    
    // since 4.3
    $related_position = get_theme_mod( 'wi_single_related_position', 'after_main_content' );
    if ( 'after_container' != $related_position ) {
        $related_position = 'after_main_content';
    }
    
    /**
     * RELATED POSTS
     */
    if ( $params[ 'related_show' ] && 'after_container' == $related_position ) {
        
        $defaults = [
            'number' => 3,
            'source' => 'tag',
            'order' => 'desc',
            'orderby' => 'date',
            'layout' => 'grid-3',
            
            'date_show' => true,
            'excerpt_show' => false,
            'item_spacing' => 'small',
            'item_template' => 2,
        ];
        $prefix = 'single_related';
        
        fox_single_related( $prefix, $defaults, 'single-big-section single-bottom-section single-big-section-related' );
        
    }
    
    if ( $params[ 'nav_show' ] ) {
        
        fox_single_navigation( $params );
        
    }
    
    if ( $params[ 'bottom_posts_show' ] ) {
        
        fox_single_bottom_posts( $params );
        
    }
     
}

/* TEMPLATE FUNCTIONS
------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'fox_single_share' ) ) :
/**
 * Fox Single Share
 * @since 4.0
 */
function fox_single_share( $post_type = 'post' ) {
    
    if ( fox_show( 'share', $post_type ) ) { ?>

<div class="single-component single-component-share">
    
    <?php fox_share(); ?>

</div><!-- .single-share-section -->
    <?php
    }
    
}
endif;

if ( ! function_exists( 'fox_single_tags' ) ) :
/**
 * Fox Single Tags
 *
 * @since 4.0
 * @modified since 4.3
 */
function fox_single_tags() {
    
    $tags = get_the_tag_list( '<ul><li>','</li><li>','</li></ul>' );
    if ( ! $tags ) return;
    
    ?>
<div class="single-component single-component-tag single-component-tag">
    
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

if ( ! function_exists( 'fox_related_posts' ) ) :
/**
 * Related posts
 * Just display the blog part, nothing about the markup around
 * 
 * @since 4.3
 */
function fox_related_posts( $prefix = '', $defaults = [] ) {
    
    if ( empty( $defaults ) ) {
        $defaults = [
            'number' => 3,
            'source' => 'tag',
            'order' => 'desc',
            'orderby' => 'date',
            'layout' => 'grid-3',
            
            'date_show' => false,
            'list_sep' => false,
        ];
    }
    
    if ( ! $prefix ) {
        $prefix = 'single_related';
    }
    
    $query_obj = fox_related_query( $prefix, $defaults );
    if ( ! $query_obj ) {
        wp_reset_query();
        return;
    }
    
    $params = [];
    
    $layout = get_theme_mod( 'wi_' . $prefix . '_layout', $defaults[ 'layout' ] );
    $loop = fox_get_loop_from_layout( $layout );
    $column = fox_get_column_from_layout( $layout );
    
    $params[ 'layout' ] = $layout;
    
    if ( 2 == $column ) {
        $params[ 'title_size' ] = 'normal';
        $params[ 'item_spacing' ] = 'normal';
    } elseif ( 3 == $column ) {
        $params[ 'title_size' ] = 'small';
    } elseif ( 4 == $column ) {
        $params[ 'title_size' ] = 'tiny';
        $params[ 'item_spacing' ] = 'small';
    } elseif ( 5 == $column ) {
        $params[ 'title_size' ] = 'tiny';
    }
    
    // get more args from the defaults
    $params = wp_parse_args( $params, $defaults );
    
    // options that 100% false
    $default_params = [];
    $default_params[ 'pagination' ] = false;
    $default_params[ 'live' ] = false;
    $default_params[ 'item_template' ] = 1;
    $default_params[ 'item_spacing' ] = 'normal';
    
    $default_params[ 'thumbnail_show' ] = true;
    $default_params[ 'thumbnail_format_indicator' ] = true;
    $default_params[ 'thumbnail_index' ] = false;
    
    $default_params[ 'title_show' ] = true;
    $default_params[ 'title_tag' ] = 'h3';
    $default_params[ 'category_show' ] = false;
    $default_params[ 'author_show' ] = false;
    $default_params[ 'date_show' ] = false;
    $default_params[ 'reading_time_show' ] = false;
    $default_params[ 'view_show' ] = false;
    $default_params[ 'comment_link_show' ] = false;
    $default_params[ 'excerpt_show' ] = false;
    $default_params[ 'excerpt_more' ] = false;
    
    $params = wp_parse_args( $params, $default_params );
    
    fox44_blog( $layout, $params, $query_obj );
    
}
endif;

if ( ! function_exists( 'fox_single_related' ) ) :
/**
 * Fox Single Related Posts
 * @since 4.0
 */
function fox_single_related( $prefix = '', $defaults = [], $extra_class = '' ) {
    
    $layout = get_theme_mod( 'wi_single_related_layout' );
    if ( 'list' == $layout ) {
        $defaults[ 'title_size' ] = 'normal';
        $defaults[ 'excerpt_show' ] = true;
        $defaults[ 'excerpt_length' ] = 24;
        $defaults[ 'excerpt_more' ] = false;
        $defaults[ 'excerpt_size' ] = 'normal';
        $defaults[ 'list_sep' ] = false;
        $defaults[ 'list_spacing' ] = 'normal';
    }
    
    ob_start();
    fox_related_posts( $prefix, $defaults );
    $blog = ob_get_clean();
    
    if( ! $blog ) {
        return;
    }
    
    $class = [ 'single-related-wrapper' ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <div class="fox-related-posts">
        
        <div class="container">

            <h3 class="single-heading related-label related-heading">
                <span><?php echo fox_word( 'related' ); ?></span>
            </h3>

            <?php echo $blog; ?>
            
        </div><!-- .container -->

    </div><!-- .fox-related-posts -->
    
</div><!-- .single-component -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_single_authorbox' ) ) :
/**
 * Fox Single Author Box
 *
 * @since 4.0
 * @modified since 4.3
 */
function fox_single_authorbox() {
    
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

<div class="single-component single-component-authorbox">
    
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

</div><!-- .single-authorbox-section -->
    <?php
    
}
endif;

if ( ! function_exists( 'fox_single_comment' ) ) :
/**
 * Fox Single Comment
 * @since 4.0
 */
function fox_single_comment() {
    
    ?>

<div class="single-component single-component-comment">
    
    <?php fox_post_comment(); ?>

</div><!-- .single-component-comment -->
    <?php
    
}
endif;

if ( ! function_exists( 'fox_single_navigation' ) ) :
/**
 * Fox Single Navigation
 * @since 4.0
 */
function fox_single_navigation( $params = [] ) {
    
    if ( fox_autoload() ) return; ?>

<div class="single-big-section single-bottom-section single-navigation-section">
    
    <?php fox_post_navigation( $params ); ?>

</div><!-- .single-navigation-section -->
    <?php
    
}
endif;

if ( ! function_exists( 'fox_single_bottom_posts' ) ) :
/**
 * Fox Single Bottom Posts
 * @since 4.0
 * @modified since 4.3
 */
function fox_single_bottom_posts() {
    
    // since 4.0
    if ( fox_autoload() ) return;
    
    $prefix = 'single_bottom_posts';
    $defaults = [
        'number' => 5,
        'source' => 'category',
        'orderby' => 'date',
        'order' => 'desc',
        'layout' => 'grid-5',
        
        'item_spacing' => 'small',
        'date_show' => false,
        'excerpt_show' => ( 'true' == get_theme_mod( 'wi_single_bottom_posts_excerpt', 'true' ) ),
        'excerpt_length' => 16,
    ];
    
    $source = get_theme_mod( 'wi_single_bottom_posts_source', 'category' );
    $name = '';
    if ( 'author' == $source ) {
        
        $name = get_the_author();
        
    } elseif ( 'tag' == $source ) {
        
        $name = esc_html__( 'Same Tags', 'wi' );
        
    } elseif ( 'date' == $source ) {
        
        // just nothing
        $name = esc_html__( 'Blog', 'wi' );
        
    } elseif ( 'featured' == $source ) {
        
        $name = esc_html__( 'Featured Posts', 'wi' );
        
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
        
        $name = get_cat_name( $cat );
    
    }
    
    ob_start();
    fox_related_posts( $prefix, $defaults );
    $blog = ob_get_clean();
    
    if ( ! $blog ) return;

?>

<div class="single-big-section single-bottom-section single-bottom-posts-section">
    
    <div class="fox-bottom-posts">
    
        <div class="container">

            <h3 id="posts-small-heading" class="bottom-posts-heading single-heading">

                <span><?php printf( fox_word( 'latest' ), $name ); ?></span>

            </h3>

            <?php echo $blog; ?>

        </div><!-- .container -->

    </div><!-- .fox-bottom-posts -->

</div><!-- .single-bottom-posts-section -->
    <?php
    
}
endif;

add_action( 'fox_single_bottom', 'fox_single_sidedock' );
/**
 * Single Side Dock Post
 * @since 4.0
 */
function fox_single_sidedock( $params ) {
    
    if ( ! $params[ 'side_dock_show' ] ) return;
    
    // disable on footer
    if ( ! apply_filters( 'fox_show_footer', true ) ) return;
    
    if ( ! is_single() ) return;
    
    // disable on autoload
    if ( fox_autoload() ) return;
    
    $prefix = 'single_side_dock';
    $defaults = [
        'number' => 2,
        'source' => 'tag',
        'orderby' => 'date',
        'order' => 'desc',
        
        'layout' => 'list',
        'thumbnail' => 'thumbnail',
        'thumbnail_extra_class' => 'post-dock-thumbnail',
        'thumbnail_position' => 'left',
        'thumbnail_format_indicator' => false,
        
        'date_show' => false,
        
        'header_extra_class' => 'post-dock-header',
        
        'title_size' => 'tiny',
        'title_extra_class' => 'post-dock-title',
        
        'excerpt_show' => false, // false since 4.3
        'excerpt_extra_class' => 'post-dock-excerpt',
        'excerpt_length' => 10,
        'excerpt_more' => false,
        'excerpt_size' => 'small',
        
        'extra_class' => 'post-dock',
        'list_sep' => false,
        'list_valign' => 'top',
        'list_spacing' => 'small',
        'list_mobile_layout' => 'list',
    ];
    
    ob_start();
    fox_related_posts( $prefix, $defaults );
    $blog = ob_get_clean();
    
    if ( ! $blog ) {
        return;
    }
    
    $class = [ 'content-dock', 'sliding-box' ];
    
    // since 4.3
    $orientation = get_theme_mod( 'wi_single_side_dock_orientation', 'up' );
    $class[] = 'sliding-' . $orientation;
    
    ?>

<aside id="content-dock" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <h3 class="dock-title widget-title"><?php echo fox_word( 'related' ); ?></h3>
    
    <div class="dock-posts">
        
        <?php echo $blog; ?>
        
    </div><!-- .dock-posts -->

    <button class="close">
        <i class="feather-x"></i>
    </button>

</aside><!-- #content-dock -->
    
<?php
}

if ( ! function_exists( 'fox_sponsored_row' ) ) :
/**
 * Sponsored Post
 * @since 4.2
 */
add_action( 'fox_before_entry_content', 'fox_sponsored_row', 50 );
function fox_sponsored_row() {
    
    if ( 'true' != get_post_meta( get_the_ID(), '_wi_sponsored', true ) ) return;
    
    $open = $close = '';
    $url = get_post_meta( get_the_ID(), '_wi_sponsor_url', true );
    if ( $url ) {
        $open = '<a href="' . esc_url( $url ) . '" target="_blank">';
        $close = '</a>';
    }
    $name = get_post_meta( get_the_ID(), '_wi_sponsor_name', true );
    
    $label = get_post_meta( get_the_ID(), '_wi_sponsor_label', true );
    if ( ! $label ) {
        $label = fox_word( 'sponsored' );
    }
    ?>

<div class="sponsor-row single-component">
    
    <?php if ( $label ) { ?>
    <div class="sponsor-label"><?php echo $label; ?></div>
    <?php } ?>
    
    <div class="sponsor-meta">
        
        <?php $img_id = get_post_meta( get_the_ID(), '_wi_sponsor_image', true ); if ( $img_id ) {
            $img = wp_get_attachment_image( $img_id, 'full' );
        
        $sponsor_image_style = '';
        if ( $sponsor_image_width = get_post_meta( get_the_ID(), '_wi_sponsor_image_width', true ) )  {
            if ( is_numeric( $sponsor_image_width ) ) $sponsor_image_width .= 'px';
            $sponsor_image_style = ' style="width:' . esc_attr( $sponsor_image_width ). '"';
        }
        ?>
        <figure class="sponsor-image"<?php echo $sponsor_image_style; ?>>
            
            <?php echo $open; ?>
            
            <?php echo $img; ?>
            
            <?php echo $close; ?>
            
        </figure>
        <?php } ?>
        
        <?php if ( $name ) { ?>
        <span class="sponsor-name"><?php echo $open . $name . $close ; ?></span>
        <?php } ?>
        
    </div>

</div><!-- .sponsor-row -->

<?php
    
}
endif;