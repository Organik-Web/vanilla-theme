<?php
/**
 * Template Name: Sitemap
 * Description: A landing page template that loops through all of the page's children and displays them hierarchically
 *
 * @package orgnk_client_textdomain
 */

get_header();

$cpt_excludes = array(ORGNK_TEAMS_CPT_NAME);

?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page sitemap-page" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <section class="section section-pad section-entry-list">
        <div class="container">
            <div class="section-wrap">
                <div class="sitemap-list">

                    <?php if ( function_exists( 'orgnk_sitemap_list' ) ) echo orgnk_sitemap_list( 'h4' ) ?>

                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer();
