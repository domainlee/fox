<?php
$run_update = isset( $_GET[ 'run_update' ] ) ? $_GET[ 'run_update' ] : '';
if ( 'yes' == $run_update ) {
    
    $back_compat = new Fox_Backcompat();
    $back_compat->run_update();
    
    echo '<div class="message notice notice-success"><p>Thank you! The updater has been run successfully</p></div>';
    
}

$fix_images = isset( $_GET[ 'fix_featured_images' ] ) ? $_GET[ 'fix_featured_images' ] : '';
if ( 'yes' == $fix_images ) {
    
    $query = new WP_Query([
        'posts_per_page' => 10000,
        'fields' => 'ids',
    ]);
    
    if ( $query->have_posts() ) {
        while( $query->have_posts() ) {
            $query->the_post();
            delete_post_meta( get_the_ID(), '_wi_thumbnail' );
        }
    }
    
    wp_reset_query();
    
    echo '<div class="message notice notice-success"><p>Thank you! "Featured Images issue" has been fixed.</p></div>';
    
}

?>

<div class="wrap">

    <h1><span>Updated From Fox 3.x</span></h1>
    
    <p style="color: #b02626;background: #f5dddd; padding: 15px; display: table;">Please clear your server cache if you're using a cache plugin, and clear your browser cache to see new theme in action.</p>

    <p>If you updated to Fox 4.x and have any issue, please click to "Run Updater" to resolve it.<br>
        In case you still have trouble with the new update, please contact us we'll resolve it ASAP.</p>
    
    <p><a href="http://withemes.ticksy.com/" target="_blank">Support Desk</a> | <a href="https://themeforest.net/user/withemes#contact" target="_blank">Profile contact form on ThemeForest</a></p>
    
    <p>(Please provide us your login credentials in a private ticket for getting faster support.<br>
        Both of places are 100% safe & private for proving login info. Any admin info will NEVER be stored or shared.)</p>
    
    <p>
        <a href="<?php echo admin_url( 'admin.php?page=updated-from-fox3&run_update=yes' ); ?>" class="button button-primary">Run Updater</a>
        
    </p>
    
    <p>If you have trouble with Featured Images not showing up, please click to the button below.</p>
    
    <p> 
        <a href="<?php echo admin_url( 'admin.php?page=updated-from-fox3&fix_featured_images=yes' ); ?>" class="button button-primary">Fix Featured Images Not Showing Up</a>
    </p>
    
    <p><strong>(Fox) Post View Counter</strong> is a view count plugin to help you displaying most viewed posts. If you come from Fox v3.0, you can deactivate old <strong>Post View Counter</strong> and install <strong>(Fox) Post View Counter</strong>. All of your post view data stays safe.</p>

</div>