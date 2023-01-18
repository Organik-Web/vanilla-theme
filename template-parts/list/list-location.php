<?php
/**
 * Template part for displaying a location entry on the locations archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package kp_textdomain
 */

// Post meta variables
$image      			= orgnk_get_image_meta( get_post_thumbnail_id( get_the_ID() ) );
$meta_key				= 'location';
$custom_title			= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_custom_title', true ) );
$title 					= $custom_title ? $custom_title : get_the_title();
$subtitle				= esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_subtitle', true ) );
?>

<article id="location-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
	<a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
		<div class="entry-wrapper">

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

			<div class="entry-preview">
				<div class="entry-preview-content">

					<div class="title-wrap">
						<h2 class="title"><?php echo $title ?></h2>
					</div>

				</div>
			</div>
		</div>
	</a>
</article>
