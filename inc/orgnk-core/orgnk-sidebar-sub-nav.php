<?php
/**
 * orgnk_sidebar_sub_nav()
 * Lists parent page and all descendant pages
 * Use wp_list_pages to display parent and all child pages
 * http://codex.wordpress.org/Template_Tags/wp_list_pages#List_Sub-Pages*
*/
function orgnk_sidebar_sub_nav( $parent_heading_size = 'h3' ) {

	global $post;

	if ( ! $post ) {
		return;
	}

	$output = null;
	$current_page_id = $post->ID;
	$child_pages = null;
	$cpt_page_id = null;
	$parent_selected = null;
	$ancestors = get_post_ancestors( $current_page_id );

	// Check if page is a child page (any level)
	if ( $ancestors ) {
		$parent = end( $ancestors ); // Grab the ID of top-level page from the tree
	} else {
		$parent = $current_page_id; // Page is the top level, so use it's own id
	}

	if ( ! $parent ) return;

	$child_pages = wp_list_pages( array(
		'sort_column'		=> 'menu_order post_title',
		'child_of'			=> $parent,
		'title_li'			=> 0,
		'echo'				=> 0
	) );
	$parent_page = get_post( $parent );

	// Handle custom post types
	$cpt_object = get_post_type_object( get_post_type() );

	if ( 'page' != $cpt_object->name ) {
		$slug = $cpt_object->rewrite ? $cpt_object->rewrite['slug'] : null;

		if ( $slug && strpos( $slug, '/' ) !== false ) {
			$parts = explode( '/', $slug );
			$parent = get_page_by_path( $parts[0] );

			if ( $parent->ID ) {
				$parent = $parent->ID;
				$current_page = get_page_by_path( $slug );

				if ( isset( $current_page->ID ) ) {
					$cpt_page_id = $current_page->ID;
				}
			}
		}
	}

	if ( $child_pages ) {

		if ( $post->ID == $parent_page->ID ) {
			$parent_selected = ' current-menu-item';
		}

		$output .= '<nav class="sidebar-menu" aria-label="Sidebar menu">';
		$output .= '<ul class="menu">';
		$output .= '<li class="menu-item menu-item-' . $parent . ' parent-menu-item' . $parent_selected . '"><a class="' . $parent_heading_size . '" href="'. esc_url( get_permalink( $parent ) ) . '" target="_self"><i class="current-marker" aria-hidden="true"></i>' . esc_html( $parent_page->post_title ) . '</a>';
		$output .= orgnk_sidebar_sub_nav_menu_items( $parent, $current_page_id );
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




/**
 * orgnk_sidebar_sub_nav_menu_items()
 * Recursively add child pages of a given top level parent page to provide toggle button functionality for nested page sets
 */
function orgnk_sidebar_sub_nav_menu_items( $parent_id = null, $current_page_id = 0, $depth = 0 ) {

	if ( ! $parent_id ) {
		return false;
	}

	$output = null;
	$instantiator = ( $depth === 0 ) ? true : false;
	$ancestors = get_post_ancestors( $current_page_id );
	$depth++;

	// Prevent recursion going deeper than 4 levels
	if ( $depth < 4 ) {

		// Get child pages of the current parent
		$children = get_pages( array(
			'parent' 		=> $parent_id,
			'sort_column' 	=> 'menu_order post_title'
		) );

		if ( $children ) {

			// Don't include this stuff for the top level parent
			if ( ! $instantiator ) {
				$output .= '<button class="toggle-sub-menu"><i class="icon" aria-hidden="true"></i><span class="screen-reader-text">Expand sub menu</span></button>';
				$output .= '<ul class="sub-menu">';
			}

			foreach( $children as $child ) {

				// Get child pages of the current (child) page - recursion happens here
				$has_children = orgnk_sidebar_sub_nav_menu_items( $child->ID, $current_page_id, $depth );

				// Setup unique classes for parent item and current item
				$menu_item_classes = '';

				if ( $child->ID === $current_page_id ) {
					$menu_item_classes .= ' current-menu-item';
				} elseif ( $ancestors && in_array( $child->ID, $ancestors ) ) {
					$menu_item_classes .= ' current-page-ancestor';
				}

				if ( $has_children ) {
					$menu_item_classes .= ' menu-item-has-children';
				}

				$output .= '<li class="menu-item menu-item-' . $child->ID . $menu_item_classes . ' depth-level-' . $depth . '">';
				$output .= '<a href="' . esc_url( get_permalink( $child->ID ) ) . '" target="_self"';

				if ( $has_children ) {
					$output .= ' aria-haspopup="true"';
				}

				$output .= '><i class="current-marker" aria-hidden="true"></i>' . esc_html( $child->post_title ) . '</a>';

				// Add child pages
				if ( $has_children ) {
					$output .= $has_children;
				}

				$output .= '</li>';
			}

			// Don't include this stuff for the top level parent
			if ( ! $instantiator ) {
				$output .= '</ul>';
			}
		}
	}
	return $output;
}
