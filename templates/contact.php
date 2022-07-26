<?php
/**
 * Template Name: Contact
 * Description: The template for the contact page
 *
 * @package orgnk_client_textdomain
 */

// Post meta variables
$meta_key       = 'contact';
$form_id        = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_form_id', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page contact-page" role="main">

    <?php 
    get_template_part( 'template-parts/global/entry-header' );
    get_template_part( 'template-parts/page/contact/section-contact-general', null, array( 'form_id' => $form_id ) );
    get_template_part( 'template-parts/page/contact/section-map' );
    ?>

</main>

<?php get_footer();
