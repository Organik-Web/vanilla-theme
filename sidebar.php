<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package orgnk_client_textdomain
 */

// Conditional variables
$has_menu				= ( orgnk_sidebar_sub_nav() ) ? 'has-menu' : 'no-menu';
$is_jobs_post_type		= ( defined( 'ORGNK_JOBS_CPT_NAME' ) && ORGNK_JOBS_CPT_NAME === get_post_type() ) ? true : false;
$is_events_post_type	= ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && ORGNK_EVENTS_CPT_NAME === get_post_type() ) ? true : false;

if ( $is_jobs_post_type ) : ?>

	<?php if ( function_exists( 'orgnk_jobs_entry_meta_table' ) && orgnk_jobs_entry_meta_table() ) : ?>

		<aside class="jobs-sidebar">
			<div class="sidebar no-menu"<?php echo orgnk_sidebar_offset() ?>>
										
				<div class="sidebar-wrap">
					<?php echo orgnk_jobs_entry_meta_table() ?>

					<?php if ( function_exists( 'orgnk_jobs_entry_apply_button' ) && orgnk_jobs_entry_apply_button() ) : ?>
						<div class="job-entry-actions">
							<?php echo orgnk_jobs_entry_apply_button() ?>
						</div>
					<?php endif ?>
				</div>
			</div>
		</aside>

	<?php endif ?>

<?php elseif ( $is_events_post_type ) : ?>

	<?php if ( function_exists( 'orgnk_events_entry_meta_table' ) && orgnk_events_entry_meta_table() ) : ?>

		<aside class="events-sidebar">
			<div class="sidebar no-menu"<?php echo orgnk_sidebar_offset() ?>>
				<div class="sidebar-wrap">
					<?php echo orgnk_events_entry_meta_table() ?>

					<?php if ( function_exists( 'orgnk_events_entry_tickets_button' ) && orgnk_events_entry_tickets_button() ) : ?>
						<div class="event-entry-actions">
							<?php echo orgnk_events_entry_tickets_button() ?>
						</div>
					<?php endif ?>
				</div>
			</div>
		</aside>

	<?php endif ?>

<?php elseif ( is_home() || is_category() || is_single() ) : ?>

	<?php if ( is_active_sidebar( 'posts-sidebar' ) ) : ?>

		<aside class="post-sidebar">
			<div class="sidebar no-menu"<?php echo orgnk_sidebar_offset() ?>>
				<div class="sidebar-wrap">
					<?php dynamic_sidebar( 'posts-sidebar' ) ?>
				</div>
			</div>
		</aside>

	<?php endif ?>

<?php else : ?>

	<?php if ( orgnk_sidebar_sub_nav() || is_active_sidebar( 'pages-sidebar' ) ) : ?>

		<aside class="page-sidebar">
			<div class="sidebar <?php echo $has_menu ?>"<?php echo orgnk_sidebar_offset() ?>>
				<div class="sidebar-wrap">
					<?php echo orgnk_sidebar_sub_nav() ?>
					<?php dynamic_sidebar( 'pages-sidebar' ) ?>
				</div>
			</div>
		</aside>

	<?php endif ?>
	
<?php endif;
