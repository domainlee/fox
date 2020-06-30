<?php
namespace Fox_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.0.0
 */
abstract class Fox_Widget_Base extends Widget_Base {
    
    // in extended class, we must return the folder name
    function _base() {
    }
    
    /**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'fox' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
        
		return [ 'fox-framework' ];
        
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	function _register_controls() {
        
        $params = array();
        
        include FOX_FRAMEWORK_PATH . 'addons/' . $this->_base() . '/params.php';
        
        $start = true;
        
        foreach ( $params as $id => $param ) {
            
            // add 
            if ( isset( $param[ 'section' ] ) && isset( $param[ 'section_title' ] ) ) {
                
                if ( ! $start ) {
                    // if not start, end of previous setting before
                    $this->end_controls_section();
                }
                
                $section_setting = array( 'label' => $param[ 'section_title' ] );
                if ( isset( $param[ 'tab' ] ) ) {
                    
                    $tab = '';
                    switch( $param[ 'tab' ] ) {
                        case 'content':
                        $tab = Controls_Manager::TAB_CONTENT;
                        break;
                    }
                    
                    $section_setting[ 'tab' ] = $tab;
                }
                
                $this->start_controls_section(
                    'section_' . $param[ 'section' ],
                    $section_setting
                );
                
                // not start anymore
                $start = false;
                
            }
            
            $type = '';
            $type = isset( $param[ 'type' ] ) ? $param[ 'type' ] : '';
            switch( $param[ 'type' ] ) {
                
                case 'text' :
                $type = Controls_Manager::TEXT;
                break;
                case 'textarea' :
                $type = Controls_Manager::TEXTAREA;
                break;
                case 'media' :
                $type = Controls_Manager::MEDIA;
                break;
                case 'code' :
                $type = Controls_Manager::CODE;
                break;
                case 'select' :
                $type = Controls_Manager::SELECT;
                break;
                case 'select2' :
                $type = Controls_Manager::SELECT2;
                break;
                case 'color' :
                $type = Controls_Manager::COLOR;
                break;
                case 'gallery' :
                $type = Controls_Manager::GALLERY;
                break;
                case 'font' :
                $type = Controls_Manager::FONT;
                break;
                case 'switcher' :
                $type = Controls_Manager::SWITCHER;
                break;
                case 'url' :
                $type = Controls_Manager::URL;
                break;
                case 'heading' :
                $type = Controls_Manager::HEADING;
                break;
                default:
                break;
            }
            
            $control_setting = $param;
            
            // remove section/tab
            if ( isset( $control_setting[ 'section' ] ) ) unset( $control_setting[ 'section' ] );
            if ( isset( $control_setting[ 'tab' ] ) ) unset( $control_setting[ 'tab' ] );
            
            // type
            $control_setting[ 'type' ] = $type;
            
            // label
            $control_setting[ 'label' ] = isset( $param[ 'title' ] ) ? $param[ 'title' ] : '';
            
            if ( isset( $param[ 'std' ] ) ) {
                $control_setting[ 'default' ] = $param[ 'std' ];
            }
            
            if ( isset( $param[ 'desc' ] ) ) {
                $control_setting[ 'description' ] = $param[ 'desc' ];
            }
            
            if ( 'typography' == $type ) {
                
                $this->add_group_control(
                    
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'content_typography',
                        'label' => __( 'Typography', 'plugin-domain' ),
                        'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                        'selector' => '{{WRAPPER}} .text',
                        'default' => [
                           'font_weight' => '500',
                           'font_family'  => 'Rubik',
                            'font_size'      => '14px',
                        ]
                    ]
                );
                
            } else {
            
                $this->add_control(
                    $id,
                    $control_setting
                );
            
            }
        
        }
        
        // at least 1 section ultilized
        if ( ! $start ) {
            $this->end_controls_section();
        }
        
	}
    
    /**
     * @since Fox 4.2
     * to prevent some content from being rendered when we have pager
     */
    function _print_content() {
        
        $render = true;
        $settings = $this->get_settings_for_display();
        if ( isset( $settings[ 'paged_disable' ] ) ) {
            
            if ( 'true' == $settings[ 'paged_disable' ] || 'yes' == $settings[ 'paged_disable' ] ) {
                
                if ( is_front_page() && ! is_home() ) {
                    $paged = get_query_var( 'page' );
                } else {
                    $paged = get_query_var( 'paged' );
                }
                
                if ( $paged ) {

                    $render = false;

                }
                
            }
            
        }
        
        if ( $render ) {
            $this->render_content();
        }
        
    }

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	function render() {
        
        include FOX_FRAMEWORK_PATH . 'addons/' . $this->_base() . '/frontend.php';
        
	}
    
}