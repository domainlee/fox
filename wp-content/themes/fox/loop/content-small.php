<article <?php post_class('post-small small-item'); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="small-inner">
    
        <?php wi_display_thumbnail('thumbnail-medium','small-thumbnail',true,true); ?>

        <section class="small-body">

            <header class="small-header">

                <h3 class="small-title" itemprop="headline">
                    <a href="<?php the_permalink();?>"><?php the_title();?></a>
                </h3>

            </header><!-- .small-header -->

            <?php wi_entry_excerpt( 12, false, 'small-excerpt' ); ?>

            <div class="clearfix"></div>

        </section><!-- .small-body -->

        <div class="clearfix"></div>
    
    </div><!-- .small-inner -->

</article><!-- .post-small -->
