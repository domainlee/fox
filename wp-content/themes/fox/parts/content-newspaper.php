<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'newspaper' );
    extract( $options );
}
// classes
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-newspaper', 'fox-grid-item', 'fox-masonry-item' ];

// custom color
if ( isset( $text_color ) && $text_color ) {
    $post_class[] = 'post-custom-color';
    $post_css[] = 'color:' . $text_color;
}

$post_css = join( ';', $post_css );
if ( ! empty( $post_css ) ) {
    $post_css = ' style="' . esc_attr( $post_css ). '"';
}

// header class
$header_class = [ 'post-header', 'newspaper-header' ];
if ( isset( $header_align ) && $header_align ) {
    $header_class[] = 'align-' . $header_align;
}

// options customized only for standard blog
$meta_options = $options;
$meta_options[ 'date_fashion' ] = 'short';
$meta_options[ 'extra_class' ] = [ 'newspaper-meta' ];

// thumbnail args
$thumbnail_args = [
    'extra_class'   => 'post-thumbnail newspaper-thumbnail',
    'thumbnail'     => 'full',
    'placeholder'   => false,
    'shape'         => isset( $thumbnail_shape ) ? $thumbnail_shape : 'acute',
];

// excerpt args
$excerpt_args = [
    'extra_class' => 'small-dropcap-content dropcap-content columnable-content columnable-content-small',
    'length' => -1,
    'more' => false,
];
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="post-sep"></div>
    
    <div class="post-body post-item-inner post-newspaper-inner masonry-animation-element">
        
        <header class="<?php echo esc_attr( join( ' ', $header_class ) ); ?>">
            
            <?php if ( $show_title ) { fox_post_title([ 'extra_class' => 'newspaper-title', 'size' => 'medium' ]); } ?>
            <?php fox_post_meta( $meta_options ); ?>
        
        </header><!-- .post-header -->
        
        <?php /* ---------      Thumbnail       --------- */ ?>
        <?php if ( $show_thumbnail ) { ?>
        
        <?php if ( isset( $thumbnail_type ) && 'simple' == $thumbnail_type ) { ?>
        
        <?php fox_thumbnail( $thumbnail_args ); ?>
        
        <?php } else { ?>
        
        <?php fox_advanced_thumbnail([
                'extra_class'   => 'newspaper-thumbnail',
            ]); ?>
        
        <?php } // thumbnail type ?>
        
        <?php } // show thumbnail ?>
        
        <div class="post-content newspaper-content">
            
        <?php /* ---------      Content       --------- */ ?>
        <?php if ( 'excerpt' == $content_excerpt ) { ?>
            
            <div class="entry-excerpt">
                
                <?php fox_post_excerpt( $excerpt_args ); ?>
                
                <?php if ( $excerpt_more ) { ?>
                <p class="p-readmore">
                    <a href="<?php the_permalink();?>" class="more-link">
                        <span class="post-more"><?php echo fox_word( 'read_more' ); ?></span>
                    </a>
                </p><!-- .p-readmore -->
                <?php } ?>

            </div><!-- .entry-excerpt -->
        
        <?php } else { // content ?>
        
            <div class="entry-content small-dropcap-content dropcap-content columnable-content columnable-content-small" itemprop="text">

                <?php 
                      // .post-more class is just a legacy
                      the_content( '<span class="post-more">' . fox_word( 'more_link' ) . '</span>' );
                      fox_page_links();
                ?>

            </div><!-- .entry-content -->
        
        <?php } // content excerpt ?>
            
        </div><!-- .post-content -->
        
        <?php if ( $show_share ) fox_share(); ?>
        
        <?php if ( $show_related ) fox_blog_related( 'newspaper' ); ?>
        
    </div><!-- .post-body -->

</article><!-- .post-newspaper -->