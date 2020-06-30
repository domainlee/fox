<?php
$fields = array(
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'About Me',
    ),
    
    array(
        'id' => 'align',
        'name' => 'Align',
        'type' => 'select',
        'options' => [
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ],
    ),
    
    array(
        'id' => 'image',
        'name' => esc_html__( 'Image', 'wi' ),
        'type' => 'image',
    ),
    
    array(
        'id' => 'image_size',
        'name' => 'Image Size',
        'type' => 'select',
        'options' => [
            'thumbnail'  => 'Thumbnail 150x150',
            'medium' => 'Medium',
            'landscape'  => 'Landscape 480x384',
            'square'  => 'Square 480x480',
            'portrait'  => 'Portrait 480x600',
            'thumbnail-large'  => 'Wide 720x480',
            'large'  => 'Large (original ratio)',
        ],
        'std' => 'medium',
    ),
    
    array(
        'id' => 'image_width',
        'name' => 'Image width',
        'desc' => 'Default is 100% image width',
        'type' => 'text',
        'placeholder' => 'Eg. 240px',
    ),
    
    array(
        'id' => 'image_shape',
        'name' => 'Image shape',
        'type' => 'select',
        'options' => [
            'acute' => 'Acute',
            'round' => 'Round',
            'circle' => 'Circle',
        ],
        'std' => 'acute',
    ),
    
    array(
        'id' => 'desc',
        'name' => 'Description (Use &lt;br /&gt; to insert new line)',
        'type' => 'textarea',
    ),
    
    array(
        'id' => 'signature',
        'name' => 'Signature',
        'type' => 'image',
    ),
    
    array(
        'id' => 'signature_width',
        'name' => 'Signature image width',
        'type' => 'text',
        'placeholder' => 'Eg. 180px',
    ),
    
);