<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'image' => '',
    'tablet' => '',
    'phone' => '',
    'url' => '',
    'target' => '_blank',
    'code' => '',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( ! empty( $title ) ) {
    echo $before_title . $title . $after_title;
}

fox_ad( $instance );

echo $after_widget;