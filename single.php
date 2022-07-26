<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package orgnk_client_textdomain
 */

// Post meta variables
$hide_sidebar           = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_sidebar', true ) );
$hide_cta               = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

// Conditional variables
$is_events_post_type    = ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && ORGNK_EVENTS_CPT_NAME === get_post_type() );
$is_services_post_type  = ( defined( 'ORGNK_SERVICES_CPT_NAME' ) && ORGNK_SERVICES_CPT_NAME === get_post_type() );
$is_teams_post_type     = ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && ORGNK_TEAMS_CPT_NAME === get_post_type() );
$is_locations_post_type = ( defined( 'ORGNK_LOCATIONS_CPT_NAME' ) && ORGNK_LOCATIONS_CPT_NAME === get_post_type() );

$is_posts_post_type     = ( 'post' === get_post_type() ) ? true : false;
$sidebar_position       = ( $is_teams_post_type ) ? ' sidebar-left' : ' sidebar-right';

// Current post type name
$post_type_name         = orgnk_get_template_post_type();

get_header();
?>

<main id="<?php echo orgnk_skip_to_content_target() ?>" class="standard-page <?php echo 'single-' . $post_type_name ?>" role="main">

    <?php get_template_part( 'template-parts/global/entry-header' ) ?>

    <section class="section section-pad section-standard-page allow-overflow">
        <div class="container">
            <div class="section-wrap">
                <div class="standard-page-wrap<?php echo $sidebar_position ?>">

                    <?php while ( have_posts() ) : the_post();

                        if ( $is_events_post_type ) :
                            get_template_part( 'template-parts/single/events/single-event' );
                        elseif ( $is_services_post_type ) :
                            get_template_part( 'template-parts/single/services/single-service' );
                        elseif ( $is_teams_post_type ) :
                            get_template_part( 'template-parts/single/teams/single-team-member' );
                        elseif ( $is_locations_post_type) :
                            get_template_part( 'template-parts/single/pages/single-page' );
                        else :
                            get_template_part( 'template-parts/single/posts/single-post' );
                        endif;

                    endwhile ?>

                    <?php if ( ! $hide_sidebar && ! $is_teams_post_type  && ! $is_locations_post_type ) get_sidebar() ?>

                </div>
            </div>
        </div>
    </section>

    <?php if ( $is_teams_post_type ) :
        get_template_part( 'template-parts/single/teams/section-team-member-articles' );
    elseif ( $is_posts_post_type ) :
        get_template_part( 'template-parts/single/posts/section-related-articles' );
    endif ?>

</main>

<?php get_footer();
