<?php
/**
 * Button
 * @since 4.0
 */

if ( !class_exists( 'Wi_Widget_Button' ) ) :

add_action( 'widgets_init', 'wi_widget_button_init' );
function wi_widget_button_init() {
    register_widget( 'Wi_Widget_Button' );
}

class Wi_Widget_Button extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
        
		$widget_ops = array(
            'classname' => 'widget_button', 
            'description' => esc_html__( 'Displays button','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'wi-button', esc_html__( '(FOX) Button' , 'wi' ), $widget_ops, $control_ops );
        
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/button/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/button/widget.php' );
        
	}
	
}

endif;