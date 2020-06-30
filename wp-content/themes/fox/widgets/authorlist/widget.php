<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'number' => '4',
    'orderby' => '',
    'order' => '',
    'meta' => '',
    'style' => 'list',
    'column' => '4',
    'avatar_shape' => 'acute',
    'avatar_color' => 'grayscale_color',
    'list_sep' => true,
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

if ( 'DESC' !== $order ) $order = 'ASC';
if ( 'registered' !== $orderby && 'post_count' !== $orderby ) $orderby = 'name';
$args = array(
    'number' => $number,
    'has_published_posts' => true,
    'orderby' => $orderby,
    'order' => $order,
);

$authors = get_users( $args );
if ( ! $authors ) return;

/* Style
-------------------- */
$class = array();
if ( 'grid' !== $style ) $style = 'list';
$class[] = 'widget-author-' . $style;

// column
if ( 'grid' == $style ) {
    $class[] = 'column-' . $column;
}


// avatar shape
// since 4.4.2
if ( 'round' != $avatar_shape && 'circle' != $avatar_shape ) {
    $avatar_shape = 'acute';
}
$class[] = 'authors-avatar--' . $avatar_shape;

// avatar color
if ( ! in_array( $avatar_color, [ 'grayscale', 'color', 'color_grayscale' ] ) ) {
    $avatar_color = 'grayscale_color';
}
if ( 'grayscale' == $avatar_color || 'grayscale_color' == $avatar_color ) {
    $class[] = 'authors-avatar--grayscale';
}
if ( 'grayscale_color' == $avatar_color ) {
    $class[] = 'authors-avatar--hover-color';
}
if ( 'color_grayscale' == $avatar_color ) {
    $class[] = 'authors-avatar--hover-grayscale';
}

// list sep, since 4.4.2
if ( 'list' == $style ) {
    
    if ( $list_sep ) {
        $class[] = 'authors-has-sep';
    }
    
} 

?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <ul class="author-list">
        
        <?php foreach ( $authors as $author ) { ?>
    
        <li class="author-list-item">
            
            <div class="author-list-item-avatar">
                
                <a href="<?php echo get_author_posts_url( $author->ID, $author->nicename ); ?>" title="<?php echo esc_attr( $author->display_name ); ?>">
            
                    <?php echo get_avatar( $author->ID, 150 ); ?>

                </a>
                
            </div><!-- .author-list-item-avatar -->
            
            <?php if ( 'list' === $style ) { ?>
            
            <div class="author-list-item-text">
                
                <h3 class="author-list-item-name">
                    
                    <a href="<?php echo get_author_posts_url( $author->ID, $author->nicename ); ?>"><?php echo $author->display_name; ?></a>
                    
                </h3><!-- .author-list-item-name -->

                <?php if ( 'desc' === $meta ) { if ( $author->description ) { ?>
                
                <div class="author-list-item-description">
                    
                    <?php echo do_shortcode( $author->description ); ?>
                    
                </div>
                
                <?php } } else {
                
                    $args = array(
                        'author'        =>  $author->ID,
                        'orderby'       =>  'post_date',
                        'order'         =>  'DESC',
                        'posts_per_page' => 1
                    );
                    $author_query = new WP_Query( $args );
                    if ( $author_query->have_posts() ) {
                
                ?>
                
                <div class="author-list-item-posts">
                    
                    <?php while( $author_query->have_posts() ) { $author_query->the_post(); ?>
                    
                    <a class="author-list-item-post-name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                
                    <?php } // endwhile ?>
                    
                </div><!-- .author-list-item-posts -->
                
                <?php } // have posts ?>
                
                <?php wp_reset_query(); } // meta after title ?>
            
            </div><!-- .author-list-item-text -->
            
            <?php } // style list ?>
        
        </li><!-- .author-list-item -->
        
        <?php } ?>
        
    </ul><!-- .author-list -->

</div><!-- .widget-author-list -->

<?php echo $after_widget;