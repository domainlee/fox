<?php
/* Post Grid Options
----------------------------------- */
$pre = "blog_grid_"; // due to backward-compatibility reason

$options[] = [

    'type' => 'heading',
    'name' => 'General',

    'section' => 'blog_grid',
    'section_title' => 'Post Grid & General',
    'section_desc' => 'This section has common settings for post grid layout and will be used site-wide like in archive, homepage builder sections',

    'panel' => 'post_layouts',
    'panel_title' => 'Blog Post Layouts',
    'panel_priority' => 127,

];

$options[ "{$pre}item_spacing" ] = [

    'name' => 'Item Spacing',

    'type' => 'select',
    'options' => [
        'none' => 'No spacing',
        'tiny' => 'Tiny',
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
        'wide' => 'Wide',
        'wider' => 'Wider',
    ],
    'std' => 'normal',
    
    'hint' =>  'Post grid & general options',

];

$options[ "{$pre}item_template" ] = array(

    'name' => 'Elements order',
    'type' => 'select',

    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),

    'std' => '1',
    
    'hint' =>  'Post title, meta, excerpt order',
    
);

$options[ "{$pre}item_align" ] = array(

    'name' => 'Item Align',
    'type' => 'select',

    'options' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ),

    'std' => 'left',
    
    'hint' =>  'Post item align',

);

$options[ "{$pre}item_border" ] = array(

    'name' => 'Grid border?',
    'desc' => 'The vertical border between grid items?',
    'shorthand' => 'enable',
    'std' => 'false',
    
    'hint' =>  'Grid border',
    
);

$options[ "{$pre}item_border_color" ] = array(

    'name' => 'Border color?',
    'type' => 'color',
    
);

// THUMBNAIL
$options[] = [
    'type' => 'heading',
    'name' => 'Thumbnail',
];

$options[ "{$pre}show_thumbnail" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show Thumbnail?',
    
    'hint' =>  'Blog post thumbnail',
];

$options[ "{$pre}thumbnail" ] = [
    'type' => 'select',
    'options' => [
        'landscape' => 'Landscape (480x384)',
        'square' => 'Square (480x480)',
        'portrait' => 'Portrait (480x600)',
        'thumbnail-large' => 'Large (720x480)',
        'original' => 'Original size',
        'original_fixed' => 'Fixed height',
        'custom' => 'Enter Custom size',
    ],
    'std' => 'landscape',
    'toggle' => [
        'custom' => [ "{$pre}thumbnail_custom" ]
    ],
    'name' => 'Thumbnail',
    
    'hint' =>  'Post thumbnail size',
];

$options[ "{$pre}thumbnail_custom" ] = [
    'type' => 'text',
    'placeholder' => 'Eg. 420x560',
    'name' => 'Custom thumbnail size',
    
    'hint' =>  'Thumbnail custom size',
];

$options[ "{$pre}thumbnail_placeholder" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Use placeholder thumbnail?',
    'desc' => 'When your post has no thumbnails and no images attached.',
    
    'hint' =>  'Thumbnail placeholder image',
];

$options[ "{$pre}default_thumbnail" ] = [
    'type' => 'image',
    'name' => 'Upload your default thumbnail',
];

$options[ "{$pre}thumbnail_shape" ] = [
    'type' => 'select',
    'std'   => 'acute',
    'options' => [
        'acute'     => 'Acute',
        'round'     => 'Round',
        'circle'    => 'Circle',
    ],
    'name' => 'Thumbnail shape',
    
    'hint' =>  'Thumbnail shape',
];

$options[ "{$pre}thumbnail_hover_effect" ] = [
    'type' => 'select',
    'std'   => 'none',
    'options' => [
        'none'      => 'None',
        'fade'      => 'Image Fade',
        'dark'      => 'Dark',
        'letter'    => 'Title First Letter',
        'zoomin'    => 'Image Zoom In',
        'logo'      => 'Custom Logo',
    ],
    'toggle' => [
        'logo' => [ "{$pre}thumbnail_hover_logo", "{$pre}thumbnail_hover_logo_width" ]
    ],
    'name' => 'Thumbnail hover effect?',
    
    'hint' =>  'Thumbnail hover effect',
];

