<?php
/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/16/2020
 * Time: 4:06 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function promtino_check_expire_date($url) {
	$ad_id=url_to_postid($url);
	if (!$ad_id)
		return false;//error code 0: not ad url
	$expire_date=get_post_meta($ad_id,'cp_sys_expire_date',true);
	if(time()>strtotime($expire_date))
		return true;// ad expiered

	return false;//ad not expirered

}

add_filter('uap_filter_insert_visit','promtino_track_referal_visit_handler',10,5);
function promtino_track_referal_visit_handler($stop, $affiliate_id, $referral_id, $url, $ip){
	//$sdaf=['$affiliate_id'=>$affiliate_id,'$referral_id'=>$referral_id,'$url'=>$url,'ip'=>$ip];

	//check ad date expiretion
	if (promtino_check_expire_date($url)){
		add_filter('uap_filter_insert_referral',function( $stop, $post_data){return true;},8,2);
		return true;
	}

	$ad_error=ad_refferal_viewed_link_handler($url);
	switch ($ad_error){
		case (0):
			return $stop;
		case (1):
			add_filter('uap_filter_insert_referral',function( $stop, $post_data){return true;},9,2);
			return true;
		default:
			break;
	}

	$affiliate_error=ad_refferal_affiliate_handler($url,$affiliate_id);


	return $stop;
}

function ad_refferal_affiliate_handler($url,$affiliate_id){

	$user_id=get_uid_by_affiliate_id($affiliate_id);
	if (!$user_id)
		return 0;
	$ad_id=url_to_postid($url);
	if (!$ad_id)
		return 0;
	$affiliate_ad_link_array=get_user_meta($user_id,'affiliate_ad_link_count',true);
	if (!isset($affiliate_ad_link_array[$ad_id]))
		$affiliate_ad_link_array[$ad_id]=1;
	else
		$affiliate_ad_link_array[$ad_id]=intval($affiliate_ad_link_array[$ad_id])+1;
	update_user_meta($user_id,'affiliate_ad_link_count',$affiliate_ad_link_array);
	update_user_meta($user_id,'last_clicked_ad',$ad_id);
}

function get_uid_by_affiliate_id($affiliate_id=0){

	if ($affiliate_id){
		global $wpdb;
		$table_name = $wpdb->prefix . 'uap_affiliates';
		$q = $wpdb->prepare("SELECT uid FROM $table_name WHERE id=%d ;", $affiliate_id);
		$data = $wpdb->get_row($q);
		if (!empty($data) && !empty($data->uid)){
			return $data->uid;
		}
	}
	return 0;
}

function ad_refferal_viewed_link_handler($url){

	$ad_id=url_to_postid($url);
	if (!$ad_id)
		return 0;//error code 0: not ad url
	$done_clicks=get_post_meta($ad_id,'done_clicks',true);
	if (empty($done_clicks))
		update_post_meta($ad_id,'done_clicks',1);
	else {
		$click_limit=get_post_meta($ad_id,'click_limit',true);
		if ($done_clicks>=$click_limit)
			return 1;//error code 1: full click limit
		update_post_meta( $ad_id, 'done_clicks', ++ $done_clicks );
	}

	return 2;//successfull
}

add_filter('uap_save_referral_filter','promtino_referal_visit_amount_handler',11,1);
function promtino_referal_visit_amount_handler ($post_data){
	$user_id=get_uid_by_affiliate_id($post_data['affiliate_id']);
	if (!$user_id)
		return $post_data;
	$clicked_ad_id=get_user_meta($user_id,'last_clicked_ad',true);
	$ad_listing_fee=get_post_meta($clicked_ad_id,'cp_sys_ad_listing_fee',true);
	$click_limit=get_post_meta($clicked_ad_id,'click_limit',true);
	if (empty($ad_listing_fee) || empty($click_limit))
		return $post_data;
	$new_amount=$ad_listing_fee/$click_limit;
	$post_data['amount']=$new_amount;

	return $post_data;
}