<?php
/* POST DESIGN
----------------------------------- */
// POST TITLE
//
$options[] = [
    'type' => 'heading',
    'name' => 'Blog Post Title',

    'section'   => 'post_design',
    'section_title' => 'POST DESIGN',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post item design',
];

$id = 'post_title';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Post title font',
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
    'selector'  => $fontdata[ 'selector' ] . ' a',
    'name'      => $fontdata[ 'name' ] . ' color',
    
    'hint' =>  'Post title color',
];

$options[ $id . '_hover_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ] . ' a:hover',
    'name'      => $fontdata[ 'name' ] . ' hover color',
];

$options[ $id . '_hover_text_decoraction' ] = [
    'shorthand' => 'text-decoration',
    'selector'  => $fontdata[ 'selector' ] . ' a:hover',
    'name'      => $fontdata[ 'name' ] . ' hover',
    'std'       => 'none',
    
    'hint' =>  'Post title underline',
];

$options[ $id . '_hover_text_decoraction_color' ] = [
    'shorthand' => 'text-decoration-color',
    'selector'  => $fontdata[ 'selector' ] . ' a:hover',
    'name'      => $fontdata[ 'name' ] . ' hover decoration color',
];

// POST META
//
$options[] = [
    'type' => 'heading',
    'name' => 'Post Meta',
];

$id = 'post_meta';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Post meta font',
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
    
    'hint' =>  'Post meta color',
];

$options[ $id . '_link_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ] . ' a',
    'name'      => $fontdata[ 'name' ] . ' link color',
];

$options[ $id . '_link_hover_color' ] = [
    'shorthand' => 'color',
    'selector'  => $fontdata[ 'selector' ] . ' a:hover',
    'name'      => $fontdata[ 'name' ] . ' link hover color',
];

// STANDALONE CATEGORY
//
$options[] = [
    'type' => 'heading',
    'name' => 'Standalone Category',
];

$id = 'standalone_category';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' =>  'Post category standalone options',
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
    'selector'  => $fontdata[ 'selector' ] . ' a',
    'name'      => $fontdata[ 'name' ] . ' color',
];