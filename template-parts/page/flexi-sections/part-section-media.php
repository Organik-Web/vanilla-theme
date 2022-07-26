<?php
// Return early if no variables have been passed to this template part via get_template_part()
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;

// Section meta variables
$image              = orgnk_get_image( get_post_meta( orgnk_get_the_ID(), $meta_key . '_image', true ) );
$video              = esc_url( get_post_meta( orgnk_get_the_ID(), $meta_key . '_video', true ) );
$map                = esc_url( get_post_meta( orgnk_get_the_ID(), $meta_key . '_map', true ) );

if ( $image || $video || $map ) : ?>


    <div class="ratio-sizer image-container">
        <picture>
            <?php if ( $image['full']['url'] ) echo '<source srcset=" ' . $image['full']['url'] .  '" media="(min-width: 1024px)">'?>
            <?php if ( $image['large']['url'] ) echo '<source srcset=" ' . $image['large']['url'] . ' " media="(min-width: 768px)">'?>
            <?php if ( $image['medium']['url'] ) echo '<source srcset=" ' . $image['medium']['url'] . ' " media="(min-width: 400px)">'?>
            <img width="<?php echo $image['thumbnail']['width'] ?>"  height="<?php echo $image['thumbnail']['height'] ?>" class="image image-cover" src="<?php if ( $image['thumbnail']['url'] ) echo $image['thumbnail']['url']; else  echo get_template_directory_uri() . '/images/default-thumb.svg' ?>"  alt="<?php if ( $image['alt'] ) echo $image['alt'] ?>" loading="lazy" ></img>
        </picture>

        <?php if ( $map ) : ?>
            <?php echo orgnk_do_google_map_iframe( $map ) ?>
        <?php elseif ( $video ) : ?>
            <a class="video-button video-modal-trigger" href="<?php echo $video ?>"><?php esc_html_e( 'Play video', 'orgnk_client_textdomain' ) ?></a>
        <?php endif ?>

    </div>

<?php endif;

