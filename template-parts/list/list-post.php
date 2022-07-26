<?php
/**
 * Template part for displaying a post entry on the posts archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$image = orgnk_get_image( get_post_thumbnail_id( get_the_ID() ) );
?>

<article id="post-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
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
					<h2 class="title h3"><?php esc_html( the_title() ) ?></h2>

					<div class="meta" aria-hidden="true">
						<span class="posted-on"><?php echo get_the_date() ?></span>
						<span class="categories"><?php echo strip_tags( get_the_term_list( get_the_ID(), 'category', '', ', ' ) ) ?></span>
					</div>
				</div>
			</div>
		</div>
	</a>
</article>
