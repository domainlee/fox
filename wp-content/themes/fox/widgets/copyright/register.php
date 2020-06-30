<?php
/**
 * Footer Copyright
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Footer_Copyright' ) ) :

add_action( 'widgets_init', 'wi_widget_copyright_init' );
function wi_widget_copyright_init() {
    
    register_widget( 'Wi_Widget_Footer_Copyright' );
    
}

class Wi_Widget_Footer_Copyright extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_copyright', 
            'description' => 'Displays copyright text',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'copyright', esc_html__( '(FOOTER) Copyright' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/copyright/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/copyright/widget.php' );
        
	}
	
}

endif;