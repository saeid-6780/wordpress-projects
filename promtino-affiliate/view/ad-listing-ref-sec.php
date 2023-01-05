<?php
/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/19/2020
 * Time: 3:14 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('cp_action_before_ad_details','show_single_ad_affiliate_section',10,3);
function show_single_ad_affiliate_section($form_fields, $post, $location) {
	if ( $location == 'list' ) {
		wp_enqueue_script('promtino-script');
		?>
		<!--<p>clicks limit: <?/*= $click_limit */?></p>-->
		<?php
		if (is_user_logged_in()){
			$data_array=get_single_ad_affiliate_data($post->ID);
			//var_dump($data_array);
			if (isset($data_array['click_data']['error'])){
				?>
				<p class="affiliate-features-not-set"><?= $data_array['click_data']['error']; ?></p>
				<?php
			}else{
				wp_localize_script('promtino-script','affiliate_data',
					['click_limit'=>$data_array['click_data']['click_limit'],
					'done_clicks'=>$data_array['click_data']['done_clicks'],
					'refferal_variable'=>get_option('uap_referral_variable'),
					'campaign_variable'=>get_option('uap_campaign_variable')
					]);

				?>
				<div class="affililiate-progress-bar">
					<span class="progress-label click-label">
						<?php
						echo $data_array['click_data']['done_clicks'].'/'.$data_array['click_data']['click_limit'].' Clicks';
						?>
					</span>
					<div class="progress-bar"></div>
					<?php
				if (isset($data_array['budget_data']['error'])){
					?>
					<p class="affiliate-features-not-set"><?= $data_array['budget_data']['error']; ?></p>
					<?php
				}else{
					?>
					<span class="progress-label budget-label">
						<?php
						echo $data_array['budget_data']['spent_budget'].'/'.$data_array['budget_data']['total_budget'].' EUR';
						?>
					</span>
					<?php
					}
					?>
				</div>
				<?php
				$user_id=get_current_user_id();
				//var_dump($user_id);
				$affiliate_ad_link_array=get_user_meta($user_id,'affiliate_ad_link_count',true);
				if (isset($affiliate_ad_link_array[$post->ID])) {
					?>
					<p>your refferal clicks: <?= $affiliate_ad_link_array[ $post->ID ] ?></p>
					<?php
				}
			}
		}
	}
}

function get_single_ad_affiliate_data($ad_id){
	$data_array=[];
	$data_array['click_data']['click_limit']=get_post_meta( $ad_id, 'click_limit', true );
	if (empty($data_array['click_data']['click_limit']))
		$data_array['click_data']['error']='Total click not set!';
	$data_array['budget_data']['total_budget']=get_post_meta($ad_id,'cp_sys_ad_listing_fee',true);
	if (empty($data_array['budget_data']['total_budget']))
		$data_array['budget_data']['error']='Total budget not set!';
	$data_array['click_data']['done_clicks']=get_post_meta( $ad_id, 'done_clicks', true );
	if (empty($data_array['click_data']['done_clicks']))
		$data_array['click_data']['done_clicks']=0;

	if (isset($data_array['click_data']['error']) || isset($data_array['budget_data']['error']) || $data_array['click_data']['click_limit']==0)
		$data_array['budget_data']['spent_budget']=0;
	else
		$data_array['budget_data']['spent_budget']=$data_array['click_data']['done_clicks']*($data_array['budget_data']['total_budget']/$data_array['click_data']['click_limit']);

	return $data_array;
}

add_action('cp_action_after_ad_details', 'show_single_ad_get_link_section', 10, 3 );
function show_single_ad_get_link_section( $form_fields, $post, $location){
	if ( $location == 'list'){
		$loged_in_user_attributes     = '';
		$not_loged_in_user_attributes = '';
		if(is_user_logged_in()){
			$loged_in_user_attributes = 'show-get-link-section';
		}else{
			$not_loged_in_user_attributes = 'data-open="register-notif-modal" aria-controls="register-notif-modal" aria-haspopup="true"';
		}
		$has_error=promtino_check_expiretion($post->ID);
		if(!empty($has_error)){
			echo '<p class="affiliate-features-not-set">'.$has_error.'</p>';
		}else {
			?>
			<p>
				<button
					class="btn btn-primary btn-lg btn-block <?= $loged_in_user_attributes; ?>" <?= $not_loged_in_user_attributes; ?>
					tabindex="0">Promote this ad
				</button>
			</p>
			<?php
			affiliate_link_builder_handler( $post );
			register_modal_info_view( $post );
		}
	}
}

