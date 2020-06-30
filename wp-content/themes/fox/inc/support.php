<?php
/**
 * general array of option
 * $context can be 'post-meta' or 'customizer'
 */
function fox_generate_option( $id, $context = '', $override = [] ) {
    
    $support = fox_support( $id, 'all' );
    $return = [];
    
    if ( 'post-meta' == $context || 'page-meta' == $context ) {
        
        $support = wp_parse_args( $support, [
            'name' => '',
            'type' => '',
            'options' => [],
            'desc' => '',
            'dependency' => null,
            'tab' => '',
        ] );
        
        $options = $support[ 'options' ];
        
        // add default option
        if ( 'radio' == $support[ 'type' ] || 'select' == $support[ 'type' ] ) {
            $options = [ '' => 'Default' ] + $options;
        } elseif ( 'image_radio' == $support[ 'type' ] ) {
            $options = [ '' => [
                'html' => fox_html_image( 'default' ),
                'title' => 'Default',
            ] ] + $options;
        }
        
        $field = [
            'id'    => $id,
            'name'  =>   $support[ 'name' ],
            'type'  =>   $support[ 'type' ],
            'desc'  =>   $support[ 'desc' ],
            'dependency' => $support[ 'dependency' ],
            'tab' =>    $support[ 'tab' ],
        ];
        $type = $support[ 'type' ];
        
        if ( 'radio' == $type || 'select' == $type || 'image_radio' == $type ) {
            $field[ 'options' ] = $options;
        }
        
        // single std is often '', ie. inherit settings from customizer
        if ( isset( $support[ 'single_std' ] ) ) {
            $field[ 'std' ] = $support[ 'single_std' ];
        } else {
            $field[ 'std' ] = '';
        }
        
        $return = $field;
        
    }
    
    if ( 'customizer' == $context ) {
        
        $support = wp_parse_args( $support, [
            'name' => '',
            'type' => '',
            'options' => [],
            'desc' => '',
            'std' => '',
            
            'section' => '',
            'section_title' => '',
            'section_priority' => '',
            
            'panel' => '',
            'panel_title' => '',
            'panel_priority' => '',
            
        ] );
        
        $field = [
            'name' => $support[ 'name' ],
            'type' => $support[ 'type' ],
            'desc' => $support[ 'desc' ],
            'std' => $support[ 'std' ],
        ];
        
        $type = $support[ 'type' ];
        
        if ( 'radio' == $type || 'select' == $type || 'image_radio' == $type ) {
            $field[ 'options' ] = $support[ 'options' ];
        }
        
        $keys = [ 'section', 'section_title', 'section_priority', 'panel', 'panel_title', 'panel_priority' ];
        foreach( $keys as $key ) {
            if ( $support[ $key ] ) $field[ $key ] = $support[ $key ];
        }
        
        $return = $field;
        
    }
    
    $return = wp_parse_args( $override, $return );
    return $return;
    
}

/**
 * $prefix is the context we wanna get
 * property "foo" in get_post_meta
 * will be get_theme_mod( prefix + foo )
 */
function fox_get_option( $id, $prefix = '', $postid = null ) {
    
    global $fox;
    $options = ( array ) fox_support( $id, 'options' );
    $options = array_keys( $options );
    $std = fox_support( $id, 'std', $prefix );
    $type = fox_support( $id, 'type' );
    $get = '';
    
    // lol why??
    if ( 'page_' == $prefix && 'comment' == $id ) $std = 'false';
    
    // exception
    // post means single, just an alias
    if ( $prefix == 'post_' ) $prefix = 'single_';
    
    /**
     * get term meta / post meta depending on the context
     * default postid depending on the context
     */
    if ( 'category_' == $prefix || 'tag_' == $prefix ) {
        
        if ( ! $postid ) $postid = get_queried_object_id();
        $get = get_term_meta( $postid, '_wi_' . $id, true );
        
    } elseif ( 'single_' == $prefix  ) {
    
        if ( ! $postid ) $postid = get_the_ID();
        $get = get_post_meta( $postid, '_wi_' . $id, true );
        
    } elseif ( 'page_' == $prefix ) {
        
        if ( ! $postid ) $postid = fox_page_id();
        $get = get_post_meta( $postid, '_wi_' . $id, true );
    
    }
    
    if ( 'select' == $type || 'radio' == $type || 'image_radio' == $type ) {
         if ( ! in_array( $get, $options ) ) {
            $get = get_theme_mod( 'wi_' . $prefix . $id, $std );
        }
        
        // for choice options, we limit the option
        if ( ! in_array( $get, $options ) ) {
            $get = $std;
        }
        
    } else {
        
        // empty
        if ( '' === $get ) {
            $get = get_theme_mod( 'wi_' . $prefix . $id, $std );
        }
        
    }
    
    $get = apply_filters( 'fox_get_option', $get, $id, $prefix, $postid );
    
    return $get;
    
}

if ( ! function_exists( 'fox_single_option' ) ) :
/**
 * Get Single Option, prefix should be single_
 * @since 4.0
 */
function fox_single_option( $id ) {
    
    return fox_get_option( $id, 'single_' );
    
}
endif;

if ( ! function_exists( 'fox_page_option' ) ) :
/**
 * Get Page Option, prefix should be page_
 * @since 4.0
 */
function fox_page_option( $id ) {
    
    return fox_get_option( $id, 'page_' );
    
}
endif;

if ( ! function_exists( 'fox_category_option' ) ) :
/**
 * Get Category Option, prefix should be category_
 * @since 4.0
 */
function fox_category_option( $id ) {
    
    return fox_get_option( $id, 'category_' );
    
}
endif;

if ( ! function_exists( 'fox_tag_option' ) ) :
/**
 * Get Tag Option, prefix should be category_
 * @since 4.0
 */
function fox_tag_option( $id ) {
    
    return fox_get_option( $id, 'tag_' );
    
}
endif;

if ( ! function_exists( 'fox_show' ) ) :
/**
 * return @bool: true or false
 * check if a component is show or hide on single post
 * $context is single or page or category or tag
 * $prefix = $context . '_'
 * $prefix is used to know which context get theme mod from customizer get_theme_mod( 'wi_single_... )
 *
 * @since 4.0
 */
function fox_show( $id, $context = 'single', $postid = null ) {
    
    return ( 'true' === fox_get_option( $id, $context . '_', $postid ) );
    
}
endif;

/* NOW THE SUPPORT ARRAY
------------------------------------------------------------------------------------------------------------------------------------------------ */
global $fox;

add_action( 'init', 'fox_init_support', 0 );
add_action( 'admin_init', 'fox_init_support', 0 );

