<?php
$fields = array(
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'Banner',
    ),
    
    array(
        'id' => 'image',
        'name' => esc_html__( 'Image', 'wi' ),
        'type' => 'image',
    ),
    
    array(
        'id' => 'tablet',
        'name' => 'Tablet Image',
        'type' => 'image',
    ),
    
    array(
        'id' => 'phone',
        'name' => 'Phone Image',
        'type' => 'image',
    ),
    
    array(
        'id' => 'url',
        'name' => esc_html__( 'Link', 'wi' ),
        'type' => 'text',
    ),
    
    array(
        'id' => 'target',
        'name' => 'Open link in',
        'type' => 'select',
        'options' => [
            '_self' => 'Same tab',
            '_blank' => 'New tab',
        ],
        'std' => '_blank',
    ),
    
    array(
        'id' => 'code',
        'name' => esc_html__( 'Custom Ad Code', 'wi' ),
        'type' => 'textarea',
    ),
    
);