<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;
$section_classes    = ( $args && isset( $args['section_classes'] ) ) ? $args['section_classes'] : null;
$section_is_dark    = ( $args && isset( $args['section_is_dark'] ) ) ? $args['section_is_dark'] : null;

// Section meta variables
$section_id         = sanitize_title_with_dashes( esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_section_id', true ) ) );
$columns_count      = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_columns', true ) );

if ( $columns_count ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-two-column-content<?php echo $section_classes ?>">
        <div class="container">
            <div class="section-wrap section-column-wrap">

                <?php for ( $i = 0; $i < $columns_count; $i++ ) :

                    // Repeater variables
                    $title      = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_columns_' . $i . '_title', true ) );
                    $text       = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_columns_' . $i . '_text', true ) );
                    $buttons    = orgnk_do_acf_button_group( $meta_key . '_columns_' . $i . '_buttons', 'primary-button', 'primary-button', $section_is_dark );

                    // Redefine section meta key for passing to part files
                    $args['section_meta_key'] = $meta_key . '_columns_' . $i;

                    if ( $title && $text ) : ?>

                        <div class="section-column column-<?php echo $i + 1 ?>">

                            <div class="section-media">
                                <?php get_template_part( 'template-parts/page/flexi-sections/part-section-media', null, $args ) ?>
                            </div>

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
                    <?php endif ?>
                <?php endfor ?>
            </div>
        </div>
    </section>
<?php endif;
