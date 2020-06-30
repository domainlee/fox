<?php
/**
 * blog control components
 * return @array of compoents
 * @since 4.0
 */
function fox_component_params( $exclude = [], $override = [] ) {
    
    $params = [];
    
    $params[ 'show_thumbnail' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'Show thumbnail',
        
        'section' => 'components',
        'section_title' => 'components',
    );
    
    $params[ 'show_title' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'Show title',
    );
    
    $params[ 'excerpt' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'Display excerpt?',
    );
    
    $params[ 'excerpt_length' ] = array(
        'type' => 'text',
        'std' => '22',
        'title' => 'Excerpt length',
    );
    
    $params[ 'more' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'More Link',
    );
    
    $params[ 'show_date' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'Show post date',
    );
    
    $params[ 'show_category' ] = array(
        'type' => 'switcher',
        'std' => 'yes',
        'title' => 'Show post categories',
    );
    
    $params[ 'show_author' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Show post author',
    );
    
    $params[ 'show_author_avatar' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Show author thumbnail',
    );
    
    $params[ 'show_view' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Show view count',
    );
    
    $params[ 'show_comment_link' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Show comment link',
    );
    
    $params[ 'show_reading_time' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Show reading time',
    );
    
    // exclude
    // and set section 'query'
    $first = true;
    foreach ( $params as $id => $param ) {
        if ( in_array( $id, $exclude ) ) {
            unset( $params[ $id ] );
        } elseif ( $first ) {
            $params[ $id ][ 'section' ] = 'components';
            $params[ $id ][ 'section_title' ] = 'Components';
            $first = false;
        }
    }
    
    // OVERRIDE
    foreach ( $params as $id => $arr ) {
        if ( isset( $override[ $id ] ) ) $params[ $id ] = $override[ $id ];
    }
    
    return apply_filters( 'fox_default_component_params', $params );
    
}

/* THUMBNAIL
------------------------------------------------------------------------------------------------------------------------------------ */
if ( ! function_exists( 'fox_thumbnail_params' ) ) :
/**
 * Thumbnail params
 * @since 4.0
 */
function fox_thumbnail_params( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'include' => [],
        'exclude' => [],
        'override' => []
    ] ) );
    
    $params = [];
    
    $params[ 'thumbnail' ] = array(
        'type' => 'select',
        'options' => [
            'landscape' => 'Landscape',
            'square' => 'Square',
            'portrait' => 'Portrait',
            'original' => 'Original (No Crop)',
            'custom' => 'Custom',
        ],
        'std' => 'landscape',
        'title' => 'Thumbnail',
        
        'section' => 'thumbnail',
        'section_title' => 'Thumbnail',
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
    
    // only include
    if ( ! empty( $include ) ) {
        foreach ( $params as $id => $param ) {
            if ( ! in_array( $id, $include ) ) unset( $params[ $id ] );
        }
    }
    
    // exclude
    if ( ! empty( $exclude ) ) {
        foreach ( $params as $id => $param ) {
            if ( in_array( $id, $exclude ) ) unset( $params[ $id ] );
        }
    }
    
    // override
    if ( ! empty( $override ) ) {
        foreach ( $override as $id => $param ) {
            $params[ $id ] = $param;
        }
    }
    
    // name vs title
    // and id
    foreach ( $params as $id => $param ) {
        
        // to use in widget / metabox
        $param[ 'id' ] = $id;
        
        // name vs title
        if ( isset( $param[ 'title' ] ) ) $param[ 'name' ] = $param[ 'title' ];
        elseif ( isset( $param[ 'name' ] ) ) $param[ 'title' ] = $param[ 'name' ];
        
        $params[ $id ] = $param;
        
    }
    
    return apply_filters( 'fox_thumbnail_params', $params );
    
}
endif;

