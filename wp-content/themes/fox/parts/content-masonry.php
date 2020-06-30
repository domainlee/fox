<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'masonry' );
    extract( $options );
}
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-masonry', 'fox-grid-item', 'fox-masonry-item' ];
if ( isset( $extra_post_class ) ) $post_class[] = $extra_post_class;

if ( isset( $first_post_featured ) && $first_post_featured ) {
    $post_class[] = 'masonry-featured-post';
}

if ( isset( $item_align ) ) {
    if ( 'left' == $item_align || 'center' == $item_align || 'right' == $item_align ) {
        $post_class[] = 'post-align-' . $item_align;
    }
}

// text color
if ( isset( $text_color ) && $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}
$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

// customized options for masonry layout
$options[ 'title_class' ] = 'masonry-title';
$options[ 'header_class' ] = 'masonry-header';
$options[ 'excerpt_class' ] = 'masonry-content dropcap-content small-dropcap-content';
$options[ 'date_fashion' ] = 'short';
if ( isset( $thumbnail_inside ) ) {
    $thumbnail_args[ 'inside' ] = $thumbnail_inside;
}

// thumbnail args
$thumbnail_args = $options;
$thumbnail_args[ 'extra_class' ] = 'masonry-thumbnail masonry-animation-element';
if ( isset( $column ) && $column >= 3 ) {
    $thumbnail_args[ 'thumbnail' ] = 'medium';
} else {
    $thumbnail_args[ 'thumbnail' ] = 'large';
}

// $first_post is only set when we enable "big first post" mode in masonry layout
if ( isset( $first_post_featured ) && $first_post_featured ) {
    $thumbnail_args[ 'thumbnail' ] = 'large';
}
$thumbnail_args[ 'placeholder' ] = false;
$thumbnail_args[ 'custom' ] = '';
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="post-body masonry-item-inner">
    
        <?php if ( isset( $show_thumbnail ) && $show_thumbnail ) { fox_thumbnail( $thumbnail_args ); } ?>

        <div class="masonry-body post-item-body masonry-animation-element">
            
            <?php fox_post_body( $options ); ?>

        </div><!-- .masonry-body -->
    
    </div><!-- .post-body -->

</article><!-- .post-masonry -->