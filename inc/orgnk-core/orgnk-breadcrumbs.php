<?php
/**
 * orgnk_breadcrumbs()
 */
function orgnk_breadcrumbs() {

	// Setup the currently queried object
	$wp_the_query = $GLOBALS['wp_the_query'];
	$queried_object = sanitize_post( $wp_the_query->get_queried_object() );

	// Class for current breadcrumb item
	$current_class = 'current-page';

	// Get all custom post types
	$custom_post_types = get_post_types( array( '_builtin' => false ), 'names' );

	// Get the queried object post type
	// This is important to do first since the queried object could be an archive, taxonomy or term object, which means get_post_type would not work
	if ( is_home() ) {
		$post_type = 'post'; // attempting to get the post type on the blog/index page will return 'page' instead of 'post', so we have to set it manually
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$taxonomy_object = get_taxonomy( $queried_object->taxonomy );
		$post_type = $taxonomy_object->object_type[0]; // The first/primary post type is preferenced here to avoid taxonomies that may belong to multiple post types
	} elseif ( is_archive() ) {
		$post_type = $queried_object->name; // archives must use the query var 'name' because get_post_type() returns false on archives with no posts
	} else {
		$post_type = get_post_type();
	}

	// Get post type archive ID (returns null if the 'pages for CPT archive' functionality is not available or used since by default archives do not have IDs)
	if ( 'post' == $post_type && defined( 'PAGE_FOR_POSTS_ID' ) ) {
		$archive_id = PAGE_FOR_POSTS_ID;
	} elseif ( function_exists( 'orgnk_get_post_type_archive_id' ) && orgnk_get_post_type_archive_id( $post_type ) ) {
		$archive_id = orgnk_get_post_type_archive_id( $post_type );
	} else {
		$archive_id = null;
	}

	// Get the post type archive title if an archive page ID exists, otherwise default to the post type name
	if ( $archive_id ) {
		$archive_label = esc_html( get_the_title( $archive_id ) );
	} else {
		$archive_label = esc_html( get_post_type_object( $post_type )->labels->name );
	}

	// Retrieve the current post type's archive permalink, checking for WooCommerce product archive first
	if ( 'product' == $post_type && function_exists( 'wc_get_page_id' ) ) {
		$archive_url = esc_url( get_permalink( wc_get_page_id( 'shop' ) ) );
	} elseif ( $archive_id ) {
		$archive_url = esc_url( get_permalink( $archive_id ) );
	} else {
		$archive_url = esc_url( get_post_type_archive_link( $post_type ) );
	}

	// 1. Start HTML output
	$output  = '<nav class="breadcrumbs" aria-label="Breadcrumbs">';
	$output .= '<ul class="crumbs">';

	// Add the home link if not currently on the front page
	if ( ! is_front_page() ) {
		$output .= '<li class="crumb home"><a href="' . esc_url( get_home_url() ) . '"><span>Home</span></a></li>';
	}

	// 2. Handle all single posts which includes single pages, posts and attatchments but excluding the front page
	if ( is_singular() ) {

		$post_title = $queried_object->post_title;
		$post_parent = $queried_object->post_parent;
		$post_type = $queried_object->post_type;
		$post_id = $queried_object->ID;

		// Handle any custom post types	plus default posts that have archives
		if ( in_array( $post_type, $custom_post_types ) || 'post' === $post_type ) {

			// Pages above the archive - for all post types using the 'pages for CPT archive' functionality and default posts
			if ( $archive_id ) {
				$ancestors = array_reverse( get_post_ancestors( $archive_id ) );
				foreach ( $ancestors as $ancestor ) {
					$output .= '<li class="crumb"><a href="' . esc_url( get_permalink( $ancestor ) ) . '"><span>' . esc_html( get_the_title( $ancestor ) ) . '</span></a></li>';
				}
			}

			// The archive link
			$output .= '<li class="crumb"><a href="' . $archive_url . '"><span>' . $archive_label . '</span></a></li>';
		}

		// Any ancestors above the current post but below the archive - for all post types including pages
		if ( $post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post_id ) );
			foreach ( $ancestors as $ancestor ) {
				$output .= '<li class="crumb"><a href="' . esc_url( get_permalink( $ancestor ) ) . '"><span>' . esc_html( get_the_title( $ancestor ) ) . '</span></a></li>';
			}
		}

		// Finally, the current post as last item in the breadcrumb trail
		$output .= '<li class="crumb ' . $current_class . '"><span>' . esc_html( get_the_title() ) . '</span></li>';
	}

	// 3. Handle archives including taxonomy, tag, date, CPT and author archives
	if ( is_archive() || is_home() ) {

		// Pages above the archive - covers post types using the 'pages for CPT archive' functionality and default posts
		if ( $archive_id ) {
			$ancestors = array_reverse( get_post_ancestors( $archive_id ) );
			foreach ( $ancestors as $ancestor ) {
				$output .= '<li class="crumb"><a href="' . esc_url( get_permalink( $ancestor ) ) . '"><span>' . esc_html( get_the_title( $ancestor ) ) . '</span></a></li>';
			}
		}

		// Handle taxonomies, terms and tags
		if ( is_category() || is_tag() || is_tax() ) {

			// Set the variables for handling categories, terms and tags
			$term_object        = get_term( $queried_object );
			$term_id            = $term_object->term_id;
			$term_name          = $term_object->name;
			$term_parent        = $term_object->parent;
			$term_taxonomy      = $term_object->taxonomy;

			// The archive link
			$output .= '<li class="crumb"><a href="' . $archive_url . '"><span>' . $archive_label . '</span></a></li>';

			// Check for term parents
			if ( $term_parent ) {
				$ancestors = array_reverse( get_ancestors( $term_id, $term_taxonomy ) );
				foreach ( $ancestors as $ancestor ) {
					$ancestor_object = get_term( $ancestor, $term_taxonomy );
					$output .= '<li class="crumb"><a href="' . esc_url( get_term_link( $ancestor ) ) . '"><span>' . esc_html( $ancestor_object->name ) . '</span></a></li>';
				}
			}

			// The current term
			$output .= '<li class="crumb ' . $current_class . '"><span>' . esc_html( $term_name ) . '</span></li>';
		}

		// Handle author archives
		elseif ( is_author() ) {

			// The archive link
			$output .= '<li class="crumb"><a href="' . $archive_url . '"><span>' . $archive_label . '</span></a></li>';

			// The current author
			$output .= '<li class="crumb ' . $current_class . '"></span>Author archive</span></li>';
		}

		// Handle date archives
		elseif ( is_date() ) {

			// The archive link
			$output .= '<li class="crumb"><a href="' . $archive_url . '"><span>' . $archive_label . '</span></a></li>';

			if ( is_day() ) {
				$output .= '<li class="crumb ' . $current_class . '"><span>Archive for ' . esc_html( get_the_time('F jS, Y') ) . '</span></li>';
			} elseif ( is_month() ) {
				$output .= '<li class="crumb ' . $current_class . '"><span>Archive for ' .  esc_html( get_the_time('F,Y') ) . '</span></li>';
			} elseif ( is_year() ) {
				$output .= '<li class="crumb ' . $current_class . '"><span>Archive for ' . esc_html( get_the_time('Y') ) . '</span></li>';
			}
		}

		// Otherwise, we're on the main archive itself
		else {

			// Default posts archive
			if ( is_home() && defined( 'PAGE_FOR_POSTS_ID' ) ) {
				$output .= '<li class="crumb ' . $current_class . '"><span>' . esc_html( get_the_title( PAGE_FOR_POSTS_ID ) ) . '</span></li>';
			}

			// All other post archives
			else {
				$output .= '<li class="crumb ' . $current_class . '"><span>' . $archive_label . '</span></li>';
			}
		}
	}

	// 4. Other unique scenarios
	elseif ( is_attachment() && is_single() ) {
		$output .= '<li class="crumb ' . $current_class . '"><span>Attachment: ' . esc_html( get_the_title() ) . '</span></li>';
	}	elseif ( is_search() ) {
		$output .= '<li class="crumb ' . $current_class . '"><span>Search results</span></li>';
	} elseif ( is_404() ) {
		$output .= '<li class="crumb ' . $current_class . '"><span>Page not found</span></li>';
	}

	// 5. Close opening tags
	$output .= '</ul>';
	$output .= '</nav>';

	// Output the markup everywhere except the front page
	if ( ! is_front_page() ) {
		return $output;
	} else {
		return false;
	}
}
