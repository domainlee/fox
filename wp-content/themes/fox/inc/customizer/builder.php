<?php
/* HOMEPAGE BUILDER - 105
---------------------------------------------------------------------------------------------------------------------- */
$options[ 'builder_paged' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Builder Sections for pages 2, 3..?',

    'section'   => 'homepage_builder',
    'section_title' => 'General Settings',
    'section_priority' => 1,

    'panel'     => 'homepage',
    'panel_title' =>'Homepage Builder',
    'panel_desc'=> 'You can use mouse drag & drop to change section order.',
    'panel_priority'=> 105,
    
    'hint' => 'Homepage builder paged',
);

// a secret field
$options[ 'sections_order' ] = array(
    'type'      => 'hidden',
);

$options[ 'unique_reading' ] = array(
    'shorthand' => 'enable',
    'std'       => 'false',
    'name'      => 'Non-duplicating posts',
    'desc'      => 'If you enable, posts will not appear twice in different sections. Please note: This is non-duplicated only for builder sections, not main stream.',
    
    'hint' => 'Homepage builder non-duplicating posts',
);

$options[ 'home_padding_top' ] = array(
    'shorthand' => 'padding-top',
    'name'      => 'Homepage padding top',
    'selector'  => '.home.blog .wi-content',
    'placeholder' => 'Eg. 10px',
    
    'hint' => 'Homepage padding',
);

$options[ 'home_padding_bottom' ] = array(
    'shorthand' => 'padding-bottom',
    'name'      => 'Homepage padding bottom',
    'selector'  => '.home.blog .wi-content',
    'placeholder' => 'Eg. 10px',
);

$options[ 'max_sections' ] = array(
    'type'      => 'text',
    'std'       => 6,
    'name'      => 'Max number of sections allowed',
    'desc'      => 'You must RELOAD this customizer page after saving to see more sections.<br>
    NOTE: DO NOT enter the number more than you need.',
    
    'hint' => 'Homepage builder number of sections',
);

$options[ 'section_spacing' ] = array(
    'type'      => 'radio',
    'options'   => [
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
        'large' => 'Large',
    ],
    'std'       => 'small',
    'name'      => 'Spacing between sections',
    
    'hint' => 'Homepage builder section spacing',
);

/* BUILDER HEADING
------------------------------------ */
$options[] = [
    'type' => 'heading',
    'name' => 'Builder Heading',
];

$options[ 'builder_heading_style' ] = [
    'type'      => 'radio',
    'name'      => 'Builder Heading Style',
    'options'   => [
        'plain' => 'Plain',

        '1a' => 'Border Top',
        '1b' => 'Border Bottom',

        '2a' => '2 thin lines middle',
        '2b' => '2 thin lines bottom',

        '3a' => '2 thick lines middle',
        '3b' => '2 thick lines bottom',

        '4a' => '2 wavy lines middle',
        '4b' => '2 wavy lines bottom',

        '5' => 'Border around',

        '6' => 'Wave bottom',
        
        '7a' => 'Diagonal Stripes',
        '8a' => 'Pixelate dot band'
    ],
    'std' => '1a',
    
    'hint' => 'Homepage builder heading options',
];

$options[ 'builder_heading_line_color' ] = [
    'shorthand' => 'border-color',
    'name'      => 'Heading line color',
    'selector'  => '.heading-1a .container, .heading-1b .container, .section-heading .line, .heading-5 .heading-inner',
];

$options[ 'builder_heading_size' ] = [
    'type'      => 'radio',
    'name'      => 'Builder Heading Size',
    'options'   => [

        'ultra' => 'Ultra Large',
        'extra' => 'Extra Large',
        'large' => 'Large',

        'medium' => 'Medium',
        'normal' => 'Normal',
        'small' => 'Small',
        'tiny' => 'Tiny',

    ],
    'std' => 'large',
];

$options[ 'builder_heading_line_stretch' ] = [
    'type'      => 'radio',
    'name'      => 'Builder Heading Line Stretch',
    'options'   => [

        'content' => 'Content width',
        'content-half' => 'Half content width',
        'full' => 'Stretch full screen width',

    ],
    'std' => 'content',
];

$options[ 'builder_heading_align' ] = [
    'type'      => 'radio',
    'name'      => 'Builder Heading Align',
    'options'   => [

        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',

    ],
    'std' => 'center',
];

$id = 'elementor_heading';
$fontdata = $all[ $id ];

$options[ $id . '_font' ] = [
    'shorthand' => 'select-font',
    'name'      => $fontdata[ 'name' ] . ' Font',
    'inherit_options' => true,
    'std'       => $fontdata[ 'std' ],
    
    'hint' => 'Homepage builder heading font',
];

$options[ $id . '_typography' ] = [
    'shorthand' => 'typography',
    'selector'  => $fontdata[ 'selector' ],
    'name'      => $fontdata[ 'name' ],
    'fields'    => $fontdata[ 'fields' ],
    'std'       => $fontdata[ 'typo' ],
];

/**
 * Builder Sections
 */
