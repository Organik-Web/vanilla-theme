<?php
/**
 * The template for displaying all pages
 * This is the template that displays all pages by default
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_sidebar = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_sidebar', true ) );
$hide_cta = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="standard-page" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <section class="section section-pad section-standard-page">
        <div class="container">
            <div class="section-wrap">
                <div class="standard-page-wrap sidebar-right">

                    <?php while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/single/pages/single-page' );
                    endwhile ?>
                    
                    <?php if ( ! $hide_sidebar ) get_sidebar() ?>

                </div>
            </div>
        </div>
    </section>

    <?php if ( ! $hide_cta ) get_template_part( 'template-parts/global/call-to-action' ) ?>

</main>

<?php get_footer();