$options[ "{$pre}thumbnail_hover_logo" ] = [
    'type' => 'image',
    'name' => 'Thumbnail hover logo',
    'desc' => 'Should be a white transparent logo',
    
    'hint' =>  'Thumbnail hover logo',
];

$options[ "{$pre}thumbnail_hover_logo_width" ] = [
    'type'  => 'text',
    'std'   => '40%',
    'placeholder' => '40%',
    'name' => 'Thumbnail hover logo width',
    'desc' => 'Please enter a number in percentage.',
];

    // since 4.3
$options[ "{$pre}thumbnail_showing_effect" ] = [
    'type' => 'select',
    'std'   => 'none',
    'options' => [
        'none'      => 'None',
        'fade'      => 'Image Fade',
        'slide'     => 'Slide',
        'popup'     => 'Pop up',
        'zoomin'    => 'Zoom In',
    ],
    'name' => 'Thumbnail on showing effect?',
    
    'hint' =>  'Thumbnail loading effect',
];

$options[ "{$pre}format_indicator" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show format indicator',
    
    'hint' =>  'Post format icon',
];

$options[ "{$pre}thumbnail_index" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show counting index on thumbnail',
];

$options[ "{$pre}thumbnail_view" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show view count on thumbnail',
    
    'hint' =>  'View count on thumbnail',
];

$options[ "{$pre}thumbnail_review_score" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show review score on thumbnail',
];

// TITLE
$options[] = [
    'type' => 'heading',
    'name' => 'Title',
];

$options[ "{$pre}show_title" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show title',
];

$options[ "{$pre}title_tag" ] = [
    'type' => 'select',
    'options' => [
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
    ],
    'std' => 'h2',
    'name' => 'Title tag',
];

$options[ "{$pre}title_size" ] = [
    'type' => 'select',
    'std'   => 'normal',
    'options' => [
        'supertiny' => 'Super Tiny',
        'tiny' => 'Tiny',
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
        'large' => 'Large',
    ],
    'name' => 'Title size',
    
    'hint' =>  'Post title size',
];

$options[ "{$pre}title_weight" ] = [
    'type' => 'select',
    'std'   => '',
    'options' => [
        '' => 'Default',
        '300' => 'Light',
        '400' => 'Normal',
        '700' => 'Bold',
    ],
    'name' => 'Title weight',
    
    'hint' =>  'Post title font weight',
];

$options[ "{$pre}title_text_transform" ] = [
    'type' => 'select',
    'std'   => '',
    'options' => [
        '' => 'Default',
        'none' => 'None',
        'lowercase' => 'lowercase',
        'uppercase' => 'UPPERCASE',
        'capitalize' => 'Capitalize',
    ],
    'name' => 'Title text transform',
];

// META
$options[] = [
    'type' => 'heading',
    'name' => 'Post Meta',
];

$options[ "{$pre}show_date" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show date?',
    
    'hint' =>  'Show/hide post date',
];

$options[ "{$pre}show_category" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show category?',
    
    'hint' =>  'Show/hide post category',
];

$options[ "{$pre}show_author" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show post author?',
    
    'hint' =>  'Show/hide post author',
];

$options[ "{$pre}show_author_avatar" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Post author avatar?',
];

$options[ "{$pre}show_view" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Display view count?',
    
    'hint' =>  'Show/hide view count',
];

$options[ "{$pre}show_comment_link" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Display comment link?',
    
    'hint' =>  'Show/hide comment link',
];

$options[ "{$pre}reading_time" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Display reading time?',
    
    'hint' =>  'Show/hide reading time',
];

// EXCERPT
$options[] = [
    'type' => 'heading',
    'name' => 'Excerpt',
];

$options[ "{$pre}show_excerpt" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Show excerpt?',
    
    'hint' =>  'Show/hide excerpt',
];

