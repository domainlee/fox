<?php
if ( ! class_exists( 'Fox_Sidebar' ) ) :
/**
 * The sidebar class to settle all problems about sidebar
 * @since 4.0
 */
class Fox_Sidebar {

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
        
        // ADMIN
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );
        add_filter( 'fox_metaboxes', array( $this, 'metaboxes' ) );
        add_filter( 'fox_term_metaboxes', array( $this, 'term_metaboxes' ) );
        
        // AJAX
        add_action( 'wp_ajax_fox_sidebar_ajax_action', array( $this, 'process_sidebar' ), 10 );
        
        // REGISTER WIDGETS accordingly
        add_action( 'widgets_init', array( $this, 'register_sidebars' ), 150 );
        add_action( 'wp_head', array( $this, 'replace_sidebar' ), 150 );
        
        // FRONTEND
        add_action( 'body_class', [ $this, 'body_class' ] ); // for everything but posts
        add_action( 'post_class', [ $this, 'post_class' ] ); // only for posts
        add_filter( 'fox_sidebar_state', [ $this, 'sidebar_state' ], 10 );
        
    }
    
    /**
     * Register the custom sidebars
     * @since 4.0
     */
    function register_sidebars() {
        
        $sidebars = $this->get_sidebars();
        if ( empty( $sidebars ) ) return;
        
        foreach( $sidebars as $slug => $sidebar_data ) {
            $name = isset( $sidebar_data[ 'name' ] ) ? $sidebar_data[ 'name' ] : '';
            if ( ! $name ) $name = $slug;
            
            register_sidebar(
                array(
                    'name'              => $name,
                    'id'                => $slug,
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h3 class="widget-title"><span>',
                    'after_title'   => '</span></h3>',
                )
            );
            
        }
    
    }
    
    function replace_sidebar() {
        
        global $_wp_sidebars_widgets;
        
        $positions = fox_sidebar_possition_support();
        
        // Make a clone
        $clone = $_wp_sidebars_widgets;
        
        if ( is_single() ) {
            
            foreach ( $positions as $id => $name ) {
                
                $custom_sidebar = get_post_meta( get_the_ID(), '_wi_sidebar_'. $id, true );
                if ( ! $custom_sidebar ) continue;
                
                // replace it
                if ( isset( $clone[ $custom_sidebar ] ) ) {
                    $_wp_sidebars_widgets[ $id ] = $clone[ $custom_sidebar ];
                }
                
            }
            
        } elseif ( is_page() ) {
        
            $page_positions = fox_sidebar_possition_support([ 'main' => [ 'page-sidebar' => 'Page Sidebar' ] ]);
            
            foreach ( $page_positions as $id => $name ) {
                
                $custom_sidebar = get_post_meta( get_the_ID(), '_wi_sidebar_'. $id, true );
                if ( ! $custom_sidebar ) continue;
                
                // replace it
                if ( isset( $clone[ $custom_sidebar ] ) ) {
                    $_wp_sidebars_widgets[ $id ] = $clone[ $custom_sidebar ];
                }
                
            }
        
        } elseif ( is_category() || is_tag() ) {
            
            $term_id = get_queried_object_id();
            
            foreach ( $positions as $id => $name ) {
                
                $custom_sidebar = get_term_meta( $term_id, '_wi_sidebar_'. $id, true );
                if ( ! $custom_sidebar ) continue;
                
                // replace it
                if ( isset( $clone[ $custom_sidebar ] ) ) {
                    $_wp_sidebars_widgets[ $id ] = $clone[ $custom_sidebar ];
                }
                
            }
        
        }
    
    }
    
    /**
     * Add a metabox field to post / page
     * @since 4.0
     */
    function metaboxes( $metaboxes ) {
        
        $all = $this->get_sidebars();
        $sidebars = [];
        foreach ( $all as $slug => $sidebar_data ) {
            
            $name = isset( $sidebar_data[ 'name' ] ) ? $sidebar_data[ 'name' ] : '';
            $sidebars[ $slug ] = $name;
            
        }
        
        $post_positions = fox_sidebar_possition_support();
        $page_positions = fox_sidebar_possition_support([ 'main' => [ 'page-sidebar' => 'Page Sidebar' ] ]);
        
        if ( isset( $metaboxes[ 'post-settings' ] ) ) {
            
            $fields = [];
            foreach ( $post_positions as $pos => $pos_name ) {

                $options = array_merge( [ '' => 'Default' ], $sidebars );

                $fields[] = [
                    'id' => 'sidebar_' . $pos,
                    'type' => 'select',
                    'options' => $options,
                    'std' => '',
                    'name' => 'Custom Sidebar for ' . $pos_name,
                    'tab' => 'sidebar_replacement',
                ];

            }
            
            $metaboxes[ 'post-settings' ][ 'tabs' ][ 'sidebar_replacement' ] = 'Sidebar Replacement';
            $metaboxes[ 'post-settings' ][ 'fields' ] = array_merge( $metaboxes[ 'post-settings' ][ 'fields' ], $fields );
            
        }
        
        if ( isset( $metaboxes[ 'page-settings' ] ) ) {
            
            $fields = [];
            foreach ( $page_positions as $pos => $pos_name ) {

                $options = array_merge( [ '' => 'Default' ], $sidebars );

                $fields[] = [
                    'id' => 'sidebar_' . $pos,
                    'type' => 'select',
                    'options' => $options,
                    'std' => '',
                    'name' => 'Custom Sidebar for ' . $pos_name,
                    'tab' => 'sidebar_replacement',
                ];

            }
            
            $metaboxes[ 'page-settings' ][ 'tabs' ][ 'sidebar_replacement' ] = 'Sidebar Replacement';
            $metaboxes[ 'page-settings' ][ 'fields' ] = array_merge( $metaboxes[ 'page-settings' ][ 'fields' ], $fields );
            
        }
        
        return $metaboxes;
        
    }
    
    /**
     * Term Metaboxes
     * For categories, tags etc
     * @since 4.0
     */
    function term_metaboxes( $metaboxes ) {
        
        $all = $this->get_sidebars();
        $sidebars = [];
        foreach ( $all as $slug => $sidebar_data ) {
            
            $name = isset( $sidebar_data[ 'name' ] ) ? $sidebar_data[ 'name' ] : '';
            $sidebars[ $slug ] = $name;
            
        }
        
        $positions = fox_sidebar_possition_support();
        
        $fields = [];
        
        foreach ( $positions as $pos => $pos_name ) {
            
            $options = array_merge( [ '' => 'Default' ], $sidebars );
            
            $fields[] = [
                'id' => 'sidebar_' . $pos,
                'type' => 'select',
                'options' => $options,
                'std' => '',
                'name' => $pos_name . ': ' ,
            ];
            
        }
        
        if ( isset( $metaboxes[ 'term-settings' ] ) ) {
            
            $metaboxes[ 'term-settings' ][ 'fields' ] = array_merge( $metaboxes[ 'term-settings' ][ 'fields' ], $fields );
            
        }
        
        return $metaboxes;
    
    }
    
    /**
     * add an admin menu
     * @since 4.0
     */
    function admin_menu() {
    
        // add admin page to Appearance
        $hook = add_submenu_page(
            'fox',
            'Sidebar Manager',
            'Sidebar Manager',
            'manage_options',
            'sidebar-manager',
            array( $this, 'create_admin_page')
        );
    
    }
    
    /**
     * Options page callback
     *
     * @since 1.0.0
     */
    public function create_admin_page() {
        
        // retrive the list of all sidebars
        // array[ 'id' => sidebar_data ]
        $sidebars = $this->get_sidebars();
        
        ?>

        <div class="wrap">
            
            <h1>Sidebar Manager</h1>
            
            <div class="instruction">
                
                <p style="font-size:1.3em">You can create additional sidebars here.</p>
            
            </div>
            
            <div class="fox-wrap">
                
                <div class="fox-wrapper" style="max-width:600px;">
                    
                    <form>
                        
                        <?php wp_nonce_field( 'fox-sidebar-processor-action','fox_ajax_processor_nonce' ); ?>
                        
                        <p>
                            
                            <label for="sidebar_name">
                                <span>Sidebar Name</span>
                                <input name="sidebar_name" type="text" size="18" id="sidebar_name" value="" placeholder="My Sidebar">
                            </label>
                            
                            <label for="sidebar_slug">
                                <span>Sidebar Slug</span>
                                <input name="sidebar_slug" type="text" size="18" id="sidebar_slug" value="" placeholder="my-sidebar">
                            </label>
                            
                            <button class="button button-primary fox-add-sidebar" data-type="add"><?php _e('+ Add sidebar', 'wi');?></button>
                            
                            <span class="spinner"></span>
                        </p>
                        
                        <table class="widefat" id="fox-table">
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th width="10%">Delete</th>
                            </tr>

                            <?php if( empty( $sidebars ) ) : ?>
                            
                            <tr class="no-sidebar-tr">
                                
                                <td colspan="3">No Custom Sidebars</td>    
                                
                            </tr>
                            
                            <?php else: ?>

                            <?php foreach ( ( array ) $sidebars as $slug => $sidebar_data ) : $name = isset( $sidebar_data[ 'name' ] ) ? $sidebar_data[ 'name' ] : $slug ?>
                            
                            <tr>
                                <td><?php echo esc_html( $name );?></td>
                                
                                <td><?php echo esc_html( $slug );?></td>
                                
                                <td><button class="button button-small fox-remove-sidebar" data-type="remove" data-name="<?php echo esc_attr( $name ); ?>" data-slug="<?php echo esc_attr( $slug );?>">Delete</button></td>
                            </tr>
                            
                            <?php endforeach;
        
                            endif; // empty_sidebar ?>
                            
                        </table>
                        
                        <p class="fox-notice"></p>
                        
                    </form>
                    
                </div>
                
            </div>
            
        </div><!-- .wrap -->

        <?php
    }
    
    /**
     * Get All Sidebars
     * return array of all sidebars
     * @since 4.0
     */
    function get_sidebars() {
        
        $sidebars = get_option( 'fox_sidebars' );
        if ( empty( $sidebars ) ) return [];
        else return $sidebars;
        
    }
    
    /**
     * Update action
     * @since 4.0
     */
    function update_sidebars( $sidebars ) {
        
        $update = update_option( 'fox_sidebars', $sidebars );
        if ( ! $update ) {
            add_option( 'fox_sidebars', $sidebars );
        }
        
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        
        foreach ( $sidebars as $slug => $sidebar_data ) {
            
            if ( ! isset( $sidebars_widgets[ $slug ] ) ) {
                $sidebars_widgets[ $slug ] = [];
            }
            
        }
        
        update_option( 'sidebars_widgets', $sidebars_widgets );
    
    }
    
    /**
     * Check if this sidebar exists or not
     * @since 4.0
     */
    function has_sidebar( $slug ) {
        
        $sidebars = $this->get_sidebars();
        return isset( $sidebars[ $slug ] );
        
    }
    
    /**
     * add a sidebar based on its data
     * @since 4.0
     */
    function add_sidebar( $args = [] ) {
        
        extract( wp_parse_args( $args, [
            'slug' => '',
            'name' => '',
        ] ) );
        
        $sidebars = $this->get_sidebars();
        $sidebars[ $slug ] = [
            'slug' => $slug,
            'name' => $name,
        ];
        
        $this->update_sidebars( $sidebars );
        
    }
    
    /**
     * Add/Remove sidebar in action
     * @since 4.0
     */
    public function process_sidebar() {
        
        if( empty( $_POST['nonce'] )) die('-1'); 

        $nonce = $_POST['nonce'];

        $action = 'fox-sidebar-processor-action';
        
        /**
         * Check sercurity
         */
        $adminurl = strtolower( admin_url() );
        $referer = strtolower( wp_get_referer() );
        check_admin_referer( $action, 'nonce' );

        $name = isset( $_POST['name'] ) ? $_POST['name'] : '';
        $slug = isset( $_POST['slug'] ) ? $_POST['slug'] : '';
        
        $action_type = !empty( $_POST['type'] ) ? $_POST['type'] : '';

        if( ! in_array( $action_type, array( 'add', 'remove' ) ) )
            die( '-1' );
        
        // when both name and slug is empty
        if ( empty( $name ) && empty( $slug ) )
            die( '-1' );
        
        $return = array(
            'success' => true,
        );
        
        $sidebars = $this->get_sidebars();
        
        /**
         * Add New Sidebar
         */
        if ( 'add' == $action_type ) {
            
            $sidebar_name = $name;
            $sidebar_slug = $slug;
            
            // get slug from name
            if ( empty( $slug ) ) {
                
                $sidebar_slug = preg_replace ("/ +/", " ", $sidebar_name); // convert all multispaces to space
                $sidebar_slug = str_replace( ' ', '-', $sidebar_slug );
                $sidebar_slug = sanitize_key( $sidebar_slug );
            
            // get name from slug    
            } elseif ( empty( $name ) ) {
                
                $sidebar_name = $sidebar_slug;
                
            }
            
            $return['data'] = array(
                
                'message'   => 'Sidebar Added Successfully!',
                'type'      => $action_type,
                'slug'      => $sidebar_slug,
                'name'      => $sidebar_name,
                
            );

            if( isset( $sidebars[ $sidebar_slug ] ) ) {
                
                $return[ 'success' ] = false;
                $return[ 'data' ][ 'message' ] = 'Sidebar already exists, please use a different name.';
                
            } else {

                $sidebars[ $sidebar_slug ] = [
                    'name' => $sidebar_name,
                    'slug' => $sidebar_slug,
                ];
                
                $this->update_sidebars( $sidebars );

            }
          
        // remove    
        } else {
            
            if ( isset( $sidebars[ $slug ] ) ) {
                
                if ( ! $name ) $name = $slug;
                
                $return['data'] = array(
                    'message'   => 'Sidebar "' . $name . '" has been removed.',
                    'type'      => $action_type,
                );
                
                unset( $sidebars[ $slug ] );
                $this->update_sidebars( $sidebars );
            
            } else {
                
                $return[ 'success' ] = false;
                $return['data'] = array(
                    'message'   => 'Sidebar doesn\'t exists',
                    'type'      => $action_type,
                );
            }


        }

        wp_send_json( $return );

    }
    
    /**
     * The filter to return sidebar state
     * @since 4.0
     */
    function sidebar_state( $state ) {
        
        if ( is_home() ) {

            $state = get_theme_mod( 'wi_home_sidebar_state', 'sidebar-right' );

        } elseif ( is_category() || is_tag() ) {

            $obj_id = get_queried_object_id();
            $state = get_term_meta( $obj_id, '_wi_sidebar_state', true );

            // legacy attemp
            if ( ! $state ) {
                $state = get_theme_mod( "taxonomy_{$obj_id}" );
            }

            if ( ! $state ) {

                if ( is_category() ) {

                    $state = get_theme_mod( 'wi_category_sidebar_state', 'sidebar-right' );

                } else {

                    $state = get_theme_mod( 'wi_tag_sidebar_state', 'sidebar-right' );

                }

            }

        } elseif ( is_search() ) {

            $state = get_theme_mod( 'wi_search_sidebar_state', 'sidebar-right' );

        } elseif ( is_author() ) {

            $state = get_theme_mod( 'wi_author_sidebar_state', 'sidebar-right' );

        } elseif ( is_archive() ) {

            $state = get_theme_mod( 'wi_archive_sidebar_state', 'sidebar-right' );

        } elseif ( is_singular() ) {

            // legacy
            if ( is_page_template( 'page-fullwidth.php' ) || is_page_template( 'page-one-column.php' ) ) {

                $state = 'no-sidebar';

            } else {

                $state = get_post_meta( get_the_ID(), '_wi_sidebar_state', true );
                
                // legacy, old meta key _wi_sidebar_layout
                if ( ! $state ) {
                    $state = get_post_meta( get_the_ID(), '_wi_sidebar_layout', true );
                }

                if ( ! $state ) {

                    if ( is_page() ) {

                        $state = get_theme_mod( 'wi_page_sidebar_state', 'sidebar-right' );

                    } else {

                        $state = get_theme_mod( 'wi_single_sidebar_state', 'sidebar-right' );

                    }

                }

            }

        }
        
        return $state;
    
    }
    
    /**
     * add appropriate class into body
     * @since 4.0
     */
    function body_class( $classes ) {

        if ( ! is_single() ) {
            
            $state = fox_sidebar_state();

            if ( 'sidebar-left' == $state ) {

                $classes[] = 'has-sidebar sidebar-left';

            } elseif ( 'sidebar-right' == $state ) {

                $classes[] = 'has-sidebar sidebar-right';

            } else {

                $classes[] = 'no-sidebar';

            }
            
        }

        return $classes;

    }
    
    /**
     * add appropriate class into post
     * @since 4.0
     */
    function post_class( $classes ) {

        if ( is_single() ) {
            
            $state = fox_sidebar_state();

            if ( 'sidebar-left' == $state ) {

                $classes[] = 'has-sidebar sidebar-left';

            } elseif ( 'sidebar-right' == $state ) {

                $classes[] = 'has-sidebar sidebar-right';

            } else {

                $classes[] = 'no-sidebar';

            }
            
        }

        return $classes;

    }

}

