<?php
/**
 * Hero Header
 * since 4.3
 */
function fox43_hero_header() {
    
    ?>
<div class="hero-header">
    
    <div class="container">
        
        <div class="hero-main-header narrow-area">
    
            <?php
            fox_post_categories([ 
                'extra_class' => 'standalone-categories'
            ]);
            echo fox_format( '<h1 class="post-title post-item-title hero-title">{}</h1>', get_the_title() );
            echo fox_get_subtitle();

            ?>
            
        </div><!-- .narrow-area -->
        
    </div><!-- .container -->
        
</div><!-- .hero-header -->

    <?php
    
}

if ( ! function_exists( 'fox43_single_option' ) ) :
/**
 * get single property from 2 layers: single post first, then global prop in Customizer
 *
 * @since 4.3
 */
function fox43_single_option( $prop, $std = '' ) {
    
    $get = get_post_meta( get_the_ID(), '_wi_' . $prop, true );
    if ( ! $get ) {
        $get = get_theme_mod( 'wi_single_' . $prop, $std );
    }
    
    return $get;
    
}
endif;

if ( ! function_exists( 'fox43_page_option' ) ) :
/**
 * @since 4.3
 */
function fox43_page_option( $prop, $std = '' ) {
    
    $get = get_post_meta( get_the_ID(), '_wi_' . $prop, true );
    if ( ! $get ) {
        $get = get_theme_mod( 'wi_page_' . $prop, $std );
    }
    
    return $get;
    
}
endif;

if ( ! function_exists( 'fox43_single_header' ) ) :
/**
 * Single Thumbnail
 *
 * @since 4.3
 */
function fox43_single_header( $params ) {
    
    $params = wp_parse_args( $params, [
        
        'style' => '1',
        'sidebar_state' => 'right',
        'thumbnail_stretch' => 'stretch-none',
        'content_width' => 'full',
        'image_stretch' => 'stretch-none',
        'column_layout' => 1,

        'header_align' => 'center',
        'header_item_template' => '1',
        
    ] );
    
    extract( $params );
    
    if ( ! $post_header_show ) {
        return;
    }
    
    // legacy
    $class = [
        'single-header',
        'post-header',
        'entry-header',
    ];
    
    // depending on the layout, it'll be a section or a big-section
    if ( '2' == $style ) {
        $class[] = 'single-big-section';
    } elseif ( '1' == $style || '1b' == $style || '3' == $style ) {
        $class[] = 'single-section';
    }
    
    $main_class = [
        'header-main'
    ];
    
    /**
     * align
     */
    if ( 'left' != $header_align && 'right' != $header_align ) {
        $header_align = 'center';
    }
    $class[] = 'align-' . $header_align;
    
    /**
     * content narrow
     */
    $narrow = false;
    if ( 'narrow' == $content_width && 'no-sidebar' == $sidebar_state ) {
        $narrow = true;
    }
    
    if ( $narrow ) {
        $main_class[] = 'narrow-area';
    }
    
    /**
     * item_template
     */
    if ( '2' != $header_item_template && '4' != $header_item_template ) {
        $header_item_template = '1';
    }
    $class[] = 'single-header-template-' . $header_item_template;
    
    /**
     * setup params for post body
     */
    $body_params = $params;
    $body_params[ 'live' ] = true;
    $body_params[ 'item_template' ] = $header_item_template;
    $body_params[ 'title_html' ] = 
        '<div class="title-subtitle">' . fox_format( '<h1 class="post-title post-item-title">{}</h1>', get_the_title() ) . fox_get_subtitle() . '</div>';
    
    $body_params[ 'title_show' ] = true;
    $body_params[ 'excerpt_show' ] = false;
    
    /**
     * border border
     */
    $single_header_border = get_theme_mod( 'wi_single_meta_border' );
    if ( $single_header_border ) {
        $borders = explode( '|', $single_header_border );
        foreach ( $borders as $bor ) {
            $class[] = 'post-header-' . $bor;
        }
    }
    
    ?>
    <header class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemscope itemtype="https://schema.org/WPHeader">
    
        <div class="container">
            
            <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">

                <?php fox43_post_body( $body_params ); ?>
                
            </div><!-- .header-main -->

        </div><!-- .container -->
    
    </header><!-- .single-header -->
    <?php
    
}
endif;

if ( ! function_exists( 'fox43_page_header' ) ) :
/**
 * Page Header
 *
 * @since 4.3
 */
