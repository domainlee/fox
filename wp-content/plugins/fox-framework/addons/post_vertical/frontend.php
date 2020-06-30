<?php
if ( ! function_exists( 'fox_blog_vertical' ) ) return;

$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'vertical';

$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_vertical( $query, $settings );
wp_reset_query();