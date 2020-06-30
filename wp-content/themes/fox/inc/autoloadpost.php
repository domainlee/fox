<?php
/**
 * Check if auto load post module enabled
 * @since 4.0
 */
function fox_autoload() {
    
    if ( ! is_single() ) return false;
    
    // a mechanism to disable autoload feature on some particle post
    $get = get_post_meta( get_the_ID(), '_wi_autoload', true );
    if ( 'false' == $get ) return false;
    if ( 'true' == $get ) return true;
    
    $get = get_theme_mod( 'wi_autoload_post', 'false' );
        
    return (bool) ( 'true' === $get );
    
}

add_action( 'fox_single_bottom', 'fox_autoload_single_nav' );

/**
 * add nav for autoload
 */
function fox_autoload_single_nav() { 
    if ( ! fox_autoload() ) return;
?>
<div class="autoload-nav">
    
    <div class="container">
        
        <?php the_post_navigation([
        'in_same_term' => ( 'true' == get_theme_mod( 'wi_autoload_post_nav_same_term', 'false' ) ),
        ]); ?>
        
    </div><!-- .container -->
    
</div><!-- .autoload-nav -->
<?php
}

/** 
*  Add the endpoint for the call to get the post html only
**/

function fox_alnp_add_endpoint() {
    add_rewrite_endpoint( 'partial', EP_PERMALINK );
}

add_action( 'init', 'fox_alnp_add_endpoint' );

/**
* When /partial endpoint is used on a post, get just the post html
**/
function fox_alnp_template_redirect() {
    global $wp_query;
 
    // if this is not a request for partial or a singular object then bail
    if ( ! isset( $wp_query->query_vars['partial'] ) || ! is_singular() )
        return;
 
	// include custom template
    include get_parent_theme_file_path( '/content-partial.php' );

    exit;
}

add_action( 'template_redirect', 'fox_alnp_template_redirect' );

function partial_endpoints_activate() {

    // ensure our endpoint is added before flushing rewrite rules
    fox_alnp_add_endpoint();
    
    // flush rewrite rules - only do this on activation as anything more frequent is bad!
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'partial_endpoints_activate' );
 

function partial_endpoints_deactivate() {
    // flush rules on deactivate as well so they're not left hanging around uselessly
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'partial_endpoints_deactivate' );