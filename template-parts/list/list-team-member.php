<?php
/**
 * Template part for displaying a team member entry on the team members archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$image			= orgnk_get_image( get_post_thumbnail_id( get_the_ID() ) );
$position		= esc_html( get_post_meta( orgnk_get_the_ID(), 'team_member_position', true ) );
?>

<article id="team-member-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
	<a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
		<div class="entry-wrapper">

			<div class="entry-thumb"<?php if ( $image ) echo ' style="background-image: url(' . $image['medium']['url'] . ');"' ?>>
				<div class="ratio-sizer"></div>
			</div>

			<div class="entry-preview">
				<div class="entry-preview-content">

					<h2 class="name h4"><?php esc_html( the_title() ) ?></h2>

					<?php if ( $position ) : ?>
						<div class="meta" aria-hidden="true">
							<span class="position"><?php echo $position ?></span>
						</div>
					<?php endif ?>

				</div>
			</div>
		</div>
	</a>
</article>
