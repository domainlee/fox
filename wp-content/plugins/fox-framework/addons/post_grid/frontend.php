<?php
if ( ! function_exists( 'fox_blog_grid' ) ) return;

$settings = $this->get_settings_for_display();        
$settings[ 'layout' ] = 'grid';

$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_grid( $query, $settings );
wp_reset_query();