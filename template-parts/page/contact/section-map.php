<?php
// Post meta variables
$meta_key			        = 'contact';
$map                        = esc_url( get_post_meta( orgnk_get_the_ID(), $meta_key . '_map', true ) );

if ( $map ) : ?>

    <section class="section section-pad-bottom section-light section-contact-map">
        <div class="container">
            <div class="section-wrap">
                <div class="section-map">
                    <?php echo orgnk_do_google_map_iframe( $map ) ?>
                </div>
            </div>
        </div>
    </section>

<?php endif;
