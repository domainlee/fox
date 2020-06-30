<?php
/**
 * Use HTML to render as image
 * @since 4.0
 */
function fox_html_image( $id = '' ) {
    
    ob_start();
    
    switch ( $id ) : 
    
    /* ------------------------       DEFAULT           ------------------------ */
    case 'default' : ?>

<div class="html-item">
        
    <div style="display:flex; align-items: center; height: 100%;">
        
        <div style="text-align:center; width: 100%;">
        
            <span class="html-text">default</span>
            
        </div>
        
    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       SIDEBAR RIGHT           ------------------------ */
    case 'sidebar_right' : ?>

<div class="html-item">
    
    <div class="html-header">
        <div class="html-logo"></div>
        <div class="html-nav"></div>
    </div>
        
    <div class="html-content">
        
        <div class="html-primary">

            <div class="html-text">

                <div class="html-title"></div>

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       SIDEBAR LEFT           ------------------------ */
    case 'sidebar_left' : ?>

<div class="html-item">
    
    <div class="html-header">
        <div class="html-logo"></div>
        <div class="html-nav"></div>
    </div>
        
    <div class="html-content sidebar-left">
        
        <div class="html-primary">

            <div class="html-text">

                <div class="html-title"></div>

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       NO SIDEBAR           ------------------------ */
    case 'no_sidebar' : ?>

<div class="html-item">
    
    <div class="html-header">
        <div class="html-logo"></div>
        <div class="html-nav"></div>
    </div>
        
    <div class="html-content">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-title"></div>

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       STRETCH NONE           ------------------------ */
    case 'stretch_none' : ?>

<div class="html-item" style="padding:10px 20px">
    
    <div class="html-image"></div>
    
    <div class="html-content">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       STRETCH BIGGER           ------------------------ */
    case 'stretch_bigger' : ?>

<div class="html-item" style="padding:10px 20px">
    
    <div class="html-image" style="margin:0 -13px; width:calc(100% + 26px)"></div>
    
    <div class="html-content">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       STRETCH FULL           ------------------------ */
    case 'stretch_full' : ?>

<div class="html-item" style="padding:10px 0">
    
    <div class="html-image"></div>
    
    <div style="padding:0 20px">
        
        <div class="html-content">

            <div class="html-primary" style="width:100%;">

                <div class="html-text">

                    <div class="html-line"></div>
                    <div class="html-line"></div>
                    <div class="html-line"></div>
                    <div class="html-line"></div>

                </div>

            </div>

        </div><!-- .html-content -->
        
    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       CONTENT STRETCH NONE           ------------------------ */
    case 'content_stretch_none' : ?>

<div class="html-item" style="padding:10px 20px">
    
    <div class="html-content" style="padding-top:0">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->
    
    <div style="height:5px"></div>
    
    <div class="html-image"></div>
    
    <div class="html-content">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       CONTENT STRETCH BIGGER           ------------------------ */
    case 'content_stretch_bigger' : ?>

<div class="html-item" style="padding:10px 20px">
    
    <div class="html-content" style="padding-top:0">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->
    
    <div style="height:5px"></div>
    
    <div class="html-image" style="margin:0 -10px; width:calc(100% + 20px)"></div>
    
    <div class="html-content">
        
        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       CONTENT STRETCH FULL           ------------------------ */
    case 'content_stretch_full' : ?>

<div class="html-item" style="padding:10px 0">
    
    <div style="padding:0 20px">
        
        <div class="html-content" style="padding-top:0">

            <div class="html-primary" style="width:100%;">

                <div class="html-text">

                    <div class="html-line"></div>
                    <div class="html-line"></div>
                    <div class="html-line"></div>

                </div>

            </div>

        </div><!-- .html-content -->
        
    </div>
    
    <div style="height:5px"></div>
    
    <div class="html-image"></div>
    
    <div style="padding:0 20px">
        
        <div class="html-content">

            <div class="html-primary" style="width:100%;">

                <div class="html-text">

                    <div class="html-line"></div>
                    <div class="html-line"></div>
                    <div class="html-line"></div>

                </div>

            </div>

        </div><!-- .html-content -->
        
    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       CONTENT FULL           ------------------------ */
    case 'content_full' : ?>

<div class="html-item" style="padding:10px 12px">
    
    <div class="html-image"></div>
    
    <div class="html-content">

        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>

            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       CONTENT NARROW           ------------------------ */
    case 'content_narrow' : ?>

<div class="html-item" style="padding:10px 12px">
    
    <div class="html-image"></div>
    
    <div class="html-content" style="width:32px">

        <div class="html-primary" style="width:100%;">

            <div class="html-text">

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>

            </div>

        </div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>
    
<?php /* ------------------------       LAYOUT  1           ------------------------ */
    case 'layout_1' : ?>

<div class="html-item">
    
    <div class="html-content">

        <div class="html-primary">

            <div class="html-image"></div>

            <div class="html-text" style="padding-top:6px">

                <div class="html-title"></div>

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       LAYOUT  1B           ------------------------ */
    case 'layout_1b' : ?>

<div class="html-item">
    
    <div class="html-content">

        <div class="html-primary">

            <div class="html-text">

                <div class="html-title"></div>
                
                <div class="html-image" style="margin-bottom:6px"></div>

                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                
            </div>
        </div>

        <div class="html-sidebar"></div>

    </div><!-- .html-content -->

</div><!-- .item -->

<?php break; // end ?>
    
<?php /* ------------------------       LAYOUT  2           ------------------------ */
    case 'layout_2' : ?>

<div class="html-item" style="padding:8px 10px">
        
    <div class="html-text">

        <div class="html-title"></div>

    </div>

    <div class="html-image"></div>

    <div class="html-content">

        <div class="html-primary">

            <div class="html-text">
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       LAYOUT  3           ------------------------ */
    case 'layout_3' : ?>

<div class="html-item">
        
    <div class="html-header">
        <div class="html-logo"></div>
        <div class="html-nav"></div>
    </div>

    <div class="html-content" style="padding:0">

        <div class="html-image"></div>

    </div>

    <div class="html-content">

        <div class="html-primary">

            <div class="html-text">
                <div class="html-title"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       LAYOUT  4           ------------------------ */
    case 'layout_4' : ?>

<div class="html-item">
        
    <div class="html-image" style="height:40px"></div>

    <div class="html-content">

        <div class="html-primary">

            <div class="html-text">
                <div class="html-title"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       LAYOUT  5           ------------------------ */
    case 'layout_5' : ?>

<div class="html-item">
        
    <div style="display:flex; align-items: center; height: 40px; border-bottom: 1px solid #ccc;">

        <div class="html-text" style="width:50%;">

            <div style="padding:10px">

                <div class="html-title"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>

            </div>

        </div>

        <div class="html-image" style="width: 50%; height:100%;">

        </div>

    </div>

    <div class="html-content">

        <div class="html-primary">

            <div class="html-text">
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
                <div class="html-line"></div>
            </div>

        </div>

        <div class="html-sidebar"></div>

    </div>

</div><!-- .item -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY GRID           ------------------------ */
    case 'gallery_grid' : ?>

<div class="html-gallery html-gallery-grid">
    
    <?php for ( $i = 1; $i <= 6; $i++ ) { ?>
    
    <div class="html-gallery-item">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <?php } ?>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY MASONRY           ------------------------ */
    case 'gallery_masonry' : ?>

<div class="html-gallery html-gallery-masonry">
    
    <?php for ( $i = 1; $i <= 6; $i++ ) {
    $padding_style = '';
        if ( 5 == $i ) $padding_style = ' style="padding-bottom:150%;"';
        elseif ( 2 == $i ) $padding_style = ' style="padding-bottom:60%;"';
        elseif ( 4 == $i ) $padding_style = ' style="padding-bottom:125%;"';
        elseif ( 6 == $i ) $padding_style = ' style="padding-bottom:60%;"';
    ?>
    
    <div class="html-gallery-item"<?php if ( $i == 5 ) echo ' style="transform:translate(0, -7px)"'; ?>>
        <div class="html-gallery-item-main"<?php echo $padding_style; ?>></div>
    </div>
    
    <?php } ?>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY METRO           ------------------------ */
    case 'gallery_metro' : ?>

<div class="html-gallery html-gallery-metro">
    
    <?php for ( $i = 1; $i <= 6; $i++ ) { ?>
    
    <div class="html-gallery-item">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <?php } ?>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY STACK           ------------------------ */
    case 'gallery_stack' : ?>

<div class="html-gallery html-gallery-stack">
    
    <?php for ( $i = 1; $i <= 3; $i++ ) { ?>
    
    <div class="html-gallery-item">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <?php } ?>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY SLIDER           ------------------------ */
    case 'gallery_slider' : ?>

<div class="html-gallery html-gallery-slider">
    
    <div class="html-gallery-item">
        <div class="html-gallery-item-main"></div>
        
        <span class="html-slider-nav html-slider-nav-prev">
            <i class="dashicons dashicons-arrow-left-alt2"></i>
        </span>
        
        <span class="html-slider-nav html-slider-nav-next">
            <i class="dashicons dashicons-arrow-right-alt2"></i>
        </span>
        
    </div>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       GALLERY CAROUSEL           ------------------------ */
    case 'gallery_carousel' : ?>

<div class="html-gallery html-gallery-carousel">
    
    <div class="html-gallery-item" style="width:10px">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <div class="html-gallery-item" style="width:20px">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <div class="html-gallery-item" style="width:40px">
        <div class="html-gallery-item-main"></div>
    </div>
    
    <div class="html-gallery-item" style="width:15px">
        <div class="html-gallery-item-main"></div>
    </div>

</div><!-- .html-gallery -->

<?php break; // end ?>

<?php /* ------------------------       SLIDER RICH           ------------------------ */
    case 'gallery_slider_rich' : ?>

<div class="html-gallery html-gallery-slider html-gallery-slider-rich">
    
    <div class="html-gallery-item">
        
        <div class="html-gallery-item-main">
            <div class="html-gallery-item-image"></div>
            <div class="html-gallery-item-text">
                
                <div class="html-gallery-item-title"></div>
                <div class="html-gallery-item-line"></div>
                <div class="html-gallery-item-line"></div>
                <div class="html-gallery-item-line"></div>
                
            </div>
        </div>
        
        <span class="html-slider-nav html-slider-nav-prev">
            <i class="dashicons dashicons-arrow-left-alt2"></i>
        </span>
        
        <span class="html-slider-nav html-slider-nav-next">
            <i class="dashicons dashicons-arrow-right-alt2"></i>
        </span>
        
    </div>

</div><!-- .html-gallery -->

<?php break; // end ?>
    
    <?php 
    
    default: 
    
    break;
    
    endswitch;
    
    return ob_get_clean();
    
}