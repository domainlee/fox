<?php
$post_class = [
    'post-item',
    'post-related',
]; ?>

<article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="related-inner">
    
        <?php fox_thumbnail([
            'thumbnail' => 'thumbnail',
            'placeholder' => false,
            'extra_class' => 'related-thumbnail',

            // @todo: circle, round
            'shape' => 'acute',
            'format_indicator' => false,
        ]); ?>

        <div class="related-body post-item-body">

        <?php fox_post_body([
            'title_tag' => 'h3',
            'title_class' => 'related-title',
            'title_size' => 'tiny',

            'show_excerpt' => true,
            'excerpt_class' => 'related-excerpt',
            'excerpt_length' => '20',
            'excerpt_more' => false,

            'show_date' => false,
            'show_category' => false,
            'show_author' => false,
        ]); ?>
        
        </div><!-- .related-body -->
    
    </div><!-- .related-inner -->
    
</article><!-- .post-related -->