function fox_post_group1_params() {
    
    $params = fox_query_params();

    /* General
    ------------------------------------ */
    $params[ 'bigpost_ratio' ] = array(
        'type' => 'select',
        'title' => 'Big post ratio',
        'options' => array(
            '2/3' => '2/3',
            '3/4' => '3/4',
        ),
        'std' => '2/3',

        'section' => 'layout',
        'section_title' => 'Layout',
    );

    $params[ 'bigpost_position' ] = array(
        'type' => 'select',
        'title' => 'Big post position',
        'options' => array(
            'left'  => 'Left',
            'right' => 'Right',
        ),
        'std' => 'left',
    );

    $params[ 'text_color' ] = array(
        'type' => 'color',
        'title' => 'Custom Text Color',
    );

    $params[ 'sep_border' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Border between big posts & small posts',
    );

    $params[ 'sep_border_color' ] = array(
        'type' => 'color',
        'title' => 'Border color',
    );

    /* Big Post
    ------------------------------------ */
    $params += fox_generate_options([
        'base' => 'grid',
        'prefix' => 'bigpost_',
        'context' => 'elementor',

        'exclude' => [
            'column',
            'item_spacing',
            'text_color',
            'list_sep',
            'list_spacing',
            'list_valign',
            'thumbnail_position',
            'thumbnail_width',
            'list_mobile_layout',
            'thumbnail_placeholder',
            
            'thumbnail_heading',
            'title_heading',
        ],

        'append' => [

            'thumbnail_type' => [
                'title' => 'Thumbnail Type',
                'type' => 'select',
                'options' => [
                    'advanced' => 'Full slider, video etc',
                    'simple' => 'Just simple image thumbnail',
                ],
                'std' => 'simple',
                'after' => 'show_thumbnail',
            ],

        ],

        'override' => [
            'title_size' => [
                'std' => 'medium',
            ],
            'item_align' => [
                'title' => 'Item alignment',
                'std' => 'center',
            ],
            'thumbnail' => [
                'options' => [
                    'thumbnail-large' => 'Large (720x480)',
                    'large' => 'Large original ratio',
                    'custom' => 'Custom',
                ],
                'std' => 'thumbnail-large',
            ],
            'excerpt_more_style' => [
                'std' => 'btn'
            ],
        ],

        'section' => 'bigpost',
        'section_title' => 'Big Post',
    ]);

    /* Small Posts
    ------------------------------------ */
    $params += fox_generate_options([
        'base' => 'grid',
        'prefix' => 'small_posts_',
        'context' => 'elementor',

        'exclude' => [
            'column',
            'item_spacing',
            'item_align',
            'text_color',
            
            'thumbnail_heading',
            'title_heading',
        ],

        'override' => [
            'title_size' => [
                'std' => 'small',
            ],
            'excerpt_length' => [
                'std' => 12,
            ],
            'excerpt_more' => [
                'std' => '',
            ],
            'thumbnail_position' => [
                'title' => 'Thumbnail Position',
            ],
            'thumbnail_width' => [
                'title' => 'Thumbnail Width',
            ],
            'list_mobile_layout' => [
                'title' => 'Layout on mobile',
                'std' => 'list',
            ],
            'show_category' => [
                'std' => '',
            ],
        ],

        'section' => 'smallposts',
        'section_title' => 'Small Posts',
    ]);
    
    return apply_filters( 'fox_params', $params, 'post_group1' );
    
}

