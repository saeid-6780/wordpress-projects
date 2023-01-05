<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 7/3/2018
 * Time: 11:28 AM
 */
class analysis_procedure
{

	public static $evtrack_files_index;
    public static function single_hovered_percentage()
    {
        $cat_id_list=[];
        $content_less_products=[65,69,126,130,146,191];
        $categorys=get_terms([
            'taxonomy' => 'product_category',
            'parent ' => 0,
            'orderby'=>'id',
            'order'=>'ASC',
            'hide_empty' => false
        ]);

        foreach ($categorys as $category) {
            $cat_id_list[$category->slug]='(';
            $args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => $category->taxonomy,
                        'field' => 'slug',
                        'terms' => $category->slug,
                    ),
                ),
            );
            $query = new WP_Query($args);
            $the_query = new WP_Query($args);

// The Loop
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    if (!in_array(get_the_ID(),$content_less_products))
                        $cat_id_list[$category->slug].=get_the_ID().',';
                }
                $cat_id_list[$category->slug]=substr($cat_id_list[$category->slug],0,-1);
                $cat_id_list[$category->slug].=')';
                /* Restore original Post Data */
                wp_reset_postdata();
            } else {
                // no posts found
            }
        }

        $important_tag_percentage=[];
        $smtdb=new general_smtdb();
        foreach($cat_id_list as $cat_slug=>$single_cat_products){
            $rated_page_records = $smtdb->get_rated_product_records($single_cat_products);
            $rated_records_arr = [];
            foreach($rated_page_records as $rated_page_record){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
                if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['hovered'])) {
                    $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['hovered'] .= ',' . $rated_page_record['hovered'];
                } else {
                    $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['hovered'] = $rated_page_record['hovered'];
                }
            }

            $important_tag_percentage['product_slider_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#product-slider');
            $important_tag_percentage['rating_modal_warning_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#rating-modal-warning');
            $important_tag_percentage['product_rater_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#product-rater');
            $important_tag_percentage['product_introduction_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#product-introduction');
            $important_tag_percentage['product_top_section']=self::find_tag_hover_percentage($rated_records_arr,'HTML>BODY>DIV.userpr-content>DIV.content>DIV.single-wl3>DIV.container>DIV.single-grids');
            //$basket_quantity_form_percentage=self::find_tag_hover_percentage($rated_records_arr,'form.basket-quantity-form');
            //$social_icon_percentage=self::find_tag_hover_percentage($rated_records_arr,'div.social-icon');
            $important_tag_percentage['comments_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#comment-');
            $important_tag_percentage['comment_form_percentage']=self::find_tag_hover_percentage($rated_records_arr,'FORM#commentform');
            $important_tag_percentage['product_content_percentage']=self::find_tag_hover_percentage($rated_records_arr,'DIV#myTabContent')+self::find_tag_hover_percentage($rated_records_arr,'DIV#product-description')+self::find_tag_hover_percentage($rated_records_arr,'DIV#home')+self::find_tag_hover_percentage($rated_records_arr,'DIV#product-content');
            $important_tag_percentage['product_attributes_percentage']=self::find_tag_hover_percentage($rated_records_arr,'SECTION#product-attributes');


            $column_name=['hover_percent','rate'];
            analysis_file::csv_maker($important_tag_percentage,$cat_slug,$column_name);

            //var_dump($important_tag_percentage['product_content_percentage']);
        }
    }

    public static function find_tag_hover_percentage($rated_records_arr,$tag)
    {
        $rate_hoverPercentage=[];
        foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $all_tag_num=substr_count($product['hovered'],',')+1;
                $tag_num=substr_count($product['hovered'],$tag);
                $rate_hoverPercentage[]=[$tag.'_hover_percent'=>($tag_num/$all_tag_num)*100,'rate'=>$product['rate']];
            }
        }
        return $rate_hoverPercentage;
    }

    public static function single_session_time()
    {
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();

        foreach($rated_page_records as $rated_page_record){
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['sess_time'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['sess_time'] += $rated_page_record['sess_time'];
            }else{
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['sess_time'] = $rated_page_record['sess_time'];
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
        }

        $rate_sessTime_arr=[];
        foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        analysis_file::csv_maker([$rate_sessTime_arr],'page_veiw_time',['page_veiw_time','rate']);
        //var_dump($rate_sessTime_arr);
    }

    public static function single_click_count()
    {
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();

	    //var_dump(count($rated_page_records));

        foreach($rated_page_records as $rated_page_record){
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['clicks'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['clicks'] .=','. $rated_page_record['clicks'];
            }else{
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['clicks'] = $rated_page_record['clicks'];
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
        }

        $rate_sessTime_arr=[];

	    //var_dump(count($rated_records_arr));
        foreach ($rated_records_arr as $ckey=>$client){
        	var_dump(count($client));
            foreach ($client as $pkey=>$product){
                $click_cnt=0;
                $clicks=explode(',',$product['clicks']);
                $i=1;
                while ($i<count($clicks)){
                        $j=$i;
                    if ($clicks[$j]==1) {
                        $click_cnt++;
                        while ($j < count($clicks)&&$clicks[$j]==1) {
                            $j++;
                        }
                    }
                    $i=$j+1;
                }
                $rate_sessTime_arr[]=['clicks_num'=>$click_cnt,'rate'=>$product['rate'],'c'=>$ckey,'p'=>$pkey];
	            $function_rate_sessTime_arr[]=['clicks_num'=>analysis_features::click_number($clicks,0,count($clicks)),'rate'=>$product['rate'],'c'=>$ckey,'p'=>$pkey];
            }
        }
        //var_dump($rate_sessTime_arr);
	    var_dump($function_rate_sessTime_arr);
        //analysis_file::csv_maker([$rate_sessTime_arr],'total_click_cnt',['total_click_cnt','rate']);
        //var_dump($rate_sessTime_arr);
    }

    public static function single_total_distance()
{
    $rated_records_arr=[];
    $smtdb=new general_smtdb();
    $rated_page_records=$smtdb->get_rated_product_records();

    foreach($rated_page_records as $rated_page_record){
        $coords_x=explode(',',$rated_page_record['coords_x']);
        $coords_y=explode(',',$rated_page_record['coords_y']);
        if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'])){
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'] += analysis_features::distance($coords_x,$coords_y,0,count($coords_x)-1);
        }else{
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'] = analysis_features::distance($coords_x,$coords_y,0,count($coords_x)-1);
        }
        $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
    }
    var_dump($rated_records_arr);
    $rate_sessTime_arr=[];
    foreach ($rated_records_arr as $ckey=>$client){
        foreach ($client as $pkey=>$product){
            $rate_sessTime_arr[]=$product;
        }
    }
    analysis_file::csv_maker([$rate_sessTime_arr],'total_distance',['total_distance','rate']);

}

    public static function single_mean_velocity()
    {
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        $cnt=1;

        foreach($rated_page_records as $rated_page_record){
            $coords_x=explode(',',$rated_page_record['coords_x']);
            $coords_y=explode(',',$rated_page_record['coords_y']);
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'] += analysis_features::velocity($coords_x,$coords_y,0,count($coords_x)-1);
                $cnt++;
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance']=($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'])/$cnt;
            }else{
                $cnt=1;
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['total_distance'] = analysis_features::velocity($coords_x,$coords_y,0,count($coords_x)-1);
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
        }
        var_dump($rated_records_arr);
        $rate_sessTime_arr=[];
        foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        analysis_file::csv_maker([$rate_sessTime_arr],'mean_velocity',['mean_velocity','rate']);

    }

    public static function single_dwelling_number()
    {
        set_time_limit(50000);
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        var_dump($rated_page_records);
        //for($k=0;$k<=6;$k++){
            $coords_x=explode(',',$rated_page_records[6]['coords_x']);
            $coords_y=explode(',',$rated_page_records[6]['coords_y']);
            if (isset($rated_records_arr[$rated_page_records[6]['client_id']][$rated_page_records[6]['product_id']]['dwelling_number'])){
                $rated_records_arr[$rated_page_records[6]['client_id']][$rated_page_records[6]['product_id']]['dwelling_number'] += analysis_features::dwelling($coords_x,$coords_y,0,count($coords_x)-1);
            }else{
                $rated_records_arr[$rated_page_records[6]['client_id']][$rated_page_records[6]['product_id']]['dwelling_number'] = analysis_features::dwelling($coords_x,$coords_y,0,count($coords_x)-1);
            }
            $rated_records_arr[$rated_page_records[6]['client_id']][$rated_page_records[6]['product_id']]['rate'] = $rated_page_records[6]['rate'];
            var_dump([$rated_records_arr[$rated_page_records[6]['client_id']][$rated_page_records[6]['product_id']],$rated_page_records[6]['client_id'],$rated_page_records[6]['product_id']]);
        //}
        var_dump($rated_records_arr);
        $rate_sessTime_arr=[];
        /*foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        analysis_file::csv_maker([$rate_sessTime_arr],'dwelling_number',['dwelling_number','rate']);*/
        wp_die();
    }

    public static function single_scroll_y_percentage()
    {
        //set_time_limit(50000);
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        var_dump($rated_page_records);
        foreach($rated_page_records as $rated_page_record){
            $coords_x=explode(',',$rated_page_record['coords_x']);
            $coords_y=explode(',',$rated_page_record['coords_y']);
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['max_y'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['max_y'] = max(analysis_features::max_y_coordinate($coords_y)/$rated_page_record['vp_height'],$rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['max_y']);
            }else{
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['max_y'] = analysis_features::max_y_coordinate($coords_y)/$rated_page_record['vp_height'];
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
            //var_dump($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]);
        }
        //var_dump($rated_records_arr);
        $rate_sessTime_arr=[];
        foreach($rated_records_arr as $ckey=>$client){
            foreach($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        //var_dump($rate_sessTime_arr);
        analysis_file::csv_maker([$rate_sessTime_arr],'page_scroll_Percentage',['scroll_Percentage','rate']);
    }

    public static function single_scroll_Number()
    {
        //set_time_limit(50000);
        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        var_dump($rated_page_records);
        foreach($rated_page_records as $rated_page_record){
            $coords_x=explode(',',$rated_page_record['coords_x']);
            $coords_y=explode(',',$rated_page_record['coords_y']);
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_number'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_number'] += analysis_features::scroll_number($coords_x,$coords_y,0,count($coords_x)-1);
            }else{
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_number'] = analysis_features::scroll_number($coords_x,$coords_y,0,count($coords_x)-1);
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
            //var_dump($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]);
        }
        //var_dump($rated_records_arr);
        $rate_sessTime_arr=[];
        foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        var_dump($rate_sessTime_arr);
        analysis_file::csv_maker([$rate_sessTime_arr],'page_scroll_Number',['scroll_Number','rate']);
    }

    public static function single_scroll_velocity()
    {
        //set_time_limit(50000);
        $rated_records_arr=[];
        $cnt=0;
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        var_dump($rated_page_records);
        foreach($rated_page_records as $rated_page_record){
            $coords_x=explode(',',$rated_page_record['coords_x']);
            $coords_y=explode(',',$rated_page_record['coords_y']);
            if (isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_velocity'])){
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_velocity'] += analysis_features::scroll_number($coords_x,$coords_y,0,count($coords_x)-1);
                $cnt++;
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_velocity']=($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_velocity'])/$cnt;
            }else{
                $cnt=1;
                $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['scroll_velocity'] = analysis_features::scroll_number($coords_x,$coords_y,0,count($coords_x)-1);
            }
            $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]['rate'] = $rated_page_record['rate'];
            //var_dump($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']]);
        }
        var_dump($rated_records_arr);
        $rate_sessTime_arr=[];
        foreach ($rated_records_arr as $ckey=>$client){
            foreach ($client as $pkey=>$product){
                $rate_sessTime_arr[]=$product;
            }
        }
        //var_dump($rate_sessTime_arr);
        //analysis_file::csv_maker([$rate_sessTime_arr],'page_scroll_velocity',['scroll_velocity','rate']);
    }

	private static function get_in_element_coords_index($coords_x,$coords_y,$element_tag_name,$view_ranges){
		$tag_name_to_element_title=[
			'.container .logo'=>'logo section',
			'.header-right .site-guide.btn.btn-info:first'=>'guide button',
			'.header-right .site-guide.btn.btn-info:last'=>'register button',
			'zoomable_gallery_image'=>'big zoomable image',
			'thumbnail_gallery_image'=>'gallery thumbnail image',
			'product_rater'=>'product rating section',
			'#product-introduction'=>'product introduction',
			'#set-basket-quantity .entry.value-plus1'=>'increase basket quantity',
			'#set-basket-quantity .entry.value-minus1'=>'decrease basket quantity',
			'#add-to-basket a.my-cart-b.item_add'=>'add to basket button',
			'.icon'=>'facebook share button',
			'.icon1'=>'twitter share button',
			'.icon2'=>'google plus share button',
			'.icon3'=>'linkedin share button',
			'#home-tab'=>'analysis tab button',
			'#reviews-tab'=>'comments tab button',
			'#product-content'=>'product content',
			'#product-attributes'=>'product features',
			'#myTabContent1'=>'comments section',
			'#commentform'=>'comment form'
		];
		if (count($coords_x)!=count($coords_y))
			return false;

		//change $view_ranges array structure for good index setting
		$modified_view_ranges=[];
		foreach ($view_ranges as $view_range){
			$modified_view_ranges[$view_range['element_name']]=$view_range;
		}

		$in_elements_tag_index=[];
		$current_start_index=0;
		if (isset($modified_view_ranges[$element_tag_name])){
			foreach ($coords_x as $key=>$coord_x){
				$tag_range=$modified_view_ranges[$element_tag_name];
				if($coord_x>=$tag_range['start_x']&&$coord_x<=$tag_range['end_x']&&$coords_y[$key]>=$tag_range['start_y']&&$coords_y[$key]<=$tag_range['end_y']){
					if($current_start_index==0){
						$current_start_index=$key;
					}
					$in_elements_tag_index[$tag_name_to_element_title[$element_tag_name]][$current_start_index]=$key;
				}
				else $current_start_index=0;
			}
			//var_dump($in_elements_tag_index);
			return $in_elements_tag_index;
		}
		else return false;
    }

    public static function get_element_mouse_features($element_selector,$element_miningful_features)
    {
	    $tag_name_to_element_title=[
		    '.container .logo'=>'logo section',
		    '.header-right .site-guide.btn.btn-info:first'=>'guide button',
		    '.header-right .site-guide.btn.btn-info:last'=>'register button',
		    'zoomable_gallery_image'=>'big zoomable image',
		    'thumbnail_gallery_image'=>'gallery thumbnail image',
		    'product_rater'=>'product rating section',
		    '#product-introduction'=>'product introduction',
		    '#set-basket-quantity .entry.value-plus1'=>'increase basket quantity',
		    '#set-basket-quantity .entry.value-minus1'=>'decrease basket quantity',
		    '#add-to-basket a.my-cart-b.item_add'=>'add to basket button',
		    '.icon'=>'facebook share button',
		    '.icon1'=>'twitter share button',
		    '.icon2'=>'google plus share button',
		    '.icon3'=>'linkedin share button',
		    '#home-tab'=>'analysis tab button',
		    '#reviews-tab'=>'comments tab button',
		    '#product-content'=>'product content',
		    '#product-attributes'=>'product features',
		    '#myTabContent1'=>'comments section',
		    '#commentform'=>'comment form'
	    ];


        $rated_records_arr=[];
        $smtdb=new general_smtdb();
        $rated_page_records=$smtdb->get_rated_product_records();
        //var_dump($rated_page_records);
	    //var_dump($rated_page_records);
	    $mean_cnt=1;
	    $kkcnt=0;
        foreach($rated_page_records as $rated_page_record){
	        $coords_x  = explode( ',', $rated_page_record['coords_x'] );
	        $coords_y  = explode( ',', $rated_page_record['coords_y'] );
	        $clicks    = explode( ',', $rated_page_record['clicks'] );
	        $page_type = 2;

	        $view_ranges = $smtdb->select_element_range( $rated_page_record['scr_width'], $page_type, $rated_page_record['product_id'] );

	        $element_index_ranges=self::get_in_element_coords_index($coords_x, $coords_y, $element_selector,$view_ranges);
	        //var_dump($element_index_ranges);
	        if(empty($element_index_ranges)){
	        	if(!isset($rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']][$tag_name_to_element_title[$element_selector]]['record_id'])){
			        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[ $element_selector ] ]['empty_record_id'] = $rated_page_record['id'];
			        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[ $element_selector ] ]['empty_rate']      = $rated_page_record['rate'];
		        }
	        }else if ( count( $coords_x ) == count( $coords_y )){

	        //click features

		        if (in_array('click_num',$element_miningful_features)){
			        if (count($coords_y)==count($clicks)&&count($coords_x)==count( $clicks)){
				        //var_dump([$rated_page_record['id'],count($coords_x),count($coords_y),count($clicks)]);
				        //var_dump(array_slice($coords_x,700,150));
				        //var_dump(array_slice($coords_y,700,150));
				        //var_dump([$element_index_ranges,$rated_page_record]);
				        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
					        foreach ( $element_index_range as $start_index => $end_index ) {
						        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['click_number'] ) ) {
							        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['click_number'] += analysis_features::click_number( $clicks, $start_index, $end_index );

						        } else {
							        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['click_number'] = analysis_features::click_number( $clicks, $start_index, $end_index );

						        }
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
						        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
						            $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
						        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
							        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
						        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
						        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
						        /*foreach ($rated_records_arr as $ckey=>$client){
									foreach ($client as $pkey=>$product){
										$rate_sessTime_arr[]=$product;
									}
								}*/
						        //analysis_file::csv_maker([$rate_sessTime_arr],'page_veiw_time',['page_veiw_time','rate']);
					        }
				        }
			        }
		        }
	        //end click features

		        //hover time features
		        if (in_array('hover_time',$element_miningful_features)) {
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_time'] ) ) {
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_time'] += analysis_features::element_hover_total_time( $element_index_range );

				        } else {
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_time'] = analysis_features::element_hover_total_time( $element_index_range );

				        }
				        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
				        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
				        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
				        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
				        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
			        }
		        }
		        //end hover time features

		        //hover number features
		        if (in_array('hover_num',$element_miningful_features)) {
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_number'] ) ) {
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_number'] += analysis_features::element_hover_total_number( $element_index_range );

				        } else {
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['hover_number'] = analysis_features::element_hover_total_number( $element_index_range );

				        }
				        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
				        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
				        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
				        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
				        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
			        }
		        }
		        //end hover time features

		        //distance features
		        if (in_array('mouse_distance',$element_miningful_features)){
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        foreach ( $element_index_range as $start_index => $end_index ) {
					        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['distance'] ) ) {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['distance'] += analysis_features::distance( $coords_x, $coords_y, $start_index, $end_index );
					        } else {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['distance'] = analysis_features::distance( $coords_x, $coords_y, $start_index, $end_index );
					        }
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
					        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
					        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
				        }
			        }
		        }
		        //end distance features

		        //mean velocity features
		        if (in_array('mouse_velocity',$element_miningful_features)){
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        foreach ( $element_index_range as $start_index => $end_index ) {
					        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['mean_velocity'] ) ) {
						        $rated_records_arr[$rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['mean_velocity'] += analysis_features::velocity( $coords_x, $coords_y, $start_index, $end_index );
						        $mean_cnt ++;
						        $rated_records_arr[ $rated_page_record['client_id'] ][$rated_page_record['product_id'] ][ $element_name ]['mean_velocity'] = ( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['mean_velocity'] ) / $mean_cnt;
					        }else{
						        $mean_cnt=1;
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['mean_velocity'] = analysis_features::velocity( $coords_x, $coords_y, $start_index, $end_index );
					        }
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
					        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
					        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
				        }
			        }
		        }
		        //end mean velocity features

		        //start scroll number features
		        if (in_array('scroll_number',$element_miningful_features)){
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        foreach ( $element_index_range as $start_index => $end_index ) {
					        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_number'] ) ) {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_number'] += analysis_features::scroll_number( $coords_x, $coords_y, $start_index, $end_index );
					        } else {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_number'] = analysis_features::scroll_number( $coords_x, $coords_y, $start_index, $end_index );
					        }
					        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['rate']= $rated_page_record['rate'];
					        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
					        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
				        }
			        }
		        }
		        //end scroll number features

		        //start scroll velocity
		        if (in_array('scroll_velocity',$element_miningful_features)){
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        foreach ( $element_index_range as $start_index => $end_index ) {
					        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_velocity'] ) ) {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_velocity'] += analysis_features::scroll_number( $coords_x, $coords_y, $start_index, $end_index );
						        $mean_cnt ++;
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_velocity'] = ( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['scroll_velocity'] ) / $mean_cnt;
					        }else{
						        $mean_cnt=1;
						        $rated_records_arr[$rated_page_record['client_id']][$rated_page_record['product_id']][ $element_name]['scroll_velocity']=analysis_features::scroll_number($coords_x, $coords_y, $start_index, $end_index);
					        }
					        $rated_records_arr[ $rated_page_record['client_id']][$rated_page_record['product_id']][ $element_name ]['rate']= $rated_page_record['rate'];
					        if (!isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']= $rated_page_record['id'];
					        if (isset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']))
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['record_id']=$rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id'];
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_record_id']);
					        unset($rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $tag_name_to_element_title[$element_selector] ]['empty_rate']);
				        }
			        }
		        }
		        //end scroll velocity

		        /*if (in_array('dwelling_number',$element_miningful_features)) {
			        foreach ( $element_index_ranges as $element_name => $element_index_range ) {
				        foreach ( $element_index_range as $start_index => $end_index ) {
					        if ( isset( $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['dwelling_number'] ) ) {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['dwelling_number'] += analysis_features::dwelling( $coords_x, $coords_y, $start_index, $end_index );
					        } else {
						        $rated_records_arr[ $rated_page_record['client_id'] ][ $rated_page_record['product_id'] ][ $element_name ]['dwelling_number'] = analysis_features::dwelling( $coords_x, $coords_y, $start_index, $end_index );
					        }
				        }
			        }
		        }*/

        }

        }



