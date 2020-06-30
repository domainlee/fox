<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'list' );
    extract( $options );
}
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-list' ];
if ( isset( $extra_post_class ) ) $post_class[] = $extra_post_class;

// thumbnail position
if ( 'right' != $thumbnail_position ) $thumbnail_position = 'left';
$post_class[] = 'post-thumbnail-align-' . $thumbnail_position;

// valign
if ( 'middle' != $list_valign && 'bottom' != $list_valign ) $list_valign = 'top';
$post_class[] = 'post-valign-' . $list_valign;

// list mobile layout
if ( 'list' != $list_mobile_layout ) $list_mobile_layout = 'grid';
$post_class[] = 'list-mobile-layout-' . $list_mobile_layout;

// text color
if ( $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}
$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

// customized options for list layout
$options[ 'title_class' ] = 'list-title';
$options[ 'header_class' ] = 'list-header';
$options[ 'excerpt_class' ] = 'list-content';
$options[ 'date_fashion' ] = 'short';

$thumbnail_args = $options;
$thumbnail_args[ 'thumbnail' ] = $thumbnail;
$thumbnail_args[ 'extra_class' ] = 'list-thumbnail';
if ( isset( $thumbnail_inside ) ) {
    $thumbnail_args[ 'inside' ] = $thumbnail_inside;
}

// thumbnail width
if ( isset ( $thumbnail_width ) ) {
    if ( $thumbnail_width != '' ) {
        if ( is_numeric( $thumbnail_width ) ) $thumbnail_width .= 'px';
    }
    if ( $thumbnail_width ) {
        $thumbnail_args[ 'extra_css' ] = 'width:' . $thumbnail_width;
    }
}

// sep css
// just for multi-purpose
$sep_css = '';
if ( isset( $sep_border_color ) && $sep_border_color ) {
    $sep_css = ' style="border-color:' . esc_attr( $sep_border_color ). '"';
}
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php if ( isset( $list_sep ) && $list_sep ) { ?>
    <div class="post-list-sep"<?php echo $sep_css; ?>></div>
    <?php } ?>
    
    <div class="post-item-inner post-list-inner">
    
        <?php if ( $show_thumbnail ) { fox_thumbnail( $thumbnail_args  ); } ?>

        <div class="post-body post-item-body post-list-body">

            <div class="post-content">

                <?php fox_post_body( $options ); ?>

            </div><!-- .post-content -->

        </div><!-- .post-body -->
        
    </div><!-- .post-list-inner -->

</article><!-- .post-list -->