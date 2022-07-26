<?php
/**
 * Template part for displaying an event entry on the events archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$image                  = orgnk_get_image( get_post_thumbnail_id( get_the_ID() ) );
$venue_id               = esc_html( get_post_meta( orgnk_get_the_ID(), 'event_venue', true ) );

// Get venue post by ID
if ( $venue_id ) {
    $venue_name             = esc_html( get_the_title( $venue_id ) );
    $venue_address          = esc_html( get_post_meta( $venue_id, 'venue_address', true ) );
    $venue_suburb           = esc_html( get_post_meta( $venue_id, 'venue_suburb', true ) );
    $venue_post_code        = esc_html( get_post_meta( $venue_id, 'venue_post_code', true ) );
    $venue_region           = esc_html( get_post_meta( $venue_id, 'venue_region', true ) );
    $venue_country          = esc_html( get_post_meta( $venue_id, 'venue_country', true ) );
}
?>

<article id="event-<?php the_ID() ?>" <?php post_class( 'entry' ) ?>>
    <a class="entry-link" href="<?php esc_url( the_permalink() ) ?>" target="_self">
        <div class="entry-wrapper">

            <div class="ratio-sizer image-container">
                <picture>
                    <?php if ( $image ) : ?>
                        <?php if ( $image['medium']['url'] ) echo '<source srcset=" ' . $image['medium']['url'] . ' " media="(min-width: 400px)">'?>
                    <?php endif ?>
                    <img width="<?php echo $image['thumbnail']['width'] ?>"  height="<?php echo $image['thumbnail']['height'] ?>" class="entry-thumb image-cover" src="<?php if ( $image['thumbnail']['url'] ) echo $image['thumbnail']['url']; else  echo get_template_directory_uri() . '/images/default-thumb.svg' ?>"  alt="<?php if ( $image['alt'] ) echo $image['alt'] ?>"  loading="lazy" ></img>
                </picture>
                <?php if ( function_exists( 'orgnk_events_entry_first_date_badge' ) ) :
                    echo orgnk_events_entry_first_date_badge();
                endif ?>
            </div>

            <div class="entry-preview">
                <div class="entry-preview-content">
                    <h2 class="title"><?php esc_html( the_title() ) ?></h2>

                    <div class="meta" aria-hidden="true">

                        <?php if ( function_exists( 'orgnk_events_entry_badge_list' ) && orgnk_events_entry_badge_list() ) : ?>
                            <div class="event-attributes">
                                <?php echo orgnk_events_entry_badge_list() ?>
                            </div>
                        <?php endif ?>

                        <div class="event-overview">
                            <?php if ( function_exists( 'orgnk_events_entry_schedule' ) && orgnk_events_entry_schedule() ) :
                                echo orgnk_events_entry_schedule( $first = true );
                            endif ?>

                            <?php if ( function_exists( 'orgnk_events_entry_venue' ) && orgnk_events_entry_venue() ) :
                                echo orgnk_events_entry_venue( $short = true );
                            endif ?>
                        </div>

                        <?php if ( orgnk_auto_excerpt() ) : ?>
                            <div class="excerpt" aria-hidden="true"><?php echo orgnk_auto_excerpt() ?></div>
                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>
    </a>
</article>
