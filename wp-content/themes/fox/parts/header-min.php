<?php
/**
 * Displays a minimal header with only hamburger + logo
 * to focus all in readability
 * since 4.0
 */

$class = [
    'minimal-header',
    'top-mode'
];

?>
<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" id="minimal-header">
    
    <div class="minimal-header-inner">
        
        <?php fox_hamburger_btn(); ?>
        <?php fox_min_logo(); ?>
        
    </div><!-- .minimal-header-inner -->

</div><!-- #minimal-header -->