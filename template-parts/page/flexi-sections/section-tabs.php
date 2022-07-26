<?php
// Return early if no variables have been passed to this template part via get_template_part()

if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;
$section_classes    = ( $args && isset( $args['section_classes'] ) ) ? $args['section_classes'] : null;
$section_is_boxed   = ( $args && isset( $args['section_is_boxed'] ) ) ? $args['section_is_boxed'] : null;

// Section meta variables
$section_id         = sanitize_title_with_dashes( esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_section_id', true ) ) );
$tab_count          = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_tabs', true ) );
?>

<section <?php if ( $section_id ) echo 'id="' . $section_id . '"' ?> class="section section-tabs<?php echo $section_classes ?>">
    <div class="container">
        <div class="section-wrap">

            <?php if ( $tab_count ) : ?>
                <div class="section-tab-content">
                    <div class="orgnk-tabs" data-tabs-transition="300" data-tabs-accordion="true" data-tabs-accordian-breakpoint="1024">
                        <ul class="tabs-list orgnk-tabs-content-area">
                            <?php for ( $i = 0; $i < $tab_count; $i++ ) :
                                // Repeater variables
                                $title          = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_tabs_' . $i . '_title', true ) );
                                $text           = orgnk_get_the_content( get_post_meta( orgnk_get_the_ID(), $meta_key . '_tabs_' . $i . '_text', true ) );
                                $button		    = orgnk_do_acf_button( $meta_key . '_tabs_' . $i . '_button', 'primary-button', false );
                                $tab_classes	= 'tab tab-' . ( $i + 1 );
                                $tab_classes	.= ( $i === 0 ) ? ' first' : null;
                                $tab_classes	.= ( $i + 1 == $tab_count ) ? ' last' : null;

                                if ( $title && $text ) : ?>
                                    <li class="<?php echo $tab_classes ?>">
                                        <button id="tab-<?php echo $i + 1 ?>" tab-target="tab-<?php echo $i + 1 ?>-content" class="orgnk-tab orgnk-tab-trigger h3" aria-expanded="false" aria-controls="tab-<?php echo $i + 1 ?>-content">
                                            <div class="button-inner">
                                                <span class="button-label h3"><?php echo $title ?></span>
												<i class="accordion-arrow" aria-hidden="true"></i>
                                            </div>
                                        </button>

                                        <div id="tab-<?php echo $i + 1 ?>-content" class="orgnk-tab-target tab-content" role="region" aria-labelledby="tab-<?php echo $i + 1 ?>" aria-hidden="true">
                                            <div class="section-content">
                                                <div class="content-wrap">

                                                    <div class="content">
                                                        <div class="editor-content">
                                                            <div class="editor-content-wrap">
                                                                <?php echo $text ?>
                                                            </div>
                                                        </div>
                                                        <?php if ( $button ) echo $button ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif ?>
                            <?php endfor ?>
                        </ul>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>
