<?php
$fields = [];
$fields[] = [
    'id' => 'title',
    'type' => 'text',
    'name' => 'Title',
];

$fields[] = [
    'id' => 'style',
    'type' => 'select',
    'options' => [
        '1' => 'Style 1',
        '2' => 'Style 2',
        '3' => 'Style 3',
    ],
    'std' => '1',
    'name' => 'Style',
];

$fields[] = [
    'id' => 'image',
    'type' => 'image',
    'name' => 'Upload image',
];

$fields[] = [
    'id' => 'name',
    'type' => 'text',
    'name' => 'Imagebox name',
    'placeholder' => 'Eg. About Me',
];

$fields[] = [
    'id' => 'url',
    'type' => 'text',
    'name' => 'URL',
    'placeholder' => 'https://',
];

$fields[] = [
    'id' => 'target',
    'type' => 'select',
    'name' => 'Open URL in',
    'options' => [
        '_self' => 'Current tab',
        '_blank' => 'New tab',
    ],
    'std' => '_self',
];

$fields[] = [
    'id' => 'ratio',
    'type' => 'text',
    'name' => 'Ratio (W:H)',
    'placeholder' => 'Eg. 2:1',
    'desc' => 'Enter ratio in syntax "W : H", eg. 2:1',
];

$fields[] = [
    'id' => 'overlay',
    'type' => 'color',
    'name' => 'Overlay color',
];

$fields[] = [
    'id' => 'overlay_opacity',
    'type' => 'text',
    'name' => 'Overlay opacity',
    'desc' => 'Enter value between 0 - 1, eg. 0.7',
    'placeholder' => 'Eg. 0.5',
];