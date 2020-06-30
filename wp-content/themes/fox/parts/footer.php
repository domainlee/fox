<?php
// since 4.0 - if you wanna disable header for some purpose
if ( ! apply_filters( 'fox_show_footer', true ) ) return; ?>

<footer id="wi-footer" class="site-footer" itemscope itemtype="https://schema.org/WPFooter">
    
    <?php get_template_part( 'parts/footer', 'sidebar' ); ?>
    <?php get_template_part( 'parts/footer', 'bottom' ); ?>

</footer><!-- #wi-footer -->