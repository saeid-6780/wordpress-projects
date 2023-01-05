<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 6/29/2018
 * Time: 12:05 AM
 */
class analysis_features
{
    public static function distance($coordinates_x,$coordinates_y,$start_index,$end_index,$estimate=1)
    {
        $i=$start_index+$estimate;
        $sum=0;
        while($i<=$end_index) {
            if (isset($coordinates_x[$i - $estimate]) && isset($coordinates_y[$i - $estimate])&&isset($coordinates_x[$i]) && isset($coordinates_y[$i])) {
                $Euclidean_distance = sqrt(pow(($coordinates_x[$i] - $coordinates_x[$i - $estimate]), 2) +
                    pow(($coordinates_y[$i] - $coordinates_y[$i - $estimate]), 2));
                $sum += $Euclidean_distance;
            }
            else break;
            $i += $estimate;
        }
        return $sum;
    }

	public static function evtrack_distance_and_velocity($file_data,$html_tag=[])
	{
		$sum_distance=0;
		$interval=0;
		$in_html_tag=0;
		if (empty($html_tag)){
			var_dump($file_data[count($file_data)-1][1]);
			var_dump($file_data[1][1]);
			$interval=$file_data[count($file_data)-1][1]-$file_data[1][1];
			for ( $i = 1; $i < count( $file_data ); $i ++ ) {
				if ( $file_data[$i][4] == 'mousemove' ) {
					if ( isset( $previous_x ) && isset( $previous_y ) ) {
						$euclidean_distance = sqrt( pow( $file_data[$i][2] - $previous_x, 2 ) + pow( $file_data[$i][3] - $previous_y, 2 ) );
						$sum_distance += $euclidean_distance;
					}
					$previous_x = $file_data[$i][2];
					$previous_y = $file_data[$i][3];
				} else if ( $file_data[$i][4] == 'mousewheel' || $file_data[$i][4] == 'scroll' ) {
					unset( $previous_x );
					unset( $previous_y );
				}
			}
		}else{
			for ( $i = 1; $i < count( $file_data )-1; $i ++ ){
				//var_dump($file_data[$i]);
				$in_html_tag=0;
				foreach ($html_tag as $tag_name){
					if ( stripos( $file_data[ $i ][5], $tag_name ) ) {
						$in_html_tag = 1;
						break;
					}
				}
				if($in_html_tag==1){
					if (isset($previous_interval)){
						$interval+=$file_data[$i][1]-$previous_interval;
					}
					$previous_interval=$file_data[$i][1];
					if($file_data[$i][4] == 'mousemove' ) {
						if(isset( $previous_x ) && isset( $previous_y ) ) {
							$euclidean_distance = sqrt( pow( $file_data[$i][2] - $previous_x, 2 ) + pow( $file_data[$i][3] - $previous_y, 2 ) );
							$sum_distance += $euclidean_distance;
						}
						$previous_x = $file_data[$i][2];
						$previous_y = $file_data[$i][3];
					}else if($file_data[$i][4] == 'mousewheel' || $file_data[$i][4] == 'scroll' ){
						unset( $previous_x );
						unset( $previous_y );
					}
				}else if($file_data[$i][5]!='/'){
					unset($previous_interval);
				}
			}
		}
		if ($interval!=0)
			$velocity=$sum_distance/$interval;
		else
			$velocity=0;
		return ['distance'=>$sum_distance,'velocity'=>$velocity];
	}

    public static function velocity($coordinates_x,$coordinates_y,$start_index,$end_index,$estimate=1)
    {
        $interval=$end_index-$start_index;
        if ($interval>0){
            $distance = self::distance($coordinates_x, $coordinates_y, $start_index, $end_index,$estimate);
            $interval=$interval*1/24;
            return $distance/$interval;
        }else return false;
    }

    public static function dwelling($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        /*foreach ($coordinates_x as $key=>$coord_x){
            if ($key % 6 != 0){
                unset($coordinates_x[$key]);
                unset($coordinates_y[$key]);
            }
        }*/

        $dwell_indexes=[];
        $start_dwell=$end_dwell=0;
        $j=$start_index;
        $i=$start_index;
        while($i<=$end_index){
            while($j<$end_index){
                /*if (self::velocity($coordinates_x, $coordinates_y, $j, $j + 1) <=2){
                    if ($j == $i){
                        $start_dwell = $j;
                    }
                    $end_dwell = $j;
                    $j++;
                }else{
	                $j++;
                    break;
                }*/
                $j++;
            }

            if($start_dwell<$end_dwell){
                $dwell_indexes[$start_dwell]=$end_dwell;
            }
            $start_dwell=$end_dwell=0;
	        var_dump(['i'=>$i,'j'=>$j]);
            $i=$j;
	        //
	        //$i++;
	        //

        }
    return $dwell_indexes;
    }

