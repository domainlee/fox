<?php
define( 'FOX_REGISTER_URL', get_template_directory_uri() . '/inc/customizer/' );
define( 'FOX_REGISTER_PATH', get_template_directory() . '/inc/customizer/' );

if ( !class_exists( 'Fox_Register' ) ) :
/**
 * Register Options
 * @since 1.0
 */
class Fox_Register {
    
    private static $prefix = 'wi_';
    
    /**
	 * Construct
	 */
	public function __construct() {
	}
    
    /**
	 * The one instance of Fox_Register
	 *
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Fox_Register instance
	 *
	 * @since 1.0
	 *
	 * @return Fox_Register
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
    }
    
    /**
     * List of all options
     *
     * shorthand is a replacement for property, type and preview way. For instance, if you type shorthand: width then
     * preview should be css, type should be text, property should be width & unit often be px
     *
     * @since 1.0
     */
    public function options() {
        
        // Var
        $options = array();
        
        $all = fox_all_font_support();
        
        $layout_options = fox_archive_layout_support();
        
        include 'builder.php';
        
        include 'header.php';
        include 'footer.php';
        
        include 'archives.php';
        include 'blog.php';
        
        include 'post-design.php';
        include 'single.php';
        include 'page.php';
        include 'design.php';
        
        /* SOCIAL - 150
        -------------------------------------------------------------------------------- */
        $options[ 'social' ] = [
            'type'  => 'multiple_text',
            'name'  => 'Social Profile',
            
            'fields' => fox_social_array(),
            
            'section'     => 'social',
            'section_title'=> esc_html__( 'Social Profile', 'wi' ),
            'section_priority'=> 150,
            
            'hint' =>  'Social profile urls',
        ];
        
        /* QUICK TRANSLATION - 155
        -------------------------------------------------------------------------------- */
        $options[ 'translate' ] = [
            'type'  => 'multiple_text',
            'name'  => 'Quick Translation',
            
            'fields' => fox_quick_translation_support(),
            
            'section'     => 'translation',
            'section_title'=> esc_html__( 'Quick Translation', 'wi' ),
            'section_priority'=> 155,
            
            'hint' =>  'Translation',
        ];
        
        include 'mobile.php';
        
        /* AD
        -------------------------------------------------------------------------------- */
        $positions = [
            'single_top' => 'Top of single post',
            'single_before' => 'Before post content',
            'single_after' => 'After post content',
        ];
        
        foreach ( $positions as $pos => $label ) {
            
            $options[] = array(
                'type' => 'heading',
                'name'      => $label,

                'section'   => 'ad',
                'section_title' => 'Advertisement',
                'section_priority'=> 163,
                
                'hint' =>  'Advertisement: ' . $label,
            );
            
            $options[ $pos . '_code' ] = array(
                'type' => 'textarea',
                'name'      => 'Ad Code',
            );

            $options[ $pos . '_banner' ] = array(
                'type'      => 'image',
                'name'      => 'Banner',
            );
            
            $options[ $pos . '_banner_width' ] = array(
                'type'      => 'text',
                'placeholder'=> 'Eg. 728',
                'name'      => 'Banner width (px)',
            );

            $options[ $pos . '_banner_tablet' ] = array(
                'type'      => 'image',
                'name'      => 'Tablet image',
            );

            $options[ $pos . '_banner_phone' ] = array(
                'type'      => 'image',
                'name'      => 'Mobile image',
            );

            $options[ $pos . '_banner_url' ] = array(
                'type'      => 'text',
                'placeholder'=> 'https://',
                'name'      => 'Banner URL',
            );

            $options[ $pos . '_banner_url_target' ] = array(
                'type'      => 'select',
                'options'   => [
                    '_self' => 'Same tab',
                    '_blank' => 'New tab',
                ],
                'std' => '_blank',
                'name'      => 'Open link in',
            );
            
        }
        
        // OTHERS
        //
        $options[] = array(
            'type' => 'html',
            'std'      => '<p class="fox-info">For other positions, please drop (FOX) Ad widget in sidebars in Dashboard > Appearance > Widgets. It can be either before header, after header, 4 footer columns or the main sidebar. In each homepage builder section, it has option to insert ad too.</p>',
        );
        
        /* MISC - 170
        --------------------------------------------------------------------------------------------------------------- */
        // TWITTER USERNAME
        //
        $options[ 'twitter_username' ] = array(
            'type'      => 'text',
            'name'      => esc_html__( 'Twitter Username', 'wi' ),
            'desc'      => 'This option will be used for @via in tweet share button.',
            
            'section'     => 'misc',
            'section_title' => esc_html__( 'Miscellaneous', 'wi' ),
            'section_priority'=> 170,
            
            'hint' =>  'Twitter username',
        );
        
        // TIME FASHION
        //
        $options[ 'time_style' ] = array(
            'type'      => 'select',
            'name'      => 'Time Fashion',
            'options'   => array(
                'standard' => 'March 22, 2019',
                'human' => '5 days ago',
            ),
            'std'       => 'standard',
            
            'hint' =>  'Time style: standard or ago',
        );
        
        // SENTENCE BASE
        //
        $options[ 'sentence_base' ] = array(
            'name'      => 'Sentence Base',
            'type'      => 'select',
            'options'   => [
                'word' => 'Word',
                'char' => 'Character',
            ],
            'std'       => 'word',
            
            'hint' =>  'Sentence base: word or character',
        );
        
        // CODE
        //
        $options[ 'header_code' ] = array(
            'type'      => 'textarea',
            'name'      => 'Custom header code',
            'desc'      => 'Add any code inside <head> tag. Don\'t write anything unless you understand what you\'re doing.',
            
            'hint' =>  'Header code',
        );
        
        // COMMENT SHORTCODE
        //
        $options[ 'comment_shortcode' ] = array(
            'type'      => 'textarea',
            'name'      => 'Comment Shortcode',
            'desc'      => 'If your comment plugin (like Facebook Comments or Disqus Comments) supports a shortcode, please put it here. If you need to disable post normal comments, go to "Single Post > Show/Hide"',
        );
        
        // PERFORMANCE
        //
        $options[] = [
            'name' => 'Site Performance',
            'type' => 'heading',
        ];
        
        $options[ 'compress_files' ] = array(
            'name'      => 'Compress CSS & JS',
            'shorthand' => 'enable',
            'std'       => 'true',
            
            'hint' =>  'Compress CSS & JS',
        );
        
        $options[] = [
            'type' => 'html',
            'std' => fox_format( '<p class="fox-info">Since 4.3, lazyload feature will be deprecated due to its instability. Instead, we recommend you to install and use <a href="{link}" target="_blank">Lazy Load by WP Rocket</a> for a better experience. Fox supports that plugin compatibility.</p>', [ 'link' => 'https://wordpress.org/plugins/rocket-lazy-load/' ] ),
        ];
        
        $options[ 'lazyload' ] = array(
            'name'      => '[Deprecated] Lazy Load Images?',
            'shorthand' => 'enable',
            'std'       => 'false',
            'description'=> 'This option has been deprecated since 4.3'
        );
        
        // @hook `fox_options` so that outer options are welcome
        $options = apply_filters( 'fox_options', $options );
        
        require get_template_directory() . '/inc/customizer/processor.php';
        
        return $final;
        
    }
    
}

endif; // class exists