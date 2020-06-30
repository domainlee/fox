<?php
/**
 * install message
 * due to instagram API change, Instagram Feed plugin is required
 *
 * @since 4.3
 */
function fox_instagram_install_message() {
    
    $msg = '';
    if ( current_user_can( 'manage_options') ) {
        
        $msg = 'Due to <a href="https://www.instagram.com/developer/" target="_blank">Instagram API change</a>, you should install <a href="https://wordpress.org/plugins/instagram-feed/" target="_blank">Instagram Feed</a> plugin to make it work correctly.';
        
    }
    
    return $msg;
    
}

/**
 * installation notice for admin users
 * @since 4.3
 */
function fox_instagram_admin_message() {
    
    $msg = '';
    if ( current_user_can( 'manage_options') ) {
        
        $msg = '<strong>This message is displayed for only admins (site visitors won\'t see it).</strong><br> Due to <a href="https://www.instagram.com/developer/" target="_blank">Instagram API change</a>, you should install <a href="https://wordpress.org/plugins/instagram-feed/" target="_blank">Instagram Feed</a> plugin as soon as possible to make it work correctly in the future. The deadline is June 29, 2020.<br>
        <strong>What if i don\'t install that plugin?</strong> Then your instagram feed will still be displayed but it can be down any time in the future.<br>
        <strong>Why this happens?</strong> Because Instagaram changed their API.';

        $msg = fox_format( '<p class="fox-error">{msg}</p>', [ 'msg' => $msg ] );
        
    }
    
    return $msg;
    
}

if ( ! function_exists( 'fox_render_instagram') ) :
/**
 * render instagram with fallback to instagram-feed plugin
 *
 * @since 4.3
 */
function fox_render_instagram( $params = [] ) {
    
    $params = wp_parse_args( $params, [
        
        'username' => '', // or hashtag
        'number' => '6', // number of photos

        'column' => '', // for masonry, grid layout
        'item_spacing' => '', // spacing between items

        'cache_time' => '',
        'header' => true, // show header
        
        'show_meta' => '',
        'show_header' => true,
        'header_align' => 'center',

        'follow_text' => '', // follow  text, just a legacy
        'crop'      => true, // crop or not
        
        'images' => '', // for demo version
        
    ]);
    
    if ( ! defined( 'SBIVER' ) || ( fox_is_demo() && $params[ 'images' ] ) ) {
        
        $new_insta = new Fox_Instagram( $params );
        $new_insta->output();
        
        return;
        
    }
    
    $sc_params = [
        'header' => $params[ 'show_header' ] ? 'true' : 'false',
        'follow' => ! empty( $params[ 'follow_text' ] ) ? 'true' : 'false',
        'followtext' => $params[ 'follow_text' ],
        'number' => $params[ 'number' ],
        'column' => $params[ 'column' ],
    ];
    
    $class = [ 'fox-instagram' ];
    
    // column
    //
    $column = absint( $params[ 'column' ] );
    if ( $column < 1 ) $column = 3;
    if ( $column > 10 ) $column = 10;

    $class[] = 'icolumn-' . $column;

    // spacing
    $item_spacing = $params[ 'item_spacing' ];
    if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'wide', 'wider' ] ) ) {
        $item_spacing = 'normal';
    }
    $spacing_adapter = [
        'none' => 0,
        'tiny' => 5,
        'small' => 10,
        'normal' => 16,
        'medium' => 24,
        'wide' => 32,
        'wider' => 40,
    ];
    if ( isset( $spacing_adapter[ $item_spacing] ) ) {
        $sc_params[ 'padding' ] = $spacing_adapter[ $item_spacing];
    }
    
    $sc = fox_format( '[instagram-feed imageres="medium" num={number} cols={column} showfollow={follow} followtext="{followtext}" showheader={header} imagepadding={padding} showbutton=false showbio=true]', $sc_params );
    
    $class[] = 'ispacing-' . $item_spacing;
    
    ?>

<div class="fox-instagram-wrapper">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
        <?php // echo do_shortcode( $sc ); ?>
        
    </div><!-- .fox-instagram -->

</div><!-- .fox-instagram-wrapper -->

    <?php
    
}
endif;

if ( ! class_exists( 'Fox_Instagram' ) ) :
/**
 * Renders instagram
 * @since 4.0
 */
