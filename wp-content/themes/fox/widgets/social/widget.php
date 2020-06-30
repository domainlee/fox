<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'style' => 'black',
    'border_width' => '',
    'shape' => 'circle',
    'align' => 'center',
    'size'  => 'normal',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}
$instance[ 'extra_class' ] = 'widget-social';

fox_social_icons( $instance );

echo $after_widget;