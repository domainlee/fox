<?php
class Fox_Import {

    function __construct() {
        
        $this->define_demos();
        
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
        
        /* One click import demo
         * @since 3.0
          */
        if ( ! defined( 'PT_OCDI_PATH' ) ) {
            
            define( 'PT_OCDI_PATH', get_template_directory() . '/inc/admin/import/demo-import/' );
            define( 'PT_OCDI_URL', get_template_directory_uri() . '/inc/admin/import/demo-import/' );
            
        }
        add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
        require_once get_parent_theme_file_path( '/inc/admin/import/demo-import/one-click-demo-import.php' );
        
        /**
         * Demo Import
         *
         * @since 3.0
         */
        add_filter( 'pt-ocdi/import_files', array( $this, 'import_files' ) );
        
        // @since 4.2
        // to import sidebar
        add_action( 'pt-ocdi/before_widgets_import', array( $this, 'before_widgets_import' ) );
        add_action( 'pt-ocdi/before_content_import', array( $this, 'before_widgets_import' ) );
        
        add_action( 'pt-ocdi/after_import', array( $this, 'after_import_setup' ) );
        
        /**
         * Needed Script
         */
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    
    }
    
    function admin_enqueue_scripts( $hook ) {
        
        if ( 'fox-magazine_page_import-demo' === $hook ) {
            
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            wp_enqueue_script( 'fox-import', FOX_ADMIN_URL . 'js/import.js' , array( 'jquery', 'jquery-ui-dialog' ) );
            
            $import_js_data = array(
                'ajax_url'         => admin_url( 'admin-ajax.php' ),
                'ajax_nonce'       => wp_create_nonce( 'ocdi-ajax-verification' ),
                'dialog_options'   => [],
                'texts'            => array(
                    'missing_preview_image' => esc_html__( 'No preview image defined for this import.', 'wi' ),
                    'dialog_title'          => esc_html__( 'Are you sure?', 'wi' ),
                    'dialog_no'             => esc_html__( 'Cancel', 'wi' ),
                    'dialog_yes'            => esc_html__( 'Yes, import!', 'wi' ),
                    'selected_import_title' => esc_html__( 'Selected demo import:', 'wi' ),
                    'import_settings'       => 'If you only import settings, all content and menus will NOT be imported.',
                ),
            );
            
            if ( ! is_wp_error( $this->demos ) ) {
                $import_js_data[ 'demos' ] = $this->demos;
            }
            
            wp_localize_script( 'fox-import', 'FOX_IMPORT', $import_js_data );
            
        }
        
    }
    
    function define_demos() {
    
        $url = 'https://thefox-demo.s3.amazonaws.com/demos.txt';
        $response = wp_remote_get( esc_url_raw( $url ) );
        
        if ( is_wp_error( $response ) ) {
            
            $this->demos = new WP_Error( 'remove_get_demos', sprintf( 'Can\'t retreive the demo list from %s.', $url ) );
            
        } else {
            
            // i trust the result 100% so don't need to check again
            $body = wp_remote_retrieve_body( $response, true );
            $this->demos = json_decode( $body, true );
            
        }
    
    }
    
    /**
     * add an admin menu
     * @since 4.0
     */
    function admin_menu() {
    
        // add admin page to Appearance
        $hook = add_submenu_page(
            'fox',
            'Import Demo',
            'Import Demo',
            'manage_options',
            'import-demo',
            array( $this, 'create_admin_page')
        );
    
    }
    
