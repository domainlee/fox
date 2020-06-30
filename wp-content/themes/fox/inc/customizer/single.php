<?php
/* LAYOUT
---------------------------------------------------- */
$options[ 'single_style' ] = [
    'type' => 'image_radio',
    'options' => [
        '1' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Layout 1',
        ],
        '1b' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1b.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Layout 1b',
        ],
        '2' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Layout 2',
        ],
        '3' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Layout 3',
        ],
        '4' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/4.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Hero full',
        ],
        '5' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/5.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Hero half',
        ],
    ],
    'std' => '1',
    'name' => 'Single Post Layout',

    'section'   => 'single_layout',
    'section_title' => 'Single Post Layout',

    'panel' => 'single',
    'panel_title' => 'Single Post',
    'panel_priority' => 130,
    
    'hint' =>  'Single post layout',

];

$options[ 'single_sidebar_state' ] = [
    'type' => 'radio',
    'options' => [
        'sidebar-left' => 'Sidebar Left',
        'sidebar-right' => 'Sidebar Right',
        'no-sidebar' => 'No sidebar (fullwidth)',
    ],
    'std' => 'sidebar-right',
    'name' => 'Sidebar',
    
    'hint' =>  'Single post sidebar',
];

$options[ 'single_padding_top' ] = [
    'shorthand' => 'padding-top',
    'name' => 'Single post padding top',
    'selector' => '.single .wi-content',
    'placeholder' => 'Eg. 20px',
];

// COMPONENTS
$std = [
    'date', 
    'category',
    'post_header',
    'thumbnail',
    'share',
    'tag',
    'related',
    'authorbox',
    'comment',
    'nav',
    'bottom_posts',
    'side_dock',
];

$options[] = [
    'name' => 'Show/Hide Components',
    'type' => 'heading',
];

$options[ 'single_components' ] = [
    'type' => 'multicheckbox',

    'options' => [
        'date' => 'Meta Date',
        'category' => 'Meta Category',
        'author' => 'Meta Author',
        'author_avatar' => 'Author avatar',
        'comment_link' => 'Meta comment link',
        'reading_time' => 'Meta Reading Time',
        'view' => 'Meta view count',

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

    ],
    'std' => $std,
    'name' => 'Single post components',
    
    'hint' =>  'Single post show/hide components',
];

/* Hero Post
---------------------------------------------------- */
$options[ 'single_hero_half_skin' ] = [
    'type'      => 'select',
    'options'   => [
        'light' => 'Light',
        'dark' => 'Dark',
    ],
    'std'       => 'light',
    'name' => 'Hero half default skin',

    'panel' => 'single',
    
    'section'   => 'single_hero',
    'section_title' => 'Hero Post',
    'section_desc' => 'To set "Hero" post as default, please go to <a href="javascript:wp.customize.section( \'wi_single_layout\' ).focus();">Single Post Layout</a> section.',
    
    'hint' =>  'Hero half post skin',
];

$options[ 'single_hero_full_text_layout' ] = [
    'type'      => 'select',
    'options'   => [
        'bottom-left' => 'Bottom Left',
        'bottom-center' => 'Bottom Center',
        'center' => 'Center',
    ],
    'std'       => 'bottom-left',
    'name' => 'Hero full text position',
    
    'hint' =>  'Hero full text position',
];

$options[ 'single_hero_scroll' ] = [
    'shorthand' => 'enable',
    'std'       => 'false',
    'name' => '"Scroll down" button?',
    
    'hint' =>  'Hero post: scroll button',
];

/* Post Header
---------------------------------------------------- */
$options[ 'single_meta_template' ] = [
    'type'      => 'select',
    'options'   => [
        '1' => 'Title > Meta',
        '2' => 'Meta > Title',
        '4' => 'Category > Title > Meta',
    ],
    'std'       => '1',
    'name' => 'Post Header Elements Order',

    'panel' => 'single',
    'section'   => 'single_header',
    'section_title' => 'Post Header',
    
    'hint' =>  'Single post title, meta order',
];

