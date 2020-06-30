<?php
$params = fox_ad_params();
foreach ( $params as $id => $param ) {
    if ( 'image' == $param[ 'type' ] ) {
        $param[ 'type' ] = 'media';
    }
    $params[ $id ] = $param;
}