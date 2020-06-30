<?php
    // Start the loop.
    while ( have_posts() ) : the_post();
?>

<div class="container">
    
    <?php wi_thumbnail_carousel(); ?>
    
    <div <?php post_class( 'content wi-single-post partial-content' ); ?>>
    
        <main id="primary" class="content-area primary" role="main">
            
            <div class="theiaStickySidebar">
            
            <?php if (!get_theme_mod('fox_disable_single_image')) wi_entry_thumbnail(); ?>
            
            <header class="post-header">
            
                <h1 class="post-title single-title"><?php the_title();?></h1>

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
            
            <div class="single-body">
                
                <?php wi_single_ad( 'before' ); ?>
                
                <?php wi_review(); ?>
                
                <div class="entry-content">
                    <?php
                        the_content(); wi_page_links();
                    ?>
                    <div class="clearfix"></div>

                </div><!-- .entry-content -->
                
                <?php wi_single_ad( 'after' ); ?>

                <?php wi_single_share( 'post' ); ?>
                    
            </div><!-- .single-body -->

            <div class="clearfix"></div>
            
            <?php /*------------------------		TAGS		------------------------------- */ ?>
            <?php if ( ! get_theme_mod( 'wi_disable_nextpost_tags' ) && !get_theme_mod('fox_disable_single_tag') && get_the_tag_list()):?>
            <div class="single-tags">
                <span class="tag-label"><?php echo esc_html__( 'Tags:', 'wi' ); ?></span>
                <?php echo get_the_tag_list();?>				
            </div><!-- .tags -->
            <?php endif; ?>
            

            <?php /*------------------------		RELATED		------------------------------- */ ?>
            <?php if( ! get_theme_mod( 'wi_disable_nextpost_related' ) && !get_theme_mod('fox_disable_single_related')): ?>

                <?php

                global $post;
                $current_ID = $post->ID;
                $tags = wp_get_post_tags( $current_ID, array( 'fields' => 'ids' ) );
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,

                    'ignore_sticky_posts'   =>  true,
                    'no_found_rows' => true,
                    'cache_results' => false,
                    'post__not_in' => array( $current_ID ),
                );
                if ( !empty( $tags ) ) {
                    $args[ 'tag__in' ] = $tags;
                    
                    $related_posts = get_posts( $args );
                    if ( $related_posts ): $count = 0;?>
                    <div class="related-posts" id="related-posts">
                        
                        <h3 class="related-heading"><span><?php _e('You might be interested in','wi');?></span></h3>
                        
                        <div class="related-list blog-grid column-3">
                            <?php foreach ( $related_posts as $post ): setup_postdata($post); $count++;?>
                                
                                <?php get_template_part('loop/content-related', 'single' ); ?>

                            <?php endforeach; ?>
                            
                            <?php wp_reset_postdata(); ?>
                            
                            <div class="clearfix"></div>
                            
                        </div><!-- .related-list -->
                    </div><!-- #related-posts -->

                    <?php	
                    endif; // if realted posts
                }
                ?>

            <?php endif; // single related ?>


            <?php if( ! get_theme_mod( 'wi_disable_nextpost_authorbox' ) && !get_theme_mod('fox_disable_single_author')): ?>

                <div class="authorbox" id="authorbox"><div class="authorbox-inner">
                    <div class="author-avatar">
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>" rel="author">
                            <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wi_author_bio_avatar_size', 120 ) ); ?>
                        </a>
                    </div>
                    <div class="authorbox-content">

                        <?php /* ------- NAV -------- */ ?>

                        <?php if( !get_theme_mod('fox_disable_single_author_posts')): ?>
                        <nav class="authorbox-nav">
                            <ul>
                                <li class="active"><a data-href="#authorbox-info"><?php echo get_the_author(); ?></a></li>
                                <li><a data-href="#same-author"><?php _e('Latest posts','wi');?></a></li>
                            </ul>
                        </nav><!-- .authorbox-nav -->
                        <?php endif; ?>

                        <?php /* ------- INFO -------- */ ?>

                        <div class="authorbox-info authorbox-tab active" id="authorbox-info">
                            
                            <div class="desc">
                                <p><?php the_author_meta( 'description' ); ?></p>
                            </div>
                            <div class="author-social social-list">
                                <ul>
                                    <?php $short_social_arr = 'twitter, facebook-square, google-plus, tumblr, instagram, pinterest-p, linkedin, youtube, vimeo, soundcloud, flickr';
                                    $short_social_arr = explode(',',$short_social_arr);
                                    $short_social_arr = array_map('trim',$short_social_arr);
                                    ?>
                                    <?php foreach ( $short_social_arr as $sc ): ?>
                                        <?php if ( $url = get_the_author_meta($sc) ): ?>
                                        <?php if ($sc == 'google-plus') $rel = 'publisher'; else $rel = 'alternate'; ?>
                                        <li><a href="<?php echo esc_url($url);?>" rel="<?php echo esc_attr($rel);?>" target="_blank"><i class="fa fa-<?php echo esc_attr($sc);?>"></i></a></li>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="clearfix"></div>
                            </div><!-- .author-social -->

                        </div><!-- .authorbox-info -->

                        <?php /* ------- SAME AUTHOR -------- */ ?>

                        <div class="authorbox-tab" id="same-author">

                            <?php
                            $args = array(
                                'posts_per_page'    => 4,
                                'author'            => get_the_author_meta( 'ID' ),
                                'no_found_rows'     => true, // no need for pagination
                            );
                            $same_author = get_posts( $args );
                            if ( $same_author ): $count = 0;?>
                                <div class="same-author-posts">

                                    <ul class="same-author-list">
                                        <?php foreach ( $same_author as $post ): setup_postdata($post);?>
                                        <li>
                                            <a href="<?php the_permalink();?>"><?php the_title();?></a>
                                        </li>
                                        <?php endforeach; wp_reset_postdata(); ?>
                                    </ul><!-- .related-list -->
                                    <div class="clearfix"></div>
                                    
                                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>" rel="author" class="viewall">
                                        <span><?php _e('View all','wi');?></span>
                                    </a>
                                    
                                </div><!-- .same-author-posts -->	
                                <?php	
                            endif; // if same author
                            ?>

                        </div><!-- #same-author -->
                    </div><!-- .authorbox-content -->
                    </div><!-- .authorbox-inner -->
                </div><!-- #authorbox -->

            <?php endif;	// single author box ?>

            <?php if( !get_theme_mod('fox_disable_single_comment')): ?>

            <?php if ( ! wi_autoload() ) wi_comment(); else wi_comment_hidden(); ?>

            <?php endif; ?>
            
            </div><!-- .theiaStickySidebar -->

        </main><!-- .content-area -->
        
        <div class="clearfix"></div>
        
    </div><!-- .content -->
</div><!-- .container -->

<?php wi_autoload_post_navigation(); ?>

<?php
// End the loop.
endwhile;
?>