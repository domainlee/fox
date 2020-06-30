<?php
/**
 * These functions make your site extremply slow
 * So they're depricated @since 2.3
 */

/* Source:
http://wordpress.org/support/topic/custom-query-related-posts-by-common-tag-amount 
http://pastebin.com/NnDzdSLd
*/
if(!function_exists('get_related_tag_posts_ids')){
function get_related_tag_posts_ids( $post_id, $number = 5, $taxonomy = 'post_tag', $post_type = 'post' ) {

	$related_ids = false;

	$post_ids = array();
	// get tag ids belonging to $post_id
	$tag_ids = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
	if ( $tag_ids ) {
		// get all posts that have the same tags
		$tag_posts = get_posts(
			array(
				'post_type'		 =>	$post_type,
				'posts_per_page' => -1, // return all posts 
				'no_found_rows'  => true, // no need for pagination
				'fields'         => 'ids', // only return ids
				'post__not_in'   => array( $post_id ), // exclude $post_id from results
				'tax_query'      => array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'id',
						'terms'    => $tag_ids,
						'operator' => 'IN'
					)
				)
			)
		);

		// loop through posts with the same tags
		if ( $tag_posts ) {
			$score = array();
			$i = 0;
			foreach ( $tag_posts as $tag_post ) {
				// get tags for related post
				$terms = wp_get_post_terms( $tag_post, $taxonomy, array( 'fields' => 'ids' ) );
				$total_score = 0;

				foreach ( $terms as $term ) {
					if ( in_array( $term, $tag_ids ) ) {
						++$total_score;
					}
				}

				if ( $total_score > 0 ) {
					$score[$i]['ID'] = $tag_post;
					// add number $i for sorting 
					$score[$i]['score'] = array( $total_score, $i );
				}
				++$i;
			}

			// sort the related posts from high score to low score
			uasort( $score, 'sort_tag_score' );
			// get sorted related post ids
			$related_ids = wp_list_pluck( $score, 'ID' );
			// limit ids
			$related_ids = array_slice( $related_ids, 0, (int) $number );
		}
	}
	return $related_ids;
}
}
if ( !function_exists('sort_tag_score')){
function sort_tag_score( $item1, $item2 ) {
	if ( $item1['score'][0] != $item2['score'][0] ) {
		return $item1['score'][0] < $item2['score'][0] ? 1 : -1;
	} else {
		return $item1['score'][1] < $item2['score'][1] ? -1 : 1; // ASC
	}
}
}

/**
 * From v2.8, we no longer use walker
 */
/**
 * we keep this just a backup for backward compatibility
 */
if ( !class_exists('wi_mainnav_walker') ) {
class wi_mainnav_walker extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS class(es) applied to a menu item's list item element.
         *
         * @since 2.8.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 2.8.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 2.8.0
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item. Used for padding.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        
	}
	
}	// wi_mainnav_walker class
}	// class exists

/* -------------------------------------------------------------------- */
/* ICONS
 * depricated since 2.8
/* -------------------------------------------------------------------- */
//add_action('wp_head','wi_icons');
if (!function_exists('wi_icons')) {
    function wi_icons(){
        $sizes = array(57, 72, 76, 114, 144, 152, 180);
        
        if( get_theme_mod('fox_favicon') ) { ?>
            <link rel="shortcut icon" href="<?php echo get_theme_mod('fox_favicon');?>">
        <?php }
        foreach ( $sizes as $size ){ 
            if( get_theme_mod("wi_apple_$size") ) { ?>
                <link href="<?php echo get_theme_mod("wi_apple_$size"); ?>" sizes="<?php echo esc_attr(printf('%sx%s',$size,$size)); ?>" rel="apple-touch-icon-precomposed">
            <?php }
        } // foreach
        
    }
}

/* -------------------------------------------------------------------- */
/* IMAGE QUALITY
 * depricated since 2.8
/* -------------------------------------------------------------------- */
//add_filter('jpeg_quality', 'wi_image_full_quality');
//add_filter('wp_editor_set_quality', 'wi_image_full_quality');
if (!function_exists('wi_image_full_quality')) {
function wi_image_full_quality($quality) {
    return 100;
}
}

