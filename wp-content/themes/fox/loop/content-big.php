<?php $thumbnail_class = ( has_post_thumbnail() && !get_post_format() ) ? ' has-thumbnail' : ''; ?>

<article <?php post_class( 'post-big' . $thumbnail_class); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php wi_entry_thumbnail(); ?>
    
    <section class="big-body">
            
        <header class="big-header">

            <div class="big-meta">

                <?php if ( get_the_category_list(__( '<span class="sep">/</span>', 'wi' )) ): ?>
                <span class="big-cats">
                    <?php echo get_the_category_list(__( '<span class="sep">/</span>', 'wi' )); ?>
                </span><!-- .big-cats -->
                <?php endif; ?>

                <span class="big-date">
                    <time datetime="<?php echo get_the_date('c');?>"><?php echo get_the_date('d.m.Y');?></time>
                </span><!-- .big-date -->

            </div><!-- .big-meta -->
            
            <?php wi_post_title( 'big-title' ); ?>

            <div class="big-content" itemprop="text">

                <?php the_content( '<span class="big-more">'. fox_word( 'more_link' ) .'</span>' );?>

            </div>

        </header><!-- .post-header -->

    </section><!-- .big-body -->
    
    <div class="clearfix"></div>
    
</article><!-- .post-big -->