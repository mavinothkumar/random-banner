<?php
/**
 * All Request Handler
 *
 * @package Random Banner
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'wp_ajax_bc_rb_save_banner', 'bc_rb_save_banner' );
add_action( 'wp_ajax_nopriv_bc_rb_save_banner', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_delete_banner', 'bc_rb_delete_banner' );
add_action( 'wp_ajax_nopriv_bc_rb_delete_banner', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_save_options', 'bc_rb_save_options' );
add_action( 'wp_ajax_nopriv_bc_rb_save_options', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_save_popup', 'bc_rb_save_popup' );
add_action( 'wp_ajax_nopriv_bc_rb_save_popup', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_save_category', 'bc_rb_save_category' );
add_action( 'wp_ajax_nopriv_bc_rb_save_category', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_delete_category', 'bc_rb_delete_category' );
add_action( 'wp_ajax_nopriv_bc_rb_delete_category', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_save_insert_post', 'bc_rb_save_insert_post' );
add_action( 'wp_ajax_noprivbc_rb_save_insert_post', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_rb_donation_later', 'bc_rb_donation_later' );
add_action( 'wp_ajax_nopriv_bc_rb_donation_later', 'bc_rb_save_banner_no_priv' );

add_action( 'wp_ajax_bc_delete_dbs', 'bc_delete_dbs' );
add_action( 'wp_ajax_nopriv_bc_delete_dbs', 'bc_rb_save_banner_no_priv' );


/**
 * Delete Banner by it ID
 */
function bc_rb_delete_banner() {
	$post_payload = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_nonce_delete' );
		// Delete Upload or Script Banner by ID.
		bc_rb_delete_upload_script( $post_payload );
	}
}


/**
 * Save Banner
 */
function bc_rb_save_banner() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_nonce' );
		// Create or update the uploaded or script banner type.
		bc_rb_create_update_upload_script( $_POST );
	}
}

/**
 * Skip Donation Banner Popup by 2 days
 */
function bc_rb_donation_later() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_donation_later' );

		// Update DB to remind Later.
		bc_rb_remind_later();
	}

}

/**
 * Restricting for No Privilege Users
 */
function bc_rb_save_banner_no_priv() {
	echo 'You must log in to vote';
	die();
}

/**
 * Save Options
 */
function bc_rb_save_options() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_save_options' );
		bc_rb_save_setting_options( $_POST );
	}
}

/**
 * Save Popup
 */
function bc_rb_save_popup() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_save_popup' );
		$post_payload = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		bc_rb_save_popup_options( $post_payload );
	}
}

/**
 * Update or Save Category
 */
function bc_rb_save_category() {
	$post_payload = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_save_category' );
	// Create or update the category name
	bc_rb_create_update_category( $post_payload );
}

/**
 * Delete the Category by ID
 */
function bc_rb_delete_category() {
	$post_payload = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
	bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_delete_category' );
	bc_rb_delete_category_id( $post_payload );
}

/**
 * Insert Shortcode inside the post Save
 */
function bc_rb_save_insert_post() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['nonce'], 'bc_rb_save_insert_post' );
		bc_rb_save_insert_post_model( $_REQUEST );
	}
}

/**
 * Delete all DBs and its options
 */
function bc_delete_dbs() {
	if ( isset( $_REQUEST['nonce'] ) && ! empty( $_REQUEST['nonce'] ) ) {
		bc_rb_check_nonce( $_REQUEST['bc_delete_dbs'], 'bc_delete_dbs' );
		uninstall_bc_random_banner_table();

		echo wp_json_encode(
			array(
				'status'  => 'ok',
				'message' => __( 'You have deleted all your Random Banner tables and its options, Please uninstall the plugin now', 'random-banner' ),
				'type'    => 'success',
			)
		);
		exit();
	}
	echo wp_json_encode(
		array(
			'status'  => 'no',
			'message' => __( 'Something went wrong', 'random-banner' ),
			'type'    => 'error',
		)
	);
	exit();
}

