<?php
// Post meta variables
$meta_key 	= 'front_quick_links';
$count 		= esc_html( get_post_meta( FRONT_PAGE_ID, $meta_key, true ) );

if ( $count ) : ?>

	<section class="section section-pad section-front-quick-links">
		<div class="container">
			<div class="section-wrap">
				<div class="quick-links-list">

					<?php for( $i = 0; $i < $count; $i++ ) :

						// Repeater variables 
						$title = esc_html( get_post_meta( FRONT_PAGE_ID, $meta_key . '_' . $i . '_title', true ) );
						$text = esc_html( get_post_meta( FRONT_PAGE_ID, $meta_key . '_' . $i . '_text', true ) );
						$url = esc_url( get_permalink( get_post_meta( FRONT_PAGE_ID, $meta_key . '_' . $i . '_link', true ) ) );

						if ( $title && $text && $url ) : ?>

							<div class="quick-link link-<?php echo $i + 1 ?>">
								<a class="link" href="<?php echo $url ?>" target="_self">
									<div class="link-wrapper">
										<div class="link-icon"></div>
									
										<div class="link-preview">

											<h2 class="title h3"><?php echo $title ?></h2>

											<span class="subtitle" aria-hidden="true">
												<?php echo $text ?>
											</span>

										</div>
									</div>			
								</a>
							</div>
						<?php endif ?>
					<?php endfor ?>
				</div>
			</div>
		</div>
	</section>

<?php endif;