$postTypes = get_post_types( array() );
$postTypesList = array();
$excludedPostTypes = array(

    'post',
    'revision',
    'nav_menu_item',
    'vc_list_item',
    'page',
    'attachment',
    'custom_css',
    'customize_changeset',
    'vc4_templates',
    'wpcf7_contact_form',
    'tablepress_table',
    'mc4wp-form',
    'product_variation',
    'shop_order',
    'shop_order_refund',
    'shop_coupon',
    'shop_webhook',

    'oembed_cache',
    'user_request',
    'wp_block',
    'scheduled-action',
    'jp_pay_order',
    'jp_pay_product',

    'elementor_library',
);
if ( is_array( $postTypes ) && ! empty( $postTypes ) ) {

    $postTypes = array_diff( $postTypes, $excludedPostTypes );
    foreach ( $postTypes as $postType ) {
        $label = ucfirst( $postType );
        $postTypesList[ 'post_type_' . $postType ] = 'Post Type: ' . $label;
    }
}

// cat array
$source_arr = array(
    'none'          =>  '...',
    'all'       =>  'All categories',
    'featured'  =>  'Featured Posts (marked by "star")',
    'sticky'    =>  'Sticky posts',
    'video'     =>  'Video Posts',
    'gallery'   =>  'Gallery Posts',
    'audio'     =>  'Audio Posts',
);
$cat_arr = [];

$cats = get_categories();
foreach ( $cats as $cat ) {

    $source_arr[ 'cat_' . $cat->slug ] = sprintf('Category: %s',$cat->name);
    $cat_arr[ $cat->slug ] = $cat->name;

}

$source_arr += $postTypesList;

$source_arr[ 'shortcode' ] = 'A Shortcode'; // since 4.3

$source_arr[ 'sidebar' ] = 'A Custom Sidebar'; // since 4.4

// author array
$author_arr = [];
$authors = get_users([
    'who' => 'authors',
    'orderby' => 'display_name',
    'order' => 'ASC',
    'number' => 100,
]);
foreach ( $authors as $user ) {
    $author_arr[ $user->user_nicename ] = $user->display_name;
}

// orderby array
$orderby_arr = array(
    'date'=>'Date',
    'comment'=>'Comment count',
    'view'=>'View count',
    'view_week' =>'View count (Weekly)',
    'view_month'=>'View count (Monthly)',
    'view_year'=>'View count (Yearly)',

    'random' => 'Random',
);

$sections = array();
$section_info = fox_builder_section_info();

$max_sections = $section_info[ 'max_sections' ];

$taxlist = [];
foreach( $postTypes as $pt ) {

    $taxlist = array_merge( $taxlist, get_object_taxonomies( $pt ) );

}
$taxlist = array_unique( $taxlist );

$sidebar_list = [ '' => 'Select Sidebar' ];
foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
    $sidebar_list[ $sidebar['id'] ] = $sidebar['name'];
}

$layout_arr = fox_builder_layout_support();

$sections_order_without_main = $section_info[ 'sections_order_without_main' ];

$main_priority = 10000; // a relatively large number
if ( ! $section_info[ 'main_after' ] ) {
    $main_priority = 5;
}

