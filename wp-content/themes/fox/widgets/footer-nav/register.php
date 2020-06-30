<?php
/**
 * Footer Nav
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Footer_Nav' ) ) :

add_action( 'widgets_init', 'wi_widget_footer_nav_init' );
function wi_widget_footer_nav_init() {
    
    register_widget( 'Wi_Widget_Footer_Nav' );
    
}

class Wi_Widget_Footer_Nav extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'footer_widget_nav', 
            'description' => 'Displays Footer Menu',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'footer-nav', esc_html__( '(FOOTER) Menu' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/footer-nav/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/footer-nav/widget.php' );
        
	}
	
}

endif;