function fox43_page_header( $params ) {
    
    $params = wp_parse_args( $params, [
        
        'style' => '1',
        'sidebar_state' => 'right',
        'thumbnail_stretch' => 'stretch-none',
        'content_width' => 'full',
        'image_stretch' => 'stretch-none',
        
        'column_layout' => 1,
        'title_align' => '',
        
    ] );
    
    extract( $params );
    
    if ( ! $post_header_show ) {
        return;
    }
    
    // legacy
    $class = [
        'single-header',
        'post-header',
        'entry-header',
        'page-header',
    ];
    
    if ( 'left' == $title_align || 'center' == $title_align || 'right' == $title_align ) {
        $class[] = 'align-' . $title_align;
    }
    
    // depending on the layout, it'll be a section or a big-section
    if ( '2' == $style ) {
        $class[] = 'single-big-section';
    } elseif ( '1' == $style || '1b' == $style || '3' == $style ) {
        $class[] = 'single-section';
    }
    
    $main_class = [
        'header-main'
    ];
    
    /**
     * content narrow
     */
    $narrow = false;
    if ( 'narrow' == $content_width ) {
        $narrow = true;
    }
    
    if ( $narrow ) {
        $main_class[] = 'narrow-area';
    }
    
    ?>
    <header class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemscope itemtype="https://schema.org/WPHeader">
    
        <div class="container">
            
            <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">

                <?php echo fox_format( '<h1 class="page-title">{}</h1>', get_the_title() ); ?>
                
                <?php echo fox_get_subtitle(); ?>
                
            </div><!-- .header-main -->

        </div><!-- .container -->
    
    </header><!-- .single-header -->

<?php
    
}
endif;

if ( ! function_exists( 'fox43_single_thumbnail' ) ) :
/**
 * Single Thumbnail
 * We'll markup the thumbnail depending on situation
 * possibilities are: content width, bigger than content, fullwidth
 *
 * 2 problems are: stretch an narrow content
 * .thumbnail-stretch-area is the div to stretch the thumbnail
 * inside it, thumbnail always display as 100% and we don't need to worry about inside it anymore
 *
 * @since 4.3
 */
