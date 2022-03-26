<?php
/**
 * CF7 Add serial number to response
 *
 * @package           PluginPackage
 * @author            takotakot
 * @copyright         2022- takotakot
 * @license           MIT/X
 *
 * @wordpress-plugin
 * Plugin Name:       CF7 Add serial number to response
 * Plugin URI:        https://github.com/takotakot/cf7-add-serial-number-to-response
 * Description:       Add serial number id to ajax response
 * Version:           1.0.0
 * Requires at least: 5.9.2
 * Requires PHP:      7.4
 * Author:            takotakot
 * Author URI:        https://github.com/takotakot/
 * Text Domain:       cf7-add-serial-number-to-response
 * License:           MIT/X
 * License URI:       https://opensource.org/licenses/mit-license.php
 * Update URI:        https://wordpress.org/plugins/cf7-add-serial-number-to-response/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'CF7_ADD_SERIAL_NUMBER_TO_RESPONSE_VERSION', '1.0.0' );
define( 'CF7_ADD_SERIAL_NUMBER_TO_RESPONSE_ROOT', dirname( __FILE__ ) );
define( 'CF7_ADD_SERIAL_NUMBER_TO_RESPONSE_URL', plugins_url( '/', __FILE__ ) );
define( 'CF7_ADD_SERIAL_NUMBER_TO_RESPONSE_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * Validate Contact Form 7 exists and is activated.
 *
 * @access public
 * @since 1.0.0
 */
function cf7asn_validate_cf7_plugin_exists() {
	$plugin = CF7_ADD_SERIAL_NUMBER_TO_RESPONSE_BASE_NAME;
	if ( ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) || ( ! file_exists( plugin_dir_path( __DIR__ ) . 'contact-form-7/wp-contact-form-7.php' ) ) ) {
		deactivate_plugins( $plugin );
		if ( isset( $_GET['activate'] ) ) {// phpcs:ignore
			unset( $_GET['activate'] );// phpcs:ignore
		}
	}
}

/**
 * Add serial number as serial_number to ajax resnponse.
 * Value can be get with event.detail.apiResponse.serial_number .
 *
 * @since 1.0.0
 * @param array $response variable.
 * @param array $result   variable.
 */
function cf7asn_add_serial_number_to_response( $response, $result ) {
	global $cf7asn_serial_number;
	$serial = $cf7asn_serial_number;
	if ( isset( $serial ) ) {
		$response['serial_number'] = $serial;
	}
	return $response;
}

// @var int $cf7asn_serial_number Variable for storing serial_number .
global $cf7asn_serial_number;

/**
 * Save serial_number to $cf7asn_serial_number using wpcf7_mail_sent action.
 *
 * @since 1.0.0
 */
function cf7asn_save_serial_number() {
	$tagname  = '_serial_number';
	$mail_tag = new WPCF7_MailTag(
		sprintf( '[%s]', $tagname ),
		$tagname,
		''
	);

	global $cf7asn_serial_number;
	$cf7asn_serial_number = apply_filters( 'wpcf7_special_mail_tags', null, $tagname, false, $mail_tag );
}

add_action( 'admin_init', 'cf7asn_validate_cf7_plugin_exists' );
add_action( 'wpcf7_mail_sent', 'cf7asn_save_serial_number' );
add_filter( 'wpcf7_feedback_response', 'cf7asn_add_serial_number_to_response', 10, 2 );
