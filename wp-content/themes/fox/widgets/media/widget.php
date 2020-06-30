<?php
extract( $args );
extract( wp_parse_args( $instance, array(
    'title' => '',
    'code' => '',
) ) );
echo $before_widget;

$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
if ( !empty( $title ) ) {	
    echo $before_title . $title . $after_title;
}

global $wp_embed;
$return = $wp_embed->run_shortcode('[embed]' . $code . '[/embed]');
if ($return):?>
    <div class="media-container">
        <?php echo $return; ?>
    </div><!-- .media-container -->
<?php endif;

echo $after_widget;