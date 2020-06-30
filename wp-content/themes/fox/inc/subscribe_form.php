<?php
if ( ! function_exists( 'fox_subscribe_form_params' ) ) :
/**
 * Subscribe Form Params
 * This function helps implementing fox button in many places: widget, theme option or page builder
 * @since 4.2
 */
function fox_subscribe_form_params( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        'include' => [],
        'exclude' => [],
        'override' => []
    ] ) );
    
    $params = [];
    
    $forms = array( 'Select Form' => '' );
    $std = '';
    $args = array(
        'post_type' => 'mc4wp-form',
        'posts_per_page' => 100,
        'ignore_sticky_posts' => true,
    );
    $get_forms = get_posts( $args );
    foreach ( $get_forms as $form ) {
        if ( ! $std ) $std = strval($form->ID);
        $forms[ strval($form->ID) ] = $form->post_title;
    }

    if ( ! $forms ) $forms = array( 'Please go to Dashboard > MailChimp for WP > Forms to create at least a form.' => '', );
    
    // FORM
    $params[ 'form' ] = array(
        'type' => 'select',
        'title' => 'Select form',
        'std' => $std,
        'options' => $forms,
        'desc' => 'The premium version allows you to have more than one form.',
        
        'section' => 'form',
        'section_title' => 'Form',
    );
    
    $params[ 'layout' ] = array(
        'type' => 'select',
        'title' => 'Layout',
        'std' => 'inline',
        'options' => [
            'inline' => 'Inline',
            'stack' => 'Stack',
        ],
    );

    $params[ 'align' ] = array(
        'name' => 'Align',
        'type' => 'select',
        'options' => array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
        ),
        'std' => 'center',
    );
    
    // INPUT STYLE
    $params[ 'heading_input_style' ] = array(
        'name' => 'Input Style',
        'type' => 'heading',
        
        'section' => 'input_style',
        'section_title' => 'Input Style',
    );
    
    $params[ 'input_border_width' ] = array(
        'name' => 'Input border width',
        'type' => 'text',
        'placeholder' => 'Eg. 1px',
    );
    
    $params[ 'input_border_radius' ] = array(
        'name' => 'Input border radius',
        'type' => 'text',
        'placeholder' => 'Eg. 3px',
    );
    
    $params[ 'input_text_color' ] = array(
        'name' => 'Input text color',
        'type' => 'color',
    );
    
    $params[ 'input_background' ] = array(
        'name' => 'Input background',
        'type' => 'color',
    );
    
    $params[ 'input_border_color' ] = array(
        'name' => 'Input border color',
        'type' => 'color',
    );
    
    $params[ 'input_focus_text_color' ] = array(
        'name' => 'Input focus text color',
        'type' => 'color',
    );
    
    $params[ 'input_focus_background' ] = array(
        'name' => 'Input focus background',
        'type' => 'color',
    );
    
    $params[ 'input_focus_border_color' ] = array(
        'name' => 'Input focus border color',
        'type' => 'color',
    );
    
    $params[ 'input_text_size' ] = array(
        'name' => 'Input text size',
        'type' => 'text',
        'placeholder' => 'Eg. 12px',
    );
    
    // BUTTON STYLE
    $params[ 'heading_button_style' ] = array(
        'name' => 'Button Style',
        'type' => 'heading',
        
        'section' => 'button_style',
        'section_title' => 'Button Style',
    );
    
    $params[ 'button_color' ] = array(
        'name' => 'Button text color',
        'type' => 'color',
    );
    
    $params[ 'button_background' ] = array(
        'name' => 'Button background',
        'type' => 'color',
    );
    
    $params[ 'button_hover_color' ] = array(
        'name' => 'Button hover text color',
        'type' => 'color',
    );
    
    $params[ 'button_hover_background' ] = array(
        'name' => 'Button hover background',
        'type' => 'color',
    );
    
    $params[ 'button_text_size' ] = array(
        'name' => 'Button text size',
        'type' => 'text',
        'placeholder' => 'Eg. 12px',
    );
    
    $params[ 'button_border_radius' ] = array(
        'name' => 'Button border radius',
        'type' => 'text',
        'placeholder' => 'Eg. 3px',
    );
    
    // only include
    if ( ! empty( $include ) ) {
        foreach ( $params as $id => $param ) {
            if ( ! in_array( $id, $include ) ) unset( $params[ $id ] );
        }
    }
    
    // exclude
    if ( ! empty( $exclude ) ) {
        foreach ( $params as $id => $param ) {
            if ( in_array( $id, $exclude ) ) unset( $params[ $id ] );
        }
    }
    
    // override
    if ( ! empty( $override ) ) {
        foreach ( $override as $id => $param ) {
            $params[ $id ] = $param;
        }
    }
    
    // name vs title
    // and id
    foreach ( $params as $id => $param ) {
        
        // to use in widget / metabox
        $param[ 'id' ] = $id;
        
        // name vs title
        if ( isset( $param[ 'title' ] ) ) $param[ 'name' ] = $param[ 'title' ];
        elseif ( isset( $param[ 'name' ] ) ) $param[ 'title' ] = $param[ 'name' ];
        
        $params[ $id ] = $param;
        
    }
    
    return apply_filters( 'fox_btn_params', $params );
    
}
endif;

if ( ! function_exists( 'fox_subscribe_form' ) ) :
/**
 * Fox Subscribe Form
 * @since 4.2
 */
