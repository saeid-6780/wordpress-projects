<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 5/27/2018
 * Time: 5:47 AM
 */
class general_lottery
{
    public static function save_smt_client_id($entry, $form)
    {
        $user_client_id=substr(get_client_id(),0,20);
        $email_meta_key='smt_client_id';
        if ($user_client_id){
            gform_update_meta($entry['id'],$email_meta_key,$user_client_id);
        }
        self::client_email_set($entry['id']);
    }

    public static function client_email_set($email_entry_id)
    {
        global $client_set_email;
        $smtdb=new general_smtdb();
        if ($smtdb->search_email()==null)
            $smtdb->set_email($email_entry_id);
        $client_set_email=1;
    }
    public static function close_email_init()
    {
        if (!isset($_SESSION['close_email']))
            $_SESSION['close_email']=[];
    }

    public static function add_close_num()
    {
        self::close_email_init();
        if (isset( $_SESSION['close_email']['close_num'])) {
            $_SESSION['close_email']['close_num']++;
        }else{
            $_SESSION['close_email']['close_num']=1;
        }
    }

    public static function get_close_num()
    {
        if (isset( $_SESSION['close_email']['close_num'])) {
            return $_SESSION['close_email']['close_num'];
        }
        return 0;
    }

    /*function set_profile_resume($entry, $form){
        global $ae_post_factory,$user_ID;
        $post_object = $ae_post_factory->get( PROFILE );

        $profile_id = get_user_meta( $user_ID, 'user_profile_id', true );

        $profile = array();
        if ( $profile_id ) {
            $profile_post = get_post( $profile_id );
            if ( $profile_post && ! is_wp_error( $profile_post ) ) {
                $profile = $post_object->convert( $profile_post );
            }
        }
        $user_meta_key='resume_id';
        $resume_meta_key='user_id';
        if (isset($profile->post_author))
            gform_update_meta($entry['id'],$resume_meta_key,$profile->post_author);
        if (isset($profile->ID))
            update_post_meta($profile->ID,$user_meta_key,$entry['id']);
    }*/
}