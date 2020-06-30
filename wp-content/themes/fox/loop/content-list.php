<article <?php post_class( [ 'post-item', 'post-list' ] ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php wi_display_thumbnail( 'thumbnail-medium','post-item-thumbnail list-thumbnail',true,true, true ); ?>
        
    <section class="post-body">
        
        <div class="post-content">
            
            <header class="list-header">
                
                <?php wi_short_meta( 'list-meta' ); ?>
                
                <?php wi_post_title( 'list-title' ); ?>
        
            </header><!-- .list-header -->
            
            <?php wi_entry_excerpt( null, null, 'list-content' ); ?>
            
            <div class="clearfix"></div>

        </div><!-- .post-content -->
        
    </section><!-- .post-body -->
    
    <div class="clearfix"></div>
    
</article><!-- .post-list -->
