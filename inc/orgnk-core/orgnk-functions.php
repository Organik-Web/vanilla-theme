<?php
//=======================================================================================================================================================
// Organik templating and helper functions
//=======================================================================================================================================================

/**
 * orgnk_site_copyright()
 * Prints a simple copyright string, including the site name and year
 * Mainly used in the bottom of the footer
 */
function orgnk_site_copyright( $site_name = null ) {
	$site_name = ( $site_name ) ? $site_name : get_bloginfo( 'name' );
	return '&copy; ' . date( 'Y' ) . ' ' . esc_html( $site_name );
}

//=======================================================================================================================================================

/**
 * orgnk_developer_link()
 * Prints a simple link back to Organik Web
 */
function orgnk_developer_link( $label = 'Website by', $name = 'Organik Web' ) {
	return '<span>' . esc_html( $label ) . ' </span><a href="https://www.organikweb.com.au/" target="_blank" rel="noopener">' . esc_html( $name ) . '</a>';
}

//=======================================================================================================================================================

/**
 * orgnk_seo_agency_link()
 * Prints a simple link back to White Chalk Road
 */
function orgnk_seo_agency_link( $label = 'SEO by', $name = 'White Chalk Road' ) {
	return '<span>' . esc_html( $label ) . ' </span><a href="https://www.whitechalkroad.com.au/" target="_blank" rel="noopener">' . esc_html( $name ) . '</a>';
}

//=======================================================================================================================================================

/**
 * orgnk_skip_to_content_target()
 * The target element for the previous function, used on each main template file
 */
function orgnk_skip_to_content_target() {
	return 'main-content';
}

/**
 * orgnk_skip_to_content_button()
 * Creates a button for skipping to the main content of the page
 */
function orgnk_skip_to_content_button() {

	$target = orgnk_skip_to_content_target();

	return '<a href="#' . $target . '" class="skip-to skip-to-content"><span>' . esc_html( 'Skip to content' ) . '</span></a>';
}

//=======================================================================================================================================================

/**
 * orgnk_get_the_ID()
 * Get the current query ID, taking into account unique page types
 */
function orgnk_get_the_ID() {

	$page_id = null;

	// Check first if the page is the default posts archive
	if ( is_home() && ! in_the_loop() && defined( 'PAGE_FOR_POSTS_ID' ) ) {
		$page_id = PAGE_FOR_POSTS_ID;
	}

	// Next, check if the page is a dummy term archive using the using the term archives functionality
	// Note: we're not setting a fallback page ID for archives because they don't have them by default, so we want to return null in that case
	elseif ( is_tax() && ! in_the_loop() ) {
		if ( function_exists( 'orgnk_get_term_archive_id' ) && orgnk_get_term_archive_id() ) {
			$page_id = orgnk_get_term_archive_id();
		}
	}

	// Finally, check if the page is a dummy archive using the pages for CPT archive functionality
	// Note: we're not setting a fallback page ID for archives because they don't have them by default, so we want to return null in that case
	elseif ( is_archive() && ! in_the_loop() ) {
		if ( function_exists( 'orgnk_get_post_type_archive_id' ) && orgnk_get_post_type_archive_id() ) {
			$page_id = orgnk_get_post_type_archive_id();
		}
	}

	elseif ( is_search() && ! in_the_loop() ) {
		$page_id = null;
	}

	// Otherwise, use the default get_the_ID() function
	else {
		$page_id = get_the_ID();
	}

	return $page_id;
}

//=======================================================================================================================================================

/**
 * orgnk_get_archive_post_type()
 * Get the current archive post type name
 */
function orgnk_get_archive_post_type() {
    return is_archive() ? get_queried_object()->name : false;
}

//=======================================================================================================================================================

/**
 * orgnk_get_entry_title()
 * Returns a heading tag with the correct page title based on various conditional checks
 */
