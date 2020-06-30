<?php
/* -------------------------------------------------------------------- */
/* FONT PROBLEM
 *
 * @since 1.0
 * @modified in 2.3
 *
/* -------------------------------------------------------------------- */
if ( ! function_exists( 'fox_fonts' ) ) :
/**
 * return font url for loading in stylesheet
 * we just care about Google Fonts to load them correctly
 * @since 4.0
 */
function fox_fonts() {
    
    $fonts_url = '';
	$fonts     = array();
    $subsets = trim( get_theme_mod( 'wi_font_subsets' ) );
    
    $primary_positions = fox_primary_font_support(); // body, heading, nav
    $primary_array = [];
    foreach ( $primary_positions as $key => $value ) {
        $primary_array[] = 'font_' . $key;
    }
    $all_positions = fox_all_font_support(); // all positions
    
    $google_fonts = fox_google_fonts();
    
    // this will be array face => styles
    $final_font_array = [];
    
    foreach ( $all_positions as $id => $fontdata ) {
        
        // if fontdata std not set, it means this is merely a typo option, don't need to load
        if ( ! isset( $fontdata[ 'std' ] ) ) continue;
        
        // for image logo, don't load extra font
        if ( 'logo' == $id ) {
            if ( 'text' != get_theme_mod( 'wi_logo_type', 'text' ) ) continue;
        }
        
        // if font source is local font, don't care
        $source = get_theme_mod( "wi_{$id}_font_source", 'standard' );
        if  ( 'local' == $source ) continue;
        
        // get value
        $value = trim( get_theme_mod( 'wi_' . $id . '_font', $fontdata[ 'std' ] ) );
        
        // empty value
        if ( ! $value ) continue;
        
        // if this is just primary value, don't care
        if ( in_array( $value, $primary_array ) ) continue;
        
        // now start analyze it,
        // value is like: Open Sans:400, 400i
        $explode = explode( ':', $value );
        $face = $explode[0];
        $styles = isset( $explode[1] ) ? $explode[1] : '';
        
        $face = str_replace('+', ' ', $face );
        $face = preg_replace( '/\s/', ' ', $face ); // replace all white spaces by simple white space
        $face = ucwords( $face ); // open sans ==> Open Sans
        
        // if this is not in Google font array, don't care
        if ( ! isset ( $google_fonts[ $face ] ) ) continue;
        
        // now it passes
        if ( ! isset( $final_font_array[ $face ] ) ) {
            $final_font_array[ $face ] = [];
        }
        
        // now, the styles
        $available_styles = $google_fonts[ $face ][ 'styles' ];
        $styles = trim( $styles );
        
        if ( ! $styles ) {
            $styles = '400'; // just regular
        }
        $styles = explode( ',', $styles );
        foreach ( $styles as $i => $style ) {
            $styles[ $i ] = trim( strtolower( $style ) );
        }
        
        foreach ( $available_styles as $style ) {
            
            // $style is regular then n_style is 400
            // $n_style will be the one added to the final font array
            $n_style = $style;
            if ( 'regular' == $style ) {
                $n_style = '400';
            } elseif ( 'italic' == $style ) {
                $n_style = '400italic';
            }
            
            // if we have this style
            if ( in_array( $n_style, $styles ) || in_array( $style, $styles ) ) {
                
                // but it's not in the final array yet, then add it
                if ( ! in_array( $n_style, $final_font_array[ $face ] ) ) {
                    
                    $final_font_array[ $face ][] = $n_style;
                    
                }
            }
            
        }
    
    }
    
    // NOW COMBINE THEM ALL
    $font_strs = [];
    foreach ( $final_font_array as $face => $styles ) {
        $weights = join( ',', $styles );
        $font_strs[] = "$face:$weights";
    }
    
    if ( ! empty( $font_strs ) ) {
        
        $query_args = [
            'family' => urlencode( implode( '|', $font_strs ) )
        ];
        if ( ! empty( $subsets ) ) {
            $query_args[ 'subset' ] = urlencode( $subsets );
        }
    
        $fonts_url = add_query_arg( $query_args , 'https://fonts.googleapis.com/css' );
        return esc_url_raw( $fonts_url );
        
    }
    
    return $fonts_url;
    
}
endif;

if ( ! function_exists('fox_font_size_array') ) :
/**
 * Font Size Mechanism
 * Each element has its own "reduce factor" to have best look on devices
 *
 * @since 2.0
 * @improved since 4.0
 */
