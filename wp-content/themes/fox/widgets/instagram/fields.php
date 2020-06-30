<?php
$fields = array();
    
$fields[] = array(
    'id' => 'title',
    'type' => 'text',
    'name' => esc_html__( 'Title', 'wi' ),
    'std' => '',
);

$fields = array_merge( $fields, fox_instagram_params() );

if ( fox_is_demo() ) {
    
    $fields[] = [
        'name' => 'Upload your images',
        'type' => 'images',
        'id' => 'images',
    ];
    
}