class Fox_Instagram {
    
    function __construct( $args = [] ) {

        $this->args = wp_parse_args( $args, [

            'username' => '', // or hashtag
            'number' => '6', // number of photos

            'column' => '', // for masonry, grid layout
            'item_spacing' => '', // spacing between items

            'cache_time' => '',
            'header' => true, // show header
            'show_meta' => '',
            'show_header' => true,
            'header_align' => 'center',
            
            'follow_text' => '', // follow  text, just a legacy
            'crop'      => true, // crop or not
            
            'images' => '',

        ] );
        
    }
    
    /**
     * Render Output
     * @since 4.0
     */
    function output( $echo = true ) {
        
        if ( fox_is_demo() && ! empty( $this->args[ 'images' ] ) ) {
            
            $images = $this->args[ 'images' ];
            if ( ! is_array( $images ) ) {
                $images = explode( ',', $images );
                $images = array_map( 'absint', $images );
            }
            
            $attachments = get_posts([
                'post_type' => 'attachment',
                'post__in' =>$images,
                'posts_per_page' => -1,
            ]);
            
            $link = 'https://www.instagram.com/';
            if ( $this->args[ 'username' ] ) {
                $link .= $this->args[ 'username' ];
            }
            
            $photos = [];
            foreach ( $attachments as $attachment ) {
                
                $photo = [
                    'img_html' => wp_get_attachment_image( $attachment->ID, 'thumbnail-square' ),
                    'description' => $attachment->post_excerpt,
                    'link' => $link,
                    'likes' => '',
                    'comments' => '',
                    'type' => 'image',
                ];
                $photos[] = $photo;
                
            }
            
            $info = [];
            
        } else {
            
            $get = $this->get_photos();

            $number = absint( $this->args[ 'number' ] );
            if ( $number < 1 ) $number = 6;
            if ( $number > 12 ) $number = 12;

            if ( null === $get ) return;

            if ( is_wp_error( $get ) ) {
                $return = '<div class="fox-error">' . $get->get_error_message() . '</div>';
                if ( $echo ) echo $return;
                return $return;
            }

            if ( ! isset( $get[ 'photos' ] ) ) return;
            if ( ! isset( $get[ 'info' ] ) ) return;

            $photos = $get[ 'photos' ]; $photos = array_slice( $photos, 0, $number );
            
            $info = $get[ 'info' ];
            
        }
        
        ob_start();
        
        $class = [
            'fox-instagram',
            'fox-grid',
            'fox-grid-gallery',
        ];
        
        // column
        //
        $column = absint( $this->args[ 'column' ] );
        if ( $column < 1 ) $column = 3;
        if ( $column > 10 ) $column = 10;

        $class[] = 'icolumn-' . $column;
        
        // spacing
        $item_spacing = $this->args[ 'item_spacing' ];
        if ( ! in_array( $item_spacing, [ 'none', 'tiny', 'small', 'normal', 'wide', 'wider' ] ) ) {
            $item_spacing = 'normal';
        }
        $class[] = 'spacing-' . $item_spacing;
        
        // crop
        if ( ! $this->args[ 'crop' ] ) {
            $class[] = 'no-crop';
        }
        
        // bio link
        if ( substr( $this->args[ 'username' ], 0, 1 ) == '#' ) {
            $case = 'hashtag';
            $url = 'https://www.instagram.com/explore/tags/' . str_replace( '#', '', $this->args[ 'username' ] );
        } else {
            $case = 'user';
            $url = 'https://www.instagram.com/' . $this->args[ 'username' ];
        }
?>

<div class="fox-instagram-wrapper">
    
    <?php if ( ! fox_is_demo() ) echo fox_instagram_admin_message(); ?>
    
    <?php if ( ! empty( $info ) ) { ?>
    
        <?php if ( $this->args[ 'show_header' ] ) {
            $header_align = $this->args[ 'header_align' ];
                if ( 'left' != $header_align && 'right' != $header_align ) $header_align = 'center';
        ?>

        <?php if ( 'user' == $case ) { ?>

        <div class="insta-header align-<?php echo esc_attr( $header_align ); ?>">

            <div class="insta-avatar">
                <a href="<?php echo esc_url( $url ); ?>" target="_blank">

                    <img src="<?php echo esc_url( $info[ 'avatar' ] ); ?>" alt="<?php echo esc_attr( $info[ 'name' ] ); ?>" data-lazy="false" />

                </a>
            </div>

            <div class="insta-text">
                <h4 class="insta-name">
                    <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                        <?php echo $info[ 'name' ]; ?>
                    </a>
                </h4>
                <div class="insta-followers">
                    <span class="insta-follower-count"><?php printf( esc_html__( '%s followers', 'wi' ), fox_number( $info[ 'followers' ] ) ); ?></span>
                </div>
            </div>

        </div><!-- .insta-header -->

        <?php } else { ?>

        <div class="insta-header">

            <div class="insta-avatar">
                <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $info[ 'avatar' ] ); ?>" data-lazy="false" />
                </a>
            </div>

            <div class="insta-text">
                <h4 class="insta-name">
                    <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                        <?php echo $this->args[ 'username' ]; ?>
                    </a>
                </h4>
                <div class="insta-followers">
                    <span class="insta-follower-count"><?php printf( esc_html__( '%s posts', 'wi' ), fox_number( $info[ 'count' ] ) ); ?></span>
                </div>
            </div>

        </div><!-- .insta-header -->

        <?php } ?>

        <?php } // show header ?>
    
    <?php } // check empty info ?>
    
