<?php
/**
 * Custom controller that decides the format of the json output through the REST API.
 *
 * @package     wp-edd-addons-page-api
 * @copyright   Copyright (c) 2016, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP_EDD_Addons_API_Controller Class.
 */
class WP_EDD_Addons_API_Controller extends WP_REST_Controller {

	/**
	 * Register the route to make the request to the api.
	 *
	 * @return void
	 */
	public function register_routes() {

		register_rest_route( 'wp/v2', '/edd-addons', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_addons' )
		) );

	}

	/**
	 * Callback function that retrieves only the products marked as addon.
	 *
	 * @param  object $request instace of the request.
	 * @return object          response.
	 */
	public function get_addons( $request ) {

		if ( ! $request instanceof WP_REST_Request ) {
			wp_die();
		}

		$addons = array();

		$cached_addons = get_transient( 'wp_edd_addons_api_cached' );

		if( ! empty( $cached_addons ) ) {

			$addons = $cached_addons;

		} else {

			$args   = array(
				'post_type'      => 'download',
				'posts_per_page' => 9999,
				'no_found_rows'  => true,
				'meta_query'     => array(
					array(
						'key'   => 'wp_edd_product_is_addon',
						'value' => 1,
					),
				),
			);

			$addons_query = new WP_Query( apply_filters( 'wp_edd_addons_api_query', $args ) );

			if( $addons_query->have_posts() ) {

				while( $addons_query->have_posts() ) : $addons_query->the_post();

					$download_id = get_the_id();

					$addons[] = array(
						'id'            => $download_id,
						'slug'          => get_post_field( 'post_name', $download_id ),
						'date'          => strtotime( get_the_date() ),
						'modified_date' => strtotime( get_the_modified_date() ),
						'link'          => get_permalink(),
						'sticky'        => is_sticky(),
						'title'         => get_the_title(),
						'excerpt'       => get_the_excerpt(),
						'content'       => get_the_content(),
						'thumbnail'     => wp_get_attachment_url( get_post_thumbnail_id() ),
						'categories'    => wp_get_post_terms( $download_id, 'download_category' ),
						'tags'          => wp_get_post_terms( $download_id, 'download_tag' )
					);

				endwhile;

			}

			set_transient( 'wp_edd_addons_api_cached', $addons, DAY_IN_SECONDS );

		}

		return new WP_REST_Response( $addons, 200 );

	}

}
