<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		//$this->shop_db = $this->load->database('store_db', TRUE);
	}
	
	public function userlogin($username, $password, $remember=FALSE)
	{
		$auth = $this->get_userlogin($username,$password);
		
		return $auth;
	}
	
	public function get_userlogin($username, $password)
	{
		$this->load->database();

		//Check From Database 
		$this->db->select('*');
		$this->db->from('pk_users');
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$this->db->where('status', '1');
		$this->db->where('user_level', 'user');
		$query = $this->db->get();
		$result = $query->row();
		$query->free_result();

		return $result;
	}

	public function adminlogin($username, $password, $remember=FALSE)
	{ 
		$auth = $this->get_adminlogin($username,$password);
		
		return $auth;
	}
	
	public function get_adminlogin($username, $password)
	{
		$this->load->database();

		//Check From Database 
		$this->db->select('*');
		$this->db->from('pk_users');
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$this->db->where('user_level', 'admin');
		$query = $this->db->get();
		$result = $query->row();
		$query->free_result();

		return $result;
	}
	
}