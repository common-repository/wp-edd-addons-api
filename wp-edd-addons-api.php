<?php
/*
Plugin Name: WP EDD Addons API
Plugin URI:  http://alessandrotesoro.me
Description: Provides the API required to expose EDD Addons through the REST API.
Version: 1.0.0
Author:      Alessandro Tesoro
Author URI:  http://alessandrotesoro.me
License:     GPLv2+
Text Domain: wp-edd-addons-api
Domain Path: /languages
*/

/**
 * Copyright (c) 2016 Alessandro Tesoro
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP_EDD_Addons_API Class.
 */
class WP_EDD_Addons_API {

	/**
	 * Get things started.
	 */
	public function __construct() {

		$this->includes();

	}

	/**
	 * Run hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_filter( 'edd_download_post_type_args', array( $this, 'expose_downloads_in_api' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

	}

	/**
	 * Include required files.
	 *
	 * @return void
	 */
	public function includes() {

		if( is_admin() ) {
			require_once( plugin_dir_path( __FILE__ ) .'/includes/class-wp-edd-addons-api-meta.php' );
		}

		require_once( plugin_dir_path( __FILE__ ) .'/includes/class-wp-edd-addons-api-controller.php' );

	}

	/**
	 * Modify the post type registration settings of the EDD plugin
	 * to enable the REST API for the post type.
	 *
	 * @param  array $args original args.
	 * @return array      new args.
	 */
	public function expose_downloads_in_api( $args ) {

		$args['show_in_rest']          = true;
		$args['rest_base']             = 'edd-addons';
		$args['rest_controller_class'] = 'WP_EDD_Addons_API_Controller';

		return $args;

	}

	/**
	 * Textdomain.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'wp-edd-addons-api', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

}

$wp_edd_addons = new WP_EDD_Addons_API;
$wp_edd_addons->init();
