<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		//load database
		//$this->load->database();

		//load library session		
		$this->load->library('session');

	}

	public function do_upload($file_name, $field_type, $field_name, $size=array())
	{
		$config['upload_path'] = 'assets/img/content/';
		if($field_type =='vdo'){
			$config['allowed_types'] = 'mp4';
		}else if($field_type =='pdf'){
			$config['allowed_types'] = 'pdf';
		}else if($field_type =='file'){
			$config['allowed_types'] = 'zip|doc|pdf|xls';
		}else if($field_type =='vdo'){ 
			$config['allowed_types'] = 'mp4';
		}else if($field_type =='image'){
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_width']  = '2000';
			$config['max_height']  = '2000';
			if(count($size)){
				$config['max_width']  = $size[0];
				$config['max_height']  = $size[1];
			}
		}else if($field_type =='cover'){
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_width']  = '5000';
			$config['max_height']  = '2500';
			if(count($size)){
				$config['max_width']  = $size[0];
				$config['max_height']  = $size[1];
			}			
		}

		$field_name = $field_name;
		$config['max_size']	= '500000';
		$config['file_name'] = $file_name;
		$config['overwrite'] = false;

		$this->load->library('upload', $config);

		$this->upload->initialize($config);

		if (!$this->upload->do_upload($field_name))
		{
			$error = array('error' => $this->upload->display_errors());
			//print_r($_FILES);
			//print_r($config); 
			print_r($error);exit;
			return '';
			//$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$_data = array();
			$_data['file_name'] = $data['upload_data']['file_name'];
			$_data['file_type'] = $data['upload_data']['file_type'];
			$_data['file_path'] = $data['upload_data']['file_path'];
			$_data['full_path'] = $data['upload_data']['full_path'];
			$_data['raw_name'] = $data['upload_data']['raw_name'];
			$_data['orig_name'] = $data['upload_data']['orig_name'];
			$_data['file_ext'] = $data['upload_data']['file_ext'];
			$_data['file_size'] = $data['upload_data']['file_size'];
			$_data['is_image'] = $data['upload_data']['is_image'];
			$_data['image_width'] = $data['upload_data']['image_width'];
			$_data['image_height'] = $data['upload_data']['image_height'];
			$_data['image_type'] = $data['upload_data']['image_type'];
			$_data['image_size_str'] = $data['upload_data']['image_size_str'];
			$_data['status'] = '1';
			$_data['updatedate'] = date("Y-m-d H:i:s");

			// Insert
			//$file_id = $this->db->insert('tbl_billingfile', $_data);
			//$this->load->view('upload_success', $data);
			if($_data['file_name']!=''){
				return $_data['file_name'];

			}
			return '';
		}
	}

	public function call_rest($url, $fields, $use_get = true) 
	{
		$curl = curl_init($url);
		if($use_get) {
			//$get_params = array();
		}
		else {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		}
		curl_setopt($curl, CURLOPT_REFERER, $url);
		//curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36');
		//curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)'); 
		//curl_setopt($curl, CURLOPT_ENCODING , "gzip");
		curl_setopt($curl, CURLOPT_TIMEOUT, 600); // 60 second

		$result = curl_exec($curl);
		curl_close($curl);

		// Convert the json response to an array
		$response = json_decode($result, true);
		echo $url."<pre>";print_r($response);exit;
		// Check for errors
		if (count($response['errors']))
		{
			throw new Exception(implode('; ', $response['errors']));
		}

		// Set specified data from response
		$resultJSON = $response;
		$result = (array)$resultJSON;
		
		return $result;
	}
	
	public function gen_shortcode($length=6) 
	{
	    $characters = '123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ';
	    $charactersLength = strlen($characters);
	    $shortcode = '';
	    for ($i = 0; $i < $length; $i++) {
	        $shortcode .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $shortcode;		
	}

	public function gen_urlname($realname) 
	{
		$realname = trim($realname);
		$seoname = str_replace("!","",$realname);
		$seoname = str_replace("+","",$seoname);
		$seoname = str_replace("/","",$seoname);
		$seoname = str_replace("&","",$seoname);
		$seoname = str_replace("%","",$seoname);
		$seoname = str_replace("(","",$seoname);
		$seoname = str_replace(")","",$seoname);
		$seoname = str_replace(",","",$seoname);
		$seoname = str_replace("[","",$seoname);
		$seoname = str_replace("]","",$seoname);
		$seoname = str_replace("?","",$seoname);
		$seoname = str_replace(":","",$seoname);

		return $seoname;
	}

	public function gen_keywords_seo($realname) 
	{
		$realname = trim($realname);
		$seoname = preg_replace('/\%/',' ',$realname);
		$seoname = preg_replace('/\@/',' at ',$seoname);
		$seoname = preg_replace('/\&/',' ',$seoname);
		$seoname = preg_replace('/\s[\s]+/',', ',$seoname);    // Strip off multiple spaces
		$seoname = preg_replace('/[\s\W]+/',', ',$seoname);    // Strip off spaces and non-alpha-numeric
		$seoname = preg_replace('/^[\-]+/','',$seoname); // Strip off the starting hyphens
		$seoname = preg_replace('/[\-]+$/','',$seoname); // // Strip off the ending hyphens
		$seoname = strtolower($seoname);
		
		return $seoname;
	}

	public function gen_slug_seourl($realname) 
	{
		$realname = trim($realname);
		$seoname = preg_replace('/\%/',' percentage',$realname);
		$seoname = preg_replace('/\@/',' at ',$seoname);
		$seoname = preg_replace('/\&/',' and ',$seoname);
		$seoname = preg_replace('/\s[\s]+/','-',$seoname);    // Strip off multiple spaces
		$seoname = preg_replace('/[\s\W]+/','-',$seoname);    // Strip off spaces and non-alpha-numeric
		$seoname = preg_replace('/^[\-]+/','',$seoname); // Strip off the starting hyphens
		$seoname = preg_replace('/[\-]+$/','',$seoname); // // Strip off the ending hyphens
		$seoname = strtolower($seoname);

		return $seoname;
	}	

	public function slug_seourl($realname) 
	{
		$realname = trim($realname);
		$seoname = preg_replace('/\%/',' percentage',$realname);
		$seoname = preg_replace('/\@/',' at ',$seoname);
		$seoname = preg_replace('/\&/',' and ',$seoname);
		$seoname = preg_replace('/\s[\s]+/','-',$seoname);    // Strip off multiple spaces
		$seoname = preg_replace('/[\s\W]+/','-',$seoname);    // Strip off spaces and non-alpha-numeric
		$seoname = preg_replace('/^[\-]+/','',$seoname); // Strip off the starting hyphens
		$seoname = preg_replace('/[\-]+$/','',$seoname); // // Strip off the ending hyphens
		$seoname = strtolower($seoname);

		return $seoname;
	}


	public function get_sess() 
	{
		$_sess = $this->session->all_userdata();
		return $_sess;
	}
	
	public function callrest($url, $fields, $use_get = false) 
	{
		$ch = curl_init();
		if($use_get) {
			$get_params = array();
		}
		else {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		// Convert the json response to an array
		$response = json_decode($result, true);
		
		//echo $url."<pre>";print_r($response);exit;		// Set specified data from response
		$resultJSON = $response;
		$result = (array)$resultJSON;

		return $result;
	}

	public function callget($url, $param=array()) 
	{

		// Set the URL
		$curl = curl_init();

		// Set options
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 30
		));

		// Send the request
		$response = curl_exec($curl);

		// Close request
		curl_close($curl);

		// Convert the json response to an array
		$response = json_decode($response, true);

		// Check for errors
		if (count($response['errors']))
		{
			throw new Exception(implode('; ', $response['errors']));
		}

		// Set specified data from response
		$resultJSON = $response;
		$result = (array)$resultJSON;

		return $result;
	}
		
	public function swap_img($img)
	{
		//$img = str_replace('images.six.betanews.com', 'img2.rotibit.com', $img);
		//$img = str_replace('screenshots.en.sftcdn.net', 'img2.rotibit.com', $img);
		//$img = str_replace('cache.filehippo.com', 'img3.rotibit.com', $img);

		return $img;
	}

	public function os_text($os)
	{
		$os=strtolower(substr($os,0,3));

		if ($os=="win"){
			$os_text='Windows';
		}else if($os=="mac"){
			$os_text='OS Mac';
		}else if($os=="ios"){
			$os_text='iOS';
		}else if($os=="and"){
			$os_text='Android';
		}else{
			$os_text = 'Linux';
		}

		return $os_text;
	}


	public function get_ratedownload( $id) {

		$download_rate = $this->db->select('rate')
			->from('download_rate')
			->where("rateid", $id )
			->get()
			->result();

			$meanRate = array();
			$num = count($download_rate);
			if( count($download_rate) ){
				foreach($download_rate as $key => $val) {
					//$val->rate;
					$meanRate[$val->rate]= isset($meanRate[$val->rate]) ? $meanRate[$val->rate]+1 : 1;
				}

				$rest=0;
				while (list($key, $val) = each($meanRate)) {
					 $rest=$rest+($key * $val);
				}
				$num0 = floor($rest / $num);
				$num1 = explode(".", $num0);
				//if($t==0){$txt="/5 ($num)";}

				if(count($num1)>1){
					return number_format($num0, 1, '.', '');
				}else{
					return number_format($num0);
				}
			}else{
				return 0;
			}
	}

	public function date_thai($dt,$lang,$type)
	{
		$date= strtotime($dt);
		$lang= $lang;
		$type= $type;
		
		if($date){
			$day=date("j",$date);
			$month=date("n",$date);			
			$year=date("Y",$date);
			// Th
			if($lang=="th"){
				$month=(int)$month-1;
				$year=$year+543;
				if($type=="1"){
					$thaimonth=array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
				}else{
					$thaimonth=array("ม.ค","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				}
				$month=$thaimonth[$month];
				$ret = $day."&nbsp;".$month."&nbsp;".$year;
			// En
			}else if($lang=="en"){
				if($type=="1"){
					$month=date("F",$date);
				}else{
					$month=date("M",$date);
				}
				$ret = $day."&nbsp;".$month."&nbsp;".$year;
			// age
			}else if($lang=="age"){
					$ret= date("Y")-$params['dt']."  ปี";
			}else{
				if($type=="1"){
					$ret=date("G:i น.",$date);
				}else{
					$ret=date("G:i:s",$date);
				}
			}

			return $ret;
		}else{
			return '-';
		}
	}

	public function get_monthyear($lang="th", $date="", $type="0")
	{
			$month=date("n",$date);
			$year=date("Y",$date);
			if($lang=="th"){
				$month=(int)$month-1;
				$year=$year+543;
				if($type=="1"){
					$thaimonth=array("มกราคม","กุมภาพันธ์ ","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				}else{
					$thaimonth=array("ม.ค.","ก.พ.","มี.ค.","ม.ย.","พ.ค.","มิ.ย.", "ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				}
				$month=$thaimonth[$month];
				return $month." ".$year;
			}else{
				$month=date("M",$date);
				return $month." ".$year;
			}
	}
	public function get_dateformat($lang="th", $date="", $type="0")
	{

		if($date){
			$dayofweek=date("l",$date);
			$day=date("j",$date);
			$month=date("n",$date);
			$year=date("Y",$date);
			$tm = '';
			// Th
			if($lang=="th"){
				$month=(int)$month-1;
				$year=$year+543;
				if($type=="1"){
					$thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
				}else{
					$thaimonth=array("ม.ค.","ก.พ.","มี.ค.","ม.ย.","พ.ค.","มิ.ย.", "ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
				} 

				if($type=="3"){
					$tm = ' '.date("H:i",$date)." น.";
				}
				$month=$thaimonth[$month];

				return (int)$day." ".$month." ".$year.$tm;

			// En
			}else{
				if($type=="1"){
					$month=date("F",$date);
					return (int)$day." ".$month." ".$year;
				}else if($type=="2"){
					$month=date("F",$date);
					return $dayofweek.', '.(int)$day." ".$month." ".$year;
				}else if($type=="4"){
					$month=date("M",$date);
					return (int)$day." ".$month." ".$year;
				}else if($type=="5"){
					$month=date("m",$date);
					$day=date("d",$date);
					return $day."/".$month."/".$year;
				}else{
					$month=date("M",$date);
					return $dayofweek.', '.(int)$day." ".$month." ".$year;
				}

				

			} //Saturday, 10 September 2016

			//echo (int)$day." ".$month." ".$year;  exit;
			
		}else{
			return "-";
		}
	}
	public function gethtml_curl($_url) 
	{
		$characters = '012';
		$charactersLength = strlen($characters);
		$scode = 0;
		for ($i = 0; $i < 1; $i++) {
			$scode = $characters[rand(0, $charactersLength - 1)];
		}
		$ch = curl_init($_url);
		$AGENT[0] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
		$AGENT[1] ='Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
		$AGENT[2]= 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html';

		curl_setopt($ch, CURLOPT_USERAGENT, $AGENT[$scode]);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_REFERER, $_url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 500);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$data = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		return array('status'=>$status, 'info'=>$info,'data'=>$data );
	}

	public function get_checkinout($format="Y-m-d", $chkin='',$days='')
	{	
		$ret = array();
		if($chkin!='' && $days!=''){
				$strtime = strtotime($chkin.' 00:00:00');
				$ret['checkin'] = date($format, $strtime);
				$tomorrow = mktime(0, 0, 0, date("m",$strtime), date("d",$strtime)+$days, date("Y",$strtime));
				$ret['checkout'] = date($format,$tomorrow);
		}else{
			if(date("H")<15)
			{
				$ret['checkin'] = date($format);
				$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
				$ret['checkout'] = date($format,$tomorrow);
			}else{
				$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
				$ret['checkin'] = date($format, $tomorrow);
				$tomorrow2 = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
				$ret['checkout'] = date($format, $tomorrow2);
			}
		}

		return $ret;
	}
}