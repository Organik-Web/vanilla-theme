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
$cards_count        = esc_html( get_post_meta( get_the_ID(), $meta_key . '_cards', true ) );

if ( $cards_count ) : ?>

    <section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-cards-list<?php echo $section_classes ?>">
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
                    <div class="cards-list">
                        <?php for ( $i = 0; $i < $cards_count; $i++ ) :

                            // Repeater variables
                            $card_image         = orgnk_get_image( get_post_meta( orgnk_get_the_ID(), $meta_key . '_cards_' . $i . '_image', true ) );
                            $card_title         = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_cards_' . $i . '_title', true ) );
                            $card_text          = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_cards_' . $i . '_text', true ) );
                            $card_link          = orgnk_get_acf_link( $meta_key . '_cards_' . $i . '_link' );

                            if ( $card_title ) : ?>
                                <div class="card card-<?php echo $i + 1 ?>">
                                    <?php if ( $card_link ) echo '<a class="card-link" href="' . $card_link['url'] . '"' . $card_link['target'] . '>' ?>
                                        <div class="card-wrapper">

                                            <?php if ( $card_image ) : ?>
                                                <div class="ratio-sizer card_image-container">
                                                    <picture>
                                                        <source srcset="<?php if ( $card_image ) echo $card_image['full']['url'] ?>" media="(min-width: 1024px)">
                                                        <source srcset="<?php if ( $card_image ) echo $card_image['large']['url'] ?>" media="(min-width: 768px)">
                                                        <source srcset="<?php if ( $card_image ) echo $card_image['medium']['url'] ?>" media="(min-width: 400px)">
                                                        <img width="<?php echo $card_image['thumbnail']['width'] ?>"  height="<?php echo $card_image['thumbnail']['height'] ?>" class="card-thumb image-cover" <?php if ( $card_image ) echo 'src="' . $card_image['thumbnail']['url'] . '"' ?> alt="<?php if ( $card_image['alt'] ) echo $card_image['alt'] ?>" loading="lazy" ></img>
                                                    </picture>
                                                </div>
                                            <?php endif ?>

                                            <div class="card-preview">
                                                <div class="card-preview-content">

                                                    <span class="title h4"><?php echo $card_title ?></span>

                                                    <?php if ( $card_text ) : ?>
                                                        <span class="subtitle" aria-hidden="true"><?php echo $card_text ?></span>
                                                    <?php endif ?>

                                                    <?php if ( $card_link ) : ?>
                                                        <div class="actions">
                                                            <div class="secondary-button<?php if ( $section_is_dark ) echo ' white' ?>"><?php echo $card_link['title'] ?></div>
                                                        </div>
                                                    <?php endif ?>

                                                </div>
                                            </div>
                                        </div>
                                    <?php if ( $card_link ) echo '</a>' ?>
                                </div>
                            <?php endif ?>
                        <?php endfor ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;