function fox_init_support() {
    
    global $fox;
    
    /**
     * FORMAT 
     * ---------------------------------------------------------------------------------------
     * Gallery Style
     */
    $fox[ 'format_gallery_style' ] = array(
        'name' => 'Gallery Style',
        'type' => 'image_radio',
        'options' => [
            'metro' => [
                'html' => fox_html_image( 'gallery_metro' ),
                'title' => 'Metro',
            ],
            'stack' => [
                'html' => fox_html_image( 'gallery_stack' ),
                'title' => 'Stack Images',
            ],
            'carousel' => [
                'html' => fox_html_image( 'gallery_carousel' ),
                'title' => 'Carousel',
            ],
            'slider' => [
                'html' => fox_html_image( 'gallery_slider' ),
                'title' => 'Slider',
            ],
            'slider-rich' => [
                'html' => fox_html_image( 'gallery_slider_rich' ),
                'title' => 'Rich Content Slider',
            ],
            'grid' => [
                'html' => fox_html_image( 'gallery_grid' ),
                'title' => 'Grid',
            ],
            'masonry' => [
                'html' => fox_html_image( 'gallery_masonry' ),
                'title' => 'Masonry',
            ],
        ],
        'std' => 'metro',

        'dependency' => [
            'element' => 'post_format',
            'element_prefix' => false,
            'value' => 'gallery',
        ],

        'tab' => 'format',
    );
    
    $fox[ 'format_gallery_lightbox' ] = array(
        'name' => 'Open lightbox?',
        'type' => 'select',
        
        'options' => [
            'true' => 'Yes Please',
            'false' => 'No Thanks',
        ],
        'std' => 'true',

        'dependency' => [
            'element' => 'post_format',
            'element_prefix' => false,
            'value' => 'gallery',
        ],

        'tab' => 'format',
    );
    
    /* Slider
    ------------------ */
    $fox[ 'format_gallery_slider_effect' ] = [
        'name' => 'Slider Effect?',
        'type' => 'select',
        'options' => [
            'fade' => 'Fade',
            'slide' => 'Slide',
        ],
        'std' => 'fade',
        'dependency' => [
            'element' => 'format_gallery_style',
            'value' => 'slider',
        ],
        'tab' => 'format',
    ];
    
    $fox[ 'format_gallery_slider_size' ] = array(
        'name' => 'Image Crop',
        'type' => 'select',
        'options' => [
            'original' => 'Original Size',
            'crop' => 'Crop',
        ],
        'std' => 'crop',

        'dependency' => [
            'element' => 'format_gallery_style',
            'value' => 'slider',
        ],

        'tab' => 'format',
    );
    
    /* Grid
    ------------------ */
    $fox[ 'format_gallery_grid_column' ] = array(
        'name' => 'Gallery Grid Column',
        'type' => 'select',
        'options' => [
            '2' => '2 Columns',
            '3' => '3 Columns',
            '4' => '4 Columns',
            '5' => '5 Columns',
        ],
        'std' => '3',

        'dependency' => [
            'element' => 'format_gallery_style',
            'value' => [ 'grid', 'masonry' ],
        ],

        'tab' => 'format',
    );
    
    $fox[ 'format_gallery_grid_size' ] = array(
        'name' => 'Gallery Grid Image Size',
        'type' => 'select',
        'options' => [
            'landscape' => 'Landscape',
            'square' => 'Square',
            'portrait' => 'Portrait',
            'original' => 'Original',
            'custom' => 'Custom Size',
        ],
        'std' => 'landscape',

        'dependency' => [
            'element' => 'format_gallery_style',
            'value' => 'grid',
        ],

        'tab' => 'format',
    );
            
    $fox[ 'format_gallery_grid_size_custom' ] = array(
        
        'name' => 'Grid Image Custom Size',
        'type' => 'text',
        'placeholder' => 'Eg. 600x320',
        'desc' => 'Syntax: WxH',

        'dependency' => [
            'element' => 'format_gallery_grid_size',
            'value' => 'custom',
        ],

        'tab' => 'format',
    );
    
    /**
     * LAYOUT 
     * --------------------------------------------------------------------------------------- */
    // style
    /* layout options */
    $layouts = [];

    for ( $i = 1; $i <= 5; $i++ ) {
        $layouts[ $i ] = [
            'html' => fox_html_image( 'layout_' . $i ),
            'title' => 'Layout ' . $i,
        ];
        if ( 1 == $i ) {
            $layouts[ '1b' ] = [
                'html' => fox_html_image( 'layout_1b' ),
                'title' => 'Layout 1b',
            ];
        }
    }
    $layouts[ 4 ][ 'title' ] = 'Hero Full';
    $layouts[ 5 ][ 'title' ] = 'Hero Half';
    
    $fox[ 'style' ] = array(
        'name' => 'Layout Style',
        'type' => 'image_radio',
        'options' => $layouts,
        'std' => 1,
        'tab' => 'layout',
    );
    
    $fox[ 'sidebar_state' ] = [
        'type' => 'image_radio',
        'std' => 'sidebar-right',
        'name' => 'Sidebar State',
        'options' => [
            'sidebar-right' => [
                'html' => fox_html_image( 'sidebar_right' ),
                'title' => 'Sidebar Right',
            ],
            'sidebar-left' => [
                'html' => fox_html_image( 'sidebar_left' ),
                'title' => 'Sidebar Left',
            ],
            'no-sidebar' => [
                'html' => fox_html_image( 'no_sidebar' ),
                'title' => 'No Sidebar',
            ],
        ],
        'tab' => 'layout',
    ];
    
    $fox[ 'thumbnail_stretch' ]  = array(
        'name' => 'Thumbnail Stretch',
        'type' => 'image_radio',
        'options' => [
            'stretch-none' => [
                'html' => fox_html_image( 'stretch_none' ),
                'title' => 'No Stretch',
            ],
            'stretch-bigger' => [
                'html' => fox_html_image( 'stretch_bigger' ),
                'title' => 'Bigger than content',
            ],
            'stretch-full' => [
                'html' => fox_html_image( 'stretch_full' ),
                'title' => 'Fullwidth',
            ]
        ],
        'std' => 'stretch-none',
        'tab' => 'layout',
        'desc' => 'Only in some layouts, thumbnail can stretch to bigger than the content',
    );
    
    $fox[ 'content_width' ] = [
        'name' => 'Content Width',
        'type' => 'image_radio',
        'options' => [
            'full' => [
                'html' => fox_html_image( 'content_full' ),
                'title' => 'Standard',
            ],
            'narrow' => [
                'html' => fox_html_image( 'content_narrow' ),
                'title' => 'Narrow',
            ],
        ],
        'std' => 'full',
        'tab' => 'layout',
    ];
    
    $fox[ 'content_image_stretch' ] = [
        'name' => 'Content Image Stretch',
        'type' => 'image_radio',
        'tab' => 'layout',
        'options' => [
            'stretch-none' => [
                'html' => fox_html_image( 'content_stretch_none' ),
                'title' => 'No Stretch',
            ],
            'stretch-bigger' => [
                'html' => fox_html_image( 'content_stretch_bigger' ),
                'title' => 'Bigger than content',
            ],
            'stretch-full' => [
                'html' => fox_html_image( 'content_stretch_full' ),
                'title' => 'Fullwidth',
            ],
        ],
        'std' => 'stretch-none',
        'desc' => 'This ONLY works when you choose "Narrow" Content Width',
    ];
    
    $fox[ 'column_layout' ] = [
        'name' => 'Text Column Layout',
        'type' => 'select',
        'options' => array(
            '1' => '1 column',
            '2' => '2 columns',
        ),
        'std' => '1',
        'tab' => 'misc',
    ];
    
    $fox[ 'dropcap' ] = [
        'name' => 'Dropcap single post',
        'type' => 'select',
        'options' => array(
            'true' => 'Enable',
            'false' => 'Disable',
        ),
        'std' => 'false',
        'tab' => 'misc',
    ];
    
    $fox[ 'blog_dropcap' ] = [
        'name' => 'Dropcap on blog',
        'type' => 'select',
        'options' => array(
            'true' => 'Enable',
            'false' => 'Disable',
        ),
        'std' => 'false',
        'tab' => 'misc',
    ];
    
    $components = fox_single_element_support();
    foreach ( $components as $id => $name ) {
        $fox[ $id ] = [
            'name' => $name,
            'type' => 'select',
            'options' => [
                'true'  => 'Enable',
                'false' => 'Disable',
            ],
            'std' => 'true',
            'tab' => 'component',
        ];
    }
    
    // only for page
    $fox[ 'page_share' ] = [
        'name' => 'Share icons',
        'type' => 'select',
        'options' => [
            'true'  => 'Enable',
            'false' => 'Disable',
        ],
        'std' => 'false',
        'tab' => 'component',
    ];
    
    /* BLOG
    ------------------------------ */
    $fox[ 'meta_template' ] = array(
        'type'      => 'select',
        'options'   => [
            '1' => 'Title > Meta',
            '2' => 'Meta > Title',
            '4' => 'Category > Title > Meta',
        ],
        'std'       => '4',
        'name'      => 'Meta Item Template',

        'section' => 'single_meta',
        'section_title' => 'Single Post Header',
        'panel' => 'single',
    );
    
}

