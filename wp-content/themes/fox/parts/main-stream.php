<?php
if ( 'false' == get_theme_mod( 'wi_main_stream', 'true' ) ) return;

global $wp_query;

$layout = get_theme_mod( 'wi_home_layout', 'list' );
$local_params = [
    'pagination' => true,
];

/**
 * options must have in archive
 */
$params[ 'pagination' ] = true;

/* ------------------------ SECTION LAYOUT/DESIGN ------------------------ */
$prefix = 'main_stream_';

/**
 * Class
 */
$section_class = array(
    'wi-section', 
    'fox-section',
    'section-main',
    'main-stream'
);

$section_class[] = 'section-layout-' . $layout;
// $section_class[] = 'section-loop-' . $loop;
$section_id = 'main-stream';
$section_css = [];

/**
 * Heading
 */
$heading = trim( get_theme_mod( "{$prefix}heading" ) );
if ( '' != $heading ) {
    $section_class[] = 'section-has-heading';
}

$sidebar_state = get_theme_mod( "wi_home_sidebar_state", 'right' );
if ( 'right' == $sidebar_state || 'left' == $sidebar_state ) {
    
    $section_class[] = 'section-has-sidebar';
    $section_class[] = 'section-sidebar-' . $sidebar_state;
    
} else {
    
    $section_class[] = 'section-fullwidth';
    
}

/**
 * CSS
 */
$background = get_theme_mod( "{$prefix}background" );
if ( $background ) {
    $section_css[] = 'background-color:' . $background;
    $section_class[] = 'has-background';
}
$text_color = get_theme_mod( "{$prefix}text_color" );
if ( $text_color ) {
    $section_css[] = 'color:' . $text_color;
    $section_class[] = 'custom-color';
}

/**
 * stretch
 */
$stretch = get_theme_mod( "{$prefix}stretch" );
if ( 'full' == $stretch ) {
    $section_class[] = 'section-stretch-full';   
} else {
    $section_class[] = 'section-stretch-content';
}

/**
 * Border
 */
$border = get_theme_mod( "{$prefix}border" );
if ( $border ) {
    $section_class[] = 'has-border';
    $section_class[] = 'section-border-' . $border;
}

/**
 * section CSS
 * @since 4.3
 */
$section_css = join( ';', $section_css );
if ( ! empty( $section_css ) ) {
    $section_css = ' style="' . esc_attr( $section_css ) . '"';
}

/**
 * Heading Params
 */
$heading_params = [
    'heading' => $heading,
    
    'url' => get_theme_mod( "{$prefix}viewall_link" ),
    'target' => '_self',
    
    'color' => get_theme_mod( "{$prefix}heading_color" ),
];

$heading_props = [
    'align' => 'center', 
    'style' => '1a',
    'line_stretch' => 'content',
    'size' => 'large', 
];
foreach ( $heading_props as $prop => $std ) {
    $get = get_theme_mod( "{$prefix}heading_{$prop}", '' );
    if ( ! $get ) {
        $get = get_theme_mod( "wi_builder_heading_{$prop}", $std );
    }
    $heading_params[ $prop ] = $get;
}

?>

<div class="<?php echo esc_attr( join( ' ', $section_class ) );?>" id="<?php echo esc_attr( $section_id ); ?>"<?php echo $section_css; ?>>
    
    <?php 
    
    $ad_visibility = get_theme_mod( "{$prefix}ad_visibility", 'desktop,tablet,mobile' );
    $visibility_class = fox_visibility_class( $ad_visibility );
    
    fox_ad([
            'code' => get_theme_mod( "{$prefix}ad_code" ),
            'image' => get_theme_mod( "{$prefix}banner" ),
            'width' => get_theme_mod( "{$prefix}banner_width" ),
    
            'tablet' => get_theme_mod( "{$prefix}banner_tablet" ),
            'tablet_width' => get_theme_mod( "{$prefix}banner_tablet_width" ),
    
            'phone' => get_theme_mod( "{$prefix}banner_mobile" ),
            'phone_width' => get_theme_mod( "{$prefix}banner_mobile_width" ),
            'url' => get_theme_mod( "{$prefix}banner_url" ),
            'extra_class' => 'section-ad ' . join( ' ', $visibility_class ),
        ]); ?>
    
    <?php fox_section_heading( $heading_params ); ?>
    
    <div class="container">
        
        <div class="section-container">
            
            <div class="section-inner">
                
                <div class="content-area primary" id="primary" role="main">

                    <div class="theiaStickySidebar">

                        <?php fox44_blog( $layout, $local_params, $wp_query ); ?>

                    </div><!-- .theiaStickySidebar -->

                </div><!-- .content-area -->

                <?php fox_sidebar(); ?>
                
                <?php if ( 'true' == get_theme_mod( $prefix . 'sidebar_sep' ) ) {
                    $section_sep_css = '';
                    $section_sep_color = get_theme_mod( $prefix . 'sidebar_sep_color' );
                    if ( $section_sep_color ) {
                        $section_sep_css = ' style="color:' . esc_attr( $section_sep_color ) . '"';
                    }
                ?>

                <div class="section-sep"<?php echo $section_sep_css; ?>></div>

                <?php } ?>

                <div class="clearfix"></div>
                
            </div><!-- .section-inner -->
            
        </div><!-- .section-container -->
        
    </div><!-- .container -->
    
</div><!-- .fox-section -->