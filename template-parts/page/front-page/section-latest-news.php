<?php
// Post meta variables
$title		= esc_html( get_post_meta( FRONT_PAGE_ID, 'front_latest_news_title', true ) );

$args = array(
	'post_type' 		=> 'post',
	'post_status' 		=> 'publish',
	'orderby' 			=> 'date',
	'order' 			=> 'DESC',
	'posts_per_page' 	=> 3
);
$posts_loop = new WP_Query( $args );

if ( $posts_loop->post_count >= 3 && $posts_loop->have_posts() ) : ?>

	<section class="section section-pad section-front-blog">
		<div class="container">
			<div class="section-wrap">

				<div class="section-header split">
					<div class="section-header-wrap">
					
						<div class="section-header-content">
							<h1 class="title"><?php echo ( $title ) ? $title : esc_html_e( 'Latest news', 'orgnk_client_textdomain' ) ?></h1>
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
