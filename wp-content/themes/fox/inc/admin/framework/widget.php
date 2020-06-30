<?php

// Widget
// This Class makes process of building a widget much more easier
// =================================================================

if ( !class_exists ( 'Wi_Widget' ) ) :

class Wi_Widget extends WP_Widget
{
    // placeholder function
    function fields() {
        return array();
    }
    
    function default_form_args() {
        $defaults = array(
            'id' => '',
            'type' => '',
            'multiple' => false, // for select
            'name' => '',
            'desc' => '',
            'fields' => array(), // for group
            'placeholder' => '',
            'std' => null,
            'options' => array(),
        );
        return $defaults;
    }
    
    /**
     * Update widget
     * All widgets updated in a same way -_-
     *
     * @since 1.0
     * @return $instance
     */
    function update ( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        foreach ( (array) $this->fields() as $field ) {
            if ( !isset($field['type']) ) {
                continue;
            }
            $type = $field['type'];
            if ( !isset( $field['id']) && $type != 'group' ) {
                continue;
            }
            
            if ( $type != 'group' ) {
                $instance[ $field['id'] ] = $this->update_field( $new_instance, $field['id'], $type );
            } else {
                foreach ( (array) $field['fields'] as $subfield ) {
                    $instance[ $subfield['id'] ] = $this->update_field( $new_instance, $subfield['id'], $subfield['type'] );   
                }
            }
        }
        
        return $instance;
	}
    
    /**
     * Used to update widget
     *
     * @since 1.0
     */
    function update_field( $new_instance, $key, $type ) {
        $return = null;
        if ( $type == 'text' ) {
            $return = isset( $new_instance[ $key ] ) ? sanitize_text_field ( $new_instance[ $key ] ) : '';
        } elseif ( $type == 'checkbox' ) {
            $return = isset( $new_instance[ $key ] ) && !empty($new_instance[ $key ]) ? 1 : 0;
        } elseif ( $type == 'image_select' || $type == 'radio' ) {
            $return = isset( $new_instance[ $key ] ) ? $new_instance[ $key ] : null;
        } elseif ( $type == 'select' ) {
            $return = isset( $new_instance[ $key ] ) ?  $new_instance[ $key ] : null;
        } else {
            $return = isset( $new_instance[ $key ] ) ?  $new_instance[ $key ] : null;
        }
        return $return;
    }
    
    /**
     * Render Form
     * All widgets have the same form structures
     *
     * @since 1.0
     * @return void
     */
    function form( $instance ) {
        
        $this->display_form( $this->fields(), $instance );
        
	}
    
    /**
     * Display the form base on fields
     * This function eases the building process
     *
     * @since 1.0
     * @return void
     */
    function display_form( $fields = array(), $instance = array() ) {
        if ( !array($fields) || empty($fields) ) {
            return;
        }
        
        foreach ( $fields as $key => $field ) {
            if (!isset($field[ 'std' ]) ) { 
                $field[ 'std' ] = null;
            }
            $fields[ $key ]  = $field;
        }
        
        foreach ( $fields as $field ) {
            if ( !isset( $field['type'] ) ) {
                continue;
            }
            
            // group is special
            if ( $field['type'] == 'group' ) {
                
                $field = wp_parse_args( $field, $this->default_form_args() );
                
                $this->wi_form_group( $field, $instance );
                continue;
            }
            // other types
            $this->render_field( $field, $instance );
        }
        
    }
    
    /**
     * Render field pass arg $instanace
     *
     * @since 1.0
     * @return void
     */
    function render_field( $field = array(), $instance = array() ) {
        $type = $field['type'];
        $field = wp_parse_args( $field, $this->default_form_args() );
        $method = 'wi_form_' . $type;

        if ( method_exists( __CLASS__ , $method ) ) {
            // get value
            if ( $type == 'text' ) {
                $value = isset( $instance[ $field['id'] ] ) ? sanitize_text_field( $instance[ $field['id'] ] ) : $field['std'];
            } elseif ( $type == 'checkbox' ) {
                $value = isset( $instance[ $field['id'] ] ) ? (bool) $instance[ $field['id'] ] : $field['std'];
            } else {
                $value = isset( $instance[ $field['id'] ] ) ? $instance[ $field['id'] ] : $field['std'];
            }
            
            ob_start();
            
            $this->$method( $field, $value );
            
            echo ob_get_clean();
            
        }
    }
    
    /**
     * Type: Heading
     *
     * @since 1.0
     * @return void
     */
    function wi_form_heading( $field = array(), $value = null ) {
?>
    <h4 class="wi-widget-heading"><?php echo esc_html( $field['name'] ); ?></h4>
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif;
    }
    
