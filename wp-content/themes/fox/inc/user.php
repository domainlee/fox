<?php
if ( ! function_exists( 'fox_author_query_params' ) ) :
/**
 * Returns array of params
 * @since 4.0
 */
function fox_author_query_params( $args = [] ) {
    
}
endif;

if ( ! function_exists( 'fox_authors' ) ) :
/**
 * Display authors
 * @since 4.0
 */
function fox_authors( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        // query options
        'number' => '',
        'orderby' => '',
        'order' => '',
        'include' => '',
        
        // layout
        'layout' => '',
        'column' => '',
        'align' => '',
        'text_color' => '',
        
        'border' => 'yes',
        'border_color' => '#c0c0c0',
        
        // components
        'show_author_avatar' => 'yes',
        'author_avatar_shape' => 'circle',
        'show_author_name' => 'yes',
        'show_author_description' => 'yes',
        'show_author_social' => 'yes',
        'author_social_style' => '',
        
        // 'show_post_count' => false,
        // 'recent_posts' => 0, @todo
        
    ] ) );
    
    $args = array(
        'number' => $number,
        'has_published_posts' => true,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    $include = trim( $include );
    if ( ! empty( $include ) ) {
        $include = explode( ',', $include );
        $include = array_map( 'absint', $include );
        $args = [ 'include' => $include ];
        $args[ 'orderby' ] = 'include';
    }
    
    // The Query
    $user_query = new WP_User_Query( $args );
    
    if ( empty( $user_query->get_results() ) ) {
        echo '<div class="fox-error">' . esc_html__( 'No users found', 'wi' ) . '</div>';
        return;
    }
    
    // LAYOUT
    $class = [
        'fox-authors',
        'fox-users',
    ];
    $css = [];
    
    if ( 'list' != $layout ) $layout = 'grid';
    $class[] = 'fox-authors-' . $layout;
    
    if ( 'grid' == $layout ) {
    
        // column
        if ( $column < 1 || $column > 4 ) $column = 3;
        $class[] = 'column-' . $column;
        
        // align
        if ( 'center' != $align && 'right' != $align ) $align = 'left';
        $class[] = 'align-' . $align;
    
    }
    
    // text_color
    if ( $text_color ) {
        $css[] = 'color:' . $text_color ;
    }
    $css = join( ';', $css );
    if ( ! empty( $css ) ) {
        $css = ' style="' . esc_attr( $css ). '"';
    }
    
    // line css
    $line_css = '';
    if ( $border_color ) {
        $line_css = ' style="background-color:' . $border_color . '"';
    }
    
    // avatar shape
    if ( 'round' != $author_avatar_shape && 'acute' != $author_avatar_shape ) $author_avatar_shape = 'circle';
    
?>

<div class="fox-authors-wrapper">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>"<?php echo $css; ?>>

        <?php foreach ( $user_query->get_results() as $user ) :
    
        if ( 'yes' == $border && 'list' == $layout ) { ?>
        
        <div class="line"<?php echo $line_css; ?>></div>
        
        <?php } // list border
        
            fox_user([
                'user' => $user,

                'avatar' => ('yes' == $show_author_avatar ),
                'avatar_shape' => $author_avatar_shape,

                'name' => ( 'yes' == $show_author_name ),
                'description' => ( 'yes' == $show_author_description ),

                'social' => ( 'yes' == $show_author_social ),
                'social_style' => $author_social_style,
            ]);

        endforeach; // each user ?>
        
        <?php if ( 'yes' == $border && 'grid' == $layout ) { for ( $i = 1; $i < $column; $i++ ) { ?>
        
        <div class="line line-<?php echo $i; ?>"<?php echo $line_css; ?>></div>
        
        <?php } } ?>

    </div><!-- .fox-authors -->
    
</div><!-- .fox-authors-wrapper -->

<?php
    
}
endif;

if ( ! function_exists('wi_contactmethods') ):
add_filter( 'user_contactmethods' , 'wi_contactmethods' );
/**
 * Add contact methods
 * @since 4.0
 */
function wi_contactmethods( $contactmethods ) {
    
    $all = fox_all_brands();
    foreach( fox_user_social_support() as $brand ) {
        
        $brand_data = $all[ $brand ];
        $contactmethods[ $brand ] = $brand_data[ 'title' ] . ' URL';
        
    }

	return $contactmethods;
}
endif;

if ( ! function_exists( 'fox_user_social' ) ) :
/**
 * Display user social array
 * @since 4.0
 */