foreach ( $sections_order_without_main as $key => $i ) {
    
    $priority = 10 * ( $key + 1 );
    if ( $section_info[ 'main_after' ] == $i ) {
        $main_priority = 10 * ( $key + 1 ) + 5;
    }

    $pre = "bf_{$i}_";

    // add section
    $cat = get_theme_mod( 'bf_' . $i . '_cat' );
    $layout = get_theme_mod( 'bf_' . $i . '_layout', 'slider' );
    if ( $layout == 'big-post' ) $layout = 'big';
    
    $title = '';
    
    if ($cat == 'featured') $title .= 'Featured posts';
    elseif ($cat == 'all') $title .= 'All';
    elseif ($cat == 'sticky') $title .= 'Sticky posts';
    elseif ( substr( $cat, 0, 10 ) == 'post_type_' ) {
        $pt = substr( $cat, 10 );
        $title .= 'Post type: ' . $pt;
    } elseif ($cat != '' && $cat != 'none' ) {
        $catname = str_replace( 'cat_', '', $cat );
        $catname = str_replace( '-', ' ', $catname );
        $title .= '' . ucfirst( $catname );
    }

    if ( $cat && $cat != 'none' && $cat != 'shortcode' && $cat != 'sidebar' ) {
        $title .= ' (' . $layout_arr[ $layout ] . ')';
    }
    
    if ( empty( $title ) ) {
        $title = '---';
        $hint = '';
    } else {
        $hint = $title;
    }

    $source_toggle = [];
    foreach( $source_arr as $source => $source_name ) {

        if ( substr( $source, 0, 10 ) != 'post_type_' ) {

            $source_toggle[ $source ] = [ "{$pre}cat_include", "{$pre}cat_exclude" ];

        } else {

            $pt = substr( $source, 10 );
            $taxes = get_object_taxonomies( $pt );
            $source_toggle[ $source ] = [];
            foreach ( $taxes as $tax ) {
                $source_toggle[ $source ][] = "{$pre}tax_{$tax}";
            }

        }
    }
    $source_toggle[ 'shortcode' ] = [ "{$pre}shortcode" ];
    $source_toggle[ 'sidebar' ] = [ "{$pre}main_sidebar", "{$pre}sidebar_layout" ];

    $options[ "{$pre}cat" ] = array(
        'name'    => 'Display:',
        'type'     => 'select',
        'options'  => $source_arr,
        'std' => 'none',

        'toggle' => $source_toggle,

        'section'  => 'bf_'.$i,
        'section_title' => $title,
        'section_priority' => $priority,
        'panel' => 'homepage',

        'prefix'    => false,
    );
    if ( $hint ) {
        $options[ "{$pre}cat" ][ 'hint' ] = $hint;
    }

    $options[ "{$pre}shortcode" ] = array(
        'name'    => 'Shortcode:',
        'type'     => 'textarea',
        'placeholder' => 'Eg. [author_grid number=3 column=3 /]',
        'prefix'    => false,
    );
    
    $options[ "{$pre}main_sidebar" ] = array(
        'name'    => 'Choose sidebar',
        'type'     => 'select',
        'options'   => $sidebar_list,
        'std'       => '',
        'prefix'    => false,
        'desc'   => 'Go to <a href="' . admin_url( 'admin.php?page=sidebar-manager' ) . '" target="_blank">Dashboard > Fox Magazine > Sidebar Manager</a> to create your custom sidebar then it\'ll appear in this list',
    );
    
    $options[ "{$pre}sidebar_layout" ] = array(
        'name'    => 'Widgets Layout',
        'type'     => 'image_radio',
        'options'   => [
            '1' => [
                'src' => get_template_directory_uri() . '/inc/customizer/assets/img/1-col.jpg',
                'width' => '100',
                'height' => 'auto',
                'title' => 'Fullwidth',
            ],
            '2' => [
                'src' => get_template_directory_uri() . '/inc/customizer/assets/img/2-cols.jpg',
                'width' => '100',
                'height' => 'auto',
                'title' => '2 columns',
            ],
            '3' => [
                'src' => get_template_directory_uri() . '/inc/customizer/assets/img/3-cols.jpg',
                'width' => '100',
                'height' => 'auto',
                'title' => '3 columns',
            ],
            '4' => [
                'src' => get_template_directory_uri() . '/inc/customizer/assets/img/4-cols.jpg',
                'width' => '100',
                'height' => 'auto',
                'title' => '4 columns',
            ],
        ],
        'std'       => '3',
        'prefix'    => false,
        'desc'      => 'If you have 3 columns, please use 3 widgets in your sidebar',
    );
    
    // 01 - QUERY OPTIONS
    $options[] = array(
        'type' => 'heading',
        'name' => 'Query Options',
    );

    foreach( $taxlist as $tax ) {

        $tax_obj = get_taxonomy( $tax );

        $options[ "{$pre}tax_{$tax}" ] = array(
            'name'    => "{$tax_obj->labels->name} ({$tax}):",
            'placeholder' => 'Eg. Fiction, Comedy',
            'desc'    => 'Separate items by comma.',
            'type'     => 'text',

            'prefix'    => false,
        );

    }

    $options[ "{$pre}number" ] = array(
        'name'    => 'Number of posts to show?',
        'type'     => 'text',
        'prefix'    => false,
    );

    $options[ "{$pre}cat_include" ] = array(
        'name'    => 'Include only categories',
        'type'     => 'multiselect',
        'options'  => $cat_arr,
        'prefix'    => false,
    );

    $options[ "{$pre}cat_exclude" ] = array(
        'name'    => 'Exclude categories:',
        'type'     => 'multiselect',
        'options'  => $cat_arr,
        'prefix'    => false,
    );

    $options[ "{$pre}authors" ] = array(
        'name'    => 'Only from authors:',
        'type'     => 'multiselect',
        'options'  => $author_arr,
        'prefix'    => false,
    );

    $options[ "{$pre}orderby" ] = array(
        'name'    => 'Order By?',
        'type'     => 'select',
        'options'   => $orderby_arr,
        'std'       => 'date',

        'prefix'    => false,
    );

    $options[ "{$pre}order" ] = array(
        'name'    => 'Order',
        'type'     => 'select',
        'options'   => [
            'asc' => 'Ascending',
            'desc' => 'Descending',
        ],
        'std'       => 'desc',
        'prefix'    => false,
    );

    $options[ "{$pre}offset" ] = array(
        'name'    => 'Offset',
        'desc'      => 'Number of posts to pass by',
        'type'     => 'text',

        'prefix'    => false,
    );

    $options[ "{$pre}custom_query" ] = array(
        'name'    => 'Custom Query (query string)',
        'placeholder' => 'Eg. posts_per_page=1&order=asc',
        'desc'      => 'Never use unless you know exactly what are you doing.',
        'type'     => 'textarea',
        'prefix'    => false,
    );

    // DISPLAY OPTIONS
    $options[] = array(
        'type' => 'heading',
        'name' => 'Displaying',
    );

    $toggle = [
        'standard' => [
            'color', 'content_excerpt', 'excerpt_length', 'excerpt_size', 'excerpt_more_text', 'excerpt_more_style',
        ],
        'grid' => [
            'color', 'item_spacing', 'item_align', 'item_border', 'item_border_color', 'title_size', 'excerpt_length', 'excerpt_size', 'item_template', 'excerpt_more_text', 'excerpt_more_style', 'thumbnail', 'thumbnail_custom', 'thumbnail_shape',
        ],
        'masonry' => [
            'color', 'item_spacing', 'item_align', 'item_border', 'item_border_color', 'title_size', 'excerpt_length', 'excerpt_size', 'item_template', 'excerpt_more_text', 'excerpt_more_style', 'thumbnail_shape',
        ],
        'list' => [
            'color', 'title_size', 'excerpt_length', 'excerpt_size', 'item_template'
            , 'excerpt_more_text', 'excerpt_more_style', 'thumbnail', 'thumbnail_custom', 'thumbnail_shape',
        ],
        'vertical' => [
            'color', 'excerpt_length', 'item_template', 'excerpt_more_text', 'excerpt_more_style',
        ],
        'newspaper' => [
            'color', 'content_excerpt', 'excerpt_length', 'excerpt_size', 'excerpt_more_text', 'excerpt_more_style',
        ],
        'big' => [
            'color', 'content_excerpt', 'item_align', 'excerpt_length', 'title_size', 'excerpt_size', 'excerpt_more_text', 
        ],
        'slider' => [
            'item_align', 'title_size',
        ],
        'group-1' => [
            "big_post_position"
        ],
        'group-2' => [
            "columns_order",
        ],
        'slider-1' => [
            'slide_content_color',
            'slide_content_background',
            'slide_content_background_opacity',
        ],
    ];

    foreach ( $toggle as $k => $arr ) {
        $new_arr = [];
        foreach ( $arr as $item ) {
            $new_arr[] = $pre . $item;
        }
        $toggle[ $k ] = $new_arr ;
    }
    for ( $j = 2; $j <=5; $j++ ) {
        $toggle[ 'grid-' . $j ] = $toggle[ 'grid' ];
        $toggle[ 'masonry-' . $j ] = $toggle[ 'masonry' ];
    }

    $options[ "{$pre}layout" ] = array(
        'name'    => 'Displaying as',
        'type'     => 'select',
        'options'   => $layout_arr,
        'std'       => 'slider',
        'prefix'    => false,

        'toggle' => $toggle,

        'desc' => 'To customize post layouts, please go to<br><a href="javascript:wp.customize.panel( \'wi_post_layouts\' ).focus();"><strong>"Customize > Blog Post Layouts" panel</strong></a>.',
    );

    $options[ "{$pre}customize_components" ] = array(
        'shorthand' => 'enable',
        'options' => [
            'true' => 'Yes please!',
            'false' => 'No thanks!',
        ],
        'std' => 'false',
        'name' => 'Customize show/hide components',
        'toggle' => [
            'true' => [ "{$pre}components" ],
        ],
        'desc' => 'This only applies for: Grid, masonry, list, standard, newspaper, vertical, big.',
        'prefix'    => false,
    );

    $options[ "{$pre}components" ] = array(
        'type' => 'multicheckbox',
        'options' => [
            'thumbnail' => 'Thumbnail',
            'title' => 'Title',
            'date' => 'Date',
            'category' => 'Category',
            'author' => 'Author',
            'author_avatar' => 'Author avatar',
            'excerpt' => 'Excerpt',
            'excerpt_more' => 'More link',
            'view' => 'View count',
            'reading_time' => 'Reading time',
            'comment_link' => 'Comment link',
        ],
        'std' => 'thumbnail,title,date,category,excerpt',
        'name' => 'Components',
        'prefix'    => false,
    );
    
    // slider-1 options
    $options[ "{$pre}slide_content_color" ] = array(
        'name'    => 'Slide Text Color',
        'type'     => 'color',
        'prefix'    => false,
    );
    
    $options[ "{$pre}slide_content_background" ] = array(
        'name'    => 'Slide Text Background',
        'type'     => 'color',
        'prefix'    => false,
    );
    
    $options[ "{$pre}slide_content_background_opacity" ] = array(
        'name'    => 'Background Opacity',
        'type'     => 'text',
        'placeholder' => 'Number 0 - 1, eg. 0.7',
        'prefix'    => false,
    );

    // group 1 option
    $options[ "{$pre}big_post_position" ] = array(
        'name'    => 'Big Post Position',
        'type'     => 'select',
        'options'   => [
            '' => 'Default',
            'left' => 'Left',
            'right' => 'Right',
        ],
        'std' => '',
        'prefix'    => false,
    );

    // group 2 option
    $options[ "{$pre}columns_order" ] = array(
        'name'    => 'Columns Order',
        'type'     => 'select',
        'options'   => [
            '' => 'Default',
            '1a-1b-3'  => 'Big / Medium / Small posts',
            '1b-1a-3'  => 'Medium / Big / Small posts',

            '1a-3-1b'  => 'Big / Small posts / Medium',
            '1b-3-1a'  => 'Medium / Small posts / Big',

            '3-1a-1b'  => 'Small posts / Big / Medium',
            '3-1b-1a'  => 'Small posts / Medium / Big',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}item_spacing" ] = array(
        'name'    => 'Item Spacing',
        'type'     => 'select',
        'options'   => [
            '' => 'Default',
            'none' => 'No spacing',
            'tiny' => 'Tiny',
            'small' => 'Small',
            'normal' => 'Normal',
            'medium' => 'Medium',
            'wide' => 'Wide',
            'wider' => 'Wider',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}item_template" ] = array(
        'name' => 'Elements order',
        'type' => 'select',

        'options' => array(
            ''  => 'Default',
            '1' => 'Title > Meta > Excerpt',
            '2' => 'Meta > Title > Excerpt',
            '3' => 'Title > Excerpt > Meta',

            '4' => 'Category > Title > Meta > Excerpt',
            '5' => 'Category > Title > Excerpt > Meta',
        ),

        'std' => '',
        'prefix'    => false,
    );
    
    $options[ "{$pre}item_border" ] = array(
        'name' => 'Grid item border',
        'type' => 'select',

        'options' => array(
            ''  => 'Default',
            'true' => 'Yes',
            'false' => 'No',
        ),
        'std' => '',
        'prefix'    => false,
    );
    
    $options[ "{$pre}item_border_color" ] = array(
        'name' => 'Border color',
        'type' => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}item_align" ] = array(
        'name' => 'Item alignment',
        'type' => 'select',
        'options' => array(
            '' => 'Default',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ),
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}thumbnail" ] = array(
        'name' => 'Thumbnail size',
        'type' => 'select',
        'options' => array(
            '' => 'Default',
            'landscape' => 'Medium (480x384)',
            'square' => 'Square (480x480)',
            'portrait' => 'Portrait (480x600)',
            'thumbnail-large' => 'Large (720x480)',
            'original' => 'Original size',
            'original_fixed' => 'Fixed height',
            'custom' => 'Enter Custom size',
        ),
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}thumbnail_custom" ] = array(
        'name' => 'Thumbnail custom',
        'type' => 'text',
        'placeholder' => 'Eg. 420x260',
        'std' => '',
        'desc' => 'In case you choose "Custom size" above',
        'prefix'    => false,
    );
    
    $options[ "{$pre}thumbnail_shape" ] = array(
        'name' => 'Thumbnail shape',
        'type' => 'select',
        'options' => array(
            '' => 'Default',
            'acute' => 'Acute',
            'round' => 'Round',
            'circle' => 'Circle',
        ),
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}color" ] = array(
        'name'    => 'Text Color',
        'type'     => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}title_size" ] = array(
        'name' => 'Title size',
        'type' => 'select',
        'options' => [
            '' => 'Default',
            'supertiny' => 'Super Tiny',
            'tiny' => 'Tiny',
            'small' => 'Small',
            'normal' => 'Normal',
            'medium' => 'Medium',
            'large' => 'Large',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}content_excerpt" ] = array(
        'type' => 'select',
        'name' => 'Content/Excerpt?',
        'options' => [
            '' => 'Default',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}excerpt_length" ] = array(
        'type' => 'text',
        'name' => 'Excerpt length',
        'prefix'    => false,
    );

    $options[ "{$pre}excerpt_more_style" ] = [
        'type' => 'select',
        'options' => [
            '' => 'Default',
            'simple' => 'Plain Link',
            'simple-btn' => 'Minimal Link', // simple button
            'btn' => 'Fill Button', // default btn
            'btn-outline' => 'Outline Button',
            'btn-black' => 'Solid Black Button',
            'btn-primary' => 'Primary Button',
        ],
        'std' => '',
        'name' => 'More link style',
        'prefix'    => false,
    ];

    $options[ "{$pre}excerpt_more_text" ] = [
        'type' => 'text',
        'placeholder' => 'Eg. Keep Reading',
        'std' => '',
        'name' => 'Excerpt more text',
        'prefix'    => false,
    ];

    $options[ "{$pre}excerpt_size" ] = array(
        'type' => 'select',
        'options' => [
            '' => 'Default',
            'small' => 'Small',
            'normal' => 'Normal',
            'medium' => 'Medium',
        ],
        'std' => '',
        'name' => 'Excerpt size',
        'prefix'    => false,
    );

    // HEADING
    $options[] = array(
        'type' => 'heading',
        'name' => 'Heading',
    );

    $options[ "{$pre}heading" ] = array(
        'name'      => 'Heading text',
        'type'      => 'text',

        'prefix'    => false,
    );

    $options[ "{$pre}heading_color" ] = array(
        'name'      => 'Heading color',
        'type'      => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_style" ] = array(
        'name'      => 'Heading style',
        'type'      => 'select',
        'options'   => [
            '' => 'Inherit',
            'plain' => 'Plain',

            '1a' => 'Border Top',
            '1b' => 'Border Bottom',

            '2a' => '2 thin lines middle',
            '2b' => '2 thin lines bottom',

            '3a' => '2 thick lines middle',
            '3b' => '2 thick lines bottom',

            '4a' => '2 wavy lines middle',
            '4b' => '2 wavy lines bottom',

            '5' => 'Border around',

            '6' => 'Wave bottom',
            
            '7a' => 'Diagonal Stripes',
            '8a' => 'Pixelate dot band'
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_line_stretch" ] = [
        'type'      => 'select',
        'name'      => 'Heading Line Stretch',
        'options'   => [
            '' => 'Default',
            'content' => 'Content width',
            'content-half' => 'Half content width',
            'full' => 'Stretch full screen width',

        ],
        'std' => '',
        'prefix'    => false,
    ];

    $options[ "{$pre}heading_align" ] = array(
        'name'      => 'Heading align',
        'type'      => 'select',
        'options'   => [
            '' => 'Default',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ],
        'std'       => '',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_size" ] = array(
        'name'      => 'Heading size',
        'type'      => 'select',
        'options'   => [
            '' => 'Default',
            'ultra' => 'Ultra Large',
            'extra' => 'Extra Large',
            'large' => 'Large',
            'medium' => 'Medium',
            'normal' => 'Normal',
            'small' => 'Small',
            'tiny' => 'Tiny',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}viewall_link" ] = array(
        'name'      => 'Heading URL',
        'type'      => 'url',
        'placeholder' => 'https://',

        'prefix'    => false,
    );

    /**
     * deprecated since 4.3
    $options[ "{$pre}viewall_text" ] = array(
        'name'      => '"View all" text',
        'type'      => 'text',

        'prefix'    => false,
    );
    */

    // SIDEBAR

    $options[] = array(
        'type' => 'heading',
        'name' => 'Sidebar',
    );

    $options[ "{$pre}sidebar" ] = array(
        'name'    => 'Sidebar?',
        'type'     => 'select',
        'options'   => $sidebar_list,
        'std'       => '',
        'prefix'    => false,
    );

    $options[ "{$pre}sidebar_position" ] = array(
        'name'    => 'Sidebar position',
        'type'     => 'select',
        'options'   => [
            'left' => 'Left',
            'right' => 'Right',
        ],
        'std'       => 'right',
        'prefix'    => false,
    );

    $options[ "{$pre}sidebar_sticky" ] = array(
        'name'    => 'Sticky Sidebar?',
        'shorthand' => 'enable',
        'options' => [
            'true' => 'Yes please!',
            'false' => 'No thanks!',
        ],
        'std' => 'false',
        'prefix'    => false,
    );
    
    $options[ "{$pre}sidebar_sep" ] = array(
        'name'    => 'Separator between main & sidebar',
        'shorthand' => 'enable',
        'options' => [
            'true' => 'Yes please!',
            'false' => 'No thanks!',
        ],
        'std' => 'false',
        'prefix'    => false,
    );
    
    $options[ "{$pre}sidebar_sep_color" ] = array(
        'name'    => 'Separator color',
        'type'    => 'color',
        'prefix'    => false,
    );

    // BANNER
    $options[] = array(
        'type' => 'heading',
        'name' => 'Banner & Ad',
    );

    $options[ "{$pre}ad_code" ] = array(
        'name'      => 'Advertisement Code',
        'type'      => 'textarea',
        'desc'      => 'Note that the ad will appear BEFORE this section. You can insert HTML, Javascript, Adsense code... If you use image banner, you can use upload button below.',

        'prefix'    => false,
    );

    $options[ "{$pre}banner" ] = array(
        'name'      => 'Image Banner',
        'type'      => 'image',
        'desc'      => 'This banner appears before posts',

        'prefix'    => false,
    );

    $options[ "{$pre}banner_width" ] = array(
        'name'      => 'Banner width',
        'type'      => 'text',
        'placeholder' => 'Eg. 728',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_tablet" ] = array(
        'name'      => 'Tablet Image',
        'type'      => 'image',
        'desc'      => 'This banner appears before posts',

        'prefix'    => false,
    );

    $options[ "{$pre}banner_tablet_width" ] = array(
        'name'      => 'Banner tablet width',
        'type'      => 'text',
        'placeholder' => 'Eg. 600',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_mobile" ] = array(
        'name'      => 'Mobile Image',
        'type'      => 'image',

        'prefix'    => false,
    );

    $options[ "{$pre}banner_mobile_width" ] = array(
        'name'      => 'Banner mobile width',
        'type'      => 'text',
        'placeholder' => 'Eg. 300',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_url" ] = array(
        'name'      => 'Banner URL',
        'type'      => 'text',
        'placeholder' => 'http://',

        'prefix'    => false,
    );
    
    $options[ "{$pre}ad_visibility" ] = array(
        'name'      => 'Ad Visibility',
        'type'      => 'multicheckbox',
        'options'   => [
            'desktop' => 'Desktop',
            'tablet' => 'Tablet',
            'mobile' => 'Mobile',
        ],
        'std' => 'desktop,tablet,mobile',

        'prefix'    => false,
    );

    // 05 - DESIGN
    $options[] = array(
        'type' => 'heading',
        'name' => 'Section Design',
    );
    
    $options[ "{$pre}section_visibility" ] = array(
        'name'      => 'Section Visibility',
        'type'      => 'multicheckbox',
        'options'   => [
            'desktop' => 'Desktop',
            'tablet' => 'Tablet',
            'mobile' => 'Mobile',
        ],
        'std' => 'desktop,tablet,mobile',

        'prefix'    => false,
    );

    $options[ "{$pre}stretch" ] = array(
        'name'      => 'Stretch',
        'type'      => 'select',
        'options'   => [
            'narrow' => 'Narrow width',
            'content' => 'Content width',
            'full' => 'Full width (edge of screen)'
        ],
        'std'       => 'content',
        'prefix'    => false,
    );

    $options[ "{$pre}background" ] = array(
        'name'      => 'Background',
        'type'      => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}text_color" ] = array(
        'name'      => 'Text Color',
        'type'      => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}border" ] = array(
        'name'      => 'Border',
        'type'      => 'select',
        'options' => [
            ''    => 'None',
            'shadow' => '3D-like',
            '1px' => 'Border 1px',
            '2px' => 'Border 2px',
            '3px' => 'Border 3px',
            '4px' => 'Border 4px',
            '5px' => 'Border 5px',
            '6px' => 'Border 6px',
            '8px' => 'Border 8px',
            '10px' => 'Border 10px',
            'dotted' => 'Dotted',
            'dashed' => 'Dashed',
        ],
        'prefix'    => false,
    );

}

