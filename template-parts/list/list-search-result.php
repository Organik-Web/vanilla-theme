<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Allow WordPress defaults
$allowed_post_types = array(
	'page'		=> 'page',
	'article'	=> 'post',
);

// Add custom post types to allowed post types array
if ( defined( 'ORGNK_EVENTS_CPT_NAME' ) ) $allowed_post_types['event'] = ORGNK_EVENTS_CPT_NAME;
if ( defined( 'ORGNK_SERVICES_CPT_NAME' ) ) $allowed_post_types['service'] = ORGNK_SERVICES_CPT_NAME;

?>

<article id="entry-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
	<div class="entry-wrapper">

		<div class="entry-preview">
			<div class="entry-preview-content">

				<h2 class="title"><?php esc_html( the_title() ) ?></h2>

				<?php if ( in_array( get_post_type(), $allowed_post_types ) ) :

					$label = array_keys( $allowed_post_types, get_post_type() )[0];

					if ( orgnk_auto_excerpt() ) : ?>
						<div class="excerpt" aria-hidden="true"><?php echo orgnk_auto_excerpt(40) ?></div>
					<?php endif ?>

					<div class="actions" aria-hidden="true">
						<a class="secondary-button" href="<?php echo esc_url( the_permalink() ) ?>" target="_self"><?php esc_html_e( 'View ' . $label, 'orgnk_client_textdomain' ) ?></a>
					</div>
							
				<?php endif ?>

			</div>
		</div>
	</div>
</article>
