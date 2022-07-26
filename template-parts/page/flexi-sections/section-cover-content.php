<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;
$section_classes    = ( $args && isset( $args['section_classes'] ) ) ? $args['section_classes'] : null;
$section_is_dark    = ( $args && isset( $args['section_is_dark'] ) ) ? $args['section_is_dark'] : null;

// Section meta variables
$section_id     = sanitize_title_with_dashes( esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_section_id', true ) ) );
$image          = orgnk_get_image( get_post_meta( orgnk_get_the_ID(), $meta_key . '_image', true ) );
$title          = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_title', true ) );
$text           = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_text', true ) );
$buttons        = orgnk_do_acf_button_group( $meta_key . '_buttons', 'primary-button', 'primary-button', $section_is_dark );

if ( $title && $text ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-cover-content<?php echo $section_classes ?>"<?php if ( $image ) echo ' style="background-image: url(' . $image['full']['url'] . ');"' ?>>

        <div class="overlay"></div>

        <div class="container">
            <div class="section-wrap">

                <div class="section-content">
                    <div class="content-wrap">

                        <h2 class="title<?php echo orgnk_class_by_string_length( $title, 35, 'h1' ) ?>"><?php echo $title ?></h2>

                        <div class="content">
                            <div class="editor-content">
                                <div class="editor-content-wrap">
                                    <?php echo $text ?>
                                </div>
                            </div>
                        </div>

                        <?php if ( $buttons ) echo $buttons ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif;
