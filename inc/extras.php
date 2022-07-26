<?php
//=======================================================================================================================================================
// Extras - additional functions for this client's version of the theme
//=======================================================================================================================================================

/**
 * orgnk_template_remove_editor()
 * Remove the content editor from pages/templates that don't need it
 */
function orgnk_template_remove_editor() {

	if ( ! is_admin() ) return;

	if ( isset( $_GET['post'] ) ) {

		$current_id 			= $_GET['post'];
		$post_types 			= array();
		$current_template 		= esc_html( get_post_meta( $current_id, '_wp_page_template', true ) );

		// Setup array of custom templates
		$custom_templates = array(
			'templates/auto-landing.php',
			'templates/flexi-sections.php',
			'templates/contact.php',
			'templates/sitemap.php',
			'templates/form-submission.php',
		);

		$special_pages = array(
			get_option( 'page_on_front' ),
			get_option( 'page_for_posts' ),
		);

		// Conditionally add CPT special pages
		if ( defined( 'ORGNK_SERVICES_CPT_NAME' ) ) $special_pages[] = get_option( 'page_for_' . ORGNK_SERVICES_CPT_NAME );
		if ( defined( 'ORGNK_TEAMS_CPT_NAME' ) ) $special_pages[] = get_option( 'page_for_' . ORGNK_TEAMS_CPT_NAME );
		if ( defined( 'ORGNK_EVENTS_CPT_NAME' ) ) $special_pages[] = get_option( 'page_for_' . ORGNK_EVENTS_CPT_NAME );

		foreach ( $post_types as $post_type ) {
			remove_post_type_support( $post_type, 'editor' );
		}

		foreach ( $custom_templates as $template ) {
			if ( $current_template === $template ) {
				remove_post_type_support( 'page', 'editor' );
			}
		}

		foreach ( $special_pages as $page ) {
			if ( $current_id === $page ) {
				remove_post_type_support( 'page', 'editor' );
			}
		}
	}
}
add_action( 'init', 'orgnk_template_remove_editor' );

//=======================================================================================================================================================

/**
 * orgnk_term_archives_supported_taxonomies()
 * Set which taxonomies support the term archives CPT for display sections or content
 * This function is unique to each client, and affects how the taxonomy and term select fields are populated in the back-end
 * Important: if this theme doesn't need to support any custom term archives,
 * or the theme doesn't use any custom taxonomies, set this to return false
 */
function orgnk_term_archives_supported_taxonomies() {

	$taxonomies = array();

	if ( defined( 'ORGNK_SERVICE_TYPE_TAX_NAME' ) ) $taxonomies[] = ORGNK_SERVICE_TYPE_TAX_NAME;

	return $taxonomies;
}

//=======================================================================================================================================================

/**
 * orgnk_header_sub_nav_cpt_archives()
 * Set which custom post type archives use the header sub nav to list their posts
 * This function is unique to each client, and affects the output in the header template part
 * and the conditional checks run before the body class is added in orgnk-header-sub-nav.php
 * Important: if this theme doesn't need to support any custom post type archives in the header sub nav,
 * or the theme doesn't use the header sub nav, set this to return false
*/
function orgnk_header_sub_nav_cpt_archives() {

	$cpts = array();

	if ( defined( 'ORGNK_SERVICES_CPT_NAME' ) ) $cpts[] = ORGNK_SERVICES_CPT_NAME;
	if ( defined( 'ORGNK_TEAMS_CPT_NAME' ) ) $cpts[] = ORGNK_TEAMS_CPT_NAME;

	return $cpts;
}

//=======================================================================================================================================================

/**
 * orgnk_flexi_sections_classes()
 * Returns an array of classes depending on various options for each flexible section
 */
