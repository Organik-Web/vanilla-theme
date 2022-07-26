<?php
/**
 * Template part for displaying a team member entry
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

if ( has_post_thumbnail() ) : ?>

	<aside class="team-member-sidebar">
		<div class="team-member-thumb" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?>');">
			<div class="ratio-sizer"></div>
		</div>
	</aside>

<?php endif ?>

<article id="team-member-<?php the_ID() ?>" <?php post_class() ?>>

	<div class="editor-content">
		<div class="editor-content-wrap">
			<?php the_content() ?>
		</div>
	</div>

	<?php if ( function_exists( 'orgnk_teams_entry_meta_contact' ) ) echo orgnk_teams_entry_meta_contact() ?>

</article>