$options[ "{$pre}excerpt_length" ] = [
    'type' => 'text',
    'std' => '22',
    'placeholder' => 'Eg. 22',
    'name' => 'Excerpt length',
    
    'hint' =>  'Excerpt length',
];

$options[ "{$pre}excerpt_hellip" ] = [
    'shorthand' => 'enable',
    'std' => 'false',
    'name' => 'Excerpt dot dot dot?',
    
    'hint' =>  'Excerpt ...',
];

$options[ "{$pre}excerpt_size" ] = [
    'type' => 'select',
    'options' => [
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
    ],
    'name' => 'Excerpt font size',
    'std'   => 'normal',
];

$options[ "{$pre}excerpt_color" ] = [
    'shorthand' => 'color',
    'selector' => '.post-item-excerpt',
    'name' => 'Excerpt color',
];

$options[ "{$pre}excerpt_more" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'More link?',
    
    'hint' =>  'Read more link',
];

$options[ "{$pre}excerpt_more_style" ] = [
    'options' => [
        'simple' => 'Plain Link',
        'simple-btn' => 'Minimal Link', // simple button
        'btn' => 'Fill Button', // default btn
        'btn-outline' => 'Button outline',
        'btn-black' => 'Solid Black Button',
        'btn-primary' => 'Primary Button',
    ],
    'std' => 'simple',
    'type' => 'select',
    'name' => 'More link style?',
];

$options[ "{$pre}excerpt_more_text" ] = [
    'type' => 'text',
    'placeholder' => 'Eg. Continue Reading..',
    'name' => 'Excerpt more text',
    
    'hint' =>  'Read more text',
];

/* Post Masonry Options
----------------------------------- */
$options[] = [

    'type' => 'html',
    'std' => '<p class="fox-info">Most of post masonry options are inherit from post grid options, such as item spacing, layout, show/hide elements..<br><a href="javascript:wp.customize.section( \'wi_blog_grid\' ).focus();">Go to post grid settings &rarr;</a><br>Here we only list options that apply to masonry layout</p>',

    'section' => 'blog_masonry',
    'section_title' => 'Post Masonry',
    'section_desc' => 'This section has common settings for post masonry layout and will be used site-wide like in archive, homepage builder sections',

    'panel' => 'post_layouts',

];

$options[ "{$pre}big_first_post" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Make first post bigger?',
    
    'hint' =>  'Post masonry: first post big',
];

/* Post List Options
----------------------------------- */
$options[] = [

    'type' => 'html',
    'std' => '<p class="fox-info">Many of list options are inherit from post grid options, such as item spacing, item layout, show/hide elements.. <br><a href="javascript:wp.customize.section( \'wi_blog_grid\' ).focus();">Go to post grid settings &rarr;</a><br>Here we only list options that apply to list layout</p>',

    'section' => 'blog_list',
    'section_title' => 'Post List',
    'section_desc' => 'This section has common settings for post list layout and will be used site-wide like in archive, homepage builder sections',

    'panel' => 'post_layouts',

];

$options[ $pre . 'list_spacing' ] = array(
    'type' => 'select',
    'name' => 'Spacing between list items',
    'options' => [
        'none' => 'No Spacing',
        'tiny' => 'Tiny',
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
        'large' => 'Large',
    ],
    'std' => 'normal',
    
    'hint' =>  'Post list spacing',
);

$options[ "{$pre}list_sep" ] = [
    'shorthand' => 'enable',
    'std' => 'true',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
    'name' => 'Use separator line between list posts',
    
    'hint' =>  'Post list separator line',
];

$options[ "{$pre}list_sep_style" ] = [
    'shorthand' => 'border-style',
    'selector'  => '.post-list-sep',
    'std'  => 'solid',
    'name' => 'Border style',
];

$options[ "{$pre}list_sep_color" ] = [
    'shorthand' => 'border-color',
    'selector'  => '.post-list-sep',
    'name' => 'Separator color',
];

