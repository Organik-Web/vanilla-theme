<?php
/**
 * Template Name: Auto Landing
 * Description: A landing page template that loops through all of the page's children and displays them hierarchically
 *
 * @package orgnk_client_textdomain
 */

// Setup child posts loop
$args = array(
    'post_parent'       => $post->ID,
    'post_type'         => 'page',
    'post_status'       => 'publish',
    'orderby'           => 'menu_order',
    'order'             => 'ASC',
    'posts_per_page'    => -1
);
$children = new WP_Query( $args );

// Post meta variables
$hide_cta = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page auto-landing-page" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <?php if ( $children->have_posts() ) : ?>

        <section class="section section-pad section-entry-list">
            <div class="container">
                <div class="section-wrap">
                    <div class="pages-list">

                        <?php while ( $children->have_posts() ) : $children->the_post();
                            get_template_part( 'template-parts/list/list-page' );
                        endwhile; wp_reset_postdata() ?>

                    </div>
                </div>
            </div>
        </section>

    <?php endif ?>

    <?php if ( ! $hide_cta ) get_template_part( 'template-parts/global/call-to-action' ) ?>

</main>

<?php get_footer();
