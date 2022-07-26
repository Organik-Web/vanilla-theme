<?php
/**
 * Template part for displaying a post entry on the posts archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$employment_type = esc_html( get_post_meta( get_the_ID(), 'job_employment_type', true ) );

?>

<article id="post-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
	<a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
		<div class="entry-wrapper">
			<div class="entry-preview">
				<div class="entry-preview-content">

					<h2 class="title"><?php esc_html( the_title() ) ?></h2>

					<?php if ( $employment_type ) : ?>
						<div class="meta">
							<span class="employment-type"><?php echo $employment_type ?></span>
						</div>
					<?php endif ?>

					<?php if ( orgnk_auto_excerpt() ) : ?>
						<div class="excerpt" aria-hidden="true"><?php echo orgnk_auto_excerpt() ?></div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</a>
</article>