/*
//amaadeh sazi baraye file dar halete vizhegihaye tak tak va hamrah ba fazaye khaali baraye eslahe dadeha
foreach($rated_records_arr as $cid=>$crecords){
	foreach($crecords as $pid=>$precords){
		foreach($precords as $tag_name=>$records){
			if (isset($records['rate'])){
				$cnt1++;
				$rate_val=$records['rate'];
				unset($records['rate']);
			}else
				$cnt2++;
			//$records['rate']=$rate_val;
			//$all_records[]=$records;
			foreach ($records as $key=>$record){
				$tmp_record[$key]=$record;
				array_push($tmp_record,$rate_val);
				array_push($tmp_record,'');
			}
			$all_records_with_space[]=$tmp_record;
			$tmp_record=[];
		}
	}
}*/
//self::make_all_features_data_file($rated_records_arr,$element_miningful_features);
return self::make_users_features_data_file($rated_records_arr,$element_miningful_features,$tag_name_to_element_title[$element_selector]);
	    //var_dump(count($rated_records_arr));
	  /*  foreach ($rated_records_arr as $k1){
	    	var_dump(count($k1));
	    	foreach ($k1 as $k2){
	    		foreach ($k2 as $k3){
	    		    $goh[]=$k3;
			    }
		    }

	    }*/
	    //var_dump(count($goh));
	    //self::single_click_count();
    }

	public static function compute_in_db_tag_features() {
		$all_features_name=[
			'hover_time','hover_num','click_num','mouse_distance','mouse_velocity','scroll_velocity','scroll_number','dwelling_number'
		];
		$cnt=0;
		$bad_record=0;
		/*
		$minus_basket_quantity_features=['click_num'];
		self::get_element_mouse_features('#set-basket-quantity .entry.value-minus1',$minus_basket_quantity_features);

		$plus_basket_quantity_features=['click_num'];
		self::get_element_mouse_features('#set-basket-quantity .entry.value-plus1',$plus_basket_quantity_features);

		$facebook_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon',$facebook_share_button_features);

		$twitter_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon1',$twitter_share_button_features);

		$google_plus_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon2',$google_plus_share_button_features);

		$linkedin_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon3',$linkedin_share_button_features);

		$analysis_tab_button_features=['click_num'];
		self::get_element_mouse_features('#home-tab',$analysis_tab_button_features);

		$comments_tab_button_features=['click_num'];
		self::get_element_mouse_features('#reviews-tab',$comments_tab_button_features);
		*/

		$zoomable_gallery_image_features=['hover_time','hover_num','mouse_distance','mouse_velocity'];
		$all_features_data=self::get_element_mouse_features('zoomable_gallery_image',$zoomable_gallery_image_features);

		$thumbnail_gallery_image_features=['hover_time','hover_num','click_num','mouse_distance','mouse_velocity'];
		$tmp_features_data=self::get_element_mouse_features('thumbnail_gallery_image',$thumbnail_gallery_image_features);
		foreach ($all_features_data as $cid=>$client_features_data){
			if (count($client_features_data)==count($tmp_features_data[$cid])){
				foreach ($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach ($single_record_data as $key=>$feature_value){
						if (!is_numeric($key)){
							if ($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}

							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_2']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
			}
		}

		$add_to_basket_features=['hover_time','hover_num','click_num'];
		$tmp_features_data=self::get_element_mouse_features('#add-to-basket a.my-cart-b.item_add',$add_to_basket_features);
		foreach ($all_features_data as $cid=>$client_features_data){
				foreach ($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach ($single_record_data as $key=>$feature_value){
						if (!is_numeric($key)){
							if ($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}

							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_3']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
		}


		$product_introduction_features=['hover_time','mouse_distance','mouse_velocity','scroll_velocity'];
		$tmp_features_data=self::get_element_mouse_features('#product-introduction',$product_introduction_features);
		foreach ($all_features_data as $cid=>$client_features_data){
				foreach ($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach ($single_record_data as $key=>$feature_value){
						if (!is_numeric($key)){
							if ($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}

							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_4']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
		}


		$product_content_features=['hover_time','hover_num','mouse_distance','mouse_velocity','scroll_velocity','scroll_number'];
		$tmp_features_data=self::get_element_mouse_features('#product-content',$product_content_features);
		foreach ($all_features_data as $cid=>$client_features_data){
				foreach ($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach ($single_record_data as $key=>$feature_value){
						if (!is_numeric($key)){
							if ($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}

							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_5']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
		}
//A_little_bit_nghty


		$comments_section_features=['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','scroll_velocity','scroll_number'];
		$tmp_features_data=self::get_element_mouse_features('#myTabContent1',$comments_section_features);
		foreach($all_features_data as $cid=>$client_features_data){
				foreach($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach($single_record_data as $key=>$feature_value){
						if(!is_numeric($key)){
							if($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}

							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_6']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
		}


		$product_rater_features=['hover_time','hover_num','click_num'];
		$tmp_features_data=self::get_element_mouse_features('product_rater',$product_rater_features);
		foreach ($all_features_data as $cid=>$client_features_data){
				foreach ($tmp_features_data[$cid] as $record_key=>$single_record_data){
					foreach ($single_record_data as $key=>$feature_value){
						if (!is_numeric($key)){
							if ($key=='rate'){
								if (isset($all_features_data[$cid][$record_key])) {
									if ( $feature_value != $all_features_data[ $cid ][ $record_key ]['rate'] ) {
										$cnt ++;
										$bad_record = 1;
										break;
									}
								}
								unset($tmp_features_data[$cid][$record_key][$key]);
							}
							if ($key=='record_id'){
								unset($tmp_features_data[$cid][$record_key][$key]);
							}
							if ($key!='rate'&&$key!='record_id')
								$tmp_features_data[$cid][$record_key][$key.'_7']=$feature_value;
							unset($tmp_features_data[$cid][$record_key][$key]);
						}
					}
					if ($bad_record!=0) {
						unset($all_features_data[ $cid ][ $record_key ]);
						$bad_record=0;
					}
					else if (isset($all_features_data[ $cid ][ $record_key ])) {
						$all_features_data[ $cid ][ $record_key ] = array_merge( $all_features_data[ $cid ][ $record_key ], $tmp_features_data[ $cid ][ $record_key ] );
					}
				}
		}

		foreach ( $all_features_data as $key=>$item ) {
			$cnt+=count($item);
			echo $key.': '.count($item).'<br>';
		}
		var_dump($all_features_data);
		$correlations=self::get_pearson_correlation($all_features_data);
		$for_Kmeans_data=self::run_KNN($all_features_data,$correlations);
		var_dump($for_Kmeans_data);
		//az dataye kami eslah shode va kam shode baraye kmeans estefadeh shod. mitavan b jash az hamun $all_features_data k havie hameye dade hast estefadeh kard.
		$mapping=self::run_Kmeans($for_Kmeans_data);
		self::run_KNN_for_clusters($for_Kmeans_data,$mapping);
		var_dump($mapping);
    }

	public static function run_KNN_for_clusters($data,$mapping) {
		$clustered_data=[];
		$clusters_correlation=[];
		foreach ($mapping as $client_id => $client_mapping){
			foreach ( $client_mapping as $record_key => $centroidID ) {
				if (isset($clustered_data[$centroidID]['records']))
					array_push($clustered_data[$centroidID]['records'],$data[$client_id][$record_key]);
				else
					$clustered_data[$centroidID]['records']=[];
			}
		}
		foreach ($clustered_data as $centroidID=>$data){
			$clusters_correlation[$centroidID]=self::get_pearson_correlation($data);
		}
		var_dump($clustered_data);
		var_dump($clusters_correlation);


		///////
		foreach ($clusters_correlation as $centroid_id=>$cluster_correlation){
			self::run_clusters_knn($clustered_data[$centroid_id]['records'],$cluster_correlation);
		}




    }

	public static function run_Kmeans($for_Kmeans_data) {
		$kkmalcnt=0;

		$k=5;

		$mapping=[];
		//var_dump($for_Kmeans_data);
		$centroid_vector_dimensions=self::find_centroid_vector_dimensions($for_Kmeans_data);
		//var_dump($centroid_vector_dimensions);
		$centroids=self::find_centroids($for_Kmeans_data,$centroid_vector_dimensions,$k);
		while (true) {
			$new_mapping = self::assignCentroids( $for_Kmeans_data, $centroids );
			//var_dump( $new_mapping );
			$changed = false;
			foreach ( $new_mapping as $client_id => $client_mapping ) {
				foreach ( $client_mapping as $record_key => $centroidID ) {
					if (!isset($mapping[$client_id][$record_key])||$centroidID!=$mapping[$client_id][$record_key]){
						$mapping = $new_mapping;
						$changed = true;
						break;
					}
				}
				if ($changed)
					break;
			}
			if ( ! $changed ) {
				return $mapping;
			}
			$centroids = self::update_centroids($mapping,$for_Kmeans_data,$k);
		}

		return $centroids;

    }

	public static function update_centroids($mapping,$for_Kmeans_data,$k) {
		$centroids = array();

		$flat_mapping=[];
		foreach ($mapping as $client_id => $client_mapping){
			foreach ( $client_mapping as $record_key => $centroidID ) {
				//var_dump($centroidID);
				array_push($flat_mapping,$centroidID);
			}
		}
		//var_dump($flat_mapping);
		$counts = array_count_values($flat_mapping);
		//var_dump($counts);
		foreach ( $mapping as $client_id => $client_mapping ) {
			foreach ( $client_mapping as $record_key => $centroidID ) {
				foreach($for_Kmeans_data[$client_id][$record_key] as $dim => $value) {
					if (!isset($centroids[$centroidID][$dim]))
						$centroids[$centroidID][$dim] = ($value/$counts[$centroidID]);
					else
						$centroids[$centroidID][$dim] += ($value/$counts[$centroidID]);
				}
			}
		}
		return $centroids;
    }

	public static function assignCentroids($for_Kmeans_data, $centroids) {
		$mapping=[];
		foreach ($for_Kmeans_data as $client_id=>$client_records) {
			foreach ( $client_records as $record_key => $record ) {
				$minDist     = null;
				$minCentroid = null;
				foreach ( $centroids as $centroidID => $centroid ) {
					$dist = 0;
					foreach ( $centroid as $dim => $value ) {
						if (isset($record[$dim]))
							$dist += abs( $value - $record[ $dim ] );
					}
					if ( is_null( $minDist ) || $dist < $minDist ) {
						$minDist     = $dist;
						$minCentroid = $centroidID;
					}
				}
				$mapping[$client_id][$record_key] = $minCentroid;
			}
		}
		return $mapping;
    }

	public static function find_centroids($for_Kmeans_data,$centroid_vector_dimensions,$k) {
		$dimmax = [];
		$dimmin = [];
		foreach ($for_Kmeans_data as $client_id=>$client_records){
			foreach ($client_records as $record_key=>$record){
				foreach ($centroid_vector_dimensions as $feature_name){
					if (isset($record[$feature_name])){
							if(!isset($dimmax[$feature_name]) || $record[$feature_name] > $dimmax[$feature_name]){
								$dimmax[$feature_name] = $record[$feature_name];
							}
							if(!isset($dimmin[$feature_name]) || $record[$feature_name] < $dimmin[$feature_name]) {
								$dimmin[$feature_name] = $record[$feature_name];
							}
					}
				}
			}
		}
		//var_dump($dimmin);
		//var_dump($dimmax);

		for($i = 0; $i < $k; $i++){
			foreach($centroid_vector_dimensions as $feature_name){
				$centroids[$i][$feature_name] = rand($dimmin[$feature_name],$dimmax[$feature_name]);
			}
		}
		return $centroids;
    }

	public static function find_centroid_vector_dimensions( $for_Kmeans_data ) {
		$centroid_vector_dimensions=[];
		$is_complete=0;
		foreach ($for_Kmeans_data as $client_record){
			if ($is_complete)
				break;
			foreach ( $client_record as $record ){
				if ($is_complete){
					break;
				}
				unset($record['record_id']);
				unset($record['rate']);
				foreach ( $record as $feature_name => $feature_value ) {
					if ( ! is_numeric( $feature_name ) ) {
						if ( ! in_array( $feature_name, $centroid_vector_dimensions ) ) {
							$centroid_vector_dimensions[] = $feature_name;
						}
					}
					if ( count( $centroid_vector_dimensions ) == 32 )
						$is_complete = 1;
				}
			}
		}
		return $centroid_vector_dimensions;
    }

	public static function run_KNN($all_features_data,$correlations) {
var_dump(count($correlations));
$correlation_threshold=0.1;
$distance_sum=0;
$distance_array=[];
$k=5;
$predicted_rates=[];

$mean_sum=0;
$mean_cnt=0;

$for_Kmeans_data=[];
foreach ($correlations as $client_id=>$correlation){
foreach ($correlation as $feature_name=>$correlation_value){
if ($correlation_value<$correlation_threshold){
unset($correlations[$client_id][$feature_name]);
}
}
if (count($correlations[$client_id])<2)
	unset($correlations[$client_id]);
}
var_dump(count($correlations));
var_dump($correlations);
//$client_id='03df05a430667c3b5148';
//$correlated_features=$correlations[$client_id];
foreach ($correlations as $client_id=>$correlated_features){
	foreach ($all_features_data[$client_id] as $record_key=>$record){
		$for_Kmeans_data[$client_id][$record_key]=$record;
		foreach ($all_features_data[$client_id] as $neighbor_key=>$neighbor_record){
			if ($record_key!=$neighbor_key){
				foreach ($correlated_features as $feature_name=>$correlation_value){
					if (isset($record[$feature_name])&&isset($neighbor_record[$feature_name])) {
						$distance_sum += pow(($record[$feature_name]-$neighbor_record[$feature_name]),2);
					}
				}
				$distance_array[$client_id][$record_key][$neighbor_key]=sqrt($distance_sum);
				$distance_sum=0;
			}
		}
		asort($distance_array[$client_id][$record_key]);
		if (count($distance_array[$client_id][$record_key])>$k){
			$distance_array[$client_id][$record_key]=array_slice($distance_array[$client_id][$record_key],0,$k,true);
		}
		//3 way for KNN prediction
		//maximum value
		foreach ($distance_array[$client_id][$record_key] as $neighbor_key=>$neighborhood_value){
			if (isset($tmp_predicted_Rate[$all_features_data[$client_id][$neighbor_key]['rate']])){
				$tmp_predicted_Rate[$all_features_data[$client_id][$neighbor_key]['rate']]++;
			}else{
				$tmp_predicted_Rate[$all_features_data[$client_id][$neighbor_key]['rate']]=1;
			}
		}

		$max_cnt=1;
		$max_value=0;
		$have_absolute_max=1;
		foreach ($tmp_predicted_Rate as $rate=>$cnt){
			if ($max_cnt<$cnt){
				$max_cnt=$cnt;
				$max_value=$rate;
				$have_absolute_max=1;
			}elseif ($max_cnt==$cnt){
				$have_absolute_max=0;
			}
		}
		if ($have_absolute_max==1&&$max_value!=0){
			$predicted_rates[$client_id][$record_key]['max']=$max_value;
		}else{
			foreach ($distance_array[$client_id][$record_key] as $neighbor_key=>$neighborhood_value){
				$mean_sum+=$all_features_data[$client_id][$neighbor_key]['rate'];
			}
			$predicted_rates[$client_id][$record_key]['max']=round($mean_sum/count($distance_array[$client_id][$record_key]));
		}

		$tmp_predicted_Rate=[];
		$mean_sum=0;
		//round
		$weighted_mean_cnt=0;
		foreach ($distance_array[$client_id][$record_key] as $neighbor_key=>$neighborhood_value){
			if($neighborhood_value==0){
				$neighborhood_value=0.00000001;
			}
			$weighted_mean_cnt+=1/pow($neighborhood_value,2);
			$mean_sum+=(1/pow($neighborhood_value,2))*$all_features_data[$client_id][$neighbor_key]['rate'];
		}

		/*
		//for general mean
		 $predicted_rates[$client_id][$record_key]['round']=round($mean_sum/count($distance_array[$client_id][$record_key]));
		$predicted_rates[$client_id][$record_key]['exact']=$mean_sum/count($distance_array[$client_id][$record_key]);
		$predicted_rates[$client_id][$record_key]['orginal_rate']=$all_features_data[$client_id][$record_key]['rate'];*/
		//for weighted_mean
		$predicted_rates[$client_id][$record_key]['round']=round($mean_sum/$weighted_mean_cnt);
		$predicted_rates[$client_id][$record_key]['exact']=$mean_sum/$weighted_mean_cnt;
		$predicted_rates[$client_id][$record_key]['orginal_rate']=$all_features_data[$client_id][$record_key]['rate'];

		$mean_sum=0;
	}

}
$kkmalsum=0;
$dorostsum_round=0;
$dorostsum_max=0;
$ghalatsum=0;
$MAE_round_sum=0;
$MAE_max_sum=0;
$MAE_org_sum=0;
var_dump($predicted_rates);
foreach ($predicted_rates as $predicted_rate){
	$kkmalsum+=count($predicted_rate);
	foreach ($predicted_rate as $kkmal=>$gohmal){
		if(intval($gohmal['orginal_rate'])==intval($gohmal['round'])){
			$dorostsum_round++;
		}
		if(intval($gohmal['orginal_rate'])==intval($gohmal['max'])){
			$dorostsum_max++;
		}
		if (intval($gohmal['orginal_rate'])!=intval($gohmal['round'])&&intval($gohmal['orginal_rate'])!=intval($gohmal['max'])){
			$ghalatsum++;
		}

		$MAE_max_sum+=abs(intval($gohmal['max']-intval($gohmal['orginal_rate'])));
		$MAE_round_sum+=abs(intval($gohmal['round']-intval($gohmal['orginal_rate'])));
		$MAE_org_sum+=abs(intval($gohmal['exact']-intval($gohmal['orginal_rate'])));

	}
}
var_dump($kkmalsum);
var_dump($dorostsum_round);
var_dump($dorostsum_max);
var_dump($ghalatsum);

var_dump($MAE_max_sum/$kkmalsum);
var_dump($MAE_round_sum/$kkmalsum);
var_dump($MAE_org_sum/$kkmalsum);

$max_correct_num=[];
$round_correct_num=[];
for ($i=0;$i<=4;$i++){
	$max_correct_num[$i]=0;
	$round_correct_num[$i]=0;
}
foreach ($predicted_rates as $client_id=>$predicted_rate){
	foreach ($predicted_rate as $record_key=>$record){
		$max_correct_num[abs($record['max']-$record['orginal_rate'])]++;
		$round_correct_num[abs($record['round']-$record['orginal_rate'])]++;
	}
}
var_dump($max_correct_num);
var_dump($round_correct_num);

return $for_Kmeans_data;
}

	public static function get_pearson_correlation($features_data){
		$tmp_features_list=[];
		$main_features_list=[];
		$client_record_rate=0;
		$client_record_id=0;

		$kkcnt=0;
		$soorat_sum=0;
		$makhraj1_sum=0;
		$makhraj2_sum=0;
		//$client_id='03df05a430667c3b5148';
		//$client_features=$features_data[$client_id];
		$correlations=[];
		//var_dump($client_features);
		foreach ($features_data as $client_id=>$client_features){
			if (count($client_features)>1){
				$kkcnt++;
				foreach ($client_features as $record_key=>$record){
					$client_record_rate=$record['rate'];
					$client_record_id=$record['record_id'];
					//unset($client_features[$record_key]['rate']);
					//unset($client_features[$record_key]['record_id']);
					//unset($record['rate']);
					unset($record['record_id']);
					foreach ($record as $feature_name=>$feature_value){
						if (!is_numeric($feature_name)){
							if (!isset($tmp_features_list[$feature_name])){
								$tmp_features_list[$feature_name]['cnt']=1;
								$tmp_features_list[$feature_name]['sum']=$feature_value;
							}else{
								$tmp_features_list[ $feature_name ]['cnt'] ++;
								$tmp_features_list[ $feature_name ]['sum'] += $feature_value;
							}
						}
					}
				}
				//var_dump($tmp_features_list);
				$rate_mean=$tmp_features_list['rate']['sum']/$tmp_features_list['rate']['cnt'];
				unset($tmp_features_list['rate']);
				foreach ($tmp_features_list as $feature_name=>$feature_stat){
					if($feature_stat['cnt']>1){
						$main_features_list[$feature_name]=$feature_stat['sum']/$feature_stat['cnt'];
					}
				}
				foreach ($main_features_list as $feature_name=>$feature_value_mean){
					foreach($client_features as $record_key=>$record){
						if (isset($record[$feature_name])){
							$soorat_sum+=($record[$feature_name]-$feature_value_mean)*($record['rate']-$rate_mean);
							$makhraj1_sum+=pow(($record[$feature_name]-$feature_value_mean),2);
							$makhraj2_sum+=pow(($record['rate']-$rate_mean),2);
							if ($soorat_sum==0 || $makhraj1_sum==0 || $makhraj2_sum==0){
								$correlations[$client_id][$feature_name]=0;
							}else {
								$correlation = (( $soorat_sum ) / (( sqrt( $makhraj1_sum ) ) * ( sqrt( $makhraj2_sum ) )));
								if ($correlation>1){
									echo 'meghdare jame soorat baraye cliente '.$client_id.' va vizhegie '.$feature_name.' dar recorde shomare '.$record_key.'barabar ast ba:'.($record[$feature_name]-$feature_value_mean)*($record['rate']-$rate_mean).'<br>';
									echo 'meghdare jame 1 makhraj baraye cliente '.$client_id.' va vizhegie '.$feature_name.' dar recorde shomare '.$record_key.'barabar ast ba:'.pow(($record[$feature_name]-$feature_value_mean),2).'<br>';
									echo 'meghdare jame 2 makhraj baraye cliente '.$client_id.' va vizhegie '.$feature_name.' dar recorde shomare '.$record_key.'barabar ast ba:'.pow(($record['rate']-$rate_mean),2).'<br>';
									echo 'sume soorat='.$soorat_sum.'<br>';
									echo 'sume makh1='.$soorat_sum.'<br>';
									echo 'sume makh2='.$soorat_sum.'<br><br>';
								}
							}
						}
					}
					if ($soorat_sum==0 || $makhraj1_sum==0 || $makhraj2_sum==0){
						$correlations[$client_id][$feature_name]=0;
					}else
						$correlations[$client_id][$feature_name]=(($soorat_sum)/((sqrt($makhraj1_sum))*(sqrt($makhraj2_sum))));
					$soorat_sum=0;
					$makhraj1_sum=0;
					$makhraj2_sum=0;
				}

				$tmp_features_list=[];
				$main_features_list=[];
			}
		}
		var_dump($correlations);
		return $correlations;
    }

	public static function make_all_features_data_file( $rated_records_arr,$element_miningful_features){

		$column_name=[];
		$cnt1=0;
		$cnt2=0;

		//for file save
		$all_records=[];
		$all_records_with_space=[];
		//var_dump($rated_records_arr['0b508c52b6b71308ebd3'][184]);
		$tmp_record=[];
//amaadeh sazi baraye file dar halete negah dashtane hameye recordha baraye traine dadeha dar weka
		var_dump($rated_records_arr);
		foreach($rated_records_arr as $cid=>$crecords){
			foreach($crecords as $pid=>$precords){
				foreach($precords as $tag_name=>$records){
					if (isset($records['rate'])){
						$cnt1++;
						$rate_val=$records['rate'];
						$record_id_val=$records['record_id'];
						unset($records['rate']);
						unset($records['record_id']);

						array_push($tmp_record,$record_id_val);
						foreach ($records as $key=>$record){
							$tmp_record[$key]=$record;
						}
						array_push($tmp_record,$rate_val);
						$column_name=array_keys($tmp_record);
					}else {
						$cnt2 ++;
						$rate_val=$records['empty_rate'];
						$record_id_val=$records['empty_record_id'];
						unset($records['empty_rate']);
						unset($records['empty_record_id']);
						array_push($tmp_record,$record_id_val);
						foreach ($element_miningful_features as $single_feature){
							array_push($tmp_record,'?');
						}
						array_push($tmp_record,$rate_val);
					}
					//$records['rate']=$rate_val;
					//$all_records[]=$records;

					$all_records_with_space[]=$tmp_record;
					$tmp_record=[];
				}
			}
		}
		$file_name=$tag_name;

		foreach ($column_name as $key=>$name){
			if (is_numeric($name))
				$column_name[$key]='';
		}
		var_dump($column_name);


//var_dump($all_records_with_space);


		usort($all_records_with_space,function ($a, $b) {
			if ($a[0] == $b[0]) {
				return 0;
			}
			return ($a[0] < $b[0]) ? -1 : 1;
		});

		var_dump($all_records_with_space);
		echo 'tedad: '.count($all_records_with_space).'<br>';

		echo 'hover shode: '.$cnt1.'<br>'.'hover nashode: '.$cnt2;
		//analysis_file::csv_maker([$all_records_with_space],$file_name,$column_name);
	}

	public static function make_users_features_data_file( $rated_records_arr,$element_miningful_features,$element_selector) {
		$column_name=[];
		$cnt1=0;
		$cnt2=0;

		//for file save
		$all_records=[];
		$all_records_with_space=[];
		//var_dump($rated_records_arr['0b508c52b6b71308ebd3'][184]);
		$tmp_record=[];
//amaadeh sazi baraye file dar halete negah dashtane hameye recordha baraye traine dadeha dar weka
		var_dump($rated_records_arr);
		foreach($rated_records_arr as $cid=>$crecords){
			foreach($crecords as $pid=>$precords){
				foreach($precords as $tag_name=>$records){
					if (isset($records['rate'])){
						$cnt1++;
						$rate_val=$records['rate'];
						$record_id_val=$records['record_id'];
						unset($records['rate']);
						unset($records['record_id']);

						//array_push($tmp_record,$record_id_val);
						$tmp_record['record_id']=$record_id_val;
						foreach ($records as $key=>$record){
							$tmp_record[$key]=$record;
						}
						//array_push($tmp_record,$rate_val);
						$tmp_record['rate']=$rate_val;
						$column_name=array_keys($tmp_record);
					}else {
						$cnt2 ++;
						$rate_val=$records['empty_rate'];
						$record_id_val=$records['empty_record_id'];
						unset($records['empty_rate']);
						unset($records['empty_record_id']);
						//array_push($tmp_record,$record_id_val);
						$tmp_record['record_id']=$record_id_val;
						foreach ($element_miningful_features as $single_feature){
							array_push($tmp_record,'?');
						}
						//array_push($tmp_record,$rate_val);
						$tmp_record['rate']=$rate_val;
					}
					//$records['rate']=$rate_val;
					//$all_records[]=$records;

					$all_records_with_space[$cid][]=$tmp_record;
					$tmp_record=[];
				}
			}
		}
		$file_name=$tag_name;

		foreach ($column_name as $key=>$name){
			if (is_numeric($name))
				$column_name[$key]='';
			else
				$column_name[$key]=$element_selector.' '.$name;
		}



foreach ($all_records_with_space as $key=>$feature_records){
	usort($feature_records,function($a,$b){
		if($a['record_id'] == $b['record_id']){
			return 0;
		}
		return ( $a['record_id'] < $b['record_id'] ) ? - 1 : 1;
	});
	$all_records_with_space[$key]=$feature_records;
}

var_dump($all_records_with_space);

		echo 'tedad: '.count($all_records_with_space).'<br>';

		echo 'hover shode: '.$cnt1.'<br>'.'hover nashode: '.$cnt2;

		//analysis_file::csv_maker([$all_records_with_space],$file_name,$column_name);
		return $all_records_with_space;
	}

	public static function run_clusters_knn($all_features_data,$correlations) {
		$correlation_threshold = 0.1;
		$distance_sum          = 0;
		$distance_array        = [ ];
		//$ks                     = [3,5,7,9,11,13,15,17,19];
		$k=5;
		$predicted_rates       = [ ];

		$mean_sum = 0;
		foreach ( $correlations as $client_id => $correlation ) {
			foreach ( $correlation as $feature_name => $correlation_value ) {
				if ( $correlation_value < $correlation_threshold ) {
					unset( $correlations[ $client_id ][ $feature_name ] );
				}
			}
			if ( count( $correlations[ $client_id ] ) < 2 ) {
				unset( $correlations[ $client_id ] );
			}
		}
		//var_dump(count($correlations));
		//var_dump($correlations);
//$client_id='03df05a430667c3b5148';
//$correlated_features=$correlations[$client_id];
		foreach ( $correlations as $client_id => $correlated_features ) {
			foreach ( $all_features_data as $record_key => $record ) {
				foreach ( $all_features_data as $neighbor_key => $neighbor_record ) {
					if ( $record_key != $neighbor_key ) {
						foreach ( $correlated_features as $feature_name => $correlation_value ) {
							if ( isset( $record[ $feature_name ] ) && isset( $neighbor_record[ $feature_name ] ) ) {
								$distance_sum += pow( ( $record[ $feature_name ] - $neighbor_record[ $feature_name ] ), 2 );
							}
						}
						$distance_array[ $record_key ][ $neighbor_key ] = sqrt( $distance_sum );
						$distance_sum                                   = 0;
					}
				}
				asort( $distance_array[ $record_key ] );
				if ( count( $distance_array[ $record_key ] ) > $k ) {
					$distance_array[ $record_key ] = array_slice( $distance_array[ $record_key ], 0, $k, true );
				}
				//3 way for KNN prediction
				//maximum value
				foreach ( $distance_array[ $record_key ] as $neighbor_key => $neighborhood_value ) {
					if ( isset( $tmp_predicted_Rate[ $all_features_data[ $neighbor_key ]['rate'] ] ) ) {
						$tmp_predicted_Rate[ $all_features_data[ $neighbor_key ]['rate'] ] ++;
					} else {
						$tmp_predicted_Rate[ $all_features_data[ $neighbor_key ]['rate'] ] = 1;
					}
				}

				$max_cnt           = 1;
				$max_value         = 0;
				$have_absolute_max = 1;
				foreach ( $tmp_predicted_Rate as $rate => $cnt ) {
					if ( $max_cnt < $cnt ) {
						$max_cnt           = $cnt;
						$max_value         = $rate;
						$have_absolute_max = 1;
					} elseif ( $max_cnt == $cnt ) {
						$have_absolute_max = 0;
					}
				}
				if ( $have_absolute_max == 1 && $max_value != 0 ) {
					$predicted_rates[ $record_key ]['max'] = $max_value;
				} else {
					foreach ( $distance_array[ $record_key ] as $neighbor_key => $neighborhood_value ) {
						$mean_sum += $all_features_data[ $neighbor_key ]['rate'];
					}
					$predicted_rates[ $record_key ]['max'] = round( $mean_sum / count( $distance_array[ $record_key ] ) );
				}

				$tmp_predicted_Rate = [ ];
				$mean_sum           = 0;
				//round
				$weighted_mean_cnt = 0;
				foreach ( $distance_array[ $record_key ] as $neighbor_key => $neighborhood_value ) {
					if ( $neighborhood_value == 0 ) {
						$neighborhood_value = 0.00000001;
					}
					$weighted_mean_cnt += 1 / pow( $neighborhood_value, 2 );
					$mean_sum += ( 1 / pow( $neighborhood_value, 2 ) ) * $all_features_data[ $neighbor_key ]['rate'];
				}

				/*
				//for general mean
				 $predicted_rates[$client_id][$record_key]['round']=round($mean_sum/count($distance_array[$client_id][$record_key]));
				$predicted_rates[$client_id][$record_key]['exact']=$mean_sum/count($distance_array[$client_id][$record_key]);
				$predicted_rates[$client_id][$record_key]['orginal_rate']=$all_features_data[$client_id][$record_key]['rate'];*/
				//for weighted_mean
				$predicted_rates[ $record_key ]['round']        = round( $mean_sum / $weighted_mean_cnt );
				$predicted_rates[ $record_key ]['exact']        = $mean_sum / $weighted_mean_cnt;
				$predicted_rates[ $record_key ]['orginal_rate'] = $all_features_data[ $record_key ]['rate'];

				$mean_sum = 0;
			}

		}
		$kkmalsum        = 0;
		$dorostsum_round = 0;
		$dorostsum_max   = 0;
		$ghalatsum       = 0;
		$MAE_round_sum   = 0;
		$MAE_max_sum     = 0;
		$MAE_org_sum     = 0;
		var_dump( $predicted_rates );
		$kkmalsum = count( $predicted_rates );
		$predict_number_accuracy=['0-1'=>0,'1-2'=>0,'2-3'=>0,'3-4'=>0,'4-5'=>0];
		$predict_number_accuracy_cnt=0;
		$default_cnt=0;
		foreach ($predicted_rates as $record){
			$t=abs(intval($record['orginal_rate'])-$record['exact']);
			switch ($t) {
				case ( $t <= 1 ):
					$predict_number_accuracy['0-1']++;
					$predict_number_accuracy_cnt++;
					break;
				case ( $t <= 2 ):
					$predict_number_accuracy['1-2']++;
					$predict_number_accuracy_cnt++;
					break;
				case ( $t <= 3 ):
					$predict_number_accuracy['2-3']++;
					$predict_number_accuracy_cnt++;
					break;
				case ( $t <= 4 ):
					$predict_number_accuracy['3-4']++;
					$predict_number_accuracy_cnt++;
					break;
				case ( $t <= 5 ):
					$predict_number_accuracy['4-5']++;
					$predict_number_accuracy_cnt++;
					break;
				default:
					$predict_number_accuracy['0-1']++;
					$default_cnt++;
			}
		}
		var_dump($predict_number_accuracy);
		var_dump(['cnt'=>$predict_number_accuracy_cnt,'df'=>$default_cnt]);
		foreach ( $predicted_rates as $kkmal => $gohmal ) {
			if ( intval( $gohmal['orginal_rate'] ) == intval( $gohmal['round'] ) ) {
				$dorostsum_round ++;
			}
			if ( intval( $gohmal['orginal_rate'] ) == intval( $gohmal['max'] ) ) {
				$dorostsum_max ++;
			}
			if ( intval( $gohmal['orginal_rate'] ) != intval( $gohmal['round'] ) && intval( $gohmal['orginal_rate'] ) != intval( $gohmal['max'] ) ) {
				$ghalatsum ++;
			}

			$MAE_max_sum += abs( intval( $gohmal['max'] - intval( $gohmal['orginal_rate'] ) ) );
			$MAE_round_sum += abs( intval( $gohmal['round'] - intval( $gohmal['orginal_rate'] ) ) );
			$MAE_org_sum += abs( intval( $gohmal['exact'] - intval( $gohmal['orginal_rate'] ) ) );

		}
		var_dump( $kkmalsum );
		var_dump( $dorostsum_round );
		var_dump( $dorostsum_max );
		var_dump( $ghalatsum );

		var_dump( $MAE_max_sum / $kkmalsum );
		var_dump( $MAE_round_sum / $kkmalsum );
		var_dump( $MAE_org_sum / $kkmalsum );

		$max_correct_num   = [ ];
		$round_correct_num = [ ];
		for ( $i = 0; $i <= 4; $i ++ ) {
			$max_correct_num[ $i ]   = 0;
			$round_correct_num[ $i ] = 0;
		}
		foreach ( $predicted_rates as $record_key => $record ) {
			$max_correct_num[ abs( $record['max'] - $record['orginal_rate'] ) ] ++;
			$round_correct_num[ abs( $record['round'] - $record['orginal_rate'] ) ] ++;
		}
		var_dump( $max_correct_num );
		var_dump( $round_correct_num );

	$distance_sum          = 0;
	$distance_array        = [ ];
	$predicted_rates       = [ ];
	$mean_sum = 0;
		//return $for_Kmeans_data;
	}

	//start file logs analysis
	public static function haminjoori(  ) {
		$rated_records_arr=[];
		self::set_evtrack_files_index();
		var_dump(self::$evtrack_files_index['5.124.39.63']);
		var_dump(self::$evtrack_files_index['5.124.39.61']);
		var_dump(self::$evtrack_files_index);

		$smtdb=new general_smtdb();
		$rated_page_records=$smtdb->get_rated_product_records_date_base();
		//var_dump($rated_page_records);

		//analysis_file::read_evtrack_xml_file_values('sdfas','sadfsda');
	}

	public static function traverse_evtrack_files() {
		$my_file='20190106202802.xml';
		$file_names=[];
		$dir = new DirectoryIterator(dirname(get_home_path().DIRECTORY_SEPARATOR.'evtrack-master'.DIRECTORY_SEPARATOR.'archived logs_98-1-23'.DIRECTORY_SEPARATOR.$my_file));
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				if (strpos($fileinfo->getFilename(),'.xml'))
					$file_names[]=($fileinfo->getFilename());
			}
		}
		//var_dump($file_names);
		return $file_names;
	}

	public static function set_evtrack_files_index(){
		$file_names=self::traverse_evtrack_files();
		//var_dump($file_names);

		self::$evtrack_files_index=[];
		foreach ($file_names as $file_name){
			$tmp=analysis_file::read_evtrack_xml_file_values($file_name,['ip','url']);
			self::$evtrack_files_index[trim($tmp['ip'])][]=['file_name'=>$file_name,'url'=>$tmp['url']];
		}
		$cnt=0;
		foreach (self::$evtrack_files_index as $kk){
			$cnt+=count($kk);
		}
	}

	public static function evtrack_feature_test(){
		var_dump(self::traverse_evtrack_files());
		$file_name='20190107100807';
		$file_name=get_home_path().DIRECTORY_SEPARATOR.'evtrack-master'.DIRECTORY_SEPARATOR.'archived logs_98-1-23'.DIRECTORY_SEPARATOR.$file_name;
		$file_data=analysis_file::csv_reader($file_name,' ');
		$features=analysis_features::evtrack_element_hover($file_data,["@id='select-item-header'"]);
		//var_dump($features);
	}

	public static function evtrack_product_sessions_files(){
		$smtdb=new general_smtdb();
		$cnt1=$cnt2=0;
		$rated_page_records=$smtdb->get_rated_product_records_date_base();
		self::set_evtrack_files_index();
		//var_dump(self::$evtrack_files_index['5.124.39.63']);
		//var_dump(self::$evtrack_files_index['5.124.39.61']);
		var_dump(self::$evtrack_files_index);
		$unique_page_veiws=[];
		foreach ($rated_page_records as $key=>$records){
			if ((!isset($unique_page_veiws[$records['ip'].'-'.$records['client_id']]))||(isset($unique_page_veiws[$records['ip'].'-'.$records['client_id']])&&!in_array($records['url'],$unique_page_veiws[$records['ip'].'-'.$records['client_id']])))
				$unique_page_veiws[$records['ip'].'-'.$records['client_id']][]=$records['url'].'*'.$records['rate'];
		}
		//var_dump($unique_page_veiws);

		$product_view_files=[];
		//var_dump(self::$evtrack_files_index);
		foreach ($unique_page_veiws as $ip_client=>$client){
			foreach ($client as $view_url) {
				//var_dump( $unique_page_veiw['url'] );
				$ip=explode('-',$ip_client)[0];
				if ( isset( self::$evtrack_files_index[ $ip ] ) ) {
//var_dump(self::$evtrack_files_index[$records['ip']]);
					foreach ( self::$evtrack_files_index[ $ip ] as $record ) {
						$kkmaal = (string) $record['url'];
						//var_dump( $kkmaal );
						if ( (string) $record['url'] == explode('*',(string)$view_url)[0] || stripos( (string) $record['url'], explode('*',(string)$view_url)[0] ) ) {
							//$cnt2 ++;
							$product_view_files[explode('-',$ip_client)[1]][$view_url][]=$record['file_name'];
								//var_dump( $record['file_name'] );
						}
					}
				}
			}
		}
		//var_dump($product_view_files);
		return $product_view_files;
	}

	public static function evtrack_page_tag_list(  ) {
		$tag_name=[];
		$product_view_files=self::evtrack_product_sessions_files();
		foreach ($product_view_files as $user_sess){
			foreach ($user_sess as $page_sess){
				foreach ($page_sess as $file_name){
					$file_name=get_home_path().DIRECTORY_SEPARATOR.'evtrack-master'.DIRECTORY_SEPARATOR.'archived logs_98-1-23'.DIRECTORY_SEPARATOR.$file_name;
					//var_dump($file_name);
					$file_data=analysis_file::csv_reader(explode('.',$file_name)[0]);

					foreach ($file_data as $record){
						//var_dump($record);
						$record=explode(' ',$record[0]);
						if (stripos($record[5],"@id='product-rater")) {
							if ( isset( $tag_name[ $record[5] ] ) ) {
								$tag_name[ $record[5] ] ++;
							} else {
								$tag_name[ $record[5] ] = 1;
							}
						}
					}
				}
			}
		}
		//var_dump($tag_name);
	}

	public static function evtrack_get_tag_features(){
		$tag_names=[
			'zoomable_gallery_image'=>["[@id='product-slider']","[@id='slider-id-"],
			'thumbnail_gallery_image'=>["[@id='product-slider']/ol"],
			'product_introduction'=>["[@id='product-price']","[@id='product-introduction']"],
			'product-introduction-show-more'=>["[@id='product-introduction-show-more']"],
			'add_to_basket'=>["[@id='add-to-basket']'"],
			'basket-view-button'=>["@id='basket-view-button'"],
			'product_description'=>["[@id='product-describtion-section']","[@id='product_description_button']","[@id='product_description']","[@id='ui-id-","[@id='frmAtcTabs_Review_Description']"],
			'product-attributes'=>["[@id='product-attributes_section']","[@id='product_attributes_button']","[@id='product_attributes']"],
			'comment-section'=>["[@id='reviews-main']","[@id='comment-section']","[@id='comment-section']/div[","[@id='product_reviews_button_","[@id='product_reviews_button']","[@id='comment-","[@id='like-","[@id='dislike-","[@id='reply-to-","[@id='respond]'","[@id='commentform']"],
			'comment_text'=>["[@id='comment-"],
			'comment_rates'=>["[@id='like-","[@id='dislike-"],
			'comment_reply'=>["[@id='reply-to-"],
			'comment_form'=>["[@id='respond]'","[@id='commentform']"],
			'modal_rate'=>["[@id='rating-modal-warning']","[@id='product-rater-modal']"],
			'page_rate'=>["[@id='product-rater']"],
		];

		$tag_features=[
			'zoomable_gallery_image'=>['hover_time','hover_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count'],
			'thumbnail_gallery_image'=>['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count'],
			'product_introduction'=>['hover_time','mouse_distance','mouse_velocity','dwelling_time','dwelling_count'],
			'product-introduction-show-more'=>['hover_time','hover_num','click_num'],
			'add_to_basket'=>['hover_time','hover_num','click_num','dwelling_time','dwelling_count'],
			'basket-view-button'=>['hover_time','hover_num','click_num'],
			'product_description'=>['hover_time','hover_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count','click_num'],
			'product-attributes'=>['hover_time','hover_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count','click_num'],
			'comment-section'=>['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count','click_num'],
			'comment_text'=>['hover_time','mouse_distance','mouse_velocity','dwelling_time','dwelling_count'],
			'comment_rates'=>['hover_time','hover_num','click_num'],
			'comment_reply'=>['hover_time','hover_num','click_num'],
			'comment_form'=>['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','dwelling_time'],
			'modal_rate'=>['hover_time','hover_num','click_num','mouse_distance','mouse_velocity'],
			'page_rate'=>['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','dwelling_time','dwelling_count'],
		];

		$file_dir=get_home_path().DIRECTORY_SEPARATOR.'evtrack-master'.DIRECTORY_SEPARATOR.'archived logs_98-1-23'.DIRECTORY_SEPARATOR;
		$features_data=[];

		$product_view_files=self::evtrack_product_sessions_files();
		//var_dump($product_view_files);
		foreach($product_view_files as $client_id=>$client_sessions){
			foreach ($client_sessions as $page_url=>$client_page_session){
				foreach ($client_page_session as $single_session){
					//var_dump($single_session);
					$file_name=explode('.',$single_session)[0];
					$file_data=analysis_file::csv_reader($file_dir.$file_name,' ');
					foreach ($tag_names as $tag_name=>$xml_tags){
						//var_dump($tag_name);
						foreach ($tag_features[$tag_name] as $feature_name){
							switch ($feature_name):
								case 'hover_time':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_element_hover($file_data,$xml_tags)['hover_time'];
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_element_hover($file_data,$xml_tags)['hover_time'];
									break;
								case 'hover_num':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_element_hover($file_data,$xml_tags)['hover_count'];
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_element_hover($file_data,$xml_tags)['hover_count'];
									break;
								case 'click_num':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_click_number($file_data,$xml_tags);
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_click_number($file_data,$xml_tags);
									break;
								case 'mouse_distance':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_distance_and_velocity($file_data,$xml_tags)['distance'];
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_distance_and_velocity($file_data,$xml_tags)['distance'];
									break;
								case 'mouse_velocity':
									$features_data[$client_id][$page_url][$tag_name][$feature_name][]=analysis_features::evtrack_distance_and_velocity($file_data,$xml_tags)['velocity'];
									break;
								case 'dwelling_time':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_dwelling($file_data,$xml_tags)['dwelling_time'];
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_dwelling($file_data,$xml_tags)['dwelling_time'];
									break;
								case 'dwelling_count':
									if (isset($features_data[$client_id][$page_url][$tag_name][$feature_name]))
										$features_data[$client_id][$page_url][$tag_name][$feature_name]+=analysis_features::evtrack_dwelling($file_data,$xml_tags)['dwelling_count'];
									else
										$features_data[$client_id][$page_url][$tag_name][$feature_name]=analysis_features::evtrack_dwelling($file_data,$xml_tags)['dwelling_count'];
									break;
							endswitch;
						}
						//var_dump($features_data[$client_id][$page_url][$tag_name]);

					}

					$scroll_features=analysis_features::evtrack_scroll($file_data);
					$scroll_number=$scroll_features['scroll_number'];
					$scroll_time=$scroll_features['scroll_time'];
					$scroll_velocity=$scroll_features['scroll_velocity'];

					if (isset($features_data[$client_id][$page_url]['total_page']['scroll_number']))
						$features_data[$client_id][$page_url]['total_page']['scroll_number']+=$scroll_number;
					else
						$features_data[$client_id][$page_url]['total_page']['scroll_number']=$scroll_number;

					if (isset($features_data[$client_id][$page_url]['total_page']['scroll_time']))
						$features_data[$client_id][$page_url]['total_page']['scroll_time']+=$scroll_time;
					else
						$features_data[$client_id][$page_url]['total_page']['scroll_time']=$scroll_time;

					$features_data[$client_id][$page_url]['total_page']['scroll_velocity'][]=$scroll_velocity;

				}

				$scroll_velocity_sum=0;
				$scroll_velocity_cnt=0;
				foreach ($features_data[$client_id][$page_url]['total_page']['scroll_velocity'] as $ss_velocity){
					$scroll_velocity_cnt++;
					$scroll_velocity_sum+=$ss_velocity;
				}
				$features_data[$client_id][$page_url]['total_page']['scroll_velocity']=$scroll_velocity_sum/$scroll_velocity_cnt;
				////
				$velocity_sum=0;
				$velocity_cnt=0;
				//var_dump($features_data[$client_id][$page_url]);
				foreach ($features_data[$client_id][$page_url] as $tag_name=>$user_page_session){
					//var_dump($user_page_session);
					if(isset($user_page_session['mouse_distance']) && $user_page_session['mouse_distance']==0){
						$features_data[$client_id][$page_url][$tag_name]['mouse_velocity']=0;
					}
					else if (isset($user_page_session['mouse_velocity'])) {
						//var_dump($user_page_session['mouse_velocity']);
						foreach ( $user_page_session['mouse_velocity'] as $velocity ) {
							$velocity_sum += $velocity;
							$velocity_cnt ++;
						}
						$features_data[ $client_id ][ $page_url ][ $tag_name ]['mouse_velocity'] = $velocity_sum / $velocity_cnt;
					}
				}

				$features_data[$client_id][$page_url]['rate']['rate']=explode('*',$page_url)[1];

			}
			//var_dump($features_data[$client_id][$page_url]);
		}
		var_dump($features_data);
//var_dump($features_data['08aa04983c1720bfd85c']['http://userpr.ir/product/lg-k10-2017-m250e-dual-sim-mobile-phone/*4']);
		return $features_data;
}

	public static function evtrack_analysis_procedure(  ) {
		$features_data=self::evtrack_get_tag_features();
		$features_data_2d=[];
		foreach($features_data as $client_id=>$client_sessions){
			foreach ( $client_sessions as $page_url => $client_page_session ) {
				foreach ( $client_page_session as $element_name => $element_features ) {
					foreach ($element_features as $feature_name=>$element_feature){
						$features_data_2d[$client_id.'*'.$page_url][$element_name.'-'.$feature_name]=$element_feature;
					}
				}
			}
		}
		if(self::countdim($features_data_2d)<=2)
			$features_data_3d['all_users']=$features_data_2d;
		else $features_data_3d=$features_data_2d;

		var_dump($features_data_3d);
		//comented alaan
		/*$correlation=self::pearson_correlation($features_data_3d);


		$correlated_features_data_3d=self::remove_none_correlated_features($correlation,$features_data_3d,0.2);
		var_dump($correlated_features_data_3d);


		/*$kkcnt=0;
		foreach ($correlated_features_data_3d as $predicted_rate)
			$kkcnt+=count($predicted_rate);
		var_dump($kkcnt);*/


		/*foreach ($correlated_features_data_3d as $file_name=>$feature_Data_3d){
			foreach ($feature_Data_3d as $features){
				$features_name=array_keys($features);
				break;
			}
			analysis_file::evtrack_csv_maker($feature_Data_3d,$file_name,$features_name);
		}



		$predicted_rates=self::KNN($correlated_features_data_3d,3);
		var_dump($predicted_rates);
		$mae_array=self::MAE($predicted_rates);
		$prediction_num_array=self::prediction_number($predicted_rates);

		$mae_sum_array=[];
		foreach ($mae_array as $client_mae){
			foreach ($client_mae as $method_name=>$mae_val){
				if (isset($mae_sum_array[$method_name]))
					$mae_sum_array[$method_name]+=$mae_val;
				else
					$mae_sum_array[$method_name]=$mae_val;
			}
		}
		foreach ($mae_sum_array as $method_name=>$single_mae_sum)
			$total_user_based_mae[$method_name]=$single_mae_sum/count($mae_array);
		var_dump($total_user_based_mae);

		$total_prediction_num=[];
		foreach ($prediction_num_array as $client_prediction_num){
			foreach ($client_prediction_num as $method_name=>$prediction_num_array){
				foreach ($prediction_num_array as $prediction_error=>$singe_prediction_num) {
					if ( isset( $total_prediction_num[$method_name][$prediction_error] ) ) {
						$total_prediction_num[$method_name][$prediction_error]+= $singe_prediction_num;
					} else {
						$total_prediction_num[$method_name][$prediction_error]= $singe_prediction_num;
					}
				}
			}
		}
		var_dump($total_prediction_num);*/
		//end commented alaan


		// cluster base
		$centroids=self::run_Kmeans($features_data_3d);
		var_dump($centroids);

		$clustered_data=[];
		foreach ($centroids as $client_id=>$client_centroids){
			foreach ($client_centroids as $url=>$centroid_key){
				$clustered_data[$centroid_key][$url]=$features_data_3d[$client_id][$url];
			}
		}
		var_dump($clustered_data);

		$correlation=self::pearson_correlation($clustered_data);
		var_dump($correlation);
		$correlated_features_data_3d=self::remove_none_correlated_features($correlation,$clustered_data,0.2);

		$predicted_rates=self::KNN($correlated_features_data_3d,3);
		var_dump($predicted_rates);

		//compute recall,precision,f1,accuracy
		$tp=$tn=$fp=$fn=0;
		foreach ($predicted_rates as $cluster=>$records){
			foreach ($records as $record){
				switch (true){
					case ($record['orginal_rate'] >=3 and $record['exact'] >=3):
						$tp++;
						break;
					case ($record['orginal_rate'] >=3 and $record['exact'] <3):
						$fn++;
						break;
					case ($record['orginal_rate'] <3 and $record['exact'] >=3):
						$fp++;
						break;
					case ($record['orginal_rate'] <3 and $record['exact'] <3):
						$tn++;
						break;
					default:
						echo 'nothing';
						break;
				}
			}
		}
		var_dump(['tp'=>$tp,'tn'=>$tn,'fp'=>$fp,'fn'=>$fn]);
		$recall=$tp/($tp+$fn);
		$precision=$tp/($tp+$fp);
		$f1=2*(($precision*$recall)/($precision+$recall));
		$accuracy=($tp+$tn)/($tp+$tn+$fp+$fn);
		var_dump(['$recall'=>$recall,'$precision'=>$precision,'$f1'=>$f1,'$accuracy'=>$accuracy]);
		//37932271 golestan
		//37932290
		//37932258 majed
		//end compute recall,precision,f1,accuracy

		/*$mae_array=self::MAE($predicted_rates);
		$prediction_num_array=self::prediction_number($predicted_rates);

		var_dump($mae_array);
		var_dump($prediction_num_array);

		$mae_sum_array=[];
		foreach ($mae_array as $client_mae){
			foreach ($client_mae as $method_name=>$mae_val){
				if (isset($mae_sum_array[$method_name]))
					$mae_sum_array[$method_name]+=$mae_val;
				else
					$mae_sum_array[$method_name]=$mae_val;
			}
		}
		foreach ($mae_sum_array as $method_name=>$single_mae_sum)
			$total_user_based_mae[$method_name]=$single_mae_sum/count($mae_array);
		var_dump($total_user_based_mae);

		$total_prediction_num=[];
		foreach ($prediction_num_array as $client_prediction_num){
			foreach ($client_prediction_num as $method_name=>$prediction_num_array){
				foreach ($prediction_num_array as $prediction_error=>$singe_prediction_num) {
					if ( isset( $total_prediction_num[$method_name][$prediction_error] ) ) {
						$total_prediction_num[$method_name][$prediction_error]+= $singe_prediction_num;
					} else {
						$total_prediction_num[$method_name][$prediction_error]= $singe_prediction_num;
					}
				}
			}
		}
		var_dump($total_prediction_num);*/
	}

	public static function pearson_correlation($features_data_3d) {

		//var_dump($features_data_3d);
		foreach ($features_data_3d as $client_records){
			foreach ($client_records as $record) {
				$features_name = array_keys( $record );
				break;
			}
			break;
		}

		$mean_data=[];
		foreach ($features_name as $feature_name){
			$feature_mean_sum=0;
			$feature_mean_cnt=0;
			foreach ($features_data_3d as $client_id=>$client_features){
				foreach ( $client_features as $feature_data ) {
					//unset($features_data_3d[$key]['rate-rate']);
					$feature_mean_sum += $feature_data[ $feature_name ];
					$feature_mean_cnt ++;
				}
				$mean_data[$client_id][$feature_name]=$feature_mean_sum/$feature_mean_cnt;
			}

		}

		foreach ( $features_name as $key=>$feature_name ) {
			if ($feature_name=='rate-rate')
				unset($features_name[$key]);
		}

		foreach ($features_name as $feature_name){
			foreach ($features_data_3d as $client_id=>$client_features){
				$s_sum=0;
				$m1_sum=0;
				$m2_sum=0;
				foreach ( $client_features as $feature_data ) {
					$s_sum +=($feature_data[$feature_name]-$mean_data[$client_id][$feature_name])*($feature_data['rate-rate']-$mean_data[$client_id]['rate-rate']);
					$m1_sum+=pow($feature_data[$feature_name]-$mean_data[$client_id][$feature_name],2);
					$m2_sum+=pow($feature_data['rate-rate']-$mean_data[$client_id]['rate-rate'],2);
				}
				if ($s_sum==0 || $m1_sum==0 || $m2_sum==0)
					$correlation[$client_id][$feature_name]=0;
				else
					$correlation[$client_id][$feature_name]=$s_sum/(sqrt($m1_sum)*sqrt($m2_sum));
			}
		}

//var_dump($correlation);
		return $correlation;

}

	public static function remove_none_correlated_features($correlation,$features_data_3d,$correlation_threshold){
		foreach ($correlation as $client_id=>$client_correlation){
			foreach ($client_correlation as $feature_name=>$correlation_value){
				if ($correlation_value<$correlation_threshold ){
					foreach ($features_data_3d[$client_id] as $url=>$client_records){
						unset($features_data_3d[$client_id][$url][$feature_name]);
					}
				}
			}
		}
		return $features_data_3d;
}

	public static function countdim($array) {
		if (is_array(reset($array)))
		{
			$return = self::countdim(reset($array)) + 1;
		}

		else
		{
			$return = 1;
		}

		return $return;
	}

	public static function KNN($features_data_3d,$k) {
		$distance_sum=0;

			foreach ( $features_data_3d as $client_id => $client_records ) {
				if (count($client_records)>1) {
					foreach ( $client_records as $url => $client_record ) {
						foreach ( $client_records as $neighbor_url => $neighbor_record ) {
							if ( $url != $neighbor_url ) {
								foreach ( $client_record as $feature_name => $feature_value ) {
									if ( isset( $client_record[ $feature_name ] ) && isset( $neighbor_record[ $feature_name ] ) ) {
										$distance_sum += pow( ( $client_record[ $feature_name ] - $neighbor_record[ $feature_name ] ), 2 );
									}
								}
								$distance_array[ $client_id ][ $url ][ $neighbor_url ] = sqrt( $distance_sum );
								$distance_sum                                          = 0;
							}
						}
						asort( $distance_array[ $client_id ][ $url ] );
						if ( count( $distance_array[ $client_id ][ $url ] ) > $k ) {
							$distance_array[ $client_id ][ $url ] = array_slice( $distance_array[ $client_id ][ $url ], 0, $k, true );
						}

						//3 way for KNN prediction

						//1_maximum value
						$tmp_predicted_Rate = [ ];
						$mean_sum           = 0;
						foreach ( $distance_array[ $client_id ][ $url ] as $neighbor_key => $neighborhood_value ) {
							if ( isset( $tmp_predicted_Rate[ $features_data_3d[ $client_id ][ $neighbor_key ]['rate-rate'] ] ) ) {
								$tmp_predicted_Rate[ $features_data_3d[ $client_id ][ $neighbor_key ]['rate-rate'] ] ++;
							} else {
								$tmp_predicted_Rate[ $features_data_3d[ $client_id ][ $neighbor_key ]['rate-rate'] ] = 1;
							}
						}
						$max_cnt           = 1;
						$max_value         = 0;
						$have_absolute_max = 1;
						foreach ( $tmp_predicted_Rate as $rate => $cnt ) {
							if ( $max_cnt < $cnt ) {
								$max_cnt           = $cnt;
								$max_value         = $rate;
								$have_absolute_max = 1;
							} elseif ( $max_cnt == $cnt ) {
								$have_absolute_max = 0;
							}
						}
						if ( $have_absolute_max == 1 && $max_value != 0 ) {
							$predicted_rates[ $client_id ][ $url ]['max'] = $max_value;
						} else {
							foreach ( $distance_array[ $client_id ][ $url ] as $neighbor_key => $neighborhood_value ) {
								$mean_sum += $features_data_3d[ $client_id ][ $neighbor_key ]['rate-rate'];
							}
							$predicted_rates[ $client_id ][ $url ]['max'] = round( $mean_sum / count( $distance_array[ $client_id ][ $url ] ) );
						}

						//2_round
						$mean_sum          = 0;
						$weighted_mean_cnt = 0;
						foreach ( $distance_array[ $client_id ][ $url ] as $neighbor_key => $neighborhood_value ) {
							/*if($neighborhood_value==0){
								$neighborhood_value=0.00000001;
							}*/
							//$weighted_mean_cnt+=1/pow($neighborhood_value,2);
							//$mean_sum+=(1/pow($neighborhood_value,2))*$features_data_3d[$client_id][$neighbor_key]['rate'];
							$mean_sum += $features_data_3d[ $client_id ][ $neighbor_key ]['rate-rate'];

						}
						//for general mean
						$predicted_rates[ $client_id ][ $url ]['round']        = round( $mean_sum / count( $distance_array[ $client_id ][ $url ] ) );
						$predicted_rates[ $client_id ][ $url ]['exact']        = $mean_sum / count( $distance_array[ $client_id ][ $url ] );
						$predicted_rates[ $client_id ][ $url ]['orginal_rate'] = $features_data_3d[ $client_id ][ $url ]['rate-rate'];
					}
				}
			}
			//var_dump($distance_array);
		//var_dump($predicted_rates);
		return $predicted_rates;
		
	}

	public static function MAE($predicted_rates) {
		$mae_array=[];
		$mae_sums=[];
		foreach ($predicted_rates as $client_id=>$client_predicted_rates){
			foreach ($client_predicted_rates as $client_predicted_rate){
				foreach ($client_predicted_rate as $method_name=>$single_predicted_rate){
					if ($method_name!='orginal_rate'){
						if (!isset($mae_sums[$client_id][$method_name]))
							$mae_sums[$client_id][$method_name]=abs($single_predicted_rate-$client_predicted_rate['orginal_rate']);
						else
							$mae_sums[$client_id][$method_name]+=abs($single_predicted_rate-$client_predicted_rate['orginal_rate']);
					}
				}
			}
			foreach ($mae_sums[$client_id] as $method_name=>$mae_sum){
				$mae_array[$client_id][$method_name]=$mae_sum/count($client_predicted_rates);
			}
		}
		//var_dump($mae_array);
		return $mae_array;
	}

	public static function prediction_number($predicted_rates) {
		$defference_array_num=[];
		foreach ($predicted_rates as $client_id=>$client_predicted_rates){
			foreach ($client_predicted_rates as $client_predicted_rate){
				foreach ($client_predicted_rate as $method_name=>$single_predicted_rate){
					if ($method_name!='orginal_rate' && $method_name!='exact'){
						$defference_tmp=abs($single_predicted_rate-$client_predicted_rate['orginal_rate']);
						if (!isset($defference_array_num[$client_id][$method_name][$defference_tmp]))
							$defference_array_num[$client_id][$method_name][$defference_tmp]=1;
						else
							$defference_array_num[$client_id][$method_name][$defference_tmp]++;
					}
				}
			}
		}
		//var_dump($defference_array_num);
		return $defference_array_num;
	}


/*
$kkmalsum=0;
$dorostsum_round=0;
$dorostsum_max=0;
$ghalatsum=0;
$MAE_round_sum=0;
$MAE_max_sum=0;
$MAE_org_sum=0;
var_dump($predicted_rates);
foreach ($predicted_rates as $predicted_rate){
	$kkmalsum+=count($predicted_rate);
	foreach ($predicted_rate as $kkmal=>$gohmal){
		if(intval($gohmal['orginal_rate'])==intval($gohmal['round'])){
			$dorostsum_round++;
		}
		if(intval($gohmal['orginal_rate'])==intval($gohmal['max'])){
			$dorostsum_max++;
		}
		if (intval($gohmal['orginal_rate'])!=intval($gohmal['round'])&&intval($gohmal['orginal_rate'])!=intval($gohmal['max'])){
			$ghalatsum++;
		}

		$MAE_max_sum+=abs(intval($gohmal['max']-intval($gohmal['orginal_rate'])));
		$MAE_round_sum+=abs(intval($gohmal['round']-intval($gohmal['orginal_rate'])));
		$MAE_org_sum+=abs(intval($gohmal['exact']-intval($gohmal['orginal_rate'])));

	}
}
var_dump($kkmalsum);
var_dump($dorostsum_round);
var_dump($dorostsum_max);
var_dump($ghalatsum);

var_dump($MAE_max_sum/$kkmalsum);
var_dump($MAE_round_sum/$kkmalsum);
var_dump($MAE_org_sum/$kkmalsum);

$max_correct_num=[];
$round_correct_num=[];
for ($i=0;$i<=4;$i++){
	$max_correct_num[$i]=0;
	$round_correct_num[$i]=0;
}
foreach ($predicted_rates as $client_id=>$predicted_rate){
	foreach ($predicted_rate as $record_key=>$record){
		$max_correct_num[abs($record['max']-$record['orginal_rate'])]++;
		$round_correct_num[abs($record['round']-$record['orginal_rate'])]++;
	}
}
var_dump($max_correct_num);
var_dump($round_correct_num);
}
	/*
		$minus_basket_quantity_features=['click_num'];
		self::get_element_mouse_features('#set-basket-quantity .entry.value-minus1',$minus_basket_quantity_features);

		$plus_basket_quantity_features=['click_num'];
		self::get_element_mouse_features('#set-basket-quantity .entry.value-plus1',$plus_basket_quantity_features);

		$facebook_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon',$facebook_share_button_features);

		$twitter_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon1',$twitter_share_button_features);

		$google_plus_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon2',$google_plus_share_button_features);

		$linkedin_share_button_features=['click_num'];
		self::get_element_mouse_features('.icon3',$linkedin_share_button_features);

		$analysis_tab_button_features=['click_num'];
		self::get_element_mouse_features('#home-tab',$analysis_tab_button_features);

		$comments_tab_button_features=['click_num'];
		self::get_element_mouse_features('#reviews-tab',$comments_tab_button_features);
		/*
$zoomable_gallery_image_features=['hover_time','hover_num','mouse_distance','mouse_velocity'];
$all_features_data=self::get_element_mouse_features('zoomable_gallery_image',$zoomable_gallery_image_features);

$thumbnail_gallery_image_features=['hover_time','hover_num','click_num','mouse_distance','mouse_velocity'];
$tmp_features_data=self::get_element_mouse_features('thumbnail_gallery_image',$thumbnail_gallery_image_features);

$add_to_basket_features=['hover_time','hover_num','click_num'];
$tmp_features_data=self::get_element_mouse_features('#add-to-basket a.my-cart-b.item_add',$add_to_basket_features);

$product_introduction_features=['hover_time','mouse_distance','mouse_velocity','scroll_velocity'];
$tmp_features_data=self::get_element_mouse_features('#product-introduction',$product_introduction_features);

$product_content_features=['hover_time','hover_num','mouse_distance','mouse_velocity','scroll_velocity','scroll_number'];
$tmp_features_data=self::get_element_mouse_features('#product-content',$product_content_features);

$comments_section_features=['hover_time','hover_num','click_num','mouse_distance','mouse_velocity','scroll_velocity','scroll_number'];
$tmp_features_data=self::get_element_mouse_features('#myTabContent1',$comments_section_features);


$product_rater_features=['hover_time','hover_num','click_num'];
$tmp_features_data=self::get_element_mouse_features('product_rater',$product_rater_features);

}*/
}


/*
 //query taghiire iphaye tecrarie client haye mokhtalef
 UPDATE smt2_records SET ip='5.124.39.61' WHERE client_id='23dc56db66e1580c9103' AND ip='5.124.39.62';
UPDATE smt2_records SET ip='5.124.39.63' WHERE client_id='68282a4cb3b5c2297ff4' AND ip='5.124.39.62';*/
