<?php
// Return early if the support for taxonomy archive functionality is not defined or is disabled
if ( ! defined( 'SUPPORTS_TERM_ARCHIVES' ) || SUPPORTS_TERM_ARCHIVES === false ) return;

/**
 * Define constant variables
 */
define( 'ORGNK_TERM_ARCHIVES_CPT_NAME', 'term-archive' );
define( 'ORGNK_TERM_ARCHIVES_SINGLE_NAME', 'Term Archive' );
define( 'ORGNK_TERM_ARCHIVES_PLURAL_NAME', 'Term Archives' );

class Organik_Term_Archives {

	/**
     * The single instance of Organik_Term_Archives
     */
	private static $instance = null;

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

        // Hook into the 'init' action to add the Custom Post Type
        add_action( 'init', array( $this, 'orgnk_term_archives_cpt_register' ) );

        // Change the title placeholder
        add_filter( 'enter_title_here', array( $this, 'orgnk_term_archives_cpt_title_placeholder' ) );

        // Add post meta to the admin list view for this CPT
		add_filter( 'manage_' . ORGNK_TERM_ARCHIVES_CPT_NAME . '_posts_columns', array( $this, 'orgnk_term_archives_admin_table_column' ) );
		add_action( 'manage_' . ORGNK_TERM_ARCHIVES_CPT_NAME . '_posts_custom_column', array( $this, 'orgnk_term_archives_admin_table_content' ), 10, 2 );

        // Add a notice to the admin list view for this CPT
		add_action( 'admin_notices', array( $this, 'orgnk_term_archives_admin_table_notice' ) );

        // Add a notice to the post edit view for this CPT
		add_action( 'edit_form_after_title', array( $this, 'orgnk_term_archives_admin_post_notice' ) );

