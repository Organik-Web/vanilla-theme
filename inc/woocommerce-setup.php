<?php
//=======================================================================================================================================================
// WooCommerce setup
//=======================================================================================================================================================

/**
 * orgnk_woocommerce_setup()
 * Make changes when initialising WooCommerce
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 */
function orgnk_woocommerce_setup() {
	remove_theme_support( 'wc-product-gallery-zoom' );
	remove_theme_support( 'wc-product-gallery-lightbox' );
	remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'template_redirect', 'orgnk_woocommerce_setup', 100 );

//=======================================================================================================================================================

/**
 * Disable the default WooCommerce stylesheet
 * Removing the default WooCommerce stylesheet and using our own will protect us during WooCommerce core updates
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

//=======================================================================================================================================================

/**
 * orgnk_is_woocommerce_active()
 * Check whether WooCommerce is activated
 */
function orgnk_is_woocommerce_active() {
	if ( class_exists( 'woocommerce' ) ) {
		return true;
	} else {
		return false;
	}
}

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_pages_exclude_from_search()
 * Exclude WooCommerce pages from front-end search results
 */
if ( ! is_admin() ) {

	function orgnk_woocommerce_pages_exclude_from_search( $query ) {

		$woocommerce_keys = array (
			"woocommerce_shop_page_id",
			// "woocommerce_terms_page_id", we want to keep terms & conditions searchable
			"woocommerce_cart_page_id",
			"woocommerce_checkout_page_id",
			"woocommerce_pay_page_id",
			"woocommerce_thanks_page_id",
			"woocommerce_myaccount_page_id",
			"woocommerce_edit_address_page_id",
			"woocommerce_view_order_page_id",
			"woocommerce_change_password_page_id",
			"woocommerce_logout_page_id",
			"woocommerce_lost_password_page_id"
		);

		$id_list = array();

		foreach ( $woocommerce_keys as $wc_page_id ) {
			$id = get_option( $wc_page_id , 0 );
			$id_list[] = $id;
		}

		if ( ! $query->is_admin && $query->is_search && $query->is_main_query() ) {
			$query->set( 'post__not_in', $id_list );
		}
	}
	add_action(' pre_get_posts','orgnk_woocommerce_pages_exclude_from_search' );
}

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_active_body_class()
 * Add classes to the body tag if WooCommerce is active
 */
function orgnk_woocommerce_active_body_class( $classes ) {

	$classes[] = 'orgnk-woocommerce-active';

	if ( orgnk_is_woocommerce_active() && WC()->cart->get_cart_contents_count() == 0 ) {
		$classes[] .= 'cart-empty';
	}

	return $classes;
}

if ( orgnk_is_woocommerce_active() ) {
	add_filter( 'body_class', 'orgnk_woocommerce_active_body_class' );
}

//=======================================================================================================================================================

/**
 * orgnk_is_woocommerce_page()
 * Check whether a page is a WooCommerce page
 * Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes, which are also included)
 */
function orgnk_is_woocommerce_page() {
	if ( orgnk_is_woocommerce_active() && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return true;
	}

	$woocommerce_keys = array(
		"woocommerce_shop_page_id",
		"woocommerce_terms_page_id",
		"woocommerce_cart_page_id",
		"woocommerce_checkout_page_id",
		"woocommerce_pay_page_id",
		"woocommerce_thanks_page_id",
		"woocommerce_myaccount_page_id",
		"woocommerce_edit_address_page_id",
		"woocommerce_view_order_page_id",
		"woocommerce_change_password_page_id",
		"woocommerce_logout_page_id",
		"woocommerce_lost_password_page_id"
	);

	foreach ( $woocommerce_keys as $wc_page_id ) {
		if ( get_the_ID() == get_option( $wc_page_id , 0 ) ) {
			return true;
		}
	}

	return false;
}

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_products_per_page()
 * Set number of products per page
 */
function orgnk_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'orgnk_woocommerce_products_per_page' );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_thumbnail_columns()
 * Set product thumbnail gallery column count
 */
