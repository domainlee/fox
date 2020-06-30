<?php
$fields = array(
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'Authors',
    ),
    
    array(
        'id' => 'number',
        'name' => esc_html__( 'Number', 'wi' ),
        'type' => 'text',
        'std' => '3',
    ),
    
    array(
        'id' => 'orderby',
        'name' => esc_html__( 'Order by', 'wi' ),
        'type' => 'select',
        'options' => array(
            'name' => esc_html__( 'Name', 'wi' ),
            'post_count' => esc_html__( 'Post Count', 'wi' ),
            'registered' => esc_html__( 'Time of registration', 'wi' ),
        ),
        'std' => 'name',
    ),
    
    array(
        'id' => 'order',
        'name' => esc_html__( 'Order', 'wi' ),
        'type' => 'select',
        'options' => array(
            'DESC' => esc_html__( 'Descending', 'wi' ),
            'ASC' => esc_html__( 'Ascending', 'wi' ),
        ),
        'std' => 'ASC',
    ),
    
    array(
        'id' => 'style',
        'name' => esc_html__( 'Style', 'wi' ),
        'type' => 'select',
        'options' => array(
            'list' => esc_html__( 'List', 'wi' ),
            'grid' => esc_html__( 'Grid', 'wi' ),
        ),
        'std' => 'list',
    ),
    
    array(
        'id' => 'column',
        'name' => 'Grid Column',
        'type' => 'select',
        'options' => array(
            '2' => '2 columns',
            '3' => '3 columns',
            '4' => '4 columns',
        ),
        'std' => '4',
    ),
    
    array(
        'id' => 'meta',
        'name' => esc_html__( 'Display after title:', 'wi' ),
        'desc' => esc_html__( 'This option only applies for list style', 'wi' ),
        'type' => 'select',
        'options' => array(
            'post' => esc_html__( 'The last post', 'wi' ),
            'desc' => esc_html__( 'Author description', 'wi' ),
        ),
        'std' => 'post',
    ),
    
    array(
        'id' => 'avatar_shape',
        'name' => 'Avatar shape',
        'type' => 'select',
        'options' => array(
            'acute' => 'Acute',
            'round' => 'Round',
            'circle' => 'Circle',
        ),
        'std' => 'acute',
    ),
    
    array(
        'id' => 'avatar_color',
        'name' => 'Avatar color effect',
        'type' => 'select',
        'options' => array(
            'grayscale_color' => 'Grayscale >> color hover',
            'grayscale' => 'Grayscale',
            'color' => 'Natural Color',
            'color_grayscale' => 'Color >> grayscale hover'
        ),
        'std' => 'grayscale_color',
    ),
    
    array(
        'id' => 'list_sep',
        'name' => 'Seperator between list items',
        'desc' => 'This option only applies for list style',
        'type' => 'checkbox',
        'value' => 'true',
        'std' => 'true',
    ),
    
);