    /**
     * Type: Group
     * To display group of options
     *
     * @since 1.0
     * @return void
     */
    function wi_form_group( $field = array(), $instance = array() ) {
?>
<div class="widget widget-group open" id="group-<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>">
    <div class="widget-top">
        <div class="widget-title-action">
            <a class="widget-action hide-if-no-js" href="#available-widgets"></a>
            <a class="widget-control-edit hide-if-js" href="widgets.php?editwidget=group-<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>">
                <span class="edit"><?php esc_html_e('Edit','wi');?></span>
                <span class="add"><?php esc_html_e('Add','wi');?></span>
                <span class="screen-reader-text"><?php echo esc_html( $field['name'] ); ?></span>
            </a>
        </div>
        <div class="widget-title">
            <h3><?php echo esc_html( $field['name'] ); ?></h3>
        </div>
    </div>
    <div class="widget-inside" style="display:block;">
        
        <?php if ( $field['desc'] ) : ?>
        <small class="widget-group-content-desc"><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
        <?php endif; ?>
        
        <?php foreach ( (array) $field['fields'] as $subfield ) {
            $this->render_field( $subfield, $instance );
        } // endforeach ?>
    </div><!-- .widget-inside -->
</div><!-- .wi-widget-group -->
<?php
    }
    
    /**
     * Type: Text
     *
     * @since 1.0
     * @return void
     */
    function wi_form_text( $field = array(), $value = null ) {
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?>" type="text" value="<?php echo esc_attr( $value ); ?>"<?php if ( isset($field['placeholder']) ) echo ' placeholder="' . esc_attr( $field['placeholder'] ) . '"';?> />
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif; ?>
</p>
     <?php   
    }
    
    /**
     * Type: Colorpicker
     *
     * @since 1.0
     * @return void
     */
    function wi_form_color( $field = array(), $value = null ) {
?>
<div class="wi-colorpicker-wrapper">
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    <input class="widefat wi-colorpicker" id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif; ?>
</div>
     <?php   
    }
    
    /**
     * Type: Textarea
     *
     * @since 1.0
     * @return void
     */
    function wi_form_textarea( $field = array(), $value = null ) {
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?>" rows="4"><?php echo esc_attr( $value ); ?></textarea>
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif; ?>
</p>
     <?php   
    }
    
    /**
     * Type: Select
     *
     * @since 1.0
     * @return void
     */
    function wi_form_select( $field = array(), $value = null ) {
        $multiple = $field['multiple'] ? ' multiple' : '';
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    
    <select class="widefat" id="<?php echo esc_attr($this->get_field_id( $field['id'] )); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?><?php if ( $field['multiple'] ) echo '[]';?>"<?php echo $multiple;?>>
        <?php foreach ( (array) $field['options'] as $key => $val ): ?>
        
        <?php if ( $field['multiple'] ) : ?>
        
        <option value="<?php echo esc_attr($key);?>" <?php if (in_array( $key, (array) $value) ) echo ' selected="selected"';?>><?php echo esc_html($val); ?></option>
        
        <?php else: ?>
        
        <option value="<?php echo esc_attr($key);?>" <?php selected($value,$key) ?>><?php echo esc_html($val); ?></option>
        
        <?php endif; ?>
        
        <?php endforeach; ?>
        
    </select>
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif; ?>
</p>
<?php   
    }
    
