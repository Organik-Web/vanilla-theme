<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_cta               = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

// Conditional variables
$is_jobs_post_type      = ( defined( 'ORGNK_JOBS_CPT_NAME' ) && ORGNK_JOBS_CPT_NAME === get_post_type() ) ? true : false;
$is_events_post_type    = ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && ORGNK_EVENTS_CPT_NAME === get_post_type() ) ? true : false;
$is_services_post_type  = ( defined( 'ORGNK_SERVICES_CPT_NAME' ) && ORGNK_SERVICES_CPT_NAME === get_post_type() ) ? true : false;
$is_teams_post_type     = ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && ORGNK_TEAMS_CPT_NAME === get_post_type() ) ? true : false;
$is_locations_post_type = ( defined( 'ORGNK_LOCATIONS_CPT_NAME' ) && ORGNK_LOCATIONS_CPT_NAME === get_post_type() ) ? true : false;


// Current post type name
$post_type_name         = orgnk_get_template_post_type();

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="full-width-page <?php echo $post_type_name . '-archive' ?>" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <?php // Content or sections before loop here ?>

    <?php if ( have_posts() ) : ?>

        <section class="section section-pad section-entry-list">
            <div class="container">
                <div class="section-wrap">
                    <div class="<?php echo $post_type_name . '-list' ?>">

                        <?php while ( have_posts() ) : the_post();

                            if ( $is_jobs_post_type ) :
                                get_template_part( 'template-parts/list/list-job' );
                            elseif ( $is_locations_post_type ) :
                                get_template_part( 'template-parts/list/list-location' );
                            elseif ( $is_events_post_type ) :
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
