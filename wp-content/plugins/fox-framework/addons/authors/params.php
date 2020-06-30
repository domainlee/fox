<?php
$params[ 'number' ] = array(
    'type' => 'text',
    'title' => 'Number of authors to show:',
    'std' => '4',
    
    'section' => 'settings',
    'section_title' => 'Settings',
);

$params[ 'orderby' ] = array(
    'type' => 'select',
    'options' => array(
        'display_name' => 'Name',
        'post_count' => 'Post count',
        'registered' => 'Registered Date',
    ),
    'std' => 'post_count',
    'title' => 'Order by',
);

$params[ 'order' ] = array(
    'type' => 'select',
    'options' => array(
        'asc' => 'Ascending',
        'desc' => 'Descending',
    ),
    'std' => 'desc',
    'title' => 'Order',
);

$params[ 'include' ] = array(
    'type' => 'text',
    'title' => 'Only show users with IDs:',
    'desc' => 'Separate ids by comma',
);

$params[ 'layout' ] = array(
    'type' => 'select',
    'title' => 'Layout',
    'options' => array(
        'grid' => 'Grid',
        'list' => 'List',
    ),
    'std' => 'grid',
);

$params[ 'column' ] = array(
    'type' => 'select',
    'title' => 'Column?',
    'options' => array(
        '1' => '1 column',
        '2' => '2 columns',
        '3' => '3 columns',
        '4' => '4 columns',
    ),
    'std' => '3',
    'desc' => 'If you use list layout, skip this option.'
);

$params[ 'align' ] = array(
    'type' => 'select',
    'title' => 'Align',
    'options' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ),
    'std' => 'left',
    'desc' => 'If you use list layout, skip this option.'
);

$params[ 'text_color' ] = array(
    'type' => 'color',
    'title' => 'Text Color',
);

$params[ 'border' ] = array(
    'type' => 'switcher',
    'title' => 'Border',
    'std' => 'yes',
);

$params[ 'border_color' ] = array(
    'type' => 'color',
    'title' => 'Border Color',
);

// COMPONENTS
$params[ 'show_author_avatar' ] = array(
    'type' => 'switcher',
    'std' => 'yes',
    'title' => 'Display author avatar',
    
    'section' => 'components',
    'section_title' => 'Components',
);

$params[ 'author_avatar_shape' ] = array(
    'type' => 'select',
    'std' => 'circle',
    'options' => [
        'acute' => 'Acute',
        'round' => 'Round',
        'circle' => 'Circle',
    ],
    'title' => 'Avatar shape',
);

$params[ 'show_author_name' ] = array(
    'type' => 'switcher',
    'std' => 'yes',
    'title' => 'Display author name',
);

$params[ 'show_author_description' ] = array(
    'type' => 'switcher',
    'std' => 'yes',
    'title' => 'Display author description',
);

$params[ 'show_author_social' ] = array(
    'type' => 'switcher',
    'std' => 'yes',
    'title' => 'Display author social links',
);

$params[ 'author_social_style' ] = array(
    'type' => 'select',
    'options' => fox_social_style_support(),
    'std' => 'plain',
    'title' => 'Social icon style',
);

/*
@todo
$params[ 'show_post_count' ] = array(
    'type' => 'switcher',
    'std' => false,
    'title' => 'Display post count',
);

$params[ 'recent_posts' ] = array(
    'type' => 'select',
    'std'   => '0',
    'options' => [
        '0' => 'No posts',
        '1' => '1 post',
        '2' => '2 posts',
        '3' => '3 posts',
        '4' => '4 posts',
        '5' => '5 posts',
    ],
    'title' => 'Recent posts',
);
*/