<?php
class Organik_Page_For_Post_Type_Archive {

	/**
     * The single instance of Organik_Services
     */
	protected static $instance = null;

	/**
     * Other protected class variables
     */
	protected $excludes = array();
	protected $original_slugs = array();

	/**
     * Main class instance
     * Ensures only one instance of this class is loaded or can be loaded
     */
	public static function init() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
     * Constructor function
     */
	public function __construct() {

		// Add settings fields to 'reading' settings page
		add_action( 'admin_init', array( $this, 'orgnk_pfpt_reading_settings_options' ) );

		// Update post type objects
		add_action( 'registered_post_type', array( $this, 'orgnk_pfpt_update_post_type' ), 11, 2 );

		// Menu classes
		add_filter( 'wp_nav_menu_objects', array( $this, 'orgnk_pfpt_filter_wp_nav_menu_objects' ), 1, 2 );

		// edit.php view
		add_filter( 'display_post_states', array( $this, 'orgnk_pfpt_post_states' ), 100, 2 );

		// Post status changes/deletion
		add_action( 'transition_post_status', array( $this, 'action_transition_post_status' ), 10, 3 );
		add_action( 'deleted_post', array( $this, 'action_deleted_post' ), 10 );

		// Notice when editing pages set as a post type archive
		add_action( 'edit_form_after_title', array( $this, 'orgnk_pfpt_edit_archive_notice' ) );

		if ( class_exists( 'ACF' ) ) {

			// Add the 'unique page/post state' as an option to the ACF location rules for field groups
			add_filter( 'acf/location/rule_values/page_type', array( $this, 'orgnk_pfpt_acf_page_type_option' ) );

			// Rule match the location option with the current edit screen to display the field group and fields
			add_filter( 'acf/location/rule_match/page_type', array( $this, 'orgnk_pfpt_acf_rule_match' ), 10, 4);
		}
	}




	// Add settings fields to 'reading' settings page
	public function orgnk_pfpt_reading_settings_options() {

		$cpts = get_post_types( array(), 'objects' );

		add_settings_section(
			'page_for_post_type',
			'Pages for Post Type Archives',
			array( $this, 'orgnk_pfpt_settings_content' ),
			'reading'
		);

		foreach ( $cpts as $cpt ) {

			if ( ! $cpt->has_archive ) continue;

			$id = "page_for_{$cpt->name}";
			$value = get_option( $id );

			// Flush rewrite rules when the option is changed
			register_setting(
				'reading',
				$id,
				array( $this, 'validate_field' )
			);

			add_settings_field(
				$id,
				$cpt->labels->name,
				array( $this, 'orgnk_pfpt_settings_field' ),
				'reading',
				'page_for_post_type',
				array(
					'name'      		=> $id,
					'post_type' 		=> $cpt,
					'value'     		=> $value
				)
			);
		}
	}


	public function orgnk_pfpt_settings_content() {
		echo '<strong>Important:</strong> changes to these settings will flush permalinks. Do not changes these settings too often!';
	}



	// Callback function to create field inputs and labels
	public function orgnk_pfpt_settings_field( $args ) {

		$value = intval( $args['value'] );
		$default = $args['post_type']->name;

		if ( isset( $this->original_slugs[ $args['post_type']->name ] ) ) {
			$default = $this->original_slugs[ $args['post_type']->name ];
		}

		wp_dropdown_pages( array(
			'post_type'        		=> 'page',
			'name'             		=> esc_attr( $args['name'] ),
			'id'               		=> esc_attr( $args['name'] . '_dropdown' ),
			'sort_column'      		=> 'menu_order post_title',
			'echo'             		=> 1,
			'class'               	=> 'orgnk-pfpt-option',
			'selected'         		=> $value,
			'show_option_none' 		=> '— Select —',
		));
	}




