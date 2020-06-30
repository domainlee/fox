<?php
if ( ! function_exists( 'fox_blog_newspaper' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'newspaper';

$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_newspaper( $query, $settings );
wp_reset_query();