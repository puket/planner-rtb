<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class onload
{
	private $ci;
	/**
	* includes the directory application\my_classes\Classes in your includes directory
	*
	*/
	function __construct()
	{
		$this->ci = & get_instance();
	}

	function chk_login()
	{
		//includes the directory application\my_classes\Classes\
		
		//ini_set('include_path', ini_get('include_path').':'.BASEPATH.'app_tax/excel_class/Classes/');

		$controller = strtolower($this->ci->router->class);	
			
		$method = strtolower($this->ci->router->method);
		//echo $controller .''.$method;exit;
		$un_cont = array('welcome','shopee','urltips','webhook','wp','kpproject','order','urlake','home');
		/*$in_cont = array('adm','auth');*/
		if(in_array($controller, $un_cont) )
		{
		}else{

			if(!$this->ci->session->userdata('admlogged')) {
				
				if($method != 'logout' && $method != 'login' && $method != 'loging' ){

					//echo $controller; echo ' --s '; echo $method; exit;
					redirect("login","refresh");
					exit;				
				}
			}else{

				if($controller == 'auth' && ($method == 'index' || $method == 'login' || $method == 'adm')){
					redirect('adm',"refresh");
					exit;		
				}		
			}
		}
		/*else{
			redirect("adm","refresh");
			exit;
		}*/

		//$un_cont = array('online','design','newhome','callaj','seattemplate','payment','dew','sysview','facebook','vamp','viewvam');
		/*
		if(!$this->ci->session->userdata('admlogged')) {
			if(!in_array($controller, $un_cont))
			{
				if(
					$controller != 'online' && 
					($controller != 'auth' || $method != 'index') && 
					($controller != 'auth' || $method != 'login') && 
					($controller != 'auth' || $method != 'loging')&& 
					($controller != 'auth' || $method != 'logout')&& 
					($controller != 'auth' || $method != 'loginview')&& 
					($controller != 'auth' || $method != 'logoutview')
					)
				{ 
					redirect("adm","refresh");
					exit;
				}
			}
		}
		else{ //echo 'logged'; exit;
			if($controller == 'auth' && ($method == 'index' || $method == 'login' || $method == 'adm')){
				redirect('adm/dashboard',"refresh");
				exit;
			}
		}		
		*/
	}

}

?>