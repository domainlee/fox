<?php
/**
 * Best Rated
 */

if ( !class_exists( 'Wi_Widget_Best_Rated' ) ) :

add_action( 'widgets_init', 'wi_widget_best_rated_init' );
function wi_widget_best_rated_init() {
    register_widget( 'Wi_Widget_Best_Rated' );
}

class Wi_Widget_Best_Rated extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_best_rated', 
            'description' => esc_html__( 'Best Rated','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'best-rated', esc_html__( 'Best Rated' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/best-rated/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/best-rated/widget.php' );
        
	}
	
}

endif;