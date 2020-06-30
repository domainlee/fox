<?php
/**
 * Author list
 * @since 2.0
 */

if ( !class_exists( 'Wi_Widget_Authorlist' ) ) :

add_action( 'widgets_init', 'wi_widget_authorlist_init' );
function wi_widget_authorlist_init() {
    register_widget( 'Wi_Widget_Authorlist' );
}

class Wi_Widget_Authorlist extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
        
		$widget_ops = array(
            'classname' => 'widget_authorlist', 
            'description' => esc_html__( 'Displays authorlist','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'wi-authorlist', esc_html__( '(FOX) Authorlist' , 'wi' ), $widget_ops, $control_ops );
        
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/authorlist/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/authorlist/widget.php' );
        
	}
	
}

endif;