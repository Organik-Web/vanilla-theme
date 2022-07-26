<?php
$write_review   = ( function_exists( 'orgnk_greviews_do_write_review_button' ) ) ? orgnk_greviews_do_write_review_button() : null;
$view_reviews   = ( function_exists( 'orgnk_greviews_do_view_reviews_button' ) ) ? orgnk_greviews_do_view_reviews_button( 'secondary-button ') : null;

if ( function_exists( 'orgnk_greviews_has_reviews' ) && orgnk_greviews_has_reviews() ) : ?>

    <section class="section section-pad section-google-reviews">
        <div class="container">
            <div class="section-wrap">

                <?php echo orgnk_greviews_do_reviews_list( 'slider' ) ?>

                <?php if ( $write_review || $view_reviews ) : ?>
                    <div class="actions">
                        <div class="button-group">
                            <?php echo $write_review ?>
                            <?php echo $view_reviews ?>
                        </div>
                    </div>
                <?php endif ?>

            </div>
        </div>
    </section>

<?php endif;
