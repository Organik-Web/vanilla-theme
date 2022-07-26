<?php
$args = array(
	'post_type' 		=> 'post',
	'post_status' 		=> 'publish',
	'orderby' 			=> 'publish_date',
	'order' 			=> 'DESC',
	'posts_per_page' 	=> -1,
	'meta_key' 			=> 'entry_team_author',
	'meta_value' 		=> get_the_ID()
);

$posts_loop = new WP_Query( $args );

if ( $posts_loop->post_count >= 3 && $posts_loop->have_posts()  ) : ?>

	<section class="section section-pad section-team-member-articles">
		<div class="container">
			<div class="section-wrap">

				<div class="section-header split">
					<div class="section-header-wrap">
					
						<div class="section-header-content">
							<span class="title h3"><?php esc_html_e( 'Articles by ' . esc_html( get_the_title() ), 'orgnk_client_textdomain' ) ?></span>
						</div>

						<div class="section-header-actions hide-mobile">
							<a class="secondary-button button-all-posts" href="<?php echo esc_url( get_permalink( PAGE_FOR_POSTS_ID ) ) ?>"><?php esc_html_e( 'View all articles', 'orgnk_client_textdomain' ) ?></a>
						</div>
				
					</div>
				</div>

				<div class="posts-list">
					<?php while ( $posts_loop->have_posts() ) : $posts_loop->the_post();
						get_template_part( 'template-parts/list/list-post' );
					endwhile; wp_reset_postdata() ?>
				</div>

				<div class="section-footer hide-desktop">
					<div class="section-footer-wrap">
						<div class="section-footer-actions">
							<a class="secondary-button button-all-posts" href="<?php echo esc_url( get_permalink( PAGE_FOR_POSTS_ID ) ) ?>"><?php esc_html_e( 'View all articles', 'orgnk_client_textdomain' ) ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
<?php endif;