function fox43_single_thumbnail( $params = [] ) {
    
    $params = wp_parse_args( $params, [
        
        'style' => '1',
        'sidebar_state' => 'right',
        'thumbnail_stretch' => 'stretch-none',
        'content_width' => 'full',
        'image_stretch' => 'stretch-none',
        'column_layout' => 1,
        
    ]);
    
    extract( $params );
    
    if ( ! $thumbnail_show ) {
        return;
    }
    
    $thumbnail = fox_get_advanced_thumbnail();
    if ( ! $thumbnail ) return;
    
    $class = [
        'thumbnail-wrapper',
        'single-big-section-thumbnail',
    ];
    
    // depending on the layout, it'll be a section or a big-section
    if ( '2' == $style || '3' == $style ) {
        $class[] = 'single-big-section';
    } elseif ( '1' == $style || '1b' == $style ) {
        $class[] = 'single-section';
    }
    
    $main_class = [
        'thumbnail-main',
    ];
    
    /**
     * check if this post allow stretch
     */
    $allow_stretch = false;
    if ( '1' == $style || '1b' == $style ) {
        if ( 'no-sidebar' == $sidebar_state ) {
            $allow_stretch = true;
        }
    } elseif ( '2' == $style || '3' == $style ) {
        $allow_stretch = true;
    }
    
    /**
     * narrow
     * we only narrow down the thumbnail size in:
     * no sidebar mode
     * style 1, 1b, 2, 3
     */
    $narrow = false;
    if ( 'narrow' == $content_width && 'no-sidebar' == $sidebar_state ) {
        
        if ( '1' == $style || '1b' == $style || '2' == $style || '3' == $style ) {
            
            $narrow = true;
            
        }
        
    }
    
    /**
     * Body layout: if boxed, then we disabllow stretch bigger
     */
    if ( 'boxed' == $body_layout && ! $narrow && 'stretch-bigger' == $thumbnail_stretch ) {
        $allow_stretch = false;
    }
    
    // if allow stretch and stretch full, then no longer narrow
    if ( $allow_stretch && ( 'stretch-full' == $thumbnail_stretch || 'stretch-container' == $thumbnail_stretch ) ) {
        $narrow = false;
    }
    
    if ( $allow_stretch ) {
    
        /**
         * bigger means bigger than 120px
         * 60px for each side
         *
         * this applies for $style 1, 1b in fullwidth mode, and $style 2, 3
         */
        if ( 'stretch-bigger' == $thumbnail_stretch ) {
        
            $class[] = 'wrapper-thumbnail-stretch-bigger';
            
        } elseif ( 'stretch-full' == $thumbnail_stretch ) {
            
            $class[] = 'wrapper-thumbnail-stretch-full';
            
        }
    }
    
    if ( $narrow ) {
        $main_class[] = 'narrow-area';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="thumbnail-container">
        
        <div class="container">
            
            <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">
                
                <div class="thumbnail-stretch-area">

                    <?php echo $thumbnail; ?>
                    
                </div><!-- .thumbnail-stretch-area -->
                
            </div><!-- .thumbnail-main -->

        </div><!-- .container -->
        
    </div><!-- .thumbnail-container -->
    
</div><!-- .thumbnail-wrapper -->

<?php
    
}
endif;

if ( ! function_exists( 'fox43_single_body' ) ) :
/**
 * Single Body
 * it has only 1 problem: narrow content
 *
 * we also add some useful classes for stretch content images problem
 *
 * @since 4.3
 */
function fox43_single_body( $params ) {
    
    $params = wp_parse_args( $params, [
        
        'style' => '1',
        'sidebar_state' => 'right',
        'thumbnail_stretch' => 'stretch-none',
        'content_width' => 'full',
        'image_stretch' => 'stretch-none',
        'column_layout' => 1,

        'header_align' => 'center',
        'header_item_template' => '1',
        
        'dropcap' => false,
        'text_column' => 1,
    ]);
    
    extract( $params );
    
    $class = [
        'single-section', 
        'single-main-content' 
    ];
    
    /**
     * Narrow
     */
    $main_class = [
        'content-main',
    ];
    
    if ( 'narrow' == $content_width ) {
        $main_class[] = 'narrow-area';
    }
    
    /**
     * cases that allow stretch
     * narrow content: left, right, bigger
     * no-sidebar: bigger, full
     *
     * to keep it simple, stretch bigger is only allowed with narrow content
     */
    $allow_stretch = [];
    if ( 'no-sidebar' == $sidebar_state ) {
        $allow_stretch[] = 'stretch-full';
    }
    if ( 'narrow' == $content_width ) {
        $allow_stretch[] = 'stretch-left';
        $allow_stretch[] = 'stretch-right';
        $allow_stretch[] = 'stretch-bigger';
    }
    
    $allow_stretch = array_unique( $allow_stretch );
    
    /**
     * STRETCH ALL
     * stretch-full will become stretch-bigger in case it has sidebar
     * and in case it has sidebar + content full, no stretch at all
     */
    if ( 'no-sidebar' != $sidebar_state ) {
        if ( 'stretch-full' == $image_stretch ) {
            $image_stretch = 'stretch-bigger';
        }
        if ( 'full' == $content_width ) {
            $image_stretch = 'stretch-none';
        }
    }
    
    /**
     * Body layout: if boxed, then we disabllow stretch bigger, left, right for full content
     */
    if ( 'boxed' == $body_layout && 'full' == $content_width ) {
        $allow_stretch = array_diff( $allow_stretch, [ 'stretch-bigger', 'stretch-right', 'stretch-left' ] );
    }
    
    if ( $image_stretch == 'stretch-bigger' ) {
        // $allow_stretch = array_diff( $allow_stretch, [ 'stretch-left', 'stretch-right' ] );
    }
    
    // if 2 column text, disallow stretch
    // FINAL GUARD
    if ( 2 == $text_column ) {
        $allow_stretch = [];
    }
    
    if ( in_array( $image_stretch, $allow_stretch ) ) {
        $class[] = 'content-all-' . $image_stretch;
    }
    
    foreach ( $allow_stretch as $str ) {
        
        $class[] = 'allow-' . $str;
        
    }
    
    /**
     * share side
     */
    $share_positions = [];
    if ( 'narrow' != $content_width ) {
        
        $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
        $share_positions = explode( ',', $share_positions );
        $share_positions = array_map( 'trim', $share_positions );
        if ( in_array( 'side', $share_positions ) ) {
            $class[] = 'side-share';
        }
        
    }
    
    /**
     * drop cap
     */
    if ( $dropcap ) {
        $class[] = 'enable-dropcap';
    } else {
        $class[] = 'disable-dropcap';
    }
    
    /**
     * text column
     */
    if ( 2 == $text_column ) {
        $class[] = 'enable-2-columns';
    }
    
    ?>

<div class="single-body single-section">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <?php if ( 'narrow' != $content_width && in_array( 'side', $share_positions ) ) {
            fox_share([
                'extra_class' => 'vshare',
                'style' => 'custom',
            ]);
        } ?>
        
        <div class="entry-container">
            
            <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">
            
                <?php 
    
                    /**
                     * 10 - fox_append_single_ad_before: ad
                     * 50 - fox_sponsored_row: sponsor tag
                     */
                    do_action( 'fox_before_entry_content', $params ); // since 4.0 ?>

                <div class="dropcap-content columnable-content entry-content single-component">

                    <?php the_content(); fox_page_links(); ?>

                </div><!-- .entry-content -->

                <?php 
    
                    /**
                     * 10 - fox_append_single_ad_after: ad
                     * 20 - fox43_after_entry_content: share, related, authorbox, tags.
                     */
                    do_action( 'fox_after_entry_content', $params ); // since 4.0 ?>
                
            </div>
            
        </div><!-- .container -->
    
    </div><!-- .single-section -->
    
    <?php do_action( 'fox_after_single_content', $params ); ?>

</div><!-- .single-body -->

<?php
    
}
endif;

/**
 * Page Body
 *
 * @since 4.3
 */
function fox43_page_body( $params = [] ) {
    
    $params = wp_parse_args( $params, [
        
        'style' => '1',
        'sidebar_state' => 'right',
        'thumbnail_stretch' => 'stretch-none',
        'content_width' => 'full',
        'image_stretch' => 'stretch-none',
        'column_layout' => 1,

        'dropcap' => false,
        'text_column' => 1,
        
        'share_show' => false,
        'comment_show' => false,
    ]);
    
    extract( $params );
    
    $class = [
        'single-section', 
        'single-main-content' 
    ];
    
    /**
     * Narrow
     */
    $main_class = [
        'content-main',
    ];
    
    if ( 'narrow' == $content_width ) {
        $main_class[] = 'narrow-area';
    }
    
    /**
     * cases that allow stretch
     * narrow content: left, right, bigger
     * no-sidebar: bigger, full
     *
     * to keep it simple, stretch bigger is only allowed with narrow content
     */
    $allow_stretch = [];
    if ( 'no-sidebar' == $sidebar_state ) {
        $allow_stretch[] = 'stretch-full';
    }
    if ( 'narrow' == $content_width ) {
        $allow_stretch[] = 'stretch-left';
        $allow_stretch[] = 'stretch-right';
        $allow_stretch[] = 'stretch-bigger';
    }
    
    $allow_stretch = array_unique( $allow_stretch );
    
    /**
     * STRETCH ALL
     * stretch-full will become stretch-bigger in case it has sidebar
     * and in case it has sidebar + content full, no stretch at all
     */
    if ( 'no-sidebar' != $sidebar_state ) {
        if ( 'stretch-full' == $image_stretch ) {
            $image_stretch = 'stretch-bigger';
        }
        if ( 'full' == $content_width ) {
            $image_stretch = 'stretch-none';
        }
    }
    
    if ( in_array( $image_stretch, $allow_stretch ) ) {
        
        $class[] = 'content-all-' . $image_stretch;
        
    }
    
    /**
     * Body layout: if boxed, then we disabllow stretch bigger, left, right for full content
     */
    if ( 'boxed' == $body_layout && 'full' == $content_width ) {
        $allow_stretch = array_diff( $allow_stretch, [ 'stretch-bigger', 'stretch-right', 'stretch-left' ] );
    }
    
    if ( $image_stretch == 'stretch-bigger' ) {
        // $allow_stretch = array_diff( $allow_stretch, [ 'stretch-left', 'stretch-right' ] );
    }
    
    // if 2 column text, disallow stretch
    if ( 2 == $text_column ) {
        $allow_stretch = [];
    }
    
    foreach ( $allow_stretch as $str ) {
        
        $class[] = 'allow-' . $str;
        
    }
    
    /**
     * share side
     */
    $share_positions = [];
    $share_positions = get_theme_mod( 'wi_page_share_positions', 'after' );
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    if ( 'narrow' != $content_width && $params[ 'share_show' ] ) {
        
        if ( in_array( 'side', $share_positions ) ) {
            $class[] = 'side-share';
        }
        
    }
    
    /**
     * drop cap
     */
    if ( $dropcap ) {
        $class[] = 'enable-dropcap';
    } else {
        $class[] = 'disable-dropcap';
    }
    
    /**
     * text column
     */
    if ( 2 == $text_column ) {
        $class[] = 'enable-2-columns';
    }
    
?>    
<div class="single-body single-section">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <?php if ( 'narrow' != $content_width && $params[ 'share_show' ] && in_array( 'side', $share_positions ) ) {
            fox_share([
                'extra_class' => 'vshare',
                'style' => 'custom',
            ]);
        } ?>
        
        <div class="entry-container">
            
            <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">
            
                <div class="dropcap-content columnable-content entry-content single-component">

                    <?php the_content(); fox_page_links(); ?>

                </div><!-- .entry-content -->

                <?php
    
    /**
     * Share
     */
    if ( $params[ 'share_show' ] ) {
        
        if ( in_array( 'after', $share_positions ) ) {
            echo '<div class="single-component single-component-share">';
            fox_share();
            echo '</div>';
        }

        // when we have side share, we need a fallback
        if ( in_array( 'side', $share_positions ) && ! in_array( 'after', $share_positions ) && ! in_array( 'before', $share_positions ) ) {
            echo '<div class="single-component single-component-share hide_on_desktop show_on_tablet">';
            fox_share();
            echo '</div>';
        }
        
    }
    
    /**
     * COMMENT
     */
    if ( $params[ 'comment_show' ] ) {
        
        fox_page_comment();
        
    } 
    ?>
                
            </div>
            
        </div><!-- .container -->
    
    </div><!-- .single-section -->

</div><!-- .single-body -->

<?php
    
}

add_action( 'fox_after_entry_content', 'fox43_after_entry_content', 20 );
/**
 * After single post content / sections and organizing
 *
 * @since 4.3
 */
function fox43_after_entry_content( $params = [] ) {
    
    /**
     * SHARE
     */
    $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    if ( in_array( 'after', $share_positions ) ) {
        echo '<div class="single-component single-component-share">';
        fox_share();
        echo '</div>';
    }
    
    // when we have side share, we need a fallback
    if ( in_array( 'side', $share_positions ) && ! in_array( 'after', $share_positions ) && ! in_array( 'before', $share_positions ) ) {
        echo '<div class="single-component single-component-share hide_on_desktop show_on_tablet">';
        fox_share();
        echo '</div>';
    }
    
    /**
     * TAG
     */
    if ( $params[ 'tag_show' ] ) {
        fox_single_tags();
    }
    
    $related_position = get_theme_mod( 'wi_single_related_position', 'after_main_content' );
    if ( 'after_container' != $related_position ) {
        $related_position = 'after_main_content';
    }
    
    /**
     * RELATED POSTS
     */
    if ( $params[ 'related_show' ] && 'after_main_content' == $related_position ) {
        
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
        
        fox_single_related( $prefix, $defaults, 'single-component single-component-related' );
        
    }
    
    /**
     * AUTHOR BOX
     */
    if ( $params[ 'authorbox_show' ] ) {
        
        fox_single_authorbox();
        
    }
    
    /**
     * COMMENT
     */
    if ( $params[ 'comment_show' ] ) {
        
        fox_single_comment();
        
    }
     
}

add_action( 'fox_before_entry_content', 'fox43_share_before_content', 20 );
/**
 * share before content
 *
 * @since 4.3
 */
function fox43_share_before_content() {
    
    $share_positions = get_theme_mod( 'wi_share_positions', 'after' );
    $share_positions = explode( ',', $share_positions );
    $share_positions = array_map( 'trim', $share_positions );
    
    if ( in_array( 'before', $share_positions ) ) {
        fox_share([
            'extra_class' => 'fox-share-top single-component',
        ]);
    }
    
}

add_action( 'fox_before_entry_content', 'fox43_hero_meta', 15 );
/**
 * Hero Post Meta
 * while we move all hero meta components to content
 * to make hero header really beautiful
 *
 * @since 4.3
 */
function fox43_hero_meta( $params ) {
    
    if ( 4 != $params[ 'style' ] && 5 != $params[ 'style' ] ) {
        return;
    }
    
    $style = $params[ 'style' ];
    $header_item_template = $params[ 'header_item_template' ];
    $header_align = $params[ 'header_align' ];
    $content_width = $params[ 'content_width' ];
    
    // legacy
    $class = [
        'hero-meta',
        'single-component',
    ];
    
    $main_class = [
        'header-main'
    ];
    
    /**
     * content narrow
     */
    $narrow = false;
    if ( 'narrow' == $content_width ) {
        $narrow = true;
    }
    
    if ( $narrow ) {
        $main_class[] = 'narrow-area';
    }
    
    $body_params = $params;
    $body_params[ 'live' ] = true;
    $body_params[ 'item_template' ] = 1;
    $body_params[ 'title_show' ] = false;
    $body_params[ 'excerpt_show' ] = false;
    
    $body_params[ 'category_show' ] = false;
    // $body_params[ 'comment_link_show' ] = false;
    $body_params[ 'view_show' ] = false;
    
    ?>
<header class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemscope itemtype="https://schema.org/WPHeader">

    <div class="container">

        <div class="<?php echo esc_attr( join( ' ', $main_class ) ); ?>">

            <?php fox43_post_body( $body_params ); ?>

        </div>

    </div><!-- .container -->

</header>

<?php
    
}