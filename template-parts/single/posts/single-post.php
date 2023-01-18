<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package orgnk_client_textdomain
 */
?>

<article id="post-<?php the_ID() ?>" <?php post_class() ?>>

	<div class="editor-content">
		<div class="editor-content-wrap">
			<?php the_content() ?>
		</div>
	</div>

	<div class="entry-meta">
		<?php if ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && function_exists( 'orgnk_teams_author_avatar' ) && orgnk_teams_author_avatar() ) :
			echo orgnk_teams_author_avatar();
		else : ?>
			<div class="post-attributes">
				<span class="posted-on"><?php esc_html_e( 'Published on ' . get_the_date(), 'orgnk_client_textdomain' ) ?></span>
				<span class="categories"><?php esc_html_e( ' in ', 'orgnk_client_textdomain' ); echo get_the_term_list( get_the_ID(), 'category', '', ', ' ) ?></span>
			</div>
		<?php endif ?>
	</div>

</article>
