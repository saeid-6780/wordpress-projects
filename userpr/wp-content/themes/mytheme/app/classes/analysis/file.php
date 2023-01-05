<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/4/2018
 * Time: 6:16 PM
 */
class analysis_file
{
    public static function csv_maker($data,$file_name,$column_name)
    {
        $my_excel_filename = $file_name.".csv";
        $fp = fopen($my_excel_filename, 'wb');
        //$my_excel_content=[[1,2,3],[4,5,6]];
        fputcsv($fp,['']);
        foreach ($data as $key=>$tag_percentages) {
            fputcsv($fp, []);
            fputcsv($fp, [$key]);
            fputcsv($fp, $column_name);
            foreach ($tag_percentages as $tag_percentage) {
                fputcsv($fp, $tag_percentage);
            }
        }

        //file_put_contents($my_excel_filename,$my_excel_content);
        //fclose($fp);
    }

	public static function evtrack_csv_maker($data,$file_name,$column_name) {
		$my_excel_filename = $file_name.".csv";
		$fp = fopen($my_excel_filename, 'wb');
		fputcsv($fp, $column_name);
		foreach ($data as $record_key=>$record){
			foreach ($record as $feature_key=>$feature_val){
				if ($feature_val==0){
					$data[$record_key][$feature_key]='?';
				}
			}
		}
		foreach ($data as $record) {
			fputcsv($fp, $record);
		}
	}

	public static function multi_column_scv_make($data,$file_name,$column_name) {

    }

    public static function csv_reader($file_name,$delimeter=',')
    {
        $row = 1;
        $kkmaal=[];
        if (($handle = fopen($file_name.".csv", "r")) !== FALSE){
            while (($data = fgetcsv($handle, 8096, $delimeter)) !== FALSE) {

                $kkmaal[]=$data;
                $row++;

            }
            fclose($handle);
        }
        return $kkmaal;
    }

	public static function read_evtrack_xml_file_values($file_name,$values_name) {
		libxml_use_internal_errors(true);
		$file_name=get_home_path().DIRECTORY_SEPARATOR.'evtrack-master'.DIRECTORY_SEPARATOR.'archived logs_98-1-23'.DIRECTORY_SEPARATOR.$file_name;
		$xml=simplexml_load_file($file_name) or die("Error: Cannot create object");
		$values=[];
		//var_dump($xml);

		foreach ($values_name as $value_name){
			$values[$value_name]=$xml->$value_name;
		}
		return $values;
    }
}