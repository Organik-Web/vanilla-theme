<?php
//=======================================================================================================================================================
// Modifications to the TinyMCE editor
//=======================================================================================================================================================

/**
 * orgnk_mce_custom_settings
 * Apply our custom settings to the TinyMCE editor on init
 */
function orgnk_mce_custom_settings( $settings ) {

	// Add 'button' as a format to the 'styleselect' dropdown
	// Allows links to be converted into buttons by attaching the 'primary-button' class to <a>
	$style_formats = array(

		// Each array child is a format with it's own settings
		array(
			'title' 	=> 'Button',
			'selector' 	=> 'a',
			'classes' 	=> 'primary-button'
		)
	);

	// Insert the JSON encoded array into 'style_formats' parameter
	$settings['style_formats'] = json_encode( $style_formats );

	// Limit the heading/paragraph formats available in the editor
	$settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';

	// Force the hidden toolbar to always be displayed
	$settings['wordpress_adv_hidden'] = false;

	// Prevent the automatic creation of paragraphs on return/enter in Mozilla/Firefox
	$settings['forced_root_block'] = 'p';

	// Change the indentation value
	$settings['indentation'] = '20px';

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'orgnk_mce_custom_settings' );

//=======================================================================================================================================================

/**
 * orgnk_mce_enable_styleselect
 * Adds the 'styleselect' dropdown to the editor toolbar so we can insert custom format options
 */
function orgnk_mce_enable_styleselect( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'mce_buttons_2', 'orgnk_mce_enable_styleselect' );

//=======================================================================================================================================================

/**
 * orgnk_mce_remove_buttons
 * Remove some buttons from the editor we don't need
 */
function orgnk_mce_remove_buttons( $buttons ) {

    $remove_buttons = array(
		'indent',
		'outdent'
	);

    foreach ( $buttons as $button_key => $button_value ) {
        if ( in_array( $button_value, $remove_buttons ) ) {
            unset( $buttons[ $button_key ] );
        }
    }
    return $buttons;
}
add_filter( 'mce_buttons', 'orgnk_mce_remove_buttons'); // First row of buttons
add_filter( 'mce_buttons_2', 'orgnk_mce_remove_buttons'); // Second row of buttons