function fox_font_size_array() {
    
    $size_arr = array();
    
    $size_arr['body'] = array(
        'name'      => 'Body text font size',
        'selector'      =>  'body',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .875,
        'iphone2'   =>  .875,
    );
    
    $size_arr['logo'] = array(
        'name'      => 'Logo text font size',
        'selector'      =>  '.fox-logo',
        'std'       =>  120,
        'ipad1'     =>  .6,
        'ipad2'     =>  .4,
        'iphone1'   =>  .4,
        'iphone2'   =>  .3,
    );
    
    $size_arr['nav'] = array(
        'name'      => 'Navigation item font size',
        'selector'      =>  '#wi-mainnav .menu > ul > li > a, .header-social ul li a, .offcanvas-nav .menu > ul > li > a',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  .75,
        'iphone1'   =>  .75,
        'iphone2'   =>  .75,
    );
    
    $size_arr['nav-sub'] = array(
        'name'      => 'Navigation dropdown font size',
        'selector'      =>  '#wi-mainnav .menu > ul > li > ul > li > a',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  1,
    );
    
    $size_arr['section-heading'] = array(
        'name'      => 'Section heading font size',
        'selector'      =>  '.section-heading',
        'std'       =>  80,
        'ipad1'     =>  1,
        'ipad2'     =>  .7,
        'iphone1'   =>  .5,
        'iphone2'   =>  .325,
    );
    
    $size_arr['slider-title'] = array(
        'name'      => 'Slider title font size',
        'selector'      =>  '.slider-title',
        'std'       =>  60,
        'ipad1'     =>  1,
        'ipad2'     =>  .8,
        'iphone1'   =>  .6,
        'iphone2'   =>  .5,
    );
    
    $size_arr['big-title'] = array(
        'name'      => 'Post big font size',
        'selector'      =>  '.big-title',
        'std'       =>  16,
        'ipad1'     =>  1,
        'ipad2'     =>  .8,
        'iphone1'   =>  .5,
        'iphone2'   =>  .4,
    );
    
    $size_arr['post-title'] = array(
        'name'      => 'Standard post title font size',
        'selector'      =>  '.post-title',
        'std'       =>  52,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .46,
    );
    
    $size_arr['grid-title'] = array(
        'name'      => 'Post grid font size',
        'selector'      =>  '.grid-title',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .92,
    );
    
    $size_arr['masonry-title'] = array(
        'name'      => 'Post masonry font size',
        'selector'      =>  '.masonry-title',
        'std'       =>  32,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .75,
    );
    
    $size_arr['newspaper-title'] = array(
        'name'      => 'Post newspaper style font size',
        'selector'      =>  '.newspaper-title',
        'std'       =>  36,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  .666,
    );
    
    $size_arr['list-title'] = array(
        'name'      => 'Post list style font size',
        'selector'      =>  '.list-title',
        'std'       =>  36,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .611,
        'iphone2'   =>  .611,
    );
    
    $size_arr['page-title'] = array(
        'name'      => 'Page title font size',
        'selector'      =>  '.page-title',
        'std'       =>  70,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .6,
    );
    
    $size_arr['archive-title'] = array(
        'name'      => 'Archive title font size',
        'selector'      =>  '.archive-title',
        'std'       =>  80,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .6,
        'iphone2'   =>  .4,
    );
    
    $size_arr['widget-title'] = array(
        'name'      => 'Widget title font size',
        'selector'      =>  '.widget-title',
        'std'       =>  12,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  1,
        'iphone2'   =>  1,
    );
    
    $size_arr['h1'] = array(
        'name'      => 'H1 size',
        'selector'      =>  'h1',
        'std'       =>  40,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h2'] = array(
        'name'      => 'H2 size',
        'selector'      =>  'h2',
        'std'       =>  32,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h3'] = array(
        'name'      => 'H3 size',
        'selector'      =>  'h3',
        'std'       =>  26,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h4'] = array(
        'name'      => 'H4 size',
        'selector'      =>  'h4',
        'std'       =>  22,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h5'] = array(
        'name'      => 'H5 size',
        'selector'      =>  'h5',
        'std'       =>  18,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    $size_arr['h6'] = array(
        'name'      => 'H6 size',
        'selector'      =>  'h6',
        'std'       =>  14,
        'ipad1'     =>  1,
        'ipad2'     =>  1,
        'iphone1'   =>  .7,
        'iphone2'   =>  .7,
    );
    
    // since 4.0
    $size_arr = apply_filters( 'fox_font_size_array', $size_arr );
    return $size_arr;
    
}
endif;