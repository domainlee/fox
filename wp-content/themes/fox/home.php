<?php get_header(); ?>
<?php get_template_part( 'parts/titlebar' ); ?>

<div class="wi-content content">
    
    <?php
    $class = [
        'all-sections', 'wi-homepage-builder',
    ];
    // since 4.3
    $section_spacing = get_theme_mod( 'wi_section_spacing', 'small' );
    $class[] = 'sections-spacing-' . $section_spacing;

    ?>
    <div id="wi-bf" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php include_once get_parent_theme_file_path( 'parts/home-builder.php' ); ?>

        <div class="clearfix"></div>

    </div><!-- #wi-bf -->

</div><!-- .content -->

<?php get_footer(); ?>
