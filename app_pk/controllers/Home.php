<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

class Home extends CI_Controller {
	
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
		//$this->load->model('Master_model');
		//$this->load->model('Tools_model');
	}

	public function index() 
	{
		$data = array(
					  'header'	=> $this->load->view('header', $this->data_header, TRUE),
					  'content'  => $this->load->view('dashboard', $this->data_inner, TRUE),
					  'footer'	=> $this->load->view('footer', $this->data_footer,TRUE)
					);

		$this->parser->parse('main_template', $data);
	}

}
