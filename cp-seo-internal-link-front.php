<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CP_Seo_Internal_Link_Front
 *
 * Frontend Class
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	void
 */	
if( ! class_exists('CP_Seo_Internal_Link_Front') ) :

class CP_Seo_Internal_Link_Front extends CP_Seo_Internal_Link_Base {

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

		// Show links only if plugin is enabled
		if( $this->get_setting( 'plugin_enabled', true ) ) {
			// Filter
			add_filter( 'the_content', array( $this, 'filter_content' ) );

			// Check if link enabled for excerpts
			if( $this->get_setting( 'show_in_excerpt', true ) ) {
				add_filter( 'the_excerpt', array( $this, 'filter_content' ) );
			}
		}
	}
	
	/**
	 * filter_content
	 *
	 * the_content callback
	 *
	 * @since	1.0.0
	 *
	 * @param	void
	 * @return	void
	 */	
	function filter_content( $content ) {
		global $post;

		// Get meta values
	    $keyword_link = $this->get_formated_link( $post->ID );

	    // append Link in content
	    if( $keyword_link ) { 
	    	$content .= $keyword_link;
	    }
	    
	    return $content;
	}
}

/*
 * CP_Seo_Internal_Link_Front
 *
 * The main function responsible for returning the one true CP_Seo_Internal_Link_Front Instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php $CP_Seo_Internal_Link_Front = CP_Seo_Internal_Link_Front(); ?>
 *
 * @since	1.0.0
 *
 * @param	void
 * @return	CP_Seo_Internal_Link_Front
 */
function CP_Seo_Internal_Link_Front() {
	global $CP_Seo_Internal_Link_Front;
	
	// Instantiate only once.
	if( !isset($CP_Seo_Internal_Link_Front) ) {
		$CP_Seo_Internal_Link_Front = new CP_Seo_Internal_Link_Front();
	}
	return $CP_Seo_Internal_Link_Front;
}

// Instantiate.
CP_Seo_Internal_Link_Front();

endif; // class_exists check
