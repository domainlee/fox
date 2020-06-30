<?php
if ( ! function_exists( 'fox_blog_big' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'big';
    
$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_big( $query, $settings );
wp_reset_query();