function fox_user_social( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        'user' => null,
        'style' => 'plain',
        'extra_class' => '',
        
    ] ) );
    
    if ( ! in_array( $style, [ 'plain', 'black', 'outline', 'fill', 'color' ] ) ) {
        $style = 'plain';
    }
    
    // in case no user set
    if ( ! $user ) {
        if ( is_single() ) {
            $user = get_the_author_meta( 'ID' );
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata( $author );
            $user = $userdata->ID;
        }
    }
    
    $class = [
        'social-list',
        'user-item-social',
        'shape-circle',
        
        'style-' . $style,
    ];
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    $legacy = [
        'facebook' => 'facebook-square',
        'pinterest' => 'pinterest-p',
        'vimeo' => 'vimeo-square',
        'vkontakte' => 'vk',
    ];
    
    $brands = fox_all_brands();
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <ul>
    
        <?php foreach ( fox_user_social_support() as $brand ) : $url = get_user_meta( $user, $brand, true ); 

        // legacy, try to get value from old key
        if ( ! $url ) {
            if ( isset( $legacy[ $brand ] ) ) {
                $url = get_user_meta( $user, $legacy[ $brand ], true );
            }
        }
        if ( ! $url ) continue;
    
        $title = isset( $brands[ $brand ][ 'title' ] ) ? $brands[ $brand ][ 'title' ] : '';
        $icon = isset( $brands[ $brand ][ 'icon' ] ) ? $brands[ $brand ][ 'icon' ] : $brand;
    if ( 'facebook' == $brand ) {
        $icon = 'facebook-square'; // legacy
    }
            
        ?>

        <li class="li-<?php echo esc_attr( $brand ); ?>">
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="alternate" title="<?php echo esc_attr( $title ); ?>">
                <i class="fab fa-<?php echo $icon; ?>"></i>
            </a>
        </li>

        <?php endforeach; ?>
        
        <?php $user = get_userdata( $user ); $url = $user->user_url; if ( $url ) { ?>
        
        <li class="li-website">
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" title="<?php echo esc_html__( 'Website', 'wi' ); ?>">
                <i class="fa fa-globe-americas"></i>
            </a>
        </li>
        
        <?php } ?>
        
    </ul>
    
</div><!-- .user-item-social -->

<?php
    
}
endif;

if ( ! function_exists( 'fox_user' ) ) :
/**
 * Display a user
 * @since 4.0
 */
function fox_user( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        'user' => null,
        
        'avatar' => true,
        'avatar_shape' => 'circle',
        
        'name' => true,
        'post_count' => true,
        'description' => true,
        
        'social' => true,
        'social_style' => 'plain',
        
        'extra_class' => '',
        'after_body' => '',
        
        'author_page' => false,
        
    ] ) );
    
    // in case no user set
    if ( ! $user ) {
        if ( is_single() ) {
            $user = get_userdata( get_the_author_meta( 'ID' ) );
        } elseif ( is_author() ) {
            global $author;
            $user = get_userdata( $author );
        }
    }
    
    if ( ! is_object( $user ) ) return;
    
    $link = get_author_posts_url( $user->ID, $user->nicename );
    
    $class = [
        'fox-user-item',
        'fox-author',
        'fox-user',
    ];
    
    if ( $extra_class ) $class[] = $extra_class;
    ?>
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php if ( $avatar ) { ?>

        <div class="user-item-avatar avatar-<?php echo esc_attr( $avatar_shape ); ?>">

            <a href="<?php echo $link; ?>">

                <?php echo get_avatar( $user->ID, 300 ); ?>

            </a>

        </div><!-- .user-item-avatar -->

        <?php } ?>

        <div class="user-item-body">

            <?php if ( $name ) { ?>

            <div class="user-item-header">

                <div class="user-item-name-wrapper">
                    
                    <?php if ( ! $author_page ) { ?>
                    <h3 class="user-item-name">

                        <a href="<?php echo $link; ?>"><?php echo $user->display_name; ?></a>

                    </h3>
                    <?php } else { ?>
                    
                    <h1 class="user-item-name"><?php echo $user->display_name; ?></h1>
                    
                    <?php } ?>
                    
                </div><!-- .user-item-name-wrapper -->

            </div><!-- .user-item-header -->

            <?php } ?>

            <?php if ( $description && $user->description ) { ?>

            <div class="user-item-description">

                <?php echo wpautop( $user->description ); ?>

            </div><!-- .user-item-description -->

            <?php } ?>
            
            <?php if ( $social ) { ?>

            <?php fox_user_social([ 'user' => $user->ID, 'style' => $social_style, 'extra_class' => 'user-item-name-meta' ] ); ?>

            <?php } ?>

        </div><!-- .user-item-body -->
        
        <?php if ( $after_body ) echo $after_body; ?>

    </div><!-- .fox-user-item -->

    <?php

}
endif;