$new_fox_sidebar = new Fox_Sidebar();
$new_fox_sidebar->init();

endif;

if ( ! function_exists( 'fox_sidebar_state' ) ) :
/**
 * Sidebar State
 * @since 4.0
 *
 * return 'sidebar-left' / 'sidebar-right' or 'no-sidebar'
 */
function fox_sidebar_state() {
    
    $state = apply_filters( 'fox_sidebar_state', 'no-sidebar' );
    
    // final check by logic
    if ( 'sidebar-left' != $state && 'sidebar-right' != $state ) $state = 'no-sidebar';
    
    return $state;
    
}
endif;

if ( ! function_exists( 'fox_sidebar' ) ) :
/**
 * Display sidebar if sidebar_state returns true
 * @since 4.0
 */
function fox_sidebar( $sidebar = 'sidebar' ) {

    if ( 'no-sidebar' != fox_sidebar_state() ) get_sidebar( $sidebar );
    
}
endif;

if ( ! function_exists( 'fox_add_sidebar' ) ) :
/**
 * add new custom sidebar from outer scope of class
 * @since 4.2
 */
function fox_add_sidebar( $args ) {
    
    $new_fox_sidebar = new Fox_Sidebar();
    $new_fox_sidebar->add_sidebar( $args );
    
}
endif;