<?php 

$column = wi_content_column();
$column_class = ( $column == '1' ) ? 'disable-2-columns' : 'enable-2-columns';

?>
<article <?php post_class( array( 'wi-post', $column_class ) ); ?> itemscope itemtype="https://schema.org/CreativeWork">
        
    <section class="post-body">
        
        <header class="post-header">
            
            <?php wi_post_title( 'post-title' ); ?>
            
            <div class="post-header-meta">

                <?php if (!get_theme_mod('fox_disable_blog_date')):?>
                <?php wi_entry_date(); ?>
                <?php endif; ?>
                
                <?php if (!get_theme_mod('fox_disable_blog_categories') ):?>
                <?php wi_entry_categories(); ?>
                <?php endif; ?>
                
                <?php if (!get_theme_mod('fox_disable_blog_author')):?>
                <?php wi_entry_author(); ?>
                <?php endif; ?>
                
                <?php if ( get_theme_mod('fox_blog_view_count')):?>
                <?php wi_view_count(); ?>
                <?php endif; ?>
                
            </div><!-- .post-header-meta -->

        </header><!-- .post-header -->
        
        <?php if ( !get_theme_mod('fox_disable_blog_image') ) wi_entry_thumbnail(); ?>

        <div class="post-content" itemprop="text">
            
            <?php if (get_theme_mod('fox_blog_standard_display') == 'excerpt'): ?>
            
            <div class="entry-content entry-excerpt dropcap-content columnable-content">
                <?php the_excerpt(); ?>
                
                <?php if (!get_theme_mod('fox_disable_blog_readmore')):?>
                <p class="p-readmore">
                    <a href="<?php the_permalink();?>" class="more-link"><span class="post-more"><?php echo fox_word( 'more_link' ); ?></span></a>
                </p>
                <?php endif; ?>
                
            </div><!-- .entry-content -->
            
            <?php else: ?>
            
            <div class="entry-content dropcap-content columnable-content">
                <?php
                    the_content('<span class="post-more">' . fox_word( 'more_link' ) . '</span>');
                ?>
                <div class="clearfix"></div>
            </div><!-- .entry-content -->
            
            <?php endif; ?>
                
            <?php if (!get_theme_mod('fox_disable_blog_share')):?>
                <?php wi_share(true); ?>
            <?php endif; ?>
                        
            <div class="clearfix"></div>

        </div><!-- .post-content -->
        
        <?php /*------------------------		RELATED		------------------------------- */ ?>
        <?php if( !get_theme_mod('fox_disable_blog_related')): ?>

            <?php

            $related_query = wi_related_query();

            if ( $related_query && $related_query->have_posts() ) {
                ?>
                <div class="related-area">

                    <h3 class="blog-related-heading"><span><?php _e('You might be interested in','wi');?></span></h3>

                    <div class="blog-related">
                        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>

                            <?php get_template_part( 'loop/content', 'related' ); ?>

                        <?php endwhile; ?>

                        <div class="clearfix"></div>

                        <div class="line line1"></div>
                        <div class="line line2"></div>

                    </div><!-- .blog-related -->
                    
                </div><!-- #related-posts -->

                <?php	
                
            }
            wp_reset_query();
            ?>

        <?php endif; // blog related ?>
        
    </section><!-- .post-body -->
    
    <div class="clearfix"></div>
    
</article><!-- .wi-post -->