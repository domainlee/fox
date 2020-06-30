<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'big' );
    extract( $options );
}

$post_css = $meta_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-big', 'has-thumbnail' ]; // has-thumbnail is a legacy

if ( isset( $item_align ) ) {
    if ( 'left' == $item_align || 'center' == $item_align || 'right' == $item_align ) {
        $post_class[] = 'post-align-' . $item_align;
    }
}

// custom text color
if ( isset( $text_color ) && $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}

$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

if ( isset( $meta_background ) && $meta_background ) {
    $post_class[] = 'post-has-meta-custom-bg';
    $meta_css[] = 'background:' . $meta_background;
}
$meta_css = join( ';', $meta_css );
if ( ! empty( $meta_css ) ) {
    $meta_css = ' style="' . esc_attr( $meta_css ). '"';
}

if ( ! isset( $options ) ) $options = fox_default_blog_options( 'big' );
extract( $options );
$date_format = apply_filters( 'fox_big_date_format', 'd.m.Y' );
?>

<article <?php post_class( $post_class ); ?>  <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php fox_thumbnail([
        'thumbnail' => $options[ 'thumbnail' ],
        'custom' => $options[ 'thumbnail_custom' ],
        'shape' => $options[ 'thumbnail_shape' ],
        'placeholder' => false,
        'extra_class' => 'post-thumbnail post-big-thumbnail'
    ]); ?>
    
    <div class="big-body container">
            
        <header class="big-header post-item-header">
            
            <?php if ( $show_category || $show_date || $show_author || $show_view || $show_reading_time || $show_comment_link ) { ?>
            
            <div class="post-item-meta big-meta"<?php echo $meta_css; ?>>
                
                <?php if ( $show_category ) fox_post_categories([ 'extra_class' => 'big-cats' ]); ?>
                <?php if ( $show_date ) fox_post_date([ 'extra_class' => 'big-date', 'format' => $date_format, 'style' => 'standard', 'fashion' => 'short' ]); ?>
                <?php if ( $show_author ) fox_post_author( $show_author_avatar ); ?>
                <?php if ( $show_view ) fox_post_view(); ?>
                <?php if ( $show_reading_time ) fox_reading_time(); ?>
                <?php if ( $show_comment_link ) fox_comment_link(); ?>

            </div><!-- .big-meta -->
            
            <?php } ?>
            
            <?php if ( $show_title ) fox_post_title([ 'extra_class' => 'big-title', 'size' => $title_size ]); ?>

        </header><!-- .big-header -->
        
        <?php if ( $show_excerpt ) { ?>
            
            <?php if ( $content_excerpt == 'content' ) { ?>
            <div class="big-content" itemprop="text">

                <?php the_content( '<span class="big-more">'. fox_word( 'more_link' ) .'</span>' );?>

            </div>
            <?php } else { ?>
            <div class="big-content" itemprop="text">

                <?php fox_post_excerpt([ 'length' => -1, 'more' => false ]); ?>

                <?php if ( $excerpt_more ) { ?>

                <a href="<?php the_permalink(); ?>" class="more-link">
                    <span class="big-more"><?php echo fox_word( 'more_link' ); ?></span>
                </a>

                <?php } ?>

            </div>
            <?php } ?>

        <?php } // show excerpt ?>

    </div><!-- .big-body -->
    
</article><!-- .post-big -->