$options[ 'single_meta_align' ] = array(
    'type'      => 'select',
    'name'      => 'Post Header Alignment',
    'options'   => [
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ],
    'std'       => 'center',
);

$options[ 'single_meta_border' ] = array(
    'type'      => 'select',
    'name'      => 'Post Header Border',
    'options'   => [

        'none' => 'None',

        'top-1' => 'Top 1px',
        'top-2' => 'Top 2px',

        'bottom-1' => 'Bottom 1px',
        'bottom-2' => 'Bottom 2px',

        'top-1|bottom-1' => 'Top 1px - Bottom 1px',
        'top-2|bottom-2' => 'Top 2px - Bottom 2px',
        'top-3|bottom-1' => 'Top 3px - Bottom 1px',

    ],
    'std'       => 'none',
    
    'hint' =>  'Single post header border',
);

$options[ 'single_meta_border_color' ] = array(
    'shorthand' => 'border-color',
    'name'      => 'Post Header Border Color',
    'selector'  => '.single-header .container',
);

// TITLE
//
$options[] = [
    'type'      => 'heading',
    'name'      => 'Single Post Title',
];

$id = 'single_post_title';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Single post title font',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

$options[ $id . '_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ] . ' color',
];

// SUBTITLE
//
$options[] = [
    'type'      => 'heading',
    'name'      => 'Single Post Subtitle',
];

$id = 'single_post_subtitle';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Subtitle font',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

$options[ $id . '_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ] . ' color',
];

// HEADER BOX
$options[] = [
    'type'      => 'heading',
    'name'      => 'Post Header Box',
];

$options[ 'post_header_box' ] = [
    'shorthand' => 'box',
    'name'      => 'Single post header box',
];

/* Post Content
---------------------------------------------------- */
$options[ 'single_thumbnail_stretch' ] = [
    'type' => 'radio',
    'options' => [
        'stretch-none' => 'No stretch',
        'stretch-bigger' => 'Stretch Wide',
        'stretch-container' => 'Container Width',
        'stretch-full' => 'Stretch Fullwidth',
    ],
    'std' => 'stretch-none',
    'name' => 'Thumbnail stretch',

    'section'   => 'single_content',
    'section_title' => 'Post Content',
    'panel'     => 'single',
    
    'hint' =>  'Post thumbnail stretch',
];

$options[ 'single_content_width' ] = [
    'type' => 'radio',
    'options' => [
        'full' => 'Full width',
        'narrow' => 'Narrow width',
    ],
    'std' => 'full',
    'name' => 'Content width',
    
    'hint' =>  'Post content narrow width',
];

// since 4.1
$options[ 'content_link_style' ] = array(
    'type'      => 'select',
    'options'   => array(
        '1' => 'Grey underline',
        '2' => 'Same color underline',
        '3' => 'Black underline',
        '4' => 'No style',
    ),
    'std'       => '1',
    'name'      => 'Post Content Link Style',
    
    'hint' =>  'Post content link underline style',
);

$options[ 'single_column_layout' ] = fox_generate_option( 'column_layout', 'customizer', [
    'name' => 'Post Text Column Layout',
    'hint' =>  'Post content text column',
] );

$options[ 'single_dropcap' ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'name' => 'Drop cap?',
    
    'hint' =>  'Enable drop cap',
];

$options[ 'single_content_image_stretch' ] = [
    'name' => 'Stretch All Content Images',
    'type' => 'radio',
    'options' => [
        'stretch-none' => 'No strech',
        'stretch-bigger' => 'Stretch Wide',
        'stretch-full' => 'Stretch Fullwidth',
    ],
    'std' => 'stretch-none',
    'desc' => 'Each photo has its own option for stretching. By using this option. you stretch ALL alignnone, aligncenter images in your post.',
    
    'hint' =>  'Post content image stretch',
];

$id = 'single_content';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Single post content font',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

$options[ $id . '_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ] . ' color',
];

// SINGLE LABELS
//
$options[] = [
    'type' => 'heading',
    'name' => 'Single Label',
    'desc'  => 'Share label, tag label, related post heading etc.',
];

