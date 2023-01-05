<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/27/2018
 * Time: 10:08 AM
 */
class theme_optionsPanel
{
    public function __construct()
    {
        add_action('admin_menu',[$this,'theme_menu_handler']);
        add_action('save_theme_options',[$this,'save_options']);
    }

    public function theme_menu_handler()
    {
        add_theme_page('تنظیمات قالب','تنظیمات پوسته','manage_options','theme_options_panel',[$this,'theme_options_view_handler']);
    }

    public function theme_options_view_handler()
    {
        if (isset($_POST['submit_theme_form'])){

            do_action('save_theme_options',$_POST);
        }
        $option=self::get_options();
        $view_path=THEME_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'theme_options'.DIRECTORY_SEPARATOR.'main.php';
        if (is_file($view_path) && is_readable($view_path))
            include $view_path;
    }

    public function save_options()
    {
        $option['banner_text']=$_POST['banner_text'];
        $option['help_section']=isset($_POST['help_section'])?1:0;
        update_option(THEME_OPTIONS,$option);
    }

    public static function get_options()
    {
        return get_option(THEME_OPTIONS);
    }

}