function orgnk_flexi_sections_classes( $meta_key = null, $i = null ) {

	$classes = array( 'section-flexible' );

	if ( isset( $meta_key ) && isset( $i ) ) {

		$type 				= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key, true )[$i] );
		$bg_color 			= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_background_color', true ) );
		$top_padding		= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_top_padding', true ) );
		$bottom_padding		= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_bottom_padding', true ) );
		$position			= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_content_position', true ) );

		if ( $top_padding === 'default' && $bottom_padding === 'default' ) {

			$classes[] = 'section-pad';

		} else {

			if ( $top_padding === 'default' ) {
				$classes[] = 'section-pad-top';
			}

			if ( $bottom_padding === 'default' ) {
				$classes[] = 'section-pad-bottom';
			}
		}

		if ( $type !== 'cover-content' || $type !== 'pricing-list' ) {

			// Background colors
			if ( $bg_color === 'dark' ) {
				$classes[] = 'section-dark';
			} elseif ( $bg_color === 'light' ) {
				$classes[] = 'section-light';
			} elseif ( $bg_color === 'white' ) {
				$classes[] = 'section-white';
			}
		}

		if ( $type === 'cover-content' ) {
			$classes[] = 'section-dark';
		}

		if ( $type === 'pricing-list' ) {
			$classes[] = 'section-light';
		}

		if ( $type === 'simple-content' || $type === 'split-content' || $type === 'cover-content' ) {
			if ( $position === 'left' ) {
				$classes[] = 'content-left';
			} elseif ( $position === 'center' ) {
				$classes[] = 'content-center';
			} elseif ( $position === 'right' ) {
				$classes[] = 'content-right';
			}
		}

		if ( $top_padding === 'none' ) {
			$classes[] = 'pad-top-none';
		}

		if ( $bottom_padding === 'none' ) {
			$classes[] = 'pad-bottom-none';
		}
	}

	return ' ' . implode( ' ', array_filter( $classes ) );
}

//=======================================================================================================================================================

/**
 * orgnk_flexi_sections_is_dark()
 * Determines whether the current section has it's background color set to something dark
 * Mainly used to invert buttons through the orgnk_do_acf_button_group() function
 */
function orgnk_flexi_sections_is_dark( $meta_key = null, $i = null ) {

	if ( isset( $meta_key ) && isset( $i ) ) {

		$type		= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key, true )[$i] );
		$bg_color	= esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_' . $i . '_background_color', true ) );

		if ( $type === 'cover-content' || $bg_color === 'dark' || $bg_color === 'blue' ) {
			return true;
		}
	}

	return false;
}

//=======================================================================================================================================================

/**
 * orgnk_has_enquiry_form()
 * Checks whether a Gravity forms enquiry form ID has been set in theme settings, and whether the Gravity forms is active and the ID is valid/active
 */
function orgnk_has_enquiry_form() {

	$form_id = get_option( 'options_enquiry_form_id' );

	if ( $form_id ) {
		if ( function_exists( 'gravity_form' ) && gravity_form( $form_id, false, false, false, null, false, null, false ) ) {
			return true;
		}
	} else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_has_global_cta()
 * Checks if the global 'call to action' section is enabled in theme settings and returns true/false
 */
function orgnk_has_global_cta() {

	$cta_active = esc_html( get_option( 'options_global_cta_active' ) );
	$hide_cta = esc_html( get_post_meta( orgnk_get_the_ID(), 'entry_hide_cta', true ) );

	if ( $cta_active && ! $hide_cta ) {
		return true;
	} else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_do_cta_links()
 * Returns a button pair for the global enquiry panel trigger and a phone number button
 * Uses orgnk_has_enquiry_form() function check if enquiry panel trigger should be shown
 * Uses the phone number in theme settings to check if a phone button should be shown
 */
function orgnk_do_cta_links( $invert = false ) {

	$output = null;
	$invert_class = ( $invert ) ? ' white' : null;
	$phone = esc_html( get_option( 'options_business_phone' ) );

	if ( orgnk_has_enquiry_form() || $phone ) {
		$output .= '<div class="actions">';
		$output .= '<div class="button-group">';

		if ( orgnk_has_enquiry_form() ) {
			$output .= '<a class="primary-button enquiry-panel-trigger' . $invert_class . '" href="#enquiry-form">Enquire now</a>';
		}

		if ( $phone ) {
			$output .= '<a class="phone-button' . $invert_class . '" href="tel:' . orgnk_format_phone_link( $phone ) . '">' . $phone . '</a>';
		}

		$output .= '</div>';
		$output .= '</div>';
	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_get_opening_hours()
 * Loops through array of opening hours and returns the opening hours for the week if it is not set to just return todays opening hours
 */
function orgnk_get_opening_hours( $today = false ) {

	$output = null;

	$opening_hours = array(
		'Monday'        => esc_html( get_option( 'options_hours_monday' ) ),
		'Tuesday'       => esc_html( get_option( 'options_hours_tuesday' ) ),
		'Wednesday'     => esc_html( get_option( 'options_hours_wednesday' ) ),
		'Thursday'      => esc_html( get_option( 'options_hours_thursday' ) ),
		'Friday'        => esc_html( get_option( 'options_hours_friday' ) ),
		'Saturday'      => esc_html( get_option( 'options_hours_saturday' ) ),
		'Sunday'        => esc_html( get_option( 'options_hours_sunday' ) )
	);

	$opening_hours = array_filter( $opening_hours );

	if ( ! empty( $opening_hours ) ) {
		$output = $opening_hours;
	}

	return $output;
}