function affiliate_link_builder_handler($post){
	if (is_user_logged_in()) {
		$user_id=get_current_user_id();
		$affiliate_id=affiliate_get_id_by_uid($user_id);
		if ($affiliate_id) {
			$affiliate_link=get_permalink($post->ID).'?'.get_option('uap_referral_variable').'='.$affiliate_id;
			$user_campains=get_campaigns_for_affiliate_id($affiliate_id);
			affiliate_link_builder_veiw( $affiliate_link,$user_campains );
		}
	}
}

function affiliate_link_builder_veiw($affiliate_link,$user_campains){
	echo '<div class="affiliate-link-builder-section" >';
if (!empty($user_campains)) {
		?>
		<select class="custom-select" id="campain-select">
			<option selected value>Choose your refferal campain</option>
			<?php
			foreach ($user_campains as $user_campain) {
				?>
				<option value="<?= $user_campain ?>"><?= $user_campain ?></option>
				<?php
			}
				?>

		</select>

		<?php
	}
	?>
		<div class="input-group">
			<div class="copy-clipboard-tooltip">
			<div class="input-group-prepend" onclick="copyToClipBoard('#copyable-affiliate-link')"  onmouseout="tooltipMakeDefaul()">
				<span class="tooltiptext" id="copy-tooltip">Copy to clipboard</span>
				<i class="fa fa-copy input-group-text"></i>
			</div>
			</div>
			<input id="copyable-affiliate-link" value="<?= $affiliate_link ?>" class="form-control" type="text" readonly>
		</div>
	</div>
	<?php
}

function promtino_check_expiretion($ad_id) {
	if (!$ad_id)
		return 'ERROR';//error code 0: not ad url
	$error=0;
	$expire_date=get_post_meta($ad_id,'cp_sys_expire_date',true);
	if(time()>strtotime($expire_date))
		$error='This ad has expired on '.$expire_date;
	$click_limit=get_post_meta( $ad_id, 'click_limit', true );
	$done_clicks=get_post_meta( $ad_id, 'done_clicks', true );
	if (!empty($click_limit) && !empty($done_clicks) && $done_clicks>=$click_limit)
		$error='All '.$click_limit.' clicks of this ad has been already completed.';
	return $error;
}

function affiliate_get_id_by_uid($uid=0){

	if ($uid){
		global $wpdb;
		$table_name = $wpdb->prefix . 'uap_affiliates';
		$q = $wpdb->prepare("SELECT id FROM $table_name WHERE uid=%d ;", $uid);
		$data = $wpdb->get_row($q);
		if (!empty($data) && !empty($data->id)){
			return $data->id;
		}
	}
	return 0;
}

function get_campaigns_for_affiliate_id($affiliate_id=0){

	global $wpdb;
	$return = array();
	$table = $wpdb->prefix . 'uap_campaigns';
	$affiliate_id = esc_sql($affiliate_id);
	$data = $wpdb->get_results("SELECT name FROM $table WHERE affiliate_id=$affiliate_id ");
	if (!empty($data) && is_array($data)){
		foreach ($data as $object){
			$return[] = $object->name;
		}
	}
	return $return;
}

function register_modal_info_view($post){
	?>
	<div class="reveal-overlay" style="display: none;">
		<div class="reveal" id="register-notif-modal" data-reveal="142uww-reveal" role="dialog" aria-hidden="true"
		     data-yeti-box="register-notif-modal" data-resize="register-notif-modal"
		     style="display: none; top: 34px;" tabindex="-1">
			<p class="promotion-invite-to-signup">To promote this ad
				<a href="<?= wp_login_url( get_permalink( $post->ID ) ) ?>">login</a>
				or</p>
			<div id="app-contact-form-response"></div>
			<a href="<?= wp_registration_url() ?>" target="_blank"
			   class="btn btn-primary btn-lg btn-block">Register</a>
			<button class="close-button" data-close="" aria-label="Close modal window" type="button">
				<span aria-hidden="true">×</span>
			</button>
		</div>
	</div>
<?php
}