/* ------------------------------           USER BACKGROUND        --------------------------------------------- */
add_action( 'show_user_profile', 'fox_profile_background_field' );
add_action( 'edit_user_profile', 'fox_profile_background_field' );

function fox_profile_background_field( $user ) {
    
    // so it becomes a local property
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_background';
    
    $image_id = '';
    $image = get_user_meta( $user->ID, $field_id , true );
    if ( $image ) {
        $image_id = $image;
        $image = wp_get_attachment_image_src( $image, 'medium' );
        if ( $image ) {
            $image = $image[0];
        }
    }
    $upload_button_name = $image ? esc_html__( 'Change Image','wi' ) : esc_html__( 'Upload Image','wi' );
	?>
	<h3><?php esc_html_e( 'Upload Cover Photo', 'wi' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Cover Photo', 'wi' ); ?></label></th>
			<td>
                
                <div class="wi-upload-wrapper">
    
                    <figure class="image-holder">

                        <?php if ( $image ) : ?>
                        <img src="<?php echo esc_url($image);?>" />
                        <?php endif; ?>

                        <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

                    </figure>

                    <input type="hidden" class="media-result" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $image_id ); ?>" />
                    <input type="button" class="upload-image-button button button-primary" value="<?php echo $upload_button_name;?>" />

                </div><!-- .wi-upload-wrapper -->
                
            </td>
		</tr>
	</table>
	<?php
}

add_action( 'personal_options_update', 'fox_profile_background_field_update' );
add_action( 'edit_user_profile_update', 'fox_profile_background_field_update' );

function fox_profile_background_field_update( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
    
    // so it becomes a local property
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_background';

	// if ( ! empty( $_POST[ $field_id ] ) ) {
    update_user_meta( $user_id, $field_id, intval( $_POST[ $field_id ] ) );
	// }
}

/* ------------------------------           USER AVATATR, SINCE 4.3        --------------------------------------------- */
/* ------------------------------           USER AVATATR        --------------------------------------------- */
add_action( 'show_user_profile', 'fox_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'fox_show_extra_profile_fields' );

function fox_show_extra_profile_fields( $user ) {
    
    // so it becomes a local property
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_avatar';
    
    $image_id = '';
    $image = get_user_meta( $user->ID, $field_id , true );
    if ( $image ) {
        $image_id = $image;
        $image = wp_get_attachment_image_src( $image, 'medium' );
        if ( $image ) {
            $image = $image[0];
        }
    }
    $upload_button_name = $image ? esc_html__( 'Change Image','wi' ) : esc_html__( 'Upload Image','wi' );
	?>
	<h3><?php esc_html_e( 'Upload Avatar', 'wi' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Avatar', 'wi' ); ?></label></th>
			<td>
                
                <div class="wi-upload-wrapper">
    
                    <figure class="image-holder">

                        <?php if ( $image ) : ?>
                        <img src="<?php echo esc_url($image);?>" />
                        <?php endif; ?>

                        <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

                    </figure>

                    <input type="hidden" class="media-result" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $image_id ); ?>" />
                    <input type="button" class="upload-image-button button button-primary" value="<?php echo $upload_button_name;?>" />

                </div><!-- .wi-upload-wrapper -->
                
            </td>
		</tr>
	</table>
	<?php
}

add_action( 'personal_options_update', 'fox_update_profile_fields' );
add_action( 'edit_user_profile_update', 'fox_update_profile_fields' );

function fox_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
    
    // so it becomes a local property
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_avatar';

	// if ( ! empty( $_POST[ $field_id ] ) ) {
		update_user_meta( $user_id, $field_id, intval( $_POST[ $field_id ] ) );
	// }
}

add_filter( 'get_avatar_url', 'fox_custom_avatar_url', 10, 3 );
function fox_custom_avatar_url( $url, $id_or_email, $args ) {
    
    // so it becomes a local property
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_avatar';
    
    $id = 0;
    if ( $id_or_email instanceof WP_User ) {
        
        $id = $id_or_email->ID;
        
    } elseif ( $id_or_email instanceof WP_Comment ) {
        
        $id = $id_or_email->user_id;
        
    } elseif ( is_numeric( $id_or_email ) ) {
    
        $id = $id_or_email;
        
    } elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
        
        $user = get_user_by( $id_or_email, 'email' );
        if ( $user ) {
            $id = $user->ID;
        }
    
    }
    
    if ( $id ) {
        
        $image = get_user_meta( $id, $field_id, true );
        if ( $image ) {
            
            $image_id = $image;
            $image = wp_get_attachment_image_src( $image, 'thumbnail' );
            if ( $image ) {
                $image = $image[0];
            }
            
        }
        if ( $image ) {
            $url = $image;
        }
            
    }
        
    return $url;
    
}