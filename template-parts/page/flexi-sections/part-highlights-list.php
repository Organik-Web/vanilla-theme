<?php
// Return early if no variables have been passed to this template part via get_template_part
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;

// Section meta variables
$highlights_count   = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_highlights', true ) );

if ( $highlights_count ) : ?>

    <div class="highlights-list">

        <?php for ( $i = 0; $i < $highlights_count; $i++ ) :

            // Repeater variables
            $highlight_icon         = orgnk_get_image( get_post_meta( orgnk_get_the_ID(), $meta_key . '_highlights_' . $i . '_image', true ) );
            $highlight_title        = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_highlights_' . $i . '_title', true ) );
            $highlight_text         = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_highlights_' . $i . '_text', true ) );

            if ( $highlight_title && $highlight_text ) : ?>

                <div class="highlight highlight-<?php echo $i + 1 ?>">
                    <div class="highlight-wrapper">

                        <?php if ( $highlight_icon && orgnk_image_is_svg( $highlight_icon['full']['url'] ) ) : ?>
                            <div class="highlight-thumb">
                                <?php echo orgnk_get_svg_contents( $highlight_icon['full']['url'] ) ?>
                            </div>
                        <?php elseif ( $highlight_icon ) : ?>
                            <div class="highlight-thumb" style="background-image: url('<?php echo $highlight_icon['full']['url'] ?>');">
                                <div class="ratio-sizer"></div>
                            </div>
                        <?php endif ?>

                        <div class="highlight-preview">
                            <div class="highlight-preview-content">
                                <span class="title h4"><?php echo $highlight_title ?></span>
                                <span class="subtitle"><?php echo $highlight_text ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        <?php endfor ?>
    </div>

<?php endif;
