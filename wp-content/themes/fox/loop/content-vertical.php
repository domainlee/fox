<?php
/**
 * Vertical content part
 * @since 3.0
 */
?>
<article <?php post_class( [ 'post-item', 'post-vertical' ] ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php wi_display_thumbnail( 'thumbnail-vertical','vertical-thumbnail', true, false , false ); ?>
        
    <section class="post-body">
        
        <div class="post-content">
            
            <header class="post-vertical-header">
                
                <?php wi_short_meta( 'post-vertical-meta' ); ?>
                
                <?php wi_post_title( 'post-vertical-title' ); ?>
        
            </header><!-- .post-vertical-header -->
            
            <?php wi_entry_excerpt( 'post-vertical-content' ); ?>
            
            <div class="clearfix"></div>

        </div><!-- .post-content -->
        
    </section><!-- .post-body -->
    
    <div class="clearfix"></div>
    
</article><!-- .post-vertical -->