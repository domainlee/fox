<article <?php post_class('post-related'); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="related-inner">
    
        <?php wi_display_thumbnail('thumbnail-medium','related-thumbnail',true, true);?>

        <section class="related-body">

            <div class="post-content">

                <header class="related-header">

                    <h3 class="related-title" itemprop="headline">
                        
                        <a href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a>
                    
                    </h3>

                </header><!-- .related-header -->

                <div class="clearfix"></div>

            </div><!-- .post-content -->

        </section><!-- .related-body -->

        <div class="clearfix"></div>
    
    </div><!-- .related-inner -->

</article><!-- .post-related -->
