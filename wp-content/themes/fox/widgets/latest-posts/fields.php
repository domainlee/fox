<?php
$cat_arr = array( '' => 'All' );
$author_arr = array( '' => 'All' );
$categories = get_categories();
foreach ( $categories as $cat ) {
    $cat_arr[ strval( $cat->term_id ) ] = $cat->name;
}

$args = array(
    'number' => 100,
    'has_published_posts' => true,
    'orderby' => 'name',
    'order' => 'asc',
);

$authors = get_users( $args );
foreach ( $authors as $user ) {
    $author_arr[ strval( $user->ID ) ] = $user->display_name;
}

$fields = array(
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'Latest Posts',
    ),
    
    array(
        'id' => 'number',
        'name' => esc_html__( 'Number of posts to show', 'wi' ),
        'std'   => 4,
        'type' => 'text',
    ),
    
    array(
        'id' => 'category',
        'name' => esc_html__( 'Category', 'wi' ),
        'type' => 'select',
        'options' => $cat_arr,
    ),
    
    array(
        'id' => 'tag',
        'name' => 'Only from tags:',
        'type' => 'text',
        'placeholder' => 'Eg. 25, 342',
        'desc' => 'Enter tag IDs, separated by comma. You can find tag ID in your browser address bar when you edit a tag.',
    ),
    
    array(
        'id' => 'author',
        'name' => 'Author',
        'type' => 'select',
        'options' => $author_arr,
    ),
    
    array(
        'id' => 'orderby',
        'name' => 'Orderby',
        'type' => 'select',
        'options' => fox_orderby_support(),
        'std' => 'date',
    ),
    
    array(
        'id' => 'order',
        'name' => 'Order',
        'type' => 'select',
        'options' => fox_order_support(),
        'std' => 'desc',
    ),
    
    array(
        'id' => 'show_excerpt',
        'name' => 'Show Excerpt',
        'type' => 'checkbox',
    ),
    
    array(
        'id' => 'show_date',
        'name' => 'Show Date',
        'type' => 'checkbox',
        'std' => true,
    ),
    
    array(
        'id' => 'layout',
        'name' => esc_html__( 'Layout', 'wi' ),
        'type' => 'select',
        'options' => array(
            'small' => 'Small Image',
            'big' => 'Big Image',
        ),
        'std' => 'small',
    ),
    
    array(
        'id' => 'thumbnail_show',
        'name' => 'Show thumbnail?',
        'desc' => 'Show thumbnail',
        'type' => 'checkbox',
        'std' => true,
    ),
    
    array(
        'id' => 'thumbnail_align',
        'name' => 'Thumbnail Align',
        'type' => 'select',
        'options' => [
            'left'  => 'Left',
            'right' => 'Right',
        ],
        'desc' => 'Option for small image layout',
        'std'   => 'left',
    ),
    
    array(
        'id' => 'thumbnail',
        'name' => 'Thumbnail',
        'type' => 'select',
        'options' => [
            'thumbnail'  => 'Thumbnail 150x150',
            'medium' => 'Medium',
            'landscape'  => 'Landscape 480x384',
            'square'  => 'Square 480x480',
            'portrait'  => 'Portrait 480x600',
            'thumbnail-large'  => 'Wide 720x480',
            'large'  => 'Large (original ratio)',
        ],
        'std'   => 'landscape',
    ),
    
    array(
        'id' => 'index',
        'name' => 'Show Index on thumbnail?',
        'desc' => 'Option for big image layout',
        'type' => 'checkbox',
    ),
    
    array(
        'id' => 'view',
        'name' => 'Show view count on thumbnail?',
        'desc' => 'Option for big image layout',
        'type' => 'checkbox',
    ),
    
);