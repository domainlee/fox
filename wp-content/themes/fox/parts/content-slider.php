<?php
if ( ! isset( $options ) ) {
    $options = fox_default_blog_options( 'slider' );
    extract( $options );
}
$post_class = [ 'post-item', 'post-slider' ];
$meta_options = $options;
$meta_options[ 'extra_class' ]  = 'slider-meta';

// align
if ( 'center' != $item_align && 'right' != $item_align ) $item_align = 'left';
$post_class[] = 'post-slide-align-' . $item_align;

// 
if ( $title_background ) {
    $post_class[] = 'style--title-has-background';
}

?>
<article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php fox_thumbnail([
    
        'extra_class' => 'slider-thumbnail',
        'thumbnail' => 'custom',
        'custom' => $slider_size, 
        'format_indicator' => false,
        'disable_lazyload' => true,
    
    ]); ?>
    
    <div class="slider-body">
        
        <div class="slider-table">
            
            <div class="slider-cell">
        
                <div class="post-content">

                    <?php if ( $show_title ) { ?>
                    
                    <div class="slider-header">

                        <?php fox_post_title([
    'extra_class' => 'slider-title',
    'tag' => $title_tag,
    'size' => $title_size,
    'weight' => $title_weight,
    'text_transform' => $title_text_transform,
]); ?>

                    </div><!-- .slider-header -->
                    
                    <?php } ?>

                    <?php if ( $show_excerpt ) { ?>
                    
                    <div class="slider-excerpt">
                        
                        <?php fox_post_meta( $meta_options ); ?>

                        <?php fox_post_excerpt([ 'extra_class' => 'slider-excerpt-text', 'exclude_class' => [ 'entry-excerpt' ], 'length' => $excerpt_length, 'more' => ( 'yes' == $excerpt_more ) ]); ?>

                    </div><!-- .slider-excerpt -->
                    
                    <?php } ?>

                </div><!-- .post-content -->
            
            </div><!-- .slider-cell -->
        
        </div><!-- .slider-table -->
        
    </div><!-- .slider-body -->
    
</article><!-- .post-slider -->