<?php
/**
 * back compat with Fox v3.x
 * @since 4.0
 */
class Fox_Backcompat {
    
    function __construct() {
        
        add_action( 'init', [ $this, 'check_run_update' ], 0 );
        
        /**
         * back compat on options from body_class and post_class
         * very soon immediately after init
         */
        add_action( 'wp', [ $this, 'alter_single_meta' ], 0 );
        
    }
    
    function check_run_update() {
        
        /**
         * detect any signal from ppl using fox before
         * signal: any theme mod with prefix wi_
         */
        $mods = get_theme_mods();
        $has_wi = false;
        if ( is_array( $mods ) ) {
            foreach ( $mods as $k => $v ) {
                if ( 0 === strpos( $k, 'wi_' ) ) {
                    $has_wi = true;
                    break;
                }
            }
        }
        
        $fox_version = get_option( 'fox_version' );
        
        // signal for Fox3 
        if ( 4 != $fox_version && $has_wi ) {
            $this->run_update();
        }
        
        if ( $fox_version !== false ) {

            // The option already exists, so we just update it.
            if ( 4 != $fox_version ) {

                update_option( 'fox_version', 4 );

            }

        } else {

            // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            add_option( 'fox_version', 4, $deprecated, $autoload );

        }
        
    }
    
    /**
     * change the post meta and term meta accordingly
     * @since 4.0
     */
    function alter_single_meta() {
        
        if ( is_singular( [ 'post', 'page' ] ) ) {
            
            // cool post
            $postid = get_the_ID();
            $style = '';

            /**
             * cool
             */
            if ( $cool = get_post_meta( $postid, '_wi_cool', true ) ) {
                
                if ( true === $cool || 'true' === $cool ) {
                    
                    $style = 3;
                    add_post_meta( $postid, '_wi_content_width', 'narrow', true );
                    add_post_meta( $postid, '_wi_sidebar_state', 'none', true );

                    $cool_thumbnail_size = get_post_meta( $postid, '_wi_cool_thumbnail_size', true );
                    $thumbnail_stretch = '';
                    if ( 'big' == $cool_thumbnail_size ) {
                        $thumbnail_stretch = 'stretch-bigger';
                    } elseif ( 'full' == $cool_thumbnail_size ) {
                        $thumbnail_stretch = 'stretch-full';
                    }
                    if ( $thumbnail_stretch ) {
                        add_post_meta( $postid, '_wi_thumbnail_stretch', $thumbnail_stretch, true );
                    }
                    
                }

            }

            /**
             * hero post
             */
            if ( $hero = get_post_meta( $postid, '_wi_hero', true ) ) {
                if ( 'full' == $hero ) {
                    $style = 4;
                } elseif ( 'half' == $hero ) {
                    $style = 5;
                } elseif ( 'none' == $hero ) {

                    // if style not set yet, it should be 1, otherwise it's 3
                    if ( ! $style ) $style = 1;

                }

            }

            if ( $style ) {
                add_post_meta( $postid, '_wi_style', $style, true );
            }

            /**
             * sidebar layout
             */
            $sidebar_layout = get_post_meta( $postid, '_wi_sidebar_layout', true );
            if ( $sidebar_layout ) {

                add_post_meta( $postid, '_wi_sidebar_state', $sidebar_layout, true );

            }

            /**
             * text column layout
             */
            $column_layout = get_post_meta( $postid, '_wi_column_layout', true );
            if ( $column_layout ) {
                if ( 'single-column' == $column_layout ) {
                    update_post_meta( $postid, '_wi_column_layout', 1, true );
                } elseif ( 'two-column' == $column_layout ) {
                    update_post_meta( $postid, '_wi_column_layout', 2, true );
                }
            }

            /**
             * hide featured thumbnail
             */
            if ( 'true' == get_post_meta( $postid, '_wi_hide_featured_image', true ) ) {
                add_post_meta( $postid, '_wi_thumbnail', 'false', true );
            }

            /**
             * disable share
             */
            if ( 'true' == get_post_meta( $postid, '_wi_disable_share', true ) ) {
                add_post_meta( $postid, '_wi_share', 'false', true );
            }
            
            /**
             * gallery type
             */
            if ( is_single() && 'gallery' == get_post_format() ) {
                
                $effect = get_post_meta( $postid, '_format_gallery_effect', true );
                if ( 'carousel' == $effect ) {
                    add_post_meta( $postid, '_wi_format_gallery_style', 'carousel', true );
                } elseif ( 'fade' == $effect || 'slide' == $effect ) {
                    add_post_meta( $postid, '_wi_format_gallery_style', 'slider', true );
                    add_post_meta( $postid, '_wi_format_gallery_slider_effect', $effect , true );
                }
                
                delete_post_meta( $postid, '_format_gallery_effect' );
                
            }

            // and finally, delete old meta
            delete_post_meta( $postid, '_wi_hero' );
            delete_post_meta( $postid, '_wi_cool' );
            delete_post_meta( $postid, '_wi_cool_thumbnail_size' );
            delete_post_meta( $postid, '_wi_sidebar_layout' );
            delete_post_meta( $postid, '_wi_hide_featured_image' );
            delete_post_meta( $postid, '_wi_disable_share' );
        
        /**
         * singular terms
         * luckily we have only layout and sidebar state :))
         * @since 4.0
         */
        } elseif ( is_category() || is_tag() ) {
            
            $term_id = get_queried_object_id();
            $term_meta = get_option( "taxonomy_{$term_id}" );
            $layout = isset($term_meta['layout']) ? $term_meta['layout'] : '';
            $sidebar_state = isset($term_meta['sidebar_state']) ? $term_meta['sidebar_state'] : '';
            
            if ( $layout ) {
                add_term_meta( $term_id, 'layout', $layout, true );
            }
            if ( $sidebar_state ) {
                add_term_meta( $term_id, 'sidebar_state', $sidebar_state, true );
            }
            
            // remove it
            delete_option( "taxonomy_{$term_id}" );
            
        }
        
    }
    
