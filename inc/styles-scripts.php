<?php
//=======================================================================================================================================================
// Styles & scripts
//=======================================================================================================================================================

/**
 * orgnk_frontend_enqueue_scripts()
 * Enqueue front-end stylesheets & scripts
 */
function orgnk_frontend_enqueue_scripts() {

	// Move jQuery to the footer
	wp_dequeue_script( 'jquery' );
	wp_dequeue_script( 'jquery-core' );
	wp_dequeue_script( 'jquery-migrate' );
	wp_enqueue_script( 'jquery', false, array(), false, true );
	wp_enqueue_script( 'jquery-core', false, array(), false, true );
	wp_enqueue_script( 'jquery-migrate', false, array(), false, true );

	// Add JavaScript files
	orgnk_enqueue_script( 'what-input', '/js/libs/what-input.min.js', array('jquery'), SCRIPT_VERSION, true ); // v5.2.10
	orgnk_enqueue_script( 'modaal', '/js/libs/modaal.min.js', array('jquery'), SCRIPT_VERSION, true ); // v0.4.4
	orgnk_enqueue_script( 'splide', '/js/libs/splide.min.js', array(), SCRIPT_VERSION, true ); // v2.4.12
	orgnk_enqueue_script( 'orgnk-core', '/js/orgnk-core-js/orgnk-core.min.js', array('jquery'), SCRIPT_VERSION, true );
	orgnk_enqueue_script( 'extras', '/js/extras.min.js', array('jquery'), SCRIPT_VERSION, true );

	// Add CSS files
	orgnk_enqueue_style( 'main', '/css/main.min.css', array(), SCRIPT_VERSION );

	// Remove Gutenberg Block Library CSS from loading on the frontend if Gutenberg is not being used
	if ( SUPPORTS_GUTENBERG === false ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
	}
}
add_action( 'wp_enqueue_scripts', 'orgnk_frontend_enqueue_scripts' );

// Disable filters that remove query strings from CSS & JS
remove_filter( 'style_loader_src', 'headache_remove_script_version' , 15, 1);
remove_filter( 'script_loader_src', 'headache_remove_script_version' , 15, 1);

//=======================================================================================================================================================

/**
 * orgnk_admin_enqueue_scripts()
 * Enqueue admin stylesheets & scripts
 */
function orgnk_admin_enqueue_scripts() {
	wp_enqueue_style( 'theme-admin', get_template_directory_uri() . '/css/admin.min.css', array(), SCRIPT_VERSION );
}
add_action( 'admin_enqueue_scripts', 'orgnk_admin_enqueue_scripts', 100 );

//=======================================================================================================================================================

/**
 * orgnk_admin_gutenberg_enqueue_scripts()
 * Enequeue scripts for the Gutenberg editor if Gutenberg is being used
 */
function orgnk_admin_gutenberg_enqueue_scripts() {
	wp_enqueue_script( 'block-editor', get_template_directory_uri() . '/js/block-editor.min.js', array( 'wp-blocks', 'wp-dom' ), SCRIPT_VERSION, true );
}

// Enqueue the Gutenberg scripts if the SUPPORTS_GUTENBERG variable is set to true
if ( defined( 'SUPPORTS_GUTENBERG' ) && SUPPORTS_GUTENBERG === true ) {
	add_action( 'enqueue_block_editor_assets', 'orgnk_admin_gutenberg_enqueue_scripts' );
}
