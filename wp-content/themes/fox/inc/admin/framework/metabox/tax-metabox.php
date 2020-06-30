<?php
if ( !class_exists( 'Fox_Framework_Term_Metabox' ) ) :
/**
 * Term Metabox
 * ref: https://gist.github.com/ms-studio/fc21fd5720f5bbdfaddc
 * @since 4.0
 */
class Fox_Framework_Term_Metabox {
    
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
	 * @since 4.0
	 */
	private static $instance;

	/**
	 * Instantiate or return the one class instance
	 *
	 * @since 4.0
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
     * @since 4.0
     */
    public function init() {
        
        $this->tax = [];
        
        add_action( 'admin_init', [ $this, 'init_metaboxes' ] );
        
        // prefix all metaboxes
        add_filter ( 'fox_term_metaboxes', array( $this, 'prefix_metaboxes' ), 100 );
        
    }
    
    /**
     * init metaboxes for taxonomies
     * @since 4.0
     */
    function init_metaboxes() {
        
        $metaboxes = apply_filters( 'fox_term_metaboxes', [] );
        
        foreach ( $metaboxes as $metabox ) {
            
            extract( wp_parse_args( $metabox, [ 
                'id' => 'metabox-id',
                'title' => 'metabox title',
                'screen' => array ( 'category', 'post_tag' ),
                'fields' => array(),
            ] ) );
            
            $screen = ( array ) $screen;
            foreach ( $screen as $tax ) {
                
                if ( ! isset( $this->tax[ $tax ] ) ) $this->tax[ $tax ] = [];
                $this->tax[ $tax ] = array_merge( $this->tax[ $tax ], $fields );
                
                add_action( $tax . '_add_form_fields', array( $this, 'add_metabox_forms' ), 10, 1 );
                add_action( $tax . '_edit_form_fields', array( $this, 'edit_metabox_forms' ), 10, 2 );
                
                add_action( 'edited_' . $tax, array( $this, 'save_taxonomy' ), 10, 2 );  
                add_action( 'create_' . $tax, array( $this, 'save_taxonomy' ), 10, 2 );
                
            }

        }
        
    }
    
    /**
     * Save Taxonomy
     * @since 4.0
     */
    function save_taxonomy( $term_id, $taxonomy_term_id ) {
        
        // verify the nonce
        if ( ! isset( $_POST['fox_tax_meta_nonce'] ) || ! wp_verify_nonce( $_POST['fox_tax_meta_nonce'], basename( __FILE__ ) ) )
            return;
        
        $term = get_term( $term_id ); if ( ! $term ) return;
        $tax = $term->taxonomy;
        
        // Before save action
        // We can alter value here or validate the data
        do_action( 'fox_before_save_taxonomy', $term_id, $tax );
        
        $fields = isset( $this->tax[ $tax ] ) ? ( array ) $this->tax[ $tax ] : [];
        if ( empty( $fields ) ) return;
        
        foreach ( $fields as $field ) {
            
            extract( wp_parse_args( $field, [
                'id' => '',
                'type' => '',
            ] ) );
            
            $value = isset( $_POST[ $id ] ) ? $_POST[ $id ] : null;
        
            $update = update_term_meta( $term_id, $id, $value );
                    
            if ( ! $update ) {

                $add = add_term_meta( $term_id, $id, $value, true );

            }
        
        }
        
    }
    
    /**
     * Add Screen
     * @since 4.0
     */
    function add_metabox_forms( $tax = 'category' ) {
        
        $fields = isset( $this->tax[ $tax ] ) ? ( array ) $this->tax[ $tax ] : [];
        if ( empty( $fields ) ) return;
        
        $this->implement_metabox_forms( $fields, null, 'add' );
        
    }
    
    /**
     * Edit Screen
     * @since 4.0
     */
    function edit_metabox_forms( $term, $tax = 'category' ) {
        
        $fields = isset( $this->tax[ $tax ] ) ? ( array ) $this->tax[ $tax ] : [];
        if ( empty( $fields ) ) return;
        
        $this->implement_metabox_forms( $fields, $term, 'edit' );
        
    } // function
    
