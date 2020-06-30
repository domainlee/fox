<?php
if ( ! function_exists( 'fox_blog_masonry' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'masonry';
        
$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_masonry( $query, $settings );
wp_reset_query();