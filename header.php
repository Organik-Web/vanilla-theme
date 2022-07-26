<?php
/**
 * The header for the theme
 *
 * @package orgnk_client_textdomain
 */
?>

<!DOCTYPE html>
<html dir="ltr" <?php language_attributes() ?>>
	<head>

		<meta charset="<?php bloginfo( 'charset' ) ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">

		<link rel="author" href="Organik Web">

		<link rel="preload" as="font" href="<?php echo esc_url( get_template_directory_uri() ) ?>/fonts/orgnk-ui-icons/ui-icons.woff2" type="font/woff2" crossorigin="anonymous">
		<link rel="preload" as="font" href="<?php echo esc_url( get_template_directory_uri() ) ?>/fonts/orgnk-ui-icons/ui-icons.woff" type="font/woff" crossorigin="anonymous">

		<link rel="preload" as="font" href="<?php echo esc_url( get_template_directory_uri() ) ?>/fonts/orgnk-social-icons/social-icons.woff2" type="font/woff2" crossorigin="anonymous">
		<link rel="preload" as="font" href="<?php echo esc_url( get_template_directory_uri() ) ?>/fonts/orgnk-social-icons/social-icons.woff" type="font/woff" crossorigin="anonymous">

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( home_url() ) ?>/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( home_url() ) ?>/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( home_url() ) ?>/favicon-16x16.png">
		<link rel="manifest" href="<?php echo esc_url( home_url() ) ?>/site.webmanifest">
		<link rel="mask-icon" href="<?php echo esc_url( home_url() ) ?>/safari-pinned-tab.svg" color="#00d6bd">
		<link rel="shortcut icon" href="<?php echo esc_url( home_url() ) ?>/favicon.ico">
		<meta name="apple-mobile-web-app-title" content="<?php esc_html( bloginfo( 'name' ) ) ?>">
		<meta name="application-name" content="<?php esc_html( bloginfo( 'name' ) ) ?>">
		<meta name="msapplication-TileColor" content="#001c2d">
		<meta name="theme-color" content="#ffffff">

		<?php wp_head() ?>

		<?php if ( defined( 'HOST_ENVIRONMENT' ) && HOST_ENVIRONMENT === 'production' ) : ?>
			<!-- Google tag manager head script -->
		<?php endif ?>

	</head>

	<body <?php body_class('no-js') ?>>

		<script>document.body.className = document.body.className.replace('no-js','js');</script>

		<?php if ( defined( 'HOST_ENVIRONMENT' ) && HOST_ENVIRONMENT === 'production' ) : ?>
			<!-- Google tag manager body script -->
		<?php endif ?>

		<?php echo orgnk_skip_to_content_button() ?>

		<?php get_template_part( 'template-parts/global/site-header' ) ?>

		<div class="page-body">
