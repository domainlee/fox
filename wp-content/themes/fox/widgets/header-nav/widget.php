<?php 
extract( $args );
if ( function_exists( 'fox_header_nav' ) ) {
    echo $before_widget;
    fox_header_nav();
    echo $after_widget;
}