<?php
/**
 * Save Update Delete
 *
 * @package save_update_delete
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Create or Update, Script or Upload Banner Type
 *
 * @param  Array $post  Banner Data.
 */
function bc_rb_create_update_upload_script( $post ) {
	if ( isset( $post['file_url_link'] ) && isset( $post['banner_type'] ) ) {
		$content = array();
		if ( $post['banner_type'] == 'upload' ) {
			$content['file_url_link'] = sanitize_url( $post['file_url_link'] );
			$content['external_link'] = isset( $post['external_link'] ) ? sanitize_url( $post['external_link'] ) : '';
			$content['width']         = isset( $post['width'] ) ? intval( $post['width'] ) : '';
			$content['height']        = isset( $post['height'] ) ? intval( $post['height'] ) : '';
			$content['automatic']     = isset( $post['automatic'] ) ? bc_rb_sanitize_text_field( $post['automatic'] ) : '';
		}

		if ( $post['banner_type'] == 'script' ) {
			$content['file_url_link'] = ( $post['file_url_link'] );
		}

		$content['file_description'] = isset( $post['file_description'] ) ? bc_rb_sanitize_text_field( $post['file_description'] ) : '';
		$content['category']         = isset( $post['category'] ) ? bc_rb_sanitize_text_field( $post['category'] ) : 'default';
		$content['banner_id']        = isset( $post['banner_id'] ) ? (int) $post['banner_id'] : 'no';
		$content['banner_type']      = bc_rb_sanitize_text_field( $post['banner_type'] );

		// Add new banner to table.
		if ( $content['banner_id'] == 'no' ) {
			$db_status = bc_rb_create( $content );
			populate_data( $db_status, esc_html__( 'Successfully Added', 'random-banner' ) );
		} else { // Update the banner with ID.
			$db_status = bc_rb_update( $content );
			populate_data( $db_status, esc_html__( 'Successfully Updated', 'random-banner' ) );
		}
	}
}


/**
 * Delete Upload or Script by ID
 *
 * @param  Array $post  Post Details.
 */
function bc_rb_delete_upload_script( $post ) {
	if ( isset( $post['banner_id'] ) ) {
		$content              = array();
		$content['banner_id'] = (int) $post['banner_id'];
		$verify               = bc_rb_delete_by_id( $content['banner_id'] );
		if ( $verify == 'success' ) {
			echo wp_json_encode(
				array(
					'message' => __( 'Successfully Deleted', 'random-banner' ),
					'type'    => 'success',
				)
			);
			die();
		}
	}
	echo wp_json_encode(
		array(
			'error' => __( 'Something went wrong, Please try again', 'random-banner' ),
			'type'  => 'error',
		)
	);
	die();
}

/**
 * Save Setting Options
 *
 * @param  Array $post  Banner Settings.
 */
function bc_rb_save_setting_options( $post ) {
	// Setting Default Value to not to brake array_intersect_key.
	if ( ! isset( $post['bc_rb_setting_options'] ) ) {
		$post = array(
			'bc_rb_setting_options' => array(
				'open'           => '',
				'disable'        => '',
				'user_logged_in' => '',
				'empty_banner'   => __( 'There is no ads to display, Please add some', 'random-banner' ),
			),
		);
	}
	// Default Values.
	$empty_banner = isset( $post['empty_banner'] ) ? bc_rb_sanitize_text_field( $post['empty_banner'] ) : __( 'There is no ads to display, Please add some', 'random-banner' );
	$array_key    = array(
		'bc_rb_setting_options' => array(
			'open'           => '',
			'disable'        => '',
			'user_logged_in' => '',
			'empty_banner'   => $empty_banner,
		),
	);

	// Intersect and get only required post value.
	$value = array_intersect_key( $post['bc_rb_setting_options'], $array_key['bc_rb_setting_options'] );

	update_option( 'bc_rb_setting_options', serialize( bc_rb_sanitize_text_field( $value ) ) );
	echo 'ok';
	die();
}

/**
 * Save Popup Options
 *
 * @param  array $post  Popup Settings.
 */
