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
$sub_content_type   = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_sub_content', true ) );
$buttons            = orgnk_do_acf_button_group( $meta_key . '_buttons', 'primary-button', 'primary-button', $section_is_dark );
$highlights_count   = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_highlights', true ) );
$list_items_count   = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_list_items', true ) );

if ( $title && $text ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-split-content<?php echo $section_classes ?>">
        <div class="container">
            <div class="section-wrap section-column-wrap">

                <div class="section-media section-column">
                    <?php get_template_part( 'template-parts/page/flexi-sections/part-section-media', null, $args ) ?>
                </div>

                <div class="section-content section-column">
                    <div class="content-wrap">
                        <h2 class="title<?php echo orgnk_class_by_string_length( $title, 35, 'h1' ) ?>"><?php echo $title ?></h2>
                        <div class="content">
                            <div class="editor-content">
                                <div class="editor-content-wrap">
                                    <?php echo $text ?>
                                </div>
                            </div>
                        </div>
                        <?php if ( $sub_content_type === 'buttons' && $buttons ) : ?>
                            <?php echo $buttons ?>
                        <?php elseif ( $sub_content_type === 'highlights' && $highlights_count ) : ?>
                            <div class="section-sub-content sub-content-highlights">
                                <div class="section-list">
                                    <?php get_template_part( 'template-parts/page/flexi-sections/part-highlights-list', null, $args ) ?>
                                </div>
                            </div>
                        <?php elseif ( $sub_content_type === 'list' && $list_items_count ) : ?>
                            <div class="section-sub-content sub-content-list">
                                <div class="section-list">
                                    <?php get_template_part( 'template-parts/page/flexi-sections/part-simple-list', null, $args ) ?>
                                </div>
                            </div>
                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif;
