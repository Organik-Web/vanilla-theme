<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package orgnk_client_textdomain
 */

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="page-404" role="main">
    <section class="section section-404">
        <div class="container">
            <div class="section-wrap">
                <div class="section-content">
                    <div class="content-wrap">

                        <h1 class="title"><?php esc_html_e( 'Sorry, that page can\'t be found', 'orgnk_client_textdomain' ) ?></h1>

                        <div class="content">
                            <span><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search or return home.', 'orgnk_client_textdomain' ) ?></span>
                        </div>

                        <?php get_search_form() ?>

                        <div class="actions">
                            <a class="primary-button" href="<?php echo esc_url( home_url( '/' ) ) ?>"><?php esc_html_e( 'Return home', 'orgnk_client_textdomain' ) ?></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer();