    <div class="fox-instagram-container">

        <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

            <?php foreach ( $photos as $photo ) :
        
                $alt = strip_tags( $photo[ 'description' ] );
        
        if ( isset( $photo[ 'img_html' ] ) ) {
            
            $img_html = $photo[ 'img_html' ];
            
        } else {
            
            $src = $photo[ 'original' ];
            if ( ! $this->args[ 'crop' ] ) {
                $src = $photo[ 'original' ];
            } else {
                $src = $photo[ 'large' ];
            }
            
            $img_html = '<img ';
            $img_attrs = [
                'src' => $src,
                'alt' => $alt,
            ];

            if ( $this->args[ 'crop' ] ) {

                $img_attrs[ 'sizes' ] = '(max-width: 480px) 100vw, 1080px';

                $img_attrs[ 'srcset' ] = join( ',', [

                    $photo[ 'small' ] . ' 400w',
                    $photo[ 'thumbnail' ] . ' 600w',
                    $photo[ 'large' ] . ' 800w',

                ] );

            }

            if ( fox_lazyload() ) {

                $img_attrs[ 'data-src' ] = $img_attrs[ 'src' ];
                if ( isset( $img_attrs[ 'srcset' ] ) ) {
                    $img_attrs[ 'data-srcset' ] = $img_attrs[ 'srcset' ];
                    unset( $img_attrs[ 'srcset' ] );
                }

                if ( $this->args[ 'crop' ] ) {

                    $img_attrs[ 'src' ] = $photo[ 'small' ];

                }

            }

            foreach ( $img_attrs as $k => $n ) {
                $img_html .= $k . '="' . esc_attr( $n ) . '"';
            }

            $img_html .= ' />';
            $img_html = '<span class="image-element">' . $img_html . '<span class="height-element"></span></span>';
            
        }
        
        $class = [
            'fox-figure',
            'insta-item-inner',
            'custom-thumbnail'
        ];
        
        if ( ! $this->args[ 'crop' ] ) {
            
            $class[] = 'custom-thumbnail-contain';
                
        }
        
        if ( $this->args[ 'crop' ] && fox_lazyload() ) {
            
            $class[] = 'lazyload-figure';
            
        }
        
            ?>

            <div class="fox-grid-item insta-item">

                <figure class="<?php echo esc_attr( join( ' ', $class ) ); ?>" itemscope itemtype="https://schema.org/ImageObject">

                    <a href="<?php echo esc_url( $photo[ 'link' ] ); ?>" target="_blank" title="<?php echo esc_attr( $alt ); ?>">

                        <?php echo $img_html; ?>

                        <?php if ( 'video' == $photo[ 'type' ] ) : ?>
                        <span class="insta-video-icon">
                            <i class="fa fa-play"></i>
                        </span>
                        <?php endif; ?>

