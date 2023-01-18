<?php
/**
 * Template part for displaying a single event entry
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */
?>

<article id="event-<?php the_ID() ?>" <?php post_class() ?>>

	<?php if ( function_exists( 'orgnk_events_entry_schedule' ) && orgnk_events_entry_schedule() ) : ?>
		<div class="event-entry-overview">

			<?php echo orgnk_events_entry_schedule( $first = true, $date_size = 'h3' );

			if ( function_exists( 'orgnk_events_entry_badge_list' ) && orgnk_events_entry_badge_list() ) : ?>
				<div class="event-attributes">
					<?php echo orgnk_events_entry_badge_list() ?>
				</div>
			<?php endif ?>
		</div>
	<?php endif ?>

	<div class="editor-content">
		<div class="editor-content-wrap">
			<?php the_content() ?>
		</div>
	</div>

</article>
