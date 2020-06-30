<?php
/**
 * Custom Control Class
 *
 * This class doesn't dirty behind stuffs for other custom classes
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Customize_Control' ) ) :

class Fox_Customize_Control extends WP_Customize_Control
{
    
    /**
     * Compress to reduce size
     */
    protected function render() {
        $id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
        $class = 'customize-control fox-customize-control customize-control-' . $this->type;

        ?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php $this->render_content(); ?></li><?php
    }
    
    /*
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {}
    
    /**
     * Function to extend
     */
    public function js_content() {}
    
    /*
     * Render the content on the theme customizer page
     */
    public function content_template()
    {
        ?>
        <# if ( data.label ) { #>
        <label>    
            <span class="customize-control-title">{{{ data.label }}}</span>
        </label>
        <# } #>

        <?php $this->js_content(); ?>
            
        <# if ( data.description ) { #>
            <span class="description">{{{ data.description }}}</span>
        <# } #>
        <?php
    }
    
}

endif;

/**
 * Hidden Control
 *
 * @since 4.4
 */
if ( !class_exists( 'Fox_Hidden_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Hidden_Control' );

class Fox_Hidden_Control extends Fox_Customize_Control
{
    
    public $type = 'hidden';
    
    public function js_content() { ?>
                
        <input type="hidden" data-customize-setting-link="{{ data.settings.default }}" />
        
    <?php }
    
}

endif;    

/**
 * Textarea Control
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Textarea_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Textarea_Control' );

class Fox_Textarea_Control extends Fox_Customize_Control
{
    
    public $type = 'fox_textarea';
    
    public function js_content() { ?>
                
        <textarea rows="5" data-customize-setting-link="{{ data.settings.default }}" placeholder="{{ data.placeholder }}"></textarea>
        
    <?php }
    
}

endif;

/**
 * Background Control
 * @since 4.0
 */
if ( ! class_exists( 'Fox_Background_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Background_Control' );

class Fox_Background_Control extends Fox_Customize_Control {
    
    public $type = 'fox_background';
    
    public function js_content() {
        
        ?>
            
            <div class="fox-background-control">
            
                <div style="fox-control-row">
                    
                    <div class="fox-control-col col-1-1">
                    
                        <span class="fox-control-label">Background Color</span>
                        <input type="text" class="background-input-color" data-prop="background-color" />
                    
                    </div>
                
                </div>
                
                <div class="fox-control-row">
                
                    <div class="fox-control-col col-1-1">
                    
                        <div class="wi-upload-wrapper">
                            
                            <span class="fox-control-label">Background Image</span>
                            <input type="hidden" class="background-input-image media-result" data-prop="background-image" />
                            
                            <div class="image-holder">
                            
                                <a href="#" rel="nofollow" class="remove-image-button" title="<?php esc_html_e( 'Remove Image', 'wi' );?>">&times;</a>
                                
                            </div><!-- .image-holder -->

                            <div class="attachment-media-view">
                                
                                <button type="button" class="button button-primary upload-image-button">Select image</button>
                                
                            </div><!-- .attachment-media-view -->
                            
                        </div><!-- .image-wrapper -->
                        
                    </div>
                
                </div>
                
                <span class="fox-control-label">Background Size</span>
                
                <div class="fox-control-row">
                    
                    <div class="fox-control-col col-1-2">
                        
                        <select data-prop="background-size">
                            
                            <option value="cover">Cover</option>
                            <option value="contain">Contain</option>
                            <option value="auto">Auto</option>
                            <option value="custom">Custom</option>
                            
                        </select>
                        
                    </div>
                    
                    <div class="fox-control-col col-1-2">
                        
                        <input type="text" placeholder="Custom Size" data-prop="background-size-custom" />
                        
                    </div>
                    
                </div>
                
                <div class="fox-control-row">
                    
                    <div class="fox-control-col col-1-3">
                        
                        <span class="fox-control-label">Position</span>
                        
                        <select data-prop="background-position">
                            
                            <?php $props = fox_background_position(); foreach ( $props as $key => $name ) { ?>
                            
                            <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                            
                            <?php } ?>
                            
                        </select>
                        
                    </div>
                    
                    <div class="fox-control-col col-1-3">
                        
                        <span class="fox-control-label">Repeat</span>
                        
                        <select data-prop="background-repeat">
                            
                            <?php $props = fox_background_repeat(); foreach ( $props as $key => $name ) { ?>
                            
                            <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                            
                            <?php } ?>
                            
                        </select>
                        
                    </div>
                    
                    <div class="fox-control-col col-1-3">
                        
                        <span class="fox-control-label">Attachment</span>
                        
                        <select data-prop="background-attachment">
                            
                            <?php $props = fox_background_attachment(); foreach ( $props as $key => $name ) { ?>
                            
                            <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                            
                            <?php } ?>
                            
                        </select>
                        
                    </div>
                    
                </div>
            
            </div><!-- .fox-background-control -->
            
            <?php
    }
    
}
endif;

/**
 * Multiple Text Control
 * @since 4.0
 */
if ( !class_exists( 'Fox_Multiple_Text_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Multiple_Text_Control' );

class Fox_Multiple_Text_Control extends Fox_Customize_Control {
    
    public $type = 'multiple_text';
    
    public function js_content() { ?>
        
            <div class="fox-multiple-text-control">
                
                <# _.each( data.fields, function( name, id ) { #>
                    
                    <div class="fox-text-row">
                        
                        <span class="text-row-label">{{{ name }}}</span>
                        
                        <div class="text-row-input">
                            
                            <input type="text" data-id="{{ id }}" />
                            
                        </div><!-- .text-row-input -->
                        
                    </div><!-- .fox-text-row -->
                    
                <# }); #>
            
            </div><!-- .fox-multiple-text-control -->
        
    <?php }
    
}
endif;

/**
 * Box Control
 * @since 4.0
 */
if ( !class_exists( 'Fox_Box_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Box_Control' );

class Fox_Box_Control extends Fox_Customize_Control {
    
    public $type = 'box';
    
    public function js_content() {
        
        $pos = [
            'top', 'right', 'bottom', 'left'
        ];
        $radius_pos = [
            'top-left' => '10h',
            'top-right' => '2h',
            'bottom-right' => '5h',
            'bottom-left' => '8h',
        ];
        ?>
            
            <# var has_tabs = ( data.fields.indexOf( 'margin' ) > -1 ) || ( data.fields.indexOf( 'padding' ) > -1 ); #>
        
        <div class="fox-box-control-wrapper">
            
            <# if ( has_tabs ) { #>
            <div class="fox-box-control-tabs">
                <ul>
                    <li title="Desktop" data-tab="desktop" class="active"><i class="dashicons dashicons-desktop"></i></li>
                    <li title="Tablet" data-tab="tablet"><i class="dashicons dashicons-tablet"></i></li>
                    <li title="Mobile" data-tab="phone"><i class="dashicons dashicons-smartphone"></i></li>
                </ul>
            </div>
            <# } #>
                
        <# var screens = has_tabs ? [ 'desktop', 'tablet', 'phone' ] : [ 'desktop' ]; #>
        
        <# _.each( screens, function ( screen ) {
           var active = ( 'desktop' == screen ) ? 'active' : '';
           var prefix = ( 'desktop' == screen ) ? '' : ( screen + '-' );
           #>
            
        <div class="fox-box-control {{ active }}" data-tab="{{ screen }}">

            <# if ( data.fields.indexOf( 'margin' ) > -1 ) { #>

            <div class="box-margin fox-box-section">

                <span class="fox-control-label">Margin (px/em)</span>

                <div class="fox-control-row">

                    <?php foreach ( $pos as $p ) { ?>
                    <div class="fox-control-col col-1-4">
                        <span class="small-label"><?php echo ucfirst( $p ); ?></span>
                        <input type="text" data-prop="{{ prefix }}margin-<?php echo $p; ?>" />
                    </div>
                    <?php } ?>

                </div><!-- .fox-control-row -->

            </div>

            <# } #>

            <# if ( data.fields.indexOf( 'padding' ) > -1 ) { #>

            <div class="box-padding fox-box-section">

                <span class="fox-control-label">Padding (px/em)</span>

                <div class="fox-control-row">

                    <?php foreach ( $pos as $p ) { ?>
                    <div class="fox-control-col col-1-4">
                        <span class="small-label"><?php echo ucfirst( $p ); ?></span>
                        <input type="text" data-prop="{{ prefix }}padding-<?php echo $p; ?>" />
                    </div>
                    <?php } ?>

                </div><!-- .fox-control-row -->

            </div>

            <# } #>
                
                <# if ( 'desktop' == screen ) { #>

            <# if ( data.fields.indexOf( 'border' ) > -1 ) { #>

            <div class="box-border fox-box-section">

                <span class="fox-control-label">Border (px)</span>

                <div class="fox-control-row">

                    <?php foreach ( $pos as $p ) { ?>
                    <div class="fox-control-col col-1-4">
                        <span class="small-label"><?php echo ucfirst( $p ); ?></span>
                        <input type="text" data-prop="border-<?php echo $p; ?>-width" />
                    </div>
                    <?php } ?>

                </div><!-- .fox-control-row -->

            </div>

            <# } #>

            <# if ( data.fields.indexOf( 'border-color' ) > -1 ) { #>

            <div class="box-border-color fox-box-section">
                <span class="fox-control-label">Border color</span>
                <input type="text" data-prop="border-color" class="box-input-color" />
            </div>

            <# } #>

            <# if ( data.fields.indexOf( 'border-style' ) > -1 ) { #>

            <div class="box-border-color fox-box-section">
                <span class="fox-control-label">Border style</span>
                <select data-prop="border-style">
                    <option value="solid">Solid</option>
                    <option value="dotted">Dotted</option>
                    <option value="dashed">Dashed</option>
                    <option value="double">Double</option>
                </select>
            </div>

            <# } #>
                
            <# if ( data.fields.indexOf( 'border-radius' ) > -1 ) { #>    
                
            <div class="box-border fox-box-section">

                <span class="fox-control-label">Border Radius</span>

                <div class="fox-control-row">

                    <?php foreach ( $radius_pos as $p => $p_name ) { ?>
                    <div class="fox-control-col col-1-4">
                        <span class="small-label"><?php echo $p_name; ?></span>
                        <input type="text" data-prop="border-<?php echo $p; ?>-radius" />
                    </div>
                    <?php } ?>

                </div><!-- .fox-control-row -->

            </div>    
                
            <# } #>
                
                <# } // desktop screen #>

        </div><!-- .fox-box-control -->
                
        <# }); // each #>

    </div><!-- .fox-box-control-wrapper -->
        
    <?php }
    
}
endif;

/**
 * Typography
 * @since 4.0
 */
if ( !class_exists( 'Fox_Typography_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Typography_Control' );

class Fox_Typography_Control extends Fox_Customize_Control {
    
    public $type = 'typography';
    
    public function js_content() {
        
                ?>
                
                <div class="fox-typography">
                    
                    <# if ( data.fields.indexOf( 'size' ) > -1 ) { #>
                    
                    <label class="fox-typo-label">Font Size</label>
                    
                    <div class="fox-control-row">

                        <div class="fox-control-col col-1-2">

                            <input type="text" placeholder="" class="fox-typo-input fox-typo-input-size" data-prop="font-size" />
                            <i class="dashicons dashicons-desktop"></i>

                        </div>

                        <div class="fox-control-col col-1-4" title="If sizes on tablet is not specified, it'll be calculated automatically">

                            <input type="text" placeholder="" class="fox-typo-input fox-typo-input-size" data-prop="font-size-tablet" />
                            <i class="dashicons dashicons-tablet"></i>

                        </div>

                        <div class="fox-control-col col-1-4" title="If sizes on phone is not specified, it'll be calculated automatically">
                            <input type="text" placeholder="" class="fox-typo-input fox-typo-input-size" data-prop="font-size-phone" />
                            <i class="dashicons dashicons-smartphone "></i>
                        </div>

                    </div>
                        
                    <# } #>
                    
                    <div class="fox-control-row">
                        
                        <div class="fox-control-col col-1-3">
                            
                            <# if ( data.fields.indexOf( 'weight' ) > -1 ) { #>
                        
                            <div class="fox-typo-weight fox-typo-prop fox-typo-select">
                                
                                <label class="fox-typo-label">Weight</label>

                                <select data-prop="font-weight">

                                    <option value=""></option>
                                    
                                    <?php for ( $i = 1; $i<=9; $i++ ) { ?>
                                    <option value="<?php echo $i * 100; ?>"><?php echo $i * 100; ?></option>
                                    <?php } ?>

                                </select>

                            </div>
                            
                        </div>
                            
                        <# } #>
                            
                        <# if ( data.fields.indexOf( 'style' ) > -1 ) { #>
                        
                        <div class="fox-control-col col-1-3">
                    
                            <div class="fox-typo-style fox-typo-prop fox-typo-checkbox">

                                <label class="fox-typo-label">Style</label>
                                <label>
                                    <select data-prop="font-style">
                                        <option value=""></option>
                                        <option value="normal">Normal</option>
                                        <option value="italic">Italic</option>
                                    </select>
                                </label>

                            </div>
                            
                        </div>
                            
                        <# } #>
                            
                        <# if ( data.fields.indexOf( 'text-transform' ) > -1 ) { #>
                        
                        <div class="fox-control-col col-1-3">
                    
                            <div class="fox-typo-style fox-typo-prop fox-typo-select">
                                
                                <label class="fox-typo-label">Text</label>

                                <select data-prop="text-transform">
                                    <option value=""></option>
                                    <option value="uppercase">UPPERCASE</option>
                                    <option value="lowercase">lowercase</option>
                                    <option value="capitalize">Capitalize</option>
                                    <option value="none">None</option>
                                </select>

                            </div>
                            
                        </div>
                            
                        <# } #>    
                        
                    </div><!-- .fox-control-row -->
                    
                    <div class="fox-control-row">
                        
                        <# if ( data.fields.indexOf( 'letter-spacing' ) > -1 ) { #>
                        
                        <div class="fox-control-col col-1-3" title="Eg. 2px">
                            
                            <label class="fox-typo-label">Spacing</label>
                            
                            <div class="fox-typo-spacing fox-typo-text">
                                
                                <input type="text" class="fox-typo-input" data-prop="letter-spacing" />
                                
                            </div>
                            
                        </div>
                            
                        <# } #>    
                            
                        <# if ( data.fields.indexOf( 'line-height' ) > -1 ) { #>
                        
                        <div class="fox-control-col col-1-3">
                            
                            <label class="fox-typo-label">Line Height</label>
                            
                            <div class="fox-typo-line-height fox-typo-text" title="Eg. 1.3">
                                
                                <input type="text" class="fox-typo-input" data-prop="line-height" />
                                
                            </div>
                            
                        </div>
                            
                        <# } #>    
                        
                    </div>
             
                </div><!-- .fox-typography -->
                
    <?php
        
    }
    
}
endif;

/**
 * Select Font
 * @since 4.0
 */
if ( !class_exists( 'Fox_Select_Font_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Select_Font_Control' );

class Fox_Select_Font_Control extends Fox_Customize_Control
{
    
    public $type = 'select_font';
    
    public function js_content() { ?>
                
    <# if ( data.inherit_options ) { #>

        <div class="fox-select-font-wrapper select-inherit">

            <select class="font-select-inherit">
                
                <?php $primary_fonts = fox_primary_font_support(); foreach ( $primary_fonts as $id => $fontdata ) { ?>
                
                <option value="font_<?php echo $id; ?>">Same as <?php echo $fontdata[ 'name' ]; ?></option>
                
                <?php } ?>
                
                <option value="font_custom">Enter a custom font</option>

            </select>

    <# } else { #>

        <div class="fox-select-font-wrapper select-non-inherit">

    <# } #>    
                
            <div class="fox-select-font ui-widget">
                
                <input class="font-input" type="text" placeholder="Type font name.." />    

                <div class="font-weights">

                    <?php for ( $i = 1; $i <=9; $i++ ) {
                    if ( $i == 4 ) { $label = '400 (Regular)'; $label2 = '400 <em>Italic</em>'; }
                    elseif ( $i == 7 ) { $label = '700 (Bold)'; $label2 = '700 Bold <em>Italic</em>'; }
                    elseif ( $i == 3 ) { $label = '300 (Light)'; $label2 = '300 Light <em>Italic</em>'; }
                    else { $label = $i * 100; $label2 = $i*100 . ' <em>Italic</em>'; }
                    ?>
                    <label class="font-weight" data-weight="<?php echo $i * 100; ?>" for="{{ data.settings.default }}-weight-<?php echo $i *100; ?>">
                        <input type="checkbox" value="<?php echo $i * 100; ?>" class="weight-<?php echo $i * 100; ?>" id="{{ data.settings.default }}-weight-<?php echo $i *100; ?>" />
                        <span><?php echo $label; ?></span>
                    </label>
                    <label class="font-weight" data-weight="<?php echo $i * 100; ?>italic" for="{{ data.settings.default }}-weight-<?php echo $i *100; ?>-italic">
                        <input type="checkbox" value="<?php echo $i * 100; ?>italic" class="weight-<?php echo $i * 100; ?>italic" id="{{ data.settings.default }}-weight-<?php echo $i *100; ?>-italic" />
                        <span><?php echo $label2; ?></span>
                    </label>
                    <?php } ?>

                </div>
            
                <input class="font-result" type="hidden" data-customize-setting-link="{{ data.settings.default }}" />
                
            </div>

        </div><?php // .fox-select-font-wrapper ?>
                
    <?php }
    
}

endif;

/**
 * Radio Control
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Radio_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Radio_Control' );

class Fox_Radio_Control extends Fox_Customize_Control
{
    
    public $type = 'fox_radio';
    
    public function js_content() { ?>
                    
                    <div class="customize-control-content">
                    
                <# _.each( data.choices, function( value, key, obj ) { #>
                    
                    <label>
                        <input value="{{ key }}" type="radio" name="_customize-radio-{{ data.settings.default }}" data-customize-setting-link="{{ data.settings.default }}" />
                        {{{ value }}}<br/>
                    </label>
                    
                <# }) #>
                    
                    </div>
        
    <?php }
    
}

endif;

/**
 * Checkbox Control
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Checkbox_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Checkbox_Control' );

class Fox_Checkbox_Control extends Fox_Customize_Control
{
    
    public $type = 'fox_checkbox';
    
    public function content_template()
    {
        ob_start();
        ?>
        <label>
            <input type="checkbox" data-customize-setting-link="{{ data.settings.default }}" />
            {{{ data.label }}}
            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>
        </label>
        <?php
        echo ob_get_clean();
    }
    
}

endif;

/**
 * Custom Heading Control
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Heading_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Heading_Control' );

class Fox_Heading_Control extends Fox_Customize_Control
{
    
    public $type = 'heading';
    
    /*
     * Render the content on the theme customizer page
     */
    public function content_template()
    {
        ?>
        <div class="fox-customize-heading">
            <# if ( data.label ) { #>
                <h2>{{{ data.label }}}</h2>
            <# } #>
            <# if ( data.description ) { #>
                <div class="heading-desc">{{{ data.description }}}</div>
            <# } #>
        </div>
        <?php
    }
    
}

endif;

/**
 * Custom Message Control
 *
 * Prints an instruction for ease of customization
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Message_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Message_Control' );

class Fox_Message_Control extends Fox_Customize_Control
{
    
    public $type = 'message';
    
    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     */
    public function to_json() {
        parent::to_json();
        $unset = array( 'label', 'description' );
        foreach ( $unset as $un ) {
            if ( isset( $this->json[ $un ] ) )
                unset( $this->json[ $un ] );
        }
        $this->json['message'] = $this->setting->default;
    }
    
    public function content_template() {
        ?>
        <div class="fox-message">{{{ data.message }}}</div>
        <?php
    }
}

endif;

/**
 * Custom HTML
 *
 * Prints html
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_HTML_Control' ) ) :

$wp_customize->register_control_type( 'Fox_HTML_Control' );

class Fox_HTML_Control extends Fox_Customize_Control
{
    
    public $type = 'html';
    
    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     */
    public function to_json() {
        parent::to_json();
        $unset = array( 'label', 'description' );
        foreach ( $unset as $un ) {
            if ( isset( $this->json[ $un ] ) )
                unset( $this->json[ $un ] );
        }
        $this->json['html'] = $this->setting->default;
    }
    
    public function content_template() {
        ?>
        {{{ data.html }}}
        <?php
    }
}

endif;

/**
 * Image Radio: Prints radio input fields with image labels for ease of selection
 *
 * @since 1.0
 */
if ( !class_exists( 'Fox_Image_Radio_Control' ) ) :

$wp_customize->register_control_type( 'Fox_Image_Radio_Control' );

class Fox_Image_Radio_Control extends Fox_Customize_Control
{
    public $type = 'image_radio';
    
    public function to_json() {
        parent::to_json();
        $this->json['choices'] = $this->choices;
    }
    
    public function js_content() {
        ?>
        <div class="customize-control-content control-image-radio">
            
            <# var src, width, height, title,
               id = data.settings.default,
               name = '_customize-radio-' + id;
               
               _.each( data.choices, function ( value, key, obj ) {
               if ( 'object' === typeof value ) {
                    src = value.src || '',
                    html = value.html || '',
                    width = value.width || '',
                    height = value.height || '',
                    title = value.title || '';
               } else {
                    src = value;
                    width = height = title = html = '';
                }
               #>
                <label>
                    
                    <input type="radio" value="{{ key }}" name="{{ name }}" data-customize-setting-link="{{ id }}" />
                    <# if ( html ) { #>
                        
                        <div class="html-img" title="{{ title }}">
                    
                            {{{ html }}}

                            <# if ( title ) { #>
                            <span class="html-img-title">{{{ title }}}</span>
                            <# } #>

                        </div>
                        
                    <# } else { #>
                    <img src="{{ src }}" width="{{ width }}" height="{{ height }}" />
                    <# if ( title ) { #>
                    <small>{{{ title }}}</small>
                    <# } #>
                        
                    <# } #>
                        
                </label>
                        
            <# }) #>

        </div>
        <?php
    }

}

endif;

if ( !class_exists( 'Fox_Multicheckbox_Control' ) ) :
/**
 * Multicheckbox Control
 *
 * @since 1.0
 */
$wp_customize->register_control_type( 'Fox_Multicheckbox_Control' );

class Fox_Multicheckbox_Control extends Fox_Customize_Control {
    
    public $type = 'multicheckbox';
    
    public function to_json() {
        parent::to_json();
        $this->json['choices'] = $this->choices;
    }
    
    public function js_content() {
        ?>
        <ul>
            <# if ( ! jQuery.isArray( data.choices ) ) { for ( key in data.choices ) { value = data.choices[ key ]; #>
                
            <li>
                <label>
                    <input type="checkbox" value="{{ key }}" />
                    {{{ value }}}
                </label>
            </li>
                
            <# } } else { jQuery.each( data.choices, function( key, value ) { #>
                
            <li>
                <label>
                    <input type="checkbox" value="{{ value }}" />
                    {{{ value }}}
                </label>
            </li>

            <# }); } #>
        </ul>
        <input type="hidden" class="checkbox-result" data-customize-setting-link="{{ data.settings.default }}" />
    <?php
    }
}

endif;

if ( !class_exists( 'Fox_Multiselect_Control' ) ) :
/**
 * Multiselect Control
 *
 * @since 4.3
 */
$wp_customize->register_control_type( 'Fox_Multiselect_Control' );

class Fox_Multiselect_Control extends Fox_Customize_Control {
    
    public $type = 'multiselect';
    
    public function to_json() {
        parent::to_json();
        $this->json['choices'] = $this->choices;
    }
    
    public function js_content() {
        ?>
        <select data-customize-setting-link="{{ data.settings.default }}" multiple>
            <# if ( ! jQuery.isArray( data.choices ) ) { for ( key in data.choices ) { value = data.choices[ key ]; #>
                
            <option value="{{ key }}">{{{ value }}}</option>
                
            <# } } else { jQuery.each( data.choices, function( key, value ) { #>
                
            <option value="{{ value }}">{{{ value }}}</option>

            <# }); } #>
        </select>
        <small class="use-hint">Hold Ctrl (Windows) or Command (MacOS) for multi-select</small>        
    <?php
    }
}

endif;

if ( !class_exists( 'Fox_Slide_Control' ) ) :
/**
 * Slide Control
 *
 * @since 1.0
 */
$wp_customize->register_control_type( 'Fox_Slide_Control' );

class Fox_Slide_Control extends Fox_Customize_Control
{
    public $type = 'slide';
    
    public function js_content() {
        ?>
                <div class="slide-container">
                    
                    <div class="slide-input-area">
                        
                        <input type="text" class="slide-input" data-customize-setting-link="{{ data.settings.default }}" placeholder="{{ data.placeholder }}" />
                        <# if ( data.unit ) { #> <span class="unit">{{ data.unit }}</span> <# } #>
                            
                    </div><!-- .slide-input-area -->
                            
                    <div class="slide-control"></div>
                    
                </div><!-- .slide-container -->
    <?php
    }
}

endif;