<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 4/15/2018
 * Time: 12:36 PM
 */
class general_smtdb
{
    public $smtdb;
    public $client_id;

    public function __construct()
    {

        $this->smtdb = new wpdb('root','','cakanehi_smt2','localhost');
        $this->client_id=get_client_id();
        $this->client_id=substr($this->client_id,0,20);
        //echo $this->smtdb->smt2_users;

    }

    public function search_comment_rate($comment_id)
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare("SELECT * FROM smt2_commentrates WHERE client_id =%d AND comment_id=%d",$this->client_id,$comment_id);
        $results=$this->smtdb->get_row($query,ARRAY_A );
        /*$wpdb->show_errors();
        $query=$wpdb->prepare("SELECT * FROM mtrc_attributes WHERE id =%d ",1);
        $results=$wpdb->get_row($query,ARRAY_A );*/
        return $results;
    }

    public function insert_comment_rate($comment_id,$url,$rate)
    {
        //echo $this->client_id;
        $this->smtdb->show_errors();
        /*$this->smtdb->insert(
            'smt2_commentrates',
            ['client_id' => $this->client_id,'comment_id' => $comment_id,'url'=>$url,'rate'=>$rate],
            ['%s','%d','%s','%d' ]
        );*/
        $this->smtdb->insert(
            'smt2_commentrates',
            ['client_id' => $this->client_id,'comment_id' => $comment_id,'url'=>$url,'rate'=>$rate],
            ['%s','%d','%s','%d' ]
        );
    }

    public function update_comment_rate($id,$rate)
    {
        $query=$this->smtdb->prepare('UPDATE smt2_commentrates 
	SET rate = %d,change_date=%s
	WHERE id = %d
	',
        $rate,date('Y-m-d H:i:s'), $id);
        $this->smtdb->query($query);
    }

    public function search_product_rate($product_id)
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare("SELECT * FROM smt2_productrates WHERE client_id =%s AND product_id=%d",$this->client_id,$product_id);
        $results=$this->smtdb->get_row($query,ARRAY_A );
        return $results;
    }

    public function insert_product_rate($product_id,$url,$rate)
    {
        $this->smtdb->show_errors();

        $this->smtdb->insert(
            'smt2_productrates',
            ['client_id' => $this->client_id,'product_id' => $product_id,'url'=>$url,'rate'=>$rate],
            ['%s','%d','%s','%d' ]
        );
    }

    public function update_product_rate($id,$rate)
    {
        $query=$this->smtdb->prepare('UPDATE smt2_productrates 
	SET rate = %d,change_date=%s
	WHERE id = %d
	',
            $rate,date('Y-m-d H:i:s'), $id);
        $this->smtdb->query($query);
    }

    public function search_basket($session_id)
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare("SELECT * FROM smt2_usersessions WHERE client_id =%s AND session_id=%s",$this->client_id,$session_id);
        $results=$this->smtdb->get_row($query,ARRAY_A );
        return $results;
    }
    public function insert_basket($session_id,$basket)
    {
        $this->smtdb->show_errors();

        $basket=serialize($basket);
        $this->smtdb->insert(
            'smt2_usersessions',
            ['client_id' => $this->client_id,'session_id' => $session_id,'basket'=>$basket],
            ['%s','%s','%s']
        );
    }
    public function update_basket($id,$basket)
    {
        $basket=serialize($basket);
        $query=$this->smtdb->prepare('UPDATE smt2_usersessions 
	SET basket = %s
	WHERE id = %d
	', $basket, $id);
        $this->smtdb->query($query);
    }
//
    public function search_email()
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare("SELECT * FROM smt2_usersessions WHERE client_id =%s AND email_set > %d",$this->client_id,0);
        $results=$this->smtdb->get_row($query,ARRAY_A );
        return $results;
    }

    public function set_email($email_entry_id)
    {
        $this->smtdb->show_errors();

        $this->smtdb->insert(
            'smt2_usersessions',
            ['client_id' => $this->client_id,'session_id' => '','basket'=>'','email_set'=>$email_entry_id],
            ['%s','%s','%s','%d']
        );
    }

    public function set_cached_page_type($checking_string,$page_type_value)
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare('UPDATE smt2_cache SET page_type = %d 
	WHERE url LIKE %s AND page_type IS NULL
	',
            $page_type_value,'%/'.$checking_string.'/%');
        return $this->smtdb->query($query);
    }

    public function get_rated_product_records($product_id_list='')
    {
        $this->smtdb->show_errors();
        if (empty($product_id_list)) {
            $query = $this->smtdb->prepare('SELECT c.url AS url,r.id AS id,r.client_id AS client_id,r.ip AS ip,pr.product_id AS product_id,r.vp_height AS vp_height,r.coords_x,r.scr_width,r.coords_y,r.clicks,r.hovered,r.clicked,r.sess_date AS sess_date,pr.change_date AS change_date,r.sess_time as sess_time,pr.rate AS rate FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url INNER JOIN smt2_records r ON c.id=r.cache_id AND pr.client_id=r.client_id WHERE r.sess_time>%f AND c.page_type=%d AND pr.client_id!=%s ORDER BY client_id,product_id DESC',
                4.00,2,'eb1fd4b630610ab9387c');
        }else{
            $query = $this->smtdb->prepare('SELECT r.id AS id,r.client_id AS client_id,pr.product_id AS product_id,r.vp_height AS vp_height,r.coords_x,r.coords_y,r.clicks,r.hovered,r.clicked,r.sess_date AS sess_date,pr.change_date AS change_date,r.sess_time as sess_time,pr.rate AS rate FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url INNER JOIN smt2_records r ON c.id=r.cache_id AND pr.client_id=r.client_id WHERE r.sess_time>%f AND c.page_type=%d AND pr.client_id!=%s AND pr.product_id IN ' . $product_id_list . ' ORDER BY client_id,product_id DESC',
                4.00, 2, 'eb1fd4b630610ab9387c'
            );
        }
        return $this->smtdb->get_results($query,ARRAY_A);
        //SELECT * FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url WHERE c.page_type=2 AND pr.client_id!='eb1fd4b630610ab9387c'
        //SELECT url,client_id,COUNT(id) AS kuft FROM ( SELECT r.id AS id,r.client_id AS client_id,pr.url AS url,r.sess_date AS sess_date,pr.change_date AS change_date,pr.rate AS rate FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url INNER JOIN smt2_records r ON c.id=r.cache_id AND pr.client_id=r.client_id WHERE c.page_type=2 AND pr.client_id!='eb1fd4b630610ab9387c') as kuft GROUP BY url,client_id ORDER BY client_id DESC
    }

	public function get_rated_product_records_date_base()
	{
		$this->smtdb->show_errors();
		if (empty($product_id_list)) {
			$query = $this->smtdb->prepare('SELECT c.url AS url,r.id AS id,r.client_id AS client_id,r.ip AS ip,c.url AS url,pr.product_id AS product_id,r.vp_height AS vp_height,r.coords_x,r.scr_width,r.coords_y,r.clicks,r.hovered,r.clicked,r.sess_date AS sess_date,pr.change_date AS change_date,r.sess_time as sess_time,pr.rate AS rate FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url INNER JOIN smt2_records r ON c.id=r.cache_id AND pr.client_id=r.client_id WHERE r.sess_time>%f AND c.page_type=%d AND pr.client_id!=%s AND change_date >= %s ORDER BY client_id,product_id DESC',
				4.00,2,'eb1fd4b630610ab9387c','2019-01-01 00:00:00');
		}
		return $this->smtdb->get_results($query,ARRAY_A);
		//SELECT * FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url WHERE c.page_type=2 AND pr.client_id!='eb1fd4b630610ab9387c'
		//SELECT url,client_id,COUNT(id) AS kuft FROM ( SELECT r.id AS id,r.client_id AS client_id,pr.url AS url,r.sess_date AS sess_date,pr.change_date AS change_date,pr.rate AS rate FROM smt2_productrates pr INNER JOIN smt2_cache c ON pr.url=c.url INNER JOIN smt2_records r ON c.id=r.cache_id AND pr.client_id=r.client_id WHERE c.page_type=2 AND pr.client_id!='eb1fd4b630610ab9387c') as kuft GROUP BY url,client_id ORDER BY client_id DESC
	}
    //WHERE startTime >= '2010-04-29' AND startTime < ('2010-04-29' + INTERVAL 1 DAY)

    //test query
    public function record_id_select()
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare('select id from smt2_records 
	WHERE sess_time>%f AND cache_id>%d
	',
            150.00,17);
        return $this->smtdb->get_results($query);
    }

