<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/26/2018
 * Time: 1:08 PM
 */
class product_basket
{
    public static function add($product_id,$quantity=1)
    {
        self::basket_init();
        $product=product_product::find($product_id);
        if (self::exist_item($product_id)) {
            if ($quantity < 0)
                $quantity = 0;
            $_SESSION['basket']['items'][$product_id]['quantity'] += $quantity;
        }
        else{
            if (isset($_SESSION['basket']['items'])) {
                if (count($_SESSION['basket']['items']) >= 2) {
                    return false;
                }
            }
            $_SESSION['basket']['items'][$product_id] = [
                'product_name' => $product->post_title,
                'quantity' => $quantity,
                'product_price' => product_product::price($product_id, false)['discount_price']
            ];

            /*$ajaxsessiondata = [];
            $ajaxsessiondata['ajax_url'] = admin_url() . 'admin-ajax.php';
            $ajaxsessiondata['basket_exist1'] = 1;
            wp_localize_script('sessionhandler', 'ajaxdata', $ajaxsessiondata);*/
        }
        return true;
    }

    public static function update($product_id,$quantity)
    {
        if (self::exist_item($product_id)){
            if ($quantity <= 0) {
                $_SESSION['basket']['items'][$product_id]['quantity'] = 0;
                self::remove($product_id);
                return true;
            }
            else {
                $_SESSION['basket']['items'][$product_id]['quantity'] = $quantity;
                return true;
            }
        }
        else return false;

    }

    public static function remove($product_id)
    {
        if (self::exist_item($product_id))
            unset($_SESSION['basket']['items'][$product_id]);
    }

    public static function basket_init()
    {
        if (!isset($_SESSION['basket']))
            $_SESSION['basket']=[];
    }

    public static function exist_item($product_id){
        if (isset($_SESSION['basket']['items'][$product_id])){
            return true;
        }
        return false;
    }

    public static function total_count()
    {
        if (isset($_SESSION['basket']['items'])){
            return count($_SESSION['basket']['items']);
        }
        return 0;
    }

    public static function basket_total_price()
    {
        $items=$_SESSION['basket']['items'];
        $total_price = array_reduce($items,function ($carry,$item){
            return $carry+$item['product_price']*$item['quantity'];
        });
        return $total_price;
    }

    public static function basket_items()
    {
        return $_SESSION['basket']['items'];
    }

    public static function save_basket2db()
    {
        $basket_tbl=[];
        if (isset($_SESSION['basket']['items'])) {

            $session_id = session_id();

            $smtdb = new general_smtdb();
            $basket_tbl = $smtdb->search_basket($session_id);
            if ($basket_tbl == null) {
                $smtdb->insert_basket($session_id, $_SESSION['basket']);
            } else {
                $smtdb->update_basket($basket_tbl['id'], $_SESSION['basket']);
            }
        }

            wp_die(json_encode($basket_tbl));

    }

    public static function ajax_remove()
    {
        $return_json=[];
        if (isset($_POST['product_id'])){
            self::remove($_POST['product_id']);
        }
        if (count($_SESSION['basket']['items'])<=0){
            $return_json['error']=1;
        }
        else{
            $return_json['total_count']=general_utility::convert_eng_to_fa(self::total_count());
            $return_json['total_price']=general_utility::convert_eng_to_fa(self::basket_total_price());
        }


        wp_die(json_encode($return_json));
    }

}