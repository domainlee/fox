<?php
extract( $args );
if ( function_exists( 'fox_header_logo' ) ) {
    echo $before_widget;
    fox_header_logo();
    echo $after_widget;
}