/* Main Stream
-------------------- */
$pre = 'main_stream_';

$options[ 'main_stream' ] = array(
    'shorthand' => 'enable',
    'std'       => 'true',
    'name'      => 'Main posts stream?',

    'section'   => 'main_stream',
    'section_title' => 'Main Stream',
    'section_priority' => $main_priority,
    'section_desc' => 'Check this to disable main posts stream on your homepage. This will make your site looks like a magazine instead of a blog.',
    'panel'     => 'homepage',
    
    'hint' => 'Main stream',
);

$options[ 'home_layout' ] = array(
    'type'      => 'select',
    'options'   => $layout_options,
    'std'       => 'list',
    'name'      => 'Main Stream Layout',
    
    'hint' => 'Main stream layout',
);

$options[ 'home_sidebar_state' ] = array(
    'type'      => 'select',
    'options'   => [
        'sidebar-left'  => 'Sidebar Left',
        'sidebar-right' => 'Sidebar Right',
        'no-sidebar'    => 'No Sidebar',
    ],
    'std'       => 'sidebar-right',
    'name'      => 'Main Stream Sidebar',
    
    'hint' => 'Main stream sidebar',
);

$options[ "{$pre}sidebar_sep" ] = array(
    'name'    => 'Separator between main & sidebar',
    'shorthand' => 'enable',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'std' => 'false',
    'prefix'    => false,
);

