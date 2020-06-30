<?php
$settings = $this->get_settings_for_display();
if ( ! function_exists( 'fox_subscribe_form' ) ) return;

fox_subscribe_form( $settings );