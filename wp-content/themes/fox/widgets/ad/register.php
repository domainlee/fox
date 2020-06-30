<?php
/**
 * Ad
 */

if ( !class_exists( 'Wi_Widget_Ad' ) ) :

add_action( 'widgets_init', 'wi_widget_ad_init' );
function wi_widget_ad_init() {
    register_widget( 'Wi_Widget_Ad' );
}

class Wi_Widget_Ad extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_ad', 
            'description' => esc_html__( 'Ad','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'ads', esc_html__( '(FOX) Ad' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/ad/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/ad/widget.php' );
        
	}
	
}

endif;