<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;

// Section meta variables
$image              = orgnk_get_image_meta(get_post_meta( orgnk_get_the_ID(), $meta_key . '_image', true ) );
$image_contain      = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_image_contain', true ) );
$video              = esc_url( get_post_meta( orgnk_get_the_ID(), $meta_key . '_video', true ) );
$map                = esc_url( get_post_meta( orgnk_get_the_ID(), $meta_key . '_map', true ) );

if ( $image || $video || $map ) : ?>


    <div class="picture-ratio-sizer image-container <?php if ($image_contain) echo 'image-contain' ?>">
        <?php if ( $image  && function_exists( "orgnk_picture" ) ) :?>
            <?php orgnk_picture($image['id'], [
                0 => ['lg'],
                200 => ['thumb.webp', 'thumb'],
                800 => ['lg.webp', 'lg'],
                1280 => ['xl.webp', 'xl'],
            ], [
                'class' => 'image entry-thumb image-cover',
                'alt' => $image['alt'] ?? null,
                'loading' => 'lazy',
                'width' => 400,
                'height' => 400
            ]); ?>
        <?php endif ?>

        <?php if ( $map ) : ?>
            <?php echo orgnk_do_google_map_iframe( $map ) ?>
        <?php elseif ( $video ) : ?>
            <a class="video-button video-modal-trigger" href="<?php echo $video ?>"><?php esc_html_e( 'Play video', 'orgnk_client_textdomain' ) ?></a>
        <?php endif ?>

    </div>

<?php endif;