        if ( class_exists( 'ACF' ) ) {

			// Insert ACF fields
			add_filter( 'init', array( $this, 'orgnk_term_archives_acf_fields' ) );

            // Dynamically load the supported taxonomies and their terms into the ACF select fields
            add_filter( 'acf/load_field/name=term_archive_linked_taxonomy', array( $this, 'orgnk_term_archives_populate_linked_taxonomy_field' ) );
            add_filter( 'acf/load_field/name=term_archive_linked_term', array( $this, 'orgnk_term_archives_populate_linked_term_field' ) );
        }
	}

    //=======================================================================================================================================================

	/**
	 * orgnk_term_archives_cpt_register()
	 * Register the custom post type
	 */
	public function orgnk_term_archives_cpt_register() {

		$labels = array(
			'name'                      	=> ORGNK_TERM_ARCHIVES_PLURAL_NAME,
			'singular_name'             	=> ORGNK_TERM_ARCHIVES_SINGLE_NAME,
			'menu_name'                 	=> ORGNK_TERM_ARCHIVES_PLURAL_NAME,
			'name_admin_bar'            	=> ORGNK_TERM_ARCHIVES_SINGLE_NAME,
			'archives'              		=> 'Term archives',
			'attributes'            		=> 'Term Archive Attributes',
			'parent_item_colon'     		=> 'Parent term archive:',
			'all_items'             		=> 'All term archives',
			'add_new_item'          		=> 'Add new',
			'add_new'               		=> 'Add new',
			'new_item'              		=> 'New term archive',
			'edit_item'             		=> 'Edit term archive',
			'update_item'           		=> 'Update term archive',
			'view_item'             		=> 'View term archive',
			'view_items'            		=> 'View term archives',
			'search_items'          		=> 'Search term archive',
			'not_found'             		=> 'Not found',
			'not_found_in_trash'    		=> 'Not found in Trash',
			'featured_image'        		=> 'Featured Image',
			'set_featured_image'    		=> 'Set featured image',
			'remove_featured_image' 		=> 'Remove featured image',
			'use_featured_image'    		=> 'Use as featured image',
			'insert_into_item'      		=> 'Insert into term archive',
			'uploaded_to_this_item' 		=> 'Uploaded to this term archive',
			'items_list'            		=> 'Term archives list',
			'items_list_navigation' 		=> 'Term archives list navigation',
			'filter_items_list'     		=> 'Filter term archives list'
		);

		$args = array(
			'label'                 		=> ORGNK_TERM_ARCHIVES_SINGLE_NAME,
			'description'           		=> 'Manage content for taxonomy term archives',
			'labels'                		=> $labels,
			'supports'              		=> array( 'title', 'thumbnail', 'revisions' ),
			'taxonomies'            		=> array(),
			'hierarchical'          		=> false,
			'public'                		=> true,
			'show_ui'               		=> true,
			'show_in_menu'          		=> true,
			'menu_position'         		=> 20,
			'menu_icon'             		=> 'dashicons-editor-table',
			'show_in_admin_bar'     		=> true,
			'show_in_nav_menus'     		=> true,
			'can_export'            		=> true,
			'has_archive'           		=> false, // The slug for archive, bool toggle archive on/off
			'publicly_queryable'    		=> false, // Bool toggle single on/off
			'exclude_from_search'   		=> false,
			'capability_type'       		=> 'page',
			'rewrite'						=> false
		);
		register_post_type( ORGNK_TERM_ARCHIVES_CPT_NAME, $args );
	}

    //=======================================================================================================================================================

    /**
	 * orgnk_term_archives_cpt_title_placeholder()
	 * Change CPT title placeholder on edit screen
	 */
	public function orgnk_term_archives_cpt_title_placeholder( $title ) {

		$screen = get_current_screen();

		if ( $screen && $screen->post_type == ORGNK_TERM_ARCHIVES_CPT_NAME ) {
			return 'Add term archive title';
		}

		return $title;
	}

    //=======================================================================================================================================================

    /**
	 * orgnk_term_archives_admin_table_column()
	 * Register new column(s) in admin list view
	 */
	public function orgnk_term_archives_admin_table_column( $defaults ) {

		$new_order = array();

		foreach ( $defaults as $key => $value ) {
			// When we find the date column, slip in the new column before it
			if ( $key == 'date' ) {
				$new_order['term_archive_linked_taxonomy'] = 'Taxonomy';
				$new_order['term_archive_linked_term'] = 'Term';
			}
			$new_order[$key] = $value;
		}

		return $new_order;
	}

    //=======================================================================================================================================================

	/**
	 * orgnk_term_archives_admin_table_content()
	 * Return the content for the new admin list view columns for each post
	 */
	public function orgnk_term_archives_admin_table_content( $column_name, $post_id ) {

        $taxonomy = esc_html( get_post_meta( $post_id, 'term_archive_linked_taxonomy', true ) );
        $taxonomy = $taxonomy ? get_taxonomy( $taxonomy ) : null;

        $term = esc_html( get_post_meta( $post_id, 'term_archive_linked_term', true ) );
        $term = $taxonomy && $term ? get_term_by( 'term_id', $term, $taxonomy->name ) : null;

		if ( $column_name == 'term_archive_linked_taxonomy' && $taxonomy ) {
			echo $taxonomy->labels->name;
		}

		if ( $column_name == 'term_archive_linked_term' && $term ) {
			echo $term->name;
		}
	}

    //=======================================================================================================================================================

	/**
	 * orgnk_term_archives_admin_table_notice()
	 * Add a notice to the admin list view explaining the purpose of this CPT
	 */
	public function orgnk_term_archives_admin_table_notice() {

		$output = '';
		$current_screen = get_current_screen();

		if ( is_admin() && $current_screen && $current_screen->post_type === ORGNK_TERM_ARCHIVES_CPT_NAME && $current_screen->base === 'edit' ) {
			$output .= '<div class="notice notice-info" style="margin: 15px 0;">';
            $output .= '<p><strong style="display: block; font-size: 16px; margin: 0 0 10px 0;">About this post type</strong>This post type is used to add content to taxonomy term archives (where applicable). Depending on your theme, not all taxonomies may support this functionality. ';
            $output .= 'These entries are not publicly visible and only act as dummy entries to hold the relevant content. The content in these entries will only display if both a taxonomy and term are assigned.</p>';
            $output .= '<hr style="margin: 10px 0;">';
            $output .= '<p><strong style="display: block; font-size: 16px; margin: 0 0 10px 0;">Important</strong>None of these entries should be assigned to the same term.</p>';
			$output .= '</div>';
		}

		echo $output;
	}

    //=======================================================================================================================================================

	/**
	 * orgnk_term_archives_admin_post_notice()
	 * Add a notice to the post edit view about how events are ordered in the front-end
	 */
	public function orgnk_term_archives_admin_post_notice( $post ) {

		$output = '';
		$current_screen = get_current_screen();

		if ( is_admin() && $current_screen && $current_screen->post_type === ORGNK_TERM_ARCHIVES_CPT_NAME ) {
			$output .= '<div class="notice notice-warning inline" style="margin: 15px 0;">';
            $output .= '<p><strong>Important: </strong>the entry title will display as written above for this term archive on the front-end. If this entries\' title is not the term name, it is recommended to set a custom title the same as or including the term name.</p>';
			$output .= '</div>';
		}

		echo $output;
	}

	//=======================================================================================================================================================

	/**
	 * orgnk_term_archives_acf_fields()
	 * Manually insert ACF fields for this CPT
	 */
	function orgnk_term_archives_acf_fields() {

		// Return early if ACF or the CPT isn't active
		if ( ! class_exists( 'ACF' ) || ! function_exists( 'acf_add_local_field_group' ) || ! defined( 'ORGNK_TERM_ARCHIVES_CPT_NAME' ) || ! is_admin() ) return;

		// Field Group - Term Archive Settings
		acf_add_local_field_group( array(
			'key' 				=> 'group_5f755ab5bd3f4',
			'title'				=> 'Term Archive Settings',
			'fields'			=> array(

				//	Field - Linked Taxonomy - Select
				array(
					'key'				=> 'field_60459be6a3647',
					'label'				=> 'Taxonomy',
					'name' 				=> 'term_archive_linked_taxonomy',
					'type' 				=> 'select',
					'instructions' 		=> '',
					'required' 			=> 0,
					'conditional_logic' => 0,
					'wrapper' 			=> array(
						'width' 			=> '',
						'class' 			=> '',
						'id' 				=> '',
					),
					'choices' 			=> array(),
					'default_value' 	=> false,
					'allow_null' 		=> 1,
					'multiple' 			=> 0,
					'ui' 				=> 0,
					'return_format' 	=> 'value',
					'ajax' 				=> 0,
					'placeholder' 		=> '',
				),

				// Field - Linked Term - Select
				array(
					'key' 				=> 'field_6045cc5cdabf6',
					'label'				=> 'Term',
					'name' 				=> 'term_archive_linked_term',
					'type' 				=> 'select',
					'instructions'		=> '',
					'required' 			=> 0,
					'conditional_logic' => 0,
					'wrapper'			=> array(
						'width' 			=> '',
						'class' 			=> '',
						'id' 				=> '',
					),
					'choices'			=> array(),
					'default_value' 	=> false,
					'allow_null' 		=> 1,
					'multiple' 			=> 0,
					'ui' 				=> 0,
					'return_format' 	=> 'value',
					'ajax' 				=> 0,
					'placeholder' 		=> '',
				),
				array(
					'key' 				=> 'field_6045b88bf98bf',
					'label'				=> 'Important',
					'name' 				=> '',
					'type' 				=> 'message',
					'instructions' 		=> '',
					'required' 			=> 0,
					'conditional_logic' => 0,
					'wrapper' 			=> array(
						'width' 			=> '',
						'class' 			=> '',
						'id' 				=> '',
					),
					'message'				=> 'When selecting or changing the taxonomy, you must save this entry to refresh the list of terms.<br><br>Make sure that no other entries are assigned to the chosen term, otherwise the content in this entry may not display.',
					'new_lines'				=> 'wpautop',
					'esc_html'				=> 0,
				),
			),

			// Location Rules - Single Term Archive
			'location' 						=> array(
				array(
					array(
						'param'						=> 'post_type',
						'operator'					=> '==',
						'value'						=> 'term-archive',
					),
				),
			),
			'menu_order'				=> 0,
			'position'					=> 'side',
			'style'						=> 'default',
			'label_placement'			=> 'top',
			'instruction_placement'		=> 'label',
			'hide_on_screen'			=> '',
			'active'					=> true,
			'description'				=> '',
		));
	}

    //=======================================================================================================================================================

    /**
     * orgnk_term_archives_populate_linked_taxonomy_field()
     * Dynamically populates the 'term_archive_linked_taxonomy' ACF field for this CPT with the taxonomies set in the orgnk_term_archives_supported_taxonomies() function - see extras.php
     */
    function orgnk_term_archives_populate_linked_taxonomy_field( $field ) {

        // Get the current screen
        $screen = get_current_screen();

        // Reset the choices
        $field['choices'] = array();

        // Check first that we're on the edit screen for this CPT
        // This is important, as without it this function will also attempt to populate the field choices in the field group edit screen
		if ( $screen && $screen->post_type == ORGNK_TERM_ARCHIVES_CPT_NAME ) {

            // Get the supported taxonomies
            $taxonomies = function_exists( 'orgnk_term_archives_supported_taxonomies' ) ? orgnk_term_archives_supported_taxonomies() : null;

            // Loop through supported taxonomies and add them to the field choices
            if ( $taxonomies ) {
                foreach ( $taxonomies as $taxonomy ) {
                    $field['choices'][$taxonomy] = get_taxonomy( $taxonomy )->labels->name;
                }
            }
		}

        return $field;
    }

    //=======================================================================================================================================================

    /**
     * orgnk_term_archives_populate_linked_term_field()
     * Dynamically populates the 'term_archive_linked_term' ACF field for this CPT with the terms from the taxonomy selected in the 'term_archive_linked_taxonomy' ACF field
     */
    function orgnk_term_archives_populate_linked_term_field( $field ) {

        // Get the current screen
        $screen = get_current_screen();

        // Reset the choices
        $field['choices'] = array();

        // Check first that we're on the edit screen for this CPT
        // This is important, as without it this function will also attempt to populate the field choices in the field group edit screen
		if ( $screen && $screen->post_type == ORGNK_TERM_ARCHIVES_CPT_NAME ) {

            // Get the selected taxonomy from the previous field for this post
            $taxonomy = esc_html( get_post_meta( get_the_ID(), 'term_archive_linked_taxonomy', true ) );

            // Retrieve all the terms of the selected taxonomy for this post
            $terms = $taxonomy ? get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) : null;

            // Loop through all of the terms and add them to the field choices
            if ( $terms ) {
                foreach ( $terms as $term ) {
                    $field['choices'][$term->term_id] = $term->name;
                }
            }
        }

        return $field;
    }
}

