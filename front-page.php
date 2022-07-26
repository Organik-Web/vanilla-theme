<?php
/**
 * The template for displaying the front page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="front-page" role="main">

    <?php
    get_template_part( 'template-parts/page/front-page/section-hero' );
    get_template_part( 'template-parts/page/front-page/section-quick-links' );
    get_template_part( 'template-parts/page/front-page/section-services' ); // Uses flexi section
    get_template_part( 'template-parts/page/front-page/section-about' ); // Uses flexi section
    get_template_part( 'template-parts/page/front-page/section-google-reviews' );
    get_template_part( 'template-parts/page/front-page/section-latest-news' );
    get_template_part( 'template-parts/page/front-page/section-card-scroller' );
    get_template_part( 'template-parts/global/call-to-action' );
    ?>

</main>

<?php get_footer();
