<?php 

/*
Plugin Name: Relevanssi: add ACF subfields to index
Plugin URI: http://github.com/cftp/relevanssi-acf-subfields/
Description: Finds subfields from ACF and feeds them to the Relevanssi indexer so they're findable in search
Version: 0.2
Author: Code for the People Ltd
Author URI: http://codeforthepeople.com/
*/
 
/*  Copyright 2013 Code for the People Ltd
                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


/**
 * 
 * 
 * @package Relevanssi ACF subfields
 **/
class Relevanssi_ACF_Subfields {

	/**
	 * A version integer.
	 *
	 * @var int
	 **/
	var $version;

	/**
	 * Singleton stuff.
	 * 
	 * @access @static
	 * 
	 * @return Relevanssi_ACF_Subfields object
	 */
	static public function init() {
		static $instance = false;

		if ( ! $instance )
			$instance = new Relevanssi_ACF_Subfields;

		return $instance;

	}

	/**
	 * Class constructor
	 *
	 * @return null
	 */
	public function __construct() {
		add_filter( 'relevanssi_content_to_index', array( $this, 'filter_relevanssi_content_to_index' ), 10, 2 );

		$this->version = 1;
	}

	// HOOKS
	// =====

	/**
	 * Hooks the WP filter relevanssi_content_to_index
	 *
	 * @filter relevanssi_content_to_index
	 * 
	 * @param string $content Content to pass to Relevanssi
	 * @param object $post A WP_Post object
	 * @return string The content we're going to pass to Relevanssi
	 * @author Simon Wheatley
	 **/
	public function filter_relevanssi_content_to_index( $content, WP_Post $post ) {
		// If we don't have ACF available, bail out
		if ( ! function_exists( 'the_repeater_field' ) )
			return $content;

		$custom_fields = relevanssi_get_custom_fields();
		foreach ( $custom_fields as $custom_field ) {
			if ( false === strpos( $custom_field, '%' ) )
				continue;

			preg_match( '/([a-z0-9\_]+)_\%_([a-z0-9\_]+)/i', $custom_field, $matches );
			$field_name = $matches[ 1 ];
			$subfield_name = $matches[ 2 ];
			$num_fields = get_post_meta( $post->ID, $field_name, true );
			for ( $i = 0; $i < $num_fields; $i++ ) {
				$raw = get_post_meta( $post->ID, "{$field_name}_{$i}_{$subfield_name}", true );
				// Copied and pasted from Relevanssi
				remove_shortcode('noindex');
				add_shortcode('noindex', 'relevanssi_noindex_shortcode_indexing');
				$disable_shortcodes = get_option('relevanssi_disable_shortcodes');
				$shortcodes = explode(',', $disable_shortcodes);
				foreach ($shortcodes as $shortcode) {
					remove_shortcode(trim($shortcode));
				}
				remove_shortcode('contact-form');		// Jetpack Contact Form causes an error message
				remove_shortcode('starrater');			// GD Star Rating rater shortcode causes problems
				$value = do_shortcode( $raw ) . PHP_EOL;
				$content .= $value;
			}
		}
		return $content;
	}

}


// Initiate the singleton
Relevanssi_ACF_Subfields::init();