$options[ "{$pre}sidebar_sep_color" ] = array(
    'name'    => 'Separator color',
    'type'    => 'color',
    'prefix'    => false,
);

$options[ 'home_number' ] = array(
    'type'      => 'text',
    'name'      => esc_html__( 'Custom number of posts to show on blog', 'wi' ),
    'desc'      => 'This option only works for the main blog. For a general setting of number of posts to show, please visit Dashboard > Settings > Reading',
    
    'hint' => 'Main stream number of posts',
);

$options[ 'offset' ] = array(
    'type'      => 'text',
    'name'      => 'Offset?',
    'placeholder' => 'Eg. 3',
    'desc'      => 'If you enter 3, your blog stream starts from 4th. This option only works for the main blog.',
    
    'hint' => 'Main stream offset',
);

$categories = get_categories( array(
    'fields' => 'id=>name',
    'orderby'=> 'slug',
    'hide_empty' => false,

    'number' => 100, // prevent huge blogs
));

// since 4.0
$options[ 'include_categories' ] = array(
    'type'      => 'multicheckbox',
    'name'      => 'Include only categories:',
    'std'       => '',
    'options'   => $categories,
    
    'hint' => 'Main stream include/exclude cats',
);

$options[ 'exclude_categories' ] = array(
    'type'      => 'multicheckbox',
    'name'      => 'Exclude categories:',
    'options'   => $categories,
);

