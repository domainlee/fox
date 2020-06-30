<?php
if ( ! function_exists( 'fox_word' ) ) :
/**
 * @since 4.0
 * Since 4.0, we replace "gettext" filter by fox_word function
 * to get a better experience and consitent code
 */
function fox_word( $id = '' ) {
    
    $strings = fox_quick_translation_support();
    
    if ( ! isset( $strings[ $id ] ) ) return;
    
    $translation = get_theme_mod( 'wi_translate' );
    if ( ! $translation ) return $get = $strings[ $id ];;
    
    try {
        $translation = json_decode( $translation, true );
    } catch ( Exception $err ) {
        $translation = [];
    }
    
    $translation = ( array ) $translation;
    
    $get = isset( $translation[ $id ] ) ? $translation[ $id ] : '';
    
    if ( ! $get ) {
        $get = $strings[ $id ];
    }
    
    return $get;
    
}
endif;

/**
 * Builder Section Info
 * @since 4.4
 */
function fox_builder_section_info() {
    
    /**
     * problem 1: max_sections
     */
    $max_sections = get_theme_mod( 'wi_max_sections', '' );
    // it means it hasn't been changed yet
    if ( '' == $max_sections ) {
        $max = 6;
        for ( $i = 7 ; $i <= 10; $i++ ) {
            $display = get_theme_mod( 'bf_' . $i . '_cat' );
            if ( $display && 'none' != $display ) {
                $max = $i;
            }
        }
    } else {
        $max = absint( $max_sections );
    }
    if ( $max < 2 || $max > 40 ) $max = 10;
    $max_sections = $max;
    
    /**
     * problem 2: sections_order
     */
    $sections_order = get_theme_mod( 'wi_sections_order', '' );
    $sections_order = explode( ',', $sections_order );
    $sections_order = array_map( 'trim', $sections_order );
    
    $main_last = false;
    if ( count( $sections_order ) && 'main' == $sections_order[ count( $sections_order ) - 1 ] ) {
        $main_last = true;
    }
    
    $sections_order_without_main = [];
    $has_main = false;
    $valid = true;
    $prev_item = 0;
    $main_after = 0;
    foreach ( $sections_order as $item ) {
        if ( $item != 'main' ) {
            
            if ( ! is_numeric( $item ) ) {
                $valid = false;
            }
            $item = absint( $item );
            if ( $item > $max_sections ) continue;
            
            $sections_order_without_main[] = $item;
            $prev_item = $item;
        } else {
            $has_main = true;
            $main_after = $prev_item;
        }
    }
    
    $max = max( $sections_order_without_main );
    // in case we have not enough
    if ( $max < $max_sections ) {
        for ( $i = $max+1; $i <= $max_sections; $i++ ) {
            $sections_order_without_main[] = $i;
            $sections_order[] = $i;
        }
    }
    if ( $main_last ) {
        $main_after = 1000;
        $sections_order = $sections_order_without_main;
        $sections_order[] = 'main';
    }
    
    if ( ! $has_main ) {
        $valid = false;
    }
    
    /**
     * problem 3: main_stream_order fallback
     * deprecated since 4.4
     *
    $main_stream_order = get_theme_mod( 'wi_main_stream_order', '' );
    */
    
    /**
     * if $valid = false for any reason, reset it to default
     */
    if ( ! $valid ) {
        
        $default_sections_order = range( 1, $max_sections );
        $default_sections_order_without_main = $default_sections_order;
        $default_sections_order[] = 'main';
        $main_after = 1000;
        
        return [
            'valid' => false,
            'max_sections' => $max_sections,
            'sections_order' => $default_sections_order,
            'sections_order_without_main' => $default_sections_order_without_main,
            'main_after' => $main_after,
        ];
        
    }
    
    return [
        'valid' => true,
        'max_sections' => $max_sections,
        'sections_order' => $sections_order,
        'sections_order_without_main' => $sections_order_without_main,
        'main_after' => $main_after,
    ];
    
}

/**
 * check if we're in demo version
 * @since 4.4
 */
function fox_is_demo() {
    
    return defined( 'FOX_DEMO_URL' );
    
}

/**
 * Return array of visibility classes
 * @since 4.4
 */
function fox_visibility_class( $visibility ) {
    
    if ( ! is_array( $visibility ) ) {
        $visibility = explode( ',', $visibility );
    }
    
    $visibility = array_map( 'trim', $visibility );
    
    $class = [];
    
    if ( ! in_array( 'desktop', $visibility ) ) {
        $class[] = 'hide_on_desktop';
    } else {
        $class[] = 'show_on_desktop';
    }
    
    if ( in_array( 'tablet', $visibility ) ) {
        $class[] = 'show_on_tablet';
    } else {
        $class[] = 'hide_on_tablet';
    }
    
    if ( in_array( 'mobile', $visibility ) ) {
        $class[] = 'show_on_mobile';
    } else {
        $class[] = 'hide_on_mobile';
    }
    
    return $class;
    
}