                        <?php if ( $this->args[ 'show_meta' ] ) { ?>

                        <span class="insta-item-overlay"></span>

                        <span class="insta-meta">

                            <span class="insta-meta-item insta-meta-likes">
                                <i class="fa fa-heart"></i>
                                <span class="insta-meta-item-num"><?php echo fox_number( $photo[ 'likes' ] ); ?></span>
                            </span>

                            <span class="insta-meta-item insta-meta-comments">
                                <i class="fa fa-comment-alt"></i>
                                <span class="insta-meta-item-num"><?php echo fox_number( $photo[ 'comments' ] ); ?></span>
                            </span>

                        </span><!-- .insta-meta -->

                        <?php } ?>

                    </a>

                </figure><!-- .fox-grid-item -->

            </div><!-- .insta-item -->

            <?php endforeach ; ?>

        </div><!-- .fox-instagram -->
        
    </div><!-- .fox-instagram-container -->

        <?php if ( $this->args[ 'follow_text' ] ) {
        
?>
            
    <div class="follow-text fox-button">

        <a href="<?php echo esc_url( $url ); ?>" target="_blank" class="follow-us fox-btn btn-primary"><?php echo esc_html( $this->args[ 'follow_text' ] ); ?></a>

    </div><!-- .follow-text -->
    
    <?php } ?>
    
</div><!-- .fox-instagram-wrapper -->

    <?php
        
        $return = ob_get_clean();
        if ( $echo ) echo $return;
        return $return;
        
    }
    
    /**
     * returns array of instagram data
     * return WP_Error class on error
     * @since 4.0
     */
    function get_photos() {
        
        $username = trim( strtolower( $this->args[ 'username' ] ) );
        $cache_time = absint( $this->args[ 'cache_time' ] );

        if ( ! $username ) return new WP_Error( 'empty_username', esc_html__( 'Empty Instagram username', 'wi' ) );

        if ( false === ( $instagram = get_transient( 'wi-instagram-' . sanitize_title_with_dashes( $username . '-12' ) ) ) ) {

            switch ( substr( $username, 0, 1 ) ) {
                case '#':
                    $url              = 'https://www.instagram.com/explore/tags/' . str_replace( '#', '', $username );
                    $transient_prefix = 'h';
                    break;

                default:
                    $url              = 'https://www.instagram.com/' . str_replace( '@', '', $username );
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
                    return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wi' ) . fox_instagram_install_message() );
                }
                
                $user = $hashtag = $info = [];
                if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'] ) ) {
                    $user = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'];
                } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag'] ) ) {
                    $hashtag = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag'];
                } else {
                    return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wi' ) . fox_instagram_install_message() );
                }
                
                if ( ! empty( $user ) ) {
                    
                    $images = $user['edge_owner_to_timeline_media']['edges'];
                    
                    $info = [
                        'followers' => $user[ 'edge_followed_by' ][ 'count' ],
                        'description' => $user[ 'biography' ],
                        'name' => $user[ 'full_name' ],
                        'avatar' => $user[ 'profile_pic_url' ],
                    ];
                    
                } else {
                    
                    $images = $hashtag['edge_hashtag_to_media']['edges'];
                    $info = [
                        'count' => isset( $hashtag[ 'edge_hashtag_to_media' ][ 'count' ] ) ? $hashtag[ 'edge_hashtag_to_media' ][ 'count' ] : '',
                        'avatar' => $hashtag[ 'profile_pic_url' ] ? $hashtag[ 'profile_pic_url' ] : '',
                    ];
                    
                }
                
                if ( ! is_array( $images ) ) {
                    return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid photos data.', 'wi' ) );
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
                
                $instagram = [
                    'info' => $info,
                    'photos' => $instagram,
                ];

                // do not set an empty transient - should help catch private or empty accounts.
                if ( ! empty( $instagram ) ) {
                    $instagram = base64_encode( serialize( $instagram ) );
                    set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, $cache_time );
                }
            }

            if ( ! empty( $instagram ) ) {

                $instagram = unserialize( base64_decode( $instagram ) );

            } else {

                return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) . fox_instagram_install_message() );

            }
            
        }

        if ( ! empty( $instagram ) ) {

            return $instagram;

        } else {

            return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wi' ) . fox_instagram_install_message() );

        }
        
    }
    
}
endif;

if ( ! function_exists( 'fox_instagram_params' ) ) :
/**
 * Instagram Params
 * This function helps implementing fox Instagram in many places: widget, theme option or page builder
 * @since 4.0
 */
