<?php
extract( $args );
if ( function_exists( 'fox_footer_copyright' ) ) {
    echo $before_widget;
    fox_footer_copyright();
    echo $after_widget;
}