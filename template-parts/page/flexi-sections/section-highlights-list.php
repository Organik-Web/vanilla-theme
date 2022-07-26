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
$highlights_count   = esc_html( get_post_meta( get_the_ID(), $meta_key . '_highlights', true ) );

if ( $highlights_count ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-highlights-list<?php echo $section_classes ?>">
        <div class="container">
            <div class="section-wrap">

                <?php if ( $title || $text ) : ?>
                    <div class="section-content section-header margin-lg">
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
                    <?php get_template_part( 'template-parts/page/flexi-sections/part-highlights-list', null, $args ) ?>
                </div>
            </div>
        </div>
    </section>
<?php endif;
