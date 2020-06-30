<?php
extract( $args );
if ( function_exists( 'fox_header_search' ) ) {
    echo $before_widget;
    fox_header_search();
    echo $after_widget;
}