$id = 'single_heading';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Single labels design',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

$options[ 'single_heading_box' ] = [
    'shorthand' => 'box',
    'name'      => 'Single label box',
    'fields'    => [ 'border', 'border-style', 'border-color' ],
];

/* SHARE
---------------------------------------------------- */
$options[ 'share_icons' ] = array(
    'type'      => 'multicheckbox',
    'name'      => esc_html__( 'Icons', 'wi' ),
    'options'   => array(
        'facebook' => 'Facebook',
        'messenger' => 'Messenger',
        'twitter' => 'Twitter',
        'pinterest' => 'Pinterest',
        'linkedin' => 'Linked In',
        'whatsapp' => 'Whatsapp',
        'reddit'    => 'Reddit',
        'email'     => 'Email',
    ),
    'std'       => 'facebook,messenger,twitter,pinterest,whatsapp,email',

    'section'   => 'single_share',
    'section_title' => 'Social Share',
    'panel'     => 'single',
    
    'hint' =>  'Social Share',
);

$options[ 'share_positions' ] = array(
    'type'      => 'multicheckbox',
    'name'      => 'Share Positions',
    'options'   => array(
        'before'    => 'Before content',
        'side'      => 'Left Side of content',
        'after'     => 'After content',
    ),
    'std'       => 'after',
    'desc'      => 'Note that "side share" is not available for content narrow.',
    
    'hint' =>  'Share positions',
);

$options[ 'share_icon_style' ] = array(
    'name'      => 'Icon style',
    'type'      => 'select',
    'options'   => [
        'default'   => 'Default',
        'custom'    => 'Custom design',
    ],
    'toggle' => [
        'custom' => [
            
            'share_layout',
            
            'share_icon_shape',
            'share_icon_size',

            'share_icon_color',
            'share_icon_custom_color',
            'share_icon_background',
            'share_icon_custom_background',

            'share_icon_hover_color',
            'share_icon_hover_custom_color',
            'share_icon_hover_background',
            'share_icon_hover_custom_background',
            
        ],
    ],
    'std'       => 'default',
    
    'hint' =>  'Share icon styles',
);

// since 4.3
$options[ 'share_layout' ] = array(
    'type'      => 'radio',
    'name'      => 'Share Layout',
    'options'   => array(
        'inline'     => 'Left Label - Right Icons',
        'stack'    => 'Label above - Icons below',
    ),
    'std'       => 'inline',
);

$options[ 'share_icon_shape' ] = array(
    'name'      => 'Icon shape',
    'type'      => 'select',
    'options'   => fox_shape_support(),
    'std'       => 'circle'
);

$options[ 'share_icon_size' ] = array(
    'name'      => 'Icon Size (px)',
    'shorthand' => 'width',
    'std'       => '32px',
    'selector'  => '.share-style-custom a',
);

$options[ 'share_icon_color' ] = array(
    'name'      => 'Icon Color',
    'type'      => 'select',
    'options'   => [
        'custom' => 'Custom color',
        'brand' => 'Brand color',
    ],
    'toggle'    => [
        'custom' => [ 'share_icon_custom_color' ]
    ],
    'std'       => 'custom',
);

$options[ 'share_icon_custom_color' ] = array(
    'name'      => 'Icon Custom Color',
    'shorthand' => 'color',
    'selector'  => '.fox-share.color-custom a',
);

$options[ 'share_icon_background' ] = array(
    'name'      => 'Icon Background',
    'type'      => 'select',
    'options'   => [
        'custom' => 'Custom color',
        'brand' => 'Brand color',
    ],
    'toggle'    => [
        'custom' => [ 'share_icon_custom_background' ]
    ],
    'std'       => 'custom',
);

$options[ 'share_icon_custom_background' ] = array(
    'name'      => 'Icon Custom Background',
    'shorthand' => 'background-color',
    'selector'  => '.fox-share.background-custom a',
);

