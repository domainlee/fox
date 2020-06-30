<?php
/**
 * Media
 */

if ( !class_exists( 'Wi_Widget_Media' ) ) :

add_action( 'widgets_init', 'wi_widget_media_init' );
function wi_widget_media_init() {
    register_widget( 'Wi_Widget_Media' );
}

class Wi_Widget_Media extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_media', 
            'description' => esc_html__( 'Display video/audio','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'media', esc_html__( 'Video/Audio' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        include get_theme_file_path( '/widgets/media/fields.php' );
        return $fields;
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/media/widget.php' );
        
	}
	
}

endif;