function fox_post_group2_params() {

    $params = fox_query_params();

    /* General
    ------------------------------------ */
    $params[ 'columns_order' ] = array(
        'type' => 'select',
        'title' => 'Columns Order',
        'options' => array(
            '1a-1b-3'  => 'Big / Medium / Small posts',
            '1b-1a-3'  => 'Medium / Big / Small posts',

            '1a-3-1b'  => 'Big / Small posts / Medium',
            '1b-3-1a'  => 'Medium / Small posts / Big',

            '3-1a-1b'  => 'Small posts / Big / Medium',
            '3-1b-1a'  => 'Small posts / Medium / Big',
        ),
        'std' => '1a-3-1b',

        'section' => 'layout',
        'section_title' => 'Layout',
    );

    $params[ 'text_color' ] = array(
        'type' => 'color',
        'title' => 'Custom Text Color',
    );

    $params[ 'sep_border' ] = array(
        'type' => 'switcher',
        'std' => '',
        'title' => 'Border between big posts & small posts',
    );

    $params[ 'sep_border_color' ] = array(
        'type' => 'color',
        'title' => 'Border color',
    );

    /* Big Post
    ------------------------------------ */
    $params += fox_generate_options([
        'base' => 'grid',
        'prefix' => 'bigpost_',
        'context' => 'elementor',

        'exclude' => [
            'column',
            'item_spacing',
            'text_color',
            'list_sep',
            'list_spacing',
            'list_valign',
            'thumbnail_position',
            'thumbnail_width',
            'list_mobile_layout',
            'thumbnail_placeholder',
            
            'thumbnail_heading',
            'title_heading',
        ],

        'append' => [

            'thumbnail_type' => [
                'title' => 'Thumbnail Type',
                'type' => 'select',
                'options' => [
                    'advanced' => 'Full slider, video etc',
                    'simple' => 'Just simple image thumbnail',
                ],
                'std' => 'simple',
                'after' => 'show_thumbnail',
            ],

        ],

        'override' => [
            'title_size' => [
                'std' => 'medium',
            ],
            'item_align' => [
                'title' => 'Item alignment',
                'std' => 'center',
            ],
            'thumbnail' => [
                'options' => [
                    'thumbnail-large' => 'Large (720x480)',
                    'large' => 'Large original ratio',
                    'custom' => 'Custom',
                ],
                'std' => 'thumbnail-large',
            ],
            'excerpt_more_style' => [
                'std' => 'btn'
            ],
        ],

        'section' => 'bigpost',
        'section_title' => 'Big Post',
    ]);

    /* Medium Post
    ------------------------------------ */
    $params += fox_generate_options([
        'base' => 'grid',
        'prefix' => 'medium_post_',
        'context' => 'elementor',

        'exclude' => [
            'column',
            'item_spacing',
            'text_color',
            'list_sep',
            'list_spacing',
            'list_valign',
            'thumbnail_position',
            'thumbnail_width',
            'list_mobile_layout',
            'thumbnail_placeholder',
            
            'thumbnail_heading',
            'title_heading',
        ],

        'override' => [
            'title_size' => [
                'std' => 'small',
            ],
            'excerpt_length' => [
                'std' => 32,
            ],
            'show_category' => [
                'std' => '',
            ],
            'thumbnail' => [
                'options' => [
                    'landscape' => 'Landscape',
                    'square' => 'Square',
                    'portrait' => 'Portrait',

                    'medium' => 'Medium',
                    'large' => 'Large',
                    'custom' => 'Custom',
                ],
                'std' => 'medium',
            ],
        ],

        'section' => 'medium_post',
        'section_title' => 'Medium Post',
    ]);

    /* Small Posts
    ------------------------------------ */
    $params += fox_generate_options([
        'base' => 'grid',
        'prefix' => 'small_posts_',
        'context' => 'elementor',

        'exclude' => [
            'column',
            'item_spacing',
            'text_color',
            'list_sep',
            'list_spacing',
            'list_valign',
            'thumbnail_position',
            'thumbnail_width',
            'list_mobile_layout',
            'thumbnail_placeholder',
            
            'thumbnail_heading',
            'title_heading',
        ],

        'override' => [
            'title_size' => [
                'std' => 'tiny',
            ],
            'show_excerpt' => [
                'std' => '',
            ],
            'excerpt_length' => [
                'std' => 12,
            ],
            'excerpt_more' => [
                'std' => '',
            ],
            'show_category' => [
                'std' => '',
            ],
            'thumbnail' => [
                'options' => [
                    'landscape' => 'Landscape',
                    'square' => 'Square',
                    'portrait' => 'Portrait',

                    'medium' => 'Medium',
                    'large' => 'Large',
                    'custom' => 'Custom',
                ],
                'std' => 'landscape',
            ],
        ],

        'section' => 'smallposts',
        'section_title' => 'Small Posts',
    ]);
    
    return apply_filters( 'fox_params', $params, 'post_group2' );
    
}

/**
 * Return blog params
 */
