<?php
//=======================================================================================================================================================
// ACF theme settings page
//=======================================================================================================================================================

if ( function_exists( 'acf_add_options_page' ) ) {
	
	acf_add_options_page( array(
		'page_title' 	=> __( 'Theme Settings', 'orgnk_client_textdomain' ),
		'menu_title'	=> __( 'Theme Settings', 'orgnk_client_textdomain' ),
		'menu_slug' 	=> __( 'theme-settings', 'orgnk_client_textdomain' ),
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
