<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cto_user_menu extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('cto_menu_model');
		$this->load->model('cto_menupackage_model');
		$this->load->model('cto_user_menu_model');
		$this->load->model('cto_user_model');
	}
	
	public function edit($id)
	{
		$data['ctovar_menupackage'] = $this->cto_menupackage_model->getDataWhereArr(array("is_active"=>1), "sequence");
		$data['ctovar_menu'] = $this->cto_menu_model->getDataWhereArr(array("is_active"=>1));
		//$data['ctovar_user_menu'] = $this->cto_user_menu_model->getDataWhereArr(array("is_active"=>1, "fk_user_id => ".$id));
		
		$data['cto_id'] = $id;
		$this->view('cto_user_menu/cto_form_user_menu', $data);
	}
	
	public function update()
	{
		$user_id = $this->input->post('id');
		$user = $this->cto_user_model->getDataWhereArr(array('username' => $_SESSION['username']));
		$pesan['status'] = true;
		$pesan['messages'] = 'Data is saved!';
		$cto_check = true;
		
		foreach($this->cto_menu_model->getDataWhereArr(array("is_active"=>1)) as $menu){
			
			$is_active = -1;
			//get menu is_active data from view
			if ($this->input->post('ctof_menu_'.$menu['id']) != null && $this->input->post('ctof_menu_'.$menu['id']) != "" ) {
				$is_active = (int)$this->input->post('ctof_menu_'.$menu['id']);
			}
			
			//check is user menu exist for this user. If no, insert it
			if(count($this->cto_user_menu_model->getDataWhereArr(array("fk_user_id" => $user_id, 'fk_menu_id' => $menu["id"]))) <= 0){
				if(!$this->cto_user_menu_model->insert(array('fk_user_id' => $user_id, 'username' => $user[0]['username'], 'fk_menu_id' => $menu['id'], 'menu_name' => $menu['name'], 'is_active' => $is_active))){
					$pesan['messages'] = 'Data isn\'t saved!';
					$pesan['status'] = false;
				}
			}else{
			//user menu is exist,just update it
				$this->cto_user_menu_model->updateWhere(array('fk_user_id' => $user_id, 'fk_menu_id' => $menu['id']), array('menu_name' => $menu['name'], 'is_active' => $is_active));
			}
		}

		echo json_encode($pesan);
	}
	
	// public function cto_getDatas(){
		// //$data = json_decode(stripslashes($_POST['data']));
		// // here i would like use foreach:
		// // foreach($data as $d){
			// // echo $d;
		// // }
		// $param = array('is_active' => 1);
		// $menupack = $this->cto_menupackage_model->cto_getDatas($param);
		// $value = array();
		// foreach($menupack->result() as $mp){
			// array_push($value, array($mp->value, $mp->label));
		// } 
		// echo json_encode($value);
	// }
	
	public function cto_GetDataUserMenu(){
		if ($this->input->post('data') != "" ) {
			$id = $this->input->post('data');
		}
		$usermenu = $this->cto_user_menu_model->getDataWhereArr(array("is_active" => 1, "fk_user_id" => (int)$id));
		$value = array();
		foreach($usermenu as $um){
			array_push($value, '#ctof_menu_'.$um["fk_menu_id"]);
		} 
		echo json_encode($value);
	}
}
