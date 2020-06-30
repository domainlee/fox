<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
    
    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
    
    <?php do_action( 'fox_after_body' ); // since 4.0 ?>
    
    <div id="wi-all" class="fox-outer-wrapper fox-all wi-all">

        <?php do_action( 'fox_before_wrapper' ); // since 4.0 ?>

        <div id="wi-wrapper" class="fox-wrapper wi-wrapper">

            <div class="wi-container">

                <?php do_action( 'fox_wrapper' ); // since 4.0 ?>

                <?php get_template_part( 'parts/header' ); ?>

                <?php do_action( 'fox_after_masthead' ); ?>

                <div id="wi-main" class="wi-main fox-main">