/**
 * those functions have been moved to clas Wi_Admin
 */
if ( !function_exists('wi_columns_filter') ) {
function wi_columns_filter( $columns ) {
 	$column_thumbnail = array( 'thumbnail' => __('Thumbnail','wi') );
	$columns = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
	return $columns;
}
}
if ( !function_exists('wi_add_thumbnail_value_editscreen') ) {
function wi_add_thumbnail_value_editscreen($column_name, $post_id) {

	$width = (int) 50;
	$height = (int) 50;

	if ( 'thumbnail' == $column_name ) {
		// thumbnail of WP 2.9
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		// image from gallery
		$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
		if ($thumbnail_id)
			$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
		elseif ($attachments) {
			foreach ( $attachments as $attachment_id => $attachment ) {
				$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
			}
		}
		if ( isset($thumb) && $thumb ) {
			echo $thumb;
		} else {
			echo '<em>' . __('None','wi') . '</em>';
		}
	}
}
}

/* -------------------------------------------------------------------- */
/* FORMAT GALLERY OPTIONS
/* -------------------------------------------------------------------- */
function wi_add_gallery_effect_field_init() {
	$post_formats = get_theme_support('post-formats');
	if (!empty($post_formats[0]) && is_array($post_formats[0])) {
		if (in_array('gallery', $post_formats[0])) {
			add_action('save_post', 'wi_format_gallery_save_post');
		}
	}
}

if (!function_exists('wi_add_gallery_effect_field')){
function wi_add_gallery_effect_field() {
	global $post;
	$effect = get_post_meta($post->ID, '_format_gallery_effect', true);
    if ($effect!='fade' && $effect!='carousel') $effect = 'slide';
	?>
	<div class="vp-pfui-elm-block" style="padding-left:2px; margin-bottom:10px;">
        
		<label for="vp-pfui-format-gallery-type" style="padding-left:0; margin-bottom:10px;"><?php _e('Gallery Style', 'wi'); ?></label>
        
        <input type="radio" name="_format_gallery_effect" value="slide" id="slide" <?php checked( $effect, "slide" ); ?>>
		<label style="display:inline-block;padding-right: 20px;margin-bottom: 4px;padding-left:4px;" for="slide"><?php _e('Slide Slider','wi');?></label>
        
		<input type="radio" name="_format_gallery_effect" value="fade" id="fade" <?php checked( $effect, "fade" ); ?>>
		<label style="display:inline-block;padding-right:20px;margin-bottom: 4px;padding-left:4px;" for="fade"><?php _e('Fade Slider','wi');?></label>
        
        <input type="radio" name="_format_gallery_effect" value="carousel" id="carousel" <?php checked( $effect, "carousel" ); ?>>
		<label style="display:inline-block;padding-right: 20px;margin-bottom: 4px;padding-left:4px;" for="carousel"><?php _e('Carousel','wi');?></label>
        
    </div>
	<?php
}
}
if (!function_exists('wi_format_gallery_save_post')){
function wi_format_gallery_save_post($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_gallery_effect'])) {
		update_post_meta($post_id, '_format_gallery_effect', $_POST['_format_gallery_effect']);
	}
}
}

/* -------------------------------------------------------------------- */
/* CATEGORY FIELD
/* -------------------------------------------------------------------- */
// Add term page
if (!function_exists('wi_taxonomy_add_new_meta_field')) {
function wi_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
    
    $layout_arr = wi_layout_array();
    $layout_arr = array( '' => 'Default' ) + $layout_arr;
    $sidebar_state_arr = wi_sidebar_array();
    $sidebar_state_arr = array( '' => 'Default' ) + $sidebar_state_arr;
    
	?>
	<tr class="form-field">
	   <th scope="row" valign="top"><label for="term_meta[layout]"><?php _e( 'Select layout', 'wi' ); ?></label></th>
		<td>
            <select name="term_meta[layout]" id="term_meta[layout]">
                <?php foreach ($layout_arr as $lay => $out): ?>
                <option value="<?php echo esc_attr($lay);?>"><?php echo esc_html($out);?></option>
                <?php endforeach; ?>
            </select>
			<p class="description"><?php _e( 'Select layout for displaying posts on this category','wi' ); ?></p>
		</td>
	</tr>
    
    <tr class="form-field">
	   <th scope="row" valign="top"><label for="term_meta[sidebar_state]"><?php _e( 'Sidebar layout', 'wi' ); ?></label></th>
		<td>
            <select name="term_meta[sidebar_state]" id="term_meta[sidebar_state]">
                <?php foreach ($sidebar_state_arr as $side => $bar ): ?>
                <option value="<?php echo esc_attr($side);?>"><?php echo esc_html($bar);?></option>
                <?php endforeach; ?>
            </select>
			<p class="description"><?php _e( 'Select sidebar layout for  this category','wi' ); ?></p>
		</td>
	</tr>
<?php
}
}

