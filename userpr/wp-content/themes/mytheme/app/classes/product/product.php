<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/1/2018
 * Time: 8:27 PM
 */
class product_product
{

    public static function find($product_id)
    {
        return get_post($product_id);
    }

    public static function price($product_id,$for_show=true)
    {
        if (!$product_id){
            return 0;
        }
        $price=get_post_meta($product_id,'product_price',true);
        if (intval($price)>0){
            $discount_price=self::price_with_discount($product_id,$price);
            $org_price=apply_filters('product_price',$price);
            if ($for_show) {
                $discount_price = general_utility::convert_eng_to_fa($discount_price);
                $org_price = general_utility::convert_eng_to_fa($org_price);
            }
            return ['discount_price'=>$discount_price,'org_price'=>$org_price];
        }
        else return 0;
    }

    public static function price_with_discount($product_id,$price)
    {
        $discount_percent=get_post_meta($product_id,'product_dicount_percent',true);
        if (intval($discount_percent>0)) {
            $discount_price = $price - (($discount_percent * $price) / 100);
            return apply_filters('product_diccount_price', $discount_price);
        }
        return $price;
    }


    public static function slider($product_id)
    {
        if (!$product_id){
            return 0;
        }
        $slider=get_post_meta($product_id,'product_slider',true);
        if (isset($slider)){

            return $slider;
        }
        else return false;
    }

    public static function thumbnails($product_id)
    {
        if (!$product_id){
            return 0;
        }
        $slider=get_post_meta($product_id,'product_thumbnails',true);
        if (isset($slider)){

            return $slider;
        }
        else return false;
    }

    public static function product_hook_handler()
    {
        add_action('save_post_product','product_product::product_rate_init');
        add_action('wp_ajax_product_rate','product_product::set_product_rate');
        add_action('wp_ajax_nopriv_product_rate','product_product::set_product_rate');
    }

    public static function product_rate_init($product_id)
    {
        $rates=self::get_product_rate($product_id);
        if (!$rates){
            $rate=['num'=>0,'avrate'=>0];
            update_post_meta($product_id,'product_rate',$rate);
        }
    }

    public static function get_product_rate($product_id)
    {
        if (!$product_id){
            return false;
        }
        $rates=get_post_meta($product_id,'product_rate',true);
        if (isset($rates)){
            return $rates;
        }
        else return false;
    }

    public static function set_product_rate()
    {
        $product_id=$_POST['product_id'];
        $inputrate=$_POST['rate'];
        $rates=self::get_product_rate($product_id);
        if ($rates){
            $smtdb=new general_smtdb();
            $user_rate=$smtdb->search_product_rate($product_id);
            if (is_null($user_rate)) {
                $oldnum = $rates['num'];
                $rates['num']++;
                $rates['avrate'] = (($rates['avrate'] * $oldnum) + $inputrate) / $rates['num'];
                update_post_meta($product_id, 'product_rate', $rates);

                $post_url=get_permalink($product_id);
                $smtdb->insert_product_rate($product_id,$post_url,$inputrate);
            }
            else{
                $oldrate=$user_rate['rate'];
                $rates['avrate']=(($rates['avrate']*$rates['num'])-$oldrate+$inputrate)/$rates['num'];
                update_post_meta($product_id, 'product_rate', $rates);
                $smtdb->update_product_rate($user_rate['id'],$inputrate);
            }
        }
        $rates['your_rate']=$inputrate/5;
        wp_die(json_encode($rates));
    }
15 20
39 80

}