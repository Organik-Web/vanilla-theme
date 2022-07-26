<?php
/**
 * Template part for displaying sub page entries on the auto landing template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$image = orgnk_get_image( get_post_thumbnail_id( get_the_ID() ) );
?>

<article id="page-<?php the_ID() ?>" <?php post_class( 'entry' ); ?>>
    <a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
        <div class="entry-wrapper">

            <div class="ratio-sizer image-container">
                <picture>
                    <?php if ( $image ) : ?>
                        <?php if ( $image['medium']['url'] ) echo '<source srcset=" ' . $image['medium']['url'] . ' " media="(min-width: 400px)">'?>
                    <?php endif ?>
                    <img width="<?php echo $image['thumbnail']['width'] ?>"  height="<?php echo $image['thumbnail']['height'] ?>" class="entry-thumb image-cover" src="<?php if ( $image['thumbnail']['url'] ) echo $image['thumbnail']['url']; else  echo get_template_directory_uri() . '/images/default-thumb.svg' ?>"  alt="<?php if ( $image['alt'] ) echo $image['alt'] ?>"  loading="lazy" ></img>
                </picture>
            </div>

            <div class="entry-preview">
                <div class="entry-preview-content">
                    <h2 class="title<?php echo orgnk_class_by_string_length( get_the_title(), 35, null, 'h3' ) ?>"><?php echo esc_html( get_the_title() ) ?></h2>

                    <?php if ( orgnk_auto_excerpt() ) : ?>
                        <div class="excerpt" aria-hidden="true"><?php echo orgnk_auto_excerpt() ?></div>
                    <?php endif ?>

                    <div class="actions" aria-hidden="true">
                        <div class="secondary-button"><?php esc_html_e( 'Read more', 'orgnk_client_textdomain' ) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</article>
