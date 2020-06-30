<?php

// This module allows you to add custom fields to a post type edit screen
// =================================================================

if ( !class_exists( 'Wi_Framework_Metabox' ) ) :

class Wi_Framework_Metabox
{   
    
    /**
     * @var bool Used to prevent duplicated calls like revisions, manual hook to wp_insert_post, etc.
     */
    public $saved = false;
    
    /**
	 *
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of the class
	 *
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * Instantiate or return the one class instance
	 *
	 * @since 1.0
	 *
	 * @return class
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
    
    /**
     * Initiate the class
     * contains action & filters
     *
     * @since 1.0
     */
    public function init() {
        
        // init the metabox
        add_action( 'add_meta_boxes', array( $this, 'metabox_init' ) );
        
        // save post action
        add_action( 'save_post', array( $this, 'metabox_save' ), 10, 3 );
        
        // prefix all metaboxes
        add_filter ( 'fox_metaboxes', array( $this, 'prefix_metaboxes' ), 100 );
        
    }
    
    // Register Metabox
    // ========================
    function metabox_init() {
    
        // Retrieve metaboxes
        $metaboxes = apply_filters( 'fox_metaboxes', array() );

        foreach ( $metaboxes as $metabox ) {
            
            $defaults = array(
                'id' => 'metabox-id',
                'title' => 'metabox title',
                'screen' => array ( 'page' ),
                'context' => 'normal',
                'priority' => 'high',
                'fields' => array(),
                'tabs' => array(),
            );
            
            $metabox = wp_parse_args( $metabox , $defaults );
            extract( $metabox );
            
            add_meta_box ( $id, $title, array( $this, 'render_metabox' ) , $screen , $context, $priority , array( 'fields' => $fields, 'tabs' => $tabs ) );

        }
        
    }
    
    // Prefix all metaboxes
    // ========================
    function prefix_metaboxes( $metaboxes ) {
    
        $prefix = '_wi_';
        
        foreach ( $metaboxes as $key1 => $metabox ) {
        
            $fields = isset ( $metabox[ 'fields' ] ) ? $metabox[ 'fields' ] : array();
            if ( is_array( $fields ) ) {
            
                foreach ( $fields as $key2 => $field ) {
            
                    if ( isset( $field[ 'id' ] ) ) {
                    
                        if ( ! isset( $field[ 'prefix' ] ) || $field[ 'prefix' ] !== false ) {
                            $field[ 'id' ] = $prefix . $field[ 'id' ];
                        }
                        
                        $metaboxes[ $key1 ][ 'fields' ][ $key2 ][ 'id' ] = $field[ 'id' ];
                        
                    }
                    
                }
                
            }
            
        }
        
        return $metaboxes;
    
    }
    
