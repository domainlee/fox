<?php
if ( ! class_exists( 'Fox_Menu_Item_Custom_Fields' ) ) :
/**
 * Menu Item Custom Fields
 *
 * @since 1.0
 */
class Fox_Menu_Item_Custom_Fields 
{
    
    /**
	 *
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of class
	 *
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * Instantiate or return the one class instance
	 *
	 * @since 1.0
	 *
	 * @return the class
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 */
	protected static $fields = array();


	/**
	 * Initialize plugin
	 */
	public function init() {
        
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( $this, '_save' ), 10, 3 );
		// add_filter( 'manage_nav-menus_columns', array( $this, '_columns' ), 99 );
		
		/* -------------------------------------------------------------------------------------------------------------------------- */
		/* EDIT FIELDS HERE 
		/* -------------------------------------------------------------------------------------------------------------------------- */
		self::$fields = array ();
        
		self::$fields[] = array(
			'id'		=>	'mega',
			'name'		=>	'Enable Mega Menu?',
			'type'		=>	'checkbox',
		);
        
        self::$fields[] = array(
			'id'		=>	'menu-icon',
			'name'		=>	'Menu icon',
			'desc'		=>	'Enter icon name from <strong>FontAwesome</strong> or <strong>Feather</strong> icon. You can also enter an image URL. See the detail guide <a href="https://foxdocs.withemes.com/header/#menu-icon" target="_blank">here</a>.',
			'type'		=>	'text',
			'std'		=>	'',
		);
        
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'edit';
		if ($action == 'add-menu-item') check_admin_referer( 'add-menu_item', 'menu-settings-column-nonce' );
		if ($action == 'update') check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $k => $v) :
			$_key = isset($v['id']) ? $v['id'] : ''; if (!$_key) continue;
			$key = sprintf( 'menu-item-%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		endforeach;
	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
        
        foreach ( self::$fields as $k => $v) :
			$_key = isset($v['id']) ? $v['id'] : ''; if (!$_key) continue;
			$type = isset($v['type']) ? $v['type'] : '';
			$label = isset($v['name']) ? $v['name'] : '';
			$desc = isset($v['desc']) ? $v['desc'] : '';
			$std = isset($v['std']) ? $v['std'] : '';
			$type = isset($v['type']) ? $v['type'] : 'text';
			$options = isset($v['options']) ? $v['options'] : array();
			$key   = sprintf( 'menu-item-%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			
			switch($type):
				case 'select': if ( ! $value ) $value = isset( $v[ 'std' ] ) ? $v[ 'std' ] : '';
				?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr($id);?>">
							<?php echo esc_html( $label );?><br>
							<select id="<?php echo esc_attr($id);?>" class="widefat" name="<?php echo esc_attr( $name );?>">
								<?php foreach ( $options as $opt_k => $opt_v ):?>
								<option value="<?php echo esc_attr($opt_k);?>" <?php selected($value,esc_attr($opt_k)) ?>><?php echo esc_html($opt_v);?></option>
								<?php endforeach; ?>
							</select>
							<?php if($desc):?>
							<span class="description"><?php echo wp_kses($desc,'');?></span>
							<?php endif;?>
						</label>
					</p>
				<?php
				break;
				case 'textarea':
				?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr($id);?>">
							<?php echo esc_html( $label );?><br>
							<textarea id="<?php echo esc_attr($id);?>" class="widefat" name="<?php echo esc_attr( $name );?>"><?php echo esc_textarea($value);?></textarea>
							<?php if($desc):?>
							<span class="description"><?php echo $desc;?></span>
							<?php endif;?>
						</label>
					</p>
				<?php
				break;
                case 'text':
				?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr($id);?>">
							<?php echo esc_html( $label );?><br>
							<input type="text" id="<?php echo esc_attr($id);?>" class="widefat" name="<?php echo esc_attr( $name );?>" value="<?php echo esc_attr($value);?>" />
							<?php if($desc):?>
							<span class="description"><?php echo $desc;?></span>
							<?php endif;?>
						</label>
					</p>
				<?php
				break;
				case 'checkbox':
				?>
                    <p class="description description-wide <?php echo esc_attr( $class ) ?>">
                        <label for="<?php echo esc_attr($id);?>">

                           <input type="checkbox" id="<?php echo esc_attr($id);?>" name="<?php echo esc_attr( $name );?>" value="true" <?php echo checked($value, 'true' );?> />

                            <?php echo esc_html( $label );?>

                        </label>
</p>

				<?php
				break;
				default:
				?>	
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<label for="<?php echo esc_attr($id);?>">
							<?php echo esc_html( $label );?><br>
							<input id="<?php echo esc_attr($id);?>" class="widefat" name="<?php echo esc_attr( $name );?>" value="<?php echo esc_attr($value);?>" />
							<?php if($desc):?>
							<span class="description"><?php echo $desc;?></span>
							<?php endif;?>
						</label>
					</p>
				<?php
				break;
			endswitch;
		endforeach;
	}


	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}
}

Fox_Menu_Item_Custom_Fields::instance()->init();

endif;