$options[ "{$pre}list_valign" ] = [
    'type' => 'radio',
    'options' => [
        'top' => 'Top',
        'middle' => 'Middle',
        'bottom' => 'Bottom',
    ],
    'std' => 'top',
    'name' => 'Item vertical alignment',
    
    'hint' =>  'Post list vertical align',
];    

$options[ "{$pre}thumbnail_position" ] = [
    'type' => 'radio',
    'options' => [
        'left' => 'Left',
        'right' => 'Right',
    ],
    'std' => 'left',
    'name' => 'Thumbnail position',
    
    'hint' =>  'Post list thumbnail left/right',
];

$options[ "{$pre}thumbnail_width" ] = [
    'type' => 'text',
    'placeholder' => 'Eg. 40% or 450px',
    'name' => 'Thumbnail width',
    'property' => 'width',
    'selector' => '.list-thumbnail',
    
    'hint' =>  'Post list thumbnail width',
];

$options[ "{$pre}list_mobile_layout" ] = [
    'type' => 'radio',
    'options' => [
        'grid' => 'Stack',
        'list' => 'List',
    ],
    'std' => 'grid',
    'name' => 'List item layout on mobile:',
    
    'hint' =>  'Post list mobile layout',
];

/* Post Standard Options
----------------------------------- */
$options[ 'blog_standard_thumbnail_type' ] = [
    'name' => 'Blog standard thumbnail type',
    'type' => 'radio',
    'options' => [
        'advanced' => 'Rich thumbnail',
        'simple' => 'Image thumbnail',
    ],
    'std' => 'simple',

    'desc' => 'Rich thumbnail includes gallery, video.. for format posts',

    'section' => 'blog_standard',
    'section_title' => 'Post Standard',
    'panel' => 'post_layouts',
    
    'hint' =>  'Blog standard options',
];

$options[ 'blog_standard_thumbnail_header_order' ] = [
    'name' => 'Thumbnail & title Order',
    'type' => 'radio',
    'options' => [
        'thumbnail' => 'Thumbnail then title',
        'header' => 'Title then thumbnail',
    ],
    'std' => 'header',
];

$options[ 'blog_standard_content_excerpt' ] = [
    'name' => 'Standard post show:',
    'type' => 'radio',
    'options' => [
        'content' => 'Content',
        'excerpt' => 'Excerpt',
    ],
    'std' => 'content',
    
    'hint' =>  'Post standard content/excerpt',
];

$options[ 'excerpt_length' ] = [
    'name' => 'Standard post excerpt length',
    'type' => 'text',
    'std' => 55,
    'desc' => 'Enter number of words',
    'placeholder' => 'Eg. 55',
];

$options[ 'blog_standard_header_align' ] = array(
    'name' => 'Header text align',
    'type' => 'select',
    'options' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ),
    'std' => 'left',
);

$options[ 'blog_standard_show_share' ] = array(
    'name' => 'Share icons after post',
    'shorthand' => 'enable',
    'std' => 'true',
);

$options[ 'blog_standard_show_related' ] = array(
    'name' => 'Show related posts',
    'shorthand' => 'enable',
    'std' => 'true',
);

$options[ 'blog_column_layout' ] = fox_generate_option( 'column_layout', 'customizer', [
    'name' => 'Blog Text Column Layout',
    'desc' => 'This only works when you use content instead of excerpt',
] );

$options[ 'blog_dropcap' ] = fox_generate_option( 'dropcap', 'customizer', [
    'name' => 'Dropcap on blog posts',
    'desc' => 'This only works when you use content instead of excerpt',
] );

// info
$options[] = [
    'type' => 'html',
    
    'std' => '<p class="fox-info">Standard post shares header, meta, title properties with single post so please go to <a href="javascript:wp.customize.section( \'wi_single_header\' ).focus();">Single Post > Post Header</a> to customize meta, title..</p>',
    
    'hint' => 'Blog standard title font'
];

