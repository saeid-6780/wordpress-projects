<?php
/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/13/2020
 * Time: 11:36 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'add_meta_boxes', 'add_click_limit_to_ad_packs' );
function add_click_limit_to_ad_packs() {
	//var_dump(get_post_type(201));
	add_meta_box(
		'click_limit',
		'click limit',
		'add_click_limit_to_ad_packs_callback',
		'package-listing'
);
}
function add_click_limit_to_ad_packs_callback( $post ) {
	// Add a nonce field so we can check for it later.
	//wp_nonce_field( 'click_limit_nonce', 'click_limit_nonce' );
	$value = get_post_meta( $post->ID, 'click_limit', true );

	?>
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="click-limit">click limit</label></th>
			<td class="at-help"></td>
			<td><input class="regular-text" id="click-limit" name="click-limit" value="<?= esc_attr( $value ) ?>" type="number"></td>
		</tr>
		</tbody>
	</table>
<?php
}
function save_ad_packs_click_limit( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['click-limit'] ) ) {
		return;
	}


	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	}
	else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Sanitize user input.
	$click_limit = intval(sanitize_text_field( $_POST['click-limit'] ));

	// Update the meta field in the database.
	update_post_meta( $post_id, 'click_limit', $click_limit );
}
add_action( 'save_post', 'save_ad_packs_click_limit' );

add_action('save_post', 'save_ad_click_limit');
function save_ad_click_limit($post_id )
{

	if (get_post_type($post_id)=='ad_listing'){
		if (isset($_POST['ad_pack_id'])){
			$ad_pack_id=$_POST['ad_pack_id'];
			if (is_numeric($ad_pack_id)) {
				$ad_click_limit = get_post_meta( $ad_pack_id, 'click_limit', true );
				update_post_meta($post_id, 'click_limit', $ad_click_limit);
			}
		}
	}

}

add_action( 'add_meta_boxes', 'add_click_limit_to_ad_listing',100 );
function add_click_limit_to_ad_listing() {
	//var_dump(get_post_type(201));
	add_meta_box(
		'click_limit',
		'click limit',
		'add_click_limit_to_ad_listing_callback',
		'ad_listing',
		'normal',
		'high'
	);
}
function add_click_limit_to_ad_listing_callback( $post ) {
	// Add a nonce field so we can check for it later.
	//wp_nonce_field( 'click_limit_nonce', 'click_limit_nonce' );
	$click_limit_value = get_post_meta( $post->ID, 'click_limit', true );
	$done_clicks_value = get_post_meta( $post->ID, 'done_clicks', true );

	?>
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><label for="click-limit">clicks limit</label></th>
			<td class="at-help"></td>
			<td><input class="regular-text" id="click-limit" name="click-limit" value="<?= esc_attr($click_limit_value) ?>" type="number"></td>
		</tr>
		<tr>
			<th scope="row"><label for="done-clicks">done clicks</label></th>
			<td class="at-help"></td>
			<td><input class="regular-text" id="done-clicks" name="done-clicks" value="<?= esc_attr($done_clicks_value) ?>" type="number"></td>
		</tr>
		</tbody>
	</table>
	<?php
}
function save_ad_listing_clicks_info( $post_id ) {

	// Check if our nonce is set.
	if ( (! isset( $_POST['click-limit'] )) && (!isset( $_POST['done-clicks'] )) ) {
		return;
	}

	// Check the user's permissions.

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Sanitize user input.
	if(isset($_POST['click-limit']) && !empty($_POST['click-limit'])){
		$click_limit = intval(sanitize_text_field($_POST['click-limit']));
		// Update the meta field in the database.
		update_post_meta( $post_id, 'click_limit', $click_limit );
	}
	if(isset( $_POST['done-clicks']) && !empty($_POST['done-clicks'])){
		$done_clicks = intval( sanitize_text_field( $_POST['done-clicks']));
		// Update the meta field in the database.
		update_post_meta( $post_id, 'done_clicks', $done_clicks );
	}
}
add_action( 'save_post', 'save_ad_listing_clicks_info' );

?>