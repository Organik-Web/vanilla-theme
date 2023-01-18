<?php
// This template part is a duplicate of the flexi sections call-to-action with different variables
// See: /template-parts/flex-sections/section-call-to-action.php

// Theme settings variables
$cta_active         = esc_html( get_option( 'options_global_cta_active' ) );
$title              = esc_html( get_option( 'options_global_cta_title' ) );
$title              = ( $title ) ? $title : 'Ready to get started?';
$text               = orgnk_get_the_content( get_option( 'options_global_cta_text' ) );
$buttons            = orgnk_do_cta_links();

if ( $cta_active ) : ?>

    <section class="section section-pad section-call-to-action section-light">
        <div class="container">
            <div class="section-wrap">

                <div class="section-content">
                    <div class="content-wrap">

                        <h2 class="title<?php echo orgnk_class_by_string_length( $title, 40, 'h1' ) ?>"><?php echo $title ?></h2>

                        <?php if ( $text ) : ?>
                            <div class="content">
                                <div class="editor-content">
                                    <div class="editor-content-wrap">
                                        <?php echo $text ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if ( $buttons ) echo $buttons ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif;
