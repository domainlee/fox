<?php
/**
 * deprecated since 4.3
 */
/**
 * Check if lazyload module enabled or not
 * return @bool
 * @since 4.0
 */
function fox_lazyload() {
    
    // since 4.3
    return false;
    
    if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
        $return = false;
    } else {
        $return = ( 'true' === get_theme_mod( 'wi_lazyload', 'false' ) );
    }
    
    return apply_filters( 'fox_is_lazyload', $return );
    
}

/**
 * Lazy Load Image Module
 * @since 4.0
 */

class Fox_LazyLoad {

    function __construct() {
        
        // deprecated since 4.3
        return;
        
        // replace attrs called after we have src and srcset attrs
        // we'll replace images with signal data-lazy="true"
        // we'll skip all other images
        // ie. data-lazy and small thumbnail are synchronized
        // but data-lazy="false" is to image being skipped from the_content parsing
        add_filter( 'wp_get_attachment_image_attributes', [ $this, 'attachment_attrs' ], 100, 3 );
        
        // general
        add_filter( 'the_content', [ $this, 'replace_content' ], PHP_INT_MAX );
        add_filter( 'widget_text', [ $this, 'replace_content' ], PHP_INT_MAX );
        add_filter( 'get_avatar', [ $this, 'replace_content' ],PHP_INT_MAX );
        add_filter( 'get_image_tag', [ $this, 'replace_content' ], PHP_INT_MAX );
        add_filter( 'post_thumbnail_html', [ $this, 'replace_content' ], PHP_INT_MAX );
            
    }
    
    /**
     * attachment_attrs to load lazy img
     * @since 4.0
     */
    function attachment_attrs( $attr, $attachment, $size ) {
        
        if ( ! fox_lazyload() ) return $attr;
        
        // not our target
        if ( ! isset( $attr[ 'data-lazy' ] ) || 'true' !== $attr[ 'data-lazy' ] ) return $attr;
        
        if ( image_get_intermediate_size( $attachment->ID, 'tiny' ) ) {
            $small_thumbnail = wp_get_attachment_image_src( $attachment->ID, 'tiny' );
        } else {
            $small_thumbnail = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
        }
        $attr[ 'data-src' ] = $attr[ 'src' ];
        if ( isset( $attr[ 'srcset' ] ) ) $attr[ 'data-srcset' ] = $attr[ 'srcset' ];
        $attr[ 'src' ] = $small_thumbnail[0];
        if ( isset( $attr[ 'srcset' ] ) ) unset( $attr[ 'srcset' ] );
        
        return $attr;
        
    }
    
    function replace_content( $content ) {
        
        if ( ! fox_lazyload() ) {
            return $content;
        }
        
        return preg_replace_callback('/(<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>)/i', [ $this, 'preg_lazyload' ], $content );
        
    }
    