/**
 * return thing we wanna get
 * $id is the unique id of element like: sidebar_state, content_stretch etc
 * $thing can be 'options', 'std', ..
 */
function fox_support( $id = '', $thing = '', $prefix = '' ) {
    
    global $fox;
    
    $prefixed_id = $prefix . $id;
    
    if ( isset( $fox[ $prefixed_id ] ) ) {
        $id = $prefixed_id;
    }
    
    if ( isset( $fox[ $id ] ) ) {
        
        $support = $fox[ $id ];
        if ( isset( $support[ $thing ] ) ) return $support[ $thing ];
        
        // return all
        else return $support;
        
    } else {
        
        return;
        
    }
    
}

/**
 * List of sidebars
 * @since 4.0
 */
function fox_sidebar_support() {
    
    $sidebars = [
        'sidebar' => [
            'name' => 'Main Sidebar',
            'desc' => 'Used for blog, archive, single post',
        ],
        'page-sidebar' => [
            'name' => 'Page Sidebar',
            'desc' => 'Used for page',
        ],
    ];
    
    $sidebars[ 'header' ] = [
        'name' => 'After Logo',
        'desc' => 'In case you wanna display some ad after the logo',
    ];
    
    $sidebars[ 'before-header' ] = [
        'name' => 'Before Header',
        'desc' => 'In case you wanna insert some banner before the header',
    ];
    
    $sidebars[ 'after-header' ] = [
        'name' => 'After Header',
        'desc' => 'In case you wanna insert some banner after the header',
    ];
    
    for ( $i = 1; $i <= 4; $i++ ) {
        $sidebars[ 'footer-' . $i ] = [
            'name' => 'Footer ' . $i,
            'desc' => 'Footer Sidebar Column ' . $i,
        ];
    }
    
    $sidebars[ 'footer-bottom-stack' ] = [
        'name' => 'Footer Bottom Stack',
        'desc' => 'Drag your widgets here if you choose Footer Bottom: Stack Layout',
    ];
    
    $sidebars[ 'footer-bottom-left' ] = [
        'name' => 'Footer Bottom Left',
        'desc' => 'Drag your widgets here if you choose Footer Bottom: Left - Right Layout',
    ];
    
    $sidebars[ 'footer-bottom-right' ] = [
        'name' => 'Footer Bottom Right',
        'desc' => 'Drag your widgets here if you choose Footer Bottom: Left - Right Layout',
    ];
    
    $query[ 'autofocus[section]' ] = 'wi_header_builder';
    $section_link = add_query_arg( $query, admin_url( 'customize.php' ) );
    
    $sidebars[ 'header-builder' ] = [
        'name' => 'Header Builder',
        'desc' => 'Drag header widgets (ie. header elements) here to build your own header. To use header builder, you must enable it in <a href="' . $section_link . '" target="_blank">Customize > Header > Header Builder</a>',
    ];
    
    return apply_filters( 'fox_sidebar_support', $sidebars );
    
}

if ( ! function_exists( 'fox_archive_layout_support' ) ) :
/**
 * return array of all possible archive layouts
 * @since 4.0
 */
function fox_archive_layout_support() {
    
    return [
        
        'standard' => 'Standard',
        'grid-2' => 'Grid 2 columns',
        'grid-3' => 'Grid 3 columns',
        'grid-4' => 'Grid 4 columns',
        'grid-5' => 'Grid 5 columns',
        
        'masonry-2' => 'Masonry 2 columns',
        'masonry-3' => 'Masonry 3 columns',
        'masonry-4' => 'Masonry 4 columns',
        'masonry-5' => 'Masonry 5 columns',
        
        'newspaper' => 'Newspaper',
        'list'      => 'List',
        'vertical'  => 'Post Vertical',
        
    ];

}
endif;

if ( ! function_exists( 'fox_builder_layout_support' ) ) :
/**
 * return array of all possible layouts the builder supports
 * @since 4.0
 */
function fox_builder_layout_support() {
    
    $layout_arr = [
        
        'standard'              =>  'Standard',
        
        'grid-2'                =>  'Grid 2 columns',
        'grid-3'                =>  'Grid 3 columns',
        'grid-4'                =>  'Grid 4 columns',
        'grid-5'                =>  'Grid 5 columns',
        
        'masonry-2'             =>  'Pinterest-like 2 columns',
        'masonry-3'             =>  'Pinterest-like 3 columns',
        'masonry-4'             =>  'Pinterest-like 4 columns',
        'masonry-5'             =>  'Pinterest-like 5 columns',
        
        'list'                  =>  'List',
        
        'newspaper'             =>  'Newspaper',
        
        'vertical'              => 'Vertical post',
        'big'                   => 'Big Post',
        
        'group-1'               => 'Post Group 1',
        'group-2'               => 'Post Group 2',
        
        'slider'                => 'Classic Slider',
        
        'slider-1'              => 'Modern Slider',
        
        // 'carousel'              => 'Post Carousel', @todo
        
    ];
    
    // since 4.0
    return $layout_arr;
    
}
endif;

if ( ! function_exists( 'fox_social_support' ) ) :
/**
 * return string of all social brands supported
 * separated by comma
 * @since 4.0
 */
function fox_social_support() {
    
    $brands = 'facebook,twitter,instagram , pinterest, linkedin, youtube, snapchat, medium, reddit, whatsapp, soundcloud, spotify, tumblr, yelp, vimeo, telegram, vkontakte, google-play, twitch-tv, tripadvisor ,behance,bitbucket,delicious,deviantart,digg,dribbble,dropbox,etsy,flickr,foursquare,github,imdb,lastfm,meetup,paypal, quora,rss-2,scribd,skype,slack,slideshare,stack-exchange,stackoverflow, steam,wordpress,wordpress-com,yahoo,stumbleupon, amazon, vine, 500px, weibo';
    
    return apply_filters( 'fox_social_support', $brands );
    
}
endif;

