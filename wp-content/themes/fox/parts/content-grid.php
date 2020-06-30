<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'grid' );
    extract( $options );
}
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-grid', 'fox-grid-item' ];
if ( isset( $extra_post_class ) ) $post_class[] = $extra_post_class;

// item align
if ( isset( $item_align ) ) {
    if ( 'left' == $item_align || 'center' == $item_align || 'right' == $item_align ) {
        $post_class[] = 'post-align-' . $item_align;
    }
}

// custom text color
if ( $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}
$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

// $options is the option we got from Fox_Blog class
// now we need few more parameters set for only grid layout

$options[ 'header_class' ] = 'grid-header';
$options[ 'title_class' ] = 'grid-title';
$options[ 'excerpt_class' ] = 'grid-content';
$options[ 'date_fashion' ] = 'short';

$thumbnail_args = $options;
$thumbnail_args[ 'thumbnail' ] = $thumbnail;
$thumbnail_args[ 'extra_class' ] = 'grid-thumbnail';
if ( isset( $thumbnail_inside ) ) {
    $thumbnail_args[ 'inside' ] = $thumbnail_inside;
}
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="post-item-inner grid-inner">
    
        <?php
        if ( $show_thumbnail ) { 
            if ( isset( $thumbnail_type ) && 'advanced' == $thumbnail_type ) {
                fox_advanced_thumbnail([
                    'extra_class' => 'grid-thumbnail',
                ]);
            } else {
                fox_thumbnail( $thumbnail_args );
            }
        } ?>

        <div class="grid-body post-item-body">
            
            <?php fox_post_body( $options ); ?>

        </div><!-- .grid-body -->
    
    </div><!-- .grid-inner -->

</article><!-- .post-grid -->