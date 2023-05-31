<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$form_id            = ( $args && isset( $args['form_id'] ) ) ? $args['form_id'] : null;

// Post meta variables
$meta_key           = 'options_business_contact';
$title              = esc_html( get_option( $meta_key . '_title' ) );
$text               = orgnk_get_the_content( get_option( $meta_key . '_text' ) );

?>

<section class="section section-contact-general section-pad">
    <div class="container">
        <div class="section-wrap section-column-wrap">

            <div class="section-content section-contact-content section-column">
                <div class="content-wrap">

                    <?php if ( $title ) : ?>

                        <div class="contact-header">

                            <h2 class="title h1"><?php echo $title ?></h2>

                            <?php if ( $text ) : ?>
                                <div class="content">
                                    <div class="editor-content">
                                        <div class="editor-content-wrap">
                                            <?php echo $text ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>

                        </div>
                    <?php endif ?>

                    <?php get_template_part( 'template-parts/global/contact-details' ) ?>
                    <?php get_template_part( 'template-parts/global/social-links' ) ?>

                </div>
            </div>

            <?php if ( $form_id && function_exists( 'gravity_form' ) ) : ?>
                <div class="section-contact-form section-column">
                    <div class="contact-form-wrap">
                        <?php gravity_form( $form_id, false, false ) ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>
