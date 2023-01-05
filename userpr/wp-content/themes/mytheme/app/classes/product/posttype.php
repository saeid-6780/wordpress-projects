<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/25/2018
 * Time: 10:17 AM
 */
class product_posttype
{
    public static function make_post_type()
    {
        $labels = array(
            'name'               => _x( 'محصولات', 'post type general name', 'your-plugin-textdomain' ),
            'singular_name'      => _x( 'محصول', 'post type singular name', 'your-plugin-textdomain' ),
            'menu_name'          => _x( 'محصولات', 'admin menu', 'your-plugin-textdomain' ),
            'name_admin_bar'     => _x( 'محصول', 'add new on admin bar', 'your-plugin-textdomain' ),
            'add_new'            => _x( 'افزودن', 'محصول', 'your-plugin-textdomain' ),
            'add_new_item'       => __( 'افزودن محصول جدید', 'your-plugin-textdomain' ),
            'new_item'           => __( 'محصول جدید', 'your-plugin-textdomain' ),
            'edit_item'          => __( 'ویرایش محصول', 'your-plugin-textdomain' ),
            'view_item'          => __( 'نمایش محصول', 'your-plugin-textdomain' ),
            'all_items'          => __( 'همه محصولات', 'your-plugin-textdomain' ),
            'search_items'       => __( 'جستجوی محصولات', 'your-plugin-textdomain' ),
            'parent_item_colon'  => __( 'والد محصولات:', 'your-plugin-textdomain' ),
            'not_found'          => __( 'محصولی یافت نشد.', 'your-plugin-textdomain' ),
            'not_found_in_trash' => __( 'در زباله دان محصولی یافت نشد.', 'your-plugin-textdomain' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'your-plugin-textdomain' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'product' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'product', $args );
    }

    public static function price_column($columns)
    {
        $columns['product_price']='قیمت';
        return $columns;
    }

    public static function price_column_value($column,$post_id)
    {
        if ($column=='product_price'){
            $price_val=get_post_meta($post_id,'product_price',true);
            //echo  utility::convert_eng_to_fa(number_format($price_val));
        }
    }
}