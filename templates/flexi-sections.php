<?php
/**
 * Template Name: Flexible Sections
 * Description: A flexible template that can be built using ACF flexible content blocks
 *
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_cta = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page flexible-sections-page" role="main">
    
    <?php
    get_template_part( 'template-parts/global/entry-header' );
    get_template_part( 'template-parts/page/flexi-sections/page-flexi-sections' );
    if ( ! $hide_cta ) get_template_part( 'template-parts/global/call-to-action' );
    ?>

</main>

<?php get_footer();
