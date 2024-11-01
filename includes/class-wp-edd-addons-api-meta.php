<?php
/**
 * Handles the display of the option within the admin panel
 * that allows administrators to mark downloads as addons.
 *
 * @package     wp-edd-addons-page-api
 * @copyright   Copyright (c) 2016, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP_EDD_Addons_API_Meta Class.
 */
class WP_EDD_Addons_API_Meta {

	/**
	 * Initialize hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'expose_addon_to_api' ) );
	}

	/**
	 * Add metabox.
	 */
	public function add_metabox() {
		add_meta_box( 'wp-edd-api-metabox', esc_html__( 'EDD Addons API', 'wp-edd-addons-api' ), array( $this, 'show_api_metabox' ), 'download', 'side' );
	}

	/**
	 * Update meta field for the download or delete if checkbox is disabled.
	 *
	 * @param  string $post_id the id number of the post we're saving.
	 * @return void
	 */
	public function expose_addon_to_api( $post_id ) {

		if( current_user_can( 'manage_options' ) && isset( $_POST['post_type'] ) && $_POST['post_type'] == 'download' ) {

			if( ! wp_verify_nonce( $_POST['edd_is_addon_nonce'], 'edd_addon_nonce_action' ) ) {
				return;
			}

			$this->clean_cache();

			$is_addon = array_key_exists( 'edd_product_is_addon' , $_POST ) ? true : false;

			if( $is_addon ) {
				update_post_meta( $post_id, 'wp_edd_product_is_addon', $is_addon );
			} else {
				delete_post_meta( $post_id, 'wp_edd_product_is_addon' );
			}

		}

	}

	/**
	 * Clean transient when a download is updated.
	 *
	 * @return void
	 */
	private function clean_cache() {
		delete_transient( 'wp_edd_addons_api_cached' );
	}

	/**
	 * Shows the content of the metabox.
	 *
	 * @return void
	 */
	public function show_api_metabox() {

		global $post;

		$is_addon = get_post_meta( $post->ID, 'wp_edd_product_is_addon', true );

		?>

		<p>
			<label for="edd_product_is_addon">
				<input type="checkbox" name="edd_product_is_addon" id="edd_product_is_addon" value="true" <?php checked( $is_addon, 1 ); ?>>
				<?php esc_html_e( 'Set this product as an addon', 'wp-edd-addons-api' ); ?>
			</label>
			<span class="edd-help-tip dashicons dashicons-editor-help" title="<?php esc_html_e( 'Enable this option to expose this product into the edd-addons REST API Route.', 'wp-edd-addons-api' ); ?>"></span>
		</p>

		<?php wp_nonce_field( 'edd_addon_nonce_action', 'edd_is_addon_nonce' ); ?>

		<?php

	}

}

$wp_edd_metabox = new WP_EDD_Addons_API_Meta;
$wp_edd_metabox->init();
