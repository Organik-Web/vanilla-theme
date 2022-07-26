<?php
//=======================================================================================================================================================
// Cleanup WordPress
//=======================================================================================================================================================

/**
 * Keep the dashboard clean
 */
function orgnk_remove_dashboard_widgets() {
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' ); // Incoming Links
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' ); // Plugins
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Press
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' ); // Recent Drafts
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress blog
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); // Other WordPress News
    remove_meta_box( 'wpdm_dashboard_widget', 'dashboard', 'normal' ); // Right Now
    remove_meta_box( 'dashboard_custom_feed', 'dashboard', 'normal' ); // Latest from ButlerBlog
    remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' ); // News from Modern Tribe
    remove_meta_box( 'pmpro_db_widget', 'dashboard', 'normal' ); // News from Modern Tribe
    remove_meta_box( 'social4i_admin_widget', 'dashboard', 'normal' ); // News and Updates
}
add_action( 'wp_dashboard_setup', 'orgnk_remove_dashboard_widgets' );

/**
 * Tidy up Customizer and remove things not supported by this theme
 */
function orgnk_theme_customizer( $wp_customize ) {
 	$wp_customize->remove_section( 'colors' );
 	$wp_customize->remove_section( 'background_image' );
 	$wp_customize->remove_section( 'static_front_page' );
 	$wp_customize->remove_section( 'custom_css' );
}
add_action( 'customize_register', 'orgnk_theme_customizer' );

/**
 * Remove the WP version from the RSS
 */
function orgnk_press_remove_wp_version_rss() {
    return '';
}
add_filter( 'the_generator', 'orgnk_press_remove_wp_version_rss' );

/**
 * Clean up the head links
 */
function orgnk_remove_head_link() {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action(  'wp_head', 'wp_shortlink_wp_head' );
    remove_action(  'wp_head', 'feed_links', 2 );
}
add_action('init', 'orgnk_remove_head_link');

/**
 * Remove emoji stuff added by WP
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Remove comment author links
 */
if ( ! function_exists( 'disable_comment_author_links' ) ) {
    function disable_comment_author_links( $author_link ) {
        return strip_tags( $author_link );
    }
    add_filter( 'get_comment_author_link', 'disable_comment_author_links' );
}

/**
 * When using the Classic editor these metaboxes are displayed, so we're removing them here
 */
function orgnk_press_remove_page_metaboxes() {
    remove_meta_box( 'commentstatusdiv', 'page', 'normal' ); // Comments Status Metabox
    remove_meta_box( 'commentsdiv', 'page', 'normal' ); // Comments Metabox
    remove_meta_box( 'postcustom', 'page', 'normal' ); // Custom Fields Metabox
    remove_meta_box( 'revisionsdiv', 'page', 'normal' ); // Revisions Metabox
    remove_meta_box( 'trackbacksdiv', 'page', 'normal' ); // Trackback Metabox
}
add_action( 'admin_menu', 'orgnk_press_remove_page_metaboxes' );