if ( ! function_exists( 'fox_social_style_support' ) ) :
/**
 * return array of all possible social styles
 * @since 4.0
 */
function fox_social_style_support() {
    
    return [
        'plain' => 'Plain',
        'black' => 'Black',
        'outline' => 'Outline',
        'fill' => 'Fill',
        'text_color' => 'Brand Color',
        'color' => 'Brand Background',
    ];
    
}
endif;

if ( ! function_exists( 'fox_social_size_support' ) ) :
/**
 * return array of all possible social styles
 * @since 4.3
 */
function fox_social_size_support() {
    
    return [
        'small' => 'Small',
        'normal' => 'Normal',
        'bigger' => 'A bit bigger',
        'medium' => 'Medium',
    ];
    
}
endif;

if ( ! function_exists( 'fox_user_social_support' ) ) :
/**
 * return array of all social icons of user the theme supports
 * @since 4.0
 */
function fox_user_social_support() {
    
    return [
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'pinterest',
        'linkedin',
        'tumblr',
        'snapchat',
        'vimeo',
        'soundcloud',
        'flickr',
        'vkontakte',
        'spotify',
        'reddit',
        'whatsapp',
        'wechat',
        'weibo',
        'telegram'
    ];
    
}
endif;

/**
 * return array of available footer sidebar layouts
 * @since 4.0
 */
function footer_sidebar_layout_support() {
    
    return [
        '1-1-1-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/4-cols.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/4 + 1/4 + 1/4 + 1/4',
        ],
        
        '2-1-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2-1-1.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '2/4 + 1/4 + 1/4',
        ],
        
        '1-2-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-2-1.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/4 + 2/4 + 1/4',
        ],
        
        '1-1-2' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-1-2.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/4 + 1/4 + 2/4',
        ],
        
        '3-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3-1.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '3/4 + 1/4',
        ],
        
        '1-3' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-3.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/4 + 1/3',
        ],

        '1-1-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3-cols.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/3 + 1/3 + 1/3',
        ],
        
        '2-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2-1.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '2/3 + 1/3',
        ],
        
        '1-3' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-2.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/3 + 2/3',
        ],

        '1-1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2-cols.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => '1/2 + 1/2',
        ],

        '1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-col.jpg',
            'width' => '80',
            'height' => 'auto',
            'title' => 'Fullwidth',
        ],
        
    ];
    
}

if ( ! function_exists( 'fox_orderby_support' ) ) :    
/**
 * Orderby array
 * @since 4.0
 */
function fox_orderby_support() {
    
    return array(
        'date' => 'Date',
        'modified' => 'Updated Date',
        
        'view' => 'View count',
        'view_week' => 'View count (Weekly)',
        'view_month' => 'View count (Monthly)',
        'view_year' => 'View count (Yearly)',
        
        'title' => 'Post title',
        'rand' => 'Random',
        'comment_count' => 'Comment count',
        
        'review_score' => 'Review Score',
        'review_date' => 'Recent Review',
    );
    
}
endif;

if ( ! function_exists( 'fox_order_support' ) ) :    
/**
 * Order array
 * @since 4.0
 */
function fox_order_support() {
    
    return array(
        'asc' => 'Ascending',
        'desc' => 'Descending',
    );
    
}
endif;

/**
 * Sidebar positions that theme supports
 */
function fox_sidebar_possition_support( $args = [] ) {
    
    $positions = isset( $args[ 'main' ] ) ? $args[ 'main' ] : [ 'sidebar' => 'Main Sidebar' ];
    
    $positions = array_merge( $positions, [
        
        'before-header' => 'Before Header',
        'header' => 'After Logo',
        'after-header' => 'After Header',
        'footer-1' => 'Footer Sidebar 1',
        'footer-2' => 'Footer Sidebar 2',
        'footer-3' => 'Footer Sidebar 3',
        'footer-4' => 'Footer Sidebar 4',

        /*
         * deprecated since 4.3
         *
        'footer-bottom-stack' => 'Footer Bottom Stack',
        'footer-bottom-left' => 'Footer Bottom Left',
        'footer-bottom-right' => 'Footer Bottom Right',
        
        'header-builder' => 'MAIN HEADER BUILDER',
        */
        
    ] );
    
    return $positions;
    
}

/**
 * Return array of translation stirngs
 * @since 4.0
 */
function fox_quick_translation_support() {
    
    $strings = array(
        'more'                  =>  esc_html__( 'More', 'wi' ),
        'more_link'             =>  esc_html__( 'Keep Reading', 'wi' ),
        'read_more'             =>  esc_html__( 'Read More', 'wi' ),
        'previous'              =>  esc_html__( 'Previous', 'wi' ),
        'next'                  =>  esc_html__( 'Next', 'wi' ),
        'next_story'            =>  esc_html__( 'Next Story', 'wi' ),
        'previous_story'        =>  esc_html__( 'Previous Story', 'wi' ),
        'search'                =>  esc_html__( 'Type & hit enter', 'wi' ),
        'author'                =>  esc_html__( 'by %s', 'wi' ),
        'date'                  =>  esc_html__( 'Published on', 'wi' ),
        'latest_posts'          =>  esc_html__( 'Latest posts', 'wi' ),
        'viewall'               =>  esc_html__( 'View all', 'wi' ),
        'latest'                =>  esc_html__( 'Latest from %s', 'wi' ),
        'go'                    =>  esc_html__( 'Go to', 'wi' ),
        'top'                   =>  esc_html__( 'Top', 'wi' ),
        
        'related'               =>  esc_html__( 'You might be interested in', 'wi' ),
        'tag_label'             =>  esc_html__( 'Tags:', 'wi' ),
        'share_label'           =>  esc_html__( 'Share this', 'wi' ),
        'start'                 =>  esc_html__( 'Start', 'wi' ),
        
        'mins_read'             =>  esc_html__( '%s mins read', 'wi' ), // 4.1.1
        'min_read'              =>  esc_html__( '1 min read', 'wi' ), // 4.1.1
        'views'                 =>  esc_html__( '%s views', 'wi' ), // 4.1.1
        'sponsored'             =>  esc_html__( 'Sponsored', 'wi' ), // since 4.2
        
        'live' => esc_html__( 'Live', 'wi' ), // since 4.4
        
        'search_result' => esc_html__('Search result','wi'),
        'result_found' => esc_html__('%s result(s) found.','wi'), // since 4.4
        'browse_category' => esc_html__( 'Browse Category' , 'wi' ),
        'browse_tag' => esc_html__('Browse Tag','wi'),
        'browse_author' => esc_html__( 'Author','wi' ),
        'paged' => esc_html__( ' - Page %d','wi' ),
        
        // comment words
        'name' => esc_html__('Name','wi'),
        'email' => esc_html__('Email','wi'),
        'website' => esc_html__('Website','wi'),
        'write_comment' => esc_html__('Write your comment...','wi'),
        
        'post_comment' => esc_html__( 'Post Comment' ),
        'title_reply'          => esc_html__( 'Leave a Reply' ),
        'title_reply_to'          => esc_html__( 'Leave a Reply to %s' ),
        'cancel_reply' => esc_html__( 'Cancel reply' ),
        
    );
    
    foreach ( $strings as $k => $str ) {
        if ( function_exists( 'pll__' ) ) {
            $strings[ $k ] = pll__( $str );
        }
    }
    
    return apply_filters( 'fox_translation_strings', $strings );
    
}

