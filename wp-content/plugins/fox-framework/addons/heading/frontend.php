<?php
$settings = $this->get_settings_for_display();
if ( ! function_exists( 'fox_heading' ) ) return;

fox_heading( $settings );