$options[ 'share_icon_hover_color' ] = array(
    'name'      => 'Icon Hover Color',
    'type'      => 'select',
    'options'   => [
        'custom' => 'Custom color',
        'brand' => 'Brand color',
    ],
    'toggle'    => [
        'custom' => [ 'share_icon_hover_custom_color' ]
    ],
    'std'       => 'custom',
);

$options[ 'share_icon_hover_custom_color' ] = array(
    'name'      => 'Icon Custom Hover Color',
    'shorthand' => 'color',
    'selector'  => '.fox-share.hover-color-custom a:hover',
);

$options[ 'share_icon_hover_background' ] = array(
    'name'      => 'Icon Hover Background',
    'type'      => 'select',
    'options'   => [
        'custom' => 'Custom color',
        'brand' => 'Brand color',
    ],
    'toggle'    => [
        'custom' => [ 'share_icon_hover_custom_background' ]
    ],
    'std'       => 'custom',
);

$options[ 'share_icon_hover_custom_background' ] = array(
    'name'      => 'Icon Custom Hover Background',
    'shorthand' => 'background-color',
    'selector'  => '.fox-share.hover-background-custom a:hover',
);

/* TAG
---------------------------------------------------- */
$options[ 'tag_style' ] = array(
    'type'      => 'select',
    'options'   => array(
        'block' => 'Block style 1',
        'block-2' => 'Block style 2',
        'block-3' => 'Block style 3',
        'plain' => 'Minimal',
    ),
    'std'       => 'block',
    'name'      => 'Tag style',

    'desc' => 'To enable/disable authorbox, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_tags',
    'section_title' => 'Post Tags',
    'panel'     => 'single',
    
    'hint' =>  'Post tag styles',
);

/* RELATED POSTS
---------------------------------------------------- */
$options[ 'single_related_number' ] = array(
    'type'      => 'text',
    'std'       => '3',
    'placeholder' => '3',
    'name'      => 'Number of related posts',

    'desc' => 'To enable/disable related posts, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_related',
    'section_title' => 'Related Posts',
    'panel'     => 'single',
    
    'hint' =>  'Related posts',
);

$options[ 'single_related_source' ] = array(
    'type'      => 'select',
    'std'       => 'tag',
    'options'   => [
        'date' => 'Latest posts',
        'category' => 'Posts in same category',
        'tag' => 'Posts with same tags',
        'author' => 'Posts by same author',
        'featured' => 'Featured posts',
    ],
    'name'      => 'Related posts source',
);

$options[ 'single_related_orderby' ] = array(
    'type'      => 'select',
    'std'       => 'date',
    'options'   => fox_orderby_support(),
    'name'      => 'Order by?',
);

$options[ 'single_related_order' ] = array(
    'type'      => 'select',
    'std'       => 'desc',
    'options'   => fox_order_support(),
    'name'      => 'Order?',
);

$options[ 'single_related_layout' ] = array(
    'type'      => 'select',
    'std'       => 'grid-3',
    'options'   => [
        'grid-2' => 'Grid 2 columns',
        'grid-3' => 'Grid 3 columns',
        'grid-4' => 'Grid 4 columns',
        'list'  => 'List',
    ],
    'name'      => 'Layout',
);

$options[ 'single_related_position' ] = array(
    'type'      => 'radio',
    'options'   => [
        'after_main_content' => 'After post content',
        'after_container' => 'After content + sidebar',
    ],
    'std'       => 'after_main_content',
    'name'      => 'Position',
);

/* AUTHOR BOX
---------------------------------------------------- */
$options[ 'authorbox_style' ] = array(
    'type'      => 'select',
    'options'   => array(
        'simple'    => 'Simple',
        'box'       => 'Box',
    ),
    'std'       => 'simple',
    'name'      => 'Author box style',

    'desc' => 'To enable/disable authorbox, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_authorbox',
    'section_title' => 'Author Box',
    'panel'     => 'single',
    
    'hint' =>  'Author box',
);