function orgnk_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'orgnk_woocommerce_thumbnail_columns' );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_loop_columns()
 * Set product archive column count
 */
function orgnk_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'orgnk_woocommerce_loop_columns' );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_related_products_args()
 * Set the related products loop arguments
 */
function orgnk_woocommerce_related_products_args( $args ) {

	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'orgnk_woocommerce_related_products_args' );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_before_shop_loop()
 * Product columns wrapper
 */
function orgnk_woocommerce_before_shop_loop() {
	$columns = orgnk_woocommerce_loop_columns();
	echo '<div class="products-index columns-' . absint( $columns ) . '">';
}
add_action( 'woocommerce_before_shop_loop', 'orgnk_woocommerce_before_shop_loop', 40 );

function orgnk_woocommerce_after_shop_loop() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop', 'orgnk_woocommerce_after_shop_loop', 40 );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_cart_link_fragment()
 * Ensure cart contents update when products are added to the cart via AJAX
 */
function orgnk_woocommerce_cart_link_fragment( $fragments ) {

	ob_start();
	orgnk_woocommerce_cart_link();
	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'orgnk_woocommerce_cart_link_fragment' );

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_header_cart()
 * The WooCommerce Mini Cart for use in the header
 * You can add the WooCommerce Mini Cart to header.php like so ...
 * if ( function_exists( 'orgnk_woocommerce_header_cart' ) ) { orgnk_woocommerce_header_cart(); }
 */
