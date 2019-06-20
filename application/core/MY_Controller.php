<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('cto_menu_model');
		$this->load->model('cto_user_model');
		$this->check_grant_access();
		$this->fillLog();
	}

	protected function view($content, $data_view = NULL) {
		// $user_data = $this->session->userdata('userdata');
		// $username = $user_data['username'];
		
		$user_data = $_SESSION;
		$username = null;
		if(!empty($user_data['username'])){
			$username = $user_data['username'];
		}
		//jika session habis, kembali ke halaman login
		
		
		$class = $this->router->fetch_class();
		$method = $this->router->fetch_method();
		
		$user_menus = $this->cto_menu_model->get_user_menus($username, $class, $method)->result();
		$data_view['user_menus'] = $user_menus;
		
		if($this->cto_menu_model->check_access($username, $class, $method)->num_rows() > 0){
			//if($this->cto_menu_model->check_is_shown($username, $class, $method)->num_rows() > 0){
				$data_view['content'] = $content;
				$this->load->view('layouts/main', $data_view);
			// }else{
				// $pesan['status'] = false;
				// $pesan['messages'] = 'Access Denied!';
				// echo json_encode($pesan);
			// }
		}else{
			echo "<div class=\"page-accessdenied\" style=\"font-size:100pt;color:red;font-weight: 900;margin: 0;position: absolute;top: 10%;right: 50%;-ms-transform: translateY(-50%);transform: translateY(-50%);-ms-transform: translateX(50%);transform: translateX(50%);\">401</div><br>
			<div class=\"page-accessdenied\" style=\"font-size:20pt;margin: 0;position: absolute;top: 33%;right: 50%;-ms-transform: translateY(-50%);transform: translateY(-50%);-ms-transform: translateX(50%);transform: translateX(50%);\">Access Denied!</div>";
		}
	}
	
	protected function view_free($content, $data_view = NULL) {
		$data_view['content'] = $content;
		$this->load->view($content, $data_view);
	}
	
	protected function check_grant_access(){
		$user_data = $_SESSION;
		$username = null;
		if(!empty($user_data['username'])){
			$username = $user_data['username'];
		}
		$controller = $this->router->fetch_class();
		$method = $this->router->fetch_method();
		
		//check menu_exist, if exist regitered in app. Then check if user have the access
		if($this->cto_menu_model->check_menu_exist($controller, $method)->num_rows() > 0){
			//check isn't user having the menu access? if weren't, then access denied
			if(!$this->cto_menu_model->check_access($username, $controller, $method)->num_rows() > 0){
				$pesan['status'] = false;
				$pesan['messages'] = 'Access Denied!';
				echo json_encode($pesan);
				exit();
			}
		}
	}
	
	protected function fillLog(){
		$user_data = $_SESSION;
		$username = 'null';
		$fk_user_id = 'null';
		if(!empty($user_data['username'])){
			$username = $user_data['username'];
			$fk_user_id = $user_data['id'];
		}
		$controller = $this->router->fetch_class();
		$method = $this->router->fetch_method();
		
		$fk_menu_id = 'null';
		if($this->cto_menu_model->check_menu_exist($controller, $method)->num_rows() > 0){
			foreach($this->cto_menu_model->check_menu_exist($controller, $method)->result() as $res){
				$fk_menu_id = $res->id;
			}
		}
		
		$ip_address = $this->getUserIP();
		//insert log
		$this->cto_user_model->insert_log($fk_user_id, $username, $ip_address, $fk_menu_id, $controller, $method);
	}
	
	function getUserIP()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				  $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		return $ip;
	}

	protected function view_2($content, $data_view = NULL) {
			$this->load->view($content);
	}
}