	/**
	 * Add an indicator to show if a page is set as a post type archive in the pages list
	 *
	 * @param array $post_states An array of post states to display after the post title
	 * @param WP_Post $post The current post object
	 * @return array
	 */
	public function orgnk_pfpt_post_states( $post_states, $post ) {

		$post_type = $post->post_type;
		$cpts = get_post_types( array( 'public' => true ), 'objects' );

		if ( 'page' === $post_type ) {
			if ( in_array( $post->ID, $this->get_cpt_archive_page_ids() ) ) {
				$cpt = array_search( $post->ID, $this->get_cpt_archive_page_ids() );
				$post_states["page_for_{$post_type}"] = sprintf( esc_html__( '%1$s Archive Page', 'pfpt' ), $cpts[ $cpt ]->labels->name );
			}
		}

		return $post_states;
	}




	/**
	 * Display a notice on the archive dummy page to warn the user they are editing an dummy archive page
	 *
	 * @param WP_Post $post The current post object
	 */
	public function orgnk_pfpt_edit_archive_notice( $post ) {

		$output = '';
		$post_type = array_search( $post->ID, $this->get_cpt_archive_page_ids() );
		$cpts = get_post_types( array( 'public' => true ), 'objects' );

		if ( is_admin() && ( 'page' === $post->post_type ) ) {

			if( in_array($post->ID, $this->get_cpt_archive_page_ids() ) ) {

				$cpt = array_search( $post->ID, $this->get_cpt_archive_page_ids() );

				$output = '<div class="notice notice-warning inline" style="margin: 15px 0 0 0;">';
				$output .= '<p>You are currently editing the page set as the archive for <a href="/wp-admin/edit.php?post_type=' . $post_type . '">' . $cpts[ $cpt ]->labels->name . '</a>. Page templates will not work for this page, unless you unset this page as an archive in the <a href="/wp-admin/options-reading.php">Reading Settings</a>.</p>';
				$output .= '</div>';
			}
		}

		echo $output;
	}




	//=======================================================================================================================================================
	// Behaviour for saving or changes to page status
	//=======================================================================================================================================================

	/**
	 * Flush rewrites and checks if the ID has been used already on this save
	 *
	 * @param $new_value
	 * @return int
	 */
	public function validate_field( $new_value ) {

		flush_rewrite_rules();

		if ( in_array( $new_value, $this->excludes ) ) {
			return 0;
		}

		$this->excludes[] = $new_value;
		return intval( $new_value );

	}




	/**
	 * Delete the setting for the corresponding post type if the page status is transitioned to anything other than published
	 *
	 * @param $new_status
	 * @param $old_status
	 * @param WP_Post $post
	 */
	public function action_transition_post_status( $new_status, $old_status, WP_Post $post ) {

		if ( 'publish' !== $new_status ) {
			$post_type = array_search( $post->ID, $this->get_cpt_archive_page_ids() );
			if ( $post_type ) {
				delete_option( "page_for_{$post_type}" );
				flush_rewrite_rules();
			}
		}
	}




	/**
	 * Delete relevant option if a page for the archive is deleted
	 *
	 * @param int $post_id
	 */
	public function action_deleted_post( $post_id ) {

		$post_type = array_search( $post_id, $this->get_cpt_archive_page_ids() );

		if ( $post_type ) {
			delete_option( "page_for_{$post_type}" );
			flush_rewrite_rules();
		}
	}




