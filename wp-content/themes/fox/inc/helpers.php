<?php
/**
 * array fill fox style
 * @since 4.3
 */
function fox_array_fill( $arr, $value ) {
    
    $return = [];
    foreach ( $arr as $k => $v ) {
        if ( is_numeric( $k ) ) {
            $return[ $v ] = $value;
        } else {
            $return[ $k ] = $v;
        }
    }
    return $return;
    
}

/* python-style format
------------------------------------------ */
// thank you lord, https://stackoverflow.com/questions/16632067/php-equivalent-of-pythons-str-format-method
function fox_format( $msg , $vars ) {
    $vars = (array)$vars;

    $msg = preg_replace_callback('#\{\}#', function($r){
        static $i = 0;
        return '{'.($i++).'}';
    }, $msg);

    return str_replace(
        array_map(function($k) {
            return '{'.$k.'}';
        }, array_keys($vars)),

        array_values($vars),

        $msg
    );
}

/* error message
------------------------------------------ */
function fox_err( $msg ) {
    
    return fox_format( '<div class="fox-error">{}</div>', $msg );
    
}

/**
 * Helper functions
 */
if ( ! function_exists( 'fox_substr' ) ) :
/**
 * Substr base on char or word
 * @since 4.0
 */
function fox_substr( $str = '',$int = 0, $length = NULL, $base = 'word' ) {
    
    if ( 'char' != $base ) $base = 'word';
    
    // length == -1
    if ( $length < 0 ) return $str;
    
    if ( 'char' == $base ) return substr( $str, $int, $length );
    
    if ( !$str ) return '';
	$words = explode(" ",$str); if (!is_array($words)) return;
	$return = array_slice($words,$int,$length); if (!is_array($return)) return;
	return implode(" ",$return);
    
}
endif;

/**
 * turn 2555 --> 2.5k
 * // thank to https://stackoverflow.com/a/14531760/1346258
 */
function fox_number( $n, $precision = 1 ) {
    
    if ($n < 1000) {
        // Anything less than a million
        $n_format = number_format($n) + 0;
    } else if ($n < 1000000) {
        // Anything less than a billion
        $n_format = ( number_format($n / 1000, $precision) + 0 ) . 'K';
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = ( number_format($n / 1000000, $precision) + 0 ) . 'M';
    } else {
        // At least a billion
        $n_format = ( number_format($n / 1000000000, $precision) + 0 ) . 'B';
    }

    return $n_format;
}

/**
 * HTML allowed to use in copyright
 */
function fox_allowed_html() {

    $return = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'class' => array(),
            'onclick' => array(),
            'rel' => array(),
            'nofollow' => array(),
        ),
        'br' => array(),
        'em' => array(
            'class' => array(),
            'title' => array(),
        ),
        'strong' => array(
            'class' => array(),
            'title' => array(),
        ),
        'span' => array(
            'class' => array(),
            'title' => array(),
        ),
        'i' => array(
            'class' => array(),
            'title' => array(),
        ),
        'b' => array(
            'class' => array(),
            'title' => array(),
        ),
        'hr' => array(
            'class' => array(),
            'title' => array(),
        ),
        'ul' => array(
            'class' => array(),
            'title' => array(),
        ),
        'ol' => array(
            'class' => array(),
            'title' => array(),
        ),
        'li' => array(
            'class' => array(),
            'title' => array(),
        ),
        'img' => array(
            'src' => array(),
            'title' => array(),
            'class' => array(),
            'width' => array(),
            'height' => array(),
        ),
    );
    return apply_filters( 'fox_allowed_html', $return );
    
}

/**
 * since 4.0
 */
function fox_get_screen_from_text( $screen ) {
    
    if ( 'ipad1' == $screen ) return 1023;
    if ( 'ipad2' == $screen ) return 767;
    if ( 'iphone1' == $screen ) return 567;
    if ( 'iphone2' == $screen ) return 479;
    
    return 1919;
    
}

/**
 * since 4.0
 */
function fox_get_query_screen_string_from_text( $screen ) {
    
    return "@media only screen and (max-width: " . fox_get_screen_from_text( $screen ) . "px)";
    
}

if ( ! function_exists( 'fox_align_options' ) ):
/**
 * Just for laziness
 * @since 4.0
 */
function fox_align_options() {
    return array(
        ''          => 'Default',
        'left'      => 'Left',
        'center'    => 'Center',
        'right'    => 'Right',
    );
}
endif;

if ( ! function_exists( 'fox_border_style' ) ):
/**
 * Border Style
 * @since 4.0
 */
function fox_border_style() {
    return array(
        'none' => esc_html__( 'None', 'wi' ),
        'solid' => esc_html__( 'Solid', 'wi' ),
        'dotted' => esc_html__( 'Dotted', 'wi' ),
        'dashed' => esc_html__( 'Dashed', 'wi' ),
        'double' => esc_html__( 'Double', 'wi' ),
    );
}
endif;

if ( ! function_exists( 'fox_background_size' ) ):
/**
 * Background Size
 *
 * @since 4.0
 */
function fox_background_size() {
    return array(
        'cover' => esc_html__( 'Cover', 'wi' ),
        'contain' => esc_html__( 'Contain', 'wi' ),
        '100% auto' => esc_html__( '100% Width', 'wi' ),
        'auto 100%' => esc_html__( '100% Height', 'wi' ),
        'auto' => esc_html__( 'Auto', 'wi' ),
        'custom' => esc_html__( 'Custom', 'wi' ),
    );
}
endif;

if ( ! function_exists( 'fox_background_position' ) ):
/**
 * Background Position
 *
 * @since 4.0
 */
function fox_background_position() {
    return array(
        'left top' => esc_html__( 'Left Top', 'wi' ),
        'center top' => esc_html__( 'Center Top', 'wi' ),
        'right top' => esc_html__( 'Right Top', 'wi' ),
        
        'left center' => esc_html__( 'Left Middle', 'wi' ),
        'center center' => esc_html__( 'Center Middle', 'wi' ),
        'right center' => esc_html__( 'Right Middle', 'wi' ),
        
        'left bottom' => esc_html__( 'Left Bottom', 'wi' ),
        'center bottom' => esc_html__( 'Center Bottom', 'wi' ),
        'right bottom' => esc_html__( 'Right Bottom', 'wi' ),
    );
}
endif;

if ( ! function_exists( 'fox_background_repeat' ) ):
/**
 * Background Repeat
 *
 * @since 4.0
 */
function fox_background_repeat() {
    return array(
        'no-repeat' => esc_html__( 'No Repeat', 'wi' ),
        'repeat' => esc_html__( 'Repeat', 'wi' ),
        'repeat-x' => esc_html__( 'Repeat X', 'wi' ),
        'repeat-y' => esc_html__( 'Repeat Y', 'wi' ),
    );
}
endif;

if ( ! function_exists( 'fox_background_attachment' ) ):
/**
 * Background Attachment
 *
 * @since 4.0
 */
function fox_background_attachment() {
    return array(
        'scroll' => esc_html__( 'Scroll', 'wi' ),
        'fixed' => esc_html__( 'Fixed', 'wi' ),
    );
}
endif;

if ( ! function_exists( 'fox_loading_element' ) ) :
/**
 * Loading Element
 * @since 4.0
 */
function fox_loading_element() {

    return '<span class="fox-loading-element"><i class="fa fa-spinner fa-spin"></i></span>';

}
endif;

function fox_helper_corona_sort_by_cases( $a, $b ) {
            
    $return = $b->cases - $a->cases;
    if ( ! $return ) {
        return 0;
    } else {
        return $return / absint( $return );
    }
    
}