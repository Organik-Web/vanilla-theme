<?php
// Post meta variables
$meta_key 		= 'front_hero';
$image 			= orgnk_get_image( get_post_meta( FRONT_PAGE_ID, $meta_key . '_image', true ) );
$title 			= esc_html( get_post_meta( FRONT_PAGE_ID, $meta_key . '_title', true ) );
$subtitle 		= esc_html( get_post_meta( FRONT_PAGE_ID, $meta_key . '_subtitle', true ) );
$buttons        = orgnk_do_acf_button_group( $meta_key . '_buttons', 'primary-button', 'primary-button', true );

if ( $title ) : ?>

	<section class="section section-front-hero">
        <?php if ( $image  && function_exists( "orgnk_picture" ) ) :?>
            <?php orgnk_picture($image['id'], [
                0 => ['xl'],
                200 => ['xl-small.webp', 'xl-small'],
                1280 => ['xl.webp', 'xl'],
            ], [
                'class' => 'image hero-image',
                'alt' => $image['alt'] ?? null,

            ]); ?>
        <?php endif ?>
		<div class="overlay"></div>

		<div class="container">
			<div class="section-wrap">
				<div class="hero-content">

					<h1 class="title big"><?php echo $title ?></h1>

					<?php if ( $subtitle ) : ?>
						<span class="subtitle"><?php echo $subtitle ?></span>
					<?php endif; ?>

					<?php if ( $buttons ) echo $buttons ?>

				</div>
			</div>
		</div>
    </section>

<?php endif;
