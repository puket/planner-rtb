<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

class Home extends CI_Controller {
	//
	var $is_hightlight = 0;
	var $cache_day=3;

	var $tmp_path = '';
	var $newtemp = 1;
	var $is_conversion = 0;

	var $sub_aff_id = '';
	var $sub_id1 = 'ROTI';
	var $laz_link = '';
	var $ecomobi_url =''; 
	var $ecomobi_token =''; 
	var $shopee_url =''; 
	var $shopee_key =''; 
	var $select_f='';
	var $shopee_chk = 32;
	var $is_aff = 'eco';
	var $redirect_link = '';
	var $go_shopee = 1;
	var $go_accesstrad= 0;

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
	{
		parent::__construct();

		//load database

		//load library session		
		$this->load->library('parser');
		$this->load->library('session');

		//load model
		$this->load->model('Master_model');
		$this->load->model('Tools_model');

		$this->laz_link = $this->config->item('laz_rootlink'); //"https://c.lazada.co.th/t/c.bg20qz";
		$this->data_header['laz_link'] = $this->data_inner['laz_link'] =  $this->data_footer['laz_link'] = $this->laz_link;

		$this->sub_aff_id = 'ROTI';
		$this->chk_site();
		/*https://go.ecotrackings.com */
		$this->redirect_link = 'https://goeco.asia/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fwww.lazada.co.th%2Fshop%2Fpuket-stores%2F&sub1='.$this->sub_id1;

		$this->ecomobi_url ='https://goeco.asia/'; 
		$this->ecomobi_token ='tuCiMtVxRlTtAqPeThNRN'; 	


		$this->tmp_path = 'shop_view/';

		$this->shopee_url = "https://shopee.co.th/universal-link/";
		$this->shopee_key = "utm_source=an_15022360000&utm_medium=affiliates&utm_campaign=-&utm_content=";
		
		$this->select_f = 'id,sku,product_name,description,affiliate,review_count,category_slug,
		is_missing,buyer,category,category_2,category_3,image_url,page_url,prod_tags,category_4,
		image_url_5,image_url_4,image_url_3,image_url_2,rating_value,category_en,brand,keywords,
		desc_highlight,category_url_1,price,discounted_price,discounted_percentage,
		app_tracking_link,tracking_link,date_add,date_upd,active,availability_instock,merchant,
		discount,rating_average,promo_link,category_url_4,category_url_3,category_url_2,brand_url,
		feature,currency';
	}
	private function chk_site(){
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'buysellthailand.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'buysellthailand.com')) )  ){
			//$this->sub_aff_id = 'BST';
			$this->sub_id1 = 'BST';
		} 
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'puketstores.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puketstores.com')) )  ){
			//$this->sub_aff_id = 'PKS';
			$this->sub_id1 = 'PUKS';
		}
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'thaiflashsale.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'thaiflashsale.com')) )  ){
			//$this->sub_aff_id = 'TFS';
			$this->sub_id1 = 'TFS';
		}
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'puket24.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puket24.com')) )  ){
			//$this->sub_aff_id = 'PK24';
			$this->sub_id1 = 'PK24';
		}
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'goodhouseideas.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'goodhouseideas.com')) )  ){
			//$this->sub_aff_id = 'GHD';
			$this->sub_id1 = 'GHD';
		}
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'akinho.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'akinho.com')) )  ){
			//$this->sub_aff_id = 'AKH';
			$this->sub_id1 = 'AKH';
		}
		if(isset($_SERVER) && ( (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'timeandfeeling.com')) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'timeandfeeling.com')) )  ){
			//$this->sub_aff_id = 'TMF';
			$this->sub_id1 = 'TMF';
		}
	}

	public function ake()
	{
		ini_set('display_errors', 1);  error_reporting(E_ALL);
		//echo "<pre>";print_r($_SERVER['HTTP_HOST']);

		$this->chk_site();
		echo $this->sub_id1; exit;

		$this->timeaf_db = $this->load->database('timeaf_db', TRUE);
		$this->timeaf_db->select('*')
		->from('tm_postmeta')
		->where('meta_key', '_product_url')
		->like('meta_value', 'https://www.rotibit.com')
		->limit(10);
		$query = $this->timeaf_db->get();
		$num_rows = $query->num_rows();
		if($num_rows) {
			$_res = $query->result();
		$query->free_result();
			foreach ($_res as $key => $val) {
				$meta_value = str_replace('www.rotibit.com','go.timeandfeeling.com',$val->meta_value);
				$this->timeaf_db->where(array('meta_id'=> $val->meta_id, 'meta_key'=>'_product_url'));
				$this->timeaf_db->update('tm_postmeta', array('meta_value'=>$meta_value));
				echo $meta_value.'<br>';
			}
		}

		//echo "<pre>";print_r($_res); exit;
		
		exit;

		echo base_url();
		echo '<p>&copy; 2018 GO
        <span style="color:#666;"> | Memory usage: '.$rmUsed = number_format(((memory_get_usage()/1024)/1024), 2, '.', ''); echo $rmUsed.' Mb.</span></p>
        <p> ' . date("Y-m-d H:i:s");
		exit;
	}

	public function gogo($url='') 
	{
		echo "<pre>"; exit;
	}

	public function links($shortcode='') 
	{
		ini_set('display_errors', 1);  error_reporting(E_ALL);
		$this->indexs($shortcode);
	}

	
	public function indexs($shortcode='') 
	{
		if (isset($_SERVER) && $_SERVER['SERVER_NAME']=='gogo.rotibit.com' && isset($_GET['url'])){
			if(isset($_GET['token']) && $_GET['token']=='tuCiMtVxRlTtAqPeThNRN'){
				$redirect_link = $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url='.$_GET['url'].'&sub1=PKS';
			} else if(isset($_GET['token']) && $_GET['token']=='an_15022360000'){
				//https://shopee.co.th/universal-link/shop/68420779?utm_source=an_15022360000&utm_medium=affiliates&utm_campaign=-&utm_content=ssp--PKS--
				$redirect_link = $this->shopee_url.''.str_replace('https://shopee.co.th/','',$_GET['url']).'?'.$this->shopee_key.'ssp--PKS--';
				//echo $redirect_link;exit;
			}
			header( "Location: $redirect_link" ); exit;
			//echo $_SERVER['SERVER_NAME'].' '.$_GET['url'];exit;
		}else{
			//load output cache
			//$this->output->cache(3*(24*60)); /* (3 day) */
			if($shortcode==''){ header( "Location: https://puketstores.com"); exit; }
		}
		
		$this->shop_db = $this->load->database('store_db', TRUE);

		$this->shop_db->select('id,c_open,long_url,affiliate')
		->from('pk_shorturls')
		->where('short_code', $shortcode);
		$query = $this->shop_db->get();
		$num_rows = $query->num_rows();
		
		if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'buysellthailand.com')){
			//$this->sub_aff_id = 'BST';
			$this->sub_id1 = 'BST';
		}else if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puketstores.com')){
			//$this->sub_aff_id = 'PUKS';
			$this->sub_id1 = 'PUKS';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'thaiflashsale.com')){
			//$this->sub_aff_id = 'TFS';
			$this->sub_id1 = 'TFS';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puket24.com')){
			//$this->sub_aff_id = 'PK24';
			$this->sub_id1 = 'PK24';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'goodhouseideas.com')){
			//$this->sub_aff_id = 'GHD';
			$this->sub_id1 = 'GHD';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'akinho.com')){
			//$this->sub_aff_id = 'AKH';
			$this->sub_id1 = 'AKH';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'timeandfeeling.com')){
			//$this->sub_aff_id = 'TMF';
			$this->sub_id1 = 'TMF';
		}
		
		if($num_rows)
		{
			$_res = $query->row();
			
			$long_url = $_res->long_url;
			$ecomobi = $this->ecomobi_url.'?token='.$this->ecomobi_token.'&';
			$long_url = str_replace('https://c.lazada.co.th/t/c.bg20qz?', $ecomobi,$long_url).'&sub1=PUKS&advertiser_id=lazada.co.th&global_domain=lazada.co.th';
			//echo $long_url; exit;
			$long_url = str_replace('go.ecotrackings.com','goeco.asia',$long_url);
			//echo $long_url; exit;
			header( "Location: $long_url");exit;

		// https://goeco.asia/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fc.lazada.co.th%2Ft%2Fc.bVZp2U&sub1=web&sub2=puks&advertiser_id=lazada.co.th&global_domain=lazada.co.th
		// https://goeco.asia/4G1ix1Ij
		// https://goeco.asia/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fc.lazada.co.th%2Ft%2Fc.YAReoT&sub1=PUKS&advertiser_id=lazada.co.th&global_domain=lazada.co.th
		// https://goeco.asia/BPCZ7VQr
			
			$long_url = str_replace('https://c.lazada.co.th/t/c.BBe', $this->laz_link,$long_url);
			$pos = strpos($long_url,'shopee');
			
			if ($pos !== false && $this->go_accesstrade) {
				if($_res->affiliate=='shopee'){
					$long_url0 = $_res->long_url;
					$_url = explode('url=', $long_url);
					$surl1 = str_replace('https%3A%2F%2F','',$_url[1]); 
					$_url = explode('&', $surl1);
					$surl = explode('%2F', $_url[0]);
					$pos1 = strpos($long_url,'product');$a='';
					if ($pos1 !== false) {
						$long_url = "https://shopee.co.th/".$surl[1].(isset($surl[2])?'/'.$surl[2]:'').(isset($surl[3])?"/".$surl[3]:'')."";
					}else if(isset($surl[2])){
						$long_url = "https://shopee.co.th/".$surl[1]."/".$surl[2]."";
					}else{
						$long_url = "https://shopee.co.th/".$surl[1]."";
					}
					$acc_longurl = $long_url;
					//echo $long_url; exit;
				}
				$_res->affiliate='accesstrade';
			}
			$sku = '';
			
		
			if($_res->affiliate=='lazada'){
				$longurl1 = $long_url.'&sub_aff_id='.$this->sub_aff_id.'&sub_id1='.$this->sub_id1;
				$_replace= 'c.bg20qz?sub_aff_id='.$this->sub_aff_id.'&sub_id1=pk24&sub_id2=subid2&';
				$long_url =  str_replace('c.Z9dN?', $_replace, $longurl1);

			}else if($_res->affiliate=='accesstrade'){
				$campaign_shop='0041fn0009o6';
				$accurl = 'https://shope.ee/an_redir?origin_link='.urldecode(isset($acc_longurl)?$acc_longurl:$_res->long_url).'?&affiliate_id=15158670000&sub_id={psn}-{clickid}-{publisher_site_url}-{campaign}-';
				$long_url = 'https://atth.me/adv.php?rk='.$campaign_shop.'&url='.urlencode($accurl);
			}
			
			if($BonusComm){
				$long_url = str_replace('c.BBe', 'c.bg20qz', $long_url);
			}

			$_data = array();
			$_data['c_open'] = ($_res->c_open+1);
			//$this->shop_db->where(array('id'=>$_res->id));
			//$status_code = $this->shop_db->update('pk_shorturls', $_data);

			if($sku!=''){
				// Set Statistic
				//$this->set_statistic($sku, 'l');
			}
			$this->load->library('mdetect');
			$detect = new Mobile_Detect();
		    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS()) {
		    	$BonusComm = 1;
		    }

			$BonusComm = 0;
			if($this->is_aff=='laz'){
				// goto lazada affiliate ONLY
				$l = 'lazada';
				if($BonusComm){
					$l = 'lazada BonusComm';
					header( "Location: $long_url" );echo $l.'<br>'.($long_url);exit;
				}else {
					$l = 'lazada 1';
					header( "Location: $long_url" );
                    echo $l.'<br>'.($long_url);exit;
				}
			}else{
				// goto ecomobi affiliate ONLY
				$l = 'ecomobi';
				$is_active = '1';
				if($BonusComm){
					$l = 'BonusComm';
					header( "Location: $long_url" );echo $l.'<br>'.($long_url);exit;
				}else{
					//if($_res2->is_active=='10')
					if($is_active=='0')
					{
						if($_res2->keywords!=''){
							$pn = explode(',', $_res2->keywords);
							$keywords = $pn[0]; 
						}else{
							$category_4 = trim($_res2->category_4);
							$pn = explode(' ', ($category_4!=''?$category_4:trim($_res2->category_3)));
							$keywords = $pn[0]; 
						}
						$l = 'ecomobi1';
						$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url=https%3A%2F%2Fwww.lazada.co.th%2Fcatalog%2F?q='.utf8_encode($keywords).'&sub1='.$this->sub_id1;
					}else{
						if($_res->affiliate=='lazada')
						{
							$l = 'ecomobi2';
							$longurl = str_replace($this->laz_link.'?url=', '', $long_url);
							$long_url = $this->ecomobi_url.'?token='.$this->ecomobi_token;
							$long_url .='&url='.($longurl).'&sub1='.$this->sub_id1;
						}else{
							$l = 'ecomobi3  '.$long_url0;
							$d = date('d')*1;
							$long_url = str_replace('RTB', $this->sub_id1, $long_url);
							if($d>=$this->shopee_chk){
								if($_res2->keywords!=''){
									$pn = explode(',', $_res2->keywords);
									$keywords = $pn[0]; 
								}else{
									$category_4 = trim($_res2->category_4);
									$pn = explode(' ', ($category_4!=''?$category_4:trim($_res2->category_3)));
									$keywords = $pn[0]; 
								}
								$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token;
								$long_url .='&url=https%3A%2F%2Fwww.lazada.co.th%2Fcatalog%2F?q='.utf8_encode($keywords).'&sub1='.$this->sub_id1;
							}
						}
					}
				}
		    }
			//echo $l.' xx <br>'.($long_url);exit;
			header( "Location: $long_url" );
		}
		// https://go.ecotrackings.com/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fshopee.co.th%2Fproduct%2F173471635%2F6531715118&sub1=PKS
		exit;
	}

	public function link($shortcode='') 
	{
		$this->indexs($shortcode);
	}

	public function index($shortcode='') 
	{
		if (isset($_SERVER) && $_SERVER['SERVER_NAME']=='gogo.rotibit.com' && isset($_GET['url'])){
			if(isset($_GET['token']) && $_GET['token']=='tuCiMtVxRlTtAqPeThNRN'){
				$redirect_link = $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url='.$_GET['url'].'&sub1=PKS';
			} else if(isset($_GET['token']) && $_GET['token']=='an_15022360000'){
				//https://shopee.co.th/universal-link/shop/68420779?utm_source=an_15022360000&utm_medium=affiliates&utm_campaign=-&utm_content=ssp--PKS--
				$redirect_link = $this->shopee_url.''.str_replace('https://shopee.co.th/','',$_GET['url']).'?'.$this->shopee_key.'ssp--PKS--';
				//echo $redirect_link;exit;
			}
			header( "Location: $redirect_link" ); exit;
			//echo $_SERVER['SERVER_NAME'].' '.$_GET['url'];exit;
		}else{
			//load output cache
			//$this->output->cache(3*(24*60)); /* (3 day) */
			if($shortcode==''){ header( "Location: https://puketstores.com"); exit; }
		}
		
		$this->shop_db = $this->load->database('store_db', TRUE);

		$this->shop_db->select('id,c_open,long_url,affiliate')
		->from('pk_shorturls')
		->where('short_code', $shortcode);
		$query = $this->shop_db->get();
		$num_rows = $query->num_rows();
		
		if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'buysellthailand.com')){
			//$this->sub_aff_id = 'BST';
			$this->sub_id1 = 'BST';
		}else if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puketstores.com')){
			//$this->sub_aff_id = 'PUKS';
			$this->sub_id1 = 'PKS';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'thaiflashsale.com')){
			//$this->sub_aff_id = 'TFS';
			$this->sub_id1 = 'TFS';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'puket24.com')){
			//$this->sub_aff_id = 'PK24';
			$this->sub_id1 = 'PK24';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'goodhouseideas.com')){
			//$this->sub_aff_id = 'GHD';
			$this->sub_id1 = 'GHD';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'akinho.com')){
			//$this->sub_aff_id = 'AKH';
			$this->sub_id1 = 'AKH';
		}if(isset($_SERVER) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'timeandfeeling.com')){
			//$this->sub_aff_id = 'TMF';
			$this->sub_id1 = 'TMF';
		}
		
		if($num_rows)
		{
			$_res = $query->row();
		
			$long_url = $_res->long_url;
			$ecomobi = $this->ecomobi_url.'?token='.$this->ecomobi_token.'&';
			$long_url = str_replace('https://c.lazada.co.th/t/c.bg20qz?', $ecomobi,$long_url).'&sub1=PKS';
			$long_url = str_replace('go.ecotrackings.com','goeco.mobi',$long_url);
			//echo $long_url; exit;
			header( "Location: $long_url");exit;


		// https://goeco.mobi/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fwww.lazada.co.th&sub1=PKS
		// https://goeco.mobi/T2YW80Xe
		// https://goeco.mobi/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fshopee.co.th%2F&sub1=PKS
		// https://goeco.mobi/ivT6p6O6
			
			$long_url = str_replace('https://c.lazada.co.th/t/c.BBe', $this->laz_link,$long_url);
			$pos = strpos($long_url,'shopee');
			
			if ($pos !== false && $this->go_accesstrade) {
				if($_res->affiliate=='shopee'){
					$long_url0 = $_res->long_url;
					$_url = explode('url=', $long_url);
					$surl1 = str_replace('https%3A%2F%2F','',$_url[1]); 
					$_url = explode('&', $surl1);
					$surl = explode('%2F', $_url[0]);
					$pos1 = strpos($long_url,'product');$a='';
					if ($pos1 !== false) {
						$long_url = "https://shopee.co.th/".$surl[1].(isset($surl[2])?'/'.$surl[2]:'').(isset($surl[3])?"/".$surl[3]:'')."";
					}else if(isset($surl[2])){
						$long_url = "https://shopee.co.th/".$surl[1]."/".$surl[2]."";
					}else{
						$long_url = "https://shopee.co.th/".$surl[1]."";
					}
					$acc_longurl = $long_url;
					//echo $long_url; exit;
				}
				$_res->affiliate='accesstrade';
			}
			/* shopee affiliate directory
			else if ($pos !== false && $this->go_shopee && $_res->affiliate=='shopee') {
				$long_url0 = $_res->long_url;
				$_url = explode('url=', $long_url);
				$surl1 = str_replace('https%3A%2F%2F','',$_url[1]); 
				$_url = explode('&', $surl1);
				$surl = explode('%2F', $_url[0]);
				//$pos1 = strpos($long_url,'product');
				//if ($pos1 !== false) {
				//	$long_url = "https://shopee.co.th/universal-link/".$surl[1].(isset($surl[2])?'/'.$surl[2]:'').(isset($surl[3])?"/".$surl[3]:'')."?utm_source=an_15131260000&utm_medium=affiliates&utm_campaign=-&";
				//}else if(isset($surl[2])){
				//	$long_url = "https://shopee.co.th/universal-link/".$surl[1]."/".$surl[2]."?utm_source=an_15131260000&utm_medium=affiliates&utm_campaign=-&";
				//}else{
				//	$long_url = "https://shopee.co.th/universal-link/".$surl[1]."?utm_source=an_15131260000&utm_medium=affiliates&utm_campaign=-&";
				//}
				//$long_url .= "utm_content=68420779--RTB--&af_siteid=an_15131260000&pid=affiliates&";
				//$long_url .= "af_sub_siteid=68420779--RTB--&deep_and_deferred=1";

				$pos1 = strpos($long_url,'product');$a='';
				if ($pos1 !== false) {
					$a='1';$long_url = "https://shopee.co.th/universal-link/".$surl[1].(isset($surl[2])?'/'.$surl[2]:'').(isset($surl[3])?"/".$surl[3]:'')."";
				}else if(isset($surl[2])){
					$a='2';$long_url = "https://shopee.co.th/universal-link/".$surl[1]."/".$surl[2]."";
				}else{
					$a='3';$long_url = "https://shopee.co.th/universal-link/".$surl[1]."";
				}
				$long_url .= "?utm_source=an_15022360000&utm_campaign=-&utm_content=ssp--".$this->sub_id1."-WEB--&utm_medium=affiliates";
				//echo"<pre>";print_r($surl); 
				// https://shopee.co.th/universal-link
				//echo $_res->long_url.' - '.$a.' - '.$long_url;exit;
				header( "Location: $long_url" ); 
				exit;
				// https://shopee.co.th/universal-link/?utm_source=an_15022360000&utm_medium=affiliates&utm_campaign=-&utm_content=ssp--PKS-Teste-
			
			}*/

			$sku = '';
			/* 
			$this->shop_db->select('sku,product_name,tracking_link,campaign,promo_link,
			keywords,category_3,category_4,is_active')
			->from('pk_products')
			->like('tracking_link', $shortcode)
			->or_like('promo_link', $shortcode);
			$query2 = $this->shop_db->get();
			$num_rows2 = $query2->num_rows();
			$BonusComm = 0;
			
			if($num_rows2){
				$_res2 = $query2->row();
				$sku = $_res2->sku;
				if($_res2->campaign=='BonusComm' || $_res2->promo_link!=''){
					$BonusComm = 1;
				}
			}
			*/
		
			if($_res->affiliate=='lazada'){
				$longurl1 = $long_url.'&sub_aff_id='.$this->sub_aff_id.'&sub_id1='.$this->sub_id1;
				$_replace= 'c.bg20qz?sub_aff_id='.$this->sub_aff_id.'&sub_id1=pk24&sub_id2=subid2&';
				$long_url =  str_replace('c.Z9dN?', $_replace, $longurl1);

			}else if($_res->affiliate=='accesstrade'){
				$campaign_shop='0041fn0009o6';
				$accurl = 'https://shope.ee/an_redir?origin_link='.urldecode(isset($acc_longurl)?$acc_longurl:$_res->long_url).'?&affiliate_id=15158670000&sub_id={psn}-{clickid}-{publisher_site_url}-{campaign}-';
				$long_url = 'https://atth.me/adv.php?rk='.$campaign_shop.'&url='.urlencode($accurl);
			}
			
			if($BonusComm){
				$long_url = str_replace('c.BBe', 'c.bg20qz', $long_url);
			}

			$_data = array();
			$_data['c_open'] = ($_res->c_open+1);
			//$this->shop_db->where(array('id'=>$_res->id));
			//$status_code = $this->shop_db->update('pk_shorturls', $_data);

			if($sku!=''){
				// Set Statistic
				//$this->set_statistic($sku, 'l');
			}
			$this->load->library('mdetect');
			$detect = new Mobile_Detect();
		    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS()) {
		    	$BonusComm = 1;
		    }

			$BonusComm = 0;
			if($this->is_aff=='laz'){
				// goto lazada affiliate ONLY
				$l = 'lazada';
				if($BonusComm){
					$l = 'lazada BonusComm';
					header( "Location: $long_url" );echo $l.'<br>'.($long_url);exit;
				}else {
					$l = 'lazada 1';
					header( "Location: $long_url" );
                    echo $l.'<br>'.($long_url);exit;
				}
			}else{
				// goto ecomobi affiliate ONLY
				$l = 'ecomobi';
				$is_active = '1';
				if($BonusComm){
					$l = 'BonusComm';
					header( "Location: $long_url" );echo $l.'<br>'.($long_url);exit;
				}else{
					//if($_res2->is_active=='10')
					if($is_active=='0')
					{
						if($_res2->keywords!=''){
							$pn = explode(',', $_res2->keywords);
							$keywords = $pn[0]; 
						}else{
							$category_4 = trim($_res2->category_4);
							$pn = explode(' ', ($category_4!=''?$category_4:trim($_res2->category_3)));
							$keywords = $pn[0]; 
						}
						$l = 'ecomobi1';
						$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url=https%3A%2F%2Fwww.lazada.co.th%2Fcatalog%2F?q='.utf8_encode($keywords).'&sub1='.$this->sub_id1;
					}else{
						if($_res->affiliate=='lazada')
						{
							$l = 'ecomobi2';
							$longurl = str_replace($this->laz_link.'?url=', '', $long_url);
							$long_url = $this->ecomobi_url.'?token='.$this->ecomobi_token;
							$long_url .='&url='.($longurl).'&sub1='.$this->sub_id1;
						}else{
							$l = 'ecomobi3  '.$long_url0;
							$d = date('d')*1;
							$long_url = str_replace('RTB', $this->sub_id1, $long_url);
							if($d>=$this->shopee_chk){
								if($_res2->keywords!=''){
									$pn = explode(',', $_res2->keywords);
									$keywords = $pn[0]; 
								}else{
									$category_4 = trim($_res2->category_4);
									$pn = explode(' ', ($category_4!=''?$category_4:trim($_res2->category_3)));
									$keywords = $pn[0]; 
								}
								$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token;
								$long_url .='&url=https%3A%2F%2Fwww.lazada.co.th%2Fcatalog%2F?q='.utf8_encode($keywords).'&sub1='.$this->sub_id1;
							}
						}
					}
				}
		    }

            /*

            $this->shop_db->select('category,category_2,category_3,category_4')
            ->from('pk_products')
            ->where("tracking_link LIKE '%".$shortcode."%'" );
            $query = $this->shop_db->get();
            $num_rows = $query->num_rows();
            if($num_rows){
                $category1= '';
                $category= '';
                $_cate = $query->row();
                if($_cate->category_4!=''){ $category=$_cate->category_4; }
                else if($_cate->category_3!=''){ $category=$_cate->category_3; }
                else if($_cate->category_2!=''){ $category=$_cate->category_2; }
                else if($_cate->category!=''){ $category=$_cate->category;  }
                if($_cate->category!=''){ $category1=$_cate->category; }
                if($category!=''){ $long_url .='&sub3='.$category1.'&sub4='.$category;
                    //echo $long_url; exit;
                }
            }else{
                //echo $l.' 333<br>'.($long_url);exit;  
            }

            */
			if( $_res->affiliate=='accesstrade'){
            //if( in_array($shortcode,array('wbryYj','SZ3pKn')) ){
				//echo $_res->long_url.'  <br><a href="'.($long_url).'">test</a>';exit;
				// https://go.ecotrackings.com/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fwww.lazada.co.th%2Fproducts%2Fchowa-calm-i4871505253.html&sub_aff_id=RTB&sub_id1=PKS&sub1=PKS
				// https://go.ecotrackings.com/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fshopee.co.th%2Fproduct%2F10758731%2F5296631181&sub1=PKS
			}
			//echo $l.' xx <br>'.($long_url);exit;
			header( "Location: $long_url" );
		}
		// https://go.ecotrackings.com/?token=tuCiMtVxRlTtAqPeThNRN&url=https%3A%2F%2Fshopee.co.th%2Fproduct%2F173471635%2F6531715118&sub1=PKS
		exit;
	}

	public function redirect_promo($shortcode) 
	{
		$this->shop_db = $this->load->database('store_db', TRUE);

		$this->shop_db->select('id,c_open,long_url,affiliate')
		->from('pk_shorturls')
		->where('short_code', $shortcode);
		$query = $this->shop_db->get();
		$num_rows = $query->num_rows();
		if($num_rows)
		{
			$_res = $query->row();
			$long_url = $_res->long_url;
			$_data = array();
			$_data['c_open'] = ($_res->c_open+1);
			$this->shop_db->where(array('id'=>$_res->id));
			$status_code = $this->shop_db->update('pk_shorturls', $_data);

			// Set Statistic
			$this->set_statistic($sku, 'l');

			header( "Location: $long_url" );
		}
		exit;
	}

	public function gotoout() 
	{
		//load output cache
		//$this->output->cache(3*(24*60)); /* (3 day) */
        $request = strtolower($this->input->server('REQUEST_METHOD'));
        $_request =  $this->input->$request();

		$this->shop_db = $this->load->database('store_db', TRUE);

        $ori_long_url = isset($_request['bu'])?$_request['bu']:'';
        $bsk = isset($_request['bsk'])?($_request['bsk']):'';
        $blk = isset($_request['blk'])?($_request['blk']):'';
        $brand = $bn = isset($_request['bn'])?$_request['bn']:'';
        if($brand!=''){
	        $this->shop_db->select('brand_url')->from('laz_products')->where('brand_name', $brand);
			$query = $this->shop_db->get();
			$num_rows = $query->num_rows();
			if($num_rows)
			{
				$_lazprod = $query->row();
				$ori_long_url = $_lazprod->brand_url;
			}
		}
        //echo $long_url;exit();
        $pos = strpos($ori_long_url,'lazada');

        if ($pos === false) {
        	//echo $long_url.' - ss';
        	$is_laz = 0;
		}else{ 
			$is_laz = 1;
			//$long_url =  str_replace('c.Z9dN?', 'c.Z9dN?sub_aff_id='.$this->sub_aff_id.'&sub_id1=pk24&sub_id2=subid2&', $long_url.'&sub_aff_id='.$this->sub_aff_id.'&sub_id1='.$this->sub_id1); //
		}
		
		if($BonusComm){
			//$long_url = str_replace('c.BBe', 'c.z4UX', $long_url);
			//echo $_res->affiliate.' - '.$long_url;exit;
		}
	
		// Set Statistic
		//$this->set_statistic($sku, 'l');

		$this->load->library('mdetect');
		$detect = new Mobile_Detect();
	    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS()) {
	    	$BonusComm = 1;
	    	//header( "Location: $long_url" );
	    }

	    //else{
		if($BonusComm){
			if($is_laz){
				$long_url = $this->laz_link.'?url='.urlencode($ori_long_url.'?sub_aff_id='.$this->sub_aff_id.'&sub_id1='.$this->sub_id1);
			}
			//echo $long_url; exit;
			//$long_url
			//header( "Location: $long_url" );
		}else{
	        if($is_laz){
	        	$longurl = str_replace($this->laz_link.'?url=', '', $ori_long_url);
				//echo"<pre>";print_r($_res2); exit;
	        	//echo $sku.' === '.$long_url;exit;
	    	}
	    }
	    if($bsk!=''){
	     	//$long_url = $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url='.urlencode($longurl.'?sub1='.$this->sub_aff_id);
			$long_url=$this->ecomobi_url.'?token='.$this->ecomobi_token.'&url=https%3A%2F%2Fshopee.co.th%2Fsearch?keyword='.($bsk).'&sub1=RTB';
		}else if($blk!=''){
			$long_url= $this->ecomobi_url.'?token='.$this->ecomobi_token.'&url=https%3A%2F%2Fwww.lazada.co.th%2Fcatalog%2F?q='.utf8_encode($blk).'&sub1=RTB';
			//$long_url=$this->ecomobi_url.'?token='.$this->ecomobi_token.'&url=https%3A%2F%2Fshopee.co.th%2Fsearch?keyword='.($bsk).'&sub1=RTB';
		}else if($bn !=''){
			$long_url = base_url('search').'?q='.utf8_encode($bn);
			
		}

	    //
	    //echo $long_url."<pre>";print_r($_request); exit;

	    header( "Location: $long_url" );
		exit;
	}

	public function goto($sku='') 
	{
		//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$entities = array('%EF','%BB','%BF','%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array("", "", "", '!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		$sku = str_replace($entities, $replacements, ($sku));//exit;

		$this->shop_db = $this->load->database('store_db', TRUE);

		//$this->output->cache($this->cache_day*(24*60)); /* (6*60 = 24 hrs.) */
		
		$this->_predata();

		$this->shop_db->select($this->select_f)
		->from('pk_products')
		->where('active','1')
		->where("sku", $sku);
		$query = $this->shop_db->get();
		$row = $this->data_inner['product'] = $product =  $query->row();


		$category_slug = $row->category_slug;
		$tracking_link = $row->tracking_link;
		$this->data_inner['promo_link'] = $promo_link = $row->promo_link;
		//echo $sku.' - '.exit;
		$subject = strip_tags($row->product_name!=''?$row->product_name:$row->category.' '.$row->category_2.' '.$row->category_3);
		$pos = strpos($tracking_link, 'lazada.co.th');
		if ($pos) {
			$slug_url = ($row->affiliate == 'lazada'?($this->laz_link.'?url='.$tracking_link):$tracking_link);
		}else{
			if ($row->affiliate=='puket24') {
				$slug_url = $tracking_link;
			}else{
				$pos1 = strpos($tracking_link, 'puket');
				$pos2 = strpos($tracking_link, 'rotibit');
				if ($pos1) {
					$slug_url = $tracking_link;
				}else{
					if ($pos2) {
						$slug_url = $tracking_link;
					}else{
						$slug_url = $this->laz_link.'?url=https://www.lazada.co.th/catalog/?q='.htmlentities(str_replace('_', ' ', $category_slug));
					}
				}
			}
		}
	
		if($row->buyer=='shopee'){
			$tracking_link =  (str_replace('https://puket.store/r', '', $slug_url));
			$tracking_link =  (str_replace('https://puket.store/shopee', '', $tracking_link));
			$tracking_link =  (str_replace('https://puket.store//shopee', '', $tracking_link));
			$row->tracking_link = $this->data_inner['tracking_link'] = base_url('link'.$tracking_link);
			if(in_array($row->sku, array('5408402656'))){
				//echo $row->tracking_link ;
			}
		}else{	
			$tracking_link =  (str_replace('https://puket.store/r', '', $slug_url));
			$tracking_link =  (str_replace('https://puket.store/lazada', '', $tracking_link));
			$tracking_link =  (str_replace('https://puket.store//lazada', '', $tracking_link));
			//$row->tracking_link = $this->data_inner['tracking_link'] = base_url('link'.$tracking_link);
			if ($pos2) {
				$tracking_link =  (str_replace('https://www.rotibit.com/link', '', $tracking_link));
				$tracking_link =  (str_replace('https://www.rotibit.com/r', '', $tracking_link));
				$tracking_link = $this->data_inner['tracking_link'] = base_url('link'.$tracking_link);
				$row->tracking_link = $this->data_inner['tracking_link'];
				//echo"***";//exit;
			}else{
				$row->tracking_link = $this->data_inner['tracking_link'] = base_url('link'.$tracking_link);
			}
		}

		$slug_url = $this->data_inner['slug_url'] = base_url('product/'.$row->buyer.'/'.$row->sku);

		if($row->buyer=='shopee')
		{
			$cate = array();
			$catenavi_list = array();
			$parent = $row->category_id;
			for ($i=2; $i < 5 ; $i++) { 
				if($i > 1){
					$k = 'category_'.$i;
				}
				$cate = $row->$k;
				if($cate!='')
				{
					$ca = $this->shop_db->select('id,slug,name_th')
					->from('pk_categories')
					->where('buyer','shopee')
					->where('parent', $parent)
					->where("name_th", $cate)
					->get()->row();
					$parent = $ca->id;
					$catenavi_list[] = $ca;
				}
			}
			$this->data_inner['catenavi_list'] =  $catenavi_list;
		}

		$seoname = $this->Tools_model->gen_urlname($row->product_name);
		$category_name = str_replace(' New', '', $row->category);
		$og_url = $slug_url;
		$og_image = $row->image_url;
		$this->data_header['meta_title'] = $subject.' - '.$this->data_header['meta_title'];
		$this->data_header['meta_keywords'] = (str_replace(' ', ', ', $row->product_name)).', '.$category_name.', '.$row->brand.', ลาซาด้า, สินค้าแนะนำ, เปรียบเทียบราคา, สินค้าขายดี, ช้อปปิ้งออนไลน์';
		$this->data_header['meta_description'] = $subject.' '.$this->data_header['meta_description'];
		$this->data_header['og_url'] = $og_url;
		$this->data_header['og_image'] =$this->data_header['meta_image']  = $og_image;
		$this->data_header['og_type'] = 'website';
		$this->data_header['og_brand'] = $row->brand; //+$this->app_config['shop_shipprice']
		$this->data_header['og_sku'] = $row->sku; //+$this->app_config['shop_shipprice']
		$this->data_header['og_category'] = $category_name.' > '.$row->category_2.' > '.$row->category_3.(($row->category_4!='')?' > '.$row->category_4:''); //+$this->app_config['shop_shipprice']
		$this->data_header['og_label0'] = $row->category_en; //+$this->app_config['shop_shipprice']
		$this->data_header['og_label1'] = $row->category; //+$this->app_config['shop_shipprice']
		$this->data_header['og_label2'] = $row->category_2; //+$this->app_config['shop_shipprice']
		$this->data_header['og_label3'] = $row->category_3; //+$this->app_config['shop_shipprice']
		$this->data_header['og_label4'] = $row->category_4; //+$this->app_config['shop_shipprice']
		$this->data_header['og_producttype'] = ($row->category_4!='')?$row->category_4:$row->category_3; //+$this->app_config['shop_shipprice']
		$this->data_header['og_amount'] = number_format((($row->discounted_price)*1), 2, '.', '');

		$this->data_inner['reviewcount'] =  1;
		$this->data_inner['aggregaterating'] = 4; 

		if(in_array($sku, array('237189806_TH-363792424','306386255_TH-532690243','BS677FAAAFVMV4ANTH-32706888')) ){
			//echo $this->data_inner['tracking_link']." <pre>"; exit;//print_r($row); 
		}

		$imageurl = array('img1'=>$row->image_url,'img2'=>$row->image_url_2,'img3'=>$row->image_url_3,'img4'=>$row->image_url_4,'img5'=>$row->image_url_5);
		foreach ($imageurl as $key => $val) {
			if($val!='')$this->data_inner['image_url'][] = $val;
		}   
		if(!$this->newtemp){
        	$this->tmp_path = '';
        }
		$template = $this->tmp_path.'product_redirect';
		
		$this->data_header['is_noindex'] = 1;

		$data = array(
					  'header'	=> $this->load->view($this->tmp_path.'header', $this->data_header, TRUE),
					  'content'  => $this->load->view($template, $this->data_inner, TRUE),
					  'footer'	=> $this->load->view($this->tmp_path.'footer', $this->data_footer,TRUE)
					);

		$this->parser->parse('main_template', $data);
	}

	public function set_statistic($sku, $type='v')
	{
		$this->shop_db = $this->load->database('store_db', TRUE);

		$this->load->library('mdetect');
		$detect = new Mobile_Detect();
	    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS() || $detect->isiOS()) {
	        //echo 'mobile';	
	    	$device = 'mobile';
	        //header("Location: ".$this->config->item('base_url')."/mobile"); 
	        //exit;
	    }else{
	    	$device = 'desktop';
	    	//echo 'Desktop';
	    }
		$UserAgent = strtolower($detect->getUserAgent());
		$pos = strpos($UserAgent, 'bot');
		$is_bot = 0;
		if ($pos) {
			$is_bot = 1;
		}
		if(!$is_bot)
		{
			$ipdate = date("Y-m-d");

			$this->shop_db->select('id,sku,category_slug')
			->from('pk_products')
			->where('sku', $sku);
			$_products = $this->shop_db->get()->row();

			// 
			$this->shop_db->select('ipdate,sku,view,link')
			->from('pk_statistic')
			->where('sku', $sku)
			->where('ipdate', $ipdate);
			$query = $this->shop_db->get();
			$num_rows = $query->num_rows();
			if(!$num_rows)
			{
				$_data = array();
	    		$_data['sku'] = $sku;
	    		$_data['device'] = $device;
	    		$_data['ipdate'] = $ipdate;
	    		$_data['view'] = 1;
				if($type=='l'){
					$_data['link'] = 1;
				}
				$this->shop_db->insert('pk_statistic', $_data);
				$insert_id = $this->shop_db->insert_id();							
			}else{
				$_statistic = $query->row();

				$_data = array();
	    		$_data['sku'] = $sku;
	    		$_data['device'] = $device;
	    		$_data['ipdate'] = $ipdate;
				$_data['view'] = $_statistic->view+1;
				if($type=='l'){
					$_data['link'] = $_statistic->link+1;
				}
				$this->shop_db->where(array('ipdate'=>$ipdate, 'sku'=>$sku));
				$upd = $this->shop_db->update('pk_statistic', $_data);			
			}
			if($_products->category_slug != '')
			{
				$slug = $_products->category_slug;
				
				$this->shop_db->select('ipdate,slug,view,link')
				->from('pk_stat_category')
				->where('slug', $slug)
				->where('ipdate', $ipdate);
				$query = $this->shop_db->get();
				$num_rows = $query->num_rows();
				if(!$num_rows)
				{
					$_data = array();
		    		$_data['slug'] = $slug;
		    		$_data['device'] = $device;
		    		$_data['ipdate'] = $ipdate;
		    		$_data['view'] = 1;
					if($type=='l'){
						$_data['link'] = 1;
					}
					$this->shop_db->insert('pk_stat_category', $_data);
					$insert_id = $this->shop_db->insert_id();							
				}else{
					$_stat = $query->row();
					$_data = array();
		    		$_data['slug'] = $slug;
		    		$_data['device'] = $device;
		    		$_data['ipdate'] = $ipdate;
					$_data['view'] = $_stat->view+1;
					if($type=='l'){
						$_data['link'] = $_stat->link+1;
					}
					$this->shop_db->where(array('ipdate'=>$ipdate, 'slug'=>$slug));
					$upd = $this->shop_db->update('pk_stat_category', $_data);			
				}
			}
		}
	}

	public function prods() 
	{
		$request = strtolower($this->input->server('REQUEST_METHOD'));
		$_request =  $this->input->$request();

		$this->rotibit_db = $this->load->database('rotibit_db', TRUE);

		$sku = str_replace('/','',$_request['p']);
		$this->rotibit_db->select('a.*')
		->from('rtb_postmeta a')
		->join('rtb_posts b','b.ID=a.post_id')
		->where('b.post_type', 'product')
		->where("b.post_status <>'trash'")
		->where('a.meta_key', '_sku')
		->where('a.meta_value', $val->sku);
		$query = $this->rotibit_db->get();
		$num_rows = $query->num_rows();
		echo $num_rows; exit;
		$query->free_result();
		if(!$num_rows) {
			$url = 'https://go.rotibit.com/webhook/rtb_postxmlrpc/?sku='.$sku ;
			$this->call_get($url);
		}
		$_redirect = "https://www.rotibit.com/product/".$sku;
		//header("Location: ".u$_redirect);
		header( "Refresh:3; url=".$_redirect, true, 303);
		exit;
	}

	private function call_get($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}
