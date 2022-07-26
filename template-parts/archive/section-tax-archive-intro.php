<?php
// Post meta variables
$meta_key           = 'term_archive_overview';
$title              = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_title', true ) );

if ( $title ) : ?>

    <section class="section section-pad section-term-archive-intro">
        <div class="container">
            <div class="section-wrap">
                <h1><?php echo $title ?></h1>
            </div>
        </div>
    </section>

<?php endif;
