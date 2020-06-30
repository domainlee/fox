<?php
$post_class = [
    'post-item',
    'post-related',
]; ?>

<article <?php post_class( $post_class ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="related-inner">
    
        <?php fox_thumbnail([
            'thumbnail' => 'thumbnail-medium',
            'placeholder' => true,
            'extra_class' => 'related-thumbnail',
            'format_indicator' => false,
    
            // @todo: circle, round
            'shape' => 'acute',
        ]); ?>

        <div class="related-body post-item-body">
            
            <?php fox_post_title([
                'tag' => 'h3',
                'extra_class' => 'related-title',
                'title_size' => 'tiny',
            ]); ?>

        </div><!-- .related-body -->
    
    </div><!-- .related-inner -->

</article><!-- .post-related -->
