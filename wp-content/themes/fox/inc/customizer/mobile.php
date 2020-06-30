<?php
// MOBILE HEADER
$options[] = array(
    'type' => 'heading',
    'name' => 'Mobile Header',

    'section'     => 'mobile',
    'section_title' => 'Mobile',
    'section_priority'=> 160,
    
    'hint' =>  'Mobile header',
);

$options[ 'mobile_header_background' ] = array(
    'shorthand' => 'background-color',
    'name'      => 'Mobile header background',
    'selector'  => '.masthead-mobile-bg',
);

$options[ 'mobile_header_color' ] = array(
    'shorthand' => 'color',
    'name'      => 'Mobile header text color',
    'selector'  => '#masthead-mobile',
);

$options[ 'mobile_logo' ] = array(
    'type'      => 'image',
    'name'      => 'Mobile header logo',
    'desc'      => 'Skip this if you use text logo',
    
    'hint' =>  'Mobile logo',
);

$options[ 'mobile_logo_height' ] = array(
    'shorthand' => 'height',
    'name'      => 'Mobile logo height',
    'placeholder' => '36px',
    'selector'  => '#mobile-logo img',
);

// OFFCANVAS
$options[] = array(
    'type' => 'heading',
    'name' => 'Off-Canvas',
    
    'hint' =>  'Off canvas menu',
);

$options[] = array(
    'type' => 'html',
    'std'  => '<p class="fox-info">You can build your off-canvas menu that\'s different from desktop header menu in <strong>Dashboard > Apperance > Menus</strong>.</p>',
);

$options[ 'offcanvas_skin' ] = array(
    'type'      => 'select',
    'options'   => [
        'light' => 'Light',
        'dark'  => 'Dark',
    ],
    'std'       => 'light',
    'name'      => 'Off-Canvas skin',
    
    'hint' =>  'Off canvas menu skin',
);

$options[ 'offcanvas_color' ] = array(
    'shorthand' => 'color',
    'selector'  => '#offcanvas',
    'name'      => 'Off-Canvas text color',
);

$options[ 'offcanvas_background' ] = array(
    'shorthand' => 'background',
    'name'      => 'Off-Canvas background',
);

// ELEMENTS
//
$options[] = array(
    'name'      => 'Off-canvas Elements',
    'type' => 'heading',
);

$options[ 'offcanvas_search' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Offcanvas search form?',
    
    'hint' =>  'Off canvas search form',
);

$id = 'offcanvas_nav';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Off canvas menu font',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

$options[ $id . '_border' ] = [
    'shorthand' => 'enable',
    'name'      => 'Offcanvas menu border?',
    'std'       => 'false',
    
    'hint' =>  'Off canvas menu item border',
];

$options[ 'offcanvas_social' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Off-Canvas social icons',
    
    'hint' =>  'Off canvas social icons',
);

$options[ 'offcanvas_social_style' ] = array(
    'type'      => 'select',
    'std'       => 'plain',
    'options'   => fox_social_style_support(),
    'name'      => 'Social icon style',
);

$options[ 'offcanvas_social_shape' ] = array(
    'type'      => 'select',
    'std'       => 'circle',
    'options'   => fox_shape_support(),
    'name'      => 'Social icon shape',
);

$options[ 'offcanvas_social_size' ] = array(
    'type'      => 'select',
    'std'       => 'bigger',
    'options' => fox_social_size_support(),
    'name'      => 'Social icon size',
);