<?php
/**
 * Template Name: Form Submission
 * Description: The template for the form submission page
 *
 * @package orgnk_client_textdomain
 */

// Post meta variables
$meta_key     = 'form_submission';
$title      = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_title', true ) );
$title      = ( $title ) ? $title : 'Your enquiry has been submitted';
$text       = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_text', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page form-submission-page" role="main">
    <section class="section section-form-submission">
        <div class="container">
            <div class="section-wrap">
                <div class="section-content">
                    <div class="content-wrap">

                        <h1 class="title"><?php echo $title ?></h1>

                        <?php if ( $text ) : ?>
                            <div class="content">
                                <div class="editor-content">
                                    <div class="editor-content-wrap">
                                        <?php echo $text ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

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
