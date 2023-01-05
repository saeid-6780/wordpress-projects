<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/19/2018
 * Time: 12:48 AM
 */
class analysis_cleansing
{
    public static function fill_missed_data()
    {
    	$file_name='product rating section';
        $data=analysis_file::csv_reader($file_name);
        $data=self::split_to_complete_incomplete($data);
        $complete_data=$data['complete_data'];
        $incomplete_data=$data['incomplete_data'];

        $sum=0;
        $sum_weight=0;

	    foreach ($complete_data as $first_element){
		    $complete_array=array_keys($first_element);
		    break;
	    }

//#D5E9E7

        //var_dump($complete_data);
        //var_dump($incomplete_data);
        $euclidean_distances=self::find_Knn($incomplete_data,$complete_data);
        //$complete_array=[0,2,4,6,8,10,12];
        foreach($euclidean_distances as $ikey=>$incomplete_record){
            $incomplete_indexes=array_diff($complete_array,array_keys($incomplete_data[$ikey]));
            foreach($incomplete_indexes as $incomplete_index){
                foreach($incomplete_record as $ckey=>$complete_record){
                	/*$complete_record=$complete_record==0?0.00001:$complete_record;
                    $weighted=(1/pow($complete_record,2))*$complete_data[$ckey][$incomplete_index];
                    $sum+=$weighted;
                    $sum_weight+=$complete_record;*/
                	$sum+=$complete_data[$ckey][$incomplete_index];
                }
                $mean=$sum/count($euclidean_distances[$ikey]);
                $sum=0;
                $sum_weight=0;
                $incomplete_data[$ikey][$incomplete_index]=$mean;
                ksort($incomplete_data[$ikey]);
            }
        }
        //var_dump($incomplete_data);
        //ksort($incomplete_data);
        var_dump($incomplete_data);
        $filled_data=[];
        foreach ($complete_data as $ckey=>$c_data){
            $filled_data[$ckey]=$c_data;
        }
        foreach ($incomplete_data as $ikey=>$i_data){
            $filled_data[$ikey]=$i_data;
        }
        ksort($filled_data);
        var_dump($filled_data);
        analysis_file::csv_maker([$filled_data],$file_name.'_filled_missed_data',[]);
    }

    public static function find_Knn($incomplete_data,$complete_data)
    {
    	$k=(count($complete_data)/5)>4?(count($complete_data)/5):1;
        foreach ($incomplete_data as $ikey=>$incomplete_record){
            foreach ($complete_data as $ckey => $complete_record){
                $euclidean_distance[$ikey][$ckey] = self::euclidean_distance($incomplete_record, $complete_record);
            }
            asort($euclidean_distance[$ikey]);
            $euclidean_distance[$ikey] = array_slice($euclidean_distance[$ikey], 0, $k, true);
            //var_dump($euclidean_distance);
        }
        return $euclidean_distance;
    }

    public static function euclidean_distance($incomplete_record,$complete_record)
    {
        $sum=0;
        foreach ($incomplete_record as $key=>$record){
            $sum+=pow(($incomplete_record[$key] - $complete_record[$key]), 2);
        }
        return sqrt($sum);
    }

    public static function split_to_complete_incomplete($data)
    {
        $complete_data=[];
        $incomplete_data=[];
        foreach ($data as $key=>$record){
            if (in_array('TRUE', $record)){
                $complete_data_size=count($record);
            for ($i = 1; $i < $complete_data_size; $i += 2){
                if ($record[$i] == 'TRUE') {
                    unset($record[$i-1]);
                }
                unset($record[$i]);
            }
            $incomplete_data[$key] = $record;
            }
            else{
                $incomplete_data_size=count($record);
                for ($i = 1; $i<$incomplete_data_size; $i += 2){
                    unset($record[$i]);
                }
                $complete_data[$key] = $record;
            }
        }
        return['complete_data'=>$complete_data,'incomplete_data'=>$incomplete_data];
    }
}