	/**
	 * Modifies the post type object to update the permastructure based on the page chosen
	 *
	 * @param $post_type
	 * @param $args
	 */
	public function orgnk_pfpt_update_post_type( $post_type, $args ) {

		global $wp_post_types, $wp_rewrite;

		$post_type_page = get_option( "page_for_{$post_type}" );

		if ( ! $post_type_page ) {
			return;
		}

		// Make sure we don't create rules for an unpublished page preview URL
		if ( 'publish' !== get_post_status( $post_type_page ) ) {
			return;
		}

		// Get the old slug
		$args->rewrite = (array) $args->rewrite;
		$old_slug = isset( $args->rewrite['slug'] ) ? $args->rewrite['slug'] : $post_type;

		// Store this for our options page
		$this->original_slugs[ $post_type ] = $old_slug;

		// Get page slug
		$slug = esc_url( get_permalink( $post_type_page ) );
		$slug = str_replace( home_url(), '', $slug );
		$slug = trim( $slug, '/' );

		$args->rewrite = wp_parse_args( array( 'slug' => $slug ), $args->rewrite );
		$args->has_archive = $slug;

		// Rebuild rewrite rules
		if ( is_admin() || '' != get_option( 'permalink_structure' ) ) {

			if ( $args->has_archive ) {

				$archive_slug = $args->has_archive === true ? $args->rewrite['slug'] : $args->has_archive;

				if ( $args->rewrite['with_front'] ) {
					$archive_slug = substr( $wp_rewrite->front, 1 ) . $archive_slug;
				} else {
					$archive_slug = $wp_rewrite->root . $archive_slug;
				}

				add_rewrite_rule( "{$archive_slug}/?$", "index.php?post_type=$post_type", 'top' );

				if ( $args->rewrite['feeds'] && $wp_rewrite->feeds ) {
					$feeds = '(' . trim( implode( '|', $wp_rewrite->feeds ) ) . ')';
					add_rewrite_rule( "{$archive_slug}/feed/$feeds/?$", "index.php?post_type=$post_type" . '&feed=$matches[1]', 'top' );
					add_rewrite_rule( "{$archive_slug}/$feeds/?$", "index.php?post_type=$post_type" . '&feed=$matches[1]', 'top' );
				}

				if ( $args->rewrite['pages'] ) {
					add_rewrite_rule( "{$archive_slug}/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", "index.php?post_type=$post_type" . '&paged=$matches[1]', 'top' );
				}
			}

			$permastruct_args = $args->rewrite;
			$permastruct_args['feed'] = $permastruct_args['feeds'];

			// support plugins that enable 'permastruct' option
			if ( isset( $args->rewrite['permastruct'] ) ) {
				$permastruct = str_replace( $old_slug, $slug, $args->rewrite['permastruct'] );
			} else {
				$permastruct = "{$args->rewrite['slug']}/%$post_type%";
			}

			add_permastruct( $post_type, $permastruct, $permastruct_args );

		}

		// update the global
		$wp_post_types[ $post_type ] = $args;
	}




	/**
	 * Make sure menu items for our pages get the correct classes assigned
	 *
	 * @param $sorted_items
	 * @param $args
	 * @return array
	 */
	public function orgnk_pfpt_filter_wp_nav_menu_objects( $sorted_items, $args ) {

		global $wp_query;
		$queried_object = get_queried_object();

		if ( ! $queried_object ) {
			return $sorted_items;
		}

		$object_post_type = false;

		if ( is_singular() ) {
			$object_post_type = $queried_object->post_type;
		}

		if ( is_post_type_archive() ) {
			$object_post_type = $queried_object->name;
		}

		if ( is_archive() && is_string( $wp_query->get( 'post_type' ) ) ) {
			$query_post_type  = $wp_query->get( 'post_type' );
			$object_post_type = $query_post_type ?: 'post';
		}

		if ( ! $object_post_type ) {
			return $sorted_items;
		}

		// get page ID array
		$page_ids = $this->get_cpt_archive_page_ids();

		if ( ! isset( $page_ids[ $object_post_type ] ) ) {
			return $sorted_items;
		}

		foreach ( $sorted_items as $item ) {
			if ( $item->type === 'post_type' && $item->object === 'page' && intval( $item->object_id ) === intval( $page_ids[ $object_post_type ] ) ) {
				if ( is_singular( $object_post_type ) ) {
					$item->classes[]            	= 'current-page-ancestor';
					$item->current_item_ancestor	= true;
					$sorted_items               	= $this->add_ancestor_class( $item, $sorted_items );
				}
				if ( is_post_type_archive( $object_post_type ) ) {
					$item->classes[]   				= 'current-menu-item';
					$item->current_item				= true;
					$sorted_items      				= $this->add_ancestor_class( $item, $sorted_items );
				}
				if ( is_archive() && $object_post_type === $wp_query->get( 'post_type' ) ) {
					$sorted_items					= $this->add_ancestor_class( $item, $sorted_items );
				}
			}
		}

		return $sorted_items;
	}