function orgnk_get_entry_title() {

	$title			= null;
	$custom_title	= esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_custom_title', true ) );

	// A filter for the entry title string, allowing for additional logic when determining the entry title
	// This filter is run here before any other checks so it can take precendence
	// IMPORTANT: use of this filter must be checked carefully, because if the filter returns a $title at this stage,
	// all others checks won't occur and this function will skip straight to output
	$title = apply_filters( 'orgnk_entry_title', $title );

	// If title has already been set by the filter above, return it straight away
	if ( $title ) return $title;

	// Index and default posts archive
	if ( is_home() ) {
		if ( defined( 'PAGE_FOR_POSTS_ID' ) && PAGE_FOR_POSTS_ID ) {
			if ( $custom_title ) {
				$title = $custom_title;
			} else {
				$title = esc_html( get_the_title( PAGE_FOR_POSTS_ID ) );
			}
		} else {
			$title = esc_html( get_post_type_object( 'post' )->labels->name ); // attempting to get the post type on the blog/index page will return 'page' instead of 'post', so we have to get it manually
		}
	}

	// Taxonomy, term and tag archives that do not have an ID (i.e. are not using the 'term archives' functionality)
	elseif ( is_category() || is_tag() || is_tax() ) {
		if ( function_exists( 'orgnk_get_term_archive_id' ) && ! orgnk_get_term_archive_id() ) {
			$title = esc_html( get_term( get_queried_object() )->name );
		}
	}

	// Archives that do not have an ID (i.e. are not using the 'pages for CPT archive' functionality)
	elseif ( is_archive() ) {
		if ( function_exists( 'orgnk_get_post_type_archive_id' ) && ! orgnk_get_post_type_archive_id() ) {
			$title = esc_html( get_queried_object()->labels->name );
		}
	}

	// Search result pages
	elseif ( is_search() ) {
		global $wp_query;
		$result_terminology = $wp_query->found_posts > 1 ? ' results' : ' result'; // Change the terminology depending on whether there is 1 or more results
		$search_terms = ( get_search_query() ) ? ' for: ' . get_search_query() :  '';
		$title = esc_html( $wp_query->found_posts . $result_terminology . $search_terms );
	}

	// 404 page
	elseif ( is_404() ) {
		$title = esc_html( 'Sorry, that page can’t be found' );
	}


	// At this point, if a title still has not been set, check for a custom title before default to the post title
	if ( ! $title ) {

		if ( $custom_title ) {
			$title = $custom_title;
		} elseif ( ! is_front_page() ) {
			$title = esc_html( get_the_title( orgnk_get_the_ID() ) );
		} else {
			$title = null;
		}
	}

	return $title;
}

//=======================================================================================================================================================

/**
 * orgnk_get_image_meta()
 * Retrieves all image meta i.e. ID, title, alt, caption & description
 */
