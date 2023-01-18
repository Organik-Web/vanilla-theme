<?php
/**
 * Template part for displaying a message that entries or content cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Conditional variables
$is_events_archive			= ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && is_post_type_archive( ORGNK_EVENTS_CPT_NAME ) ) ? true : false;
$is_services_archive		= ( defined( 'ORGNK_SERVICES_CPT_NAME' ) && is_post_type_archive( ORGNK_SERVICES_CPT_NAME ) ) ? true : false;
$is_teams_archive			= ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && is_post_type_archive( ORGNK_TEAMS_CPT_NAME ) ) ? true : false;

?>

<div class="not-found">

	<?php if ( $is_events_archive ) : ?>

		<h2><?php esc_html_e( 'No upcoming events', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'There are currently no upcoming events. Please check back later.', 'orgnk_client_textdomain' ) ?></p>

	<?php elseif ( $is_services_archive ) : ?>

		<h2><?php esc_html_e( 'No services', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'There are currently no services. Please check back later.', 'orgnk_client_textdomain' ) ?></p>

	<?php elseif ( $is_teams_archive ) : ?>

		<h2><?php esc_html_e( 'No team members', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'There are currently no team members. Please check back later.', 'orgnk_client_textdomain' ) ?></p>

	<?php elseif ( is_search() ) : ?>

		<h2><?php esc_html_e( 'Nothing found', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'orgnk_client_textdomain' ) ?></p>

	<?php elseif ( is_home() ) : ?>

		<h2><?php esc_html_e( 'Nothing found', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'There are currently no articles to show. Please check back later.', 'orgnk_client_textdomain' ) ?></p>

	<?php else : ?>

		<h2><?php esc_html_e( 'Nothing found', 'orgnk_client_textdomain' ) ?></h2>
		<p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'orgnk_client_textdomain' ) ?></p>

	<?php endif ?>

</div>
