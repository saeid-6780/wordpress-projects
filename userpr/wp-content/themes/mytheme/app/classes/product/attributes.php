<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/18/2018
 * Time: 8:02 PM
 */
class product_attributes
{

    public static function get_post_category($product_id)
    {
        if ($product_id) {
            return $post_term = wp_get_post_terms($product_id, 'product_category', ["fields" => "ids"])[0];
        }else
            return 0;
    }
    public static function product_potential_attribute($product_id)
    {
        global $wpdb;
        global $table_prefix;
        $category_id=self::get_post_category($product_id);
        if($category_id==0) return 0;
        else $attributes=get_term_meta($category_id,'attributes',true);
        if (isset($attributes)){
            $attributes=implode(',',$attributes);
            $results=$wpdb->get_results("select id,name from {$table_prefix}attributes WHERE id IN ({$attributes})",ARRAY_A);
            return $results;
        }

    }

    public static function insert_product_attribute($product_id,$attribute_ids)
    {
        $attributes=get_post_meta($product_id,'attributes',true);
        //unset($attributes[5]);
        foreach ($attribute_ids as $attribute_id){
            $attributes[$attribute_id]='';
        }
        update_post_meta($product_id,'attributes',$attributes);
    }

    public static function get_attr_values($product_id,$show_parents=1)
    {
        global $wpdb;
        global $table_prefix;

        $attr_val_ids=get_post_meta($product_id,'attributes',true);
        if (!isset($attr_val_ids)){
            return false;
        }
        $attr_ids=array_keys($attr_val_ids);
        $attr_ids=implode(',',$attr_ids);
        if ($show_parents==0){
            $attr_results = $wpdb->get_results("select id,name,parent,standard from {$table_prefix}attributes WHERE id IN ({$attr_ids}) AND parent>0", ARRAY_A);
        }
        else {
            $attr_results = $wpdb->get_results("select id,name,parent,standard from {$table_prefix}attributes WHERE id IN ({$attr_ids})", ARRAY_A);
        }

        foreach ($attr_results as $key=>$attr_result){
            $attr_results[$key]['pure_value']=$attr_val_ids[$attr_results[$key]['id']];
            if (empty($attr_val_ids[$attr_result['id']])){
                $attr_results[$key]['value']='';
            }
            else{
                if ($attr_result['standard']==0){
                    $attr_results[$key]['value']=$attr_val_ids[$attr_result['id']];
                }
                else{
                    $standard_value=$wpdb->get_row( "SELECT value FROM {$table_prefix}attrvalue WHERE id ={$attr_val_ids[$attr_result['id']]}",ARRAY_A );
                    $attr_results[$key]['value']=$standard_value['value'];
                }
            }
        }

        return $attr_results;

    }

    public static function insert_product_attr_value($product_id,$attribute_ids,$values)
    {
        $attributes=get_post_meta($product_id,'attributes',true);
        foreach ($attribute_ids as $key=>$attribute_id ){
            $attributes[$attribute_id]=$values[$key];
        }
        update_post_meta($product_id,'attributes',$attributes);
    }

    public static function get_product_attr_list($product_id)
    {
        $flat_list=self::get_attr_values($product_id);
        $structured_list=[];
        foreach ($flat_list as $key=>$list){
            if ($list['parent']==0){
                $structured_list[]=$list;
                unset($flat_list[$key]);
            }
        }
        $i=0;
        foreach ($structured_list as $i=>$list){
            $structured_list[$i]['children']=[];
            foreach ($flat_list as $childlist){
                if ($childlist['parent']==$list['id']){
                    $structured_list[$i]['children'][]=$childlist;
                }
            }
        }
        return $structured_list;
    }

    public static function get_important_attr_val($product_id)
    {
        global $wpdb;
        global $table_prefix;

        $attr_val_ids=get_post_meta($product_id,'attributes',true);
        if (!isset($attr_val_ids)){
            return false;
        }
        $attr_ids=array_keys($attr_val_ids);
        $attr_ids=implode(',',$attr_ids);
        $attr_results = $wpdb->get_results("select id,name from {$table_prefix}attributes WHERE id IN ({$attr_ids}) AND parent>0 AND important=1", ARRAY_A);

        foreach ($attr_results as $key=>$attr_result){
            $user_rate_p=($attr_val_ids[$attr_result['id']]/5)*100;
            $attr_results[$key]['value']=$user_rate_p;
            $attr_results[$key]['main_value']=$attr_val_ids[$attr_result['id']];
        }
        return $attr_results;
    }

    public static function get_mean_amportant_val($product_id)
    {
        $attr_val=self::get_important_attr_val($product_id);
        $sum=0;
        $mainsum=0;
        $cnt=0;
        foreach ($attr_val as $item){
            $sum+=$item['value'];
            $mainsum+=$item['main_value'];
            $cnt++;
        }
        return ['percent_mean'=>$sum/$cnt,'main_mean'=>round($mainsum/$cnt,1)];
    }

}