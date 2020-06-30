<?php
if ( ! function_exists( 'fox_blog_list' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'list';

$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_list( $query, $settings );
wp_reset_query();