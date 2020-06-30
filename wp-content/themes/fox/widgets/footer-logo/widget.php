<?php
extract( $args );
if ( function_exists( 'fox_footer_logo' ) ) {
    echo $before_widget;
    fox_footer_logo();
    echo $after_widget;
}