if ( ! function_exists( 'fox_sidebar_state_support' ) ) :
/**
 * Sidebar Array Support
 * @since 4.0
 */
function fox_sidebar_state_support() {
    
    return [
        'sidebar-right' => 'Sidebar Right',
        'sidebar-left' => 'Sidebar Left',
        'no-sidebar' => 'No Sidebar',
    ];
    
}
endif;

if ( ! function_exists( 'fox_single_element_support' ) ) :
/**
 * Single Elements
 * @since 4.0
 */
function fox_single_element_support() {
    
    return [
        'post_header' => 'Title area',
        'thumbnail' => 'Thumbnail',
        'share' => 'Share icon',
        'tag' => 'Tags',
        'related' => 'Related Posts',
        'authorbox' => 'Author Box',
        'comment' => 'Comment Area',
        'nav' => 'Post Navigation',
        'bottom_posts' => 'Bottom Posts',
        'side_dock' => 'Sliding-up Box',
    ];
    
}
endif;

if ( ! function_exists( 'fox_builder_block_support' ) ) :
/**
 * Return array of the page builder block support
 * @since 4.0
 */
function fox_builder_block_support() {
    
    return apply_filters( 'fox_builder_block_support', array(
        'slider'                    =>  'Slider',
        'big-post'                  =>  'Big post',
        'grid-2'                    =>  'Grid 2 columns',
        'grid-3'                    =>  'Grid 3 columns',
        'grid-4'                    =>  'Grid 4 columns',
        
        'list'                      =>  'List style',
        'vertical'                  =>  'Post Vertical',
        'group-1'                   =>  'Post Group 1',
        'group-2'                   =>  'Post Group 2',
    ) );
    
}
endif;

/**
 * Primary Font Position
 * @since 4.0
 */
function fox_primary_font_support() {
    
    return [
        'body' => [
            'name' => 'Body Font',
        ],
        'heading' => [
            'name' => 'Heading Font',
        ],
        'nav' => [
            'name' => 'Navigation Font',
        ],
    ];
    
}

/**
 * return all possible font positions
 * and their values
 */
function fox_all_font_support() {
    
    $return = [
        
        /* ---------------------     GENERAL    -------------------- */
        'body' => [
            
            'name' => 'Body Font',
            'std' => 'Helvetica Neue',
            'selector' => fox_body_selector(),
            
            'primary' => true,
            
            'typo' => [
                
                'font-size' => '16',
                'font-size-phone' => '14',
                
                'font-weight' => '400',
                'font-style' => 'normal',
                'text-transform' => 'none',
                'letter-spacing' => '0',
                'line-height' => '1.8',
                
            ],
            
        ],
        
        'heading' => [
            'name' => 'Heading Font',
            'std' => 'Helvetica Neue',
            'selector' => fox_heading_selector(), // selector for font-family property
            'typo_selector' => 'h1, h2, h3, h4, h5, h6', // typo selector applies only for "real" heading elements
            
            'primary' => true,
            
            'typo' => [
                
                'font-weight' => '700',
                'font-style' => '',
                'text-transform' => 'none',
                'letter-spacing' => '',
                'line-height' => '1.3',
                
            ],
            
            'exclude' => [ 'size' ],
            
        ],
        
        'h2' => [
            'selector' => 'h2',
            'typo' => [
                'font-size' => '2.0625em',
            ],
            'include' => [ 'size' ],
        ],
        
        'h3' => [
            'selector' => 'h3',
            'typo' => [
                'font-size' => '1.625em',
            ],
            'include' => [ 'size' ],
        ],
        
        'h4' => [
            'selector' => 'h4',
            'typo' => [
                'font-size' => '1.25em',
            ],
            'include' => [ 'size' ],
        ],
        
        'logo' => [
            'name' => 'Logo Font',
            'std' => 'font_heading',
            'selector' => fox_logo_selector(),
            
            'primary' => true,
            
            'typo' => [
                
                'font-size' => '60',
                'font-size-tablet' => '40',
                'font-size-phone' => '20',
                'font-weight' => '400',
                'font-style' => 'normal',
                'text-transform' => 'uppercase',
                'letter-spacing' => '0',
                'line-height' => '1.1',
                
            ],
            
        ],
        
        'tagline' => [
            'name' => 'Tagline Font',
            'std' => 'font_heading',
            'selector' => '.slogan',
            
            'typo' => [
                
                'font-size' => '0.8125em',
                'font-weight' => '400',
                'font-style' => 'normal',
                'text-transform' => 'uppercase',
                'letter-spacing' => '6',
                'line-height' => '1.1',
                
            ],
            
        ],
        
        /* ---------------------     NAVIGATION    -------------------- */
        'nav' => [
            'name' => 'Navigation Font',
            'std' => 'Helvetica Neue',
            'selector' => fox_nav_selector(),
            
            'primary' => true,
            
            'typo' => [
                
                'font-size' => '16',
                'font-weight' => '',
                'font-style' => '',
                'text-transform' => 'none',
                'letter-spacing' => '0',
                'line-height' => '',
                
            ],
            
        ],
        
        'nav_submenu' => [
            'name' => 'Submenu Typography',
            'std' => 'font_nav',
            'selector' => fox_nav_submenu_selector(),
            'typo' => [
            ],
            
        ],
        
        /* ---------------------     BLOG    -------------------- */
        'post_title' => [
            'name' => 'Blog Post Title',
            'std' => 'font_heading',
            'selector' => '.post-item-title',
            'typo' => [
            ],
            'exclude' => [ 'size' ],
            
            'section' => 'typography_blog',
            'section_title' => 'Blog',
        ],
        
        'post_meta' => [
            'name' => 'Post Item Meta',
            'std' => 'font_body',
            'selector' => '.post-item-meta',
            'typo' => [
            ],
        ],
        
        'standalone_category' => [
            'name' => 'Standalone meta category',
            'std' => 'font_heading',
            'selector' => '.standalone-categories',
            'typo' => [
            ],
            
        ],
        
        'archive_title' => [
            
            'name' => 'Archive Title',
            'std' => 'font_heading',
            'selector' => '.archive-title',
            'typo' => [
            ],
            
        ],
        
        /* ---------------------     SINGLE    -------------------- */
        'single_post_title' => [
            'name' => 'Single Post Title',
            'std' => 'font_heading',
            'selector' => '.post-item-title.post-title, .page-title',
            'typo' => [
            ],
        ],
        
        'single_post_subtitle' => [
            'name' => 'Post Subtitle',
            'std' => 'font_body',
            'selector' => '.post-item-subtitle',
            'typo' => [
            ],
        ],
        
        'single_content' => [
            'name' => 'Single Post Content',
            'std' => 'font_body',
            'selector' => '.single .entry-content, .page .entry-content',
            'typo' => [
            ],
        ],
        
        'single_heading' => [
            'name' => 'Single Small Headings',
            'std' => 'font_heading',
            'selector' => '.single-heading',
            'typo' => [
                'font-size' => '1.5em',
                'font-weight' => '400',
                'font-style' => 'normal',
            ],
        ],
        
        /* ---------------------     OTHER ELEMENTS    -------------------- */
        'widget_title' => [
            'name' => 'Widget title',
            'std' => 'font_heading',
            'selector' => '.widget-title',
            'typo' => [
            ],
            
        ],
        
        // elementor for legacy from 4.2
        'elementor_heading' => [
            'name' => 'Builder Heading',
            'std' => 'font_heading',
            'selector' => '.section-heading h2',
            'typo' => [
            ],
            'exclude' => [ 'size' ],
        ],
        
        'button' => [
            'name' => 'Button',
            'std' => 'font_heading',
            'selector' => fox_btn_selector(),
            'typo' => [
            ],
            'exclude' => [ 'line-height' ],
        ],
        
        'input' => [
            'name' => 'Input',
            'std' => 'font_body',
            'selector' => fox_input_selector(),
            'typo' => [
            ],
            'exclude' => [ 'line-height' ],
        ],
        
        'blockquote' => [
            'name' => 'Blockquote',
            'std' => 'font_body',
            'selector' => 'blockquote',
            'typo' => [
            ],
            'exclude' => [ 'line-height' ],
        ],
        
        'dropcap' => [
            'selector' => fox_dropcap_selector(),
            'std' => 'font_body',
        ],
        
        'caption' => [
            'name' => 'Caption',
            'std' => 'font_body',
            'selector' => '.wp-caption-text, .post-thumbnail-standard figcaption, .wp-block-image figcaption, .blocks-gallery-caption',
            'typo' => [
            ],
            'exclude' => [],
        ],
        
        /* ---------------------     MOBILE    -------------------- */
        'offcanvas_nav' => [
            'name' => 'Offcanvas Menu',
            'std' => 'font_nav',
            'selector' => '.offcanvas-nav',
            'exclude' => [ 'line-height' ],
        ],
        
    ];
    
    /**
     * turn it into "useable" form
     */
    $all_fields = [ 'size', 'weight', 'style', 'text-transform', 'letter-spacing', 'line-height' ];
    foreach ( $return as $id => $fontdata ) {
        
        // fields to include/exclude in typography
        $include = isset( $fontdata[ 'include' ] ) ? $fontdata[ 'include' ] : [];
        $exclude = isset( $fontdata[ 'exclude' ] ) ? $fontdata[ 'exclude' ] : [];
        $typo = isset( $fontdata[ 'typo' ] ) ? $fontdata[ 'typo' ] : [];
        
        if ( ! empty( $include ) ) {
            
            $fontdata[ 'fields' ] = $include;
            
        } elseif ( ! empty( $exclude ) ) {
            
            $fields = array_values( array_diff( $all_fields, $exclude ) );
            $fontdata[ 'fields' ] = $fields;
            
        } else {
            
            $fontdata[ 'fields' ] = $all_fields;
        
        }
        
        // typo default select values
        if ( ! isset( $typo[ 'font-style' ] ) ) {
            $typo[ 'font-style' ] = 'normal';
        }
        
        // and typo std value
        $typo = json_encode( $typo );
        $fontdata[ 'typo' ] = $typo;
        
        $return[ $id ] = $fontdata;
    
    }
    
    return $return;
    
}

