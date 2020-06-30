<?php
        
/* Footer Sidebar
-------------------------------------- */
$options[ 'footer_sidebar' ] = array(
    'shorthand' => 'enable',
    'name'      => esc_html__( 'Footer Sidebar', 'wi' ),
    'std'       => 'true',

    'section'   => 'footer-sidebar',
    'section_title' => 'Footer Sidebar',

    'panel' => 'footer',
    'panel_priority'     => 115,
    'panel_title' => esc_html__( 'Footer', 'wi' ),
    
    'hint' => 'Footer sidebar, widgets',
);

$options[ 'footer_sidebar_layout' ] = array(
    'type'      => 'image_radio',
    'name'      => 'Footer Sidebar Layout',
    'std'       => '1-1-1-1',
    'options'   => footer_sidebar_layout_support(),
    
    'hint' => 'Footer sidebar layout',
);

for ( $i = 1; $i<=4; $i++ ) {
    
    $options[ 'footer_' . $i . '_align' ] = array(
        'type'      => 'select',
        'name'      => 'Footer ' . $i . ' align',
        'std'       => '',
        'options'   => [
            '' => 'Default',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ],
    );
    
}

$options[ 'footer_sidebar_sep' ] = array(
    'shorthand' => 'enable',
    'name'      => 'Footer Col Separator',
    'std'       => 'true',
);

$options[ 'footer_sidebar_sep_color' ] = array(
    'shorthand' => 'border-color',
    'name'      => 'Separator Color',
    'selector'  => '.footer-col-sep',
);

$options[ 'footer_sidebar_stretch' ] = array(
    'type'      => 'select',
    'options'   => [
        'content'   => 'Content Width',
        'full'      => 'Stretch to Full-width',
    ],
    'name'      => 'Footer Sidebar Stretch',
    'std'       => 'content',
    
    'hint' => 'Footer sidebar stretch',
);

// since 4.3
$options[ 'footer_sidebar_valign' ] = array(
    'type' => 'radio',
    'options'   => [
        'stretch'   => 'Stretch',
        'top'      => 'Top',
        'middle'      => 'Middle',
        'bottom'      => 'Bottom',
    ],
    'std'       => 'stretch',
    'name'      => 'Footer Sidebar Vertical Align',
    
    'hint' => 'Footer sidebar vertical align',
);

$options[ 'footer_sidebar_skin' ] = array(
    'type' => 'select',
    'options'   => [
        'light' => 'Light',
        'dark' => 'Dark',
    ],
    'std'       => 'light',
    'name'      => 'Footer Sidebar Skin',
    
    'hint' => 'Footer sidebar skin',
);

$options[ 'footer_sidebar_color' ] = array(
    'shorthand' => 'color',
    'name'      => 'Footer Sidebar Text Color',
    'selector'  => '#footer-widgets',
    
    'hint' => 'Footer sidebar text color',
);

$options[ 'footer_sidebar_background' ] = array(
    'shorthand' => 'background',
    'name'      => 'Footer Sidebar Background',
    'selector'  => '#footer-widgets',
    
    'hint' => 'Footer sidebar background',
);

$options[ 'footer_sidebar_box' ] = array(
    'shorthand' => 'box',
    'name'      => 'Footer Sidebar Box',
);

/* Footer Bottom
-------------------------------------- */
$options[ 'footer_bottom' ] = array(
    'shorthand'      => 'enable',
    'std' => 'true',
    'name'      => 'Footer Bottom',

    'section'   => 'footer',
    'section_title' => esc_html__( 'Footer Bottom', 'wi' ),
    'panel' => 'footer',
    
    'hint' => 'Footer bottom',
);

$options[ 'footer_bottom_skin' ] = array(
    'type' => 'select',
    'options'   => [
        'light' => 'Light',
        'dark' => 'Dark',
    ],
    'std'       => 'light',
    'name'      => 'Footer Bottom Skin',
    
    'hint' => 'Footer bottom skin',
);

$options[ 'footer_text_color' ] = array(
    'shorthand' => 'color',
    'name'      => 'Footer Text Color',
    'selector'  => '#footer-bottom',
    
    'hint' => 'Footer bottom text color',
);

$options[ 'footer_bottom_background' ] = array(
    'shorthand' => 'background',
    'name'      => 'Footer Bottom Background',
    
    'hint' => 'Footer bottom background',
);

$options[ 'footer_bottom_stretch' ] = array(
    'type'      => 'select',
    'options'   => [
        'content' => 'Content Width',
        'full' => 'Stretch to fullwidth',
    ],
    'std'       => 'content',
    'name'      => 'Footer Bottom Stretch',
);

// FOOTER LOGO
//
$options[] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Footer Logo', 'wi' ),
    
    'hint' => 'Footer logo',
);

$options[ 'footer_logo_show' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Show footer logo?',
);

$options[ 'footer_logo' ] = array(
    'type'      => 'image',
    'name'      => esc_html__( 'Footer Logo', 'wi' ),
);

/**
 * deprecated since 4.0
$options[ 'footer_logo_retina' ] = array(
    'type'      => 'image',
    'name'      => esc_html__( 'Upload retina version of the footer logo', 'wi' ),
);
*/

$options[ 'footer_logo_width' ] = array(
    'shorthand' => 'width',
    'placeholder' => 'Eg. 120px',
    'selector'  => '#footer-logo img',
    'name'      => esc_html__( 'Footer logo width (px)', 'wi' ),
);