    /**
     * This function only runs the update
     * It doesn't care all other conditions
     */
    function run_update() {
        
        $mods = get_theme_mods();
        
        /**
         * make a back up for Fox 3 theme mods
         */
        $mods_3 = get_option( 'fox_mods_3' );
        if ( ! $mods_3 ) {
            add_option( 'fox_mods_3', $mods );
        }
        
        /* GENERAL PROBLEMS
        ------------------------------------------------------------------------------------------------ */
        $collect_social_array = [];
        $collect_translate_array = [];
        foreach ( $mods as $id => $value ) {
            
            /**
             * 01 - disable type
             */
            if ( 0 === strpos( $id, 'wi_disable_' ) ) {
                
                $new_id = str_replace( 'wi_disable_', '', $id );
                if ( $value ) {
                    set_theme_mod( $new_id, 'false' );
                } else {
                    set_theme_mod( $new_id, 'true' );
                }
                
            }
            
            /**
             * 02 - multiple text options
             */
            if ( 0 === strpos( $id, 'wi_social_' ) ) {
                $new_id = str_replace( 'wi_social_', '', $id );
                if ( $value ) {
                    $collect_social_array[ $new_id ] = $value;
                }
            }
            
            if ( 0 === strpos( $id, 'wi_translate_' ) ) {
                $new_id = str_replace( 'wi_translate_', '', $id );
                if ( $value ) {
                    $collect_translate_array[ $new_id ] = $value;
                }
            }
            
        }
        
        /* Enable Options
        ------------------------------------------------------------------------------------------------ */
        $enable_options = [
            'sticky_sidebar',
            'enable_hand_lines',
            'autoload_post',
            'exclude_pages_from_search',
        ];
        
        foreach ( $enable_options as $id ) {
            
            $value = get_theme_mod( 'wi_' . $id );
            $new_id = 'wi_' . $id;
            
            if ( isset( $mods[ 'wi_' . $id ] ) && $mods[ 'wi_' . $id ] ) {
                set_theme_mod( $new_id, 'true' );
            } else {
                set_theme_mod( $new_id, 'false' );
            }
            
        }
        
        /* LAYOUT
        ------------------------------------------------------------------------------------------------ */
        $positions = [
            'home', 'category', 'archive', 'tag', 'author', 'search', 'all-featured',
        ];
        foreach ( $positions as $position ) {
            
            $id = 'wi_' . $position . '_layout';
            
            // not set yet, it means standard in old system
            if ( ! isset( $mods[ $id ] ) ) {
                set_theme_mod( $id, 'standard' );
            }
            
        }
        
        /* LOGO
        ------------------------------------------------------------------------------------------------ */
        set_theme_mod( 'wi_logo_type', 'image' );
        // in old theme, header slogan is enabled by default
        // not set yet
        if ( ! isset( $mods[ 'wi_disable_header_slogan' ]  ) ) {
            set_theme_mod( 'wi_header_slogan', 'true' );
        }
        
        /* FOOTER
        ------------------------------------------------------------------------------------------------ */
        // scroll up type
        set_theme_mod( 'wi_backtotop_type', 'text' );
        
        /* DESIGN
        ------------------------------------------------------------------------------------------------ */
        $old_design = [
            'logo_width' => 1170,
        ];
        
        foreach ( $old_design as $k => $v ) {
            if ( ! isset( $mods[ 'wi_' . $k ] ) ) {
                set_theme_mod( 'wi_' . $k, $v );
            }
        }
        
        // nav size
        if ( ! isset( $mods[ 'wi_nav_size' ] ) ) {
            $nav_size = 26;
        } else {
            $nav_size = $mods[ 'wi_nav_size' ];
        }
        set_theme_mod( 'wi_nav_typography', json_encode([
            'font-size' => $nav_size,
        ]));
        
        // body font
        if ( ! isset( $mods[ 'wi_body_font' ] ) ) {
            $body_font = 'Merriweather:400';
        } else {
            $body_font = $mods[ 'wi_body_font' ] . ':300,400,700';
        }
        set_theme_mod( 'wi_body_font', $body_font );
        
        // heading font
        if ( ! isset( $mods[ 'wi_heading_font' ] ) ) {
            $heading_font = 'Oswald:300,400,700';
        } else {
            $heading_font = $mods[ 'wi_heading_font' ] . ':300,400,700';
        }
        set_theme_mod( 'wi_heading_font', $heading_font );
        
        // nav font
        if ( ! isset( $mods[ 'wi_nav_font' ] ) ) {
            $nav_font = 'Oswald:300,400,700';
        } else {
            $nav_font = $mods[ 'wi_nav_font' ] . ':300,400,700';
        }
        set_theme_mod( 'wi_nav_font', $nav_font );
        
        // general color
        set_theme_mod( 'wi_border_color', '#000' );
        set_theme_mod( 'wi_nav_submenu_box', json_encode([
            'border-color' => '#000000',
            'border-width' => 1,
            'border-style' => 'solid',
        ]));
        set_theme_mod( 'wi_nav_submenu_sep_color', '#000' );
        set_theme_mod( 'wi_nav_submenu_typography', json_encode([
            'font-size' => 11,
            'letter-spacing' => 2,
            'font-weight' => 400,
            'text-transform' => 'uppercase',
        ]));
        set_theme_mod( 'wi_blog_standard_header_align', 'center' );
        set_theme_mod( 'wi_offcanvas_nav_typography', json_encode([
            'text-transform' => 'uppercase',
            'font-size'     => 16,
            'letter-spacing' => 1,
        ]) );
        
        set_theme_mod( 'wi_button_typography', json_encode([
            'text-transform' => 'uppercase',
            'font-size'     => 13,
            'letter-spacing' => 1,
        ]) );
        
        set_theme_mod( 'wi_titlebar_box', json_encode([
            'border-top-color' => '#000',
            'border-bottom-color' => '#000',
        ]) );
        set_theme_mod( 'wi_footer_sidebar_sep_color', '#000' );
        set_theme_mod( 'wi_backtotop_border_color', '#000' );
        set_theme_mod( 'wi_sticky_header_element_style', 'border' );
        set_theme_mod( 'wi_blockquote_box', json_encode([
            'border-top-width' => '2px',
            'border-bottom-width' => '2px',
        ]) );
        
        // accent color
        if ( isset( $mods[ 'wi_primary_color' ] )  ) set_theme_mod( 'wi_accent', $mods[ 'wi_primary_color' ] );
        
        // widget title background
        if ( isset( $mods[ 'wi_widget_title_text_color' ] )  ) {
            set_theme_mod( 'wi_widget_title_color', $mods[ 'wi_widget_title_text_color' ] );
        } else {
            set_theme_mod( 'wi_widget_title_color', '#ffffff' );
        }
        if ( isset( $mods[ 'wi_widget_title_bg_color' ] )  ) {
            set_theme_mod( 'wi_widget_title_background_color', $mods[ 'wi_widget_title_bg_color' ] );
        } else {
            set_theme_mod( 'wi_widget_title_background_color', '#000000' );
        }
        
        // selection
        if ( isset( $mods[ 'wi_selection_color' ] )  ) {
            set_theme_mod( 'wi_selection_background', $mods[ 'wi_selection_color' ] );
            if ( ! isset( $mods[ 'wi_selection_text_color' ] ) ) {
                set_theme_mod( 'wi_selection_text_color', '#ffffff' );
            }
        }
        
        /**
         * background to background customizer new type
         */
        $prefix = 'wi_body_';
        $props = [
            'background_color',
            'background',
            'background_position',
            'background_size',
            'background_repeat',
            'background_attachment',
        ];
        $bg_collect = [];
        foreach ( $props as $prop ) {
            if  ( ! isset( $mods[ $prefix . $prop ] ) ) continue;
            $value = $mods[ $prefix . $prop ];
            if ( 'background' == $prop ) {
                $prop = 'background-image';
                $value = attachment_url_to_postid( $value );
            }
            $bg_collect[ $prop ] = $value;
        }
        if ( ! empty( $bg_collect ) ) {
            set_theme_mod( 'wi_body_background', json_encode( $bg_collect ) );
        } else {
            // prevent weird value from old body_background property
            set_theme_mod( 'wi_body_background', '' );
        }
        
        // old content width, if not set it's 1020
        if ( ! isset( $mods[ 'wi_content_width' ] ) ) {
            set_theme_mod( 'wi_content_width', 1020 );
        }
        
        // now re-build old fashion on elements
        $border = false;
        if ( isset( $mods[ 'wi_site_border' ] ) ) {
            if ( 'true' == $mods[ 'wi_site_border' ] ) {
                $border = true;
            } elseif ( 'false' == $mods[ 'wi_site_border' ] ) {
                $border = false;
            }
        }
        
        if ( $border ) {
            
            set_theme_mod( 'wi_body_layout', 'boxed' );
            
            $wrapper_box = [
                'border-top-width' => 2,
                'border-bottom-width' => 2,
                'border-left-width' => 2,
                'border-right-width' => 2,
                'border-style' => 'solid',
            ];
            $all_box = [
                'margin-top' => '24',
                'margin-bottom' => '24',
            ];
            
            set_theme_mod( 'wi_wrapper_box', json_encode( $wrapper_box ) );
            set_theme_mod( 'wi_all_box', json_encode( $all_box ) );
            
        } else {
        
            set_theme_mod( 'wi_body_layout', 'wide' );
        
        }
        
        /* BLOG
        ------------------------------------------------------------------------------------------------ */
        if ( isset( $mods[ 'wi_disable_blog_image' ] ) && $mods[ 'wi_disable_blog_image' ] ) {
            // disable blog image, it means, hmm, no standard image
            set_theme_mod( 'wi_blog_standard_thumbnail', 'false' );
        } else {
            set_theme_mod( 'wi_blog_standard_thumbnail', 'true' );
        }
        
        $components = [
            'image' => 'show_thumbnail',
            'date' => 'show_date',
            'categories' => 'show_category', 
            'author' => 'show_author',
            'view_count' => 'show_view',
            'comment' => 'show_comment_link',
            'share' => 'show_share',
            'related' => 'show_related',
            'standard_display' => 'content_excerpt',
        ];
        
        foreach ( $components as $com => $new_com ) {
            
            if ( isset( $mods[ 'wi_disable_blog_' . $com ] ) && $mods[ 'wi_disable_blog_' . $com ] ) {
                set_theme_mod( 'wi_blog_standard_' . $new_com, 'false' );
            } else {
                set_theme_mod( 'wi_blog_standard_' . $new_com, 'true' );
            }
            
        }
        
        if ( isset( $mods[ 'wi_grid_excerpt_length' ] ) ) set_theme_mod( 'wi_blog_grid_excerpt_length', $mods[ 'wi_grid_excerpt_length' ] );
        
        if ( isset( $mods[ 'wi_grid_excerpt_length' ] ) ) set_theme_mod( 'wi_blog_grid_excerpt_length', $mods[ 'wi_grid_excerpt_length' ] );
        
        if ( isset( $mods[ 'wi_disable_blog_readmore' ] ) && $mods[ 'wi_disable_blog_readmore' ] ) {
            
            set_theme_mod( 'wi_blog_grid_excerpt_more', 'false' );
            
        } else {
            
            set_theme_mod( 'wi_blog_grid_excerpt_more', 'true' );
            
        }
        
        /* COOL POST - HERO POST
        ------------------------------------------------------------------------------------------------ */
        /**
         * cool post problem
         * cool post means: single content width narrow and no sidebar
         */
        if ( get_theme_mod( 'wi_cool_post_all' ) ) {
        
            set_theme_mod( 'wi_single_style', 3 );
            set_theme_mod( 'wi_single_content_width', 'narrow' );
            set_theme_mod( 'wi_single_sidebar_state', 'none' );
            
            // thumbnail stretch now has 2 values
            $thumbnail_stretch = get_theme_mod( 'wi_cool_thumbnail_size', 'big' );
            if ( 'full' == $thumbnail_stretch ) {
                set_theme_mod( 'wi_single_thumbnail_stretch', 'stretch-full' );
            } else {
                set_theme_mod( 'wi_single_thumbnail_stretch', 'stretch-none' );
            }
        
        }
        
        $cool_post_stretch = get_theme_mod( 'wi_cool_post_stretch', 'bit' );
        if ( 'full' == $cool_post_stretch ) {
            set_theme_mod( 'wi_single_content_image_stretch', 'stretch-full' );
        } else {
            set_theme_mod( 'wi_single_content_image_stretch', 'stretch-bigger' );
        }
        
        $hero = get_theme_mod( 'wi_hero' );
        if ( 'full' == $hero ) {
            
            set_theme_mod( 'wi_single_style', 4 );
            
        } elseif ( 'half' == $hero ) {
            
            set_theme_mod( 'wi_single_style', 5 );
        
        } else {
            
            if ( get_theme_mod( 'wi_cool_post_all' ) ) {
                set_theme_mod( 'wi_single_style', 3 );
            } else {
                set_theme_mod( 'wi_single_style', 1 );
            }
        
        }
        
        $components = [
            'image' => 'thumbnail',
            'share' => 'share',
            'tag' => 'tag', 
            'related' => 'related',
            'author' => 'authorbox',
            'comment' => 'comment',
            'nav' => 'nav',
            'same_category' => 'bottom_posts',
            'side_dock' => 'side_dock',
        ];
        
        foreach ( $components as $com => $new_com ) {
            
            if ( isset( $mods[ 'wi_disable_single_' . $com ] ) && $mods[ 'wi_disable_single_' . $com ] ) {
                set_theme_mod( 'wi_single_' . $new_com, 'false' );
            } else {
                set_theme_mod( 'wi_single_' . $new_com, 'true' );
            }
            
        }
        
        /* SOCIAL, TRANSLATE - MULTIPLE TEXT OPTIONS
        ------------------------------------------------------------------------------------------------ */
        /**
         * Social collection
         */
        if ( ! empty( $collect_social_array ) ) {
            set_theme_mod( 'wi_social', json_encode( $collect_social_array ) );
        }
        
        if ( ! empty( $collect_translate_array ) ) {
            set_theme_mod( 'wi_translate', json_encode( $collect_translate_array ) );
        }
        
        /* MISC
        ------------------------------------------------------------------------------------------------ */
        if ( isset( $mods[ 'wi_blog_content_column' ] ) ) {
            set_theme_mod( 'wi_blog_column_layout', $mods[ 'wi_blog_content_column' ] );
        } else {
            set_theme_mod( 'wi_blog_column_layout', 1 );
        }
        
        if ( isset( $mods[ 'wi_disable_blog_dropcap' ] ) && $mods[ 'wi_disable_blog_dropcap' ] ) {
            set_theme_mod( 'wi_blog_dropcap', 'false' );
        } else {
            set_theme_mod( 'wi_blog_dropcap', 'true' );
        }
        
        /* FINAL KEEP THE OLD DESIGN AT SOME POINTS
        ------------------------------------------------------------------------------------------------ */
        $old_design = [
            
            'authorbox_style' => 'box',
            'video_indicator_style' => 'solid',
            'tag_style' => 'block',
            
            // 01 - widget title
            'widget_title_align' => 'center',
            'widget_title_font' => 'font_heading',
            'widget_title_typography' => json_encode([
                'text-transform' => 'uppercase',
                'letter-spacing' => 8,
                'font-weight' => 'normal',
                'font-size' => 12,
            ]),
            'widget_title_box' => json_encode([
                'margin-bottom' => 16,
                'padding-top' => 4,
                'padding-bottom' => 4,
                'padding-left' => 0,
                'padding-right' => 0,
            ]),
            
            
            // 02 - post meta
            'post_meta_font' => 'font_heading',
            'post_meta_typography' => json_encode([
                'text-transform' => 'uppercase',
                'font-size' => '11',
                'font-weight' => 'normal',
                'letter-spacing' => '1.5px',
            ]),
            
            // 03 - slogan
            'tagline_font' => 'font_heading',
            'tagline_typography' => json_encode([
                'letter-spacing' => '8',
                'font-size' => '0.8125em',
            ]),
            
            // 04 - single heading
            'single_heading_typography' => json_encode([
                'text-transform' => 'uppercase',
                'font-weight' => 300,
                'letter-spacing' => 6,
            ]),
        ];
        
        foreach ( $old_design as $k => $v ) {
            
            set_theme_mod( 'wi_' . $k, $v );
            
        }
        
    }

}
new Fox_Backcompat();