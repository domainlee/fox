<?php
/**
 * Post View Plugin Concerning
 */
add_filter( 'pvc_most_viewed_posts_html', 'fox_custom_most_viewed_posts_html', 10, 2 );
/**
 * @since 4.0
 */
function fox_custom_most_viewed_posts_html( $html, $args ) {
    
    ob_start();
    
    $defaults = array(
        'number_of_posts'		 => 5,
        'post_type'				 => array( 'post' ),
        'order'					 => 'desc',
        'thumbnail_size'		 => 'thumbnail',
        'show_post_views'		 => true,
        'show_post_thumbnail'	 => false,
        'show_post_excerpt'		 => false,
        'no_posts_message'		 => esc_html__( 'No Posts', 'wi' ),
        'item_before'			 => '',
        'item_after'			 => ''
    );

    $args = apply_filters( 'pvc_most_viewed_posts_args', wp_parse_args( $args, $defaults ) );
    
    $query = fox_query([
        'number' => $args[ 'number_of_posts' ],
        'orderby' => 'view',
        'order' => $args[ 'order' ],
    ]);
    
    $params = [
        
        'extra_class' => 'blog-widget blog-widget-big',
        
        'layout' => 'grid',
        'column' => '1',
        'item_spacing' => 'small',
        'item_template' => 2,

        'thumbnail_show' => $args[ 'show_post_thumbnail' ],
        'thumbnail_format_indicator' => 'true',
        
        'thumbnail' => $args[ 'thumbnail_size' ],
        'thumbnail_index' => 'true',
        'thumbnail_view' => $args[ 'show_post_views' ],
        'thumbnail_review_score' => 'false',

        'title_show' => true,
        'title_tag' => 'h3',
        'title_size' => 'small',
        'title_extra_class' => 'latest-title',

        'date_show' => 'false',
        'category_show' => 'false',
        'author_show' => false,
        'reading_time_show' => false,
        'comment_link_show' => false,
        'view_show' => false,
        
        'excerpt_show' => 'true',

        'excerpt_length' => 22,
        'excerpt_more' => 'true',
        'excerpt_more_style' => 'simple'
    ];
    
    fox44_blog( 'grid', $params, $query );

    return ob_get_clean();

}