function fox_instagram_params() {
    
    $numbers = array( '1' => esc_html__( '1 Photo', 'wi' ) );
    for ( $i = 2; $i <= 12; $i++ ) {
        $numbers[ (string) $i ] = sprintf( esc_html__( '%d Photos', 'wi' ), $i );
    }

    $columns = array( '1' => esc_html__( '1 Column', 'wi' ) );
    for ( $i = 2; $i <= 9; $i++ ) {
        $columns[ (string) $i ] = sprintf( esc_html__( '%d Columns', 'wi' ), $i );
    }
    
    $params = [];
    
    if ( ! defined( 'SBIVER' ) ) {
    
        $params[] = array (
            'id' => 'username',
            'type' => 'text',
            'placeholder' => 'yourusername',
            'name' => 'Enter a username or #hashtag',

            'section' => 'settings',
            'section_title' => 'Settings',
        );
        
    }
    
    if ( ! defined( 'SBIVER' ) ) {
    
        $params[] = array(
            'id' => 'number',
            'type' => 'select',
            'options'=> $numbers,
            'std'   => '9',
            'name' => esc_html__( 'Number of photos', 'wi' ),
        );
        
    } else {
        
        $params[] = array(
            'id' => 'number',
            'type' => 'select',
            'options'=> $numbers,
            'std'   => '9',
            'name' => esc_html__( 'Number of photos', 'wi' ),
            
            'section' => 'settings',
            'section_title' => 'Settings',
        );
        
    }
    
    $params[] = array(
        'id' => 'column',
        'type' => 'select',
        'options'=> $columns,
        'std'   => '3',
        'name' => esc_html__( 'Columns?', 'wi' ),
    );
    
    if ( ! defined( 'SBIVER' ) ) {
    
        $params[] = array(
            'id' => 'crop',
            'type' => 'checkbox',
            'std'   => true,
            'name' => 'Image Crop?',
        );
        
    }
    
    $params[] = array(
        'id' => 'item_spacing',
        'type' => 'select',
        'name' => 'Item Spacing',
        'std' => 'tiny',
        'options' => [
            'none' => 'No spacing',
            'tiny' => 'Tiny',
            'small' => 'Small',
            'normal' => 'Normal',
            'wide' => 'Wide',
            'wider' => 'Wider',
        ],
    );
    
    $params[] = array(
        'id' => 'show_header',
        'type' => 'checkbox',
        'std' => true,
        'name' => 'Show Header?',
    );
    
    if ( ! defined( 'SBIVER' ) ) {
        
        $params[] = array(
            'id' => 'header_align',
            'type' => 'select',
            'options' => [
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ],
            'std' => 'center',
            'name' => 'Header align?',
        );
    
        $params[] = array(
            'id' => 'show_meta',
            'type' => 'checkbox',
            'name' => 'Show Meta?',
        );
    
        $params[] = array(
            'id' => 'cache_time',
            'type' => 'select',
            'options' => array(

                (string) ( HOUR_IN_SECONDS/ 2 ) => esc_html__( 'Half Hours', 'wi' ),
                (string) ( HOUR_IN_SECONDS ) => esc_html__( 'An Hour', 'wi' ),
                (string) ( HOUR_IN_SECONDS * 2 ) => esc_html__( 'Two Hours', 'wi' ),
                (string) ( HOUR_IN_SECONDS * 4 ) => esc_html__( 'Four Hours', 'wi' ),
                (string) ( DAY_IN_SECONDS ) => esc_html__( 'A Day', 'wi' ),
                (string) ( WEEK_IN_SECONDS ) => esc_html__( 'A Week', 'wi' ),

            ),
            'std'   => (string) ( HOUR_IN_SECONDS * 2 ),
            'name'  => esc_html__( 'Cache Time', 'wi' ),
            'desc'  => esc_html__( 'If you do not often upload new photos, you can set longer caching time to speed up loading time.', 'wi' ),
        );
        
    }
    
    $params[] = array(
        'id' => 'follow_text',
        'type' => 'text',
        'std'   => esc_html__( 'Follow Us', 'wi' ),
        'name'  => esc_html__( 'Follow Text', 'wi' ),
    );
    
    return $params;
    
}
endif;