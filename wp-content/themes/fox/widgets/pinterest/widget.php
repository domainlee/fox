<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'username' => 'pinterest',
    'boardname' => '',
    'maxfeeds' => 6,
    'follow' => 'Follow Us',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

if ( empty( $username ) ) return;

?>

<div class="wi-widget-pinterest">

    <ul class="wi-pin-list">
        
        <?php $this->get_pins_feed_list( $username, $boardname, $maxfeeds ); ?>
        
        <li class="grid-sizer"></li>
    
    </ul>
    
    <?php if ( $follow ) { ?>
    
    <div class="widget-pin-follow fox-button button-block button-block-full">
        
        <a href="<?php echo esc_url( 'https://pinterest.com/' . $username ) ; ?>" target="_blank" class="fox-btn btn-primary btn-small">
            <?php echo esc_html( $follow ); ?>
            <i class="fab fa-pinterest-p"></i>
        </a>
        
    </div>
    
    <?php } ?>

</div><!-- .wi-widget-pinterest -->

<?php echo $after_widget;