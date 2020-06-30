<?php
// mechanism to disable it, since 4.0
if ( ! apply_filters( 'fox_show_titlebar', true ) ) return;

$current = fox_current();

extract( wp_parse_args( $current, [
    'title' => '',
    'subtitle' => '',
    'label' => '',
    'page' => '',
] ) );

$class = [
    'headline',
    'wi-titlebar',
    'post-header',
];

$align = get_theme_mod( 'wi_titlebar_align', 'center' );
if ( $align ) {
    $class[] = 'align-' . $align;
}

if ( ! $title ) return;

/**
 * Background, cover
 */
$bg_img = '';
$bg_html = '';
if ( is_category() || is_tag() ) {
    $bg_id = get_term_meta( get_queried_object_id(), '_wi_background_image', true );
    if ( $bg_id ) {
        $bg_img = wp_get_attachment_image( $bg_id, 'full' );
    }
} elseif ( is_author() ) {
    $blog_id = get_current_blog_id();
    $field_id = '_wi_' . $blog_id . '_background';
    
    global $author;
    $userdata = get_userdata( $author );
    
    $bg_id = get_user_meta( $userdata->ID, $field_id, true );
    if ( $bg_id ) {
        $bg_img = wp_get_attachment_image( $bg_id, 'full' );
    }
}
if ( $bg_img != '' ) {
    $class[] = 'has-cover';
    $bg_html .= '<div class="titlebar-bg">' . $bg_img . '<div class="titlebar-bg-overlay"></div></div>';
}
?>

<div id="titlebar" class="<?php echo esc_attr( join( ' ', $class ) ); ?>">
    
    <div class="container">
        
        <div class="title-area">
            
            <?php if ( is_author() ) : 
            $social_style = get_theme_mod( 'wi_titlebar_user_social_style', 'plain' );

            fox_user([
                'extra_class' => 'titlebar-user', 
                'social_style' => $social_style,
                'author_page' => true,
            ]); else : ?>
            
            <?php if ( $label && ( 'true' == get_theme_mod( 'wi_archive_label', 'false' ) ) ) : ?>
            
            <span class="title-label">
                
                <span><?php echo $label; ?></span>
                
            </span><!-- .title-label -->
            
            <?php endif; ?>
            
            <h1 class="archive-title" itemprop="headline">
                
                <span>
                    
                    <?php echo $title; ?>
                    
                </span>
            
            </h1><!-- .archive-title -->
            
            <?php if ( $subtitle && ( 'true' == get_theme_mod( 'wi_archive_description', 'true' ) ) ) : ?>
            
            <div class="page-subtitle archive-description">
                
                <?php echo wpautop( $subtitle ); ?>
                
            </div><!-- .page-subtitle -->
            
            <?php endif; ?>
            
            <?php if ( is_category() && ( 'true' == get_theme_mod( 'wi_titlebar_subcategories', 'true' ) ) ) : // since 4.0

            $cat = get_queried_object();
            $list = wp_list_categories([
                'echo' => false,
                'child_of' => $cat->term_id,
                'hide_empty' => false,
                'hide_title_if_empty' => true,
                'hierarchical' => false,
                'orderby' => 'name',
                'order' => 'asc',
                'separator' => '',
                'style' => 'list',
                'title_li' => '',
                'show_option_none' => '',
            ]);

            if ( $list ) :
            ?>
            
            <div class="fox-term-list">
                
                <ul>
                    
                    <?php echo $list; ?>
                    
                </ul>
                
            </div><!-- .fox-term-list -->
            
            <?php endif; // list ?>
            
            <?php endif; // titlebar_subcategories ?>
            
            <?php endif; // user or not ?>
            
        </div><!-- .title-area -->
        
    </div><!-- .container -->
    
    <?php echo $bg_html; ?>
    
</div><!-- #titlebar -->