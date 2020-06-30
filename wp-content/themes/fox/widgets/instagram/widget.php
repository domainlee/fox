<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    
    'title' => '',
    'username' => '',
    'number' => '',
    'column' => '',
    'size' => '',
    'item_spacing' => 'tiny',
    'show_header' => true,
    'show_meta' => '',
    'crop' => true,
    'cache_time' => '',
    'follow_text' => '',
    
    'images' => '', // for demo version
) ) );

echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

$params = $instance;

fox_render_instagram( $instance );

echo $after_widget;