    // Save Metabox Value
    // ========================
    function metabox_save( $post_id, $post, $update ) {
        
        // Make sure meta is added to the post, not a revision
        if ( $the_post = wp_is_post_revision( $post_id ) )
            $post_id = $the_post;
        
        // Autosave
        if ( defined( 'DOING_AUTOSAVE' ) )
            return;
        
        // Check if this function is called to prevent duplicated calls like revisions, manual hook to wp_insert_post, etc.
        // if ( true === $this->saved )
        //    return;
        // $this->saved = true;
        
        // Check Nonce
        $nonce = isset( $_REQUEST[ "_nonce_metabox_{$post_id}" ] ) ? sanitize_key( $_REQUEST[ "_nonce_metabox_{$post_id}" ] ) : '';
        if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, "wi-metabox-{$post_id}" ) )
            return;

        // Before save action
        // We can alter value here or validate the data
        do_action( 'wi_before_save_post', $post_id, $post );
        
        // Retrieve metaboxes
        $metaboxes = apply_filters( 'fox_metaboxes', array() );
        
        foreach ( $metaboxes as $metabox ) {
            
            $defaults = array(
                'id' => 'metabox-id',
                'title' => 'metabox title',
                'screen' => array ( 'page' ),
                'context' => 'normal',
                'priority' => 'high',
                'fields' => array(),
                'tabs' => array(),
            );
            
            $metabox = wp_parse_args( $metabox , $defaults );
            extract( $metabox );
            
            // don't save for other post types
            if ( !is_array( $screen ) || !in_array( $post->post_type, $screen ) ) {
                continue;
            }
            
            // check fields
            if ( !isset( $fields) || !is_array( $fields ) || empty( $fields ) ) {
                continue;
            }
            
            // foreach
            foreach ( $fields as $field ) {
            
                extract( $field );
                
                /**
                 * special case: post_format, we don't save it as metakey
                 */
                if ( 'post_format' == $id ) {
                    set_post_format( $post_id, $_POST[ $id ] );
                }
                
                // since 4.0, no save metabox
                // @since 4.0
                if ( isset( $field[ 'save' ] ) && ! $field[ 'save' ] ) continue;
                
                // check type
                if ( !isset( $type ) || !isset( $id ) ) {
                    continue;
                }
                
                // validate input before saving
                if ( isset( $_POST[ $id ] ) && ! $this->validated( $_POST[ $id ], $type ) ) {
                    
                    continue;
                    
                }
                
                // since 4.0, we no longer save empty value to save database storage
                // @since 4.0
                // if ( ! isset( $_POST[ $id ] ) ) continue;
                
                // no value and previous also no value
                if ( isset( $_POST[ $id ] ) && '' === $_POST[ $id ] && '' === get_post_meta( $post_id, $id, true ) ) continue;
                
                if ( isset( $_POST[ $id ] ) ) {
                    
                    $id = sanitize_key( $id );
                    
                    if ( 'checkbox' == $type ) {
                        if ( ! $_POST[ $id ] ) {
                            $_POST[ $id ] = 'false';
                        }
                    }
                    
                    $update = update_post_meta( $post_id, $id, $_POST[ $id ] );

                    if ( ! $update ) {

                        $add = add_post_meta( $post_id, $id, $_POST[ $id ] , true );

                    }
                    
                    if ( 'review' == $type ) {
                    
                        $reviews = $_POST[ $id ];
                        $count = 0; $total = 0;
                        foreach ( $reviews as $review ) {
                            if ( ! isset( $review[ 'score' ] ) || ! is_numeric( trim( $review[ 'score' ] ) ) ) continue;
                            $count++;
                            $total += floatval( $review[ 'score' ] );
                        }
                        if ( $count > 0 ) {
                            $update = update_post_meta( $post_id, $id . '_average', $total/($count) );
                            if ( !$update ) {
                                $add = add_post_meta( $post_id, $id . '_average', $total/($count) , true );
                            }
                        }

                    }
                   
                // Checkbox sends nothing to server when it's unchecked
                } elseif ( $type == 'checkbox' ) {
                
                    $update = update_post_meta( $post_id, $id, 'false' );
                    
                    if ( !$update ) {
                    
                        $add = add_post_meta( $post_id, $id, 'false' , true );
                    
                    }
                
                // Multiple Select sends nothing to server when nothing chosen
                } elseif ( $type == 'select' && $multiple ) {
                
                    $update = update_post_meta( $post_id, $id, array() );
                    
                    if ( !$update ) {
                    
                        $add = add_post_meta( $post_id, $id, array() , true );
                    
                    }    
                
                }
            
            }
            
            // Indicate that we've saved the value
            if ( ! get_post_meta( $post_id, '_wi_saved_once', true ) ) {
                add_post_meta( $post_id, '_wi_saved_once', true );
            }
        
        } // foreach
    
    }
    
    // Check validation of data before saving
    // ========================
    function validated( $value, $type ) {
    
        // color
        if ( $type == 'color' ) {
        
            if ( ! preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
                return false;
            }
        
        }
    
        return true;
        
    }
    
    // Render Metabox Function
    // ========================
    function render_metabox ( $post, $metabox ) {
        
        // metabox id
        if ( !isset( $metabox[ 'id' ] ) ) {
            return;
        }
        
        // custom args
        $fields = isset( $metabox['args'][ 'fields' ] ) ? $metabox['args'][ 'fields' ] : array();
        $tabs = isset( $metabox['args'][ 'tabs' ] ) ? $metabox['args'][ 'tabs' ] : array();
        $tab_contents = array();
        
        wp_nonce_field( "wi-metabox-{$post->ID}" , "_nonce_metabox_{$post->ID}" );
        
        ?>

        <div class="wi-metabox">
            
            <?php if ( is_array( $tabs ) && !empty( $tabs ) ) { $first = true; ?>
            
            <nav class="metabox-tabs">
                
                <ul>
                
                    <?php foreach ( $tabs as $id => $name ) { ?>
                    
                    <li<?php if( $first ) echo ' class="active"'; ?>><a href="#" data-href="<?php echo esc_attr( $id ) ;?>">
                        
                        <span><?php echo $name; ?></span>
                        
                        </a></li>
                    
                    <?php $first = false; } ?>
                
                </ul>
            
            </nav>
                 
            <?php } ?>
            
            <div class="metabox-fields">
            
                <?php

                foreach ( $fields as $field ) {

                    if ( !isset( $field[ 'field_id' ] ) ) {

                        if ( isset( $field[ 'id' ] ) ) {

                            $field[ 'field_id' ] = "wi-field-{$metabox['id']}-{$field['id']}";

                        }

                    }

                    $field_tab = isset( $field[ 'tab' ] ) ? $field[ 'tab' ] : '';

                    if ( ! isset( $tab_contents[ $field_tab ] ) ) $tab_contents[ $field_tab ] = '';

                    ob_start();

                    $this->render_field( $field, $post );

                    $tab_contents[ $field_tab ] .= ob_get_clean();

                }
        
                foreach ( $tab_contents as $tab_id => $tab_content ) { ?>
                
                <div class="tab-content" data-tab="<?php echo esc_attr( $tab_id ) ; ?>">
                
                    <?php echo $tab_content; ?>
                
                </div>
                
                <?php } ?>
                
            </div><!-- .metabox-fields -->
            
        </div><!-- .wi-metabox -->

        <?php
    
    }
    
    // Render Field
    // ========================
    function render_field ( $field, $post ) {
        
        // extract field
        $defaults = array(
            'id' => '',
            'type' => '',
            'name' => '',
            'desc' => '',
            'std' => null,
            'options' => [],
            'desc' => '',
            'placeholder' => '',
            'dependency' => array(),
            'tab' => '',
            'field_id' => '',
        );

        extract( wp_parse_args( $field, $defaults ) );
        
        $id = sanitize_key( $id );
        if ( !$id ) {
            return;
        }
        
        // if $post not set, we're creating a new post
        $post_id = 0;
        if ( $post ) {
            
            $post_id = $post->ID;
            $current_val = get_post_meta ( $post_id, $id , true );
            
            /**
             * just an exception
             * for placeholder purpose,
             * @since 4.0
             */
            if ( 'post_format' == $id ) {
                $current_val = get_post_format( $post_id );
            }
            
            // for radio, select fields
            if ( 'radio' == $type || 'select' == $type || 'image_radio' == $type ) {
                
                if ( ! isset( $options[ $current_val ] ) && $std !== null ) {
                    $current_val = $std;
                }
            
            }
        } else {
            $current_val = '';
        }
        
        // check this post has been saved or not to set default
        if ( $post_id ) {
            $saved_once = get_post_meta( $post_id, '_wi_saved_once', true );
        } else {
            $saved_once = false;
        }
        
        // default value
        if ( !$saved_once && empty( $current_val ) && isset( $std ) ) {
            $current_val = $std;
        }
        
        // id
        $id_attr = '';
        if ( $field[ 'field_id' ] ) {
        
            $id_attr = ' id="' . esc_attr( $field[ 'field_id' ] ) . '"';
            
        }
        
        // dependency
        $prefix = '_wi_';
        $data_attr = array();
        if ( is_array( $dependency) && isset( $dependency[ 'element' ] ) && isset( $dependency[ 'value' ] ) ) {
            $depEle = $dependency[ 'element' ];
            $depElePrefix = isset( $dependency[ 'element_prefix' ] ) ? $dependency[ 'element_prefix' ] : true;
            if ( $depElePrefix ) {
                $depEle = $prefix . $depEle;
            }
            $depVal = $dependency[ 'value' ];
            $depOperator = isset( $dependency[ 'operator' ] ) ? $dependency[ 'operator' ] : '';
            if ( !$depOperator ) {
                $depOperator = is_array( $depVal ) ? 'in' : '==';
            }
            
            if ( is_array( $depVal ) ) {
                $depVal = join( ',' , $depVal );
            }
        
            $data_attr[] = 'data-cond-option="' . $depEle . '"';
            $data_attr[] = 'data-cond-value="' . $depVal . '"';
            $data_attr[] = 'data-cond-operator="' . $depOperator . '"';
            
        }
        $data_attr = join( ' ', $data_attr );
        ?>

<div class="wi-metabox-field field-<?php echo $type; ?>"<?php echo $id_attr; ?> <?php echo $data_attr; ?> data-id="<?php echo esc_attr( $id ); ?>">
    
    <?php if ( $type == 'html' ) { // ======== HTML FIELD ?>
    
    <?php echo $std; ?>
    
    <?php } elseif ( $type == 'heading' ) { // ======== HEADING FIELD ?>
    
    <div class="metabox-heading">
        
        <h3><?php echo esc_html( $name ); ?></h3>
        <?php if ( $desc ) : ?>
        <p class="description">
            <?php echo $desc; ?>
        </p>
        <?php endif; ?>
    
    </div><!-- .metabox-heading -->
    
    <?php } elseif ( $type == 'hidden' ) { // ======== HIDDEN FIELD // we don't need to care about its markup ?>
    
    <input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="hidden" value="<?php echo $value; ?>" />
    
    <?php } else { // ======== STANDARD FIELDS ?>
    
    <div class="label-wrapper">
                
        <?php if ( $name ) { ?>
        <label for="<?php echo $id; ?>">

            <?php echo esc_html($name); ?>

        </label>
        <?php } ?>
        
        <?php if ( $desc ) : ?>
        <p class="description">
            <?php echo $desc; ?>
        </p>
        <?php endif; ?>
        
    </div><!-- .label-wrapper -->
    
    <div class="input-wrapper">

        <?php
        // ========== CHECK METHOD & RENDER EACH TYPE HERE
        
        if ( method_exists( __CLASS__, "{$type}_field" ) ) {

            call_user_func_array( array( __CLASS__ , "{$type}_field" ), array( $field, $id, $current_val ) );

        }
        
        // ========== END OF EACH TYPE
        ?>
        
    </div><!-- .input-wrapper -->
    
    <?php } // html field or just a normal field ?>
        
</div><!-- .wi-metabox-field -->

        <?php

    }
    
    // Text Field
    // ========================
    function text_field( $field, $id, $value ) {
        
        $placeholder = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : '';
        
        ?>

        <input name="<?php echo $id; ?>" id="<?php echo $id; ?>" class="widefat" type="text" value="<?php echo $value; ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" />

        <?php
    }
    
    // Textarea Field
    // ========================
    function textarea_field( $field, $id, $value ) {
        
        $placeholder = isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : '';
        
        ?>

        <textarea name="<?php echo $id; ?>" id="<?php echo $id; ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" rows="5" cols="50"><?php echo $value; ?></textarea>

        <?php
    }
    
    // Select Field
    // There's a slight difference between edit action & create action (the layout of form field)
    // ========================
    function select_field( $field, $id, $value ) {
        
        $options = isset( $field[ 'options' ] ) ? $field[ 'options' ] : array();
        if ( !is_array( $options ) || empty( $options ) ) {
            return;
        }
        $multiple = isset( $field[ 'multiple' ] ) && $field[ 'multiple' ];
        
        ?>

        <?php if ( !$multiple ) { // ======== NORMAL SELECT, not MULTIPLE ?>

        <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
            
            <?php foreach ( $options as $key => $val ) : ?>
            
            <option value="<?php echo esc_attr($key);?>" <?php selected( $value, $key ); ?>>

                <?php echo esc_html( $val );?>

            </option>
            
            <?php endforeach; ?>
            
        </select>    

        <?php } else { // ======== MULTIPLE SELECT ?>

        <select name="<?php echo $id; ?>[]" id="<?php echo $id; ?>" multiple>

            <?php foreach ( $options as $key => $val ) : ?>
            
            <option value="<?php echo esc_attr($key);?>"<?php if ( is_array( $value ) && in_array( $key, $value ) ) { echo ' selected="selected"'; } ?>>

                <?php echo esc_html( $val );?>

            </option>

            <?php endforeach; ?>
            
        </select>

        <?php } // multiple or single ?>
        
        <?php
    }
    
    // Radio Field
    // ========================
    function radio_field( $field, $id, $value ) {
        
        $options = isset( $field[ 'options' ] ) ? $field[ 'options' ] : array();
        if ( !is_array( $options ) || empty( $options ) ) {
            return;
        }
        
        foreach ( $options as $key => $val ) { ?>
        
        <!-- .radio-wrapper to make things diplayed inline-block instead of block -->
        <span class="radio-wrapper">
            
            <label for="<?php echo esc_attr( "{$id}-{$key}" );?>">
                <input name="<?php echo $id; ?>" id="<?php echo esc_attr( "{$id}-{$key}" ); ?>" type="radio" value="<?php echo $key; ?>" <?php checked( $value, $key ); ?> />
                <small><?php echo esc_attr( $val ); ?></small>
            </label>
            
        </span><!-- .radio-wrapper -->

        <?php }
    }
    
    // Image Radio
    // ========================
    function image_radio_field( $field, $id, $value ) {
        
        $options = isset( $field[ 'options' ] ) ? $field[ 'options' ] : array();
        if ( !is_array( $options ) || empty( $options ) ) {
            return;
        }
        
        foreach ( $options as $key => $val ) { 
            
            if ( is_array( $val) ) {
                $src = isset( $val[ 'src' ] ) ? $val[ 'src' ] : '';
                $html = isset( $val[ 'html' ] ) ? $val[ 'html' ] : null;
                $width = isset( $val[ 'width' ] ) ? $val[ 'width' ] : '';
                $height = isset( $val[ 'height' ] ) ? $val[ 'height' ] : '';
                $title = isset( $val[ 'title' ] ) ? $val[ 'title' ] : '';
            } else {
                $title = '';
                $html = null;
                $src = $val;
                $width = $height = '';
            }

        ?>
        <span class="radio-wrapper">
            
            <label for="<?php echo esc_attr( "{$id}-{$key}" );?>">
                <input name="<?php echo $id; ?>" id="<?php echo esc_attr( "{$id}-{$key}" ); ?>" type="radio" value="<?php echo $key; ?>" <?php checked( $value, $key ); ?> />
                <?php if ( null !== $html ) { ?>
                
                <div class="html-img" title="<?php echo esc_attr( $title ); ?>">
                    
                    <?php echo $html; ?>
                    
                    <?php if ( $title ) { ?>
                    <span class="html-img-title"><?php echo $title; ?></span>
                    <?php } ?>
                    
                </div>
                
                <?php } else { ?>
                
                <img src="<?php echo esc_url( $src ); ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height );?>" title="<?php echo esc_attr( $title ); ?>" />
                
                <?php } ?>
            </label>
            
        </span><!-- .radio-wrapper -->    

        <?php }
    }
    
    // Checkbox
    // ========================
    function checkbox_field( $field, $id, $value ) {
        
        $value_attr = isset( $field[ 'value' ] )  ? $field[ 'value' ] : 'true';
        
        ?>

        <input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="checkbox" value="<?php echo esc_attr( $value_attr ); ?>" <?php checked( $value, $value_attr ); ?>  />

        <?php
    }
    
    // File Upload Field
    // ========================
    function upload_field( $field, $id, $value ) {
        
        $file = !empty($value) ? wp_get_attachment_url( $value ) : null;
        $filename = !empty($value) ? get_the_title( $value ) : '';
        $upload_button_name = $file ? esc_html__( 'Change File','wi' ) : esc_html__( 'Upload','wi' );
        
        $file_type = isset( $field[ 'file_type' ] ) ? $field[ 'file_type' ] : '';
        ?>

        <div class="wi-upload-wrapper"<?php if ($file_type) echo ' data-type="' . esc_attr( $file_type ) . '"'; ?>>
    
            <div class="file-holder<?php if($file) echo ' has-file'; ?> <?php if ( $file_type ) echo $file_type . '-holder'; ?>">

                <div class="file-preview">
                    
                    <span class="file-icon">

                        <?php if ( 'video' == $file_type ) : ?>
                        <i class="dashicons dashicons-media-video"></i>
                        <?php elseif ( 'audio' == $file_type ) : ?>
                        <i class="dashicons dashicons-media-audio"></i>
                        <?php elseif ( 'image' == $file_type ) : ?>
                        <i class="dashicons dashicons-format-image"></i>
                        <?php else : ?>
                        <i class="dashicons dashicons-media-default"></i>
                        <?php endif; ?>

                    </span><!-- .file-icon -->

                    <span class="file-name"><?php echo $filename; ?></span>
                    
                </div><!-- .file-preview -->

                <a href="#" class="remove-file-button" title="<?php esc_html_e( 'Remove File', 'wi' );?>">&times;</a>

            </div><!-- .file-holder -->
    
            <input type="hidden" class="media-result" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo esc_attr( $value ); ?>" />
            
            <input type="button" class="upload-file-button button button-primary" value="<?php echo $upload_button_name;?>" />

        </div>

        <?php
        
    }
    
    // Image Upload
    // ========================
    function image_field( $field, $id, $value ) {
        
        $image = !empty($value) ? wp_get_attachment_image_src($value,'medium') : null;
        $upload_button_name = $image ? esc_html__( 'Change Image','wi' ) : esc_html__( 'Upload Image','wi' );
        
        ?>

        <div class="wi-upload-wrapper">
    
            <figure class="image-holder">

                <?php if ( $image ) : ?>
                <img src="<?php echo esc_url($image[0]);?>" />
                <?php endif; ?>

                <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

            </figure>
    
            <input type="hidden" class="media-result" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo esc_attr( $value ); ?>" />
            
            <input type="button" class="upload-image-button button button-primary" value="<?php echo $upload_button_name;?>" />

        </div>

        <?php
        
    }
    
    // Image Upload
    // ========================
    function images_field( $field, $id, $value ) {
        
        if ( is_array( $value ) ) {
            $images = $value;
            $value = join( ',', $value );
        } else {
            $images = explode( ',', $value );
            $images = array_map( 'trim', $images );
        }
        
        ?>

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
    
            <input type="hidden" class="media-result" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo esc_attr( $value ); ?>" />
            
            <input type="button" class="upload-images-button button button-primary" value="<?php echo esc_html__( 'Upload Images', 'wi' ) ;?>" />

        </div><!-- .wi-upload-wrapper -->
        
        <?php
        
    }
    
    // Color
    // ========================
    function color_field( $field, $id, $value ) {
        
        ?>

        <div class="wi-colorpicker">
            
            <input type="text" class="colorpicker-input" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( $value ); ?>" />
            
        </div><!-- .wi-colorpicker -->

        <?php
        
    }
    
    // Review
    // This is a very specified field
    // Just for review purpose
    // ========================
    function review_field( $field, $id, $value ) {
        
        if ( !is_array( $value ) || empty( $value ) ) {
            $value = array( array( 'criterion' => '', 'score' => '' ) );
        }
        
        ?>

<div class="review-wrapper">
    
    <div class="review-list">

        <?php foreach ( $value as $i => $review ) { 

                if ( !is_array( $review ) ) {
                    continue;
                }
                extract( wp_parse_args ( $review, array( 'criterion' => '', 'score' => '' ) ) );

        ?>

        <div class="review" data-id="<?php echo $id;?>" data-order="<?php echo $i; ?>">

            <div class="review-criterion" data-property="criterion">

                <input type="text" class="widefat" id="<?php echo $id; ?>[<?php echo $i;?>][criterion]" name="<?php echo $id; ?>[<?php echo $i;?>][criterion]" value="<?php echo esc_attr( $criterion ) ;?>" placeholder="<?php echo esc_html__( 'Criterion: Design, Usability...', 'wi' ); ?>" />

            </div><!-- .review-criterion -->

            <div class="review-score" data-property="score">

                <input type="text" class="widefat" id="<?php echo $id; ?>[<?php echo $i;?>][score]" name="<?php echo $id; ?>[<?php echo $i;?>][score]" value="<?php echo esc_attr( $score ) ;?>" placeholder="<?php echo esc_html__( 'Score: 0 - 10', 'wi' ); ?>" />

            </div><!-- .review-score -->
            
            <button class="button remove-review" type="button"><?php echo esc_html( 'Remove', 'wi' ); ?></button>

        </div><!-- .review -->

        <?php } // end foreach ?>
        
    </div><!-- .review-list -->
    
    <?php $average = floatval( get_post_meta( get_the_ID(), $id . '_average', true ) ); if ( $average < 0 || $average > 10 ) $average = 0;
    $average = number_format( $average, 2, '.', '' );
    ?>
    <div class="review-total-score">
        
        <div class="review">

            <div class="review-criterion" data-property="criterion">

                <input type="text" readonly class="widefat" id="<?php echo $id; ?>_average" name="<?php echo $id; ?>_average" value="<?php echo esc_html__( 'Total Score', 'wi' ); ?>" />

            </div><!-- .review-criterion -->

            <div class="review-score" data-property="score">

                <input type="text" readonly class="widefat" id="<?php echo $id; ?>_average" name="<?php echo $id; ?>_average" value="<?php echo esc_attr( $average ) ;?>" placeholder="-" />

            </div><!-- .review-score -->
            
        </div><!-- .review -->
    
    </div><!-- .review-score -->
    
    <button class="button new-review" type="button"><?php echo esc_html( 'Add New Criterion', 'wi' ); ?></button>

</div><!-- .reviews -->

        <?php
        
    }
    
    // Slides
    // This is a very specified field
    // Just to make slider
    // Here we use $slides instead of $value
    // ========================
    function slides_field( $field, $id, $slides ) {
        
        $defaults = array(
            array(
                'image' => '', 
                'title' => '',
                'desc' => '',
                'url' => '',
                'url_target' => '',
            ),
        );
        if ( !is_array( $slides ) || empty( $slides ) ) {
            $slides = $defaults;
        }
        
        ?>

<div class="slides-wrapper">
    
    <div class="slides-list">

        <?php foreach ( $slides as $i => $slide ) { 

                if ( !is_array( $slide ) ) {
                    continue;
                }
                extract( wp_parse_args ( $slide, $defaults ) );

        ?>

        <div class="slide" data-id="<?php echo $id;?>" data-order="<?php echo $i; ?>">

            <div class="slide-image" data-property="image">
                
                <?php
                $attachment = !empty($image) ? wp_get_attachment_image($image,'wi_medium_crop') : null;
                $upload_button_name = $attachment ? esc_html__( 'Change Image','wi' ) : esc_html__( 'Upload Image','wi' );
                ?>

                <div class="wi-upload-wrapper">

                    <figure class="image-holder">

                        <?php echo $attachment; ?>

                        <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>

                    </figure>

                    <input type="hidden" class="media-result" id="<?php echo $id; ?>[<?php echo $i;?>][image]" name="<?php echo $id; ?>[<?php echo $i;?>][image]" value="<?php echo esc_attr( $image ); ?>" />

                    <input type="button" class="upload-image-button button" value="<?php echo $upload_button_name;?>" />

                </div><!-- .wi-upload-wrapper -->

            </div><!-- .slide-image -->
            
            <div class="slide-options">
                
                <label for="<?php echo $id; ?>[<?php echo $i;?>][title]" class="slide-title" data-property="title">
                    
                    <span><?php echo esc_html__( 'Title', 'wi' ); ?></span>
                    <input type="text" id="<?php echo $id; ?>[<?php echo $i;?>][title]" name="<?php echo $id; ?>[<?php echo $i;?>][title]" value="<?php echo esc_attr( $title ) ;?>" placeholder="<?php echo esc_html__( 'Slide title', 'wi' ); ?>" />
                    
                </label>
                
                <label for="<?php echo $id; ?>[<?php echo $i;?>][desc]" class="slide-desc" data-property="desc">
                    
                    <span><?php echo esc_html__( 'Description', 'wi' ); ?></span>
                    <textarea id="<?php echo $id; ?>[<?php echo $i;?>][desc]" name="<?php echo $id; ?>[<?php echo $i;?>][desc]" placeholder="<?php echo esc_html__( 'Slide Description', 'wi' ); ?>"><?php echo $desc ;?></textarea>
                    
                </label>
                
                <label for="<?php echo $id; ?>[<?php echo $i;?>][url]" class="slide-url" data-property="url">
                    
                    <span><?php echo esc_html__( 'Slide link', 'wi' ); ?></span>
                    <input type="url" id="<?php echo $id; ?>[<?php echo $i;?>][url]" name="<?php echo $id; ?>[<?php echo $i;?>][url]" value="<?php echo esc_attr( $url ) ;?>" placeholder="<?php echo esc_html__( 'Slide link', 'wi' ); ?>" />
                    
                    <div for="<?php echo $id; ?>[<?php echo $i;?>][url_target]" class="slide-url-target" data-property="url_target">
                        
                        <select id="<?php echo $id; ?>[<?php echo $i;?>][url_target]" name="<?php echo $id; ?>[<?php echo $i;?>][url_target]">

                            <option value="_self"<?php selected( $url_target, '_self' ); ?>><?php echo esc_html__( 'Same Tab', 'wi' ); ?></option>
                            <option value="_blank"<?php selected( $url_target, '_blank' ); ?>><?php echo esc_html__( 'New Tab', 'wi' ); ?></option>

                        </select>
                        
                    </div>
                    
                </label>
            
            </div><!-- .slide-options -->
            
            <button class="button remove-slide" type="button"><?php echo esc_html( 'Remove', 'wi' ); ?></button>

        </div><!-- .slide -->

        <?php } // end foreach slides ?>
        
    </div><!-- .slides-list -->
    
    <button class="button button-primary new-slide" type="button"><?php echo esc_html( 'New Slide', 'wi' ); ?></button>

</div><!-- .slides-wrapper -->

        <?php
        
    }
    
}

Wi_Framework_Metabox::instance()->init();

endif; // class exists