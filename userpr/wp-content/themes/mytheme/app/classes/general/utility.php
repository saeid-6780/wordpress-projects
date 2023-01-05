<?php
/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/1/2018
 * Time: 8:17 PM
 */
class general_utility
{
    public static function convert_eng_to_fa($input)
    {
        $input=number_format($input);
        $persian=['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $english=['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($english,$persian,$input);
    }
}