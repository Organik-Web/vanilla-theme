<?php
//=======================================================================================================================================================
// Organik theme modifications via filters & hooks
//=======================================================================================================================================================

/**
 * orgnk_body_classes()
 * Add custom classes to the array of default body classes
 */
function orgnk_body_classes( $classes ) {

	global $post;

	// 404 page
	if ( is_404() ) {
		$classes[] = 'is-404-page';
	}

	// Front page
	if ( is_front_page() ) {
		$classes[] = 'is-front-page';
	}

	// All inner pages
	if ( ! is_front_page() ) {
		$classes[] = 'is-inner-page';
	}

	// Parent section slug as body class
	if ( is_page() ) {

		if ( $post->post_parent ) {
			$post_data = get_post( $post->post_parent );
			$classes[] = $post_data->post_name;
		} else {
			$post_data = get_post( $post );
			$classes[] = $post_data->post_name;
		}
	}

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'is-group-blog';
	}

	// Adds a class of hfeed to non-singular pages
	if ( ! is_singular() ) {
		$classes[] = 'is-hfeed';
	}

	if ( function_exists( 'orgnk_is_gutenberg_active' ) && orgnk_is_gutenberg_active() ) {
		$classes[] = 'is-gutenberg';
	} else {
		$classes[] = 'is-classic-editor';
	}

	return $classes;
}
add_filter( 'body_class', 'orgnk_body_classes' );

//=======================================================================================================================================================

/**
 * orgnk_custom_login_css()
 * Enqueue a custom stylesheet for the login page
 */
function orgnk_custom_login_css() {
	echo '<link rel="stylesheet" type="text/css" href="'  .get_template_directory_uri() . '/css/login.min.css" />';
}
add_action( 'login_head', 'orgnk_custom_login_css' );

/**
 * orgnk_login_logo_url()
 * Set the logo link on the login page to be the website's home URL
 */
function orgnk_login_logo_url() {
	return esc_url( home_url() );
}
add_filter( 'login_headerurl', 'orgnk_login_logo_url' );

/**
 * orgnk_login_logo_url_title()
 * Set the logo link's title on the login page to be the name of the website
 */
function orgnk_login_logo_url_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'orgnk_login_logo_url_title' );

//=======================================================================================================================================================

/**
 * orgnk_change_admin_post_label()
 * Rename 'Post' labels in admin to use another term
 * Note: this is purely cosmetic and does not modify the default 'post' post type in any way
 */
function orgnk_change_admin_post_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'Blog';
	$submenu['edit.php'][5][0] = 'All posts';
	$submenu['edit.php'][10][0] = 'Add new post';
}
add_action( 'admin_menu', 'orgnk_change_admin_post_label' );

//=======================================================================================================================================================

/**
 * orgnk_change_post_tax_labels()
 * Change the default post taxonomy labels to be more specific in admin to avoid multiple post types all having the same 'Categories' label
 */
function orgnk_change_post_tax_labels() {

	global $wp_taxonomies;
	$labels = &$wp_taxonomies['category']->labels;

	$labels->name = 'Post Categories';

}
add_action( 'init', 'orgnk_change_post_tax_labels' );

//=======================================================================================================================================================

/**
 * orgnk_disable_comment_url()
 * Never allow a URL on the comments form
 */
function orgnk_disable_comment_url( $fields ) {
    unset( $fields['url'] );
    return $fields;
}
add_filter( 'comment_form_default_fields', 'orgnk_disable_comment_url' );

//=======================================================================================================================================================

/**
 * orgnk_excerpt_more()
 * Filter the excerpt 'read more' string
 */
function orgnk_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'orgnk_excerpt_more' );

//=======================================================================================================================================================

/**
 * orgnk_excerpt_length()
 * Modifies the number of words in an excerpt
 */
function orgnk_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'orgnk_excerpt_length' );

//=======================================================================================================================================================

/**
 * orgnk_custom_oembed_filter()
 * Wraps video player code in a responsive container for video embeds
 */
function orgnk_custom_oembed_filter( $html, $url, $attr, $post_ID ) {
	$return = '<div class="video-container">' . $html . '</div>';
	return $return;
}
add_filter( 'embed_oembed_html', 'orgnk_custom_oembed_filter', 10, 4 );

//=======================================================================================================================================================

