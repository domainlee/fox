<?php
/**
 * Pinterest
 *
 * @package FOX
 * @since 2.8
 */

if ( !class_exists( 'Wi_Widget_Pinterest' ) ) :

add_action( 'widgets_init', 'wi_register_widget_pinterest' );

function wi_register_widget_pinterest() {

    register_widget( 'Wi_Widget_Pinterest' );

}

class Wi_Widget_Pinterest extends Wi_Widget {
	
    // initialize the widget
	function __construct() {
		$widget_ops = array(
            'classname' => 'widget_pinterest', 
            'description' => esc_html__( 'Recent Pins','wi' )
        );
		$control_ops = array('width' => 250, 'height' => 350);
		parent::__construct( 'wi-pinterest', esc_html__( '(FOX) Pinterest' , 'wi' ), $widget_ops, $control_ops );
	}
    
    // register fields
    function fields() {
        
        include get_theme_file_path( '/widgets/pinterest/fields.php' );
        return $fields;
        
    }
	
    // render it to frontend
	function widget( $args, $instance) {
        
        include get_theme_file_path( '/widgets/pinterest/widget.php' );
        
	}
    
    /**
     * Get Pins Feed
     *
     * @since 1.0
     */
    function get_pins_feed_list( $username, $boardname, $maxfeeds = 6 ) {
        
        // Get Pinterest Feed(s)
		include_once(ABSPATH . WPINC . '/feed.php');
        
        if( empty($boardname) ){
            $pinsfeed = 'https://pinterest.com/'.$username.'/feed.rss';
        } else {
            $pinsfeed = 'https://pinterest.com/'.$username.'/'.$boardname.'.rss';
        }
        
        // Get a SimplePie feed object from the Pinterest feed source
        $rss = fetch_feed($pinsfeed);
        if( $rss instanceof WP_Error ) return '';
        $rss->set_timeout(60);
        
        echo $maxfeeds;

        // Figure out how many total items there are.               
        $maxitems = $rss->get_item_quantity((int)$maxfeeds);
        
        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0,$maxitems);

        foreach ( $rss_items as $item ) { ?>

<li class="pin-item">

    <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank" title="<?php echo esc_attr( $item->get_title() ); ?>">

        <?php
            if ( $thumb = $item->get_item_tags( SIMPLEPIE_NAMESPACE_MEDIARSS, 'thumbnail' ) ) {
                $thumb = $thumb[0]['attribs']['']['url'];											
                echo '<img src="'. esc_url( $thumb ).'" alt="'.$item->get_title().'"/>'; 
            }  else {
                preg_match('/src="([^"]*)"/', $item->get_content(), $matches);
                $src = $matches[1];

                if ($matches) {
                  echo '<img src="'.$src.'" alt="'.$item->get_title().'"/>';
                } else {
                  echo "thumbnail not available";
                }
            }
        ?>

    </a>
                
</li>

<?php
          } // foreach
    
    }
	
}

endif;