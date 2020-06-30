<?php
if ( ! defined( 'FOX_ADMIN_URL' ) ) define( 'FOX_ADMIN_URL', get_template_directory_uri() . '/inc/admin/' );
if ( ! defined( 'FOX_ADMIN_PATH' ) ) define( 'FOX_ADMIN_PATH', get_template_directory() . '/inc/admin/' );

if ( !class_exists( 'Wi_Admin' ) ) :
/**
 * Admin Class
 *
 * @since Fox 2.2
 * @improved from Fox 4.0
 */
class Wi_Admin {   
    
    /**
	 *
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of Wi_Admin
	 *
	 * @since Fox 2.2
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Wi_Admin instance
	 *
	 * @since Fox 2.2
	 *
	 * @return Wi_Admin
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
     * @since Fox 2.2
     */
    public function init() {
        
        /**
         * 01 - INTERFACE
         */
        // since 4.0
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 0 );
        add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );
        
        /**
         * Add a thumbnail column in edit.php
         *
         * Thank to: http://wordpress.org/support/topic/adding-custum-post-type-thumbnail-to-the-edit-screen
         *
         * @since 1.0
         */
        add_action( 'manage_posts_custom_column', array( $this, 'add_thumbnail_value_editscreen' ), 10, 2 );
        add_filter( 'manage_edit-post_columns', array( $this, 'columns_filter' ) , 10, 1 );
        
        add_filter( 'manage_edit-category_columns', [ $this, 'edit_term_columns' ], 10, 3 );
        add_filter( 'manage_category_custom_column', [ $this, 'manage_term_custom_column' ], 10, 3 );
        add_filter( 'manage_edit-post_tag_columns', [ $this, 'edit_term_columns' ], 10, 3 );
        add_filter( 'manage_post_tag_custom_column', [ $this, 'manage_term_custom_column' ], 10, 3 );
        
        /**
         * 02 - FRAMEWORK
         */
        // image html
        require_once FOX_ADMIN_PATH . 'framework/html.php';
        
        // metabox
        require_once FOX_ADMIN_PATH . 'framework/metabox/metabox.php';
        require_once FOX_ADMIN_PATH . 'framework/metabox/tax-metabox.php';
        
        // widgets
        require_once FOX_ADMIN_PATH . 'framework/widget.php';
        
        // TGM
        require_once FOX_ADMIN_PATH . 'framework/tgm.php';
        
        /**
         * 03 - FUNCTIONALITY
         */
        // register plugins needed for theme
        add_action( 'tgmpa_register', array ( $this, 'register_required_plugins' ) );
        
        // Include media upload to sidebar area
        // This will be used when we need to upload something
        add_action( 'sidebar_admin_setup', array( $this, 'wp_enqueue_media' ) );
        
        require_once get_template_directory() . '/inc/admin/framework/nav/nav-custom-fields.php'; // fields
        // add_action( 'wp_loaded', array( $this, 'include_menu_walker' ) );
        
        // enqueue scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
        
        // localization
        add_action( 'wiadminjs', array( $this, 'l10n' ) );
        
        // metabox
        add_filter( 'fox_metaboxes', array( $this, 'metaboxes' ) );
        add_filter( 'fox_term_metaboxes', array( $this, 'term_metaboxes' ) );
        
    }
    
    /**
     * Admin Menu
     & @since 4.0
     */
    function admin_menu() {
        
        // add admin page to Appearance
        $hook = add_menu_page(
            
            // title name
            'Welcome to The Fox',
            
            // menu name
            'Fox Magazine',
            
            // role
            'manage_options',
            
            // slug
            'fox',
            
            // function
            array( $this, 'create_admin_page'),
            
            // icon
            // 'dashicons-admin-site-alt3',
            // FOX_ADMIN_URL . 'images/fox.png',
            'dashicons-fox',
            
            // priority
            2
        );
        
        $hook = add_submenu_page(
            '',
            'Updated From Fox3',
            'Updated From Fox3',
            'manage_options',
            'updated-from-fox3',
            array( $this, 'create_update_page')
        );
        
        $hook = add_submenu_page(
            'fox',
            'Theme Options',
            'Theme Options',
            'manage_options',
            'fox-theme-options',
            array( $this, 'create_theme_options_page')
        );
    
    }
    
    /**
     * Welcome page
     * @since 4.0
     */
    function create_admin_page() {
        
        include_once FOX_ADMIN_PATH . 'welcome.php';
        
    }
    
    function create_update_page() {
        
        include_once FOX_ADMIN_PATH . 'update.php';
    
    }
    
    /**
     * theme options more clear
     * @since 4.4
     */
    function create_theme_options_page() {
        ?>
        <div class="wrap about-wrap">
            
            <h1>Theme Options</h1>

            <p>Fox uses the native WordPress Customizer which can be found under <strong>Appearance > Customize</strong></p>
            
            <img src="<?php echo get_template_directory_uri() . '/inc/admin/images/customizer.jpg'; ?>" alt="Customizer" width="500" style="width:500px; background: white; padding: 10px; border: 1px solid #e0e0e0" />
            
        </div>

    <?php
    
    }
    
    /**
     * Admin Footer Text
     * @since 4.0
     */
    function admin_footer_text( $text ) {
        
        $screen = get_current_screen();
        if ( $screen->parent_base == 'fox' ) {
        
            $text = 'Enjoyed <strong>The Fox Magazine</strong>? Help us by <a href="https://themeforest.net/item/the-fox-contemporary-magazine-theme-for-creators/reviews/11103012" target="_blank">leave a 5-star rating</a>. We really appreciate your support!';
            
        }
        
        return $text;
        
    }
    
    /**
     * Render thumbnail for post
     * @since 4.0
     */
    function add_thumbnail_value_editscreen( $column_name, $post_id ) {

        $width = (int) 50;
        $height = (int) 50;

        if ( 'thumbnail' == $column_name ) {
            
            // thumbnail of WP 2.9
            $thumbnail = get_post_meta( $post_id, '_thumbnail_id', true );
            
            if ( $thumbnail ) {
                $thumbnail = wp_get_attachment_image( $thumbnail, [ 50, 50 ] );
            }
            
            if ( ! $thumbnail ) {
                $thumbnail = '<img src="' . get_template_directory_uri() . '/images/placeholder.png' . '" atl="No thumbnail" width="50" />';
            }
            $format = get_post_format( $post_id );
            $format_indicator = '';
            if ( $format ) {
                $format_indicator = '<i class="dashicons dashicons-format-' . $format . '"></i>';
            }
            
            echo '<div class="fox-column-thumbnail">' . $thumbnail . $format_indicator . '</div>';
            
        }
        
    }
    
    /**
     * Add Thumbnail column to post
     * @since 4.0
     */
    function columns_filter( $columns ) {
        
        $column_thumbnail = array( 'thumbnail' => 'Thumbnail' );
        
        $columns = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
        
        return $columns;
        
    }
    
    /**
     * Add Thumbnail column to category/tag
     * @since 4.0
     */
    function edit_term_columns( $columns ) {
        
        $columns = array_merge( [ 'term_featured_image' => 'Thumbnail' ], $columns );
        return $columns;
        
    }
    
    /**
     * Render Thumbnail category/tag
     * @since 4.0
     */
    function manage_term_custom_column( $out, $column, $term_id ) {
        
        if ( 'term_featured_image' === $column ) {
            
            $thumbnail  = get_term_meta( $term_id, '_wi_thumbnail', true );
            if ( $thumbnail ) {
                $thumbnail = wp_get_attachment_image( $thumbnail, [ 80, 80 ] );
            }
            if ( ! $thumbnail ) $thumbnail = '<img src="' . get_template_directory_uri() . '/images/placeholder.png' . '" atl="No thumbnail" width="80" />';
            
            $out = sprintf( '<div class="term-meta-thumbnail">%s</div>', $thumbnail );
            
        }
        
        return $out;
        
    }
    
    /**
     * Register Plugins
     * Instagram Widget & Post Format UI is now a part of Theme package
     *
     * @since 1.0
     */
    function register_required_plugins (){
        
        $plugins = array (
            
            array(
                'name'     				=> 'Contact Form 7', // The plugin name
                'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
            ),
            
            /**
             * change repository since 4.0
             * when plugin is removed from WP repository
             */
            array(
                'name'     				=> '(Fox) Post Views Counter', // The plugin name
                'slug'     				=> 'fox-post-views-counter', // The plugin slug (typically the folder name)
                'version'               => '1.0',
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'source'                => get_template_directory_uri() . '/inc/admin/framework/plugins/fox-post-views-counter.zip',
            ),
            
            // recommended since 4.3
            array(
                'name'     				=> 'Instagram Feed',
                'slug'     				=> 'instagram-feed',
                'required' 				=> false,
            ),
            
            array(
                'name'     				=> 'Mailchimp for WP',
                'slug'     				=> 'mailchimp-for-wp',
                'required' 				=> false,
                'message'               => false,
            ),
            
            array(
                'name'     				=> 'Elementor Page Builder', // The plugin name
                'slug'     				=> 'elementor', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'message'               => false,
            ),
            
            array(
                'name'     				=> '(Fox) Elementor Addons', // The plugin name
                'slug'     				=> 'fox-framework', // The plugin slug (typically the folder name)
                'version'               => '1.2',
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'source'                => get_template_directory_uri() . '/inc/admin/framework/plugins/fox-framework.zip',
                'message'               => false,
            ),
            
            array(
                'name'     				=> 'WooCommerce', // The plugin name
                'slug'     				=> 'woocommerce', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'message'               => false,
            ),
            
            array(
                'name'     				=> 'Envato Market', // The plugin name
                'slug'     				=> 'envato-market', // The plugin slug (typically the folder name)
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'message'               => false,
                'source'                => get_template_directory_uri() . '/inc/admin/framework/plugins/envato-market.zip',
                'version'               => '2.0.3',
            ),
            
        );

        $config = array(
            'id'           => 'tgmpa',
            'default_path' => '',
            'menu'         => 'tgma-install-plugins',
            'parent_slug'  => 'fox',
            'capability'   => 'edit_theme_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => true,
            'message'      => '',
        );

        tgmpa( $plugins, $config );
    }
    
    function wp_enqueue_media() {
        wp_enqueue_media();
    }
    
    /**
     * Includes menu walker
     *
     * @since 2.8
     * removed since 4.3 due to native WordPress 5.4 support
     */
    function include_menu_walker() {
    
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'load_menu_walker' ), 99 );
        
    }
    
    /**
     * Loads menu walker
     *
     * @since 2.8
     * removed since 4.3 due to native WordPress 5.4 support
     */
    function load_menu_walker( $walker ) {
    
        $walker = 'Fox_Menu_Item_Custom_Fields_Walker';
        if ( ! class_exists( $walker ) ) {
            require_once get_template_directory() . '/inc/admin/framework/nav/walker-nav-menu-edit.php'; // custom walker to add fields
        }

        return $walker;
        
    }
    
    /**
     * Enqueue javascript & style for admin
     *
     * @since Fox 2.2
     */
    function enqueue(){
        
        // We need to upload image/media constantly
        wp_enqueue_media();
        
        // admin css
        wp_enqueue_style( 'wi-admin', FOX_ADMIN_URL . 'css/admin.css', array( 'wp-color-picker', 'wp-mediaelement' ) );
        
        // admin javascript
        wp_enqueue_script( 'wi-admin', FOX_ADMIN_URL . 'js/admin.js', array( 'wp-color-picker', 'wp-mediaelement' ), '20160326', true );
        
        // localize javascript
        $jsdata = apply_filters( 'wiadminjs', array() );
        wp_localize_script( 'wi-admin', 'WITHEMES_ADMIN' , $jsdata );
        
    }
    
    /**
     * Localization some text
     *
     * @since Fox 2.2
     */
    function l10n( $jsdata ) {
    
        $jsdata[ 'l10n' ] =  array(
        
            'choose_image' => esc_html__( 'Choose Image', 'wi' ),
            'change_image' => esc_html__( 'Change Image', 'wi' ),
            'upload_image' => esc_html__( 'Upload Image', 'wi' ),
            
            'choose_images' => esc_html__( 'Choose Images', 'wi' ),
            'change_images' => esc_html__( 'Change Images', 'wi' ),
            'upload_images' => esc_html__( 'Upload Images', 'wi' ),
            
            'choose_file' => esc_html__( 'Choose File', 'wi' ),
            'change_file' => esc_html__( 'Change File', 'wi' ),
            'upload_file' => esc_html__( 'Upload File', 'wi' ),
        
        );
        
        return $jsdata;
    
    }
    
    /**
     * Metaboxes
     *
     * @return $metaboxes
     *
     * @modified since 2.4
     * @since Fox 2.2
     */
    function metaboxes( $metaboxes ) {
    
        /* PAGE OPTIONS
        ---------------------------------------------------------------- */
        $fields = [];
        
        $fields[] = [
            'id' => 'style',
            'name' => 'Page Layout',
            'type' => 'image_radio',
            'std' => '',
            'tab' => 'general',
            'options' => [
                '' => [
                    'src' => get_template_directory_uri() . '/inc/admin/images/default.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Default',
                ],
                '1' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 1',
                ],
                '1b' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1b.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 1b',
                ],
                '2' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 2',
                ],
                '3' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 3',
                ],
                '4' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/4.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Hero full',
                ],
                '5' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/5.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Hero half',
                ],
            ],
        ];
        
        // since 4.3
        $fields[] = [
            'id' => 'hero_half_skin',
            'name' => 'Hero Half Theme',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'light' => 'Light',
                'dark' => 'Dark',
            ],
            'std' => '',
            'tab' => 'general',
            
            'dependency' => [
                'element' => 'style',
                'value' => '5',
            ],
        ];  
        
        $fields[] = [
            'id' => 'sidebar_state',
            'name' => 'Sidebar',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'sidebar-left' => 'Sidebar Left',
                'sidebar-right' => 'Sidebar Right',
                'no-sidebar' => 'No Sidebar',
            ],
            'std' => '',
            'tab' => 'general',
        ];
        
        $fields[] = [
            'id' => 'thumbnail_stretch',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'stretch-none' => 'No stretch',
                'stretch-bigger' => 'Stretch Wide',
                'stretch-container' => 'Container Width',
                'stretch-full' => 'Stretch Fullwidth',
            ],
            'std' => '',
            'name' => 'Thumbnail stretch',
            
            'tab' => 'general',
        ];
        
        $fields[] = array(
            'id' => 'subtitle',
            'name' => 'Subtitle',
            'type' => 'textarea',
            'desc' => 'Enter page subtitle',
            'tab' => 'general',
        );
        
        $fields[] = array(
            'id' => 'title_align',
            'name' => 'Page title align',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ],
            'tab' => 'general',
        );
        
        $components = [
            'post_header' => 'Title area',
            'thumbnail' => 'Thumbnail',
            'share' => 'Share icon',
        ];
        
        foreach ( $components as $com => $name ) {
            $desc = '';
            if ( 'comment' == $com ) {
                $desc = 'If you wanna enable page comment, please enable it in "Discussion" section too (below Featured Image option on the right side)';
            }
            
            $fields[] = array(
                'id' => $com,
                'name' => 'Show/Hide ' . $name,
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Default', 'wi' ),
                    'true' => esc_html__( 'Show it', 'wi' ),
                    'false' => esc_html__( 'Hide it', 'wi' ),
                ),
                'std' => '',
                'desc'  => $desc,
                'tab' => 'component',
            );
        }
        
        $fields[] = [
            'id' => 'show_header',
            'name' => 'Show Header',
            'type' => 'select',
            'options' => array(
                '' => esc_html__( 'Default', 'wi' ),
                'true' => esc_html__( 'Show it', 'wi' ),
                'false' => esc_html__( 'Hide it', 'wi' ),
            ),
            'std' => '',
            'tab' => 'component',
        ];
        
        $fields[] = [
            'id' => 'show_footer',
            'name' => 'Show Footer',
            'type' => 'select',
            'options' => array(
                '' => esc_html__( 'Default', 'wi' ),
                'true' => esc_html__( 'Show it', 'wi' ),
                'false' => esc_html__( 'Hide it', 'wi' ),
            ),
            'std' => '',
            'tab' => 'component',
        ];
        
        /**
         * Misc
         */
        $fields[] = [
            'type' => 'select',
            'id' => 'content_width',
            'options' => [
                '' => 'Default',
                'full' => 'Full width',
                'narrow' => 'Narrow width',
            ],
            'std' => '',
            'name' => 'Content width',
            
            'tab' => 'misc',
        ];
        
        $fields[] = [
            'type' => 'select',
            'id' => 'content_image_stretch',
            'options' => [
                '' => 'Default',
                'stretch-none' => 'No stretch',
                'stretch-bigger' => 'Stretch Wide',
                'stretch-full' => 'Stretch Fullwidth',
            ],
            'std' => '',
            'name' => 'Content image stretch',
            'desc' => 'If you choose "Stretch Wide", it stretches all possible images in the post.',
            
            'tab' => 'misc',
        ];
        
        $fields[] = [
            'id' => 'padding_top',
            'name' => 'Padding top',
            'type' => 'text',
            'placeholder' => '20px',
            'tab' => 'misc',
        ];
        
        $fields[] = [
            'id' => 'padding_bottom',
            'name' => 'Padding bottom',
            'type' => 'text',
            'placeholder' => '20px',
            'tab' => 'misc',
        ];
            
        $fields[] = array(
            'id' => 'column_layout',
            'name' => 'Text Column Layout',
            'type' => 'select',
            'options' => array(
                '' => esc_html__( 'Default', 'wi' ),
                '1' => '1 column',
                '2' => '2 columns',
            ),
            'std' => '',
            'tab' => 'misc',
        );

        $fields[] = array(
            'id' => 'dropcap',
            'name' => 'Dropcap first letter',
            'type' => 'select',
            'options' => array(
                '' => 'Default',
                'true' => 'Enable',
                'false' => 'Disable',
            ),
            'std' => '',

            'tab' => 'misc',
        );
        
        $metaboxes[ 'page-settings' ] = array(
            
            'id' => 'page-settings',
            'screen' => array( 'page' ),
            'title' => esc_html__( 'Page Settings', 'wi' ),
            'tabs' => [
                'general' => 'General',
                'component' => 'Components',
                'misc' => 'Miscellaneous'
            ],
            'fields' => $fields,
        
        );
        
        /* POST OPTIONS
        ---------------------------------------------------------------- */
        $fields = [];
        
        $fields[] = [
            'id' => 'style',
            'name' => 'Single Post Layout',
            'type' => 'image_radio',
            'std' => '',
            'tab' => 'general',
            'options' => [
                '' => [
                    'src' => get_template_directory_uri() . '/inc/admin/images/default.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Default',
                ],
                '1' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 1',
                ],
                '1b' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1b.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 1b',
                ],
                '2' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 2',
                ],
                '3' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Layout 3',
                ],
                '4' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/4.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Hero full',
                ],
                '5' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/5.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Hero half',
                ],
            ],
        ];
        
        // since 4.3
        $fields[] = [
            'id' => 'hero_half_skin',
            'name' => 'Hero Half Theme',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'light' => 'Light',
                'dark' => 'Dark',
            ],
            'std' => '',
            'tab' => 'general',
            
            'dependency' => [
                'element' => 'style',
                'value' => '5',
            ],
        ];  
        
        $fields[] = [
            'id' => 'sidebar_state',
            'name' => 'Sidebar',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'sidebar-left' => 'Sidebar Left',
                'sidebar-right' => 'Sidebar Right',
                'no-sidebar' => 'No Sidebar',
            ],
            'std' => '',
            'tab' => 'general',
        ];
        
        $fields[] = [
            'id' => 'thumbnail_stretch',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'stretch-none' => 'No stretch',
                'stretch-bigger' => 'Stretch Wide',
                'stretch-container' => 'Container Width',
                'stretch-full' => 'Stretch Fullwidth',
            ],
            'std' => '',
            'name' => 'Thumbnail stretch',
            
            'tab' => 'general',
        ];
        
        /**
         * General
         */
        $fields[] = array(
            'id' => 'subtitle',
            'name' => 'Subtitle',
            'type' => 'textarea',
            'desc' => 'Enter post subtitle',
            'tab' => 'general',
        );
        
        $fields[] = array(
            'id' => '_is_featured',
            'name' => 'Feature This Post?',
            'prefix'=> false,
            'type' => 'checkbox',
            'desc' => 'Check this to make this post become a featured post.',
            'value' => 'yes',

            'tab' => 'general',
        );
        
        $fields[] = array(
            'id' => '_is_live',
            'name' => 'LIVE Post?',
            'prefix'=> false,
            'type' => 'checkbox',
            'desc' => 'Live post is a post with live update for breaking news. It will be indicated on frontpage and in the post header that it\'s a live post',
            'value' => 'true',

            'tab' => 'general',
        );
        
        $categories = get_categories( array(
            'fields' => 'id=>name',
            'orderby'=> 'slug',
            'hide_empty' => false,
            
            'number' => 100, // prevent huge blogs
        ));
        
        $categories = [ '' => 'Auto' ] + $categories;

        $fields[] = array(
            'id' => 'primary_cat',
            'name' => 'Primary Category',
            'type' => 'select',
            'options' => $categories,
            'std' => '',
            'desc' => 'Primary category will be used to render related posts and bottom posts when possible. If your post has only 1 category, that\'s primary one. Otherwise, primary category will be picked by the first category alphabetically.',

            'tab' => 'general',
        );
        
        $components = fox_single_element_support();
        
        foreach ( $components as $com => $name ) {
            
            $fields[] = fox_generate_option( $com, 'post-meta' );
            
        }
        
        $fields[] = [
            'id' => 'show_header',
            'name' => 'Show Header',
            'type' => 'select',
            'options' => array(
                '' => esc_html__( 'Default', 'wi' ),
                'true' => esc_html__( 'Show it', 'wi' ),
                'false' => esc_html__( 'Hide it', 'wi' ),
            ),
            'std' => '',
            'tab' => 'component',
        ];
        
        $fields[] = [
            'id' => 'show_footer',
            'name' => 'Show Footer',
            'type' => 'select',
            'options' => array(
                '' => esc_html__( 'Default', 'wi' ),
                'true' => esc_html__( 'Show it', 'wi' ),
                'false' => esc_html__( 'Hide it', 'wi' ),
            ),
            'std' => '',
            'tab' => 'component',
        ];
        
        /* Post Format
        -------------------------------- */
        $fields[] = array(
            'id' => 'post_format',
            'prefix' => false,
            'name' => 'Post Format',
            'type' => 'select',
            'std' => '',
            'options' => [
                '' => 'Standard',
                'video' => 'Video',
                'audio' => 'Audio',
                'gallery' => 'Gallery',
                'link' => 'Link',
            ],
            'save' => false,

            'tab' => 'format',
        );
        
        /* Video
        -------------------------------- */
        $fields[] = array(
            'id' => '_format_video_embed',
            'name' => 'Video Embed Code',
            'desc' => 'Paste <strong>YouTube</strong>, <strong>Facebook</strong>, <strong>Vimeo</strong> video URL',
            'type' => 'textarea',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'video',
            ],

            'tab' => 'format',
        );
        
        $fields[] = array(
            'id' => '_format_video',
            'name' => 'Upload your own video',
            'type' => 'upload',
            'file_type' => 'video',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'video',
            ],

            'tab' => 'format',
        );
        
        /* Audio
        -------------------------------- */
        /**
         * Format Audio
         */
        $fields[] = array(
            'id' => '_format_audio_embed',
            'name' => 'Audio Embed Code',
            'type' => 'textarea',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'audio',
            ],

            'tab' => 'format',
        );

        // self-hosted audio
        $fields[] = array(
            'id' => '_format_audio',
            'name' => 'Upload your own audio',
            'type' => 'upload',
            'file_type' => 'audio',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'audio',
            ],

            'tab' => 'format',
        );
        
        /* Link
        -------------------------------- */
        $fields[] = array(
            'id' => '_format_link_url',
            'name' => 'Format link URL',
            'type' => 'text',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'link',
            ],

            'tab' => 'format',
        );
        
        /* Gallery
        -------------------------------- */
        $fields[] = array (
            'id' => '_format_gallery_images',
            'name' => 'Gallery Images',
            'type' => 'images',
            'prefix' => false,

            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'gallery',
            ],

            'tab' => 'format',
        );
        
        $fields[] = [
            'id' => 'format_gallery_style',
            'type' => 'image_radio',
            
            'tab' => 'format',
            
            'dependency' => [
                'element' => 'post_format',
                'element_prefix' => false,
                'value' => 'gallery',
            ],
            
            'options' => [
                '' => [
                    'src' => get_template_directory_uri() . '/inc/admin/images/default.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Default',
                ],
                'metro' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/metro.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Metro',
                ],
                'stack' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/stack.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Stack Images',
                ],
                'slider' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/slider.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Slider',
                ],
                'slider-rich' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/slider-rich.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Rich Content Slider',
                ],
                'carousel' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/carousel.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Carousel',
                ],
                'grid' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/grid.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Grid',
                ],
                'masonry' => [
                    'src' => get_template_directory_uri() . '/inc/customizer/assets/img/masonry.png',
                    'width' => 80,
                    'height' => 80,
                    'title' => 'Masonry',
                ],
            ],
            'std' => '',
            'name' => 'Gallery Default Style',
        ];
        
        $fields[] = fox_generate_option( 'format_gallery_lightbox', 'post-meta' );
        
        // slider
        $fields[] = fox_generate_option( 'format_gallery_slider_effect', 'post-meta' );
        $fields[] = fox_generate_option( 'format_gallery_slider_size', 'post-meta' );
        
        // grid
        $fields[] = fox_generate_option( 'format_gallery_grid_column', 'post-meta' );
        $fields[] = fox_generate_option( 'format_gallery_grid_size', 'post-meta' );
        $fields[] = fox_generate_option( 'format_gallery_grid_size_custom', 'post-meta' );
        
        /* Review
        -------------------------------- */
        $fields[] = array(
            'id' => 'review',
            'name' => esc_html__( 'Review', 'wi' ),
            'type' => 'review',

            'tab' => 'review',
        );
        
        $fields[] = array(
            'id' => 'review_text',
            'name' => esc_html__( 'Custom Text', 'wi' ),
            'type' => 'textarea',

            'tab' => 'review',
        );
        
        for ( $i = 1; $i <= 2; $i++ ) {
            $fields[] = array(
                'id' => "review_btn{$i}_url",
                'name' => "Button {$i} URL",
                'type' => 'text',
                'placeholder' => 'https://',
                'tab' => 'review',
            );

            $fields[] = array(
                'id' => "review_btn{$i}_text",
                'name' => "Button {$i} Text",
                'type' => 'text',
                'placeholder' => 'Click Here',

                'tab' => 'review',
            );
        }
        
        /* Sponsored Post
         * @since 4.2
        -------------------------------- */
        $fields[] = array(
            'id' => 'sponsored',
            'name' => 'This is sponsored Post?',
            'type' => 'select',
            'options' => [
                'true' => 'Yes',
                'false' => 'No',
            ],
            'std' => 'false',
            'tab' => 'sponsor',
        );
        
        $fields[] = array(
            'id' => 'sponsor_name',
            'name' => 'Sponsor Name',
            'type' => 'text',
            'tab' => 'sponsor',
        );
        
        $fields[] = array(
            'id' => 'sponsor_url',
            'name' => 'Sponsor URL',
            'type' => 'text',
            'placeholder' => 'https://',
            'tab' => 'sponsor',
        );
        
        $fields[] = array(
            'id' => 'sponsor_image',
            'name' => 'Sponsor Image',
            'type' => 'image',
            'tab' => 'sponsor',
        );
        
        $fields[] = array(
            'id' => 'sponsor_image_width',
            'name' => 'Sponsor Image Width',
            'type' => 'text',
            'tab' => 'sponsor',
        );
        
        $fields[] = array(
            'id' => 'sponsor_label',
            'name' => 'Sponsor Label',
            'type' => 'text',
            'placeholder' => 'Sponsored',
            'tab' => 'sponsor',
        );
            
        /**
         * Misc
         */
        
        $fields[] = array(
            'id' => 'blog_thumbnail',
            'name' => 'Custom Blog Thumbnail',
            'type' => 'image',
            'desc' => 'Upload custom blog thumbnail if you want your blog thumbnail different from your single post thumbnail',

            'tab' => 'misc',
        );
        
        $fields[] = [
            'id' => 'autoload',
            'name' => 'Autoload on this post',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'true' => 'Enable',
                'false' => 'Disable',
            ],
            'desc' => 'Use this option if you wanna disable "autoload next post" feature just on this post.',
            
            'tab' => 'misc',
        ];
        
        // since 4.1
        $fields[] = [
            'id' => 'reading_progress',
            'name' => 'Reading Progress Bar',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'true' => 'Enable',
                'false' => 'Disable',
            ],
            
            'tab' => 'misc',
        ];
        
        $fields[] = [
            'type' => 'select',
            'id' => 'content_width',
            'options' => [
                '' => 'Default',
                'full' => 'Full width',
                'narrow' => 'Narrow width',
            ],
            'std' => '',
            'name' => 'Content width',
            
            'tab' => 'misc',
        ];
        
        $fields[] = [
            'type' => 'select',
            'id' => 'content_image_stretch',
            'options' => [
                '' => 'Default',
                'stretch-none' => 'No stretch',
                'stretch-bigger' => 'Stretch Wide',
                'stretch-full' => 'Stretch Fullwidth',
            ],
            'std' => '',
            'name' => 'Content image stretch',
            'desc' => 'If you choose "Stretch Wide", it stretches all possible images in the post.',
            
            'tab' => 'misc',
        ];
        
        $fields[] = fox_generate_option( 'column_layout', 'post-meta' );
        
        $fields[] = fox_generate_option( 'blog_dropcap', 'post-meta', [
            'name' => 'Dropcap on blog posts',
        ] );
        
        $fields[] = fox_generate_option( 'dropcap', 'post-meta', [
            'name' => 'Dropcap on single post',
        ] );
        
        $metaboxes[ 'post-settings' ] = array (
            
            'id' => 'post-settings',
            'screen' => array( 'post' ),
            'title' => esc_html__( 'Post Settings', 'wi' ),
            'tabs' => [
                
                'general' => 'General',
                'component' => 'Components',
                'format' => 'Format Options',
                'review' => 'Review',
                'sponsor' => 'Sponsor',
                'misc' => 'Miscellaneous',
                
            ],
            'fields' => $fields,
        
        );
        
        return $metaboxes;
    
    }
    
    /**
     * Term Metaboxes
     * since 4.0
     */
    function term_metaboxes( $metaboxes ) {
        
        $sidebars = [
            '' => 'Default',
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
            'no-sidebar' => 'No Sidebar',
        ];
        
        $layouts = array_merge( [ '' => 'Default' ], fox_archive_layout_support() );
        $toparea_layouts = array_merge( [ '' => 'Default' ], fox_builder_layout_support() );
        
        $fields = [];
        
        $fields[] = [
            'id' => 'layout',
            'name' => 'Layout',
            'type' => 'select',
            'options' => $layouts,
            'std' => '',
        ];
        
        $fields[] = [
            'id' => 'sidebar_state',
            'name' => 'Sidebar State',
            'type' => 'select',
            'options' => $sidebars,
            'std' => '',
        ];
        
        $fields[] = [
            'id' => 'toparea_display',
            'name' => 'Toparea displays',
            'type' => 'select',
            'options' => [
                '' => 'Default',
                'none' => 'None',
                'view' => 'Most Viewed Posts',
                'comment_count' => 'Most Commented Posts',
                'featured' => 'Featured Posts (Starred Posts)',
            ],
            'std' => '',
        ];
        
        $fields[] = [
            'id' => 'toparea_layout',
            'name' => 'Toparea Layout',
            'type' => 'select',
            'options' => $toparea_layouts,
            'std' => '',
        ];
        
        $fields[] = [
            'id' => 'toparea_number',
            'name' => 'Toparea number of posts to show',
            'type' => 'text',
            'std' => '',
        ];
        
        $fields[] = [
            'id' => 'thumbnail',
            'name' => 'Thumbnail',
            'desc' => 'Used in grid of categories.',
            'type' => 'image',
        ];
        
        $fields[] = [
            'id' => 'background_image',
            'name' => 'Background Image',
            'desc' => 'Used in category page as the header background.',
            'type' => 'image',
        ];
        
        $metaboxes[ 'term-settings' ] = array (
            
            'id' => 'term-settings',
            'screen' => array( 'category', 'post_tag' ),
            'fields' => $fields,
        
        );
        
        return $metaboxes;
        
    }
    
}

Wi_Admin::instance()->init();

endif; // class exists