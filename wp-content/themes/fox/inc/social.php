<?php
if ( ! function_exists( 'wi_get_instagram_photos' ) ) :
/**
 * retrieve instagram photos
 *
 * @since 2.8
 */
function wi_get_instagram_photos( $username, $number, $cache_time ) {

    /**
     * Get Instagram Photos
     * @Scott Evans
     */
    $username = trim( strtolower( $username ) );
    $number = absint( $number );
    $cache_time = absint( $cache_time );

    if ( ! $username ) return;

    if ( $number < 1 || $number > 12 ) $number = 6;

    if ( false === ( $instagram = get_transient( 'wi-instagram-' . sanitize_title_with_dashes( $username . '-' . $number ) ) ) ) {

        switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;

			default:
				$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}

		if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wi' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wi' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wi' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'wi' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', $cache_time ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			$instagram = unserialize( base64_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) );

		}
    }

    if ( ! empty( $instagram ) ) {

        return array_slice( $instagram, 0, $number );

    } else {

        return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) );

    }

}
endif;

add_action( 'wp_head','fox_facebook_share_picture' );
if ( ! function_exists( 'fox_facebook_share_picture' ) ) :
/**
 * Add Facebook share photo property into <head /> tag
 * @since 4.0
 */
function fox_facebook_share_picture() {
    
    if ( ! is_singular() ) return;
    
    if ( ! has_post_thumbnail() ) return;

    $thumbnail = wp_get_attachment_url( get_post_thumbnail_id(),'full' );
?>

<meta property="og:image" content="<?php echo esc_url($thumbnail);?>"/>
<meta property="og:image:secure_url" content="<?php echo esc_url($thumbnail);?>" />

    <?php
}
endif;

if ( ! function_exists( 'fox_social_array' ) ) :
/**
 * Returns array of social icons
 * since 4.0
 */
function fox_social_array() {
    
    $all = fox_all_brands();
    $return = [];
    
    $brands = fox_social_support();
    
    $brands = explode ( ',', $brands );
    $brands = array_map ( 'trim', $brands );
    
    // more brands to come, since 4.0
    $brands = array_merge ( $brands, apply_filters( 'fox_more_brands', [] ) );
    
    foreach ( $brands as $brand ) {
        
        $return[ $brand ] = $all[ $brand ][ 'title' ];
        
    }
    
    // exceptions
    $return[ 'home' ] = esc_html( 'Home', 'wi' );
    $return[ 'email' ] = esc_html( 'Email', 'wi' );
    
    return $return;
    
    
}
endif;

if ( ! function_exists( 'fox_social_icons' ) ) :
/**
 * Display social icons
 * since 4.0
 */
function fox_social_icons( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'style' => '',
        'shape' => '',
        'align' => '',
        'size'  => '',
        'extra_class' => '',
        
        'border_width' => '',
        
        'color' => '',
        'background_color' => '',
        'border_color' => '',
        
        'hover_color' => '',
        'hover_background_color' => '',
        'hover_border_color' => '',
    ] ) );
    
    $class = [
        'social-list',
    ];
    
    if ( ! empty( $extra_class ) ) $class[] = $extra_class;
    
    if ( ! in_array( $style, [ 'black', 'outline', 'fill', 'color', 'text_color', 'plain' ] ) ) $style = 'black';
    if ( ! in_array( $shape, [ 'square', 'round', 'circle' ] ) ) $shape = 'circle';
    if ( ! in_array( $align, [ 'left', 'center', 'right' ] ) ) $align = 'center';
    if ( ! in_array( $size, [ 'small', 'normal', 'bigger', 'medium' ] ) ) $size = 'normal';

    $class[] = 'style-' . $style; if ( 'text_color' == $style ) $class[] = 'style-plain';
    $class[] = 'shape-' . $shape;
    $class[] = 'align-' . $align;
    $class[] = 'icon-size-' . $size;
    
    $social_array = fox_social_array();
    
    $social = get_theme_mod( 'wi_social' );
    if ( ! $social ) return;
    
    try {
        $social = json_decode( $social, true );
    } catch ( Exception $err ) {
        $social = [];
    }
    
    $brands = fox_all_brands();
    
    $css = [];
    $id = uniqid( 'social-id-' );
    
    /**
     * CSS
     */
    if ( '' != $border_width ) {
        if ( is_numeric( $border_width ) ) $border_width .= 'px';
        $css[] = 'border-width:' . $border_width;
    }
    if ( $color ) {
        $css[] = 'color:' . $color;
    }
    if ( $background_color ) {
        $css[] = 'background:' . $background_color;
    }
    if ( $border_color ) {
        $css[] = 'border-color:' . $border_color;
    }
    
    // CUSTOM COLOR
    $hover_css = [];
    if ( $hover_color ) {
        $hover_css[] = 'color:' . $hover_color;
    }
    if ( $hover_background_color ) {
        $hover_css[] = 'background:' . $hover_background_color;
    }
    if ( $hover_border_color ) {
        $hover_css[] = 'border-color:' . $hover_border_color;
    }
    
    $css_style = [];
    if ( $css ) $css_style[] = '#' . $id . ' a{' . join( ';', $css ). '}';
    if ( $hover_css ) $css_style[] = '#' . $id . ' a:hover{' . join( ';', $hover_css ) . '}';
    
    if ( $css_style ) {
        echo '<style type="text/css">';
        echo join( '', $css_style );
        echo '</style>';
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" id="<?php echo esc_attr( $id ); ?>">
    
    <ul>
    
        <?php foreach ( $social_array as $k => $v ) : 
    
    $url = isset( $social[ $k ] ) ? $social[ $k ] : '';
    
    if ( ! $url ) continue;
        
    // helper for email
    if ( 'email' == $k && is_email( $url ) ) {
        $url = 'mailto:' . $url;
    }
    
    $icondata = isset( $brands[ $k ] ) ? $brands[ $k ] : [];
    $ic = isset( $icondata[ 'icon' ] ) ? $icondata[ 'icon' ] : $k;
    
    if ( 'email' == $k ) {
        $icon = 'feather-mail';
    } else {
        if ( 'home' == $k || 'rss-2' == $k ) {
            $icon = 'fa fa-' . $ic;
        } else {
            $icon = 'fab fa-' . $ic;
        }
    }
    
    if ( 'text_color' == $style ) {
        $li_cl = 'co-' . $k;
    } else {
        $li_cl = 'li-' . $k;
    }
        ?>
        
        <li class="<?php echo esc_attr( $li_cl ); ?>">
            <a href="<?php echo esc_attr( $url ); ?>" target="_blank" rel="alternate" title="<?php echo esc_attr( $v ); ?>">
                <i class="<?php echo esc_attr( $icon ); ?>"></i>
                <span><?php echo esc_html( $v ); ?></span>
            </a>
        </li>
        
        <?php endforeach; ?>
    
    </ul>
    
</div><!-- .social-list -->
<?php
}
endif;