<?php
/**
 * @package promtino-affiliate
 */
/*
Plugin Name: promtino-affiliate
Plugin URI: https://cakaneh.ir/
Description: handle promtino website affiliate functionality
Version: 1.0
Author: Saeid6780
Author URI: https://cakaneh.ir/
License: Saeid6780
Text Domain: promtino-affiliate
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('PLUGIN_PATH',plugin_dir_path(__FILE__));
define('PLUGIN_URL',plugin_dir_url(__FILE__));

add_action('wp_enqueue_scripts','enqueue_plugin_css_js',1);
function enqueue_plugin_css_js(){
	wp_enqueue_style('promtino-style',PLUGIN_URL.'assets/css/style.css',['at-main','slick-theme']);
	wp_enqueue_script('jqmeter-script',PLUGIN_URL.'assets/js/jqmeter.min.js',['jquery']);
	wp_register_script('promtino-script',PLUGIN_URL.'assets/js/script.js',['jqmeter-script']);
	/*wp_enqueue_script('promtino-script');*/
	/*wp_localize_script('promtino-script','affiliate_data',
		['click_limit'=>'adf',
		 'done_clicks'=>'asdf']);*/
}

require_once (PLUGIN_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'metabox.php');
require_once (PLUGIN_PATH.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'refferal-handler.php');
require_once (PLUGIN_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'ad-listing-ref-sec.php');

//register_uninstall_hook(__FILE__, 'pluginprefix_function_to_run');
?>