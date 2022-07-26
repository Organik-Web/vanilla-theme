<?php
/**
 * Template part for displaying a location entry on the locations archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package kp_textdomain
 */

// Post meta variables
$image      			= orgnk_get_image( get_post_thumbnail_id( get_the_ID() ) );
$meta_key				= 'location';
$custom_title			= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_custom_title', true ) );
$title 					= $custom_title ? $custom_title : get_the_title();
$subtitle				= esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_subtitle', true ) );
?>

<article id="location-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
	<a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
		<div class="entry-wrapper">

			<div class="ratio-sizer image-container">
                <picture>
                    <?php if ( $image ) : ?>
                        <?php if ( $image['medium']['url'] ) echo '<source srcset=" ' . $image['medium']['url'] . ' " media="(min-width: 400px)">'?>
                    <?php endif ?>
                    <img width="<?php echo $image['thumbnail']['width'] ?>"  height="<?php echo $image['thumbnail']['height'] ?>" class="entry-thumb image-cover" src="<?php if ( $image['thumbnail']['url'] ) echo $image['thumbnail']['url']; else  echo get_template_directory_uri() . '/images/default-thumb.svg' ?>"  alt="<?php if ( $image['alt'] ) echo $image['alt'] ?>"  loading="lazy" ></img>
                </picture>
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