    /**
     * Type: Checkbox
     *
     * @since 1.0
     * @return void
     */
    function wi_form_checkbox( $field = array(), $value = false ) {
?>
<p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( $field['id'] ); ?>" name="<?php echo $this->get_field_name( $field['id'] ); ?>"<?php checked( $value ); ?> />
    <label for="<?php echo $this->get_field_id( $field['id']); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    <?php if ( $field['desc'] ) { ?>
    <br><small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php } ?>
</p>
<?php }
    
    /**
     * Type: Radio
     *
     * @since 1.0
     * @return void
     */
    function wi_form_radio( $field = array(), $value = false ) {
?>
<p>
    
    <span class="option-name"><?php echo esc_html( $field['name'] ); ?></span>
    
    <?php foreach ( (array) $field['options'] as $key => $val ): ?>
    
    <input type="radio" class="radio" id="<?php echo $this->get_field_id( $field['id'] . '-' . $key ); ?>" name="<?php echo $this->get_field_name( $field['id'] ); ?>" value="<?php echo esc_attr($key);?>"<?php checked( $value, $key ); ?> />
    <label for="<?php echo $this->get_field_id( $field['id'] . '-' . $key ); ?>"><?php echo esc_html( $val ); ?></label>
    
    <?php endforeach; ?>
    
    <?php if ( $field['desc'] ) { ?>
    <br><small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php } ?>
    
</p>
<?php }
    
    /**
     * Type: Image Select
     * Its natural intrinsic is radio input
     *
     * @since 1.0
     * @return void
     */
    function wi_form_image_select( $field = array(), $value = false ) {
?>
<p class="wi-image-select">
    
    <span class="option-name"><?php echo esc_html( $field['name'] ); ?></span>
    
    <?php foreach ( (array) $field['options'] as $key => $val ): ?>
    <?php $val = wp_parse_args($val, array('src'=>'','name' =>'')); ?>
    
    <input type="radio" class="radio" id="<?php echo $this->get_field_id( $field['id'] . '-' . $key ); ?>" name="<?php echo $this->get_field_name( $field['id'] ); ?>" value="<?php echo esc_attr($key);?>"<?php checked( $value, $key ); ?> />
    
    <label for="<?php echo $this->get_field_id( $field['id'] . '-' . $key ); ?>">
        <img src="<?php echo esc_url($val['src']);?>" alt="<?php echo esc_attr($val['name']);?>" />
        <span><?php echo esc_html($val['name']);?></span>
    </label>
    
    <?php endforeach; ?>
    
    <?php if ( $field['desc'] ) { ?>
    <br><small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php } ?>
    
</p><!-- .wi-image-select -->
<?php }
    
    
    /**
     * Type: Image Upload
     * WordPress Media Uploader
     *
     * @since 1.0
     * @return void
     */
    function wi_form_image( $field = array(), $value = false ) {
        $image = ( ! empty($value) && is_numeric( $value ) ) ? wp_get_attachment_image_src($value,'medium') : $value;
        if ( is_array( $image ) ) $image = $image[0]; 
        $upload_button_name = $image ? esc_html__('Change Image','wi') : esc_html__('Upload Image','wi');
?>

<div style="margin:0 0 10px">
    
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>

    <div class="wi-upload-wrapper">

        <figure class="image-holder">

            <?php if ( $image ) : ?>
            <img src="<?php echo esc_url( $image );?>" />
            <?php endif; ?>

            <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

        </figure>

        <input type="hidden" class="media-result" id="<?php echo $this->get_field_id( $field['id'] ); ?>" name="<?php echo $this->get_field_name( $field['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        <input id="<?php echo $this->get_field_id( $field['id'] . '-upload' ); ?>" type="button" class="upload-image-button button" value="<?php echo $upload_button_name;?>" />

    </div>
    
</div>
    
<?php }
    
    /**
     * Type: Images Upload
     * WordPress Media Uploader
     * Allow upload multiple images
     *
     * @since 1.0
     * @return void
     */
    function wi_form_images( $field = array(), $value = false ) {
        
        $images = $value;
        if ( ! is_array( $images ) ) {
            $images = explode( ',', $images );
            $images = array_map( 'absint', $images );
        }
        
        ?>
<div class="wi-upload-wrapper multiple">
    
    <div class="wi-upload-wrapper">
            
        <div class="images-holder">

            <?php foreach ( $images as $image ) { 
                $image_html = wp_get_attachment_image( $image, 'thumbnail' );
                if ( !$image_html ) {
                    continue;
                }
            ?>

            <figure class="image-unit" data-id="<?php echo esc_attr( $image ); ?>">

                <?php echo $image_html; ?>

                <a href="#" rel="nofollow" class="remove-image-unit" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

            </figure><!-- .image-unit -->

            <?php } // end foreach ?>

        </div><!-- .images-holder -->

        <input type="hidden" class="media-result" id="<?php echo $this->get_field_id( $field['id'] ); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?>" value="<?php echo esc_attr( $value ); ?>" />

        <input type="button" class="upload-images-button button button-primary" value="<?php echo esc_html__( 'Upload Images', 'wi' ) ;?>" />

    </div><!-- .wi-upload-wrapper -->
    
</div>
<?php }
    
    
    /**
     * Type: Date
     *
     * @since 1.0
     * @return void
     */
    function wi_form_date( $field = array(), $value = null ) {
?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id( $field['id'] ) ); ?>"><?php echo esc_html( $field['name'] ); ?></label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $field['id'] ) ); ?>" name="<?php echo esc_attr($this->get_field_name( $field['id'] )); ?>" type="date" value="<?php echo esc_attr( $value ); ?>" />
    <?php if ( $field['desc'] ) : ?>
    <small><?php echo wp_kses( $field['desc'] , fox_allowed_html() ); ?></small>
    <?php endif; ?>
</p>
     <?php   
    }
    
    /**
     * Type: HTML
     *
     * @since 1.0
     * @return void
     */
    function wi_form_html( $field = array(), $value = null ) {
        
        echo $value;
        
    }
    
}

endif;

/**
 * Create an appropriate place to init widgets
 *
 * @since 1.0
 */
do_action( 'wi_widgets' );