function fox_blog_params( $layout = '' ) {
    
    $params = [];
    
    if ( 'grid' == $layout ) {
        
        $params = fox_query_params();
        $params += fox_generate_options([
            'base' => 'grid',
            'exclude' => [
                'thumbnail_position',
                'thumbnail_width',
                'list_sep',
                'list_mobile_layout',
                'list_spacing',
                'list_valign',
            ],
            'override' => [
                'item_spacing' => [
                    'title' => 'Item Spacing'
                ],
                'item_align' => [
                    'title' => 'Item Align',
                ],
            ],
            'context' => 'elementor',

            'section' => 'layout',
            'section_title' => 'Layout',
        ]);
        
    }
    
    if ( 'list' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([

            'base' => 'grid',
            'exclude' => [
                'column',
                'item_spacing',
                'item_align',
            ],

            'override' => [
                'thumbnail_width' => [
                    'title' => 'Thumbnail Width',
                ],
                'thumbnail_position' => [
                    'title' => 'Thumbnail Position',
                ],
                'list_mobile_layout' => [
                    'title' => 'Mobile Layout',
                ],
            ],

            'context' => 'elementor',
            'section' => 'layout',
            'section_title' => 'Layout',

        ]);
    
    }
    
    if ( 'masonry' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([
            'base' => 'grid',
            'exclude' => [
                'thumbnail_position',
                'thumbnail_width',
                'list_mobile_layout',
                'list_sep',
                
                'list_spacing',
                'list_valign',

                'thumbnail', 
                'thumbnail_custom',
                'thumbnail_placeholder',
            ],
            'override' => [
                'item_spacing' => [
                    'title' => 'Item Spacing'
                ],
                'item_align' => [
                    'title' => 'Item Align',
                ],
            ],
            'append' => [
                'big_first_post' => [
                    'title' => 'Big first post',
                    'type' => 'switcher',
                    'std' => 'yes',
                    'after' => 'column',
                ],
            ],
            'context' => 'elementor',

            'section' => 'layout',
            'section_title' => 'Layout',
        ]);
    
    }
    
    if ( 'newspaper' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([
            'base' => 'standard',
            'context' => 'elementor',

            'override' => [
                'thumbnail_type' => [
                    'std' => 'simple',
                ]
            ],

            'section' => 'layout',
            'section_title' => 'Layout',
        ]);
    
    }
    
    if ( 'standard' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([
            'base' => 'standard',
            'context' => 'elementor',

            'section' => 'layout',
            'section_title' => 'Layout',
        ]);
    
    }
    
    if ( 'vertical' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([

            'base' => 'grid',
            'exclude' => [
                'column',
                'item_spacing',
                'item_align',
                'list_spacing',
                'list_valign',
                'thumbnail_width',
                'thumbnail_placeholder',
                'thumbnail_shape',
                'list_mobile_layout',
                'title_size',
            ],
            'override' => [
                'thumbnail_position' => [
                    'title' => 'Thumbnail Position',
                ],
                'thumbnail' => [
                    'options' => [
                        'landscape' => 'Landscape',
                        'square' => 'Square',
                        'portrait' => 'Portrait',
                        'large' => 'Original (No Crop)',
                        'custom' => 'Custom',
                    ],
                    'std' => 'large',
                ],
            ],

            'append' => [
                'thumbnail_type' => array (
                    'title' => 'Thumbnail type',
                    'type' => 'select',
                    'options' => [
                        'advanced' => 'Full slider, video etc',
                        'simple' => 'Just simple image thumbnail',
                    ],
                    'std' => 'simple',
                    'after' => 'show_thumbnail',
                )
            ],

            'context' => 'elementor',
            'section' => 'layout',
            'section_title' => 'Layout',

        ]);
    
    }
    
    if ( 'big' == $layout ) {
    
        $params = fox_query_params();
        $params += fox_generate_options([
            'base' => 'standard',
            'context' => 'elementor',

            'exclude' => [
                'header_align', 
                'thumbnail_type',
                'show_thumbnail',
                'show_share',
                'show_related'
            ],

            'override' => [
                'show_author' => [
                    'std' => '',
                ],
            ],

            'append' => [

                'thumbnail' => [
                    'type' => 'select',
                    'title' => 'Thumbnail',
                    'std' => 'original',
                    'after' => 'thumbnail_shape',
                    'options' => [
                        'original' => 'Original Size',
                        'large' => 'Large',
                        'custom' => 'Custom',
                    ],
                ],

                'thumbnail_custom' => [
                    'type' => 'text',
                    'title' => 'Custom Size (Eg. 425x360)',
                ],

                'title_size' => [
                    'type'  => 'select',
                    'title' => 'Title size',
                    'options' => [
                        'extra' => 'Extra',
                        'large' => 'Large',
                        'medium' => 'Medium',
                    ],
                    'std' => 'extra',
                    'after' => 'show_title',
                ],
                
                'show_excerpt' => [
                    'type' => 'switcher',
                    'title' => 'Show content/excerpt',
                    'std' => 'yes',
                ],

                'meta_background' => [
                    'type'  => 'color',
                    'title' => 'Meta Background',
                    'after' => 'text_color',
                ],

                'item_align' => [
                    'type'  => 'select',
                    'title' => 'Item Align',
                    'options' => [
                        '' => 'Default',
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right',
                    ],
                    'std' => '',
                ],

            ],

            'section' => 'layout',
            'section_title' => 'Layout',
        ]);
    
    }
    
    if ( 'slider' == $layout ) {
    
        $params = fox_query_params( [ 'pagination' ] );

        $params[ 'slider_size' ] = [
            'type' => 'text',
            'std' => '1080x540',
            'title' => 'Slider Size (Eg. 1080x540)',

            'section' => 'layout',
            'section_title' => 'Layout',
        ];

        $params[ 'nav_style' ] = [
            'type' => 'select',
            'std' => 'text',
            'title' => 'Nav Arrow Style',
            'options' => [
                'text' => 'Text',
                'arrow' => 'Arrow',
            ],
        ];

        $params[ 'slider_effect' ] = [
            'type' => 'select',
            'std' => 'slide',
            'title' => 'Slider Effect',
            'options' => [
                'slide' => 'Slide',
                'fade' => 'Fade',
            ],
        ];

        $params[ 'item_align' ] = array(
            'title' => 'Text Alignment',
            'type' => 'select',
            'options' => array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'std' => 'left',
        );

        /* @todo
        $params[ 'text_background' ] = array(
            'title' => 'Text background',
            'type' => 'color',
        );

        $params[ 'text_color' ] = array(
            'title' => 'Text Color',
            'type' => 'color',
        );
        */

        /* Title
         * ------------------------ */
        $params[ 'placeholder_heading_title' ] = [
            'type' => 'heading',
            'title' => 'Title',
        ];

        $params[ 'show_title' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show title',
        );

        $params[ 'title_background' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Bckground for title?',
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
            'std'   => 'large',
            'options' => [
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
        $params[ 'placeholder_heading_excerpt' ] = [
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

        $params[ 'excerpt_more' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'More Link',
        );

        /* Other elements
         * ------------------------ */
        $params[ 'placeholder_heading_elements' ] = [
            'type' => 'heading',
            'title' => 'Other elements',
        ];

        $params[ 'show_date' ] = array(
            'type' => 'switcher',
            'std' => 'yes',
            'title' => 'Show post date',
        );

        $params[ 'show_category' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show post categories',
        );

        $params[ 'show_author' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show post author',
        );

        $params[ 'show_view' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show view count',
        );

        $params[ 'show_comment_link' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show comment link',
        );

        $params[ 'show_reading_time' ] = array(
            'type' => 'switcher',
            'std' => '',
            'title' => 'Show reading time',
        );
    
    }
    
    return apply_filters( 'fox_params', $params, $layout );
    
}

/**
 * default blog options to parse
 * @since 4.0
 */
function fox_default_blog_options( $layout ) {
    
    $options = [];
    if ( 'group-1' == $layout ) {
        $params = fox_post_group1_params();
    } elseif ( 'group-2' == $layout ) {
        $params = fox_post_group2_params();
    } else {
        $params = fox_blog_params( $layout );
    }
    
    foreach ( $params as $id => $param ) {
        $options[ $id ] = isset( $param[ 'std' ] ) ? $param[ 'std' ] : '';
    }
    
    return $options;
    
}

add_filter( 'fox_params', 'fox_correct_elementor_params' );
function fox_correct_elementor_params( $params ) {
    
    $sections = [];
    foreach ( $params as $id => $param ) {
    
        // 01 - image to media
        if ( 'image' == $param[ 'type' ] ) {
            $param[ 'type' ] = 'media';
        }
        $params[ $id ] = $param;
        
        // 02 - section duplication
        if ( isset( $params[ $id ][ 'section' ] ) ) {
            if ( in_array( $params[ $id ][ 'section' ], $sections ) ) {
                unset( $params[ $id ][ 'section' ] );
            } else {
                $sections[] = $params[ $id ][ 'section' ];
            }
        }
        
    }
    
    /*
    // additional common params
    $params[ 'misc_heading' ] = array(
        'type' => 'heading',
        'title' => 'Misc',
        
        'section' => 'blog_misc',
        'section_title' => 'Misc',
    );
    
    $params[ 'id' ] = array(
        'type' => 'text',
        'title' => 'Element ID',
        'placeholder' => '',
    );
    
    $params[ 'disable_when_pager' ] = array(
        'type' => 'switcher',
        'title' => 'Disable this block for pages 2, 3..',
        'std' => '',
    );
    */
    
    return $params;
    
}