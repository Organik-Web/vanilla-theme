<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;
$section_classes    = ( $args && isset( $args['section_classes'] ) ) ? $args['section_classes'] : null;
$section_is_dark    = ( $args && isset( $args['section_is_dark'] ) ) ? $args['section_is_dark'] : null;

// Section meta variables
$section_id         = sanitize_title_with_dashes( esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_section_id', true ) ) );
$title              = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_title', true ) );
$text               = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_text', true ) );
$prices_count       = esc_html( get_post_meta( get_the_ID(), $meta_key . '_prices', true ) );

if ( $prices_count ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-pricing-list<?php echo $section_classes ?>">
        <div class="container">
            <div class="section-wrap">

                <?php if ( $title || $text ) : ?>
                    <div class="section-content section-header centered margin-lg">
                        <div class="content-wrap section-header-wrap">

                            <?php if ( $title ) : ?>
                                <h2 class="title<?php echo orgnk_class_by_string_length( $title, 35, 'h1' ) ?>"><?php echo $title ?></h2>
                            <?php endif ?>

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
                    </div>
                <?php endif ?>

                <div class="section-list">
                    <div class="prices-list">

                        <?php for ( $i = 0; $i < $prices_count; $i++ ) :

                            // Repeater variables
                            $price_value        = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_prices_' . $i . '_price', true ) );
                            $price_label        = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_prices_' . $i . '_label', true ) );
                            $price_text         = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_prices_' . $i . '_text', true ) );

                            if ( $price_value ) : ?>

                                <div class="price price-<?php echo $i + 1 ?>">
                                    <div class="price-wrapper">

                                        <div class="price-summary">

                                            <div class="amount">
                                                <span class="unit">&#36;</span>
                                                <span class="value"><?php echo orgnk_format_price( $price_value ) ?></span>
                                            </div>

                                            <?php if ( $price_label ) : ?>
                                                <span class="label"><?php echo $price_label ?></span>
                                            <?php endif ?>

                                            <?php if ( $price_text ) : ?>
                                                <span class="description"><?php echo $price_text ?></span>
                                            <?php endif ?>

                                        </div>

                                        <?php if ( orgnk_has_enquiry_form() ) : ?>
                                            <div class="actions">
                                                <a class="primary-button enquiry-modal-trigger" href="#enquiry-form"><?php esc_html_e( 'Enquire now', 'orgnk_client_textdomain' ) ?></a>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endfor ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;
