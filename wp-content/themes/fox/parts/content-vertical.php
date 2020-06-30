<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'vertical' );
    extract( $options );
}
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-vertical' ];
if ( 'right' != $thumbnail_position ) $thumbnail_position = 'left';
$post_class[] = 'post-thumbnail-align-' . $thumbnail_position;

if ( $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}
$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

$options[ 'title_size' ] = 'large';
$options[ 'title_class' ] = 'post-vertical-title';
$options[ 'header_class' ] = 'post-vertical-header';
$options[ 'excerpt_class' ] = 'post-vertical-content';
$options[ 'date_fashion' ] = 'short';

$thumbnail_args = $options;
$thumbnail_args[ 'placeholder' ] = false;
$thumbnail_args[ 'extra_class' ] = 'vertical-thumbnail';
$thumbnail_args[ 'shape' ] = 'acute';
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php if ( $list_sep ) { ?>
    <div class="post-list-sep"></div>
    <?php } ?>
    
    <div class="post-item-inner post-vertical-inner">
    
        <?php if ( $show_thumbnail ) {
            if ( isset( $thumbnail_type ) && 'advanced' == $thumbnail_type ) {
                fox_advanced_thumbnail([ 'extra_class' => 'vertical-thumbnail' ]);
            } else {
                fox_thumbnail( $thumbnail_args );
            }
        } ?>

        <div class="post-body">

            <div class="post-content">

                <?php fox_post_body( $options ); ?>

            </div><!-- .post-content -->

        </div><!-- .post-body -->
        
    </div><!-- .post-vertical-inner -->

</article><!-- .post-vertical -->