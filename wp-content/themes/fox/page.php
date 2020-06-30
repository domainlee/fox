<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php
$style = fox43_page_option( 'style', '1' );
$params = [
    'style' => $style,
    
    'sidebar_state' => fox43_page_option( 'sidebar_state', 'right' ),
    'thumbnail_stretch' => fox43_page_option( 'thumbnail_stretch', 'stretch-none' ),
    'content_width' => fox43_page_option( 'content_width', 'full' ),
    'image_stretch' => fox43_page_option( 'content_image_stretch', 'stretch-none' ),
    'column_layout' => fox43_page_option( 'column_layout', 1 ),
    
    // it's a factor
    'body_layout' => get_theme_mod( 'wi_body_layout', 'wide' ),
    
    'dropcap' => ( 'true' == fox43_page_option( 'dropcap', 'false' ) ),
    'text_column' => fox43_page_option( 'column_layout', 1 ),
    
    'share_show' => ( 'true' == fox43_page_option( 'share', 'false' ) ),
    'comment_show' => ( 'false' != get_theme_mod( 'wi_page_comment', 'true' ) ),
    
    'title_align' => get_post_meta( get_the_ID(), '_wi_title_align', true ),
];

/**
 * post class based on params
 */
$cl = [
    'wi-content',
    'wi-page',
];

$cl[] = 'single-style-' . $params['style'];

// hero posts
if ( 4 == $params['style'] || 5 == $params['style'] ) {
    $cl[] = 'single-style-hero';
}

/**
 * page header
 */
$params[ 'post_header_show' ] = ( 'false' != get_post_meta( get_the_ID(), '_wi_post_header', true ) );

/**
 * thumbnail
 */
$params[ 'thumbnail_show' ] = ( 'false' != get_post_meta( get_the_ID(), '_wi_thumbnail', true ) );

/**
 * padding top problem
 * padding top should be zero, when: 
        layout 3, has thumbnail, thumbnail stretch full, show thumbnai, has thumbnail, not a post format :))
        layout 1, has thumbnail, thumbnail stretch full, no sidebar
 */
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
    $cl[] = 'no-padding-top';
}

?>

<?php switch( $style ) :

/* --------------------         LAYOUT 1            -------------------- */
case '1':
?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_single_thumbnail( $params ); ?>
                    <?php fox43_page_header( $params ); ?>
                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 1B            -------------------- */
case '1b':
?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_page_header( $params ); ?>
                    <?php fox43_single_thumbnail( $params ); ?>
                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 2            -------------------- */ ?>
<?php case '2' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php fox43_page_header( $params ); ?>
    
    <?php fox43_single_thumbnail( $params ); ?>
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section-content -->
    
    
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 3            -------------------- */ ?>
<?php case '3' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <?php fox43_single_thumbnail( $params ); ?>
    
    <div class="single-big-section single-big-section-content">
    
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_page_header( $params ); ?>
                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div>
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 4            -------------------- */ ?>
<?php case '4' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="single-big-section single-big-section-content">
    
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
</article><!-- .post -->

<?php break; ?>

<?php /* --------------------         LAYOUT 5            -------------------- */ ?>
<?php case '5' : ?>

<article id="wi-content" <?php post_class( $cl ); ?> itemscope itemtype="https://schema.org/CreativeWork">
    
    <div class="single-big-section single-big-section-content">
        
        <div class="container">

            <div id="primary" class="primary content-area">

                <div class="theiaStickySidebar">

                    <?php fox43_page_body( $params ); ?>

                </div><!-- .theiaStickySidebar -->

            </div><!-- #primary -->

            <?php fox_sidebar( 'page' ); ?>

        </div><!-- .container -->
        
    </div><!-- .single-big-section -->
    
</article><!-- .post -->

<?php break; ?>

<?php default : break; ?>

<?php endswitch; ?>

<?php endwhile; // End the loop. ?>

<?php get_footer();