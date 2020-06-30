<?php
if ( ! function_exists( 'fox_blog_slider' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'slider';
    
$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_slider( $query, $settings );
wp_reset_query();