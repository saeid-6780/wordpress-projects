<?php
/**
 * UserRegistration Updates
 *
 * Function for updating data, used by the background updater.
 *
 * @package UserRegistration\Functions
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Update DB Version.
 */
function ur_update_100_db_version() {
	UR_Install::update_db_version( '1.0.0' );
}

/**
 * Update usermeta.
 */
function ur_update_120_usermeta() {
	global $wpdb;

	// Get usermeta.
	$usermeta = $wpdb->get_results( "SELECT user_id, meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'user_registration\_%';" );

	// Update old usermeta values.
	foreach ( $usermeta as $metadata ) {
		$user_id     = intval( $metadata->user_id );
		$json_val    = json_decode( $metadata->meta_value );
		$explode_val = explode( '__', $metadata->meta_value );

		if ( $json_val && $metadata->meta_value != $json_val ) {
			update_user_meta( $user_id, $metadata->meta_key, json_decode( $metadata->meta_value ) );
		} elseif ( $metadata->meta_value !== end( $explode_val ) ) {
			update_user_meta( $user_id, $metadata->meta_key, trim( end( $explode_val ) ) );
		}
	}

	// Delete old user keys from usermeta.
	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'ur_%_params';" );
}

/**
 * Update DB Version.
 */
function ur_update_120_db_version() {
	UR_Install::update_db_version( '1.2.0' );
}

/**
 * Update usermeta.
 */
function ur_update_125_usermeta() {
	
	$users = get_users( array( 'fields' => array( 'ID' ) ) );
	
	foreach( $users as $user_id ) {

		if( metadata_exists( 'user', $user_id->ID, 'user_registration_user_first_name' ) ) {
			$first_name = get_user_meta ( $user_id->ID, 'user_registration_user_first_name', true );
			update_user_meta ( $user_id->ID, 'first_name', $first_name );
			delete_user_meta( $user_id->ID, 'user_registration_user_first_name');
		}

		if( metadata_exists( 'user', $user_id->ID, 'user_registration_user_last_name' ) ) {
			$last_name = get_user_meta ( $user_id->ID, 'user_registration_user_last_name', true );
			update_user_meta ( $user_id->ID, 'last_name', $last_name );
			delete_user_meta( $user_id->ID, 'user_registration_user_last_name');
		}
		
		if( metadata_exists( 'user', $user_id->ID, 'user_registration_user_description' ) ) {
			$description = get_user_meta ( $user_id->ID, 'user_registration_user_description', true );
			update_user_meta ( $user_id->ID, 'description', $description );
			delete_user_meta( $user_id->ID, 'user_registration_user_description');
		}

		if( metadata_exists( 'user', $user_id->ID, 'user_registration_user_nickname' ) ) {
			$nickname = get_user_meta ( $user_id->ID, 'user_registration_user_nickname', true );
			update_user_meta ( $user_id->ID, 'nickname', $nickname );
			delete_user_meta( $user_id->ID, 'user_registration_user_nickname');
		}
	
    }
}
/**
 * Update DB Version.
 */
function ur_update_125_db_version() {
	UR_Install::update_db_version( '1.2.5' );
}
