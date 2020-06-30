<?php
$settings = $this->get_settings_for_display();
if ( ! function_exists( 'fox_btn' ) ) return;

fox_btn( $settings );