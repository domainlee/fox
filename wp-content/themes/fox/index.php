<?php get_header(); ?>
<?php get_template_part( 'parts/titlebar' ); ?>
<?php get_template_part( 'parts/toparea' ); ?>

<?php

global $wp_query;

$layout = fox_layout();
$local_params = [ 'pagination' => true ];

$local_params[ 'skip_rendered' ] = ( 'true' == get_theme_mod( 'wi_top_area_non_duplicate', 'false' ) );
?>

<div class="wi-content">
    
    <div class="container">

        <div class="content-area primary" id="primary" role="main">

            <div class="theiaStickySidebar">

                <?php fox44_blog( $layout, $local_params, $wp_query ); ?>

            </div><!-- .theiaStickySidebar -->

        </div><!-- .content-area -->

        <?php fox_sidebar( 'sidebar' ); ?>

    </div><!-- .container -->
    
</div><!-- .wi-content -->

<?php get_footer(); ?>