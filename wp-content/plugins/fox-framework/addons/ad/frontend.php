<?php
$settings = $this->get_settings_for_display();
if ( ! function_exists( 'fox_ad' ) ) return;

fox_ad( $settings );