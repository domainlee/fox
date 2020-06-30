<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'url' => 'https://www.facebook.com/withemes',
    'show_faces' => '',
    'stream' => '',
    'header' => '',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

wp_enqueue_script( 'wi-facebook' );

$width = absint( get_theme_mod( 'wi_sidebar_width', 265 ) );
if ( $width < 100 || $width > 1000 ) $width = 265;

$like_box_xfbml = "<fb:like-box href=\"$url\" width=\"$width\" show_faces=\"$show_faces\" colorscheme=\"light\" border_color=\"#000\" stream=\"$stream\" header=\"$header\"></fb:like-box>";
echo '<div class="fb-container">' . $like_box_xfbml . '</div>';

echo $after_widget;