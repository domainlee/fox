<?php
/**
 * Imagebox
 */
if ( !class_exists( 'Wi_Widget_Imagebox' ) ) :

add_action( 'widgets_init', 'wi_widget_imagebox_init' );
function wi_widget_imagebox_init() {
    register_widget( 'Wi_Widget_Imagebox' );
}

class Wi_Widget_Imagebox extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_imagebox', 
            'description' => 'Imagebox',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'imagebox', esc_html__( '(FOX) Imagebox' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/imagebox/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/imagebox/widget.php' );
        
	}
	
}

endif;