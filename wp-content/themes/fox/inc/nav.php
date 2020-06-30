<?php
/**
 * All about menu functions, navigation, primary menu, footer menu
 * Mega menu, adding icons into menu etc
 *
 * Primary Menu rendering can be found in function.header
 * Footer Menu rendering can be found in function.footer
 */
if ( !function_exists( 'wi_nav_menu_css_class' ) ) :
add_filter( 'nav_menu_css_class', 'wi_nav_menu_css_class', 10, 4 );
/**
 * Mega Menu
 * @since 2.0
 */
function wi_nav_menu_css_class( $classes, $item, $args, $depth ) {

    if ( ! $depth && 'primary' === $args->theme_location && get_post_meta( $item->ID, 'menu-item-mega', true ) ) {

        $classes[] = 'mega';
        
        // since 4.0, just a backup class
        $classes[] = 'mega-item';

    }

    return $classes;

}
endif;

if ( !function_exists( 'wi_nav_menu_item_title' ) ) :
add_filter( 'nav_menu_item_title', 'wi_nav_menu_item_title', 10, 4 );
/**
 * Menu Icon
 * @since 2.0
 *
 * @since 4.0, we allow to use an image as icon
 * allow to use feather icon as menu icon
 */
function wi_nav_menu_item_title( $title, $item, $args, $depth ) {

    if ( 'primary' === $args->theme_location && ( $icon = trim( get_post_meta( $item->ID, 'menu-item-menu-icon', true ) ) ) ) {

        $icon_html = '';
        // check if it's an image
        if ( 'http' == strtolower( substr( $icon, 0, 4 ) ) ) {
            $icon_html = '<span class="menu-icon-img"><img src="' . esc_url( $icon ). '" /></span>';
        } else {
            $icon = strtolower( $icon );
            if ( substr( $icon, 0, 7 ) == 'feather' || substr( $icon, 0, 6 ) == 'fa fa-' ) {
                
            } else {
                $icon = 'fa fa-' . $icon;
            }
            $icon_html = '<span class="menu-icon-icon"><i class="' . esc_attr( $icon ) . '"></i></span>';
        }
        
        $title = $icon_html . $title;

    }

    return $title;

}
endif;

add_filter( 'walker_nav_menu_start_el', 'fox_nav_category_mega_markup', 0, 4 );
/**
 * Mark up for mega category menu
 *
 * @since 4.3
 */
function fox_nav_category_mega_markup( $item_output, $item, $depth, $args ) {
    
    if ( ! $depth && 'primary' === $args->theme_location && 'taxonomy' == $item->type && 'category' == $item->object && get_post_meta( $item->ID, 'menu-item-mega', true ) ) {
        
        $markup = '';
        ob_start();
        
        $pseudo_thumbnail_cl = [ 'nav-thumbnail-wrapper' ];
        $std_size = get_theme_mod( 'wi_blog_grid_thumbnail', 'landscape' );
        if ( in_array( $std_size, [ 'landscape', 'square', 'portrait', 'large' ] ) ) {
            $pseudo_thumbnail_cl[] = 'pseudo-thumbnail-' . $std_size;
        }
        
            ?>
            <li class="menu-item post-nav-item">
                    
                <article class="wi-post post-item post-nav-item-inner" itemscope itemtype="https://schema.org/CreativeWork">

                    <div class="<?php echo esc_attr( join( ' ', $pseudo_thumbnail_cl ) ); ?>">
                    
                        <div class="nav-thumbnail-loading">
                            <?php echo fox_loading_element(); ?>
                        </div>
                        
                    </div>
                    
                    <div class="post-nav-item-text">

                    </div><!-- .post-nav-item-text -->

                </article><!-- .post-nav-item-inner -->

            </li><!-- .post-nav-item.menu-item -->
            
        <?php
        
        $li = ob_get_clean();
        
        $markup = $li . "\n" . $li . "\n" . $li;
        $markup = '<ul class="sub-menu submenu-display-items">' . $markup . '<span class="caret"></span></ul>';
        
        $item_output .= $markup;
        
    }
    
    return $item_output;
    
}