function orgnk_get_image_meta( $attachment_id = null ) {

	if ( ! $attachment_id ) return;

	$output 			= null;
	$attachment			= get_post( $attachment_id );

	// Check that the attachment can be retrieved successfully before proceeding
	if ( $attachment ) {

		// Note: the 'url', 'width' and 'height' attributes for each size are set to default to the values of the 'full' image,
		// they are then overwritten later by each size's respective values (if they exist). This is done as small images will result in some sizes not existing,
		// which would mean the url, width and height would not be found, potentially cause errors in template usage
		$output = array(
			'id'			=> $attachment_id,
			'title' 		=> esc_html( $attachment->post_title ),
			'alt' 			=> esc_html( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ),
			'caption' 		=> esc_html( $attachment->post_excerpt ),
			'description' 	=> esc_html( $attachment->post_content ),
		);
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_get_image()
 * Retrieves all the sizes and attributes for the supplied attachment ID and stores them in an array
 */
function orgnk_get_image( $attachment_id = null ) {

	if ( ! $attachment_id ) return;

	$output 			= null;
	$attachment			= get_post( $attachment_id );
	$attachment_meta	= wp_get_attachment_metadata( $attachment_id );
	$mime_type			= get_post_mime_type( $attachment_id );

	// Check that the attachment can be retrieved successfully before proceeding
	if ( $attachment ) {

		// Note: the 'url', 'width' and 'height' attributes for each size are set to default to the values of the 'full' image,
		// they are then overwritten later by each size's respective values (if they exist). This is done as small images will result in some sizes not existing,
		// which would mean the url, width and height would not be found, potentially cause errors in template usage
		$output = array(
			'id'			=> $attachment_id,
			'title' 		=> esc_html( $attachment->post_title ),
			'alt' 			=> esc_html( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ),
			'caption' 		=> esc_html( $attachment->post_excerpt ),
			'description' 	=> esc_html( $attachment->post_content ),
			'mime-type'		=> $mime_type,
			'thumbnail'		=> array(
				'url'			=> esc_url( wp_get_attachment_image_url( $attachment->ID, 'thumbnail' ) ),
				'width'			=> ( isset( $attachment_meta['width'] ) ) ? $attachment_meta['width'] : null,
				'height'		=> ( isset( $attachment_meta['height'] ) ) ? $attachment_meta['height'] : null
			),
			'medium'		=> array(
				'url'			=> esc_url( wp_get_attachment_image_url( $attachment->ID, 'medium' ) ),
				'width'			=> ( isset( $attachment_meta['width'] ) ) ? $attachment_meta['width'] : null,
				'height'		=> ( isset( $attachment_meta['height'] ) ) ? $attachment_meta['height'] : null
			),
			'large'		=> array(
				'url'			=> esc_url( wp_get_attachment_image_url( $attachment->ID, 'large' ) ),
				'width'			=> ( isset( $attachment_meta['width'] ) ) ? $attachment_meta['width'] : null,
				'height'		=> ( isset( $attachment_meta['height'] ) ) ? $attachment_meta['height'] : null
			),
			'full'		=> array(
				'url'			=> esc_url( wp_get_attachment_url( $attachment->ID ) ),
				'width'			=> ( isset( $attachment_meta['width'] ) ) ? $attachment_meta['width'] : null,
				'height'		=> ( isset( $attachment_meta['height'] ) ) ? $attachment_meta['height'] : null
			)
		);

		// Add image dimensions for each size to the output data
		if ( isset( $attachment_meta['sizes'] ) ) {

			foreach ( $attachment_meta['sizes'] as $key => $size ) {

				if ( isset( $output[$key] ) ) {
					$output[$key]['width'] = $size['width'];
					$output[$key]['height'] = $size['height'];
				}
			}
		}
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_do_img_element()
 * Returns an HTML image element including class, source, width, height and alt text
 */
function orgnk_do_img_element( $image = null, $size = 'full', $class = null ) {

	$output = null;

	// If the image data passed in is an ID, then retrieve the image data using orgnk_get_image()
	if ( is_numeric( $image ) ) {
		$image = orgnk_get_image( $image );
	}

	// Check that the image data is an array before proceeding
	if ( ! is_array( $image ) ) return;

	$output .= '<img';

	if ( $class ) {
		$output .= ' class="' . esc_html( $class ) . '"';
	}

	$output .= ' src="' . $image[$size]['url'] . '"';

	if ( $image[$size]['width'] ) {
		$output .= ' width="' . $image[$size]['width'] . '"';
	}

	if ( $image[$size]['height'] ) {
		$output .= ' height="' . $image[$size]['height'] . '"';
	}

	if ( $image['alt'] ) {
		$output .= ' alt="' . $image['alt'] . '"';
	}

	$output .= '>';

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_default_entry_header_image()
 * Check if the current page/post has a featured image and use it as the header image
 * Otherwise, use the default page header option from theme settings, if it is set
 * Note: we are adding a filter at the start of this function before it runs its various checks so plugins etc can hook into and replace the image source
 * We also check at every interval if the $image_url is still null so we can skip to the ouput, ensuring any external filtering takes precedence
 */

function orgnk_default_entry_header_image() {

	$output				= null;
	$image_id			= null;
	$default_image_id	= get_option( 'options_entry_header_default_image' );

	// A filter for the header image url source, allowing for overriding the page header image directly when needed (i.e. custom post types)
	// This filter is run here before any other checks so it can take precendence
	// IMPORTANT: use of this filter must be checked carefully, because if the filter returns a $image_id at this stage,
	// all others checks won't occur and this function will skip straight to output
	$image_id = apply_filters( 'orgnk_entry_header_image', $image_id );

	// For the front page, get the header image URL from theme settings
	if ( is_front_page() && $default_image_id && ! $image_id ) {
		$image_id = $default_image_id;
	}

	// For everything else, use the featured image as the header image
	else {

		if ( ! $image_id ) {

			if ( is_home() || is_author() || is_category() || is_tag() ) {

				if ( defined( 'PAGE_FOR_POSTS_ID' ) && has_post_thumbnail( PAGE_FOR_POSTS_ID ) ) {
					$image_id = get_post_thumbnail_id( PAGE_FOR_POSTS_ID );
				}

			} elseif ( is_post_type_archive() || is_tax() ) {

				$archive_page_id = null;

				// Check if the page is a dummy term archive using the using the term archives functionality
				if ( is_tax() && function_exists( 'orgnk_get_term_archive_id' ) && orgnk_get_term_archive_id() ) {
					$archive_page_id = orgnk_get_term_archive_id();
				}

				// Check if the page is a dummy archive using the page for CPT archive functionality
				elseif ( is_post_type_archive() && function_exists( 'orgnk_get_post_type_archive_id' ) && orgnk_get_post_type_archive_id() ) {
					$archive_page_id = orgnk_get_post_type_archive_id();
				}

				if ( $archive_page_id && has_post_thumbnail( $archive_page_id ) ) {
					$image_id = get_post_thumbnail_id( $archive_page_id );
				}

			} elseif ( is_single() || is_page() ) {

				if ( has_post_thumbnail( get_the_ID() ) ) {
					$image_id = get_post_thumbnail_id( get_the_ID() );
				}
			}
		}
	}

	// If none of the above returns an image ID, fallback to image set in Theme Options
	if ( $default_image_id && ! $image_id ) {
		$image_id = $default_image_id;
	}

	// Finally, if an image ID has been found, retrieves all of it's attributes using orgnk_get_image()
	if ( $image_id ) {
		$output = orgnk_get_image( $image_id );
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_get_acf_link()
 * Retrieves a link field array and returns its parts
 */
function orgnk_get_acf_link( $meta_key = null, $fallback_title = null ) {

	if ( ! $meta_key ) return;

	$output 			= null;
	$link 				= maybe_unserialize( get_post_meta( orgnk_get_the_ID(), $meta_key, true ) );
	$fallback_title		= ( $fallback_title ) ? esc_html( $fallback_title ) : 'Learn more';

	if ( $link ) {

		$output = array(
			'url' 		=> ( $link['url'] ) ? esc_url( $link['url'] ) : null,
			'title'		=> ( $link['title'] ) ? esc_html( $link['title'] ) : $fallback_title,
			'target'	=> ( $link['target'] ) ? ' target="_blank" rel="noopener"' : ' target="_self"',
		);
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_get_query_post_type()
 * Get the current query post type, taking into account unique page types
 * Mainly used for determining the post type of archive pages, where get_post_type() fails if the archive has no posts
 */
function orgnk_get_query_post_type() {

	$post_type = null;

	// The currently queried object
	$wp_the_query = $GLOBALS['wp_the_query'];
	$queried_object = sanitize_post( $wp_the_query->get_queried_object() );

	// Attempting to get the post type on the blog/index page will return 'page' instead of 'post', so we have to set it manually
	if ( is_home() ) {
		$post_type = 'post';
	}

	// Next check if the queried object is an archive, taxonomy or term object, because get_post_type() will not work in these scenarios
	elseif ( is_category() || is_tag() || is_tax() ) {
		$taxonomy = get_taxonomy( $queried_object->taxonomy );
		$post_type = $taxonomy->object_type[0]; // The first/primary post type is preferenced here to avoid taxonomies that may belong to multiple post types
	}

	// Archives must use the query var 'name' because get_post_type() returns false on archives with no posts
	elseif ( is_archive() ) {
		$post_type = $queried_object->name;
	}

	// Otherwise, use the default get_post_type() function
	else {
		$post_type = get_post_type();
	}

	return $post_type;
}

//=======================================================================================================================================================

/**
 * orgnk_get_the_content()
 * Mimics the behaviour of the_content filter, without the filter hooks
 * Primarily for retrieving and sanitizing content from ACF wysiwyg fields
 * Note: shortcode_unautop is run after wpautop, followed by do_shortcode to ensure all shortcodes print correctly
 */
function orgnk_get_the_content( $content = null ) {

	if ( $content ) {
		$content = wp_kses_post( $content );
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = convert_chars( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = prepend_attachment( $content );
	}

	return $content;
}

//=======================================================================================================================================================

/**
 * orgnk_get_file_contents()
 * A replacement for file_get_contents()
 * PHP configurations (specifically, allow_url_fopen = 0) prevents file_get_contents() from retrieving URLS for security reasons
 * Usage: orgnk_get_file_contents( get_template_directory_uri() . '/template-parts/logo.svg' )
 */
function orgnk_get_file_contents( $url ) {

	if ( orgnk_local_file_exists( $url) ) {

		return file_get_contents( orgnk_url_to_local_path( $url ) );

	} elseif ( orgnk_remote_file_exists( $url ) ) {

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_URL, $url );
		$data = curl_exec( $ch );

		if ( $data !== false ) {
			return $data;
		}

		curl_close( $ch );
	}

	return false;
}

//=======================================================================================================================================================

/**
 * orgnk_local_file_exists()
 * Checks if a local file exists. Can convert a URL to the current page to a local path.
 *
 * @param string $url
 * @return bool
 */
function orgnk_local_file_exists( $url ) {

	if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
		$urlParts = parse_url( $url );
		$siteParts = parse_url( site_url() );

		if ( !in_array( strtolower( $urlParts['scheme'] ), [ 'http', 'https' ] ) ) {
			return false;
		}

		if ( $urlParts['host'] !== $siteParts['host'] ) {
			return false;
		}

		return file_exists( ABSPATH . ltrim( $urlParts['path'], '/' ) );
	}

	return file_exists( $url );

}

//=======================================================================================================================================================

/**
 * orgnk_remote_file_exists()
 * Check if a file exists to prevent curl call in orgnk_get_file_contents from hanging
 */
function orgnk_remote_file_exists( $url ) {

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_NOBODY, true );
	curl_exec( $ch );
	$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	if ( $http_code == 200 ) {
		return true;
	}

  	return false;
}

//=======================================================================================================================================================

/**
 * orgnk_url_to_local_path()
 * Converts a URL to a local path if the URL is pointing to current host.
 *
 * @param string $url
 * @return string
 */
function orgnk_url_to_local_path( $url ) {

	if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
		$urlParts = parse_url( $url );
		$siteParts = parse_url( site_url() );

		if ( !in_array( strtolower( $urlParts['scheme'] ), [ 'http', 'https' ] ) ) {
			return false;
		}

		if ( $urlParts['host'] !== $siteParts['host'] ) {
			return false;
		}

		return  ABSPATH . ltrim( $urlParts['path'], '/' );
	}

	return $url;

}

//=======================================================================================================================================================

/**
 * orgnk_format_phone_link()
 * Provides a correctly formatted international phone number from a standard phone number
 */

function orgnk_format_phone_link( $phone = null ) {

	$au_prefix = '+61';

	if ( $phone ) {

		// Remove all whitespace from start and end of string
		$phone = trim( $phone );

		// Remore all whitespace within the string
		$phone = str_replace( ' ', '', $phone );

		// Find the opening and closing brackets for region codes
		$region_start = strpos( $phone, '(' );
		$region_end = strpos( $phone, ')' );

		// Check for opening and closing brackets, meaning a region code was specified and add the prefix
		if ( $region_start !== false && $region_end !== false ) {
			$phone = str_replace( '(0', $au_prefix, $phone );
			$phone = str_replace( ')', '', $phone );
		}

		// Check for '04' at the start of the string for Australian mobile phones and add the prefix
		elseif ( '04' === substr( $phone, 0, 2 ) ) {
			$phone = $au_prefix . substr( $phone, 1 );
		}

		// If neither of the above are true, then return only numerical characters, just to be safe
		else {
			$phone = preg_replace( '/[^0-9,]+/', '', $phone );
		}
	}

	return $phone;
}

//=======================================================================================================================================================

/**
 * orgnk_which_template()
 * Displays which theme file is being used when editing the site in development
 */
function orgnk_which_template() {

	if ( is_admin() ) return;

	if ( ! is_user_logged_in() ) return;

	if ( HOST_ENVIRONMENT === 'development' ) {
		global $template;
		$template_name = substr( $template, ( strpos( $template, 'wp-content/' ) + 10 ) );
		echo '<div style="position: fixed; left: 0; bottom: 0; font-family: courier,serif; font-size: 11px; color: #000000; padding: 10px 15px; border-radius: 0 5px 0 0; background-color: #ffffff; z-index: 9999;">Template: ' . $template_name . '</div>';
	}
}

//=======================================================================================================================================================

/**
 * orgnk_social_links_shortcode()
 * A shortcode for displaying the social-links.php template part
 * Note: the social-links.php already checks whether any of the social theme options are set
 * before returning any content, so if none are set this shortcode will simply return nothing
 * Also note that the social-links.php file includes the label above each item
*/
function orgnk_social_links_shortcode( $attr ) {
	ob_start();
	get_template_part('template-parts/global/social-links');
	return ob_get_clean();
}
add_shortcode( 'social_links', 'orgnk_social_links_shortcode' );

//=======================================================================================================================================================

/**
 * orgnk_has_post_thumbnail()
 * Checks whether the page has a post thumbnail/feature image or not
 *
 * @return bool
 */
function orgnk_has_post_thumbnail() {

	// First, check if the 'page for posts' has a featured image set for unique page types
	if ( ( is_home() || is_author() || is_category() || is_tag() ) && defined( 'PAGE_FOR_POSTS_ID' ) && has_post_thumbnail( PAGE_FOR_POSTS_ID ) ) {
		return true;
	}

	// Next, check if the post/page has a featured image set only if a valid ID can be found
	// If we don't check for a valid ID first, has_post_thumbnail will return true on archives for the first post in the loop
	elseif ( orgnk_get_the_ID() && has_post_thumbnail( orgnk_get_the_ID() ) ) {
		return true;
	}

	// Finally, check if a default page header image has been set in theme settings
	elseif ( get_option( 'options_entry_header_default_image' ) ) {
		return true;
	}

	// Otherwise, there is no image
	else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_auto_excerpt()
 * Outputs an excerpt for a post using either its excerpt or by trimming the content
 */
function orgnk_auto_excerpt( $length = '25' ) {

	// Variables
	$output 		= null;
	$subtitle 		= get_post_meta( orgnk_get_the_ID(), 'entry_subtitle', true );

	if ( $subtitle || has_excerpt() || ! empty( get_the_content() ) ) {

		if ( $subtitle ) {

			$output .= esc_html( $subtitle );

		} elseif ( has_excerpt() ) {

			$excerpt = esc_html( strip_shortcodes( get_the_excerpt() ) );
			$output .= esc_html( wp_trim_words( $excerpt, $length, '...' ) );

		} else {

			$the_content		= strip_shortcodes( get_the_content() );
			$strip_tags			= wp_strip_all_tags( $the_content );
			$clean_content		= apply_filters( 'the_content', $strip_tags );

			if ( $clean_content ) {
				$output .= esc_html( wp_trim_words( $clean_content, $length, '...' ) );
			}
		}
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_gallery_slider()
 * Take an array of image IDs and converts them into a slider
 * This function is primarily used for overriding the TinyMCE Gallery, but can also be used in templates
 */
function orgnk_gallery_slider( $image_ids = null, $slide_size = 'full', $thumb_size = 'medium' ) {

	$output = null;
	$images = null;
	foreach ( $image_ids as $id ) {

		if ( ! $id ) continue;

		$images[] = orgnk_get_image( $id );
	}

	if ( $images ) {

		$output = '<div class="orgnk-gallery">';
		$output .= '<div class="gallery-main splide">';
		$output .= '<div class="splide__track">';
		$output .= '<div class="splide__list">';

		foreach ( $images as $image ) {

			$output .= '<div class="image-slide splide__slide" style="background-image: url(' . $image[$slide_size]['url'] . ');">';
			$output .= '<div class="ratio-sizer"></div>';

			if ( $image['caption'] ) {
				$output .= '<div class="caption">' . $image['caption'] . '</div>';
			}

			$output .= '</div>';
		}

		// Close main gallery
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="gallery-thumbs splide">';
		$output .= '<div class="splide__track">';
		$output .= '<div class="splide__list">';

		foreach ( $images as $image ) {

			$output .= '<div class="thumb-slide splide__slide" style="background-image: url(' . $image[$thumb_size]['url'] . ');">';
			$output .= '<div class="ratio-sizer"></div>';
			$output .= '<div class="overlay"></div>';
			$output .= '<div class="slide-active"></div>';
			$output .= '</div>';

		}

		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_mce_gallery()
 * Converts the Tiny MCE galleries into sliders using orgnk_gallery_slider
 */
function orgnk_mce_gallery( $output, $attr ) {

	global $post, $wp_locale;
	static $instance = 0;
	$instance++;

	$output = null;
	$attachments = null;

	// We're trusting author input, so let's at least make sure it looks like a valid 'orderby' statement
	if ( isset( $attr['orderby'] ) ) {

		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );

		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	extract( shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => ''
	), $attr ) );

	if ( 'RAND' == $order ) {
		$orderby = 'none';
	}

	if ( ! empty( $include ) ) {

		$include = preg_replace( '/[^0-9,]+/', '', $include );

		$attachments = get_posts( array(
			'include' 			=> $include,
			'post_status' 		=> 'inherit',
			'post_type' 		=> 'attachment',
			'post_mime_type' 	=> 'image',
			'order' 			=> $order,
			'orderby' 			=> $orderby,
			'fields' 			=> 'ids'
		) );
	}

	if ( $attachments ) {

		// Now pass the attachments over to orgnk_gallery_slider to finish the job
		$output .= '<div class="gallery">';
		$output .= orgnk_gallery_slider( $attachments, 'large' );
		$output .= '</div>';
	}

	return $output;
}
add_filter( 'post_gallery', 'orgnk_mce_gallery', 10, 2 );

//=======================================================================================================================================================

/**
 * orgnk_is_gutenberg_active()
 * Check if Gutenberg is active in core or via the plugin
 * Note: must not be used earlier than when the plugins_loaded action is fired
 *
 * @return bool
 */
function orgnk_is_gutenberg_active() {

	$gutenberg    = false;
	$block_editor = false;

	// Gutenberg is installed and activated
	if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
		$gutenberg = true;
	}

	// Block editor is active
	if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
		$block_editor = true;
	}

	if ( ! $gutenberg && ! $block_editor ) {
		return false;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
		return true;
	}

	$use_block_editor = ( get_option( 'classic-editor-replace' ) === 'no-replace' );

	return $use_block_editor;
}

//=======================================================================================================================================================

/**
 * orgnk_do_acf_link()
 * Retrieves a link field array and converts it into a plain link
 */
function orgnk_do_acf_link( $meta_key = null, $classes = null, $invert = false ) {

	if ( ! $meta_key ) return;

	$output = null;
	$link = null;
	$color_class = ( $invert === true ) ? ' white' : null;

	// If the first 6 characters of the meta key are 'option' then the ACF field is located in the theme settings and we need to use get_option() instead
	if ( substr( $meta_key, 0, 6 ) === 'option' ) {
		$link = maybe_unserialize( get_option( $meta_key ) );
	} else {
		$link = maybe_unserialize( get_post_meta( orgnk_get_the_ID(), $meta_key, true ) );
	}

  	// Check a button exists before proceeding
	if ( $link ) {

		$output .= '<a href="' . esc_url( $link['url'] ) . '" class="' . $classes . $color_class . '"';

		if ( $link['target'] ) {
			$output .= ' target="_blank" rel="noopener"';
		}

		$output .= '>' . esc_html( $link['title'] ) . '</a>';
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_do_acf_button()
 * Retrieves a link field array and converts it into a button
 */
function orgnk_do_acf_button( $meta_key = null, $classes = 'primary-button', $invert = false, $wrapper = true ) {

	if ( ! $meta_key ) return;

	$output = null;
	$button = null;
	$color_class = ( $invert === true ) ? ' white' : null;

	// If the first 6 characters of the meta key are 'option' then the ACF field is located in the theme settings and we need to use get_option() instead
	if ( substr( $meta_key, 0, 6 ) === 'option' ) {
		$button = maybe_unserialize( get_option( $meta_key ) );
	} else {
		$button = maybe_unserialize( get_post_meta( orgnk_get_the_ID(), $meta_key, true ) );
	}

  	// Check a button exists before proceeding
	if ( $button ) {

		if ( $wrapper ) {
			$output .= '<div class="actions">';
		}

		$output .= '<a href="' . esc_url( $button['url'] ) . '" class="' . $classes . $color_class . '"';

		if ( $button['target'] ) {
			$output .= ' target="_blank" rel="noopener"';
		}

		$output .= '>' . esc_html( $button['title'] ) . '</a>';

		if ( $wrapper ) {
			$output .= '</div>';
		}
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_do_acf_button_group()
 * Very similar to the function above, but uses ACF repeaters instead
 * Note: we are using a basic for loop instead of ACF's rows functions to remove dependency on the plugin
 */
function orgnk_do_acf_button_group( $meta_key = null, $first = 'primary-button', $second = 'primary-button', $invert = false, $wrapper = true ) {

	if ( ! $meta_key ) return;

	$output = null;
	$button_count = null;
	$color_class = ( $invert === true ) ? ' white' : null;

	// Retrieve the main repeater field - ACF stores this as the number of rows in the repeater in the database
	// If the first 6 characters of the meta key are 'option' then the ACF field is located in the theme settings and we need to use get_option() instead
	if ( substr( $meta_key, 0, 6 ) === 'option' ) {
		$button_count = esc_html( get_option( $meta_key ) );
	} else {
		$button_count = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key, true ) );
	}

  	// Check the repeater has buttons
	if ( $button_count ) {

		if ( $wrapper ) {
			$output .= '<div class="actions">';
			$output .= '<div class="button-group button-count-' . $button_count . '">';
		}

		// Loop through repeaters
		for ( $i = 0; $i < $button_count; $i++ ) {

			$classes = ( $i === 0 ) ? $first : $second;

			// Get the buttons
			// If the first 6 characters of the meta key are 'option' then the ACF field is located in the theme settings and we need to use get_option() instead
			if ( substr( $meta_key, 0, 6 ) === 'option' ) {
				$button = maybe_unserialize( get_option( $meta_key . '_' . $i . '_button' ) );
			} else {
				$button = maybe_unserialize( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_button', true ) );
			}

			if ( $button ) {
				$output .= '<a href="' . esc_url( $button['url'] ) . '" class="' . $classes . $color_class . '"';

				if ( $button['target'] ) {
					$output .= ' target="_blank" rel="noopener"';
				}

				$output .= '>' . esc_html( $button['title'] ) . '</a>';
			}
		}

		if ( $wrapper ) {
			$output .= '</div>';
			$output .= '</div>';
		}
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_class_by_string_length()
 * Checks if a string's length is below a certain limit and returns any supplied output
 */
function orgnk_class_by_string_length( $string = null, $limit = 50, $below = null, $above = null ) {

	$classes = array();

	if ( $string ) {
		if ( $below && strlen( $string ) < $limit ) {
			$classes[] .= $below;
		}
		if ( $above && strlen( $string ) >= $limit ) {
			$classes[] .= $above;
		}
	}

	if ( $classes ) {
		return ' ' . implode( ' ', array_filter( $classes ) );
	} else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_get_first_term()
 * Finds the first term of a given taxonomy for a single entry
 */
function orgnk_get_first_term( $taxonomy = 'category', $link = false, $link_classes = null ) {

	$output				= null;
	$terms				= get_the_terms( get_the_ID(), $taxonomy );
	$first_term			= isset( $terms[0] ) ? $terms[0] : null;
	$first_term_id		= isset( $first_term ) ? $first_term->term_id : null;
	$first_term_name	= isset( $first_term ) ? esc_html( $first_term->name ) : null;
	$link_classes		= ( $link_classes ) ? ' ' . implode( ' ', array_filter( $link_classes ) ) : null;

	if ( $first_term ) {

		if ( $link ) {
			$output .= '<a class="term-link' . $link_classes . '" href="' . esc_url( get_term_link( $first_term_id, $taxonomy ) ) . '">';
		}

		$output .= $first_term_name;

		if ( $link ) {
			$output .= '</a>';
		}
	}

	return $output;
}

//=======================================================================================================================================================
/**
 * orgnk_do_google_map_iframe()
 * Returns a basic Google Maps iframe using a supplied src string
 */
function orgnk_do_google_map_iframe( $src = null ) {

	if ( ! $src ) return;

	$output = null;
	$src = esc_url( $src );

	if ( $src ) {
		$output .= '<div class="orgnk-gmap">';
		$output .= '<iframe src="' . $src . '" style="position: relative; overflow: hidden; width: 100%; border:0;" allowfullscreen="" loading="lazy"></iframe>';
		$output .= '</div>';
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_image_is_svg()
 * Checks if supplied file path is an SVG file
 */
function orgnk_image_is_svg( $src ) {

	if ( $src ) {

		$file_type = wp_check_filetype( $src );

		if ( isset( $file_type ) && $file_type['type'] == 'image/svg+xml' ) {
			return true;
		}
	} else {
		return false;
	}
}

/**
 * orgnk_get_svg_contents()
 * Uses the orgnk_get_svg_contents() function to check a supplied media file URL is an SVG and then returns the SVG files contents as HTML
 */
function orgnk_get_svg_contents( $src ) {

	$output = null;

	if ( $src && orgnk_image_is_svg( $src ) ) {
		$output .= '<i class="icon svg-icon orgnk-svg-icon" aria-hidden="true">';
		$output .= orgnk_get_file_contents( $src );
		$output .= '</i>';
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_format_price()
 * Accepts a price int or float and formats the number
 * Note: this does not output currency symbols
 */
function orgnk_format_price( $price_val ) {

	$price = null;

	if ( $price_val ) {

		if ( ! is_float( $price_val ) ) {
			$price_val = floatval( $price_val );
		}

		$price = number_format( $price_val, 2 );
	}

	return $price;
}

//=======================================================================================================================================================

/**
 * orgnk_sidebar_offset()
 * Takes top and bottom values and uses them to offset the sidebar
 * Values must not contain units
 * This function is used to pass these values into our JavaScript thorugh data attributes
 */
function orgnk_sidebar_offset( $top = 50, $bottom = 50 ) {
	return ' data-offset-top="' . $top . '" data-offset-bottom="' . $bottom . '"';
}

//=======================================================================================================================================================

/**
 * orgnk_get_template_post_type()
 * Returns the current queries post type name
 * If the query is for a single post type, the singular name will be returned, otherwise the plural name will be returned
 * Primarily for creating dynamic classes in template files
 */
function orgnk_get_template_post_type() {

	$label = null;

	if ( is_singular() ) {
		$label = sanitize_title_with_dashes( strtolower( get_post_type_object( orgnk_get_query_post_type() )->labels->singular_name ) );
	} else {
		$label = sanitize_title_with_dashes( strtolower( get_post_type_object( orgnk_get_query_post_type() )->labels->name ) );
	}

	return $label;
}

//=======================================================================================================================================================

/**
 * orgnk_estimated_reading_time()
 * Returns the estimated reading time for a supplied piece of content
 * Typically used to calculate the reading time for the default content editor
 */
function orgnk_estimated_reading_time( $content = null, $words_per_minute = 200 ) {

	if ( ! $content ) return;

	$output = null;

	// Count the number of words in the content
	$words = str_word_count( strip_tags( $content ) );

	// Round and divide by the number of words per minute
	$minute = floor( $words / $words_per_minute );

	// Construct output
	if ( $minute > 0 ) {
		$output .= '<div class="reading-time">';
			$output .= '<i class="icon" aria-hidden="true"></i>';
			$output .= '<span class="label">' . $minute . ' min read</span>';
		$output .= '</div>';
	}

	return $output;
}

//=======================================================================================================================================================

$themeManifestCache = null;

/**
 * orgnk_enqueue_script()
 * Enqueues a JavaScript file, taking note of the Laravel Mix manifest to include any versioning / cache
 * busting suffixes.
 *
 * The path is expected to be relative to the theme root directory.
 */
function orgnk_enqueue_script($name, $path, $dependencies = [], $fallbackVersion = SCRIPT_VERSION, $inFooter = false)
{
	global $themeManifestCache;

	$themeDir = get_template_directory();
	$themeUri = get_template_directory_uri();
	$mixManifest = $themeDir . '/mix-manifest.json';
	$assetRelative = '/' . ltrim($path, '/');
	$assetPath = $themeUri . $assetRelative;

	if (!file_exists($mixManifest)) {
		// If the manifest does not exist, enqueue with fallback
		wp_enqueue_script($name, $assetPath, $dependencies, $fallbackVersion, $inFooter);
		return;
	}

	if (!is_null($themeManifestCache)) {
		$manifest = $themeManifestCache;
	} else {
		$manifest = $themeManifestCache = json_decode(file_get_contents($mixManifest), true);
	}

	if (!isset($manifest[$assetRelative])) {
		// If no manifest entry exists for this path, enqueue with fallback
		wp_enqueue_script($name, $assetPath, $dependencies, $fallbackVersion, $inFooter);
		return;
	}

	if (!preg_match('/id=(.*)$/i', $manifest[$assetRelative], $matches)) {
		// If we are unable to extract the asset ID, enqueue with fallback
		wp_enqueue_script($name, $assetPath, $dependencies, $fallbackVersion, $inFooter);
		return;
	}

	$version = $matches[1];
	wp_enqueue_script($name, $assetPath, $dependencies, $version, $inFooter);
}

/**
 * orgnk_enqueue_style()
 * Enqueues a CSS file, taking note of the Laravel Mix manifest to include any versioning / cache
 * busting suffixes.
 *
 * The path is expected to be relative to the theme root directory.
 */
function orgnk_enqueue_style($name, $path, $dependencies = [], $fallbackVersion = SCRIPT_VERSION, $media = 'all')
{
	global $themeManifestCache;

	$themeDir = get_template_directory();
	$themeUri = get_template_directory_uri();
	$mixManifest = $themeDir . '/mix-manifest.json';
	$assetRelative = '/' . ltrim($path, '/');
	$assetPath = $themeUri . $assetRelative;

	if (!file_exists($mixManifest)) {
		// If the manifest does not exist, enqueue with fallback
		wp_enqueue_style($name, $assetPath, $dependencies, $fallbackVersion, $media);
		return;
	}

	if (!is_null($themeManifestCache)) {
		$manifest = $themeManifestCache;
	} else {
		$manifest = $themeManifestCache = json_decode(file_get_contents($mixManifest), true);
	}

	if (!isset($manifest[$assetRelative])) {
		// If no manifest entry exists for this path, enqueue with fallback
		wp_enqueue_style($name, $assetPath, $dependencies, $fallbackVersion, $media);
		return;
	}

	if (!preg_match('/id=(.*)$/i', $manifest[$assetRelative], $matches)) {
		// If we are unable to extract the asset ID, enqueue with fallback
		wp_enqueue_style($name, $assetPath, $dependencies, $fallbackVersion, $media);
		return;
	}

	$version = $matches[1];
	wp_enqueue_style($name, $assetPath, $dependencies, $version, $media);
}
