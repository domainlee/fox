<?php
/**
 * Latest Posts
 */
if ( !class_exists( 'Wi_Widget_Latest_Posts' ) ) :

add_action( 'widgets_init', 'wi_widget_latest_posts_init' );
function wi_widget_latest_posts_init() {
    register_widget( 'Wi_Widget_Latest_Posts' );
}

class Wi_Widget_Latest_Posts extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_latest_posts', 
            'description' => esc_html__( 'Post List','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'latest-posts', esc_html__( '(FOX) Post List' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/latest-posts/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path ( '/widgets/latest-posts/widget.php' );
        
	}
	
}

endif;