<?php
$fields = array(
    
    array(
        'id' => 'deprecated_notice',
        'type' => 'html',
        'std' => '<div class="fox-notice fox-error"><strong>Deprecated Widget</strong>: This widget will continue to work but you should use native "<strong>Video widget</strong>" of WordPress</div>',
    ),
    
    array(
        'id' => 'title',
        'type' => 'text',
        'name' => esc_html__( 'Title', 'wi' ),
        'std' => 'Video/Audio',
    ),
    
    array(
        'id' => 'code',
        'type' => 'textarea',
        'name' => esc_html__( 'Video / SoundCloud URL', 'wi' ),
        'desc' => 'Insert Youtube URL, Vimeo URL or SoundCloud URL (NOT iframe or ID, but <strong>URL</strong>)',
    ),
    
);