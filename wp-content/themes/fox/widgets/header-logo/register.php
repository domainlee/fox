<?php
/**
 * Site Logo
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Logo' ) ) :

add_action( 'widgets_init', 'wi_widget_logo_init' );
function wi_widget_logo_init() {
    
    register_widget( 'Wi_Widget_Logo' );
    
}

class Wi_Widget_Logo extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_logo', 
            'description' => 'Displays site logo',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'logo', esc_html__( '(HEADER) Logo' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/header-logo/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/header-logo/widget.php' );
        
	}
	
}

endif;