// Edit term page
if (!function_exists('wi_taxonomy_edit_meta_field')) {
function wi_taxonomy_edit_meta_field($term) {
    
    $layout_arr = wi_layout_array();
    $layout_arr = array( '' => 'Default' ) + $layout_arr;
    $sidebar_state_arr = wi_sidebar_array();
    $sidebar_state_arr = array( '' => 'Default' ) + $sidebar_state_arr;
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_theme_mod( "taxonomy_$t_id" );
    $current_layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';
    $current_sidebar_state = isset($term_meta['sidebar_state']) ? $term_meta['sidebar_state'] : '';
?>
	<tr class="form-field">
	   <th scope="row" valign="top"><label for="term_meta[layout]"><?php _e( 'Select layout', 'wi' ); ?></label></th>
		<td>
            <select name="term_meta[layout]" id="term_meta[layout]">
                <?php foreach ($layout_arr as $lay => $out): ?>
                <option value="<?php echo esc_attr($lay);?>" <?php selected( $lay, $current_layout); ?>><?php echo esc_html($out);?></option>
                <?php endforeach; ?>
            </select>
			<p class="description"><?php _e( 'Select layout for displaying posts on this category','wi' ); ?></p>
		</td>
	</tr>
    
    <tr class="form-field">
	   <th scope="row" valign="top"><label for="term_meta[sidebar_state]"><?php _e( 'Sidebar layout', 'wi' ); ?></label></th>
		<td>
            <select name="term_meta[sidebar_state]" id="term_meta[sidebar_state]">
                <?php foreach ($sidebar_state_arr as $side => $bar ): ?>
                <option value="<?php echo esc_attr($side);?>" <?php selected( $side, $current_sidebar_state); ?>><?php echo esc_html($bar);?></option>
                <?php endforeach; ?>
            </select>
			<p class="description"><?php _e( 'Select sidebar layout for  this category','wi' ); ?></p>
		</td>
	</tr>

<?php
}
}


// Save extra taxonomy fields callback function.
if (!function_exists('save_taxonomy_custom_meta')) {
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_theme_mod( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
}

// Reduce amount of meta value saved to database
// @since 2.4
add_filter( 'baw_count_views_timings', 'wi_baw_count_views_timings' );
function wi_baw_count_views_timings( $timings ) {
    return array( 'all'=>'', 'month'=>'Ym', 'year'=>'Y', 'week' => 'YW' );
}

// Delete all day/week meta keys to save db sizes
// Since 2.4
// Please remove below comment if you wish to delete all day & week meta views
//
// add_action( 'admin_init', 'wi_delete_date_view_keys' );
function wi_delete_date_view_keys() {
    
    if ( get_theme_mod( 'wi_delete_date_view_keys' ) ) return;

    $all_posts = new WP_Query( array(
        'posts_per_page' => -1,
        'post_type' => 'any',
    ) );
    
    $done = false;
    
    global $post;
    
    if ( $all_posts->have_posts() ) : while ( $all_posts->have_posts() ) : $all_posts->the_post();
    
        global $wpdb, $timings;
        $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . (int)$post->ID . ' AND meta_key LIKE "_count-views_day%"' );
        $wpdb->query( 'DELETE FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . (int)$post->ID . ' AND meta_key LIKE "_count-views_week%"' );
    
    endwhile;
    endif;
    
    wp_reset_query();
    
    if ( ! update_option( 'wi_delete_date_view_keys', true ) ) add_option( 'wi_delete_date_view_keys', true );

}