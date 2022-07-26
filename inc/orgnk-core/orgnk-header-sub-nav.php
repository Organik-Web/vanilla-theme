<?php
/**
 * orgnk_header_sub_nav()
 * Lists parent page and all direct child pages as a menu
 * This function allows the sub nav to be shown for CPT archives, however they must be using the
 * 'pages for CPT archive' functionality, because we rely on IDs for this function to work
 * Note: the default 'post' post type cannot be passed to the $cpt_archives variable
*/
function orgnk_header_sub_nav( $cpt_archives = array(), $mobile_display = 'dropdown', $breakpoint  = 768 ) {

	$output = null;

	// Setup the currently queried object
	$wp_the_query = $GLOBALS['wp_the_query'];
	$queried_object = sanitize_post( $wp_the_query->get_queried_object() );
	$post_type = get_post_type();
	$children = null;

	// Mobile display style
	if ( $mobile_display === 'swipe' ) {
		$mobile_display_class = 'mobile-swipe';
	} else {
		$mobile_display_class = 'mobile-dropdown';
	}

	// Get post type archive ID (returns null if the 'pages for CPT archive' functionality is not available or used since by default archives do not have IDs
	if ( 'post' == $post_type && PAGE_FOR_POSTS_ID ) {
		$archive_id = intval( PAGE_FOR_POSTS_ID );
	} elseif ( function_exists( 'orgnk_get_post_type_archive_id' ) && orgnk_get_post_type_archive_id( $post_type ) ) {
		$archive_id = orgnk_get_post_type_archive_id( $post_type );
	} else {
		$archive_id = null;
	}

	// Set the current page ID
	if ( $archive_id && ( is_archive() || is_home() ) ) {
		$current_page_id = $archive_id;
	} elseif ( is_singular() ) {
		$current_page_id = get_the_ID();
	} else {
		$current_page_id = null; // Page is a standard archive or special page (search/404 etc) therefore no ID, this will make this function return early
	}

	// Retrieve ancestors - if the current page is a single CPT, then get the ancestors of the archive ID including the archive ID itself
	if ( $archive_id && is_singular() ) {
		$ancestors = array_merge( array( $archive_id ), get_post_ancestors( $archive_id ) );
	} elseif ( $archive_id && is_archive() || is_home() ) {
		$ancestors = get_post_ancestors( $archive_id );
	} else {
		$ancestors = get_post_ancestors( $current_page_id );
	}

	// Find top level page ID
	if ( $ancestors ) {  // Check if page is a child page (any level)
		$parent_id = end( $ancestors ); // Grab the ID of top-level page from the tree
	} else {
		$parent_id = $current_page_id; // Page is the top level, so use it's own ID
	}

	// If we're supporting CPT archives, then the top level page will always be the archive ID, so we'll override the parent ID here
	if ( $cpt_archives && is_array( $cpt_archives ) ) {
		foreach ( $cpt_archives as $cpt ) {
			if ( $cpt === $post_type ) {
				$parent_id = $archive_id;
			}
		}
	}

	// Return early if no parent ID is set for any reason
	if ( ! $parent_id ) return false;

	// Setup args to get child posts of the current parent
	$child_args = array(
		'post_status'         => 'publish',
		'order'               => 'ASC',
		'orderby'             => 'menu_order post_title',
		'fields' 			  => 'ids',
		'numberposts'		  => -1
	);

	// If any CPT archives are supported, set the appropriate post type for the child args
	if ( $cpt_archives && is_array( $cpt_archives ) ) {
		foreach ( $cpt_archives as $cpt ) {
			if ( $cpt === $post_type ) {
				$child_args['post_type'] = $cpt;
			}
		}
	}

	// At this point, if a post type isn't set for the child args, then default to pages
	if ( ! isset( $child_args['post_type'] ) ) {
		$child_args['post_type'] = 'page';
		$child_args['post_parent'] = $parent_id;
	}

	// Get child pages of the current parent
	$children = get_posts( $child_args );

	// Check if there any child posts before proceeding with any HTML output
	if ( $children ) {

		$current_classes = orgnk_header_sub_nav_current_classes( $parent_id, $current_page_id, $ancestors );

		$output .= '<nav class="header-sub-nav ' . $mobile_display_class . '" aria-label="Section submenu" data-sub-nav-breakpoint="' . $breakpoint . '"  >';
		$output .= '<ul class="menu">';
		$output .= '<li class="menu-item menu-item-' . $parent_id . $current_classes . ' menu-item-has-children section-parent">';
		$output .= '<a class="parent-link" href="' . esc_url( get_permalink( $parent_id ) ) . '"' . ( $mobile_display === 'dropdown' ? ' aria-haspopup="true"' : '' ) . '><i class="current-marker" aria-hidden="true"></i>' . esc_html( get_the_title( $parent_id ) ) . '</a>';

		if ( $mobile_display === 'dropdown' ) {
			$output .= '<button class="toggle-sub-menu"><i class="icon" aria-hidden="true"></i><span class="screen-reader-text">Expand sub menu</span></button>';
		}

		$output .= '<ul class="sub-menu">';

		foreach ( $children as $child ) {

			$current_classes = orgnk_header_sub_nav_current_classes( $child, $current_page_id, $ancestors );

			// Create menu items
			$output .= '<li class="menu-item menu-item-' . $child . $current_classes . '">';
			$output .= '<a class="" href="' . esc_url( get_permalink( $child ) ) . '"><i class="current-marker" aria-hidden="true"></i>' . esc_html( get_the_title( $child ) ) . '</a>';
			$output .= '</li>';
		}

		$output .= '</ul>';
		$output .= '</li>';
		$output .= '</ul>';
		$output .= '</nav>';

	}

	// Check for output before returning
	if ( $output ) {
		return $output;
	} else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_header_sub_nav_current_classes()
 * Checks the supplied IDs match and assigns menu classes accordingly
 */
function orgnk_header_sub_nav_current_classes( $id = null, $current = null, $ancestors = null ) {

	$classes = null;

	if ( $id && $current && $id === $current ) {
		$classes .= ' current-menu-item';
	} elseif ( $ancestors && in_array( $id, $ancestors ) ) {
		$classes .= ' current-page-ancestor';
	}

	return $classes;
}

//=======================================================================================================================================================

/**
 * orgnk_header_sub_nav_body_class()
 * Add a class to the body if the header sub nav is being displayed
 */
function orgnk_header_sub_nav_body_class( $classes ) {

	$cpts = ( function_exists( 'orgnk_header_sub_nav_cpt_archives' ) ) ? orgnk_header_sub_nav_cpt_archives() : null;

	if ( orgnk_header_sub_nav( $cpts ) ) {
		$classes[] = 'has-sub-nav';
	}

	return $classes;
}
add_filter( 'body_class', 'orgnk_header_sub_nav_body_class' );
