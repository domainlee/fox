<?php
/**
 * Coronavirus Widget to display in Fox
 * @since 4.4
 * v1.0
 */

if ( !class_exists( 'Wi_Widget_Coronavirus' ) ) :

add_action( 'widgets_init', 'wi_widget_coronavirus_init' );
function wi_widget_coronavirus_init() {
    register_widget( 'Wi_Widget_Coronavirus' );
}

class Wi_Widget_Coronavirus extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_coronavirus', 
            'description' => esc_html__( 'Coronavirus Data Update','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'coronavirus', esc_html__( '(FOX) Coronavirus' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/coronavirus/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/coronavirus/widget.php' );
        
	}
	
}

endif;