public function test_1_record_select()
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare('select * from smt2_records 
	WHERE id IN (%d)
	',
            1690);
        //big interaction:184,215,215,227,288,199
        return $this->smtdb->get_results($query,ARRAY_A);
    }

    public function search_element_position_range($product_url,$screen_width,$page_type)
    {
        $this->smtdb->show_errors();
        $query=$this->smtdb->prepare('select * from smt2_elementsrange 
	WHERE url=%s AND screen_width=%f AND page_type=%d
	',
            $product_url,$screen_width,$page_type);
        //big interaction:184,215,215,227,288,199
        return $this->smtdb->get_results($query,ARRAY_A);
    }

    public function insert_element_position_range($product_id,$product_url,$screen_width,$page_type,$element_name,$startx,$endx,$starty,$endy)
    {
        $this->smtdb->show_errors();
        $this->smtdb->replace(
            'smt2_elementsrange',
            [
                'product_id' => $product_id,
                'screen_width' => $screen_width,
                'url' => $product_url,
                'page_type' => $page_type,
                'element_name' => $element_name,
                'start_x' => $startx,
                'end_x' => $endx,
                'start_y' => $starty,
                'end_y' => $endy,
            ],
            [
                '%d',
                '%d',
                '%s',
                '%d',
                '%s',
                '%f',
                '%f',
                '%f',
                '%f'
            ]
        );
        return $this->smtdb->insert_id;
    }

    public function select_element_range($scr_width,$page_type=2,$product_id=0,$url='')
    {
        $this->smtdb->show_errors();
        if ($product_id!=0&&$page_type==2)
            $query=$this->smtdb->prepare('select element_name,start_x,end_x,start_y,end_y from smt2_elementsrange 
	WHERE page_type=%d AND product_id=%d AND screen_width=%f 
	',
            $page_type,$product_id,$scr_width);
        else
            $query=$this->smtdb->prepare('select element_name,start_x,end_x,start_y,end_y from smt2_elementsrange WHERE page_type=%d AND url=%s AND screen_width=%f
	',$page_type,$url,$scr_width);
        return $this->smtdb->get_results($query,ARRAY_A);
    }

}