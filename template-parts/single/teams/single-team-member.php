<?php
/**
 * Template part for displaying a team member entry
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */
$image			= orgnk_get_image_meta( get_post_thumbnail_id( get_the_ID() ) );

if ( $image ) : ?>

	<aside class="team-member-sidebar">
		<div class="picture-ratio-sizer">
			<?php if ( $image  && function_exists( "orgnk_picture" ) ) :?>
				<?php orgnk_picture($image['id'], [
					0 => ['lg'],
					200 => ['thumb.webp', 'thumb'],
					800 => ['lg.webp', 'lg'],
					1280 => ['xl.webp', 'xl'],
				], [
					'class' => 'image entry-thumb image-cover',
					'alt' => $image['alt'] ?? null,
					'loading' => 'lazy',
					'width' => 400,
					'height' => 400
				]); ?>
			<?php endif ?>
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
