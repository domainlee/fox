<?php
$fields = array(
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => '',
    ),
    
    array(
        'id' => 'big_number_display',
        'type' => 'select',
        'options' => [
            'all'     => 'World',
            'Europe' => 'Europe',
            'Asia' => 'Asia',
            'Africa' => 'Africa',
            'North America' => 'North America',
            'South America' => 'South America',
            'Oceania' => 'Oceania',
            'country' => 'A specific country',
            'state' => 'A (US) State',
        ],
        'name' => 'Main data displays:',
        'std' => 'all',
    ),
    
    array(
        'id' => 'country',
        'type' => 'text',
        'name' => 'Main data of country',
        'placeholder' => 'Eg. USA or New Zealand',
        'desc' => 'You can use country name. Skip if you don\'t choose country in above option',
    ),
    
    array(
        'id' => 'state',
        'type' => 'text',
        'name' => 'US State',
        'placeholder' => 'Eg. California or New York',
        'desc' => 'You can use state name. Skip if you don\'t choose state in above option',
    ),
    
    array(
        'id' => 'table_display',
        'type' => 'select',
        'name' => 'Table displays:',
        'options' => [
            ''     => 'No table',
            'all' => 'All countries',
            'continents' => 'All Countinents',
            'states' => 'All (US) States',
        ],
        'std' => 'all',
    ),
    
    array(
        'id' => 'cache_time',
        'type' => 'select',
        'name' => 'Cache result in:',
        'options' => [
            '0.25'     => '15 minutes',
            '0.5'     => '30 minutes',
            '1'     => '1 hour',
            '2'     => '2 hours',
            '3'     => '3 hours',
            '4'     => '4 hours',
            '6'     => '6 hours',
            '12'     => '12 hours',
            '24'     => '1 day',
        ],
        'std' => '2',
    ),
    
);