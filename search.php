<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 * @package orgnk_client_textdomain
 */

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page search-results-page" role="main">

	<?php get_template_part( 'template-parts/global/entry-header' ) ?>

	<?php if ( have_posts() ) : ?>

		<section class="section section-pad section-entry-list section-search-results">
			<div class="container">
				<div class="section-wrap">
					<div class="search-results-list">

						<?php while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/list/list-search-result' );
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

</main>

<?php get_footer();
