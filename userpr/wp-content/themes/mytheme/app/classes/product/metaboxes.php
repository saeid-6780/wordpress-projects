<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/25/2018
 * Time: 10:41 AM
 */
class product_metaboxes
{
    public static function register_price_metabox()
    {
        add_meta_box('product_price_meta_box','قیمت محصول','product_metaboxes::price_content_handler','product');
    }

    public static function price_content_handler($post)
    {
        $product_price=(int) get_post_meta($post->ID,'product_price',true);
        $product_discount_percent=(int) get_post_meta($post->ID,'product_dicount_percent',true);
        $data=['product_price'=>$product_price,'product_dicount_percent'=>$product_discount_percent];
        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'price_metabox.php';
    }

    public static function save_price($post_id)
    {
        if (isset($_POST['product_price']) && intval($_POST['product_price'])>=0){
            update_post_meta($post_id,'product_price',intval($_POST['product_price']));
        }
    }

    public static function save_discount_percent($post_id)
    {
        if (isset($_POST['product_dicount_percent']) && intval($_POST['product_dicount_percent'])>0 && intval($_POST['product_dicount_percent'])<=100){
            update_post_meta($post_id,'product_dicount_percent',intval($_POST['product_dicount_percent']));
        }
    }

    public static function register_highlight_metabox()
    {
        add_meta_box('product_highlight_meta_box','نکات برجسته محصول','product_metaboxes::highlight_content_handler','product','advanced','high');
    }

    public static function highlight_content_handler($post)
    {
        $product_highlight=get_post_meta($post->ID,'product_highlight',true);
        $data=['product_highlight'=>$product_highlight];
        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'highlight_metabox.php';
    }

    public static function save_highlight($post_id)
    {
        //var_dump($_POST['product_slider']);
        if (isset($_POST['product_highlight']) && count($_POST['product_highlight'])>0){
            update_post_meta($post_id,'product_highlight',$_POST['product_highlight']);
        }
    }

    public static function register_slider_metabox()
    {
        add_meta_box('product_slider_meta_box','تصاویر اسلایدر محصول','product_metaboxes::slider_content_handler','product','advanced','high');
    }

    public static function slider_content_handler($post)
    {
        $product_slider=get_post_meta($post->ID,'product_slider',true);
        $data=['product_slider'=>$product_slider];

        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'gallery_metabox.php';
    }

    public static function save_slider($post_id)
    {
        //var_dump($_POST['product_slider']);
        if (isset($_POST['product_slider']) && count($_POST['product_slider'])>0){
            update_post_meta($post_id,'product_slider',$_POST['product_slider']);
        }
    }
	
	public static function register_colors_metabox()
	{
		add_meta_box('product_colors_meta_box','رنگ های محصول محصول','product_metaboxes::colors_content_handler','product');
	}

	public static function colors_content_handler($post)
	{
		$product_colors=get_post_meta($post->ID,'product_colors',true);
		$data=['product_colors'=>$product_colors];
		include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'colors_metabox.php';
	}

	public static function save_colors($post_id)
	{
		//var_dump($_POST['product_slider']);
		if (isset($_POST['product_colors']) && count($_POST['product_colors'])>0){
			update_post_meta($post_id,'product_colors',$_POST['product_colors']);
		}
	}

    public static function register_thumbnails_metabox()
    {
        add_meta_box('product_thumbnails_meta_box','پیش نمایش محصول','product_metaboxes::thumbnails_content_handler','product');
    }

    public static function thumbnails_content_handler($post)
    {
        $product_thumbnails=get_post_meta($post->ID,'product_thumbnails',true);
        $data=['product_thumbnails'=>$product_thumbnails];

        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'thumbnails_metabox.php';
    }

    public static function save_thumbnails($post_id)
    {
        //var_dump($_POST['product_slider']);
        if (isset($_POST['product_thumbnails']) && count($_POST['product_thumbnails'])>0){
            update_post_meta($post_id,'product_thumbnails',$_POST['product_thumbnails']);
        }
    }

    public static function register_attribute_metabox()
    {
        add_meta_box('product_attribute_meta_box','ویژگی های محصول','product_metaboxes::attribute_content_handler','product');
    }

    public static function attribute_content_handler($post)
    {
        $potential_attribute=product_attributes::product_potential_attribute($post->ID);
        $data=['potential_attribute'=>$potential_attribute];

        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'attribute_metabox.php';
    }

    public static function save_attribute($post_id)
    {
        //var_dump($_POST['product_slider']);

        if (isset($_POST['features']) && count($_POST['features'])>0){
        product_attributes::insert_product_attribute($post_id,$_POST['features']);
        }
    }


    public static function register_attribute_value_metabox()
    {
        add_meta_box('product_attribute_value_meta_box','مقادیر ویژگی های محصول','product_metaboxes::attribute_value_content_handler','product');
    }

    public static function attribute_value_content_handler($post)
    {
        $product_attributes_values=product_attributes::get_attr_values($post->ID,0);
        $data=['product_attributes_values'=>$product_attributes_values];

        include THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'attribute_value_metabox.php';
    }

    public static function save_attribute_value($post_id)
    {
        //var_dump($_POST['product_slider']);
        if (isset($_POST['product_attr_val']) && count($_POST['product_attr_val'])>0){
            product_attributes::insert_product_attr_value($post_id,$_POST['product_attr_id'],$_POST['product_attr_val']);
        }
    }

}