/**
 * since 4.0
 */
function fox_shape_support() {
    return [
        'acute' => 'Acute',
        'round' => 'Round',
        'circle' => 'Circle',
    ];
}

function fox_get_params( $base = '' ) {
    
    $params = [];
    
    /* STANDARD
    ------------------------------------------------------------------------------------------------------------------------ */
    if ( 'standard' == $base ) {
    
        $params[] = [
            'type' => 'heading',
            'title' => 'General Layout',
        ];
        
        $params[ 'header_align' ] = array(
            'title' => 'Header text align',
            'type' => 'select',
            'options' => array(
                '' => 'Default',
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'std' => '',
        );
        
        $params[ 'text_color' ] = array(
            'title' => 'Custom Text Color',
            'type' => 'color',
        );
        
        $params[ 'content_excerpt' ] = array(
            'title' => 'Display content/excerpt',
            'type' => 'select',
            'options' => [
                'content' => 'Full Content',
                'excerpt' => 'Excerpt',
            ],
            'std' => 'excerpt',
        );
        
        $params[ 'thumbnail_type' ] = array (
            'title' => 'Thumbnail type',
            'type' => 'select',
            'options' => [
                'advanced' => 'Full slider, video etc',
                'simple' => 'Just simple image thumbnail',
            ],
            'std' => 'simple',
        );
        
        /* Elements
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Elements',
        ];
        
        $params[ 'show_thumbnail' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show thumbnail',
        );
        
        $params[ 'thumbnail_shape' ] = array(
            'type'  => 'select',
            'std'   => 'acute',
            'options' => [
                'acute'     => 'Acute',
                'round'     => 'Round',
                'circle'    => 'Circle',
            ],
            'title' => 'Thumbnail shape',
        );
        
        $params[ 'show_title' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show title',
        );
        
        $params[ 'excerpt_more' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show excerpt more',
        );
        
        $params[ 'show_date' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show date',
        );
        
        $params[ 'show_category' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show category',
        );
        
        $params[ 'show_author' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show author',
        );
        
        $params[ 'show_author_avatar' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show author avatar',
        );
        
        $params[ 'show_comment_link' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show comment link',
        );
        
        $params[ 'show_view' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show view count',
        );
        
        $params[ 'show_reading_time' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show reading time',
        );
        
        $params[ 'show_share' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show share icons',
        );
        
        $params[ 'show_related' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show related posts',
        );
    
    }
    
    /* GRID
    ------------------------------------------------------------------------------------------------------------------------ */
    if ( 'grid' == $base ) {
        
        /* General Layout
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'General Layout',
        ];
        
        $params[ 'column' ] = array(
            'type' => 'select',
            'options' => [
                '1' => '1 column',
                '2' => '2 columns',
                '3' => '3 columns',
                '4' => '4 columns',
                
                // since 4.2
                '5' => '5 columns',
            ],
            'std' => '3',
            'title' => 'Column',
        );
        
        $params[ 'item_spacing' ] = array(
            'type' => 'select',
            'options' => [
                'none' => 'No spacing',
                'tiny' => 'Tiny',
                'small' => 'Small',
                'normal' => 'Normal',
                'wide' => 'Wide',
                'wider' => 'Wider',
            ],
            'std' => 'normal',
            'title' => 'Item spacing (Grid/Masonry)',
        );
        
        $params[ 'item_align' ] = array(
            'title' => 'Item alignment (Grid/Masonry)',
            'type' => 'select',
            'options' => array(
                '' => 'Default',
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'std' => '',
        );
        
        $params[ 'item_template' ] = array(
            'title' => 'Elements order',
            'type' => 'select',
            
            'options' => array(
                '1' => 'Title > Meta > Excerpt',
                '2' => 'Meta > Title > Excerpt',
                '3' => 'Title > Excerpt > Meta',

                '4' => 'Category > Title > Meta > Excerpt',
                '5' => 'Category > Title > Excerpt > Meta',
            ),
            
            'std' => '1',
        );
        
        $params[ 'text_color' ] = array(
            'title' => 'Custom Text Color',
            'type' => 'color',
        );
        
        $params[ 'list_sep' ] = array(
            'type' => 'switcher',
            'title' => 'Sep between list items',
            'std' => 'yes',
        );
        
        $params[ 'list_spacing' ] = array(
            'type' => 'select',
            'title' => 'Spacing between list items',
            'std' => 'normal',
            'options' => [
                'none' => 'No Spacing',
                'tiny' => 'Tiny',
                'small' => 'Small',
                'normal' => 'Normal',
                'medium' => 'Medium',
                'large' => 'Large',
            ],
            'std' => 'normal',
        );
        
        $params[ 'list_valign' ] = array(
            'type' => 'select',
            'title' => 'Post list vertical align',
            'std' => 'top',
            'options' => [
                'top' => 'Top',
                'middle' => 'Middle',
                'bottom' => 'Bottom',
            ],
            'std' => 'top',
        );
    
        /* Thumbnail
         * ------------------------ */
        $params[ 'thumbnail_heading' ] = [
            'type' => 'heading',
            'title' => 'Thumbnail',
            
            'section' => 'thumbnail',
            'section_title' => 'Thumbnail',
        ];
        
        $params[ 'show_thumbnail' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show thumbnail',
        );
        
        $params[ 'thumbnail_position' ] = array(
            'type' => 'select',
            'options'   => [
                'left' => 'Left',
                'right' => 'Right',
            ],
            'std' => 'left',
            'title' => 'Thumbnail position (List/Vertical layout)',
        );
        
        $params[ 'thumbnail_width' ] = array(
            'type' => 'text',
            'placeholder' => 'Eg. 320px',
            'title' => 'Thumbnail width (List layout)',
        );
        
        $params[ 'list_mobile_layout' ] = array(
            'type' => 'select',
            'title' => 'Mobile Layout (for List layout)',
            'options' => [
                'list' => 'Still list as desktop',
                'grid' => 'Stack (Image above, content below)',
            ],
            'std'   => 'grid',
        );
        
        $params[ 'thumbnail' ] = array(
            'type' => 'select',
            'options' => [
                'landscape' => 'Medium (480x384)',
                'square' => 'Square',
                'portrait' => 'Portrait',
                
                'thumbnail-large' => 'Large (720x480)',
                
                'original' => 'Original ratio (Fullwidtn)',
                'original_fixed' => 'Original ratio (Fixed height)',
                'custom' => 'Custom',
            ],
            'std' => 'landscape',
            'title' => 'Thumbnail',
        );

        $params[ 'thumbnail_custom' ] = array(
            'type' => 'text',
            'title' => 'Custom Size (Eg. 425x360)',
        );

        $params[ 'thumbnail_placeholder' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Placeholder thumbnail',
            'desc' => 'For posts dont have a thumbnail'
        );

        $params[ 'thumbnail_shape' ] = array(
            'type'  => 'select',
            'std'   => 'acute',
            'options' => [
                'acute'     => 'Acute',
                'round'     => 'Round',
                'circle'    => 'Circle',
            ],
            'title' => 'Thumbnail shape',
        );
        
        $params[ 'thumbnail_hover_effect' ] = array(
            'type'  => 'select',
            'std'   => 'none',
            'options' => [
                'none'      => 'None',
                'fade'      => 'Image Fade',
                'dark'      => 'Dark',
                'letter'    => 'Title First Letter',
                'zoomin'    => 'Image Zoom In',
                'logo'      => 'Custom Logo',
            ],
            'title' => 'Thumbnail hover effect',
        );
        
        $params[ 'thumbnail_hover_logo' ] = array(
            'type'  => 'image',
            'title' => 'Thumbnail hover logo',
        );
        
        $params[ 'thumbnail_hover_logo_width' ] = array(
            'type'  => 'text',
            'std'   => '40%',
            'title' => 'Thumbnail hover logo width',
            'desc' => 'Please enter a number in percentage.',
        );
        
        $params[ 'format_indicator' ] = array(
            'type'  => 'switcher',
            'std'   => 'yes',
            'title' => 'Show format indicator',
        );
        
        $params[ 'thumbnail_index' ] = array(
            'type'  => 'switcher',
            'std'   => '',
            'title' => 'Index 01, 02.. on thumbnail',
        );
        
        $params[ 'thumbnail_view' ] = array(
            'type'  => 'switcher',
            'std'   => '',
            'title' => 'Display view count',
        );
        
        /* Title
         * ------------------------ */
        $params[ 'title_heading' ] = [
            'type' => 'heading',
            'title' => 'Title',
            
            'section' => 'text',
            'section_title' => 'Text',
        ];
        
        $params[ 'show_title' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show title',
        );
        
        $params[ 'title_tag' ] = array(
            'type' => 'select',
            'options' => [
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
            ],
            'std' => 'h2',
            'title' => 'Title tag',
        );
        
        $params[ 'title_size' ] = array(
            'type'  => 'select',
            'std'   => 'normal',
            'options' => [
                'supertiny' => 'Super Tiny',
                'tiny' => 'Tiny',
                'small' => 'Small',
                'normal' => 'Normal',
                'medium' => 'Medium',
                'large' => 'Large',
            ],
            'title' => 'Title size',
        );
        
        $params[ 'title_weight' ] = array(
            'type'  => 'select',
            'std'   => '',
            'options' => [
                '' => 'Default',
                '300' => 'Light',
                '400' => 'Normal',
                '700' => 'Bold',
            ],
            'title' => 'Title weight',
        );
        
        $params[ 'title_text_transform' ] = array(
            'type'  => 'select',
            'std'   => '',
            'options' => [
                '' => 'Default',
                'none' => 'None',
                'lowercase' => 'lowercase',
                'uppercase' => 'UPPERCASE',
                'capitalize' => 'Capitalize',
            ],
            'title' => 'Title text transform',
        );
        
        /* Excerpt
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Excerpt',
        ];
        
        $params[ 'show_excerpt' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Display excerpt?',
        );

        $params[ 'excerpt_length' ] = array(
            'type' => 'text',
            'std' => '22',
            'title' => 'Excerpt length',
        );
        
        $params[ 'excerpt_size' ] = array(
            'type' => 'select',
            'options' => [
                'small' => 'Small',
                'normal' => 'Normal',
                'medium' => 'Medium',
            ],
            'title' => 'Excerpt font size',
            'std'   => 'normal',
        );
        
        $params[ 'excerpt_color' ] = array(
            'type' => 'color',
            'title' => 'Excerpt custom color',
        );

        $params[ 'excerpt_more' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'More Link',
        );
        
        $params[ 'excerpt_more_style' ] = array(
            'type' => 'select',
            'options' => [
                'simple' => 'Plain Link',
                'simple-btn' => 'Minimal Link', // simple button
                'btn' => 'Fill Button', // default btn
                'btn-black' => 'Solid Black Button',
                'btn-primary' => 'Primary Button',
            ],
            'std' => 'simple',
            'title' => 'More text style',
        );
        
        $params[ 'excerpt_more_text' ] = array(
            'type' => 'text',
            'placeholder' => 'More',
            'title' => 'Custom More Text',
        );
        
        /* Date
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Date',
        ];
        
        $params[ 'show_date' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show post date',
        );
        
        /* Category
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Category',
        ];
        
        $params[ 'show_category' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show post categories',
        );

        /* Author
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Author',
        ];
        
        $params[ 'show_author' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show post author',
        );

        $params[ 'show_author_avatar' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show author avatar',
        );

        /* View count
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'View Count',
        ];
        
        $params[ 'show_view' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show view count',
        );

        /* Comment link
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Comment Link',
        ];
        
        $params[ 'show_comment_link' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show comment link',
        );

        /* Reading Time
         * ------------------------ */
        $params[] = [
            'type' => 'heading',
            'title' => 'Reading Time',
        ];
        
        $params[ 'show_reading_time' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show reading time',
        );
    
    }
    
    return $params;

}

/** 
 * Returns blog grid options, used commonly in both Elementor and Customizer
 * @since 4.0
 */
function fox_generate_options( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'base' => '',
        'exclude' => [],
        'override' => [],
        'append' => [],
        'context' => 'customizer',
        'prefix' => '',
        
        // general for all
        'section' => '',
        'section_title' => '',
        'panel' => '',
        
    ] ) );
    
    $params = fox_get_params( $base );
    if ( ! $params ) return [];
    
    // exclude
    if ( ! empty( $exclude ) ) {
        foreach ( $params as $id => $param ) {
            if ( in_array( $id, $exclude ) ) unset( $params[ $id ] );
        }
    }
    
    // override
    if ( ! empty( $override ) ) {
        foreach ( $override as $id => $param ) {
            if ( isset( $params[ $id ] ) ) {
                $params[ $id ] = wp_parse_args( $param, $params[ $id ] );
            } else {
                $params[ $id ] = $param;
            }
        }
    }
    
    // append
    if ( ! empty( $append ) ) {
        
        $append_arr = [];
        $current_after = 'allllll';
        
        foreach ( $append as $id => $param ) {
            $after = isset( $param[ 'after' ] ) ? $param[ 'after' ] : '';
            if ( $after ) {
                $current_after = $after;
                if ( ! isset( $append_arr[ $current_after ] ) ) $append_arr[ $current_after ] = [];
            }
            $append_arr[ $current_after ][ $id ] = $param;
        }
        
        $final_params = [];
        foreach ( $params as $id => $param ) {
            $final_params[ $id ] = $param;
            if ( isset( $append_arr[ $id ] ) ) {
                foreach ( $append_arr[ $id ] as $more_id => $more_param ) {
                    $final_params[ $more_id ] = $more_param;
                }
            }
        }
        if ( isset( $append_arr[ 'allllll' ] ) ) {
            
            $final_params += $append_arr[ 'allllll' ];
            
        }
        
        // restore it
        $params = $final_params;
        
    }
    
    /* Return param list for elementor
    ---------------------------------------- */
    if ( 'elementor' == $context ) {
    
        $return = [];
        
        foreach ( $params as $id => $param ) {
            
            extract( wp_parse_args( $param, [
                'name' => '',
                'std' => '',
            ] ) );
            
            /// title becomes name
            if ( $name ) {
                unset( $param[ 'name' ] );
                $param[ 'title' ] = $title;
            }
            
            $final_id = $id;
            if ( is_numeric( $id ) ) {
                $final_id = 'heading_' . $id;
            }
            
            // prefix
            if ( $prefix ) $final_id = $prefix . $final_id;
            
            // section, panel
            if ( $section ) {
                $param[ 'section' ] = $section;
                $section = ''; // elementor is weird while we can't redeclare section
                if ( $section_title ) {
                    $param[ 'section_title' ] = $section_title;
                    $section_title = '';
                }
            }
            
            $return[ $final_id ] = $param;
        
        }
        
        return $return;
        
        
    /* Return param list for customizer
    ---------------------------------------- */
    } elseif ( 'customizer' == $context ) {
        
        $return = [];
        
        foreach ( $params as $id => $param ) {
            
            extract( wp_parse_args( $param, [
                'type' => '',
                'title' => '',
                'std' => '',
            ] ) );
            
            /// switcher becomes enable shorthand
            if ( 'switcher' == $type ) {
                unset( $param[ 'type' ] );
                $param[ 'shorthand' ] = 'enable';
                if ( 'yes' == $std ) {
                    $param[ 'std' ] = 'true';
                } else {
                    $param[ 'std' ] = 'false';
                }
            }
            
            /// title becomes name
            if ( $title ) {
                unset( $param[ 'title' ] );
                $param[ 'name' ] = $title;
            }
            
            // prefix
            $final_id = $id;
            if ( $prefix ) $final_id = $prefix . $final_id;
            
            // section, panel
            if ( $section ) $param[ 'section' ] = $section;
            if ( $section_title ) $param[ 'section_title' ] = $section_title;
            if ( $panel ) $param[ 'panel' ] = $panel;
            
            $return[ $final_id ] = $param;
        
        }
        
        return $return;
        
    }
       
}