    public static function dwelling_number($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        return count(self::dwelling($coordinates_x,$coordinates_y,$start_index,$end_index));
    }

    public static function dwelling_total_time($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        $sum=0;
        $dwelling_arr=self::dwelling($coordinates_x,$coordinates_y,$start_index,$end_index);
        foreach ($dwelling_arr as $start_index=>$end_index){
            $sum+=($end_index-$start_index)*(1/24);
        }
    }

	public static function evtrack_dwelling($file_data,$html_tag=[]){
		$dwelling_index=[];
		for ($i=1;$i<=count($file_data)-2;$i++){
			$in_html_tag=0;
			foreach ($html_tag as $tag_name) {
				if ( stripos( $file_data[ $i ][5], $tag_name ) ) {
					$in_html_tag = 1;
					break;
				}
			}
			if (empty($html_tag) || (!empty($html_tag)&&$in_html_tag==1)) {
				$time_gap = $file_data[ $i + 1 ][1] - $file_data[ $i ][1];
				if ( $time_gap >= 100 ) {
					 $dwelling_index[]=[ 'start' => $i, 'end' => $i + 1,'time-gap'=>$time_gap ];
				}
			}
		}
		$dwelling_time=0;
		foreach ($dwelling_index as $single_dwell)
			$dwelling_time+=$single_dwell['time-gap'];
		return['dwelling_count'=>count($dwelling_index),'dwelling_time'=>$dwelling_time];
    }

    public static function Mouse_wheel_scroll($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        $i=$start_index+1;
        $sum=0;
        /*foreach ($coordinates_x as $key=>$coord_x){
            if ($key % 24 != 0){
                unset($coordinates_x[$key]);
                unset($coordinates_y[$key]);
            }
        }*/
        $tmp_scroll_index=[];
        while($i<$end_index){
            $delta_y=abs($coordinates_y[$i]-$coordinates_y[$i-1]);
            $delta_x=abs($coordinates_x[$i]-$coordinates_x[$i-1]);
            if($delta_x<5&&$delta_y>100){
                $tmp_scroll_index[]=[$i-1=>['coordinates_x'=>$coordinates_x[$i-1],'coordinates_y'=>$coordinates_y[$i-1]],$i=>['coordinates_x'=>$coordinates_x[$i],'coordinates_y'=>$coordinates_y[$i]]];
            }
            $i++;
        }

        return $tmp_scroll_index;
    }

    public static function max_y_coordinate($coordinate_y)
    {
        return max($coordinate_y);
    }

    public static function scroll_number($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        $scroll_arr=self::Mouse_wheel_scroll($coordinates_x,$coordinates_y,$start_index,$end_index);
        if (isset($scroll_arr)&&count($scroll_arr)>0){
            return count($scroll_arr);
        }
        else return 0;
    }

    public static function scroll_velocity($coordinates_x,$coordinates_y,$start_index,$end_index)
    {
        $scroll_arr=self::Mouse_wheel_scroll($coordinates_x,$coordinates_y,$start_index,$end_index);
        if (isset($scroll_arr)&&count($scroll_arr)>0){
            $sum=0;
            foreach($scroll_arr as $row){
                foreach($row as $key=>$scroll){
                    $sum+=$row[$key+1]['coordinates_y']-$row[$key]['coordinates_y'];
                    break;
                }
            }
            return($sum/(count($scroll_arr)*(1/24)));
        }
        else return 0;
    }