/**
 * orgnk_media_allow_svg()
 * Allow SVGs to be uploaded to the media library
 */
function orgnk_media_allow_svg( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'orgnk_media_allow_svg' );

//=======================================================================================================================================================

/**
 * orgnk_search_results_count()
 * Overrides the default posts per page setting in the reading settings and allows us to set a unique posts per page number for search results
 */
function orgnk_search_results_count( $query ) {
	if ( $query->is_search && SEARCH_POSTS_PER_PAGE ) {
		$query->set( 'posts_per_page', SEARCH_POSTS_PER_PAGE );
	}
	return $query;
}
add_filter( 'pre_get_posts', 'orgnk_search_results_count' );

//=======================================================================================================================================================

/**
 * Stop Wordpress adding inline width to images with captions in the content editor
 */
add_filter( 'img_caption_shortcode_width', '__return_zero' );

//=======================================================================================================================================================

/**
 * orgnk_audio_player_classes()
 * Add classes to the default audio player for custom styling
 */
function orgnk_audio_player_classes( $class ) {
	$class .= ' orgnk-audio-player';
	return $class;
}
add_filter( 'wp_audio_shortcode_class', 'orgnk_audio_player_classes', 1, 1 );

//=======================================================================================================================================================

/**
 * orgnk_admin_pages_order_column()
 * Add menu order as a custom column in admin
 */
function orgnk_admin_pages_order_column_head( $defaults ) {
	$newOrder = array();
	foreach ( $defaults as $key => $value ) {
		if ( $key == 'author' ) {  // When we find the author column
			$newOrder['menu_order'] = 'Order'; // Slip in the new column before it
		}
		$newOrder[$key] = $value;
	}
	return $newOrder;
}
add_filter( 'manage_pages_columns', 'orgnk_admin_pages_order_column_head' );

/**
 * orgnk_admin_pages_order_column_content()
 * Add the entries menu order to the column
 */
function orgnk_admin_pages_order_column_content( $column_name, $post_id ) {

    global $post;

	if ( $column_name == 'menu_order' ) {
		echo $post->menu_order;
	}
}
add_action( 'manage_pages_custom_column', 'orgnk_admin_pages_order_column_content', 10, 2 );

/**
 * orgnk_admin_pages_order_column_sortable()
 * Make the menu order column sortable
 */
function orgnk_admin_pages_order_column_sortable( $columns ) {
	$columns['menu_order'] = 'menu_order';
	return $columns;
}
add_filter( 'manage_edit-page_sortable_columns', 'orgnk_admin_pages_order_column_sortable');

//=======================================================================================================================================================

/**
 * orgnk_remove_default_image_sizes()
 * Remove the 2 new image sizes added in WP 5.3
 */
function orgnk_remove_default_image_sizes( $sizes ) {
	unset( $sizes['medium_large'] );
    unset( $sizes['1536x1536'] );
    unset( $sizes['2048x2048'] );
    return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'orgnk_remove_default_image_sizes' );

//=======================================================================================================================================================

/**
 * orgnk_big_image_size_threshold()
 * Set a new minimum size limit for the new WP 5.3 automatic image scaler
 */
function orgnk_big_image_size_threshold( $imagesize, $file, $attachment_id ) {
	return 4000; // Max image width
}
add_filter( 'big_image_size_threshold', 'orgnk_big_image_size_threshold', 10, 3 );

//=======================================================================================================================================================

/**
 * orgnk_add_post_mime_types()
 * Add PDFs to the filter options in media library
 */
function orgnk_add_post_mime_types( $post_mime_types ) {

	// Select the mime type then define an array with the label values
	$post_mime_types['application'] = array( 'Document', 'Manage Documents', _n_noop( 'Document <span class="count">(%s)</span>', 'Documents <span class="count">(%s)</span>' ) );

	return $post_mime_types;
}
add_filter( 'post_mime_types', 'orgnk_add_post_mime_types' );

//=======================================================================================================================================================

/**
 * orgnk_disable_search_query()
 * Disable front-end search functionality and route any direct or indirect search queries to a 404
 */
function orgnk_disable_search_query( $query ) {
	if ( ! is_admin() ) {
		if ( $query->is_search ) {
			unset( $_GET['s'], $_POST['s'], $_REQUEST['s'] );
			$query->set( 's', '' );
			$query->is_search = false;
			$query->set_404();
			header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
		}
	}
}

