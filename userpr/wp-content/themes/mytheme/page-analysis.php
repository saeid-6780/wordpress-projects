<?php

get_header();
get_template_part('views/partials/header');
while ( have_posts() ) {
    the_post();
    the_content();
}
$smtdb=new general_smtdb();
//analysis_dbmodification::test_1_row();
//var_dump($smtdb->record_id_select());
//analysis_procedure::single_hovered_percentage();
//analysis_procedure::single_click_count();
//analysis_procedure::single_total_distance();
//analysis_procedure::single_mean_velocity();
//analysis_procedure::single_dwelling_number();
//analysis_procedure::single_scroll_y_percentage();
//analysis_procedure::single_scroll_Number();
//analysis_procedure::single_scroll_velocity();
//analysis_cleansing::fill_missed_data();
analysis_procedure::compute_in_db_tag_features();
//$smtdb->set_cached_page_type('product',2);
//analysis_procedure::evtrack_analysis_procedure();
//analysis_procedure::haminjoori();
//analysis_procedure::evtrack_page_tag_list();

//dwelling feature test
/*
 $smtdb=new general_smtdb();
$kkmal=$smtdb->test_1_record_select()[0];
var_dump($kkmal);
$x=explode(',',$kkmal['coords_x']);
$y=explode(',',$kkmal['coords_y']);
$r=analysis_features::dwelling($x,$y,0,50);*/
//end dwelling feature test

get_footer();

?>