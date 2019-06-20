<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cto_user extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('cto_menu_model');
		$this->load->model('cto_menupackage_model');
		$this->load->model('cto_user_model');
	}
	
	public function index()
	{
		$data['coba'] = 'welcome';
		$this->view('cto_user/cto_list_user', $data);
		//$this->view('layouts/content', $data);
		//var_dump($this->get_user_menus('admin'));
	}
	
	public function fetch()
	{
		$keyword = null;
		if(isset($_POST["search"]["value"])){
			$keyword = $_POST["search"]["value"];
		}
		
		$orders = null;
		if(isset($_POST["order"])){
			$orders = array($_POST['order']['0']['column'], $_POST['order']['0']['dir']);
		}
		
		$limit = null;
		if(isset($_POST["length"]) && $_POST["length"] != -1){
			$limit = array(intval($_POST["start"]), intval($_POST['length']));
		}
		
		// var_dump(intval($_POST["draw"]));$var_dump();
		// var_dump(($this->bond_model->nAllData()+0));
		// var_dump($this->bond_model->getData($keyword, $orders, $limit));
		// var_dump($this->bond_model->nData($keyword, $orders, $limit));
		//$arr = $this->bond_model->getData($keyword, $orders, $limit);
		//var_dump(count($arr));
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->cto_user_model->nAllData(),
			"recordsFiltered" => intval($this->cto_user_model->nData($keyword, $orders)),
			"data"    => $this->cto_user_model->getData($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function login_validating()
	{
		$pesan['status'] = false;
		$cto_check = true;
		if ($this->input->post('username') != "" ) {
			$data['username'] = $this->input->post('username');
		}else{
			$pesan['err_username'] = 'Username tidak boleh kosong!';
			$cto_check = false;
		}


		if ($this->input->post('password') != "" ) {
			$data['password'] = md5($this->input->post('password'));
		}else{
			$pesan['err_password'] = 'Password tidak boleh kosong!';
			$cto_check = false;
		}
		
		try{
			if($cto_check){
				$user = $this->cto_user_model->login_validating($data);
				if($user->num_rows() > 0){
					$pesan['messages'] = 'Welcome '.$data['username'];
					if($this->cto_set_session($user)){
						$pesan['status'] = true;
						$pesan['landing_page'] = $this->cto_menu_model->landing_page($data['username']);
					}else{
						$pesan['status'] = false;
						$pesan['messages'] = 'session gagal';
					}
					
					//var_dump($user->row()->username);
				}else{
					$pesan['messages'] = 'Username dan Password salah!';
				}
			}else{
				$pesan['messages'] = 'Login gagal!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function cto_set_session($data){
		//$this->load->library('session');
		$newdata = array(
			'username' => $data->row()->username,
			'name' => $data->row()->name,
			'fk_company_id' => $data->row()->fk_company_id,
			'id' => $data->row()->id,
			'position' => $data->row()->position
		);
		$_SESSION = $newdata;
		if($_SESSION){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function logout(){
		// remove all session variables
		session_unset();

		// destroy the session
		session_destroy(); 
		header('Location: '.base_url());
	}
	
	
	public function create()
	{
		$data['coba'] = 'welcome';
		$this->view('cto_user/cto_form_user', $data);
	}
	
	public function insert()
	{
		$pesan['status'] = false;
		$cto_check = true;
		if ($this->input->post('name') != "" ) {
			$data['name'] = $this->input->post('name');
		}else{
			$pesan['err_name'] = 'Name cannot be empty!';
			$cto_check = false;
		}


		if (true) {
			$data['email'] = $this->input->post('email');
		}else{
			$pesan['err_email'] = 'Email cannot be empty!';
			$cto_check = false;
		}
		
		
		if (true) {
			$data['chat_id_telegram'] = $this->input->post('chat_id_telegram');
		}else{
			$pesan['err_chat_id_telegram'] = 'Chat id telegram cannot be empty!';
			$cto_check = false;
		}
		
		
		if (true) {
			$data['position'] = $this->input->post('position');
		}else{
			$pesan['err_position'] = 'Position cannot be empty!';
			$cto_check = false;
		}
		
		
		if ($this->input->post('fk_company_id') != "" ) {
			$data['fk_company_id'] = $this->input->post('fk_company_id');
		}else{
			$pesan['err_fk_company_id'] = 'Witel cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('username') != "" ) {
			$data['username'] = $this->input->post('username');
		}else{
			$pesan['err_username'] = 'Username cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('password') != "" ) {
			$data['password'] = md5($this->input->post('password'));
		}else{
			$pesan['err_password'] = 'Password cannot be empty!';
			$cto_check = false;
		}

		try{
			if($cto_check){
				if($this->cto_user_model->insert($data)){
					$pesan['messages'] = 'Data is saved';
					$pesan['status'] = true;
				}else{
					$pesan['messages'] = 'Data isn\'t saved!';
				}
			}else{
				$pesan['messages'] = 'Errors!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function edit($id)
	{
		foreach($this->cto_user_model->getAData($id) as $x => $y){
			$data[$x] = $y;
		}
		$data['cto_id'] = $id;
		$this->view('cto_user/cto_form_user', $data);
	}
	
	public function update()
	{
		$pesan['status'] = false;
		$cto_check = true;
		if ($this->input->post('name') != "" ) {
			$data['name'] = $this->input->post('name');
		}else{
			$pesan['err_name'] = 'Name cannot be empty!';
			$cto_check = false;
		}


		if (true) {
			$data['email'] = $this->input->post('email');
		}else{
			$pesan['err_email'] = 'Email cannot be empty!';
			$cto_check = false;
		}
		
		
		if (true) {
			$data['position'] = $this->input->post('position');
		}else{
			$pesan['err_position'] = 'Position cannot be empty!';
			$cto_check = false;
		}
		
		
		if (true) {
			$data['chat_id_telegram'] = $this->input->post('chat_id_telegram');
		}else{
			$pesan['err_chat_id_telegram'] = 'Chat id telegram cannot be empty!';
			$cto_check = false;
		}
		
		
		if ($this->input->post('fk_company_id') != "" ) {
			$data['fk_company_id'] = $this->input->post('fk_company_id');
		}else{
			$pesan['err_fk_company_id'] = 'Witel cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('username') != "" ) {
			$data['username'] = $this->input->post('username');
		}else{
			$pesan['err_username'] = 'Username cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('password') != "" ) {
			$data['password'] = md5($this->input->post('password'));
		}
		// else{
			// $pesan['err_password'] = 'Password cannot be empty!';
			// $cto_check = false;
		// }

		try{
			if($cto_check){
				if($this->cto_user_model->update($this->input->post('id'), $data)){
					$pesan['messages'] = 'Data is saved';
					$pesan['status'] = true;
				}else{
					$pesan['messages'] = 'Data isn\'t saved!';
				}
			}else{
				$pesan['messages'] = 'Errors!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function delete(){
		if(isset($_POST["id"])){
			if($this->cto_user_model->update($_POST["id"], array('is_active' => -1)))
			{
				$pesan['status'] = true;
				$pesan['messages'] = 'User deactivated';
				echo json_encode($pesan);
			}else{
				$pesan['status'] = false;
				$pesan['messages'] = 'Deactivated is failed!';
				echo json_encode($pesan);
			}
		}
	}
	
	public function undelete(){
		if(isset($_POST["id"])){
			if($this->cto_user_model->update($_POST["id"], array('is_active' => 1)))
			{
				$pesan['status'] = true;
				$pesan['messages'] = 'User Reactivated';
				echo json_encode($pesan);
			}else{
				$pesan['status'] = false;
				$pesan['messages'] = 'Reactivated is failed!';
				echo json_encode($pesan);
			}
		}
	}
	
	public function cto_getCompanyDatas(){
		//$data = json_decode(stripslashes($_POST['data']));
		// here i would like use foreach:
		// foreach($data as $d){
			// echo $d;
		// }
		$param = array('is_active' => 1);
		$menupack = $this->cto_user_model->cto_getCompanyDatas($param);
		$value = array();
		foreach($menupack->result() as $mp){
			array_push($value, array($mp->value, $mp->label));
		} 
		echo json_encode($value);
	}
	
	public function cto_getPositionDatas(){
		$param = array();
		$menupack = $this->cto_user_model->cto_getPositionDatas($param);
		$value = array();
		foreach($menupack->result() as $mp){
			array_push($value, array($mp->value, $mp->label));
		} 
		echo json_encode($value);
	}
	
	public function logaccess($fk_user_id)
	{
		$data['fk_user_id'] = $fk_user_id;
		$this->view('cto_user/cto_list_logaccess', $data);
		//$this->view('layouts/content', $data);
		//var_dump($this->get_user_menus('admin'));
	}
	
	public function fetch_logaccess($fk_user_id)
	{
		$keyword = null;
		if(isset($_POST["search"]["value"])){
			$keyword = $_POST["search"]["value"];
		}
		
		$orders = null;
		if(isset($_POST["order"])){
			$orders = array($_POST['order']['0']['column'], $_POST['order']['0']['dir']);
		}
		
		$limit = null;
		if(isset($_POST["length"]) && $_POST["length"] != -1){
			$limit = array(intval($_POST["start"]), intval($_POST['length']));
		}
		
		// var_dump(intval($_POST["draw"]));$var_dump();
		// var_dump(($this->bond_model->nAllData()+0));
		// var_dump($this->bond_model->getData($keyword, $orders, $limit));
		// var_dump($this->bond_model->nData($keyword, $orders, $limit));
		//$arr = $this->bond_model->getData($keyword, $orders, $limit);
		//var_dump(count($arr));
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->cto_user_model->nAllDataLogaccess($fk_user_id),
			"recordsFiltered" => intval($this->cto_user_model->nDataLogaccess($keyword, $orders, $fk_user_id)),
			"data"    => $this->cto_user_model->getDataLogaccess($keyword, $orders, $limit, $fk_user_id)
		);
		
		echo json_encode($output);
	}
}