Organik_Term_Archives::init();

//=======================================================================================================================================================

/**
 * orgnk_get_term_archive_id()
 * Get the term archive ID from the term archives CPT, if one exists
 */
function orgnk_get_term_archive_id( $term = null, $taxonomy = null ) {

	if ( ! defined( 'ORGNK_TERM_ARCHIVES_CPT_NAME' ) ) return;

    $taxonomy       = $taxonomy ? $taxonomy : get_query_var( 'taxonomy' );
    $term           = $term ? $term : get_query_var( 'term' );

    // Return early if a taxonomy or term has not been set or cannot be found in the current query for any reason
    if ( ! $taxonomy || ! $term ) return;

    $args = array(
        'post_type'                 => ORGNK_TERM_ARCHIVES_CPT_NAME,
        'post_status'               => 'publish',
        'posts_per_page'            => 1,
        'fields'                    => 'ids',
        'no_found_rows'             => true,
        'update_post_term_cache'    => false,
        'update_post_meta_cache'    => false,
        'meta_query'                => array(
            array(
                'key'               => 'term_archive_linked_term',
                'value'             => get_term_by( 'slug', $term, $taxonomy )->term_id,
                'compare'           => '='
            )
        )
    );

    $loop = new WP_Query( $args );

    if ( empty( $loop->posts ) ) {
        return false;
    } else {
        return $loop->posts[0];
    }
}