$options[ 'single_authorbox_avatar_shape' ] = array(
    'type'      => 'select',
    'options'   => [
        'acute' => 'Acute',
        'round' => 'Round',
        'circle' => 'Circle',
    ],
    'std'       => 'circle',
    'name'      => 'Author avatar shape',
    
    'hint' =>  'Author box avatar shape',
);

/* SINGLE NAVIGATION
---------------------------------------------------- */
$options[ 'single_post_navigation_style' ] = array(
    'type'      => 'select',
    'options'   => [
        'simple'    => 'Simple',
        'advanced'  => 'Advanced',
    ],
    'std'       => 'advanced',
    'name'      => 'Post Navigation Style',

    'desc' => 'To enable/disable post navigation, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_navigation',
    'section_title' => 'Post Navigation',
    'panel'     => 'single',
    
    'hint' =>  'Single post navigation',
);

$options[ 'single_post_navigation_same_term' ] = array(
    'shorthand' => 'enable',
    'options'   => [
        'true'    => 'Yes',
        'false'  => 'No',
    ],
    'std'       => 'false',
    'name'      => 'Next/Prev post in same categroy',
);

/* BOTTOM POSTS
---------------------------------------------------- */

$options[ 'single_bottom_posts_number' ] = array(
    'type'      => 'text',
    'std'       => '5',
    'placeholder' => '5',
    'name'      => 'Number of bottom posts',

    'desc' => 'To enable/disable bottom posts, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_bottom_posts',
    'section_title' => 'Bottom Posts',
    'panel'     => 'single',
    
    'hint' =>  'Single 5 bottom posts',
);

$options[ 'single_bottom_posts_source' ] = array(
    'type'      => 'select',
    'std'       => 'category',
    'options'   => [
        'date' => 'Latest posts',
        'category' => 'Posts in same category',
        'tag' => 'Posts with same tags',
        'author' => 'Posts by same author',
        'featured' => 'Featured posts',
    ],
    'name'      => 'Bottom posts source',
);

$options[ 'single_bottom_posts_orderby' ] = array(
    'type'      => 'select',
    'std'       => 'date',
    'options'   => fox_orderby_support(),
    'name'      => 'Order by?',
);

$options[ 'single_bottom_posts_order' ] = array(
    'type'      => 'select',
    'std'       => 'desc',
    'options'   => fox_order_support(),
    'name'      => 'Order?',
);

$options[ 'single_bottom_posts_excerpt' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Excerpt?',
);

/* FOOTER SLIDING BOX
---------------------------------------------------- */
$options[ 'single_side_dock_number' ] = array(
    'type'      => 'text',
    'std'       => '2',
    'placeholder' => '2',
    'name'      => 'Number of posts in Sliding box',

    'desc' => 'To enable/disable sliding box, please go to <a href="javascript:wp.customize.control( \'wi_single_components\' ).focus();">Single Post Layout > Show/Hide Components
</a> then check/uncheck this component.',

    'section'   => 'single_side_dock',
    'section_title' => 'Footer Sliding Box',
    'panel'     => 'single',
    
    'hint' =>  'Footer sliding box post suggestion',
);

$options[ 'single_side_dock_source' ] = array(
    'type'      => 'select',
    'std'       => 'tag',
    'name'      => 'Post Source',
    'options'   => [
        'date' => 'Latest posts',
        'category' => 'Posts in same category',
        'tag' => 'Posts with same tags',
        'author' => 'Posts by same author',
        'featured' => 'Featured posts',
    ],
    'name'      => 'Sliding box posts from:',
);

$options[ 'single_side_dock_orderby' ] = array(
    'type'      => 'select',
    'std'       => 'date',
    'options'   => fox_orderby_support(),
    'name'      => 'Order by?',
);

$options[ 'single_side_dock_order' ] = array(
    'type'      => 'select',
    'std'       => 'desc',
    'options'   => fox_order_support(),
    'name'      => 'Order?',
);

$options[ 'single_side_dock_orientation' ] = array(
    'type'      => 'select',
    'std'       => 'up',
    'options'   => [
        'up' => 'Bottom up',
        'right' => 'Left to right',
    ],
    'name'      => 'Sliding Orientation',
);

