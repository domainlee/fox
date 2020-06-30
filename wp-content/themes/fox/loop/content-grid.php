<article <?php post_class( [ 'wi-post', 'post-item', 'post-grid' ] ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="grid-inner">
    
        <?php wi_display_thumbnail( 'thumbnail-medium','post-item-thumbnail grid-thumbnail',true,true, true ); ?>

        <section class="grid-body">

            <div class="post-content">

                <header class="grid-header">
                    
                    <?php wi_short_meta( 'grid-meta' ); ?>
                    
                    <?php wi_post_title( 'grid-title' ); ?>

                </header><!-- .grid-header -->

                <?php 
                $grid_excerpt_length = get_theme_mod('fox_grid_excerpt_length') ? absint(get_theme_mod('fox_grid_excerpt_length')) : 22; 
                if ($grid_excerpt_length < 1) $grid_excerpt_length = 22;
                ?>
                
                <?php wi_entry_excerpt( $grid_excerpt_length, null, 'grid-content' ); ?>

                <div class="clearfix"></div>

            </div><!-- .post-content -->

        </section><!-- .grid-body -->

        <div class="clearfix"></div>
    
    </div><!-- .grid-inner -->

</article><!-- .post-grid -->