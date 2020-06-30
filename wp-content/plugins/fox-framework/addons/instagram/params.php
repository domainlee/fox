<?php
$params = [];

$instagram_params = fox_instagram_params();

foreach ( $instagram_params as $k => $param ) {
    
    extract( wp_parse_args( $param, [
        'type' => '',
        'std' => null,
        'name' => '',
    ] ) );
    
    if ( 'checkbox' == $type ) {
        $param[ 'type' ] = 'switcher';
        $param[ 'std' ] = $std ? 'yes' : '';
    }
    $param[ 'title' ] = $name;
    
    $params[ $param[ 'id' ] ] = $param;
    
}