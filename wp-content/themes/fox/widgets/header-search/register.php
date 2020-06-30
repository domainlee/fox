<?php
/**
 * Site Header_Search
 * since 4.0
 */
if ( !class_exists( 'Wi_Widget_Header_Search' ) ) :

add_action( 'widgets_init', 'wi_widget_header_search_init' );
function wi_widget_header_search_init() {
    
    register_widget( 'Wi_Widget_Header_Search' );
    
}

class Wi_Widget_Header_Search extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_header_search', 
            'description' => 'Displays header search',
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'header-search', esc_html__( '(HEADER) Search' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/header-search/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/header-search/widget.php' );
        
	}
	
}

endif;