<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/23/2018
 * Time: 11:20 PM
 */
class theme_asset
{
    public static function css($file_name)
    {
        return THEME_URL.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$file_name;
    }
    public static function js($file_name)
    {
        return THEME_URL.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$file_name;
    }
    public static function image($file_name)
    {
        return THEME_URL.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$file_name;
    }
    public static function font($file_name)
    {
        return THEME_URL.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'font'.DIRECTORY_SEPARATOR.$file_name;
    }
}