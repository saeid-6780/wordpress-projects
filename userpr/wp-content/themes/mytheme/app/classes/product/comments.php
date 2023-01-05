<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/13/2018
 * Time: 1:28 PM
 */
class product_comments
{
    public static function comments_hook_handler()
    {

        $smtdbbb=new general_smtdb();
        /*$myid=get_client_id();
        $mylink=get_permalink();
        $iiiii=2;
        $smtdbbb->insert_comment_rate($myid,$mylink,$iiiii);
        $iiiii++;*/
        //$smtdbbb->search_comment_rate(4);
        add_action ('comment_post', 'product_comments::add_comment_features', 1);
        add_action ('comment_post', 'product_comments::add_comment_likes', 2);
        add_action ('comment_post', 'product_comments::add_comment_user_smt_cookie', 2);

        //add_filter('comment_form_default_fields','product_comments::comment_features_form');
        add_action('comment_form_logged_in_after','product_comments::logged_in_comment_features_form');

        add_filter( 'preprocess_comment', 'product_comments::verify_comment_features' );
        
        add_action('wp_ajax_comment_like','product_comments::like');
        add_action('wp_ajax_nopriv_comment_like','product_comments::like');

        add_action('wp_ajax_comment_dislike','product_comments::dislike');
        add_action('wp_ajax_nopriv_comment_dislike','product_comments::dislike');
        
    }

    public static function logged_in_comment_features_form()
    {
        $comment_features=product_comments::get_comment_post_features(get_the_ID());
        $features_list='';
        foreach ($comment_features as$comment_feature){
            $features_list.='<li onclick="add_features(this)" onmouseleave="feature_hover_out(this)" onmouseenter="feature_hover_in(this)" data-id="'.$comment_feature['id'].'" data-attr-id="1"><span class="square"></span>'.$comment_feature['name'].'</li>';
        }
        echo '<p>مشخص کنید که نظر شما در مورد کدام یک از ویژگی های محصول است</p><div class="row features-selected" ></div><ul class="filter-top col-sm-4"><li onmouseenter="filter_hover_in(this)" onmouseleave="filter_hover_out(this)" class="col-sm-3"><img src="'. theme_asset::image('down-arrow.png').'"><span class="title">انتخاب ویژگی</span><div class="options "><ul>'.$features_list.'</ul></div></li></ul>';

    }

    public static function verify_comment_features($commentdata )
    {
        if (!isset($_POST['features'])){
            wp_die( __( 'خطا: لطفا حداقل یک ویژگی برای دیدگاه ارسالی خود تعیین کنید.' ) );
            return $commentdata;
        }
        return $commentdata;
    }

    public static function add_comment_features($comment_id)
    {
        if(isset($_POST['features'])) {
            //$features = wp_filter_nohtml_kses($_POST['features']);
            update_comment_meta($comment_id, 'features', $_POST['features']);
        }
    }

    public static function add_comment_likes($comment_id)
    {
        self::set_like_dislike($comment_id,0,0);
    }

    public static function get_like_dislike($comment_id)
    {
        if (!$comment_id){
            return 0;
        }
        $likes=get_comment_meta($comment_id,'likes',true);
        if (isset($likes)){
            return $likes;
        }
        else return 0;
    }
    public static function set_like_dislike($comment_id,$like_num,$dislike_num)
    {
        if (!$comment_id){
            return 0;
        }
        if (is_numeric($like_num)&&is_numeric($dislike_num)){
            if ($like_num>=0 &&$dislike_num>=0){
                $likes =['like'=>$like_num,'dislike'=>$dislike_num];
                update_comment_meta($comment_id, 'likes',$likes);
            }
        }
        else return 0;
    }

    public static function like()
    {
        $id=$_POST['comment_id'];
        $likes=self::get_like_dislike($id);
        $error_code=0;
        if ($likes!=0){
            $smtdb=new general_smtdb();
            $results=$smtdb->search_comment_rate($id);
            if (is_null($results)){
                $new_like = ++$likes['like'];
                $new_dislike = $likes['dislike'];
                self::set_like_dislike($id, $new_like, $new_dislike);
                $likes = self::get_like_dislike($id);
                $url = get_comment_link($id);
                $smtdb->insert_comment_rate($id,$url,1);
            }
            else{
                if ($results['rate']==-1){
                    $new_like = ++$likes['like'];
                    $new_dislike = --$likes['dislike'];
                    self::set_like_dislike($id, $new_like, $new_dislike);
                    $likes = self::get_like_dislike($id);
                    $smtdb->update_comment_rate($results['id'],1);
                }
                else if ($results['rate']==1){
                    $error_code=1;
                }
            }
        }
        $likes['error']=$error_code;
        wp_die(json_encode($likes) );
    }
    public static function dislike()
    {
        $id=$_POST['comment_id'];
        $likes=self::get_like_dislike($id);
        $error_code=0;
        if ($likes!=0){
            $smtdb=new general_smtdb();
            $results=$smtdb->search_comment_rate($id);
            if (is_null($results)){
                $new_like = $likes['like'];
                $new_dislike = ++$likes['dislike'];
                self::set_like_dislike($id, $new_like, $new_dislike);
                $likes = self::get_like_dislike($id);
                $url = get_comment_link($id);
                $smtdb->insert_comment_rate($id,$url,-1);
            }
            else{
                if ($results['rate']==1){
                    $new_like = --$likes['like'];
                    $new_dislike = ++$likes['dislike'];
                    self::set_like_dislike($id, $new_like, $new_dislike);
                    $likes = self::get_like_dislike($id);
                    $smtdb->update_comment_rate($results['id'],-1);
                }
                else if ($results['rate']==-1){
                    $error_code=1;
                }
            }
        }
        $likes['error']=$error_code;
        wp_die(json_encode($likes) );
    }

    public static function add_comment_user_smt_cookie($comment_id)
    {
        $smt_client_id=substr(get_client_id(),0,20);
        update_comment_meta($comment_id,'smt_client_id',$smt_client_id);
    }

    public static function get_comment_post_features($product_id)
    {
        $cat_id=product_attributes::get_post_category($product_id);
        if($cat_id==0) return 0;
        else $attributes=get_term_meta($cat_id,'attributes',true);

        global $wpdb;
        global $table_prefix;
        if (isset($attributes)){
            $attributes=implode(',',$attributes);
            $results=$wpdb->get_results("select id,name from {$table_prefix}attributes WHERE id IN ({$attributes}) and important=1 AND parent<>0",ARRAY_A);
            return $results;
        }
        return 0;
    }

}