/* Post Newspaper Options
----------------------------------- */
$options[ 'post_newspaper_thumbnail_type' ] = [
    'name' => 'Newspaper post thumbnail type',
    'type' => 'radio',
    'options' => [
        'advanced' => 'Rich thumbnail',
        'simple' => 'Image thumbnail',
    ],
    'std' => 'simple',

    'desc' => 'Rich thumbnail includes gallery, video.. for format posts',

    'section' => 'blog_newspaper',
    'section_title' => 'Post Newspaper',
    'panel' => 'post_layouts',
    
    'hint' =>  'Blog newspaper options',
];

$options[ 'post_newspaper_content_excerpt' ] = [
    'name' => 'Newspaper post show:',
    'type' => 'radio',
    'options' => [
        'content' => 'Content',
        'excerpt' => 'Excerpt',
    ],
    'std' => 'content',
];

$options[ 'post_newspaper_header_align' ] = array(
    'name' => 'Header text align',
    'type' => 'select',
    'options' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ),
    'std' => 'left',
);

$options[ 'post_newspaper_show_share' ] = array(
    'name' => 'Share icons after post',
    'shorthand' => 'enable',
    'std' => 'true',
);

$options[ 'post_newspaper_show_related' ] = array(
    'name' => 'Show related posts',
    'shorthand' => 'enable',
    'std' => 'true',
);

/* Vertical Post Options
----------------------------------- */
$options[ 'vertical_post_thumbnail_type' ] = [
    'name' => 'Vertical post thumbnail type',
    'type' => 'radio',
    'options' => [
        'advanced' => 'Rich thumbnail',
        'simple' => 'Image thumbnail',
    ],
    'std' => 'simple',

    'desc' => 'Rich thumbnail includes gallery, video.. for format posts',

    'section' => 'blog_vertical',
    'section_title' => 'Post Vertical',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post vertical thumbnail type',
];

$options[ 'vertical_post_thumbnail_position' ] = [
    'name' => 'Vertical post thumbnail position',
    'type' => 'radio',
    'options' => [
        'left' => 'Left',
        'right' => 'Right',
    ],
    'std' => 'left',
    
    'hint' =>  'Post vertical thumbnail left/right',
];

$options[ 'vertical_post_excerpt_size' ] = [
    'name' => 'Vertical post excerpt size',
    'type' => 'select',
    'options' => [
        '' => 'Default',
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
    ],
    'std' => '',
];

/* Big Post Options
----------------------------------- */
$options[ 'big_post_content_excerpt' ] = [
    'name' => 'Big post show:',
    'type' => 'radio',
    'options' => [
        'content' => 'Content',
        'excerpt' => 'Excerpt',
    ],
    'std' => 'excerpt',

    'section' => 'blog_big',
    'section_title' => 'Post Big',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post big content/excerpt',
];

/* Post Group 1 Options
----------------------------------- */
$options[ 'post_group1_big_post_position' ] = [
    'name' => 'Big Post Position',
    'type' => 'radio',
    'options' => [
        'left' => 'Left',
        'right' => 'Right',
    ],
    'std' => 'left',

    'section' => 'blog_group_1',
    'section_title' => 'Post Group 1',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post group 1 options',
];

$options[ 'post_group1_big_post_ratio' ] = [
    'name' => 'Big Post Ratio',
    'type' => 'radio',
    'options' => [
        '2/3' => '2/3',
        '3/4' => '3/4',
    ],
    'std' => '2/3',
];

$options[ 'post_group1_sep_border' ] = [
    'name' => 'Separator?',
    'shorthand' => 'enable',
    'std' => 'false',
    
    'hint' =>  'Post group 1 border',
];

$options[ 'post_group1_sep_border_color' ] = [
    'name' => 'Separator color',
    'type' => 'color',
];

// BIG POST OPTIONS
$options[] = [
    'name' => 'Big Post',
    'type' => 'heading',
];

$options[ 'post_group1_big_post_components' ] = [
    'name' => 'Big Post Components',
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
    'std' => 'thumbnail,title,date,category,excerpt,excerpt_more',
    
    'hint' =>  'Post group 1: big post options',
];

$options[ 'post_group1_big_post_align' ] = [
    'name' => 'Big Post Align',
    'type' => 'radio',
    'options' => [
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ],
    'std' => 'center',
];

