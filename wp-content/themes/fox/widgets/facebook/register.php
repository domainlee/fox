<?php
/**
 * Facebook
 */

if ( !class_exists( 'Wi_Widget_Facebook' ) ) :

add_action( 'widgets_init', 'wi_widget_facebook_init' );
function wi_widget_facebook_init() {
    register_widget( 'Wi_Widget_Facebook' );
}

class Wi_Widget_Facebook extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_facebook', 
            'description' => esc_html__( 'Display video/audio','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'facebook', esc_html__( '(FOX) Facebook Likebox' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/facebook/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/facebook/widget.php' );
        
	}
	
}

endif;