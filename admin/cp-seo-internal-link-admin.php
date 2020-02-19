<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('CP_Seo_Internal_Link_Admin') ) :

class CP_Seo_Internal_Link_Admin extends CP_Seo_Internal_Link_Base {

	/**
	 * __construct
	 *
	 * Class constructor
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function __construct() {
		parent::__construct();

		// Admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' )  );

		// Show post metaboxes only if plugin is enabled
		if( $this->get_setting( 'plugin_enabled', true ) ) {
			// Actions
			add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );
			add_action( 'save_post', array( $this, 'save_metaboxes' ) );
		}

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Setup Ajax action hook
		add_action( 'wp_ajax_cp_seo_internal_link_ajax', array( $this, 'ajax_load_urls' ) );
	}

	/**
	 * ajax_load_urls
	 *
	 * ajax_load_urls callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function ajax_load_urls() {
		// Check ajax nonce for security
		check_ajax_referer( 'cp-seo-internal-link-ajax-nonce', 'security' );

		// Get selected object type
		$object_type = !empty($_POST['object_type']) ? $_POST['object_type'] : ''; 
		$selected_id = !empty($_POST['selected_id']) ? $_POST['selected_id'] : ''; 
		
		// Url Options
		$url_options = [];

		// Get object type to query
		if( !empty($object_type) ) {
			$object_type = explode(":", $object_type);

			$query_type = isset($object_type[0]) ? $object_type[0] : 'post_type';
			$query_object = isset($object_type[1]) ? $object_type[1] : 'category';

			// Query posts url
			if( $query_type == 'post_type' ) {

				// Query args for object type
				$args = array(
				    'post_type' => $query_object,
				    'post_status' => array( 'publish' ),
				    'posts_per_page' => -1,  
				);

				// Query posts
				$custom_query = new WP_Query( $args );

				while ( $custom_query->have_posts() ) : 
					$custom_query->the_post();

					// url options
					$url_options[ get_the_ID() ] = sprintf('%s [%s]', get_the_title(), get_permalink());

				endwhile;

				wp_reset_postdata();
			}

			// Query terms url
			else if( $query_type == 'taxonomy' ) {

				// Query args for object type
				$terms = get_terms( array(
					'taxonomy' => $query_object,
				    'hide_empty' => true,
				) );

				// Get term options
				if( ! is_wp_error( $terms ) ) {
					foreach ($terms as $term) {
						$url_options[ $term->term_id ] = sprintf('%s [%s]', $term->name, get_term_link( $term->term_id ));
					}
				}
			}
		}

		wp_send_json([
			'status' => 'success',
			'options' => $this->select_options( $url_options, $selected_id, __( 'Select Url' ) )
		]);
		exit();
	}

	/**
	 * enqueue_scripts
	 *
	 * admin_enqueue_scripts callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function enqueue_scripts() {
		wp_register_script( 'cp-seo-internal-link-admin-script', CP_SEO_INTERNAL_LINK_URL . 'admin/assets/js/admin.js', array('jquery'), null, true );
		wp_localize_script( 'cp-seo-internal-link-admin-script', 'cp_seo_internal_link', array( 'ajax_url' => admin_url('admin-ajax.php'), 'check_nonce' => wp_create_nonce('cp-seo-internal-link-ajax-nonce')) );
		wp_enqueue_script( 'cp-seo-internal-link-admin-script' );
	}
	
	/**
	 * admin_menu
	 *
	 * admin_menu callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function admin_menu() {
		add_options_page( 'Seo Internal Link', 'Seo Internal Link', 'manage_options', 'seo-internal-link-settings', array( $this, 'settings_page' ) );
	}
	
	/**
	 * admin_menu
	 *
	 * admin_menu callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function settings_page() {
		// Check for permission
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		// Save submitted form
	    $messages = $this->settings_page_save();

		// Include admin settings page
	    include_once( CP_SEO_INTERNAL_LINK_PATH . 'admin/templates/settings.php');
	}


	/**
	 * admin_menu_settings_page_save
	 *
	 * Save admin settings
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function settings_page_save() {
		// Check for permission
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( ! isset( $_POST['cp_seo_internal_link_settings'] ) ) {
	        return;
	    }

	    $plugin_enabled = isset($_POST['plugin_enabled']) ? $_POST['plugin_enabled'] : 0;
	    $show_in_excerpt = isset($_POST['show_in_excerpt']) ? $_POST['show_in_excerpt'] : 0;

	    // Create option array to save
	    $options = [
	    	'plugin_enabled' => $plugin_enabled,
	    	'show_in_excerpt' => $show_in_excerpt
	    ];

	    // Update options
	    update_option( 'cp_seo_internal_link_settings',  $options );

	    // Update Settings
	    $this->set_settings( $this->settings, $options );

	    return [
	    	'status' => 'success',
	    	'message' => __( 'Settings saved' )
	    ];
	}

	/**
	 * register_metaboxes
	 *
	 * add_meta_boxes callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function register_metaboxes() {
		add_meta_box(
	        'cp_seo_internal_link',
	        __( 'Seo Keyword Internal Link', 'cp_seo_internal_link' ),
	        array( $this, 'cp_seo_internal_link_meta_box_callback' )
	    );
	}
	
	/**
	 * cp_seo_internal_link_meta_box_callback
	 *
	 * add_meta_box callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function cp_seo_internal_link_meta_box_callback( $post ) {
		// Add a nonce field so we can check for it later.
	    wp_nonce_field( 'cp_seo_internal_link_nonce', 'cp_seo_internal_link_nonce' );

	    // Get meta values
	    $keyword = get_post_meta( $post->ID, 'cp_seo_internal_link_keyword', true );
	    $url = get_post_meta( $post->ID, 'cp_seo_internal_link_url', true );

	    // Get all post types
		$post_types = get_post_types([
			'public'   => true,
  			'_builtin' => false
		], 'names', 'and');

		// builtin post types
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';

		// build options
		$post_types_options = [];
		foreach ($post_types as $slug => $name) {
			$post_types_options[ 'post_type:' . $slug ] = $name;
		}

		// Get all taxonomies
		$taxonomies = get_taxonomies([
			'public'   => true,
  			'_builtin' => false
		], 'names', 'and');
		
		// builtin taxonomies
		$taxonomies['category'] = 'category';
		$taxonomies['post_tag'] = 'post_tag';

		// Exclude taxonomies
		$exclude_taxonomies = array( 'product_shipping_class' );

		// build options
		$taxonomies_options = [];
		foreach ($taxonomies as $slug => $name) {
			if( in_array( $slug, $exclude_taxonomies ) ) {
				continue;
			}

			$taxonomies_options[ 'taxonomy:' . $slug ] = $name;
		}

		// Object types
		$object_types = [
			'Post Types' => $post_types_options,
			'Taxonomies' => $taxonomies_options,
			'Custom Link' => [
				'custom_link' => 'Custom Link'
			],
		];

		// Filter object types
		$object_types = apply_filters('cp_seo_internal_link_object_types', $object_types);

		// get saved meta
		$saved_meta = get_post_meta( $post->ID, 'cp_seo_internal_link_meta', true );

	    // Include metaboxes fields
	    include_once( CP_SEO_INTERNAL_LINK_PATH . 'admin/templates/meta-boxes.php');
	}
	
	/**
	 * save_metaboxes
	 *
	 * save_post callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function save_metaboxes( $post_id ) {
		// Check if our nonce is set.
	    if ( ! isset( $_POST['cp_seo_internal_link_nonce'] ) ) {
	        return;
	    }

	    // Verify that the nonce is valid.
	    if ( ! wp_verify_nonce( $_POST['cp_seo_internal_link_nonce'], 'cp_seo_internal_link_nonce' ) ) {
	        return;
	    }

	    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	        return;
	    }

	    // Check the user's permissions.
	    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

	        if ( ! current_user_can( 'edit_page', $post_id ) ) {
	            return;
	        }

	    }
	    else {

	        if ( ! current_user_can( 'edit_post', $post_id ) ) {
	            return;
	        }
	    }

	    /* OK, it's safe for us to save the data now. */