function orgnk_woocommerce_header_cart() {

	$output = null;

	if ( is_cart() ) {
		$class = 'current-menu-item';
	} else {
		$class = '';
	}

	$output .= '<ul id="site-header-cart" class="site-header-cart">';
	$output .= '<li class="' . $class . '">';
	$output .= orgnk_woocommerce_cart_link();
	$output .= '</li>';

	$output .= '<li>';

	$instance = array(
		'title' => '',
	);

	the_widget( 'WC_Widget_Cart', $instance );

	$output .= '</li>';
	$output .= '</ul>';

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_cart_link()
 * Displays a link to the cart including the number of items
 * You can add the Cart Link to header.php like so ...
 * if ( function_exists( 'orgnk_woocommerce_cart_link' ) ) { orgnk_woocommerce_cart_link(); }
 */
function orgnk_woocommerce_cart_link() {

	$output = null;

	if ( orgnk_is_woocommerce_active() ) {

		$count = WC()->cart->get_cart_contents_count();
		$item_count_text = sprintf( _n( '%d', '%d', $count, 'orgnk_client_textdomain' ), $count ); // translators: number of items in the mini cart

		$output .= '<a class="header-cart" href="' . esc_url( wc_get_cart_url() ) . '" title="' . print( 'View your cart' ) . '">';
		$output .= '<span class="screen-reader-text">Cart</span>';
		$output .= '<div class="cart-icon">';
		$output .= orgnk_get_file_contents( get_template_directory_uri() . '/images/cart.svg' );
		$output .= '</div>';

		if ( $item_count_text > 0 ) {
			$output .= '<div class="cart-count">' . esc_html( $item_count_text ) . '</div>';
		}

		$output .= '</a>';

	}

	return $output;
}

//=======================================================================================================================================================

/**
 * orgnk_woocommerce_account_link()
 * displays a link to the account
 * You can add the account link to header.php like so ...
 * if ( function_exists( 'orgnk_woocommerce_account_link' ) ) { orgnk_woocommerce_account_link(); }
 */
function orgnk_woocommerce_account_link() {

	$output = null;

	if ( orgnk_is_woocommerce_active() ) {

		$output .= '<a class="header-account" href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" title="' . ( is_user_logged_in() ) ? print( 'View your account' ) : print( 'Login or register' ) . '">';
		$output .= '<span class="screen-reader-text">Account</span>';
		$output .= '<div class="account-icon">';
		$output .= orgnk_get_file_contents( get_template_directory_uri() . '/images/account.svg' );
		$output .= '</div>';
		$output .= '</a>';

	}

	return $output;
}



//=======================================================================================================================================================
// WooCommerce template markup overrides
//=======================================================================================================================================================

/**
 * Cart page
 * Wrap product summary table and collaterals in seperate divs
 */
remove_action( 'woocommerce_before_cart', 'action_woocommerce_before_cart', 10, 2 );
remove_action( 'woocommerce_after_cart', 'action_woocommerce_after_cart', 10, 2 );

function orgnk_woocommerce_before_cart() {
	return '<div class="cart-wrap"><div class="cart-summary"><h2>Cart Summary</h2>';
}
add_action( 'woocommerce_before_cart', 'orgnk_woocommerce_before_cart', 10, 2 );

function orgnk_woocommerce_after_cart() {
	return '</div>';
}
add_action( 'woocommerce_after_cart', 'orgnk_woocommerce_after_cart', 10, 2 );

//=======================================================================================================================================================

/**
 * Cart page
 * Closing div tag for 'cart-summary' before collaterals from function above
 */
remove_action( 'woocommerce_after_cart_table', 'action_woocommerce_after_cart_table', 10, 2 );

function orgnk_woocommerce_after_cart_table() {
	return '</div>';
}
add_action( 'woocommerce_after_cart_table', 'orgnk_woocommerce_after_cart_table', 10, 2 );

//=======================================================================================================================================================

/**
 * Checkout Page
 * Remove coupon dropdown
 */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

//=======================================================================================================================================================

/**
 * Checkout page
 * Wrap main checkout form and provide extra class if user is not logged in
 */
remove_action( 'woocommerce_before_checkout_form', 'action_woocommerce_before_checkout_form', 10, 2 );
remove_action( 'woocommerce_after_checkout_form', 'action_woocommerce_after_checkout_form', 10, 2 );

function orgnk_woocommerce_before_checkout_form() {
	return '<div class="checkout-wrap' . ( ! is_user_logged_in() ) ? ' offset-top' : '' . ' ">';
}
add_action( 'woocommerce_before_checkout_form', 'orgnk_woocommerce_before_checkout_form', 10, 2 );

function orgnk_woocommerce_after_checkout_form() {
	return '</div>';
}
add_action( 'woocommerce_after_checkout_form', 'orgnk_woocommerce_after_checkout_form', 10, 2 );

//=======================================================================================================================================================

/**
 * Checkout page
 * Wrap product overview table and collaterals in new wrapper
 */
remove_action( 'woocommerce_checkout_after_customer_details', 'action_woocommerce_checkout_after_customer_details', 10, 2 );
remove_action( 'woocommerce_checkout_after_order_review', 'action_woocommerce_checkout_after_order_review', 10, 2 );

function orgnk_woocommerce_checkout_after_customer_details() {
	return '<div class="order-overview">';
}
add_action( 'woocommerce_checkout_after_customer_details', 'orgnk_woocommerce_checkout_after_customer_details', 10, 2 );

function orgnk_woocommerce_checkout_after_order_review() {
	return '</div>';
}
add_action( 'woocommerce_checkout_after_order_review', 'orgnk_woocommerce_checkout_after_order_review', 10, 2 );

//=======================================================================================================================================================

/**
 * Single product
 * Move product meta above add to cart
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 40 );

//=======================================================================================================================================================

/**
 * Single product
 * Remove link from gallery images
 */
function orgnk_remove_product_gallery_links( $html, $post_id ) {
    return preg_replace( "!<(a|/a).*?>!", '', $html );
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'orgnk_remove_product_gallery_links', 10, 2 );

//=======================================================================================================================================================

/**
 * Single product
 * Add title above price in summary
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

function orgnk_woocommerce_template_single_title() {
	return '<h2 class="product_title entry-title">' . esc_html( the_title() ) . '</h2>';
}
add_action(' woocommerce_single_product_summary', 'orgnk_woocommerce_template_single_title', 5 );

//=======================================================================================================================================================

/**
 * Single product
 * Remove related products
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//=======================================================================================================================================================

/**
 * Product archive
 * Wrap title and price in div
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'action_woocommerce_before_shop_loop_item_title', 10, 2 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'action_woocommerce_after_shop_loop_item_title', 10, 2 );

function orgnk_woocommerce_before_shop_loop_item_title() {
	return '<div class="product-meta">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'orgnk_woocommerce_before_shop_loop_item_title', 10, 2 );

function orgnk_woocommerce_after_shop_loop_item_title() {
	return '</div>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'orgnk_woocommerce_after_shop_loop_item_title', 10, 2 );

//=======================================================================================================================================================

/**
 * Messages/alerts
 * Restructure the 'added to cart message' and include a direct checkout option
 */
function orgnk_wc_add_to_cart_message( $message, $products ) {

	$titles = array();

    foreach ( $products as $product_id => $qty ) {
      $titles[] = strip_tags( get_the_title( $product_id ) );
	}

	$titles = array_filter( $titles );
	$added_text = sprintf( _n( '"%s" has been added to your cart.', '"%s" have been added to your cart.', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

	$message = sprintf( '%s <div class="actions"><a href="%s" class="button">%s</a><a href="%s" class="button">%s</a></div>',
		sprintf( '<span>' . $added_text . '&nbsp;&nbsp;&nbsp;</span>' ),
		esc_url( wc_get_page_permalink( 'checkout' ) ),
		esc_html__( 'Checkout', 'woocommerce' ),
		esc_url( wc_get_page_permalink( 'cart' ) ),
		esc_html__( 'View Cart', 'woocommerce' ) );

	return $message;
}
add_filter( 'wc_add_to_cart_message_html', 'orgnk_wc_add_to_cart_message', 10, 2 );

//=======================================================================================================================================================

/**
 * Messages/alerts
 * Reword the 'removed from cart message'
 */
function orgnk_woocommerce_cart_item_removed_title( $message, $cart_item ) {

    $product = wc_get_product( $cart_item['product_id'] );

    if ( $product ) {
		$message = sprintf( __('"%s" has been'), $product->get_name() );
	}

    return $message;
}
add_filter( 'woocommerce_cart_item_removed_title', 'orgnk_woocommerce_cart_item_removed_title', 12, 2 );

//=======================================================================================================================================================

/**
 * Messages/alerts
 * Fix the missing gap on 'added to cart message' before the undo button
 */
function cart_undo_translation( $translation, $text, $domain ) {

    if ( $text === 'Undo?' ) {
        $translation =  __( '&nbsp;&nbsp;Undo?', $domain );
    }
    return $translation;
}
add_filter( 'gettext', 'cart_undo_translation', 35, 3 );

//=======================================================================================================================================================

/**
 * Account
 * Add titles above each page's content
 */

// Orders page
function orgnk_woocommerce_before_account_orders() {
	return '<h2 class="account-section-title">Your orders</h2>';
}
add_action( 'woocommerce_before_account_orders', 'orgnk_woocommerce_before_account_orders', 5 );

// Addresses page
function orgnk_woocommerce_before_edit_account_address_form() {
	return '<h2 class="account-section-title">Your addresses</h2>';
}
add_action( 'woocommerce_before_edit_account_address_form', 'orgnk_woocommerce_before_edit_account_address_form', 5 );

// Payment methods page
function orgnk_woocommerce_before_account_payment_methods() {
	return '<h2 class="account-section-title">Your payment methods</h2>';
}
add_action( 'woocommerce_before_account_payment_methods', 'orgnk_woocommerce_before_account_payment_methods', 5 );

// Account details page
function orgnk_woocommerce_before_edit_account_form() {
	return '<h2 class="account-section-title">Your Account Details</h2>';
}
add_action( 'woocommerce_before_edit_account_form', 'orgnk_woocommerce_before_edit_account_form', 5 );
