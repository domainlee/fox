<?php
/**
 * action: fox_single_top
 * @since 4.3
 * 20 - fox_single_top_ad
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php
$style = fox43_single_option( 'style', '1' );
$params = [
    'style' => $style,
    
    'sidebar_state' => fox43_single_option( 'sidebar_state', 'right' ),
    'thumbnail_stretch' => fox43_single_option( 'thumbnail_stretch', 'stretch-none' ),
    'content_width' => fox43_single_option( 'content_width', 'full' ),
    'image_stretch' => fox43_single_option( 'content_image_stretch', 'stretch-none' ),
    'column_layout' => fox43_single_option( 'column_layout', 1 ),
    
    'header_align' => get_theme_mod( 'wi_single_meta_align', 'center' ),
    'header_item_template' => get_theme_mod( 'wi_single_meta_template', '1' ),
    
    // autoload problem
    'autoload' => fox_autoload(),
    
    // it's a factor
    'body_layout' => get_theme_mod( 'wi_body_layout', 'wide' ),
    
    'dropcap' => ( 'true' == fox43_single_option( 'dropcap', 'false' ) ),
    'text_column' => fox43_single_option( 'column_layout', 1 ),
];

$std = [
    'date', 
    'category',
    'post_header',
    'thumbnail',
    'share',
    'tag',
    'related',
    'authorbox',
    'comment',
    'nav',
    'bottom_posts',
    'side_dock',
];
$std = join( ',', $std );

$single_components = get_theme_mod( 'wi_single_components', $std );
$single_components = explode( ',', $single_components );
$single_components = array_map( 'trim', $single_components );

$possible_components = [
    'date', 
    'category',
    'author',
    'author_avatar',
    'comment_link',
    'reading_time',
    'view',
    
    'post_header',
    'thumbnail',
    'share',
    'tag',
    'related',
    'authorbox',
    'comment',
    'nav',
    'bottom_posts',
    'side_dock',
    'show_header',
    'show_footer',
];

$single_possible_options = [
    'post_header',
    'thumbnail',
    'share',
    'tag',
    'related',
    'authorbox',
    'comment',
    'nav',
    'bottom_posts',
    'side_dock',
    'show_header',
    'show_footer',
];

foreach ( $possible_components as $com ) {
    
    $get = '';
    if ( in_array( $com, $single_possible_options ) ) {
        $get = get_post_meta( get_the_ID(), '_wi_' . $com, true );
    }
    
    if ( 'true' == $get ) {
        $value = true;
    } elseif ( 'false' == $get ) {
        $value = false;
    } else {
        $value = in_array( $com, $single_components );
    }
    
    $params[ $com . '_show' ] = $value;
    
}

/**
 * post class based on params
 */
$cl = [
    'wi-content',
    'wi-single',
];
$cl[] = 'single-style-' . $params['style'];

// hero posts
if ( 4 == $params['style'] || 5 == $params['style'] ) {
    $cl[] = 'single-style-hero';
    
}

/**
 * padding top problem
 * set the padding appropriately base on layout
 * padding top & bottom
 * $padding_top, $bottom has 2 possible values: normal and zero
 * 
 * padding top should be zero, when:
        layout 3, has thumbnail, thumbnail stretch full, show thumbnai, has thumbnail, not a post format :))
        layout 1, has thumbnail, thumbnail stretch full, no sidebar
 */
$padding_top = '';
$padding_bottom = '';

$padding_top_zero = false;
if ( 'stretch-full' == $params[ 'thumbnail_stretch' ]
    && $params[ 'thumbnail_show'] 
    && has_post_thumbnail()
    && ! get_post_format() ) {
    
    if (
        '3' == $style ||
        ( '1' == $style && 'no-sidebar' == $params[ 'sidebar_state' ] ) ) {
        
        $padding_top_zero = true;
    }
}

if ( 4 == $style || 5 == $style ) {
    $padding_top_zero = true;
}

if ( $padding_top_zero ) {
    $cl[] = 'padding-top-zero';
} else {
    $cl[] = 'padding-top-normal';
}

?>

<?php switch( $style ) :

/* --------------------         LAYOUT 1            -------------------- */
case '1':
?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_thumbnail( $params ); ?>
                    <?php fox43_single_header( $params ); ?>
                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div>
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 1B            -------------------- */
case '1b':
?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_header( $params ); ?>
                    <?php fox43_single_thumbnail( $params ); ?>
                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 2            -------------------- */ ?>
<?php case '2' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <?php fox43_single_header( $params ); ?>
    <?php fox43_single_thumbnail( $params ); ?>
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section-content -->
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 3            -------------------- */ ?>
<?php case '3' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <?php fox43_single_thumbnail( $params ); ?>
    
    <div class="single-big-section single-big-section-content">
    
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_header( $params ); ?>
                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div>
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 4            -------------------- */ ?>
<?php case '4' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <div class="single-big-section single-big-section-content">
    
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 5            -------------------- */ ?>
<?php case '5' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php do_action( 'fox_single_top', $params ); // since 4.3 ?>
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar(); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
    <?php do_action( 'fox_single_bottom', $params ); ?>
    
</article><!-- .post -->

<?php break; ?>

<?php default : break; ?>

<?php endswitch; ?>

<?php endwhile; // End the loop. ?>

<?php get_footer();