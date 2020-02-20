<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CP_Seo_Internal_Link_Base
 *
 * Base Class
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	void
 */	
if( ! class_exists('CP_Seo_Internal_Link_Base') ) :

class CP_Seo_Internal_Link_Base {
	
	/** @var string The plugin version number. */
	var $version = '1.0.0';
	
	/** @var array The plugin settings array. */
	var $settings = array();
	
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

		// Set settings
		$this->set_settings([
			'plugin_enabled' => 1,
			'show_in_excerpt' => 1,
		], get_option('cp_seo_internal_link_settings') );
	}

	/**
	 * set_settings
	 *
	 * Update plugin settings
	 *
	 * @since	1.0.0
	 *
	 * @param	$settings: default settings, $options: options to merge
	 * @return	void
	 */	
	function set_settings($settings, $options) {
		// Merge plugin settings and default settings
		$settings = array_merge($settings, $options);

		// Filter and set Settings
		$this->settings = apply_filters('cp_seo_internal_link_settings', $settings);
	}

	/**
	 * get_setting
	 *
	 * Get setting
	 *
	 * @since	1.0.0
	 *
	 * @param	$key: settings key, $default: default value
	 * @return	Setting value
	 */	
	function get_setting($key, $default = null) {
		return isset($this->settings[$key]) ? $this->settings[$key] : $default;
	}
	
	/**
	 * get_data
	 *
	 * Get value from array
	 *
	 * @since	1.0.0
	 *
	 * @param	$data: data array, $key: data key, $default: default value
	 * @return	data value
	 */	
	function get_data($data, $key, $default = null) {
		return isset($data[$key]) ? $data[$key] : $default;
	}
	
	/**
	 * is_checked
	 *
	 * Checked checkbox
	 *
	 * @since	1.0.0
	 *
	 * @param	$value: checked value, $compare: checkbox value to compare
	 * @return	checked attribute
	 */	
	function is_checked($value, $compare = 1) {
		return ($value == $compare) ? 'checked="checked"' : '';
	}

	/**
	 * select_options
	 *
	 * Array to select options
	 *
	 * @since	1.0.0
	 *
	 * @param	$rows: array values, $selected_option: selected option, $use_key: usey keys for values
	 * @return	checked attribute
	 */	
	function select_options($rows, $selected_option = null, $empty_lable = "", $use_key = true) {
	    if( !is_array($rows) ) return;

	    $options = "";

	    // Selected value to array for multiple values
	    if($selected_option && !is_array($selected_option)) {
	        $selected_option = array($selected_option);
	    }

	    // Empty label
	    if( $empty_lable != "" ) {
	        $options .= "<option value=\"\">{$empty_lable}</option>";
	    }

	    // Creaye options from array
	    foreach ($rows as $key => $value) {
	        $value_item = ($use_key) ? $key : $value;
	        $selected = (!empty($selected_option) && in_array($value_item, $selected_option)) ? 'selected="selected"' : "";

	        $options .= "<option value=\"{$value_item}\" {$selected}>{$value}</option>";
	    }
	    return $options;
	}	

	/**
	 * get_formated_link
	 *
	 * Get formated html link 
	 *
	 * @since	1.0.0
	 *
	 * @param	$value: checked value, $compare: checkbox value to compare
	 * @return	checked attribute
	 */	
	function get_formated_link( $post_id ) {
		// get saved meta
		$saved_meta = get_post_meta( $post_id, 'cp_seo_internal_link_meta', true );


		// Check for values
		if( empty( $saved_meta['keyword'] ) || empty( $saved_meta['object_type'] ) ) {
			return;
		}

		$url = "";

		// If custom link selected get custom link
		if( $saved_meta['object_type'] == 'custom_link' && ! empty( $saved_meta['custom_url'] ) ) {
			$url = $saved_meta['custom_url'];
		}

		// else get selected object url e.g post, page, term
		else if( ! empty( $saved_meta['object_id'] ) ) {
			$object_type = explode(":", $saved_meta['object_type']);

			$link_type = isset($object_type[0]) ? $object_type[0] : 'post_type';
			$link_object = isset($object_type[1]) ? $object_type[1] : 'category';

			// if post type selected
			if( $link_type == 'post_type' ) {
				$url = get_permalink( $saved_meta['object_id'] );
			}

			// if taxonomy type selected
			else if( $link_type == 'taxonomy' ) {
				// Get term from id
				$term = get_term( $saved_meta['object_id'] );
				if( ! is_wp_error( $term ) ) {
					$term_link = get_term_link( $term );
					if( ! is_wp_error( $term_link ) ) {
						$url = $term_link;
					}
				}
			}
		}

		if( !empty( $url ) ) {
			$url = esc_url( $url );

			return sprintf('<span class="cp-seo-internal-link"><a href="%s">%s</a></span>', $url, $saved_meta['keyword']);
		}
	}
}

endif; // class_exists check
