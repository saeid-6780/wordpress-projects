<?php


class theme_initializer
{
    public static function setup()
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support( 'custom-header' );

        global $client_set_email;

        new theme_optionsPanel();

        self::session_start();
    }

    public static function session_start()
    {
        session_start();
    }
}