/* FORMAT OPTIONS
---------------------------------------------------- */
$options[ 'video_indicator_style' ] = array(
    'name'      => 'Video indicator style',
    'type'      => 'select',
    'options'   => [
        'minimal'   => 'Minimal',
        'solid'     => 'Solid',
        'outline'   => 'Outline',
    ],
    'std'       => 'outline',

    'section' => 'single_format',
    'section_title' => 'Post Format Options',
    'panel' => 'single',
    
    'hint' =>  'Video icon style',
);

$options[ 'single_format_gallery_style' ] = [
    'type' => 'image_radio',
    'options' => [
        'metro' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/metro.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Metro',
        ],
        'stack' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/stack.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Stack Images',
        ],
        'slider' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/slider.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Slider',
        ],
        'slider-rich' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/slider-rich.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Rich Content Slider',
        ],
        'carousel' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/carousel.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Carousel',
        ],
        'grid' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/grid.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Grid',
        ],
        'masonry' => [
            'src' => get_template_directory_uri() . '/inc/customizer/assets/img/masonry.png',
            'width' => 80,
            'height' => 80,
            'title' => 'Masonry',
        ],
    ],
    'std' => 'metro',
    'name' => 'Gallery Default Style',
    
    'hint' =>  'Default post gallery type',
];

$options[ 'single_format_gallery_lightbox' ] = fox_generate_option( 'format_gallery_lightbox', 'customizer', [
    'hint' =>  'Post gallery lightbox',
] );

$options[ 'single_format_gallery_slider_effect' ] = fox_generate_option( 'format_gallery_slider_effect', 'customizer' );

$options[ 'single_format_gallery_slider_size' ] = fox_generate_option( 'format_gallery_slider_size', 'customizer' );

$options[ 'single_format_gallery_grid_column' ] = fox_generate_option( 'format_gallery_grid_column', 'customizer' );

$options[ 'single_format_gallery_grid_size' ] = fox_generate_option( 'format_gallery_grid_size', 'customizer' );

$options[ 'single_format_gallery_grid_size_custom' ] = fox_generate_option( 'format_gallery_grid_size_custom', 'customizer' );

/* Reading Progress Indicator
 * since 4.1
---------------------------------------------------- */
$options[] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Reading Progress', 'wi' ),

    'section'   => 'single_reading_progress',
    'section_title' => 'Reading Progress',
    'panel'     => 'single',
);

$options[ 'single_reading_progress' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'name'      => 'Reading progress indicator?',
    'desc'      => 'Reading progress indicator won\'t be shown for articles that are too short (ie. shorter than screen height)',
    
    'hint' =>  'Reading progress',
);

$options[ 'reading_progress_position' ] = array(
    'type'      => 'select',
    'options'   => [
        'top' => 'Top',
        'bottom' => 'Bottom',
    ],
    'std'       => 'top',
    'name'      => 'Progress Bar Position',
);

$options[ 'reading_progress_height' ] = array(
    'shorthand' => 'height',
    'selector'  => '.reading-progress-wrapper',
    'std'       => '5px',
    'placeholder' => '5px',
    'name'      => 'Progress Bar Height',
);

$options[ 'reading_progress_color' ] = array(
    'type'      => 'color',
    'name'      => 'Progress Bar Color',
);

/* AUTOLOAD NEXT POST
 * since 2.9
---------------------------------------------------- */
$options[] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Autoload next post', 'wi' ),

    'section'   => 'single_autoload',
    'section_title' => 'Autoload Next post',
    'panel'     => 'single',
);

$options[ 'autoload_post' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'name'      => 'Auto load next post',
    'desc'      => 'If enabled, a new post will be loaded automatically when visitor reaches to the end of your single post.',
    
    'hint' =>  'Autoload Next Post',
);

$options[ 'autoload_post_nav_same_term' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'options'   => [
        'true' => 'Yes',
        'false' => 'No',
    ],
    'name'      => 'Only load post in same category',
);