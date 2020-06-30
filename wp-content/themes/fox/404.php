<?php get_header(); ?>
<?php get_template_part( 'parts/titlebar' ); ?>

<div class="container">
    
    <div class="page-404-content">
    
        <?php $msg = get_theme_mod( 'wi_page_404_message', 'It seems we can\'t find what you\'re looking for.' ); ?>
        <?php if ( $msg ) { ?>

        <div class="notfound-text">

            <p><?php echo $msg; ?></p>

        </div><!-- .notfound-text -->

        <?php } ?>

        <?php if ( 'true' == get_theme_mod( 'wi_page_404_searchform', 'true' ) ) { ?>

        <?php get_search_form(); ?>

        <?php } ?>
        
    </div><!-- .page-404-content -->
        
</div><!-- .container -->

<?php get_footer(); ?>