/*
$options[ 'main_stream_order' ] = array(
    'type'      => 'text',
    'placeholder' => 'Eg. 2',
    'name'      => '[Deprecated] Main stream after section?',
    'desc' => '<span class="deprecated" style="color:#b73030">By default, main stream will be displayed after all sections. By this option, you can change the order of main stream and allow it to be displayed right after some section. Enter 0 to display main stream before all. This option has been deprecated since Fox 4.4 due to section drag/drop support.</span>',
);
*/

// HEADING
    $options[] = array(
        'type' => 'heading',
        'name' => 'Heading',
    );

    $options[ "{$pre}heading" ] = array(
        'name'      => 'Heading text',
        'type'      => 'text',

        'prefix'    => false,
        
        'hint' => 'Main stream heading',
    );

    $options[ "{$pre}heading_color" ] = array(
        'name'      => 'Heading color',
        'type'      => 'color',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_style" ] = array(
        'name'      => 'Heading style',
        'type'      => 'select',
        'options'   => [
            '' => 'Inherit',
            'plain' => 'Plain',

            '1a' => 'Border Top',
            '1b' => 'Border Bottom',

            '2a' => '2 thin lines middle',
            '2b' => '2 thin lines bottom',

            '3a' => '2 thick lines middle',
            '3b' => '2 thick lines bottom',

            '4a' => '2 wavy lines middle',
            '4b' => '2 wavy lines bottom',

            '5' => 'Border around',

            '6' => 'Wave bottom',
            
            '7a' => 'Diagonal Stripes',
            '8a' => 'Pixelate dot band'
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_line_stretch" ] = [
        'type'      => 'select',
        'name'      => 'Heading Line Stretch',
        'options'   => [
            '' => 'Default',
            'content' => 'Content width',
            'content-half' => 'Half content width',
            'full' => 'Stretch full screen width',

        ],
        'std' => '',
        'prefix'    => false,
    ];

    $options[ "{$pre}heading_align" ] = array(
        'name'      => 'Heading align',
        'type'      => 'select',
        'options'   => [
            '' => 'Default',
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ],
        'std'       => '',
        'prefix'    => false,
    );

    $options[ "{$pre}heading_size" ] = array(
        'name'      => 'Heading size',
        'type'      => 'select',
        'options'   => [
            '' => 'Default',
            'ultra' => 'Ultra Large',
            'extra' => 'Extra Large',
            'large' => 'Large',
            'medium' => 'Medium',
            'normal' => 'Normal',
            'small' => 'Small',
            'tiny' => 'Tiny',
        ],
        'std' => '',
        'prefix'    => false,
    );

    $options[ "{$pre}viewall_link" ] = array(
        'name'      => 'Heading URL',
        'type'      => 'url',
        'placeholder' => 'https://',

        'prefix'    => false,
    );

    // BANNER
    $options[] = array(
        'type' => 'heading',
        'name' => 'Banner & Ad',
        
        'hint' => 'Main stream ad',
    );

    $options[ "{$pre}ad_code" ] = array(
        'name'      => 'Advertisement Code',
        'type'      => 'textarea',
        'desc'      => 'Note that the ad will appear BEFORE this section. You can insert HTML, Javascript, Adsense code... If you use image banner, you can use upload button below.',

        'prefix'    => false,
    );

    $options[ "{$pre}banner" ] = array(
        'name'      => 'Image Banner',
        'type'      => 'image',
        'desc'      => 'This banner appears before posts',

        'prefix'    => false,
        
        'hint' => 'Main stream banner',
    );

    $options[ "{$pre}banner_width" ] = array(
        'name'      => 'Banner width',
        'type'      => 'text',
        'placeholder' => 'Eg. 728',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_tablet" ] = array(
        'name'      => 'Tablet Image',
        'type'      => 'image',
        'desc'      => 'This banner appears before posts',

        'prefix'    => false,
    );

    $options[ "{$pre}banner_tablet_width" ] = array(
        'name'      => 'Banner tablet width',
        'type'      => 'text',
        'placeholder' => 'Eg. 600',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_mobile" ] = array(
        'name'      => 'Mobile Image',
        'type'      => 'image',

        'prefix'    => false,
    );

    $options[ "{$pre}banner_mobile_width" ] = array(
        'name'      => 'Banner mobile width',
        'type'      => 'text',
        'placeholder' => 'Eg. 300',
        'prefix'    => false,
    );

    $options[ "{$pre}banner_url" ] = array(
        'name'      => 'Banner URL',
        'type'      => 'text',
        'placeholder' => 'http://',

        'prefix'    => false,
    );

    $options[ "{$pre}ad_visibility" ] = array(
        'name'      => 'Ad Visibility',
        'type'      => 'multicheckbox',
        'options'   => [
            'desktop' => 'Desktop',
            'tablet' => 'Tablet',
            'mobile' => 'Mobile',
        ],
        'std' => 'desktop,tablet,mobile',

        'prefix'    => false,
    );

    // 05 - DESIGN
    $options[] = array(
        'type' => 'heading',
        'name' => 'Section Design',
        
        'hint' => 'Main stream design',
    );

    $options[ "{$pre}stretch" ] = array(
        'name'      => 'Stretch',
        'type'      => 'select',
        'options'   => [
            'content' => 'Content width',
            'full' => 'Full width (edge of screen)'
        ],
        'std'       => 'content',
        'prefix'    => false,
        
        'hint' => 'Main stream stretch',
    );

    $options[ "{$pre}background" ] = array(
        'name'      => 'Background',
        'type'      => 'color',
        'prefix'    => false,
        
        'hint' => 'Main stream background',
    );

    $options[ "{$pre}text_color" ] = array(
        'name'      => 'Text Color',
        'type'      => 'color',
        'prefix'    => false,
        
        'hint' => 'Main stream text color',
    );

    $options[ "{$pre}border" ] = array(
        'name'      => 'Border',
        'type'      => 'select',
        'options' => [
            ''    => 'None',
            'shadow' => '3D-like',
            '1px' => 'Border 1px',
            '2px' => 'Border 2px',
            '3px' => 'Border 3px',
            '4px' => 'Border 4px',
            '5px' => 'Border 5px',
            '6px' => 'Border 6px',
            '8px' => 'Border 8px',
            '10px' => 'Border 10px',
            'dotted' => 'Dotted',
            'dashed' => 'Dashed',
        ],
        'prefix'    => false,
        
        'hint' => 'Main stream border',
    );