/**
 * orgnk_disable_search_widget()
 * Disable the built-in search widget
 */
function orgnk_disable_search_widget() {
	unregister_widget( 'WP_Widget_Search' );
}

/**
 * Add callbacks for the above if the SUPPORTS_SEARCH variable is set to false
 */
if ( defined( 'SUPPORTS_SEARCH' ) && SUPPORTS_SEARCH === false ) {

	// Disable front-end search functionality and route any direct or indirect search queries to a 404
	add_action( 'parse_query', 'orgnk_disable_search_query', 5 );

	// Disable the built-in search widget
	add_action( 'widgets_init', 'orgnk_disable_search_widget', 1 );
}

//=======================================================================================================================================================

/**
 * orgnk_remove_admin_comment_support()
 * Remove admin comment pages and post type support
 */
function orgnk_remove_admin_comment_support() {

    global $pagenow;

    // Redirect any user trying to access comments page
    if ( $pagenow === 'edit-comments.php' ) {
        wp_redirect( admin_url() );
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

    // Disable support for comments and trackbacks in post types
    foreach ( get_post_types() as $post_type ) {
        if ( post_type_supports( $post_type, 'comments' ) ) {
            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}

/**
 * orgnk_remove_admin_comments_menu()
 * Remove comments page in the admin menu
 */
function orgnk_remove_admin_comments_menu() {
    remove_menu_page( 'edit-comments.php' );
}

/**
 * orgnk_remove_admin_bar_comments()
 * Remove comments button from the admin bar
 */
function orgnk_remove_admin_bar_comments() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
}

/**
 * orgnk_disable_comments_widget()
 * Disable the built-in comments widget
 */
function orgnk_disable_comments_widget() {
	unregister_widget( 'WP_Widget_Recent_Comments' );
}

/**
 * Add callbacks for the above if the SUPPORTS_COMMENTS variable is set to false
 */
if ( defined( 'SUPPORTS_COMMENTS' ) && SUPPORTS_COMMENTS === false ) {

    // Remove admin comment pages and post type support
    add_action('admin_init', 'orgnk_remove_admin_comment_support' );

    // Remove comments page in the admin menu
    add_action( 'admin_menu', 'orgnk_remove_admin_comments_menu' );

    // Remove comments button from the admin bar
    add_action( 'wp_before_admin_bar_render', 'orgnk_remove_admin_bar_comments' );

	// Disable the built-in comments widget
	add_action( 'widgets_init', 'orgnk_disable_comments_widget', 1 );

    // Close comments on the front-end
    add_filter( 'comments_open', '__return_false', 20, 2 );
    add_filter( 'pings_open', '__return_false', 20, 2 );

    // Hide existing comments
    add_filter( 'comments_array', '__return_empty_array', 10, 2) ;
}

//=======================================================================================================================================================

/**
 * orgnk_single_post_schema()
 * Generates article schema for single posts for outputting in the document head
 */
function orgnk_single_post_schema() {

    $schema = null;
    $sub_schema = array();

    if ( is_singular( 'post' ) ) {

        $title				= esc_html( get_the_title() );
        $permalink			= esc_url( get_the_permalink() );
        $image				= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
        $logo				= esc_url( wp_get_attachment_url( get_option( 'options_site_schema_logo' ) ) );
        $publish_date		= esc_html( get_the_date( 'Y-m-d H:i:s', get_the_ID() ) );
        $modified_date		= esc_html( get_the_modified_date( 'Y-m-d H:i:s', get_the_ID() ) );
        $author_id			= esc_html( get_post_meta( get_the_ID(), 'entry_team_author', true ) );

        $sub_schema = array(
            'mainEntityOfPage'	=> array(
                '@type'     		=> 'WebPage',
                '@id' 				=> $permalink,
            ),
            'headline' 			=> $title,
            'datePublished'     => (new DateTime( $publish_date ) )->format('c'), // ISO-8601 date/time format
        );

        if ( $modified_date != $publish_date ) {
            $sub_schema['dateModified'] = (new DateTime( $modified_date ) )->format('c'); // ISO-8601 date/time format
        }

        if ( has_post_thumbnail( get_the_ID() ) ) {
            $sub_schema['image'] = $image;
        }

        if ( defined( 'ORGNK_TEAMS_CPT_NAME' ) && $author_id ) {

            $sub_schema['author'] = array(
                '@type'     		=> 'Person',
                '@id' 				=> esc_html( get_the_title( $author_id ) )
            );
        }

        $sub_schema['publisher'] = array(
            '@type'     		=> 'Organization',
            'name'              => esc_html( get_bloginfo( 'name' ) )
        );

        if ( $logo ) {
            $sub_schema['publisher']['logo'] = array(
                '@type'             => 'ImageObject',
                'url'               => $logo
            );
        }

        // Check if anything has been stored for output
		if ( $sub_schema ) {

            $schema = array(
                '@context'  		=> 'http://schema.org',
                '@type'             => 'NewsArticle',
            );

            $schema = array_merge( $schema, $sub_schema );
        }

        // Finally, check if there is any compiled schema to return
        if ( $schema ) {
            echo '<script type="application/ld+json" class="organik-article-schema">' . json_encode( $schema, JSON_PRETTY_PRINT ) . '</script>';
        }
    }
}
add_action( 'wp_head', 'orgnk_single_post_schema' );

//=======================================================================================================================================================

/**
 * orgnk_modify_acf_wysiwyg_basic_toolbar()
 * Remove unnecessary buttons from the ACF wysiwyg editor field when it's set to 'simple'
 */
if ( class_exists( 'ACF' ) ) {

	function orgnk_modify_acf_wysiwyg_basic_toolbar( $toolbars ) {

		// Buttons to remove
		$buttons = array(
			'blockquote',
			'alignleft',
			'aligncenter',
			'alignright'
		);

		foreach ( $buttons as $button ) {
			if ( ( $key = array_search( $button , $toolbars['Basic'][1] ) ) !== false ) {
				unset( $toolbars['Basic'][1][$key] );
			}
		}

		return $toolbars;
	}
	add_filter( 'acf/fields/wysiwyg/toolbars' , 'orgnk_modify_acf_wysiwyg_basic_toolbar' );
}


//=======================================================================================================================================================

/**
 * orgnk_acf_restrict_backend_access()
 * Prevent all users except authorised Organik Web developers from seeing the 'Custom Fields' menu item in admin
 * Note the ACF editing capabilities are still accessible if the user visits /wp-admin/edit.php?post_type=acf-field-group directly
 */
if ( class_exists( 'ACF' ) ) {

	// function orgnk_acf_restrict_backend_access() {

	// 	// Provide a list of usernames who can edit custom field definitions here
	// 	$allowed_domains = array( 'organikweb.com.au' );

	// 	// Get the current user
	// 	$current_user = wp_get_current_user();

	// 	// Get the current user's email address
	// 	$user_email = $current_user->user_email;

	// 	// Separate the email address into parts by @ characters (there should be only one)
	// 	$email_parts = explode( '@', $user_email );

	// 	// Remove and return the last part, which should be the domain
	// 	$email_domain = array_pop($email_parts);

	// 	// Finally check if the user's email domain is the array of allowed domains
	// 	return ( in_array( $email_domain, $allowed_domains ) );
	// }
	// add_filter( 'acf/settings/show_admin', 'orgnk_acf_restrict_backend_access' );
}

//=======================================================================================================================================================

/**
 * If Gravity Forms is active, then force all scripts into the footer to avoid jQuery undefined errors
 */
if ( class_exists( 'GFCommon' ) ) {
    add_filter( 'gform_init_scripts_footer', '__return_true' );
}

//=======================================================================================================================================================

/**
 * orgnk_gforms_set_error_output()
 * Override the default output for Gravity Forms validation errors
 * Mainly used to prevent the errors from outputting using the <h2> tag which can have unpredictable results
 */
if ( class_exists( 'GFCommon' ) ) {

	function orgnk_gforms_set_error_output( $message, $form ) {

		if ( gf_upgrade()->get_submissions_block() ) {
			return $message;
		}

		$message = '<div class="validation_error"><p>There was a problem with your submission.</p>';
		$message .= '<ul>';

		foreach ( $form['fields'] as $field ) {
			if ( $field->failed_validation ) {
				$message .= sprintf( '<li>%s - %s</li>', GFCommon::get_label( $field ), $field->validation_message );
			}
		}

		$message .= '</ul></div>';

		return $message;
	}
	add_filter( 'gform_validation_message', 'orgnk_gforms_set_error_output', 10, 2 );
}
