<?php

class themeAutoloader{
    public function __construct()
    {
        spl_autoload_register([$this,'autoload']);
    }

    private function autoload($class_name)
    {
        $file=self::convert_class_to_file($class_name);
        if (file_exists($file)&&is_readable($file)){
            include $file;
        }
    }

    private static function convert_class_to_file($class_name)
    {
        $class_name=strtolower($class_name);
        $class_file='';
        $class_path_arr=explode('_',$class_name);

        foreach ($class_path_arr as $cf){
            $class_file.=DIRECTORY_SEPARATOR.$cf;
        }
        return THEME_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'classes'.$class_file.'.php';


    }
}

new themeAutoloader();



?>