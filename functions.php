<?php
//=======================================================================================================================================================
// Global variables and config
//=======================================================================================================================================================

// Current script version for this theme - used to force browsers to serve new CSS & JS when changes are made
define( 'SCRIPT_VERSION', '1.0' );

// Toggle whether this theme uses Gutenberg or not
define( 'SUPPORTS_GUTENBERG', false );

// Toggle whether this theme supports WooCommerce or not
define( 'SUPPORTS_WOOCOMMERCE', false );

// Toggle whether this theme uses the default search functionality
define( 'SUPPORTS_SEARCH', true );

// Toggle whether this theme uses the default comment functionality
define( 'SUPPORTS_COMMENTS', false );

// Toggle whether this theme uses the default comment functionality
define( 'SUPPORTS_TERM_ARCHIVES', true );

// Front page ID
define( 'FRONT_PAGE_ID', get_option( 'page_on_front' ) );

// Page for posts ID
define( 'PAGE_FOR_POSTS_ID', get_option( 'page_for_posts' ) );

// Number of search results per page
define( 'SEARCH_POSTS_PER_PAGE', 12 );

// This website's production URL - used to check the current host environment below
$production_domain = 'www.organikvanilla.com.au';

// Application environment
if ( stristr( $_SERVER['HTTP_HOST'], $production_domain ) ) {
	define( 'HOST_ENVIRONMENT', 'production' );
} else {
	define( 'HOST_ENVIRONMENT', 'development' );
}



//=======================================================================================================================================================
// Theme setup, root functions and definitions
//=======================================================================================================================================================

/**
 * orgnk_theme_setup()
 * Initialise the theme
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package orgnk_client_textdomain
 */
if ( ! function_exists( 'orgnk_theme_setup' ) ) {

	// Sets up theme defaults and registers support for various WordPress features
	// Note that this function is hooked into the after_setup_theme hook, which runs before the init hook
	// The init hook is too late for some features, such as indicating support for post thumbnails
	function orgnk_theme_setup() {

		// Make the theme available for translation - translations can be filed in the /languages/ directory
		load_theme_textdomain( 'orgnk_client_textdomain', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title
		// By adding theme support, we declare that this theme does not use a hard-coded <title> tag in the document head, and expect WordPress to provide it for us
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages
		add_theme_support( 'post-thumbnails' );

		// This theme doesn't support a custom header image
		remove_theme_support( 'custom-header' );

		// This theme uses wp_nav_menu() in one location
		$menus = array(
			'main-menu' 		=> 'Main Menu',
			'footer-menu-1' 	=> 'Footer Menu 1',
			'footer-menu-2' 	=> 'Footer Menu 2',
			'footer-bottom' 	=> 'Footer Bottom Menu'
		);
		register_nav_menus( $menus );

		// Switch default core markup for search form, comment form, and comments to output valid HTML5
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme editor-style.css to the content editor
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/editor.min.css' );

		// Add theme support for selective refresh for widgets
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
}
add_action( 'after_setup_theme', 'orgnk_theme_setup' );



//=======================================================================================================================================================
// Register widget area
//=======================================================================================================================================================

/**
 * orgnk_custom_theme_widgets()
 * Register custom widget areas
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function orgnk_custom_theme_widgets() {

    register_sidebar( array(
		'name'          => 'Pages Sidebar',
		'id'            => 'pages-sidebar',
		'description'   => 'Add widgets here.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<span class="h3">',
		'after_title'   => '</span>',
	) );

	register_sidebar( array(
		'name'          => 'Posts Sidebar',
		'id'            => 'posts-sidebar',
		'description'   => 'Add widgets here.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<span class="h3">',
		'after_title'   => '</span>',
	) );
}
add_action( 'widgets_init', 'orgnk_custom_theme_widgets' );



//=======================================================================================================================================================
// Load functionality bespoke to this theme
//=======================================================================================================================================================

// Enqueue CSS & JS
require get_template_directory() . '/inc/styles-scripts.php';

// Additional functions for this client's version of the theme
require get_template_directory() . '/inc/extras.php';

// Admin theme settings
require get_template_directory() . '/inc/theme-settings.php';

// Tiny MCE setup
require get_template_directory() . '/inc/tiny-mce-setup.php';

// Gutenberg setup file if Gutenberg is being used
if ( SUPPORTS_GUTENBERG === true ) {
	require get_template_directory() . '/inc/gutenberg-setup.php';
}

// Gutenberg setup file if Gutenberg is being used
if ( SUPPORTS_WOOCOMMERCE === true ) {
	require get_template_directory() . '/inc/woocommerce-setup.php';
}



//=======================================================================================================================================================
// Global Organik Core functionality
//=======================================================================================================================================================

// Check the orgnk-core directory exists first
if ( is_dir( get_template_directory() . '/inc/orgnk-core' ) ) {

	// Include each .php file found in the directory
	foreach ( glob( get_template_directory() . '/inc/orgnk-core/*.php') as $file ) {
		require_once $file;
	}
}

add_filter('orgnk_image_resize_sizes', function ($sizes) {
    // Add banner size
    $sizes['preview'] = [
        'width' => 256,
        'height' => 256,
        'options' => [
            'mode' => 'crop',
        ]
    ];

	$sizes['xl'] = [
        'width' => 2000,
        'height' => 2000,
		'options' => [
			'mode' => 'auto',
			'quality' => 100,
		]
    ];
	$sizes['xl.webp'] = [
        'width' => 2000,
        'height' => 2000,
		'options' => [
			'mode' => 'auto',
			'extension' => 'webp',
			'quality' => 100,
		]
    ];

	$sizes['lg'] = [
        'width' => 1000,
        'height' => 1000,
		'options' => [
			'mode' => 'auto',
			'quality' => 100,
		]
    ];
	$sizes['lg.webp'] = [
        'width' => 1000,
        'height' => 1000,
		'options' => [
			'mode' => 'auto',
			'extension' => 'webp',
			'quality' => 100,
		]
    ];

	$sizes['thumb'] = [
        'width' => 500,
        'height' => 500,
		'options' => [
			'mode' => 'auto',
			'quality' => 100,
		]
    ];
	$sizes['thumb.webp'] = [
        'width' => 500,
        'height' => 500,
		'options' => [
			'mode' => 'auto',
			'extension' => 'webp',
			'quality' => 100,
		]
    ];

    return $sizes;
});

add_filter('orgnk_image_resize_default_thumbnail', function () {
    return 'preview';
});


//=======================================================================================================================================================

/*
 * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
 */
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
	  wp_die('No post to duplicate has been supplied!');
	}
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
	  return;
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
	  /*
	   * new post data array
	   */
	  $args = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $new_post_author,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'draft',
		'post_title'     => $post->post_title,
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order
	  );
	  /*
	   * insert the post by wp_insert_post() function
	   */
	  $new_post_id = wp_insert_post( $args );
	  /*
	   * get all current post terms ad set them to the new post draft
	   */
	  $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
	  foreach ($taxonomies as $taxonomy) {
		$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
		wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	  }
	  /*
	   * duplicate all post meta just in two SQL queries
	   */
	  $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	  if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $meta_info) {
		  $meta_key = $meta_info->meta_key;
		  if( $meta_key == '_wp_old_slug' ) continue;
		  $meta_value = addslashes($meta_info->meta_value);
		  $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}
		$sql_query.= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	  }
	  /*
	   * finally, redirect to the edit post screen for the new draft
	   */
	  wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
	  exit;
	} else {
	  wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
  }

  add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
  /*
   * Add the duplicate link to action list for post_row_actions
   */
  function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
	  $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
  }
  add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
  add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);