	public static function evtrack_scroll($file_Data) {
		$first_scroll_record=1;
		$first_non_scroll_record=0;
		$scroll_data=[];
		//var_dump($file_Data);
		for ($i=1;$i<=count($file_Data)-1;$i++){
			if ($file_Data[$i][4]=='mousewheel' || $file_Data[$i][4]=='scroll'){
				if ($first_scroll_record==1){
					if ($file_Data[$i-1][4]!='load'){
						$scroll_data[$i]['start_Y']=$file_Data[$i-1][3];
					}
					$scroll_data[$i]['start_T']=$file_Data[$i][1];
					$first_scroll_record=0;
					$first_non_scroll_record=$i;
				}
			}else{
				if ($first_non_scroll_record!=0){
					if ($file_Data[$i][4]!='unload'){
						$scroll_data[$first_non_scroll_record]['end_Y']=$file_Data[$i][3];
					}
					$scroll_data[$first_non_scroll_record]['end_T']=$file_Data[$i-1][1];
					$first_non_scroll_record=0;
				}
				$first_scroll_record=1;
			}
		}
		//var_dump($scroll_data);
		$scroll_time=0;
		$scroll_velocity=0;
		foreach ($scroll_data as $single_scroll){
			if (isset($single_scroll['start_Y'])&&isset($single_scroll['end_Y'])){
				$scroll_time+=$single_scroll['end_T']-$single_scroll['start_T'];
				if ($scroll_time!=0)
					$scroll_velocity+=($single_scroll['end_Y']-$single_scroll['start_Y'])/$scroll_time;
			}
		}
		return['scroll_number'=>count($scroll_data),'scroll_time'=>$scroll_time,'scroll_velocity'=>$scroll_velocity];

    }

	public static function click_number($clicks_arr,$start_index,$end_index){
		$i=$start_index;
		$click_cnt=0;
		while ($i<$end_index){
			$j=$i;
			if ($clicks_arr[$j]==1) {
				$click_cnt++;
				while ($j < $end_index &&$clicks_arr[$j]==1) {
					$j++;
				}
			}
			$i=$j+1;
		}
		return $click_cnt;
    }

	public static function evtrack_click_number($file_data,$html_tag=[]) {
		$click_number=0;
		$in_html_tag = 0;
		for ($i=1;$i<=count($file_data)-1;$i++){
			$in_html_tag=0;
			foreach ($html_tag as $tag_name) {
				if ( stripos( $file_data[$i][5], $tag_name ) ) {
					$in_html_tag = 1;
					break;
				}
			}
			if (empty($html_tag) || (!empty($html_tag)&&$in_html_tag==1)) {
				if ($file_data[$i][4]=='click')
					$click_number++;
			}
		}
		return $click_number;
    }

	public static function element_hover_total_time($element_ranges_index_arr){
		$total_time=0;
		if (!empty($element_ranges_index_arr)){
			foreach ( $element_ranges_index_arr as $start_index=>$end_index){
				$total_time+=($end_index-$start_index+1)*(1/24);
			}
		}
		return $total_time;
    }


	public static function element_hover_total_number($element_ranges_index_arr){
		$total_number=0;
		if (!empty($element_ranges_index_arr)){
			$total_number=count($element_ranges_index_arr);
		}
		return $total_number;
    }

	public static function evtrack_element_hover($file_data,$html_tag=[]) {
		$first_in_tag_record=1;
		$first_non_in_tag_record=0;
		$first_in_scroll_record=0;
		$in_tag_data=[];
		for ($i=1;$i<=count($file_data)-1;$i++){
			$in_html_tag=0;
			foreach ($html_tag as $tag_name) {
				if ( stripos( $file_data[$i][5], $tag_name ) ) {
					$in_html_tag = 1;
					break;
				}
			}
			if (!empty($html_tag)&&$in_html_tag==1){
				if ($first_in_tag_record==1){
					$in_tag_data[$i]['start_T']=$file_data[$i][1];
					$first_in_tag_record=0;
					$first_non_in_tag_record=$i;
				}
				$first_in_scroll_record=0;
			}else{
				if($first_non_in_tag_record!=0){
					if ($file_data[$i][4]=='scroll'){
						if ($first_in_scroll_record==0){
							$tmp_T=$file_data[$i-1][1];
							$first_in_scroll_record=1;
						}
					}else{
						if ($first_in_scroll_record==1){
							if (isset($tmp_T))
								$in_tag_data[$first_non_in_tag_record]['end_T']=$tmp_T;
							$first_in_tag_record=0;
						}else
							$in_tag_data[$first_non_in_tag_record]['end_T']=$file_data[$i-1][1];
						$first_non_in_tag_record=0;
						$first_in_tag_record=1;

					}
				}
			}
		}

		$hover_time=0;
		foreach ($in_tag_data as $key=>$hover_section){
			if(!isset($in_tag_data[$key]['end_T']))
				$in_tag_data[$key]['end_T']=$file_data[count($file_data)-1][1];
			$hover_time+=$in_tag_data[$key]['end_T']-$in_tag_data[$key]['start_T'];
		}
		return(['hover_time'=>$hover_time,'hover_count'=>count($in_tag_data)]);
	}

    //

}