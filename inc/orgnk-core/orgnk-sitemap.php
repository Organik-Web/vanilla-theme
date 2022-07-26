<?php
/**
 * orgnk_sitemap_list()
 * Lists site pages and custom post type posts, and their descendants
 * Does not include default posts as they tend to grow over time
*/
function orgnk_sitemap_list( $parent_heading_size = 'h4', $cpt_exludes= array() ) {
    $output         = null;
    $cpts           = get_post_types( array(), 'objects' );
    $cpt_archives   = array();
    $excludes       = array(
        get_the_ID(), // Exclude the sitemap page from itself
        intval( get_option( 'page_on_front' ) ), // Exclude the front page
        intval( get_option( 'page_for_posts' ) ), // Exclude the blog page
    );

    // Add pages using certain templates to the array of exclusions
    $templates_args = array(
        'post_type'         => 'page',
        'post_status'       => 'publish',
        'fields'            => 'ids',
        'numberposts'       => -1,
        'meta_key'          => '_wp_page_template',
        'meta_value'        => array(
            'templates/form-submission.php'
        )
    );
    $exclude_templates = get_posts( $templates_args );

    foreach ( $exclude_templates as $template ) {
        $excludes[] = intval( $template );
    }

    // For each post type with archive enabled, check if a dummy page has been assigned using the pages for CPT archive funcionality and it to a special array for cross-referencing later
    // Checks if a cpt is in the cpt_exclude array, if it is add it to the general excludes array else include it in the cpt_archives
    foreach ( $cpts as $cpt ) {
        if ( $cpt->has_archive && get_option( 'page_for_' . $cpt->name ) ) {
            if ( in_array( $cpt->name, $cpt_exludes, true ) ) {
                $excludes[] = intval( get_option( 'page_for_' . $cpt->name ) );
            } else {
                $cpt_archives[$cpt->name] = intval( get_option( 'page_for_' . $cpt->name ) );
            }
        }
    }

    // Gathering all top level pages
    $pages_args = array(
        'post_type'         => 'page',
        'post_status'       => 'publish',
        'sort_column'       => 'menu_order',
        'sort_order'        => 'ASC',
        'parent'            => 0, // No parent i.e. top level pages only
        'exclude'           => $excludes
    );
    $pages = get_pages( $pages_args );

    // If top level pages exist, then being output and recursively looping through their children
    if ( $pages ) {
        foreach ( $pages as $page ) {
            $output .= '<div class="sitemap-group type-page">';
            $output .= '<div class="group-parent">';
            $output .= '<a class="' . esc_html( $parent_heading_size ) . '" href="' . esc_url( get_permalink( $page->ID ) ) . '" target="_self">' . esc_html( $page->post_title ) . '</a>';
            $output .= '</div>';

            // If the current page ID matches any of the post type archive dummy pages, then find the matching page ID and
            // retrieve the array key containing the post type name so we can modify the paramaters passed to orgnk_sitemap_list_menu_items()
            if ( in_array( $page->ID , $cpt_archives, true ) ) {
                $post_type = array_search( $page->ID , $cpt_archives, true );
                $output .= orgnk_sitemap_list_menu_items( 0, $post_type, $excludes );
            } else {
                $output .= orgnk_sitemap_list_menu_items( $page->ID, 'page', $excludes );
            }

            $output .= '</div>';
        }
    }
    wp_reset_postdata();

    return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_sitemap_list_menu_items()
 * Recursively add child posts of a given top level parent post
*/
function orgnk_sitemap_list_menu_items( $parent_id = null, $post_type = null, $excludes = null, $depth = 0 ) {

    if ( ! $post_type ) {
		return false;
	}

    $output = null;
	$instantiator = ( $depth === 0 ) ? true : false;
	$depth++;

    $args = array(
        'post_type'         => $post_type,
        'post_parent'       => $parent_id,
        'post_status'       => 'publish',
        'sort_column'       => 'menu_order post_title',
        'sort_order'        => 'ASC',
        'exclude'           => $excludes,
        'fields' 			=> 'ids',
        'numberposts'       => -1
    );

    $args = apply_filters( 'orgnk_sitemap_get_posts_arguments', $args );

    // Get child posts of the current parent
    $children = get_posts( $args );

    if ( $children ) {

        if ( $instantiator ) {
            $output .= '<div class="group-children">';
            $output .= '<ul class="menu">';
        } else {
            $output .= '<ul class="sub-menu">';
        }

        foreach ( $children as $child ) {

            // Get child pages of the current (child) page - recursion happens here
            $has_children = orgnk_sitemap_list_menu_items( $child, $post_type, $excludes, $depth );

            // Setup unique classes for parent item and current item
            $menu_item_classes = '';

            if ( $has_children ) {
                $menu_item_classes .= ' menu-item-has-children';
            }

            $output .= '<li class="menu-item menu-item-' . $child . $menu_item_classes . ' depth-level-' . $depth . '">';
            $output .= '<a href="' . esc_url( get_permalink( $child ) ) . '">' . esc_html( get_the_title( $child ) ) . '</a>';

            // Add child pages
            if ( $has_children ) {
                $output .= $has_children;
            }

            $output .= '</li>';
        }

        $output .= '</ul>';

        if ( $instantiator ) {
            $output .= '</div>';
        }
    }
	return $output;
}
