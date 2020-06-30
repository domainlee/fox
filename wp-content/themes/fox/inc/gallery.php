<?php
if ( ! function_exists( 'fox_gallery' ) ) :
/**
 * Fox Gallery
 * Displays a gallery from some image source with few styles
 * image source can be: uploaded images, instagram, pinterest etc
 *
 * @since 4.0
 */
function fox_gallery( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        // array of IDs
        'images' => [],
        
        // gallery style
        'style' => 'metro',
        
        // flexslider
        'effect'    => 'fade',
        'size'      => 'original',
        
        // grid, masonry
        'column' => '',
        'item_spacing' => '',
        
        'extra_class' => '',
        'extra_outer_class' => '',
        
        'caption_class' => '',
        'lightbox' => false,
        
    ]) );
    
    if ( empty( $images ) ) return;
    if ( ! is_array( $images ) ) return;
    
    $class = [
        'fox-gallery',
        'fox-gallery-' . $style
    ];
    
    if ( 'slider' == $style ) {
        $class[] = 'style--slider-navarrow';
    }
    
    $outer_class = [ 'fox-gallery-container' ];
    
    if ( $extra_outer_class ) {
        $outer_class[] = $extra_outer_class;
    }
    
    if ( $extra_class ) {
        $class[] = $extra_class;
    }
    
    if ( $lightbox ) {
        $class[] = 'fox-lightbox-gallery';
    }
    
    switch( $style ) :
    
    /* ----------------------------     FLEXSLIDER          ---------------------------- */
    case 'slider':
    
    $class[] = 'fox-flexslider';
    $class[] = 'wi-flexslider';
    
    if ( 'slide' != $effect ) $effect = 'fade';
    
    $options = [
        'animation'    => $effect,
        'slideshow'  => true,
        'animationSpeed' => 1000,
        'slideshowSpeed' =>	5000,
        'easing' => 'easeOutCubic',
        
        'prevText' => '<i class="fa fa-angle-left"></i>',
        'nextText' => '<i class="fa fa-angle-right"></i>',
    ];
    
    if ( 'slide' == $effect ) {
        $options[ 'animationSpeed' ] = 600;
    }
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $options ); ?>'>
    
    <div class="flexslider">
        
        <ul class="slides">
        
            <?php foreach ( $images as $image_id ) :
            $image_args = [
                'id' => $image_id,
                'link' => is_single() ? '' : 'single',
                'disable_lazyload' => true,
                'figure_class' => 'slide',
                'caption_class' => 'slide-caption'
            ];
    
            if ( 'custom' == $args[ 'thumbnail' ] ) {
                $image_args[ 'thumbnail' ] = 'custom';
                $image_args[ 'thumbnail_custom' ] = $args[ 'thumbnail_custom' ];
            } else {
                $image_args[ 'thumbnail' ] = $args[ 'thumbnail' ];
            }
            ?>
            
            <li class="li-slide">
                
                <?php fox_image( $image_args ); ?>
            
            </li><!-- .li-slide -->
            
            <?php endforeach; ?>
        
        </ul>
        
    </div><!-- .flexslider -->

</div><!-- .fox-gallery -->

<?php
    
    break;
    
    /* ----------------------------     CAROUSEL          ---------------------------- */
    case 'carousel':
    $class[] = 'wi-carousel';
    $class[] = 'fox-carousel';
    
    $options = [];
    
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $options ); ?>'>

    <div class="wi-slick fox-slick">
        
        <?php foreach ( $images as $image_id ) : 
    
                if ( ! get_post( $image_id ) ) {
                    continue;
                }
    
            $image_args = [
                'id' => $image_id,
                'link' => $lightbox ? 'lightbox' : '',
                'disable_lazyload' => true,
                'figure_class' => 'slide',
                'caption_class' => 'slide-caption'
            ]; ?>
            
        <div class="carousel-item">
            
            <?php fox_image( $image_args ); ?>
            
        </div><!-- .carousel-item -->

        <?php endforeach; ?>

    </div><!-- .wi-slick -->

</div><!-- .wi-carousel -->
    
    
    <?php break;
    
    /* ----------------------------     STACK          ---------------------------- */
    case 'stack':
    $class[] = 'fox-stack-images';
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

    <?php foreach ( $images as $image_id ) :
        $image_args = [
            'id' => $image_id,
            'link' => $lightbox ? 'lightbox' : '',
        ];
    ?>

    <?php fox_image( $image_args ); ?>

    <?php endforeach; ?>

</div><!-- .fox-stack-images -->
    
    <?php break;
    
    /* ----------------------------     GRID          ---------------------------- */
    case 'grid':
    
    $outer_class[] = 'fox-grid-container';
    $class[] = 'fox-grid fox-grid-gallery';
    
    $column = absint( $column );
    if ( $column < 1 || $column > 5 ) $column = 3;
    
    $class[] = 'column-' . $column;
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $outer_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php foreach ( $images as $image_id ) : $image_args = [
                'id' => $image_id,
                'link' => $lightbox ? 'lightbox' : '',
                'figure_class' => 'fox-grid-item-main slide',
                'caption_class' => 'slide-caption',
                'thumbnail' => fox_single_option( 'format_gallery_grid_size' ),
                'thumbnail_custom' => fox_single_option( 'format_gallery_grid_size_custom' ),
            ];
        ?>

        <div class="fox-grid-item grid-gallery-item fox-gallery-item">

            <?php fox_image( $image_args ); ?>

        </div><!-- .grid-gallery-item -->

        <?php endforeach; ?>

    </div><!-- .fox-grid-gallery -->
    
