<article <?php post_class('post-slider'); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php wi_display_thumbnail('thumbnail-big','slider-thumbnail',false,false); ?>
    
    <section class="slider-body">
        
        <div class="slider-table"><div class="slider-cell">
        
            <div class="post-content">

                <header class="slider-header">

                    <h2 class="slider-title" itemprop="headline">
                        <a href="<?php the_permalink();?>" rel="bookmark"><?php the_title();?></a>
                    </h2>

                </header><!-- .slider-header -->
                
                <div class="slider-excerpt">
                    <p>
                        <span class="slider-meta">
                            
                            <?php wi_entry_date(); ?>
                            
                        </span><!-- .slider-meta -->
                        
                        <span itemprop="text" class="sldier-excerpt-text">
                            <?php echo wi_subword(get_the_excerpt(),0,20);?>&hellip;
                        </span>
                        
                        <a class="slider-more" href="<?php the_permalink();?>" rel="bookmark"><?php echo fox_word( 'more_link' ); ?></a>
                        
                    </p>
                
                </div>
                
                <div class="clearfix"></div>

            </div><!-- .post-content -->
            
        </div></div><!-- .slider-cell --><!-- .slider-table -->
        
    </section><!-- .slider-body -->
    
    <div class="clearfix"></div>
    
</article><!-- .post-slider -->