    /**
     * Options page callback
     *
     * @since 1.0.0
     */
    public function create_admin_page() {
        ?>
     
<div class="wrap">

    <h1>Import Demo Data</h1>
    
    <hr>
    
    <div class="demo-browser">
        
        <div class="message"></div>
        
        <div class="loader">
            
            <div class="loader-header">
                
                <span class="fox-loading">
                    <i class="dashicons dashicons-update"></i>
                </span>
                <h3>Importing... Please don't leave this page</h3>
            </div>
            
            <div class="loader-msg">
                <p>The heaviest part of importing is Images. You can check importing progress in <a href="<?php echo admin_url( 'upload.php' ); ?>" target="_blank" title="Open in new tab"><strong>Dashboard > Media</strong></a> by checking whether all images have been downloaded or not.</p>
            </div>
        </div>
        
        <?php if ( is_wp_error( $this->demos ) ) { ?>
        
        <div class="message error">
        
            <p><?php echo $this->demos->get_error_message(); ?></p>
        
        </div>
        
        <?php } else { ?>
        
        <div class="demos wp-clearfix">
            
            <?php foreach ( $this->demos as $id => $demodata ) : 
                extract( wp_parse_args( $demodata, [
                    'name' => '',
                    'slug' => '',
                    'image' => '',
                    'preview' => '',
                ] ) );
            ?>
            
            <div class="demo" data-demo="<?php echo esc_attr( $slug ); ?>">
	
                <div class="demo-screenshot">
                    <img src="<?php echo esc_url( $image ); ?>">
                    <a class="wrap-link" target="_blank" href="<?php echo esc_url( $preview ); ?>"><?php echo $name; ?></a>
                    
                    <div class="screenshot-loader">
                        <span class="fox-loading">
                            <i class="dashicons dashicons-update"></i>
                            <span class="loading-text">Importing...</span>
                        </span>
                    </div>
                    
                </div>

                <div class="demo-id-container">

                    <div class="demo-actions">
                        <a class="button fox-import-btn" data-import="settings" data-slug="<?php echo esc_attr( $slug ); ?>" href="#" title="Import Only Settings">+Settings</a>
                        <a class="button button-primary fox-import-btn" data-import="full" data-slug="<?php echo esc_attr( $slug ); ?>" href="#" title="Import all posts, pages etc">Import Full</a>
                    </div>
                    
                </div><!-- .demo-id-container -->
                
            </div><!-- .demo -->
            
            <?php endforeach; ?>

        </div><!-- .demos -->
        
        <?php } ?>
        
    </div>
    
    <div id="js-ocdi-modal-content"></div>

</div>

<?php
    }
    
    /**
     * execute after import the content
     * before import the widgets
     *
     * @since 4.2
     */
    function before_widgets_import( $selected_import ) {
        
        $slug = isset( $selected_import[ 'slug' ] ) ? $selected_import[ 'slug' ] : '';
        
        if ( ! isset( $this->demos[ $slug ] ) ) return;
        
        $demodata = $this->demos[ $slug ];
        
        $custom_sidebars = isset( $demodata[ 'sidebars' ] ) ? $demodata[ 'sidebars' ] : [];
        $custom_sidebars = ( array ) $custom_sidebars;
        
        foreach ( $custom_sidebars as $sidebar => $sidebar_name ) {
        
            fox_add_sidebar([
                'slug' => $sidebar,
                'name' => $sidebar_name,
            ]);
        
        }
        
    }
    
    /**
     * Setup after importing process
     *
     * @since 2.0
     * @improved since 4.0
     */
    function after_import_setup( $selected_import ) {
        
        $slug = isset( $selected_import[ 'slug' ] ) ? $selected_import[ 'slug' ] : '';
        
        if ( ! isset( $this->demos[ $slug ] ) ) return;
        
        $demodata = $this->demos[ $slug ];
        
        /**
         * 01 - assign menu
         */
        $nav_menu_locations = [];
        $positions = [
            'primary', 'footer', 'mobile', 'search-menu'
        ];
        
        // Assign menus to their locations.
        foreach ( $positions as $pos ) {
            
            if ( isset( $demodata[ $pos ] ) ) {
                $nav = get_term_by( 'name', $demodata[ $pos ], 'nav_menu' );
                if ( $nav ) {
                    $nav_menu_locations[ $pos ] = $nav->term_id;
                }
            }
            
        }

        if ( ! empty( $nav_menu_locations ) ) {
            set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
        }
        
        /**
         * 02 - assign home page, blog page
         */
        update_option( 'show_on_front', 'posts' );
        
        /**
         * 03 - set option for current demo
         */
        set_theme_mod( 'wi_demo', $slug );
        
        
        
    }
    
    /**
     * Registers import files
     *
     * @since 3.0
     */
    function import_files( $files ) {
        
        $files = array();
        
        if ( ! is_wp_error( $this->demos ) ) {
            
            foreach ( $this->demos as $id => $demodata ) {

                $files[ $id ] = [
                    'slug'             => $id,
                    'import_file_name' => $demodata[ 'name' ],
                    'import_file_url' => $demodata[ 'content' ],
                    'import_widget_file_url' => $demodata[ 'widgets' ],
                    'import_customizer_file_url' => $demodata[ 'customizer' ],
                    'import_preview_image_url'      => $demodata[ 'image' ],
                ];

            }
            
        }
        
        return $files;
    
    }

}
new Fox_Import();