function bc_rb_save_popup_options( $post ) {
	$value                         = array();
	$value['enable_popup']         = isset( $post['bc_rb_setting_popup']['enable_popup'] ) ? bc_rb_sanitize_text_field( $post['bc_rb_setting_popup']['enable_popup'] ) : '';
	$value['popup_category_name']  = isset( $post['bc_rb_setting_popup']['popup_category_name'] ) ? bc_rb_sanitize_text_field( $post['bc_rb_setting_popup']['popup_category_name'] ) : '';
	$value['popup_show']           = 2000;
	$value['popup_session']        = 3;
	$value['popup_animated_style'] = 'bounce';

	update_option( 'bc_rb_setting_popup', serialize( $value ) );

	echo 'ok';
	die();
}

/**
 * Create or Update Category
 *
 * @param  Array $post  Banner Data.
 */
function bc_rb_create_update_category( $post ) {

	$category = bc_rb_add_update_category( $post );

	if ( $category == 'already_exist' ) {
		echo wp_json_encode(
			array(
				'message' => __( 'Category Name Already Exist, Please Add Different Name', 'random-banner' ),
				'type'    => 'error',
			)
		);
		exit();
	}
	if ( $category == 'new_row_created' ) {
		bc_rb_populate_category();
	}

	echo wp_json_encode(
		array(
			'message' => __( 'Something went wrong, please try again later', 'random-banner' ),
			'type'    => 'error',
		)
	);
	exit();

}

/**
 * Delete Category by ID
 *
 * @param  Array $post  Banner Data.
 */
function bc_rb_delete_category_id( $post ) {
	if ( isset( $post['category'] ) && isset( $post['category_id'] ) ) {
		$verify = bc_rb_delete_category_by_id( $post );
		if ( $verify == 'success' ) {
			echo wp_json_encode(
				array(
					'message' => __( 'Successfully Deleted ', 'random-banner' ) . bc_rb_sanitize_text_field( $post['category'] ),
					'type'    => 'success',
				)
			);
			die();
		}
	}
	echo wp_json_encode(
		array(
			'error' => __( 'Something went wrong, Please try again', 'random-banner' ),
			'type'  => 'error',
		)
	);
	die();
}

/**
 * Validate Account
 * Disabled until next version.
 *
 * @param  array  $post  Transaction Details.
 */
// function bc_rb_validate_account($post)
// {
//
// if (isset($post['domain']) && isset($post['transaction_id'])) {
// $transaction_id = bc_rb_sanitize_text_field($post['transaction_id']);
// $domain         = bc_rb_sanitize_text_field($post['domain']);
//
// $url = 'https://ifecho.com/api/random_banner/'.$transaction_id.'/'.$domain;
//
// $response = wp_remote_get($url, array('timeout' => 120, 'httpversion' => '1.1'));
// if (is_array($response)) {
// $value = wp_json_decode($response['body']);
// if ($value->status == 'ok') {
// update_option('bc_rb_payment_info', $value->transaction_id);
// echo wp_json_encode(array(
// 'status'  => 'ok',
// 'message' => __('Your Activation Code has been Successfully Verified', 'random-banner'),
// 'type'    => 'success',
// ));
// exit();
// }
// }
// }
// echo wp_json_encode(array(
// 'status'  => 'no',
// 'message' => __('Invalid Transaction Details, Please try again', 'random-banner'),
// 'type'    => 'error',
// ));
// exit();
//
// }

/**
 * Save insert short code inside all post and pages options
 *
 * @param  array $post  Post Data.
 */
function bc_rb_save_insert_post_model( $post ) {
	$serialize = serialize(
		array(
			'category_name' => bc_rb_sanitize_text_field( $post['bc_rb_category_values']['category_name'] ),
			'enable_insert' => bc_rb_sanitize_text_field( $post['bc_rb_category_values']['enable_insert'] ),
			'location'      => 'bottom',
			'slider'        => 'no',
			'post_page'     => 'post',
		)
	);
	update_option( 'bc_rb_insert_short_code_values', $serialize );
	echo wp_json_encode(
		array(
			'message' => __( 'Successfully Updated', 'random-banner' ),
			'type'    => 'success',
		)
	);
	exit();
}
