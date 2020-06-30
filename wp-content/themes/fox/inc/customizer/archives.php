<?php
/* Layout
----------------------------------- */
$pages = [
    'category' => 'Category',
    'tag' => 'Tag',
    'archive' => 'Date / Month / Year',
    'author' => 'Author',
    'search' => 'Search',
];

foreach ( $pages as $page => $name ) {

    $options[] = [
        'type' => 'heading',
        'name' => $name,

        'section' => 'blog_layout',
        'section_title' => 'Archive Layouts',
        'panel' => 'blog',
        'panel_title' => 'Archives',
        'panel_priority' => 125,
    ];

    $options[ $page . '_layout' ] = array(
        'type'      => 'select',
        'options'   => $layout_options,
        'std'       => 'list',
        'name'      => $name . ' Layout',
        
        'hint' =>  $name . ' layout',
    );

    $options[ $page . '_sidebar_state' ] = array(
        'type'      => 'select',
        'options'   => [
            'sidebar-left'  => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
            'no-sidebar'    => 'No Sidebar',
        ],
        'std'       => 'sidebar-right',
        'name'      => $name . ' Sidebar',
        
        'hint' =>  $name . ' sidebar',
    );

}

/* Category / Tag top area
----------------------------------- */
$archive_types = [
    'category' => 'Category',
    'tag' => 'Tag',
    'author' => 'Author',
];

$options[ 'top_area_non_duplicate' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'name' => 'Non-duplicate posts?',
    'desc' => 'Ie. if a post is shown in top area, it won\'t be displayed again in the main stream',
    
    'section' => 'blog_toparea',
    'section_title' => 'Archive Top Area',
    'panel' => 'blog',
);

foreach ( $archive_types as $slug => $name ) {

    $prefix = $slug . '_top_area_';

    $options[] = array(
        'type'      => 'heading',
        'name'      => $name . ' Top Area',

        'section' => 'blog_toparea',
        'section_title' => 'Archive Top Area',
        'panel' => 'blog',
        
        'hint' =>  $name . ' top area',
    );

    $options[ $prefix . 'display' ] = array(
        'type'      => 'select',
        'options'   => [
            '' => 'None',
            'view' => 'Most Viewed Posts',
            'comment_count' => 'Most Commented Posts',
            'featured' => 'Featured Posts (Starred Posts)',
        ],
        'std'       => '',
        'name'      => 'Top area of ' . $name . ' shows:',
    );

    $options[ $prefix . 'layout' ] = array(
        'type'      => 'select',
        'options'   => fox_builder_layout_support(),
        'std'       => 'group-1',
        'name'      => 'Top area layout',
    );

    $options[ $prefix . 'number' ] = array(
        'type'      => 'text',
        'std'       => '4',
        'placeholder' => '4',
        'name'      => 'Number of posts to show',
    );

}

/* Homepage
---------------------------------------------------- */
$options[] = [
    'type' => 'html',
    'std' => '<p class="fox-info">You can find settings for Homepage under section <a href="javascript:wp.customize.section( \'wi_main_stream\' ).focus();">Homepage Builder > Main Stream</a></p>',

    'section' => 'blog_home',
    'section_title' => 'Homepage',
    'panel' => 'blog',
];

/* Page 404
---------------------------------------------------- */
$options[ '404_title' ] = array(
    'type'      => 'text',
    'name'      => 'Page 404 title',
    'placeholder' => 'Page Not Found',

    'section'   => 'page_404',
    'section_title' => 'Page 404',
    'panel'     => 'blog',
    
    'hint' =>  'Page 404',
);

$options[ 'page_404_message' ] = array(
    'type'      => 'textarea',
    'name'      => 'Page 404 message',
);

$options[ 'page_404_searchform' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Page 404 search form',
);

/* Archive Page Design
---------------------------------------------------- */
$options[ 'archive_label' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'name'      => 'Archive Label: category, tag..',

    'section' => 'blog_archive',
    'section_title' => 'Archive Page Design',
    'panel' => 'blog',
    
    'hint' =>  'Archive label',
);

$options[ 'archive_description' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Archive description',
);

$options[ 'titlebar_user_social_style' ] = array(
    'type'      => 'select',
    'std'       => 'plain',
    'options'   => fox_social_style_support(),
    'name'      => 'User profile social icon style',
);    

$options[ 'titlebar_subcategories' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Shows subcategories for category archive',
);

$options[ 'titlebar_background' ] = [
    'shorthand' => 'background-color',
    'selector'  => '.wi-titlebar',
    'name'      => 'Titlebar background',
    
    'hint' =>  'Archive title bar background',
];

$options[ 'titlebar_overlay_opacity' ] = [
    'shorthand' => 'opacity',
    'name'      => 'Titlebar Overlay Opacity',
    'selector'  => '.titlebar-bg-overlay',
];

$options[ 'titlebar_align' ] = [
    'type'      => 'select',
    'options'   => fox_align_options(),
    'std'       => 'center',
    'name'      => 'Titlebar Align',
    
    'hint' =>  'Archive title bar align',
];

$id = 'archive_title';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Archive title font',
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
    'selector'  => $fontdata[ 'selector' ] . ', .archive-description',
    'name'      => $fontdata[ 'name' ] . ' color',
    
    'hint' =>  'Archive title color',
];

$options[ 'titlebar_box' ] = [
    'shorthand' => 'box',
    'name'      => 'Titlebar Box',
    'desc'      => 'Container-width box',
    
    'hint' =>  'Archive title bar margin/padding',
];