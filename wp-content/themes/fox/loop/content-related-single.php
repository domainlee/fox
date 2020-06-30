<article <?php post_class( [ 'post-item', 'post-grid' ] ); ?>  itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="grid-inner">
    
        <?php wi_display_thumbnail('thumbnail-medium','post-item-thumbnail grid-thumbnail',true,true, true); ?>

        <section class="grid-body">

            <div class="post-content">

                <header class="grid-header">

                    <div class="post-item-meta grid-meta">

                        <?php wi_short_date(); ?>

                    </div><!-- .grid-meta -->

                    <h3 class="grid-title" itemprop="headline">
                        
                        <a href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a>
                    
                    </h3>

                </header><!-- .grid-header -->

                <?php wi_entry_excerpt( 16, false, 'grid-content' ); ?>

                <div class="clearfix"></div>

            </div><!-- .post-content -->

        </section><!-- .grid-body -->

        <div class="clearfix"></div>
    
    </div><!-- .grid-inner -->

</article><!-- .post-grid -->