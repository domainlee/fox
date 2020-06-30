<aside id="secondary" class="secondary" role="complementary" itemscope itemptype="https://schema.org/WPSideBar">
    
    <div class="theiaStickySidebar">

        <div class="widget-area">
            
            <?php if ( is_active_sidebar( 'page-sidebar' ) ) { dynamic_sidebar( 'page-sidebar' ); } else { ?>
            
            <p class="fox-error">Please go to your <a href="<?php echo get_admin_url( '','widgets.php' ); ?>">Dashboard > Appearance > Widgets</a> to drop your widgets into the sidebar.</p>
            
            <?php } ?>
            
            <div class="gutter-sidebar"></div>
            
        </div><!-- .widget-area -->
        
    </div><!-- .theiaStickySidebar -->

</aside><!-- #secondary -->