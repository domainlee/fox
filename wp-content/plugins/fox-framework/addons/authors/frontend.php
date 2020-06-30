<?php
$settings = $this->get_settings_for_display();

if ( ! function_exists( 'fox_authors' ) ) return;

fox_authors( $settings );