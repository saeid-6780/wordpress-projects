<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/28/2018
 * Time: 6:47 PM
 */
class product_taxonomy {

	public static function make_category(  ) {
		$labels = array(
			'name'              => _x( 'دسته ها', 'taxonomy general name', 'textdomain' ),
			'singular_name'     => _x( 'دسته', 'taxonomy singular name', 'textdomain' ),
			'search_items'      => __( 'جستجوی دسته ها', 'textdomain' ),
			'all_items'         => __( 'همه دسته های', 'textdomain' ),
			'view_item'         => __( 'مشاهده', 'textdomain' ),
			'parent_item'       => __( 'دسته والد', 'textdomain' ),
			'parent_item_colon' => __( 'دسته والد:', 'textdomain' ),
			'edit_item'         => __( 'ویرایش دسته', 'textdomain' ),
			'update_item'       => __( 'به روزرسانی دسته', 'textdomain' ),
			'add_new_item'      => __( 'افزودن دسته جدید', 'textdomain' ),
			'new_item_name'     => __( 'نام دسته جدید', 'textdomain' ),
			'menu_name'         => __( 'دسته ها', 'textdomain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product_category' ),
		);

		register_taxonomy( 'product_category', array( 'product' ), $args );

	}

}