    /**
     * Implement metaboxes, common function for both add and edit
     * @since 4.0
     */
    function implement_metabox_forms( $fields = [], $term = null, $screen = 'edit' ) {
        
        wp_nonce_field( basename( __FILE__ ), 'fox_tax_meta_nonce' );
        
        foreach ( $fields as $key => $field ) {
            
            extract( wp_parse_args( $field, [
                'id' => '',
                'type' => '',
                'name' => '',
                'desc' => '',
                'description' => '',
                'std' => null,
                
                'options' => [],
                'multiple' => false,
            ] ) );
            
            if ( ! $type || ! $id ) continue;
            if ( ! $desc ) $desc = $description; // alias
            
            if ( $term ) {
                $value = get_term_meta( $term->term_id, $id, true );
                
                // case not an options
                if ( 'select' == $type && ( ! $multiple ) && ( ! isset( $options[ $value ] ) ) ) {
                    $value = $std;
                }
                
            } else {
                $value = $std;
            }
            
            // pass parameters to render methods
            $field[ 'id' ] = $id;
            $field[ 'value' ] = $value;
            
            if ( 'edit' ==  $screen ) {
                $wrap_open = '<tr class="form-field term-' . $id . '-wrap">';
                $wrap_close = '</tr>';
                $inner_th_open = '<th scope="row" valign="top">';
                $inner_th_close = '</th>';
            } else {
                $wrap_open = '<div class="form-field term-' . $id . '-wrap">';
                $wrap_close = '</div>';
                $inner_th_open = '';
                $inner_th_close = '';
            }
            
            ?>

        <?php echo $wrap_open; ?>

            <?php echo $inner_th_open; ?>
            
                <label for="<?php echo esc_attr( $id ); ?>"><?php echo $name; ?></label>
            
            <?php echo $inner_th_close; ?>

            <td>

                <?php
                    if ( method_exists( $this, $type . '_field' ) ) {
                        call_user_func_array([ $this, $type . '_field' ], [ $field ] );
                    }
                ?>
                
                <?php if ( $desc ) { ?>
                <p class="description"><?php echo $desc; ?></p>
                <?php } ?>

            </td>

        <?php echo $wrap_close; ?>
            
        <?php } // each field
        
    } // function
    
    /**
     * Prefix Metaboxes by _fox_
     * @since 4.0
     */
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
    
    /* FIELDS
    ------------------------------------------------------------------------ */
    /**
     * Text Field
     * @since 4.0
     */
    function text_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'value' => null
        ] ) );
        
        ?>

        <input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" size="40" />

        <?php
        
    }
    
    /**
     * Textarea Field
     * @since 4.0
     */
    function textarea_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'value' => null
        ] ) );
        
        ?>

        <textarea name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" rows="5" cols="50" type="large-text"><?php echo $value; ?></textarea>

        <?php
        
    }
    
    /**
     * Select Field
     * @since 4.0
     */
    function select_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'options' => [],
            'multiple' => false,
            'value' => null
        ] ) );
        
        if ( $multiple ) {
            $value = explode( ',', $value );
        }
        ?>

<select name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>"<?php if ( $multiple ) echo ' multiple'; ?>>
    
    <?php foreach ( $options as $opt_id => $opt_value ) { ?>
    
    <?php if ( ! $multiple ) { ?>
    
    <option value="<?php echo esc_attr( $opt_id );?>" <?php selected( $value, $opt_id ); ?>><?php echo esc_html( $opt_value );?></option>
    
    <?php } else { ?>
    
    <option value="<?php echo esc_attr( $opt_id );?>" <?php if ( in_array( $opt_id, $value ) ) echo 'selected="selected"'; ?>><?php echo esc_html( $opt_value );?></option>
    
    <?php } // end if multiple
        
    } // foreach ?>
    
</select>

<?php
        
    } // select_field
    
    /**
     * Image Upload Field
     * @since 4.0
     */
    function image_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'value' => null
        ] ) );
        
        $image = ! empty( $value ) ? wp_get_attachment_image_src( $value,'medium' ) : null;
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
    
    /**
     * Upload multiple images
     * @since 4.0
     */
    function images_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'value' => null
        ] ) );
        
        if ( is_array( $value ) ) {
            $images = $value;
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
    
    /**
     * Color Field
     * @since 4.0
     */
    function color_field( $field = [] ) {
        
        extract( wp_parse_args( $field, [
            'id' => '',
            'value' => null
        ] ) );
        
        ?>

        <div class="wi-colorpicker">
            
            <input type="text" class="colorpicker-input" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr( $value ); ?>" />
            
        </div><!-- .wi-colorpicker -->

        <?php
        
    }
    
}

Fox_Framework_Term_Metabox::instance()->init();

endif; // class exists