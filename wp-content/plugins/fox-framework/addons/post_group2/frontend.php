<?php
if ( ! function_exists( 'fox_blog_group2' ) ) return;
$settings = $this->get_settings_for_display();
$settings[ 'layout' ] = 'group2';

$query_args = $settings;
$query = fox_query( $query_args );
fox_blog_group2( $query, $settings );
wp_reset_query();