    function preg_lazyload( $img_match ) {
        
        $img = $img_match[0];
        $src = substr( $img_match[2], 5, -1 );
        $smallestW = 9999;
        $smallestSRC = '';
        
        /**
         * exceptions:
         01 - images we have set data-src already, because the final goal is data-src
         02 - inside <noscript></noscript>, hmm, in fact, we can also set data-src for <noscript> tags, it's just fine
         */
        
        // do nothing if we found data-src
        if ( preg_match( '/(<\s*img[^>]+)(data-src\s*=\s*"[^"]+")([^>]+>)/i', $img, $m ) ) {
            return $img;
            
        // or found data-lazy    
        } elseif ( preg_match( '/(<\s*img[^>]+)(data-lazy\s*=\s*"[^"]+")([^>]+>)/i', $img, $m ) ) {
            return $img;
        } else {
            // echo esc_html( $img );
        }
        
        $img_html = $img_match[1] . 'src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" data-src="' . $src . '"' . $img_match[3];
        $img_html = str_replace( 'data-srcset', 'srcset', $img_html );
        $img_html = str_replace( 'srcset', 'data-srcset', $img_html );
        
        $img_html .= '<noscript>' . $img_match[1] . 'src="' . $src . '" data-src="' . $src . '"' . $img_match[3] . '</noscript>';
        
        if ( ! preg_match( '/(<\s*img[^>]+)((class)\s*=\s*"[^"]+")([^>]+>)/i', $img_html, $m ) ) {
                
            $img_html = $img_match[1] . ' class="pure-lazyload" src="' . $smallestSRC . '" data-src="' . $src . '"' . $img_match[3];

        } else {

            $img_html = preg_replace('/class\s*=\s*"/i', 'class="pure-lazyload ', $img_html );

        }
        
        return $img_html;
        
        /*
        
        @todo: medium-like
        but we need more info to display things in blur mode
        
        /**
         * target 1: tries to detect smallest src possible. smallest src includes width and height already
         * target 2: tries to determine image's width and height
         *
         * case 1: smallest + width + height => use smallest src
         * case 2: no smallest, but width + height => use transparent 1x1 img
         * case 3: no smallest, no width no height => use transparent 1x1 img, accept reflow
         *
        
        // if we can find srcset
        // try to get smallest src from srcset
        if ( preg_match( '/(<\s*img[^>]+)(srcset\s*=\s*"[^"]+")([^>]+>)/i', $img, $m ) ) {
            
            $srcset = substr( $m[2], 6 );
            
            $srcsets = explode( ',', $srcset );
            $srcsets = array_map( 'trim', $srcsets );
            
            foreach ( $srcsets as $set ) {
                
                $explode = explode( ' ', $set );
                if ( count( $explode ) < 2 ) continue;
                
                $set_src = trim( $explode[0] );
                $set_w = str_replace( 'w', '', $explode[1] );
                $set_w = absint( $set_w );
                if ( $set_w < $smallestW ) {
                    $smallestW = $set_w;
                    $smallestSRC = $set_src;
                }
                
            }
            
        }
        
        $w = $h = 0;
        $real_w = $real_h = 0;
        $padding_css = '';
        
        $basename = basename( $src );
        
        // try to get width/height from attributes
        if ( preg_match( '/(<\s*img[^>]+)(width\s*=\s*"[^"]+")([^>]+>)/i', $img, $m ) ) {
            
            $value = str_replace( 'width', '', $m[2] );
            $value = substr( $value, 2, -1 );
            $w = $value;
            $real_w = $w;
            
        }
        
        if ( ! $w || ! $h ) {
        
            if ( preg_match( '/\-([0-9]+)x([0-9]+)\.([a-z]+)$/', $basename, $m ) ) {
                
                $w = $m[1];
                $h = $m[2];
                
            } elseif ( $smallestSRC ) {
                
                if ( preg_match( '/\-([0-9]+)x([0-9]+)\.([a-z]+)$/', $smallestSRC, $m ) ) {
                    
                    $w = $m[1];
                    $h = $m[2];
                    
                }
                
            }
            
        }
        
        if ( ! $smallestSRC ) {
            
            $smallestSRC = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
            
        }
        
        // now replace
        // data-src is set
        $img_html = $img_match[1] . 'src="' . $smallestSRC . '" data-src="' . $src . '"' . $img_match[3];
        $img_html = str_replace( 'data-srcset', 'srcset', $img_html );
        $img_html = str_replace( 'srcset', 'data-srcset', $img_html );
        
        //if ( ! ( $w > 0 && $h > 0 ) ) {
            
            if ( ! preg_match( '/(<\s*img[^>]+)((class)\s*=\s*"[^"]+")([^>]+>)/i', $img_html, $m ) ) {
                
                $img_html = $img_match[1] . ' class="pure-lazyload" src="' . $smallestSRC . '" data-src="' . $src . '"' . $img_match[3];
                
            } else {
                
                $img_html = preg_replace('/class\s*=\s*"/i', 'class="pure-lazyload ', $img_html );
                
            }
            
        // }
        
        $img_html .= '<noscript>' . $img_match[1] . 'src="' . $src . '" data-src="' . $src . '"' . $img_match[3] . '</noscript>';
        
        /*
        // now if we have width and height, set a pseudo image
        if ( $w > 0 && $h > 0 ) {
            
            $padding = $h/$w * 100;
            $padding_css = ' style="padding-bottom:' . $padding . '%;"';   
            $height_element = '<span class="height-element height-element-inline"' . $padding_css . '></span>';
            
            $outer_css = '';
            if ( $real_w ) {
                $outer_css = ' style="width:' . $real_w . 'px;"';
            }
            
            $img_html = '<span class="lazyload-figure content-lazy custom-thumbnail"' . $outer_css . '><span class="image-element">' . $img_html . $height_element . '</span></span>';
            
        }
        *
        
        return $img_html;
        
        */
        
    }

}
new Fox_LazyLoad();