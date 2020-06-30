<?php
/**
 * About
 */

if ( !class_exists( 'Wi_Widget_About' ) ) :

add_action( 'widgets_init', 'wi_widget_about_init' );
function wi_widget_about_init() {
    register_widget( 'Wi_Widget_About' );
}

class Wi_Widget_About extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_about', 
            'description' => esc_html__( 'About','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'about', esc_html__( '(FOX) About' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/about/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/about/widget.php' );
        
	}
	
}

endif;