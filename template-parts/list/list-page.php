<?php
/**
 * Template part for displaying sub page entries on the auto landing template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$image = orgnk_get_image_meta( get_post_thumbnail_id( get_the_ID() ) );
?>

<article id="page-<?php the_ID() ?>" <?php post_class( 'entry' ); ?>>
    <a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
        <div class="entry-wrapper">

            <div class="picture-ratio-sizer">
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
