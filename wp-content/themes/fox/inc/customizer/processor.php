<?php
/**
 * Processor for options from raw data
 *
 * @since 1.0
 */

// Prefix
// General prefix fox_
$prefix = self::$prefix;

// final return
$final = array();

foreach ( $options as $id => $option ) {
    
    // Check shorthand
    require get_template_directory() . '/inc/customizer/shorthand.php';
    
    // type is mandatory
    if ( ! isset( $option['type'] ) )
        continue;
    
    // prefix panel
    if ( isset( $option[ 'panel'] ) ) {
        if ( ! isset( $option[ 'panel_prefix'] ) || false !== $option[ 'panel_prefix'] )
            $option[ 'panel'] = $prefix . $option[ 'panel'];
    }
    
    // prefix section
    if ( isset( $option[ 'section'] ) ) {
        if ( ! isset( $option[ 'section_prefix'] ) || false !== $option[ 'section_prefix'] )
            $option[ 'section'] = $prefix . $option[ 'section'];
    }
    
    // prefix id
    if ( ! isset( $option[ 'prefix'] ) || false !== $option[ 'prefix'] || is_numeric( $id ) )
        $option_id = $prefix . $id;
    else $option_id = $id;
    
    // remove the id to prevent future troubles
    if ( isset( $option[ 'id' ] ) ) unset( $option[ 'id' ] );
    
    // toggle
    if ( isset( $option[ 'toggle' ] ) ) {
        $option[ 'toggle' ] = ( array ) $option[ 'toggle' ];
        foreach ( $option[ 'toggle' ] as $value => $elements ) {
            $elements = ( array ) $elements;
            foreach ( $elements as $idx => $element ) {
                if ( ! isset($options[$element]) || ! isset($options[$element]['prefix']) || false !== $options[$element]['prefix'] )
                    $elements[$idx] = $prefix . $element;
            }
            $option[ 'toggle' ][$value] = $elements;
        }
    }
    
    // Finally, update the option
    $final[ $option_id ] = $option;
    
}