<?php
/*
Plugin Name: ConnectPX Seo Internal Link
Plugin URI: http://connectpx.com/
Description: This plugin will provide meta boxes in post add/edit page to add keywords internal links for posts, pages and custom post types.
Version: 1.0.0
Author: ConnectPX
Author URI: http://connectpx.com/
Text Domain: cp_seo_internal_link
Domain Path: /lang
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CP_Seo_Internal_Link
 *
 * Main Class
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	void
 */	

if( ! class_exists('CP_Seo_Internal_Link') ) :

class CP_Seo_Internal_Link {	
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
		// Define constants.
		if(!defined('CP_SEO_INTERNAL_LINK_PATH')) {
			define( 'CP_SEO_INTERNAL_LINK_PATH', plugin_dir_path( __FILE__ ) );
		}
		if(!defined('CP_SEO_INTERNAL_LINK_URL')) {
			define( 'CP_SEO_INTERNAL_LINK_URL', plugins_url( '/', __FILE__ ) );
		}
		if(!defined('CP_SEO_INTERNAL_LINK_BASENAME')) {
			define( 'CP_SEO_INTERNAL_LINK_BASENAME', plugin_basename( __FILE__ ) );
		}	

		// Include utility functions.
		include_once( CP_SEO_INTERNAL_LINK_PATH . 'includes/wpv-utility-functions.php');

		// Include base class
		include_once( CP_SEO_INTERNAL_LINK_PATH . 'cp-seo-internal-link-base.php');

		// Include admin class
		include_once( CP_SEO_INTERNAL_LINK_PATH . 'admin/cp-seo-internal-link-admin.php');

		// Include front class
		include_once( CP_SEO_INTERNAL_LINK_PATH . 'cp-seo-internal-link-front.php');
	}
}

/*
 * CP_Seo_Internal_Link
 *
 * The main function responsible for returning the one true CP_Seo_Internal_Link Instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php $CP_Seo_Internal_Link = CP_Seo_Internal_Link(); ?>
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	CP_Seo_Internal_Link
 */
function CP_Seo_Internal_Link() {
	global $CP_Seo_Internal_Link;
	
	// Instantiate only once.
	if( !isset($CP_Seo_Internal_Link) ) {
		$CP_Seo_Internal_Link = new CP_Seo_Internal_Link();
	}
	return $CP_Seo_Internal_Link;
}

// Instantiate.
CP_Seo_Internal_Link();

endif; // class_exists check
