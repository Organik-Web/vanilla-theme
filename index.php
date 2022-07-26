<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_cta = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page index-page posts-archive" role="main">

	<?php get_template_part( 'template-parts/global/entry-header' ) ?>

	<?php if ( have_posts() ) : ?>

        <section class="section section-pad section-entry-list">
            <div class="container">
				<div class="section-wrap">
					<div class="posts-list">

						<?php while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/list/list-post' );
						endwhile ?>

					</div>

					<?php get_template_part( 'template-parts/global/pagination' ) ?>

				</div>
			</div>
        </section>

	<?php else : ?>

		<section class="section section-pad section-no-content">
			<div class="container">
				<div class="section-wrap">
					<?php get_template_part( 'template-parts/global/no-content' ) ?>
				</div>
			</div>
		</section>

	<?php endif ?>

	<?php if ( ! $hide_cta ) get_template_part( 'template-parts/global/call-to-action' ) ?>

</main>

<?php get_footer();