/**
 * Box Element Support
 * @since 4.0
 */
function fox_all_box_elements_support() {
    
    $return = [];
    
    $return[ 'logo' ] = [
        'selector' => '.fox-logo',
    ];    
    
    $return[ 'before_header' ] = [
        'selector' => '#before-header .container',
    ];
    
    $return[ 'after_header' ] = [
        'selector' => '#after-header .container',
    ];
    
    $return[ 'main_header' ] = [
        'selector'  => '#main-header .container',
    ];
    
    $return[ 'footer_sidebar' ] = [
        'selector'  => '#footer-widgets',
    ];
    
    $return[ 'footer_bottom' ] = [
        'selector'  => '#footer-bottom',
    ];
    
    $return[ 'titlebar' ] = [
        'selector' => '#titlebar .container',
    ];
    
    $return[ 'titlebar_outer' ] = [
        'selector' => '#titlebar',
    ];
    
    $return[ 'post_header' ] = [
        'selector' => '.single-header .container',
    ];
    
    $return[ 'single_heading' ] = [
        'selector' => '.single-authorbox-section, .related-heading, .comments-title, .comment-reply-title',
    ];
    
    $return[ 'all' ] = [
        'selector' => '.wi-all',
    ];
    
    $return[ 'wrapper' ] = [
        'selector'  => 'body.layout-boxed .wi-wrapper',
    ];
    
    $return[ 'nav_submenu' ] = [
        'selector'  => '.wi-mainnav ul.menu ul',
    ];
    
    $return[ 'nav_submenu_item' ] = [
        'selector'  => '.wi-mainnav ul.menu ul a',
    ];
    
    $return[ 'widget_title' ] = [
        'selector'  => '.widget-title',
    ];
    
    $return[ 'input' ] = [
        'selector'  => fox_input_selector(),
    ];
    
    $return[ 'blockquote' ] = [
        'selector'  => 'blockquote',
    ];
    
    return $return;
    
}

/**
 * Background Element Support
 * @since 4.0
 */
function fox_all_background_elements_support() {
    
    $return = [];
    
    $return[ 'body' ] = [
        'selector' => 'body.layout-boxed',
    ];
    
    $return[ 'footer_sidebar' ] = [
        'selector' => '#footer-widgets',
    ];
    
    $return[ 'footer_bottom' ] = [
        'selector' => '#footer-bottom',
    ];
    
    $return[ 'offcanvas' ] = [
        'selector' => '#offcanvas-bg',
    ];
    
    return $return;
    
}