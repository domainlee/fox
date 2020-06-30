<?php 
extract( $args );
if ( function_exists( 'fox_footer_nav' ) ) {
    echo $before_widget;
    fox_footer_nav();
    echo $after_widget;
}