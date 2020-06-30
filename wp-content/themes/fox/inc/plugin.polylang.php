<?php
if ( function_exists( 'pll_register_string' ) ) :

    /**
     * Register some string from the customizer to be translated with Polylang
     */
    function fox_theme_name_pll_register_string() {
        
        // builder heading
        for ( $i = 1; $i <= 40; $i++ ) {
            $prefix = "bf_". $i . '_';
            $heading = get_theme_mod( $prefix . 'heading' );
            if ( '' != $heading ) {
                pll_register_string( 'homepage_builder_heading_' . $i, $heading, 'Fox', true );
            }
        }
        
        // main stream heading
        $prefix = 'main_stream_';
        $heading = get_theme_mod( $prefix . 'heading' );
        if ( '' != $heading ) {
            pll_register_string( 'homepage_builder_heading_mainstream', $heading, 'Fox', true );
        }
        
        // copyright
        $copyright = get_theme_mod( 'wi_copyright' );
        if ( '' != $copyright ) {
            pll_register_string( 'copyright', $copyright, 'Fox', true );
        }
        
        // quick translate strings
        $strings = fox_quick_translation_support();
        foreach ( $strings as $k => $str ) {
            pll_register_string( $k, $str, 'Fox', true );
        }
        
    }

    add_action( 'after_setup_theme', 'fox_theme_name_pll_register_string' );

endif;