$options[ 'post_group1_big_post_item_template' ] = [
    'name' => 'Big Post Elements Order',
    'type' => 'select',
    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),
    'std' => '2',
];

$options[ 'post_group1_big_post_excerpt_length' ] = [
    'name' => 'Big Post Excerpt Length',
    'type' => 'text',
    'placeholder' => 'Eg. 32',
    'std' => '44',
];

$options[ 'post_group1_big_post_excerpt_more_text' ] = [
    'name' => 'Big Post Excerpt More Text',
    'type' => 'text',
    'placeholder' => 'Eg. Read More',
    'std' => '',
];

$options[ 'post_group1_big_post_excerpt_more_style' ] = [
    'name' => 'Big Post Excerpt More Style',
    'type' => 'select',
    'options' => [
        'simple' => 'Plain Link',
        'simple-btn' => 'Minimal Link', // simple button
        'btn' => 'Fill Button', // default btn
        'btn-black' => 'Solid Black Button',
        'btn-primary' => 'Primary Button',
    ],
    'std' => 'btn',
];

// SMALL POST OPTIONS
$options[] = [
    'name' => 'Small Posts',
    'type' => 'heading',
];

$options[ 'post_group1_small_post_components' ] = [
    'name' => 'Small Posts Components',
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
    'std' => 'thumbnail,title,date,excerpt',
    
    'hint' =>  'Post group 1: small post options',
];

$options[ 'post_group1_small_post_item_template' ] = [
    'name' => 'Small Posts Elements Order',
    'type' => 'select',
    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),
    'std' => '2',
];

$options[ 'post_group1_small_post_list_spacing' ] = [
    'name' => 'Small post list spacing',
    'type' => 'select',
    'options' => [
        'none' => 'No Spacing',
        'tiny' => 'Tiny',
        'small' => 'Small',
        'normal' => 'Normal',
        'medium' => 'Medium',
        'large' => 'Large',
    ],
    'std' => 'normal',
];

/* Post Group 2 Options
----------------------------------- */
$options[ 'post_group2_columns_order' ] = [
    'name' => 'Columns Order',
    'type' => 'select',
    'options' => array(
        '1a-1b-3'  => 'Big / Medium / Small posts',
        '1b-1a-3'  => 'Medium / Big / Small posts',

        '1a-3-1b'  => 'Big / Small posts / Medium',
        '1b-3-1a'  => 'Medium / Small posts / Big',

        '3-1a-1b'  => 'Small posts / Big / Medium',
        '3-1b-1a'  => 'Small posts / Medium / Big',
    ),
    'std' => '1a-3-1b',

    'section' => 'blog_group_2',
    'section_title' => 'Post Group 2',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post group 2 options',
];

$options[ 'post_group2_sep_border' ] = [
    'name' => 'Separator?',
    'shorthand' => 'enable',
    'std' => 'false',
    
    'hint' =>  'Post group 2 border',
];

$options[ 'post_group2_sep_border_color' ] = [
    'name' => 'Separator color',
    'type' => 'color',
];

// BIG POST OPTIONS
$options[] = [
    'name' => 'Big Post',
    'type' => 'heading',
];

$options[ 'post_group2_big_post_components' ] = [
    'name' => 'Big Post Components',
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
    'std' => 'thumbnail,title,date,category,excerpt,excerpt_more',
    
    'hint' =>  'Post group 2: big post options',
];

$options[ 'post_group2_big_post_align' ] = [
    'name' => 'Big Post Align',
    'type' => 'radio',
    'options' => [
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
    ],
    'std' => 'center',
];

$options[ 'post_group2_big_post_item_template' ] = [
    'name' => 'Big Post Elements Order',
    'type' => 'select',
    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),
    'std' => '2',
];

$options[ 'post_group2_big_post_excerpt_length' ] = [
    'name' => 'Big Post Excerpt length',
    'type' => 'text',
    'placeholder' => 'Eg. 32',
    'std' => '32',
];