	    // Make sure that it is set.
	    if ( ! isset( $_POST['cp_seo_internal_link_keyword'] ) ) {
	        return;
	    }

	    if ( ! isset( $_POST['cp_seo_internal_link_object_type'] ) ) {
	        return;
	    }

	    if ( ! isset( $_POST['cp_seo_internal_link_object_id'] ) ) {
	        return;
	    }

	    if ( ! isset( $_POST['cp_seo_internal_link_custom_url'] ) ) {
	        return;
	    }

	    // Sanitize user input.
	    $keyword = sanitize_text_field( $_POST['cp_seo_internal_link_keyword'] );
	    $object_type = sanitize_text_field( $_POST['cp_seo_internal_link_object_type'] );
	    $object_id = sanitize_text_field( $_POST['cp_seo_internal_link_object_id'] );
	    $custom_url = esc_url( $_POST['cp_seo_internal_link_custom_url'] );

	    $post_meta = [
	    	'keyword' => $keyword,
	    	'object_type' => $object_type,
	    	'object_id' => $object_id,
	    	'custom_url' => $custom_url,
	    ];

	    // Update the meta field in the database.
	    update_post_meta( $post_id, 'cp_seo_internal_link_meta', $post_meta );
	}
}

/*
 * CP_Seo_Internal_Link_Admin
 *
 * The main function responsible for returning the one true CP_Seo_Internal_Link_Admin Instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php $CP_Seo_Internal_Link_Admin = CP_Seo_Internal_Link_Admin(); ?>
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	CP_Seo_Internal_Link_Admin
 */
function CP_Seo_Internal_Link_Admin() {
	global $CP_Seo_Internal_Link_Admin;
	
	// Instantiate only once.
	if( !isset($CP_Seo_Internal_Link_Admin) ) {
		$CP_Seo_Internal_Link_Admin = new CP_Seo_Internal_Link_Admin();
	}
	return $CP_Seo_Internal_Link_Admin;
}

// Instantiate.
CP_Seo_Internal_Link_Admin();

endif; // class_exists check
