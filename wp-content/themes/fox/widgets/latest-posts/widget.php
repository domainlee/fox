<?php

extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'number' => '4',
    'category' => '',
    'tag' => '',
    'author' => '',
    
    'orderby' => 'date',
    'order' => 'desc',
    
    'show_excerpt' => false,
    'show_date' => true,
    'layout' => 'small',
    'thumbnail_show' => true,
    'thumbnail_align' => 'left',
    'thumbnail' => 'landscape',
    'index' => '',
    'view' => '',
) ) );

echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

if ( 'big' !== $layout ) $layout = 'small';

$args = [
    'number' => $number,
    'unique_posts' => false,
    'pagination' => false,
    
    'orderby' => $orderby,
    'order' => $order,
];
if ( $tag ) {
    $tags = explode( ',', $tag );
    $tags = array_map( 'trim', $tags );
    $args[ 'tags' ] = $tags;
}
if ( $category ) {
    $args[ 'categories' ] = [ $category ];
}
if ( $author ) {
    $args[ 'author' ] = $author;
}

$query = fox_query( $args );

$local_params = [
    
    'extra_class' => 'blog-widget blog-widget-' . $layout,
    'list_mobile_layout' => 'list',
    'column' => '1',
    'item_spacing' => 'small',
    'item_template' => ( 'big' == $layout ? 2 : 1 ),
    
    'thumbnail_show' => $thumbnail_show,
    'thumbnail_format_indicator' => ( 'big' == $layout ? true : false ),
    'thumbnail' => $thumbnail,
    'thumbnail_position' => $thumbnail_align,
    'thumbnail_index' => $index,
    'thumbnail_view' => $view,
    'thumbnail_review_score' => ( 'review_score' == $orderby || 'review_date' == $orderby ) ? true : false,
    
    'thumbnail_hover' => 'none',
    'thumbnail_showing_effect' => 'none',
    
    'title_show' => true,
    'title_tag' => 'h3',
    'title_size' => ( 'big' == $layout ? 'small' : 'tiny' ),
    'title_extra_class' => 'latest-title',
    
    'date_show' => $show_date,
    
    'category_show' => false,
    'author_show' => false,
    'reading_time_show' => false,
    'comment_link_show' => false,
    'view_show' => false,
    
    'excerpt_show' => $show_excerpt,
    
    'excerpt_length' => ( 'big' == $layout ? '22' : '10' ),
    'excerpt_more' => false,
    
    'live' => 'big' == $layout ? true : false,
    
];

$layout = 'big' == $layout ? 'grid' : 'list';

fox44_blog( $layout, $local_params, $query );

echo $after_widget;