<?php
/**
 * All about post hero
 * @since 4.0
 */
if ( ! function_exists( 'fox_hero_type' ) ) :
/**
 * return 'full' / 'half' / false
 * @since 4.0
 */
function fox_hero_type() {
    
    $style = fox43_single_option( 'style' );
    
    if ( 4 == $style ) return 'full';
    elseif ( 5 == $style ) return 'half';
    else return false;
    
}
endif;
if ( ! function_exists( 'fox_is_hero' ) ) :
/**
 * Check if this post is a hero post
 * return @bool
 * @since 4.0
 */
function fox_is_hero() {
    
    return is_singular() && ( false !== fox_hero_type() );
    
}
endif;

/**
 * disable header for hero posts
 * @since 4.0
 */
add_filter( 'fox_show_header', 'fox_disable_header_hero', 10 );
function fox_disable_header_hero( $show ) {
    
    if ( fox_is_hero() ) return false;
    return $show;
        
}

/**
 * display a minimal header for hero posts
 * @since 4.0
 */
add_action( 'fox_wrapper', 'fox_display_hero_min_header', 10 );
function fox_display_hero_min_header() {
    
    if ( ! fox_is_hero() ) return;
    fox_min_header();
    
}

add_action( 'fox_after_body', 'fox_hero_content_init' );
function fox_hero_content_init() {
    
    if ( ! is_singular() ) return;
    $hero_type = fox_hero_type();
    
    if ( 'full' == $hero_type ) {
        fox_hero_full();
    } elseif ( 'half' == $hero_type ) {
        fox_hero_half();
    }
    
}

/**
 * scroll btn html
 * @since 4.3
 */
function fox_hero_scroll_btn() {
    
    if ( 'true' != get_theme_mod( 'wi_single_hero_scroll', 'false' ) ) {
        return;
    }
    
    ?>
    <a href="#" class="scroll-down-btn">
        <span><?php echo fox_word( 'start' ); ?></span>
        <i class="feather-chevron-down"></i>
    </a>
    <?php
}

if ( ! function_exists( 'fox_hero_full' ) ) :
/**
 * template function to display hero full area
 * @since 4.0
 */
function fox_hero_full() {
    
    $cl = [ 'hero-full hero-section single-big-section' ];
    
    $hero_full_text_layout = get_theme_mod( 'wi_single_hero_full_text_layout', 'bottom-left' );
    if ( ! in_array( $hero_full_text_layout, [ 'center', 'bottom-center' ] ) ) {
        $hero_full_text_layout = 'bottom-left';
    }
    $cl[] = 'hero-text--' . $hero_full_text_layout;

?>

<div id="masthead-mobile-height"></div>
<div id="hero" class="<?php echo esc_attr( join( ' ', $cl ) ); ?>">
        
    <div class="hero-background">

        <?php fox43_thumbnail([
            'show_thumbnail' => true,
            'thumbnail' => 'full',
            'thumbnail_extra_class' => 'hero-thumbnail hero-full-thumbnail',
            'link' => false,
            'thumbnail_format_indicator' => false,
            'thumbnail_view' => false,
            'thumbnail_index' => false,
            'thumbnail_review_score' => false,
            'thumbnail_hover' => 'none',
            'thumbnail_placeholder' => true,
            'thumbnail_caption' => true,
            'thumbnail_shape' => 'acute',
            'thumbnail_showing_effect' => 'none',
        ]); ?>

    </div><!-- .hero-background -->

    <div class="hero-content">

        <?php fox43_hero_header(); ?>

    </div><!-- .hero-content -->
    
    <div class="hero-overlay"></div>
    
    <?php fox_hero_scroll_btn(); ?>

</div><!-- #hero -->

<?php }
endif;

if ( ! function_exists( 'fox_hero_half' ) ) :
/**
 * template function to display hero full area
 * @since 4.0
 */
function fox_hero_half() {
    
    $class = [
        'hero-half',
        'hero-section',
        'single-big-section',
    ];
    
    ?>

<div id="masthead-mobile-height"></div>
<div id="hero" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
    <div class="hero-background">
        
        <?php fox43_thumbnail([
            'show_thumbnail' => true,
            'thumbnail' => 'full',
            'thumbnail_extra_class' => 'hero-thumbnail hero-half-thumbnail',
            'link' => false,
            'thumbnail_format_indicator' => false,
            'thumbnail_view' => false,
            'thumbnail_index' => false,
            'thumbnail_review_score' => false,
            'thumbnail_hover' => 'none',
            'thumbnail_placeholder' => true,
            'thumbnail_caption' => true,
            'thumbnail_shape' => 'acute',
            'thumbnail_showing_effect' => 'none',
        ]); ?>

    </div><!-- .hero-background -->

    <div class="hero-content">
        
        <?php fox43_hero_header(); ?>

        <?php fox_hero_scroll_btn(); ?>

    </div><!-- .hero-content -->

</div><!-- #hero -->

<?php }
endif;

/**
 * add post-hero class into body class
 * @since 4.0
 */
add_action( 'body_class', 'fox_add_hero_body_class' );
function fox_add_hero_body_class( $classes ) {
    
    if ( ! is_singular() ) return $classes;
    
    $type = fox_hero_type();
    
    if ( 'full' == $type ) {
        
        $classes[] = 'post-hero post-hero-full';
        
    } elseif ( 'half' == $type ) {
        
        $classes[] = 'post-hero post-hero-half';
        
        // since 4.3
        $skin = get_post_meta( get_the_ID(), '_wi_hero_half_skin', true );
        if ( ! $skin ) {
            $skin = get_theme_mod( 'wi_single_hero_half_skin', 'light' );
        }
        if ( 'dark' != $skin ) {
            $skin = 'light';
        }

        $classes[] = 'post-hero-half-' . $skin;
        
    } else {
        
        return $classes;
        
    }
    
    /**
     * narrow problem
     */
    $content_width = fox43_single_option( 'content_width', 'full' );
    if ( 'narrow' == $content_width ) {
        $classes[] = 'post-content-narrow';
    }
    
    return $classes;
    
}