function fox_subscribe_form( $args = [] ) {
    
    extract( wp_parse_args( $args, [
        
        // form
        'form' => '',
        'layout' => 'inline',
        'align' => 'center',
        
        // input style
        'input_border_width' => '',
        'input_border_radius' => '',
        'input_text_color' => '',
        'input_background' => '',
        'input_border_color' => '',
        'input_focus_text_color' => '',
        'input_focus_background' => '',
        'input_focus_border_color' => '',
        
        // button style
        'button_border_radius' => '',
        'button_color' => '',
        'button_background' => '',
        'button_hover_color' => '',
        'button_hover_background' => '',
        
        'extra_class' => '',
    ] ) );
    
    // form ID is mandatory
    if ( ! $form ) return;
    
    // class
    $class = [ 'fox-form', 'fox-subscribe-form', 'fox-mailchimp-form' ];
    $class[] = $extra_class;
    
    // layout
    if ( 'stack' != $layout ) $layout = 'inline';
    $class[] = 'form-' . $layout;
    
    // align
    if ( 'left' != $align && 'right' != $align ) $align = 'center';
    $class[] = 'align-' . $align;
    
    $id = uniqid( 'form-' );
    
    // CSS
    $css_all = [];
    $css = [];
    
    $inputs = 'input[type="text"], input[type="email"], input[type="number"], input[type="url"], input:not([type])';
    $buttons = 'input[type="button"], input[type="submit"], button';
    $inputs = explode( ',', $inputs );
    $buttons = explode( ',', $buttons );
    
    $input_selector = [];
    $input_focus_selector = [];
    $button_selector = [];
    $button_hover_selector = [];
    
    foreach ( $inputs as $input ) {
        $input_selector[] = '#' . $id . ' ' . $input;
        $input_focus_selector[] = '#'. $id . ' ' . $input . ':focus';
    }
    $input_selector = join( ',', $input_selector );
    $input_focus_selector = join( ',', $input_focus_selector );
    
    foreach ( $buttons as $button ) {
        $button_selector[] = '#' . $id . ' ' . $button;
        $button_hover_selector[] = '#' . $id . ' ' . $button . ':hover';
    }
    $button_selector = join( ',', $button_selector );
    $button_hover_selector = join( ',', $button_hover_selector );
    
    /**
     * input style
     */
    if ( '' != $input_border_width ) {
        if ( is_numeric( $input_border_width ) ) $input_border_width .= 'px';
        $css[] = 'border-width:' . $input_border_width;
    }
    if ( '' != $input_border_radius ) {
        if ( is_numeric( $input_border_radius ) ) $input_border_radius .= 'px';
        $css[] = 'border-radius:' . $input_border_radius;
    }
    if ( '' != $input_text_size ) {
        if ( is_numeric( $input_text_size ) ) $input_text_size .= 'px';
        $css[] = 'font-size:' . $input_text_size;
    }
    if ( '' != $input_text_color ) {
        $css[] = 'color:' . $input_text_color;
    }
    if ( '' != $input_background ) {
        $css[] = 'background:' . $input_background;
    }
    if ( '' != $input_border_color ) {
        $css[] = 'border-color:' . $input_border_color;
    }
    
    if ( ! empty( $css ) ) {
        $css_all[] = $input_selector . '{' . join( ';', $css ). '}';
    }
    
    /**
     * input focus
     */
    $css = [];
    if ( '' != $input_focus_text_color ) {
        $css[] = 'color:' . $input_focus_text_color;
    }
    if ( '' != $input_focus_background ) {
        $css[] = 'background:' . $input_focus_background;
    }
    if ( '' != $input_focus_border_color ) {
        $css[] = 'border-color:' . $input_focus_border_color;
    }
    
    if ( ! empty( $css ) ) {
        $css_all[] = $input_focus_selector . '{' . join( ';', $css ). '}';
    }
    
    /**
     * button style
     */
    $css = [];
    if ( '' != $button_border_radius ) {
        if ( is_numeric( $button_border_radius ) ) $button_border_radius .= 'px';
        $css[] = 'border-radius:' . $button_border_radius;
    }
    if ( '' != $button_text_size ) {
        if ( is_numeric( $button_text_size ) ) $button_text_size .= 'px';
        $css[] = 'font-size:' . $button_text_size;
    }
    if ( '' != $button_color ) {
        $css[] = 'color:' . $button_color;
    }
    if ( '' != $button_background ) {
        $css[] = 'background:' . $button_background;
    }
    if ( ! empty( $css ) ) {
        $css_all[] = $button_selector . '{' . join( ';', $css ). '}';
    }
    
    /**
     * button hover style
     */
    $css = [];
    if ( '' != $button_hover_color ) {
        $css[] = 'color:' . $button_hover_color;
    }
    if ( '' != $button_hover_background ) {
        $css[] = 'background:' . $button_hover_background;
    }
    if ( ! empty( $css ) ) {
        $css_all[] = $button_hover_selector . '{' . join( ';', $css ). '}';
    }
    
    ?>

<?php if ( ! empty( $css_all ) ) { ?>
<style>
    <?php echo join( '', $css_all ); ?>
</style>
<?php } ?>

<div class="<?php echo esc_attr( join( ' ', $class ) ); ?>" id="<?php echo esc_attr( $id ); ?>">
    
    <div class="form-inner">
    
        <?php echo do_shortcode( '[mc4wp_form id="' . esc_attr( $form ) . '"]' ); ?>
        
    </div><!-- .form-inner -->
    
</div><!-- .fox-form -->

    <?php
}
endif;