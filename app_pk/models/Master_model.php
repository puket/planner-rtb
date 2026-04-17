<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Master_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		//load database
		//$this->load->database();
		//$this->shop_db = $this->load->database('puketstore_db', TRUE);
		
		$this->ecomobi_url ='https://go.ecotrackings.com/'; 
		$this->ecomobi_token ='tuCiMtVxRlTtAqPeThNRN'; 	
	}

	public function predata()
	{
		//load database
		$this->shop_db = $this->load->database('puketstore_db', TRUE);

		$data = array();

		//Top Menu
		$query = $this->shop_db->select('slug,txt_menu,name_th')
		->from('pk_categories')
		->where('active','1')
		->where('is_menu', '1')
		->where('is_first', '1')
		->where('is_rotibit_menu', 1)
		->get();
		$data['header']['prodcatemunu_list'] = $data['inner']['prodcatemunu_list'] = $query->result();
		$query->free_result();

		$query=$this->shop_db->select('name_th')
		->from('pk_categories')
		->where('active','1')
		->where('is_main','1')
		->order_by('date_upd','desc')
		->limit(10)
		->get();
		$data['inner']['categories_list'] = $query->result();
		$query->free_result();

		$query=$this->shop_db->select('*')
		->from('pk_tags')
		->where('active','1')->where('volume > 0')
		->get();
		$data['inner']['prodtags_list'] = $query->result();
		$query->free_result();

		return $data;
	}

	public function tag_cloud($count,$min,$max){
		$font_min_size = 13;
		$font_max_size = 32;
		$size = $font_max_size*($count-$min)/($max-$min);
		$size = intval($size) >= $font_min_size ? intval($size) : $font_min_size;

		return intval($size);
	}

    public function get_hotdownload_keywords($limit=20)
    {
		//load database
		$this->load->database();

		$this->db->select('text_key,count_time');
		$this->db->from('download_tags_keywords');
		$this->db->where("text_key <> '' AND status='yes' ");
		$this->db->order_by('count_time','DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		$hot_keywords = $query->result();
		$query->free_result();
		
		$max_count = 0;
		$min_count = 0;
		$keywords = array();
		foreach($hot_keywords as $index => $item){
				if($index==0) $max_count = $item->count_time;
				if($index==count($hot_keywords)-1) $min_count = $item->count_time;
				$keywords[] = (array)$item;
		}
		$block = array();
		$block['max_count'] = $max_count;
		$block['min_count'] = $min_count;
		$block['keywords'] = $keywords;
		$block['random_key'] = array_rand($keywords,$limit);

		return $block;
	}

	public function get_downloadgroup($_query=array(),$limit=0)
	{
		//load database
		$this->load->database();

		$this->db->select('id,name,pages,images');
		$this->db->from('download_group');
		$this->db->where($_query);
		//$this->db->order_by('updatedate','DESC')
		if($limit) {
			$this->db->limit($limit);
		}
		$datalist = $this->db->get()->result();
//echo $this->db->last_query();exit;
		return $datalist;
	}

	public function get_topdownload($_query=array(), $limit=3)
	{
		
		//load database
		$this->load->database();

		$lastmonth = mktime (0,0,0,date("m")-1,date("d"),  date("Y"));
		$last_month = date("Y",$lastmonth)."-".date("m",$lastmonth);

		$sql  = "SELECT b.id,b.name,b.pages,b.detail,b.images,b.size,b.images_thumb, b.view,b.hitdown,b.os,b.updatedate,b.adddate,SUM(a.view) as dh_view ";
		$sql .= "FROM download_his a INNER JOIN  download b ON a.downid = b.id  WHERE b.status='yes' AND a.adddate LIKE '$last_month%' AND b.id  <> '0' ";
		$sql .= "GROUP BY a.downid ORDER BY dh_view DESC ";
		if($limit){
			$sql .= "LIMIT $limit";
		}
		
		$datalist = $this->db->query($sql)->result();

		return $datalist;
	}

	public function get_populardownload($_query=array(), $limit=5)
	{
		//load database
		$this->load->database();

		$this->db->select('id,name,size,images,detail,pages,adddate,updatedate');
		$this->db->from('download');
		$this->db->where($_query);
		//$this->db->order_by('updatedate','DESC')
		if($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by("hitdown", "desc");
		$datalist = $this->db->get()->result();
//echo $this->db->last_query();exit;

		return $datalist;
	}

	public function get_lastweblink($_query=array(), $limit=5)
	{
		//load database
		$this->load->database();

		$this->db->select('id,name,adddate,url');
		$this->db->from('webdir');
		$this->db->where($_query);
		if($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by('adddate','DESC');
		$datalist = $this->db->get()->result();
//echo $this->db->last_query();exit;

		return $datalist;
	}
	//https://go.ecotrackings.com/
	public function set_shorturls($long_url, $siteaff='')
	{
		//load database
        $this->shop_db = $this->load->database('store_db', TRUE);

		$this->load->model('Tools_model');

		for ($i=0; $i <= 100 ; $i++) 
		{ 
			$shortcode = $this->Tools_model->gen_shortcode();
			$this->shop_db->select('short_code')
			->from('pk_shorturls')
			->where('short_code', $shortcode);
			$query = $this->shop_db->get();
			$num_rows = $query->num_rows();
			if(!$num_rows)
			{
				$i=100;
				if($siteaff=='bonus'){
					$_data['affiliate'] = 'lazada';
					$_data['short_url'] = $short_url = 'link/'.$shortcode;
				}else if($siteaff=='promo'){	
					$_data['affiliate'] = 'promo';
					//$long_url = "https://c.lazada.co.th/t/c.bg20qz?url=".urlencode($long_url);
					$_data['short_url'] = $short_url = 'promo/'.$shortcode;
					// https://c.lazada.co.th/t/c.bg4xRq?url=https%3A%2F%2Fwww.lazada.co.th%2Fproducts%2Fisuzu-dmax-2020-2025-i722984481-s1384870140.html&sub_aff_id=RTB
				}else{
					$pos = strpos($long_url, 'lazada');
					if ($pos !== false) {	
						if($siteaff=='') { $siteaff='lazada'; }				
					}
					//echo urlencode($long_url).'<br>';
					//echo $siteaff;exit;
					$_data = array();
					if($siteaff!='' && $siteaff=='lazada'){
						$long_url = "https://c.lazada.co.th/t/c.bg20qz?url=".urlencode($long_url);
		    			$_data['affiliate'] = 'lazada';
					}else if($siteaff!='' && $siteaff=='shopee'){
						// https://go.ecotrackings.com/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fshopee.co.th%2Ftimex_officialshop
						$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url='.urlencode($long_url).'&sub1=RTB';
		    			$_data['affiliate'] = 'shopee';
					}else{
						$_data['affiliate'] = 'accesstrade';
					}
					$_data['short_url'] = $short_url = 'link/'.$shortcode;
				}
	    		$_data['long_url'] = ($long_url);
	    		
	    		$_data['short_code'] = $shortcode;
	    		$_data['date_upd'] = date("Y-m-d H:i:s");
				$this->shop_db->insert('pk_shorturls', $_data);
				$insert_id = $this->shop_db->insert_id();							
			}
		}
		return $short_url;
	}
}