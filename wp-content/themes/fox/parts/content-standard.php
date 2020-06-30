<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'standard' );
    extract( $options );
}
// post class
$post_css = [];
$post_class = [ 'wi-post', 'post-item', 'post-standard' ];
if ( isset( $item_align ) ) {
    if ( 'left' == $item_align || 'center' == $item_align || 'right' == $item_align ) {
        $post_class[] = 'post-header-align-' . $item_align;
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

// header class
$header_class = [ 'post-header' ];
if ( isset( $header_align ) && $header_align ) {
    $header_class[] = 'align-' . $header_align;
}

// options customized only for standard blog
$meta_options = $options;
$meta_options[ 'date_fashion' ] = 'long';
$meta_options[ 'extra_class' ] = [ 'post-header-meta', 'post-standard-meta' ];

// thumbnail args
$thumbnail_args = [
    'extra_class'   => 'post-thumbnail',
    'thumbnail'     => 'full',
    'placeholder'   => false,
    'shape'         => $thumbnail_shape,
];

// excerpt args
$excerpt_args = [
    'extra_class' => 'dropcap-content columnable-content',
    'length' => -1,
    'more' => false,
];
?>

<article <?php post_class( $post_class ); ?> <?php echo $post_css; ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="post-sep"></div>
    
    <div class="post-body post-item-inner post-standard-inner">
        
        <header class="<?php echo esc_attr( join( ' ', $header_class ) ); ?>">
            
            <?php if ( $show_title ) { fox_post_title([ 'extra_class' => 'post-title' ]); } ?>
            <?php fox_post_meta( $meta_options ); ?>
        
        </header><!-- .post-header -->
        
        <?php /* ---------      Thumbnail       --------- */ ?>
        <?php if ( $show_thumbnail ) { ?>
        
        <?php if ( 'simple' == $thumbnail_type ) { ?>
        
        <?php fox_thumbnail( $thumbnail_args ); ?>
        
        <?php } else { ?>
        
        <?php fox_advanced_thumbnail(); ?>
        
        <?php } // thumbnail type ?>
        
        <?php } // show thumbnail ?>
        
        <div class="post-content">
            
        <?php /* ---------      Content       --------- */ ?>
        <?php if ( 'excerpt' == $content_excerpt ) { ?>
            
            <div class="entry-excerpt">
                
                <?php fox_post_excerpt( $excerpt_args ); ?>
                
                <?php if ( $excerpt_more ) { ?>
                
                <p class="p-readmore">
                    <a href="<?php the_permalink();?>" class="more-link">
                        <span class="post-more"><?php echo fox_word( 'read_more' );?></span>
                    </a>
                </p><!-- .p-readmore -->
                
                <?php } ?>

            </div><!-- .entry-excerpt -->
        
        <?php } else { // content ?>
        
            <div class="entry-content dropcap-content columnable-content" itemprop="text">

                <?php 
                      // .post-more class is just a legacy
                      the_content( '<span class="post-more">' . fox_word( 'more_link' ) . '</span>' );
                      fox_page_links();
                ?>

            </div><!-- .entry-content -->
        
        <?php } // content excerpt ?>
            
        </div><!-- .post-content -->
        
        <?php if ( $show_share ) fox_share(); ?>
        
        <?php if ( $show_related ) fox_blog_related(); ?>
        
    </div><!-- .post-body -->

</article><!-- .post-standard -->