$options[ 'post_group2_big_post_excerpt_more_text' ] = [
    'name' => 'Big Post Excerpt More Text',
    'type' => 'text',
    'placeholder' => 'Eg. Read More',
    'std' => '',
];

$options[ 'post_group2_big_post_excerpt_more_style' ] = [
    'name' => 'Big Post Excerpt More Style',
    'type' => 'select',
    'options' => [
        'simple' => 'Plain Link',
        'simple-btn' => 'Minimal Link', // simple button
        'btn' => 'Fill Button', // default btn
        'btn-black' => 'Solid Black Button',
        'btn-primary' => 'Primary Button',
    ],
    'std' => 'btn',
];

// MEDIUM POST OPTIONS
$options[] = [
    'name' => 'Medium Post',
    'type' => 'heading',
];

$options[ 'post_group2_medium_post_components' ] = [
    'name' => 'Medium Posts Components',
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
    'std' => 'thumbnail,title,date,excerpt,excerpt_more',
    
    'hint' =>  'Post group 2: medium post options',
];

$options[ 'post_group2_medium_post_item_template' ] = [
    'name' => 'Medium Post Elements Order',
    'type' => 'select',
    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),
    'std' => '2',
];

$options[ 'post_group2_medium_post_thumbnail' ] = [
    'name' => 'Medium Post Thumbnail',
    'type' => 'select',
    'options' => [
        'medium' => 'Medium',
        'thumbnail-medium' => 'Landscape (480x384)',
        'thumbnail-square' => 'Square (480x480)',
        'thumbnail-portrait' => 'Portrait (480x600)',
    ],
    'std' => 'medium',
];

$options[ 'post_group2_medium_post_excerpt_length' ] = [
    'name' => 'Medium Post Excerpt length',
    'type' => 'text',
    'placeholder' => 'Eg. 40',
    'std' => '40',
];

// SMALL POST OPTIONS
$options[] = [
    'name' => 'Small Posts',
    'type' => 'heading',
];

$options[ 'post_group2_small_post_components' ] = [
    'name' => 'Small Posts Components',
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
    'std' => 'thumbnail,title,date',
    
    'hint' =>  'Post group 2: small post options',
];

$options[ 'post_group2_small_post_item_template' ] = [
    'name' => 'Small Posts Elements Order',
    'type' => 'select',
    'options' => array(
        '1' => 'Title > Meta > Excerpt',
        '2' => 'Meta > Title > Excerpt',
        '3' => 'Title > Excerpt > Meta',

        '4' => 'Category > Title > Meta > Excerpt',
        '5' => 'Category > Title > Excerpt > Meta',
    ),
    'std' => '2',
];

$options[ 'post_group2_small_post_excerpt_length' ] = [
    'name' => 'Small Posts Excerpt length',
    'type' => 'text',
    'placeholder' => 'Eg. 12',
    'std' => '12',
];

/* Post Slider Options
----------------------------------- */
$options[ 'post_slider_effect' ] = [
    'name' => 'Post Slider Effect',
    'type' => 'radio',
    'options' => [
        'fade' => 'Fade',
        'slide' => 'Slide',
    ],
    'std' => 'fade',

    'section' => 'blog_slider',
    'section_title' => 'Classic Slider',
    'panel' => 'post_layouts',
    
    'hint' =>  'Post slider options',

];

$options[ 'post_slider_nav_style' ] = [
    'name' => 'Navigation Style',
    'type' => 'radio',
    'options' => [
        'text' => 'Text',
        'arrow' => 'Arrow',
    ],
    'std' => 'text',
    
    'hint' =>  'Post slider navigation type',
];

$options[ 'post_slider_size' ] = [
    'name' => 'Slider size',
    'type' => 'text',
    'placeholder' => '1020x510',
    'std' => '1020x510',
    
    'hint' =>  'Post slider size',
];

$options[ 'post_slider_title_background' ] = [
    'name' => 'Title background?',
    'shorthand' => 'enable',
    'std' => 'false',
    'options' => [
        'true' => 'Yes please!',
        'false' => 'No thanks!',
    ],
];