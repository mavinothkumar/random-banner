<?php
/**
 * Declaring Constant values through Functions
 *
 * @package constant
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Upload Banner Model
 *
 * @param array $post Banner option details.
 *
 * @return array
 */
function bc_rb_upload_banner_model( $post ) {
	global $current_user;
	$post = bc_rb_sanitize_text_field( $post );

	return array(
		'file_url'         => esc_url_raw( $post['file_url_link'] ),
		'file_description' => bc_rb_sanitize_text_field( $post['file_description'] ),
		'external_link'    => esc_url_raw( $post['external_link'] ),
		'banner_type'      => bc_rb_sanitize_text_field( $post['banner_type'] ),
		'automatic'        => bc_rb_sanitize_text_field( $post['automatic'] ),
		'width'            => (int) $post['width'],
		'height'           => (int) $post['height'],
		'user_id'          => $current_user->ID,
		'category'         => bc_rb_sanitize_text_field( $post['category'] ),
		'created'          => bc_rb_get_current_date(),
	);
}

/**
 * Upload Banner Model Options
 *
 * @return array
 */
function bc_rb_upload_banner_option_model() {
	return array(
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%d',
		'%d',
		'%d',
		'%s',
		'%s',
	);
}

/**
 * Upload Script Model
 *
 * @param array $post Banner options.
 *
 * @return array
 */
function bc_rb_script_banner_model( $post ) {
	global $current_user;

	return array(
		'file_description' => bc_rb_sanitize_text_field( $post['file_description'] ),
		'file_url'         => esc_textarea( $post['file_url_link'] ),
		'banner_type'      => bc_rb_sanitize_text_field( $post['banner_type'] ),
		'user_id'          => $current_user->ID,
		'created'          => bc_rb_get_current_date(),
		'category'         => bc_rb_sanitize_text_field( $post['category'] ),
	);
}

/**
 * Upload Script Model Options
 *
 * @return array
 */
function bc_rb_script_banner_option_model() {
	return array(
		'%s',
		'%s',
		'%s',
		'%d',
		'%s',
		'%s',
	);
}

/**
 * Category Model
 *
 * @param array $post Banner Category.
 *
 * @return array
 */
function bc_rb_category_model( $post ) {
	return array(
		'category' => strtolower( sanitize_text_field( $post['category'] ) ),
		'created'  => bc_rb_get_current_date(),
	);
}

/**
 * Category Option
 *
 * @return array
 */
function bc_rb_category_option() {
	return array(
		'%s',
		'%s',
	);
}
