<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 6/16/2018
 * Time: 6:11 PM
 */
class analysis_dbmodification
{
    public static function set_single_page_type()
    {
        $smtdb=new general_smtdb();
        return $smtdb->set_cached_page_type('product',2);
    }

    public static function test_1_row()
    {
        $in1=0;
        $in0=0;
        $clccnt=0;

        $smtdb=new general_smtdb();
        $record_row=$smtdb->test_1_record_select();

        foreach($record_row as $item){
            $coords_x1=explode(',',$item['coords_x']);
            $coords_y1=explode(',',$item['coords_y']);
            analysis_features::Mouse_wheel_scroll($coords_x1,$coords_y1,0,count($coords_x1)-1) ;
        }
        /*foreach ($record_row as $item){
            $coords_x1=explode(',',$item['coords_x']);
            $coords_y1=explode(',',$item['coords_y']);
            $clicks1=explode(',',$item['clicks']);
            $hovered1=explode(',',$item['hovered']);
            $clicked1=explode(',',$item['clicked']);
            echo count($coords_x1).' '.count($coords_y1).' '.count($hovered1).' '.count($clicks1).' '.count($clicked1).' '.count($coords_y1)/count($hovered1).' '.substr_count($item['coords_x'],',').' '.substr_count($item['coords_y'],',').' '.substr_count($item['clicks'],','). ' '.substr_count($item['hovered'],',').' tedade 1ha:'.substr_count($item['clicks'],'1').'<br>';


            foreach ($clicks1 as $clicksss){
                if ($clicksss==1){
                    $in0=0;
                    if ($in1==0)
                        $clccnt++;
                    $in1=1;

                }
                else{
                    $in1=0;

                    $in0=1;
                }
            }
            echo 'tedad click='.$clccnt.'<br>';
            if($item['id']==199){
                echo 'tedad DIV#main-banner: '.substr_count($item['hovered'],'DIV#main-banner,')/count($hovered1).'<br>';
                echo 'tedad A#select-cat-button: '.substr_count($item['hovered'],'A#select-cat-button,')/count($hovered1).'<br>';
                echo 'tedad HTML: '.substr_count($item['hovered'],'HTML,')/count($hovered1).'<br>';
                echo 'tedad HTML>BODY>DIV.userpr-content>DIV.header>DIV.container>DIV.logo>A>SPAN: '.substr_count($item['hovered'],'HTML>BODY>DIV.userpr-content>DIV.header>DIV.container>DIV.logo>A>SPAN,')/count($hovered1).'<br>';
                echo 'tedad DIV#main-banner>DIV.container: '.substr_count($item['hovered'],'DIV#main-banner>DIV.container,')/count($hovered1).'<br>';
                echo 'DIV#main-banner>DIV.container>H1: '.substr_count($item['hovered'],'DIV#main-banner>DIV.container>H1,')/count($hovered1).'<br>';
                echo 'tedad HTML>BODY>DIV.userpr-content>DIV.header>DIV.container>DIV.logo>A: '.substr_count($item['hovered'],'HTML>BODY>DIV.userpr-content>DIV.header>DIV.container>DIV.logo>A,')/count($hovered1).'<br>';
                echo $item['hovered'].'<br><br>';
            }
        }*/

        return $record_row;
    }

}