$options[ 'footer_logo_custom_link' ] = array(
    'type'      => 'text',
    'placeholder' => 'https://',
    'name'      => 'Footer Logo Custom Link',
    'desc'      => 'By default your footer logo will link to your homepage.',
);

$options[ 'footer_social_heading' ] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Footer Social', 'wi' ),
);

$options[ 'footer_social' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Footer social icons',
    
    'hint' => 'Footer social',
);

$options[ 'footer_social_skin' ] = array(
    'type'      => 'select',
    'options'   => fox_social_style_support(),
    'std'       => 'black',
    'name'      => 'Social Icons Style',
);

$options[ 'footer_search_heading' ] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Footer Search', 'wi' ),
);

$options[ 'footer_search' ] = array(
    'name'      => 'Footer search box',
    'shorthand' => 'enable',
    'std'       => 'true',
    
    'hint' => 'Footer search',
);

$options[] = array(
    'type'      => 'heading',
    'name'      => esc_html__( 'Footer Copyright', 'wi' ),
);

$options[ 'footer_copyright' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Copyright',
    
    'hint' => 'Copyright text',
);    

$options[ 'copyright' ] = array(
    'type'      => 'textarea',
    'name'      => 'Copyright Text',
    'desc'      => 'You can use insert HTML as well.',
);

/**
 * Footer Builder
 * @since 4.0
 */
$options[] = [
    'type' => 'heading',
    'name' => 'Footer Bottom Builder',
];

$options[] = array(
    'type'      => 'html',
    'std'       => '<div class="fox-info">Since Fox v4.0, you can build your own footer bottom by drag & drop widgets into Footer Bottom Widgets.</div>',
);

$options[ 'footer_bottom_builder' ] = array(
    'type'      => 'select',
    'options'   => [
        'true' => 'Yes Please',
        'false' => 'No Thanks!',
    ],
    'std' => 'false',
    'toggle' => [
        'true' => [ 'footer_bottom_layout' ],
    ],
    'name'      => 'Footer Bottom Builder',
);

$options[ 'footer_bottom_layout' ] = array(
    'type'      => 'select',
    'options'   => [
        'stack' => 'Stack',
        'inline' => 'Left - Right',
    ],
    'std'       => 'stack',
    'name'      => 'Footer Bottom Layout',
    'desc'      => 'If you choose "Stack", please add widgets to "Footer Bottom Stack" sidebar. If you choose "Left - Right", please add widgets to "Footer Bottom Left" and "Footer Bottom Right"',
);

$options[ 'footer_bottom_box' ] = array(
    'shorthand' => 'box',
    'name'      => 'Footer Bottom Box',
);

/* Back To Top Button
-------------------------------------- */
$options[ 'backtotop' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => '"Back to top" button',

    'section'   => 'backtotop',
    'section_title' => 'Go To Top Button',
    'panel'     => 'footer',
    
    'hint' => 'Go to top button',
);

$options[ 'backtotop_type' ] = array(
    'type' => 'select',
    'options' => [
        'text' => 'Text Button',
        'icon-arrow-up' => 'Arrow Up',
        'icon-chevron-up' => 'Chevron Up',
        'icon-chevrons-up' => 'Double Chevron Up'
    ],
    'std' => 'icon-chevron-up',
    'name'      => 'Button Type',

    'toggle' => [
        'icon-arrow-up' => [ 'backtotop_shape' ],
        'icon-chevron-up' => [ 'backtotop_shape' ],
        'icon-chevrons-up' => [ 'backtotop_shape' ],
    ],

);

$options[ 'backtotop_shape' ] = array(
    'type' => 'select',
    'options' => [
        'square' => 'Square',
        'circle' => 'Circle',
    ],
    'std'       => 'circle',
    'name'      => 'Button Shape',
    'desc'      => 'Note: circle button only uses icon, it doesn\'t have text.',
);

$options[ 'backtotop_border_width' ] = array(
    'type' => 'select',
    'options' => [
        '' => 'Default',
        '0px' => 'No border',
        '1px' => '1px',
        '2px' => '2px',
        '3px' => '3px',
        '4px' => '4px',
    ],
    'std' => '',
    'property' => 'border-width',
    'selector' => '#backtotop.backtotop-circle, #backtotop.backtotop-square',
    'name'      => 'Border width',
);

$options[ 'backtotop_border_radius' ] = array(
    'shorthand' => 'border-radius',
    'selector' => '#backtotop.backtotop-circle, #backtotop.backtotop-square',
    'name'      => 'Border radius',
);

$options[ 'backtotop_color' ] = array(
    'shorthand' => 'color',
    'selector'  => '#backtotop',
    'name'      => 'Button Color',
);

$options[ 'backtotop_background_color' ] = array(
    'shorthand' => 'background-color',
    'selector'  => '#backtotop',
    'name'      => 'Button Background Color',
);

$options[ 'backtotop_border_color' ] = array(
    'shorthand' => 'border-color',
    'selector'  => '#backtotop',
    'name'      => 'Button Border Color',
);

$options[ 'backtotop_hover_color' ] = array(
    'shorthand' => 'color',
    'selector'  => '#backtotop:hover',
    'name'      => 'Button Hover Color',
);

$options[ 'backtotop_hover_background_color' ] = array(
    'shorthand' => 'background-color',
    'selector'  => '#backtotop:hover',
    'name'      => 'Button Hover Background Color',
);

$options[ 'backtotop_hover_border_color' ] = array(
    'shorthand' => 'border-color',
    'selector'  => '#backtotop:hover',
    'name'      => 'Button Hover Border Color',
);