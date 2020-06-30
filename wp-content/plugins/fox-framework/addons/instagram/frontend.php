<?php
$settings = $this->get_settings_for_display();
if ( ! class_exists( 'Fox_Instagram' ) ) return;

$insta = new Fox_Instagram( $settings );
$insta->output();