</div><!-- .fox-grid-container -->
    
    <?php break;
    
    /* ----------------------------     MASONRY          ---------------------------- */
    case 'masonry':
    
    $outer_class[] = 'fox-grid-container';
    
    $class[] = 'fox-grid fox-masonry-gallery';
    $class[] = 'fox-masonry';
    
    $column = absint( $column );
    if ( $column < 1 || $column > 5 ) $column = 3;
    if ( $column < 3 ) $thumbnail = 'large';
    else $thumbnail = 'medium';
    
    $class[] = 'column-' . $column;
    
    ?>

<div class="<?php echo esc_attr( join( ' ', $outer_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
        
        <?php foreach ( $images as $image_id ) : $image_args = [
                'id' => $image_id,
                'link' => $lightbox ? 'lightbox' : '',
                'figure_class' => 'fox-grid-item-main slide masonry-animation-element',
                'caption_class' => 'slide-caption',
                'thumbnail' => $thumbnail,
            ];
        ?>

        <div class="fox-grid-item fox-masonry-item masonry-gallery-item fox-gallery-item">

            <?php fox_image( $image_args ); ?>

        </div><!-- .masonry-gallery-item -->

        <?php endforeach; ?>
        
        <div class="grid-sizer fox-grid-item"></div>

    </div><!-- .fox-grid-gallery -->
    
</div><!-- .fox-grid-container -->
    
    <?php break;
    
    /* ----------------------------     METRO          ---------------------------- */
    case 'metro':

    $class[] = 'fox-metro fox-grid fox-metro-gallery';
    
    $outer_class[] = 'fox-metro-container';
    $outer_class[] = 'fox-grid-container';
  
    $count = 0;
?>
    
<div class="<?php echo esc_attr( join( ' ', $outer_class ) ); ?>">
    
    <div class="<?php echo esc_attr( join( ' ', $class ) ); ?>">

        <?php foreach ( $images as $image_id ) : $count++;

        if ( $count % 3 == 1 ) {
            $size = '600x600';
        } else {
            $size = '320x320';
        } 
    
        $image_args = [
            'id' => $image_id,
            'link' => $lightbox ? 'lightbox' : '',
            'figure_class' => 'fox-grid-item-main slide',
            'caption_class' => 'slide-caption',
            'thumbnail' => 'custom',
            'thumbnail_custom' => $size,
        ]; ?>

        <div class="fox-metro-item fox-gallery-item fox-grid-item">

            <?php fox_image( $image_args ); ?>

        </div><!-- .fox-metro-item -->

        <?php endforeach; ?>

    </div><!-- .fox-metro-gallery -->
    
</div><!-- .fox-metro-container -->

<?php break; ?>

<?php
    /* ----------------------------     SLIDER RICH          ---------------------------- */
    case 'slider-rich':
    
    $class[] = 'wi-flexslider';
    $class[] = 'fox-flexslider';
    $class[] = 'fox-slider-rich';
    $class[] = 'style--slider-navarrow';
    
    $options = [
        'animation'         => 'fade',
        'animationSpeed'    => 100,
        'smoothHeight' => false,
        'slideshow'     => false,
    ];
    
    $args[ 'show_caption' ] = false;
    
?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" data-options='<?php echo json_encode( $options ); ?>'>
    
    <div class="flexslider">
        
        <ul class="slides">
        
            <?php foreach ( $images as $image_id ) : 
            
                $attachment = get_post( $image_id );
                if ( ! $attachment ) continue;

                $title = $attachment->post_title;
                $description = do_shortcode( $attachment->post_content );

            ?>
            
            <li class="slide">
                
                <div class="item-rich">
                    
                    <?php
                    $image_args = [
                        'id' => $image_id,
                        'caption' => false,
                        'thumbnail' => 'large',
                        'disable_lazyload' => true,
                    ];
                    fox_image( $image_args ); ?>
                    
                    <div class="item-rich-body">
                        
                        <?php if ( $title ) { ?>
                        <h3 class="item-rich-title" itemprop="headline"><?php echo esc_html( $title ); ?></h3>
                        <?php } ?>
                        
                        <?php if ( $description ) { ?>
                        <div class="item-rich-content">
                            <?php echo $description; ?>
                        </div>
                        <?php } ?>
                    
                    </div><!-- .item-rich-body -->
                    
                </div><!-- .item-slide-rich -->
            
            </li><!-- .slide -->
            
            <?php endforeach; ?>
        
        </ul>
    
    </div><!-- .flexslider -->
    
    <div class="rich-height-element"></div>
    
</div><!-- .fox-slider-rich -->

<?php
    
    break;
    
    /* ----------------------------     DEFAULT          ---------------------------- */
    default:
    break;
    
    endswitch; // switch
    
}
endif;