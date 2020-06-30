<?php
extract( $args );
echo $before_widget;

if ( function_exists( 'fox_btn' ) ) fox_btn( $instance );
    
echo $after_widget;