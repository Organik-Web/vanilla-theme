<?php
/**
 * Template part for displaying page content in page.php
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
</article>
