<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/31/2018
 * Time: 6:28 PM
 */
class analysis_elementsPosition
{
    public static function save_element_position_range()
    {
        $smtdb=new general_smtdb();
        $inserted_ids=[];
        $product_id=0;
        if (isset($_POST['product_id']))
            $product_id=$_POST['product_id'];
        $product_url=get_permalink($product_id);
        $screen_width=$_POST['screen_width'];
        $page_type=$_POST['page_type'];
        $elements_position_range=$_POST['element_range'];

        if (empty($smtdb->search_element_position_range($product_url,$screen_width,$page_type))) {
            foreach ($elements_position_range as $key => $element_position_range) {
                $inserted_ids[] = $smtdb->insert_element_position_range($product_id, $product_url, $screen_width, $page_type, $key, $element_position_range['startx'], $element_position_range['endx'], $element_position_range['starty'], $element_position_range['endy']);
            }
        }
            //page_type:ajaxdata.page_type,screen_width:screen.width,element_range:tagPixelRange
        wp_die(json_encode($inserted_ids));
    }
}

//seen pages resolutions
/*1024 	2
1280 	4
1360 	1
1366 	27
1368 	1
1475 	1
1525 	1
1536 	19
1600 	2
1920 	3*/