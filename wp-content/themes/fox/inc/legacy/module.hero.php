<?php
// add_filter( 'fox_show_header', 'fox_disable_normal_header_hero_post' );
/**
 * disable header in hero post
 * @since 4.0
 */
function fox_disable_normal_header_hero_post( $show ) {
    if ( is_singular() && wi_hero() ) $show = false;
    
    return $show;
}

// add_action( 'fox_before_wrapper', 'wi_single_hero_header' );
if ( ! function_exists( 'wi_single_hero_header' ) ) :
/**
 * Adds big hero header to single posts
 *
 * @since 3.0
 */
function wi_single_hero_header() {
    
    if ( ! is_single() ) return;
    
    $hero = wi_hero();
    if ( ! $hero ) return;
    
    $bg = '';
    if ( has_post_thumbnail() ) {
        $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
        $bg = ' style="background-image:url(' . esc_url( $image_attributes[0] ) . ')"';
    }
    ?>

<?php if ( 'full' == $hero ) : ?>

<div id="hero" class="hero-full">
    
    <div class="hero-background">
        
        <div class="hero-height"></div>
        <div class="hero-bg"<?php echo $bg?>></div>
        <div class="hero-overlay"></div>
        
    </div><!-- .hero-background -->
    
    <div class="hero-content">
    
        <div class="hero-meta">
            <?php wi_entry_categories(); ?>
            <?php echo wi_entry_date(); ?>
            <?php wi_entry_author(); ?>
        </div><!-- .hero-meta -->
        
        <h1 class="hero-headline"><?php the_title(); ?></h1>
        
        <div class="hero-excerpt">
            <?php the_excerpt(); ?>
        </div>
        
    </div><!-- .hero-content -->

</div><!-- #hero -->

<?php else : ?>

<div id="hero" class="hero-half">
    
    <div class="hero-background">
        
        <div class="hero-height"></div>
        <div class="hero-bg"<?php echo $bg?>></div>
        <div class="hero-overlay"></div>
        
    </div>
    
    <div class="hero-content">
        
        <div class="hero-content-inner">
    
            <div class="hero-meta">
                <?php wi_entry_categories(); ?>
                <?php echo wi_entry_date(); ?>
            </div><!-- .hero-meta -->

            <h1 class="hero-headline"><?php the_title(); ?></h1>

            <div class="hero-excerpt">
                <?php the_excerpt(); ?>
            </div>
            
        </div><!-- .hero-content-inner -->
        
    </div><!-- .hero-content -->

</div><!-- #hero -->

<?php endif; ?>

    <?php
    
}
endif;

// add_action( 'fox_wrapper', 'wi_hero_header' );
function wi_hero_header() {
    
    if ( ! is_singular() ) return;
    $hero = wi_hero();
    if ( ! $hero ) return;
    
    fox_min_header();
    
}