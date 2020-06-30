<?php
/**
 * Site Nav
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Nav' ) ) :

add_action( 'widgets_init', 'wi_widget_nav_init' );
function wi_widget_nav_init() {
    
    register_widget( 'Wi_Widget_Nav' );
    
}

class Wi_Widget_Nav extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_nav', 
            'description' => 'Displays primary menu',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'nav', esc_html__( '(HEADER) Navigation' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/header-nav/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/header-nav/widget.php' );
        
	}
	
}

endif;