	//=======================================================================================================================================================
	// ACF Intergration
	//=======================================================================================================================================================

	// Add the 'unique page/post state' above as an option to the ACF location rules for field groups
	function orgnk_pfpt_acf_page_type_option( $choices ) {

		$cpts = get_post_types( array(), 'objects' );

		foreach ( $cpts as $cpt ) {

			if ( ! $cpt->has_archive ) {
				continue;
			}

			$choices["page_for_{$cpt->name}"] = $cpt->labels->name  . ' Archive Page';

		}

		return $choices;
	}



	/**
	 * Rule match the location option with the current edit screen to display the field group and fields
	 * This occurs when an edit screen is loaded and ACF checks every existing field group location rule
	 *
	 * @param WP_Post $post The current post object
	 */
	function orgnk_pfpt_acf_rule_match( $match, $rule, $options, $field_group ) {

		$cpts = get_post_types( array('_builtin' => false), 'objects' );

		foreach ( $cpts as $cpt ) {

			if ( ! $cpt->has_archive ) {
				continue;
			}

			// Check if the rule value is set before attempting to match
			if( $rule['value'] == 'page_for_' . $cpt->name ) {
				$match = ( intval( get_option('page_for_' . $cpt->name ) ) === get_the_ID() );

				// Reverse if operator is set to 'not equal to'
				if( $rule['operator'] == '!=' ) {
					$match = !$match;
				}
			}
		}

		return $match;
	}





	//=======================================================================================================================================================
	// Protected Methods
	//=======================================================================================================================================================

	/**
	 * Gets an array with post types as keys and corresponding page IDs as values
	 *
	 * @return array
	 */
	protected function get_cpt_archive_page_ids() {

		$page_ids = array();
		$cpts = get_post_types( array(), 'objects' );

		foreach ( $cpts as $cpt ) {

			if ( ! $cpt->has_archive ) {
				continue;
			}

			if ( 'post' === $cpt->name ) {
				$page_id = PAGE_FOR_POSTS_ID;
			} else {
				$page_id = get_option( "page_for_{$cpt->name}" );
			}

			if ( ! $page_id ) {
				continue;
			}

			$page_ids[ $cpt->name ] = $page_id;
		}

		return $page_ids;
	}




	/**
	 * Recursively set the ancestor class
	 *
	 * @param object $child
	 * @param array $items
	 * @return array
	 */
	protected function add_ancestor_class( $child, $items ) {

		if ( ! intval( $child->menu_item_parent ) ) {
			return $items;
		}

		foreach ( $items as $item ) {
			if ( intval( $item->ID ) === intval( $child->menu_item_parent ) ) {

				$item->classes[] = 'current-menu-item-ancestor';
				$item->current_item_ancestor = true;

				if ( intval( $item->menu_item_parent ) ) {
					$items = $this->add_ancestor_class( $item, $items );
				}
				break;
			}
		}

		return $items;
	}
}

Organik_Page_For_Post_Type_Archive::init();




//=======================================================================================================================================================
// Helper functions
//=======================================================================================================================================================

/**
 * orgnk_get_post_type_archive_id()
 * Get the page ID for the given or current post type
 */
function orgnk_get_post_type_archive_id( $post_type = false ) {

	if ( ! $post_type && is_post_type_archive() ) {
		$post_type = get_queried_object()->name;
	}

	if ( ! $post_type && is_singular() ) {
		$post_type = get_queried_object()->post_type;
	}

	if ( ! $post_type ) {
		$post_type = get_post_type();
	}

	if ( $post_type && in_array( $post_type, get_post_types() ) ) {

		if ( get_option( "page_for_{$post_type}" ) ) {
			return intval( get_option( "page_for_{$post_type}" ) );
		}
	}
	return false;
}
