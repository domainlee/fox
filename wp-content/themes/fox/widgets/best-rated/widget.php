<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    
    'title' => '',
    'number' => '4',
    'category' => '',
    'tag' => '',
    'orderby' => '',
    
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

if ( !empty( $title ) ) {	
    
    echo $before_title . $title . $after_title;
    
}

$layout = 'big';
$author = '';
$thumbnail = 'landscape';
$thumbnail_align = '';
$index = false;
$show_date = true;
$show_excerpt = false;
$order = 'desc';
$view = false;

if ( 'date' == $orderby ) $orderby = 'review_date';
else $orderby = 'review_score';

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

$params = [
    
    'extra_class' => 'blog-widget blog-widget-' . $layout,
    
    'layout' => ( 'big' == $layout ? 'grid' : 'list' ),
    'list_mobile_layout' => 'list',
    'column' => '1',
    'item_spacing' => 'small',
    'item_template' => ( 'big' == $layout ? 2 : 1 ),
    
    'thumbnail_show' => true,
    'thumbnail_format_indicator' => ( 'big' == $layout ? 'true' : 'false' ),
    'thumbnail' => $thumbnail,
    'thumbnail_position' => $thumbnail_align,
    'thumbnail_index' => $index,
    'thumbnail_view' => $view,
    'thumbnail_review_score' => ( 'review_score' == $orderby || 'review_date' == $orderby ) ? true : false,
    
    'thumbnail_hover' => 'none',
    'thumbnail_showing_effect' => 'none',
    
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
    
];

$layout = 'big' == $layout ? 'grid' : 'list';

fox44_blog( $layout, $local_params, $query );

echo $after_widget;