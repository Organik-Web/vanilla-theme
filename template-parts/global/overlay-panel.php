<?php
$form_id = get_option( 'options_enquiry_form_id' );
?>

<div class="overlay-panel">
    <div class="panel-wrap">
        <div class="mobile-menu-panel">
            <div class="panel-scroll">
                <div class="container">
                    <nav class="mobile-menu" data-transition-delay="0.1" aria-label="Mobile menu">
                        <?php
                        wp_nav_menu( array(
                            'container'         => false,
                            'theme_location'    => 'main-menu',
                            'menu_class'        => 'menu',
                            'fallback_cb'       => false,
                            'depth'             => 2,
                            'link_before'       => '<i class="current-marker" aria-hidden="true"></i>'
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        </div>

        <?php if ( orgnk_has_enquiry_form() ) : ?>
            <div class="enquiry-panel">
                <div class="panel-scroll">
                    <div id="enquiry-form" class="enquiry-form">
                        <?php get_template_part( 'template-parts/page/contact/section-contact-general', null, array( 'form_id' => $form_id , 'class' => 'mobile-overlay') ) ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
