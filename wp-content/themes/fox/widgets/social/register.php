<?php
/**
 * Social
 */

if ( !class_exists( 'Wi_Widget_Social' ) ) :

add_action( 'widgets_init', 'wi_widget_social_init' );
function wi_widget_social_init() {
    register_widget( 'Wi_Widget_Social' );
}

class Wi_Widget_Social extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_social', 
            'description' => esc_html__( 'Social profile','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'social', esc_html__( '(FOX) Social' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/social/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/social/widget.php' );
        
	}
	
}

endif;