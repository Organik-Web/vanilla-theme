<?php
// Post meta variables
$image              = orgnk_default_entry_header_image();
$title              = orgnk_get_entry_title();
$subtitle           = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_subtitle', true ) );
$simple             = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_header_simple', true ) );
$buttons            = orgnk_do_acf_button_group( 'entry_header_buttons', 'primary-button', 'primary-button', true );
$team_position      = esc_html( get_post_meta( orgnk_get_the_ID(), 'team_member_position', true ) );

// Conditional variables
$is_single_job      = ( defined( 'ORGNK_JOBS_CPT_NAME' ) && is_singular( ORGNK_JOBS_CPT_NAME ) ) ? true : false;
$is_single_event    = ( defined( 'ORGNK_EVENTS_CPT_NAME' ) && is_singular( ORGNK_EVENTS_CPT_NAME ) ) ? true : false;
$is_single_team     = ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && is_singular( ORGNK_TEAMS_CPT_NAME ) ) ? true : false;
$is_simple          = ( $simple || $is_single_team || ! $image ) ? true : false;

// Opening tag based on 'simple' setting
if ( $is_simple ) : ?>
    <section class="entry-header simple">
<?php else : ?>
    <section class="entry-header has-image"<?php if ( $image ) echo ' style="background-image: url(' . $image['full']['url'] . ');"' ?>>
    <div class="overlay"></div>
<?php endif ?>

    <div class="container">
        <div class="entry-header-wrap">
            <div class="entry-header-content">

                <h1 class="entry-title<?php echo orgnk_class_by_string_length( $title, 35, 'big' ) ?>"><?php echo $title ?></h1>

                <?php if ( $is_single_team && $team_position ) : ?>
                    <span class="team-member-position h4"><?php echo $team_position ?></span>
                <?php endif ?>

                <?php echo orgnk_breadcrumbs() ?>

                <?php if ( $subtitle ) : ?>
                    <span class="entry-subtitle"><?php echo $subtitle ?></span>
                <?php endif ?>

                <?php if ( ! $is_simple ) : ?>

                    <?php if ( $is_single_job && function_exists( 'orgnk_jobs_entry_apply_button' ) && orgnk_jobs_entry_apply_button() ) : ?>
                        <div class="actions">
                            <?php echo orgnk_jobs_entry_apply_button() ?>
                        </div>
                    <?php elseif ( $is_single_event && function_exists( 'orgnk_events_entry_tickets_button' ) && orgnk_events_entry_tickets_button() ) : ?>
                        <div class="actions">
                            <?php echo orgnk_events_entry_tickets_button() ?>
                        </div>
                    <?php elseif ( $buttons ) : ?>
                        <?php echo $buttons ?>
                    <?php endif ?>

                <?php endif ?>

            </div>
        </div>
    </div>
</section>
