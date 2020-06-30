<?php
$cat_arr = array( '' => 'All' );
$categories = get_categories();
foreach ( $categories as $cat ) {
    $cat_arr[ strval( $cat->term_id ) ] = $cat->name;
}

$fields = array(
    
    array(
        'id' => 'deprecated_notice',
        'type' => 'html',
        'std' => '<div class="fox-notice fox-error"><strong>Deprecated Widget</strong>: This widget will continue to work but you should use "<strong>(FOX) Post List</strong>" widget. It also has option for displaying highest review score posts, and much more options.</div>',
    ),
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'Best Rated',
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
        'name' => esc_html__( 'Tag', 'wi' ),
        'type' => 'text',
        'desc' => 'Enter tag IDs, separated by comma. You can find tag ID in your browser address bar when you edit a tag.',
    ),
    
    // since 2.8
    array(
        'id' => 'orderby',
        'name' => esc_html__( 'Orderby', 'wi' ),
        'type' => 'select',
        'options' => array(
            'score' => 'Highest Score',
            'date' => 'Recent Review',
        ),
    ),
    
);