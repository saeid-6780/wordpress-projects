<?php
include ('constant.php');
include('app/themeAutoloader.php');
//echo get_home_path();
include ABSPATH.'/smt2/core/functions.php';

date_default_timezone_set('Asia/Tehran');


add_action('after_setup_theme','theme_initializer::setup');
add_action('init','product_posttype::make_post_type');
add_action('init','product_taxonomy::make_category');

add_action('add_meta_boxes','product_metaboxes::register_highlight_metabox');
add_action('save_post','product_metaboxes::save_highlight');

add_action('add_meta_boxes','product_metaboxes::register_price_metabox');
add_action('save_post','product_metaboxes::save_price');
add_action('save_post','product_metaboxes::save_discount_percent');

add_action('add_meta_boxes','product_metaboxes::register_slider_metabox');
add_action('save_post','product_metaboxes::save_slider');

add_action('add_meta_boxes','product_metaboxes::register_thumbnails_metabox');
add_action('save_post','product_metaboxes::save_thumbnails');

add_action('add_meta_boxes','product_metaboxes::register_colors_metabox');
add_action('save_post','product_metaboxes::save_colors');

add_action('add_meta_boxes','product_metaboxes::register_attribute_metabox');
add_action('save_post','product_metaboxes::save_attribute');

add_action('add_meta_boxes','product_metaboxes::register_attribute_value_metabox');
add_action('save_post','product_metaboxes::save_attribute_value');


add_filter('add_to_basket','product_basket::add',10,2);

add_action('wp_ajax_basket_save','product_basket::save_basket2db');
add_action('wp_ajax_nopriv_basket_save','product_basket::save_basket2db');

add_action('wp_ajax_basket_product_remove','product_basket::ajax_remove');
add_action('wp_ajax_nopriv_basket_product_remove','product_basket::ajax_remove');

add_action('wp_ajax_page_element_range','analysis_elementsPosition::save_element_position_range');
add_action('wp_ajax_nopriv_page_element_range','analysis_elementsPosition::save_element_position_range');

product_product::product_hook_handler();
product_comments::comments_hook_handler();


function custom_login_img(){
    echo '<style>#login h1 a {background-image:url('.get_bloginfo('template_directory').'/assets/images/userprlogo.png)}</style>';
}



add_filter('login_head', 'custom_login_img', 999);

//
add_action( 'gform_after_submission_1', 'general_lottery::save_smt_client_id', 10, 2 );

?>