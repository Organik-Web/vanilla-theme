<?php
//=======================================================================================================================================================
// Modifications to the Gutenberg editor
//=======================================================================================================================================================

/**
 * orgnk_gutenberg_disable_all_colors()
 * Disable all colors within Gutenberg
 */
function orgnk_gutenberg_disable_all_colors() {
    add_theme_support( 'disable-custom-gradients' );
    add_theme_support( 'editor-gradient-presets', array() );
    add_theme_support( 'editor-color-palette' ); // Disables color picking entirely
    add_theme_support( 'disable-custom-colors' );
    add_theme_support( 'disable-custom-font-sizes' );
    add_theme_support( 'editor-font-sizes', array() );
}
add_action( 'after_setup_theme', 'orgnk_gutenberg_disable_all_colors' );
