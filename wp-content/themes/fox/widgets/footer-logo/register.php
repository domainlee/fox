<?php
/**
 * Footer Logo
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Footer_Logo' ) ) :

add_action( 'widgets_init', 'wi_widget_footer_logo_init' );
function wi_widget_footer_logo_init() {
    
    register_widget( 'Wi_Widget_Footer_Logo' );
    
}

class Wi_Widget_Footer_Logo extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_footer_logo', 
            'description' => 'Displays footer logo',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'footer-logo', esc_html__( '(FOOTER) Logo' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/footer-logo/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/footer-logo/widget.php' );
        
	}
	
}

endif;