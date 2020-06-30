<?php get_header();
    // Start the loop.
    if ( have_posts() ) :
?>

<div id="wi-content" class="wi-content">
    
    <div class="container">
        
        <div id="primary" class="primary content-area">
        
            <div class="theiaStickySidebar">
                
                <?php fox_shop_header(); ?>
                <?php woocommerce_content(); ?>
                
            </div><!-- .theiaStickySidebar -->
        
        </div><!-- #primary -->
        
        <?php fox_sidebar( 'shop' ); ?>
    
    </div><!-- .container -->
    
</div><!-- .wi-content -->
    
<?php
// End the loop.
endif;

get_footer();