<?php
/**
 * The template for displaying taxonomy archive pages
 *
 * If this client's version of the orgnk theme does not need taxonomy archives to be unique (i.e. display custom content or fields),
 * then you can delete this file and all archives will default to using archive.php
 * Note: the loop part of this template should be identical to archive.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_cta                   = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

// Conditional variables
$is_events_post_type        = ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && ORGNK_EVENTS_CPT_NAME === get_post_type() ) ? true : false;
$is_services_post_type      = ( defined( 'ORGNK_SERVICES_CPT_NAME' ) && ORGNK_SERVICES_CPT_NAME === get_post_type() ) ? true : false;
$is_teams_post_type         = ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && ORGNK_TEAMS_CPT_NAME === get_post_type() ) ? true : false;

// Current post type, taxonomy and term name
$post_type_name             = orgnk_get_template_post_type();
$current_taxonomy           = sanitize_title_with_dashes( strtolower( get_query_var( 'taxonomy' ) ) );
$current_term               = sanitize_title_with_dashes( strtolower( get_query_var( 'term' ) ) );

// Section classes based on current post type, taxonomy and term
$section_classes = array(
    $post_type_name . '-archive',
    'taxonomy-archive',
    $current_taxonomy . '-taxonomy-archive',
    'term-' . $current_term
);

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page <?php echo implode(' ', $section_classes) ?>" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <?php if ( $is_services_post_type ) : ?>
        <?php get_template_part( 'template-parts/archive/section-tax-archive-intro' ) ?>
    <?php endif ?>

    <?php if ( have_posts() ) : ?>

        <section class="section section-pad section-entry-list">
            <div class="container">
                <div class="section-wrap">
                    <div class="<?php echo $post_type_name . '-list' ?>">

                        <?php while ( have_posts() ) : the_post();

                            if ( $is_events_post_type ) :
                                get_template_part( 'template-parts/list/list-event' );
                            elseif ( $is_teams_post_type ) :
                                get_template_part( 'template-parts/list/list-team-member' );
                            else :
                                get_template_part( 'template-parts/list/list-post' );
                            endif;

                        endwhile ?>

                    </div>

                    <?php get_template_part( 'template-parts/global/pagination' ) ?>

                </div>
            </div>
        </section>

    <?php else : ?>

        <section class="section section-pad section-no-content">
            <div class="container">
                <div class="section-wrap">
                    <?php get_template_part( 'template-parts/global/no-content' ) ?>
                </div>
            </div>
        </section>

    <?php endif ?>

    <?php // Content or sections after loop here ?>

    <?php if ( ! $hide_cta ) get_template_part( 'template-parts/global/call-to-action' ) ?>

</main>

<?php get_footer();
