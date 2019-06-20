<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cto_menu extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('cto_menu_model');
		$this->load->model('cto_menupackage_model');
	}
	
	public function index()
	{
		$data['coba'] = 'welcome';
		$this->view('cto_menu/cto_list_menu', $data);
		//$this->view('layouts/content', $data);
		
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
			"recordsTotal"  =>  (int)$this->cto_menu_model->nAllData(),
			"recordsFiltered" => intval($this->cto_menu_model->nData($keyword, $orders)),
			"data"    => $this->cto_menu_model->getData($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function create()
	{
		$data['coba'] = 'welcome';
		$this->view('cto_menu/cto_form_menu', $data);
	}
	
	public function insert()
	{
		$pesan['status'] = false;
		$cto_check = true;
		if ($this->input->post('controller') != "" ) {
			$data['controller'] = $this->input->post('controller');
		}else{
			$pesan['err_controller'] = 'Controller cannot be empty!';
			$cto_check = false;
		}


		if (true) {
			$data['method'] = $this->input->post('method');
		}else{
			$pesan['err_method'] = 'Method cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('path') != "" ) {
			$data['path'] = $this->input->post('path');
		}else{
			$pesan['err_path'] = 'Path cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('name') != "" ) {
			$data['name'] = $this->input->post('name');
		}else{
			$pesan['err_name'] = 'Name cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('fk_menupackage_id') != "" ) {
			$data['fk_menupackage_id'] = $this->input->post('fk_menupackage_id');
		}else{
			$pesan['err_fk_menupackage_id'] = 'Package Menu cannot be empty!';
			$cto_check = false;
		}

		if (true) {
			$data['menupackage_name'] = $this->input->post('menupackage_name');
		}else{
			$pesan['err_menupackage_name'] = 'menupackage_name cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('sequence') != "" ) {
			if (is_numeric($this->input->post('sequence'))) {
			   if($this->input->post('sequence') > 0){
				  $data['sequence'] = $this->input->post('sequence');
			   }else{
				  $pesan['err_sequence'] = 'Sequence Number is must more than 0!';
				  $cto_check = false;
			   }
			}else{        $pesan['err_sequence'] = 'Sequence Number is must numeric!';
			   $cto_check = false;
			}
		}else{
			$pesan['err_sequence'] = 'Sequence Number cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('is_shown') != "" ) {
			$data['is_shown'] = $this->input->post('is_shown');
		}else{
			$pesan['err_is_shown'] = 'Show This Menu on Left Pane cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('is_active') != "" ) {
			$data['is_active'] = $this->input->post('is_active');
		}else{
			$pesan['err_is_active'] = 'Active cannot be empty!';
			$cto_check = false;
		}


		try{
			if($cto_check){
				if($this->cto_menu_model->insert($data)){
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
		foreach($this->cto_menu_model->getAData($id) as $x => $y){
			$data[$x] = $y;
		}
		$data['cto_id'] = $id;
		$this->view('cto_menu/cto_form_menu', $data);
	}
	
	public function update()
	{
		$pesan['status'] = false;
		$cto_check = true;
		if ($this->input->post('controller') != "" ) {
			$data['controller'] = $this->input->post('controller');
		}else{
			$pesan['err_controller'] = 'Controller cannot be empty!';
			$cto_check = false;
		}


		if (true) {
			$data['method'] = $this->input->post('method');
		}else{
			$pesan['err_method'] = 'Method cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('path') != "" ) {
			$data['path'] = $this->input->post('path');
		}else{
			$pesan['err_path'] = 'Path cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('name') != "" ) {
			$data['name'] = $this->input->post('name');
		}else{
			$pesan['err_name'] = 'Name cannot be empty!';
			$cto_check = false;
		}
		
		
		if ($this->input->post('fk_menupackage_id') != "" ) {
			$data['fk_menupackage_id'] = $this->input->post('fk_menupackage_id');
		}else{
			$pesan['err_fk_menupackage_id'] = 'Package Menu cannot be empty!';
			$cto_check = false;
		}

		if (true) {
			$data['menupackage_name'] = $this->input->post('menupackage_name');
		}else{
			$pesan['err_menupackage_name'] = 'menupackage_name cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('sequence') != "" ) {
			if (is_numeric($this->input->post('sequence'))) {
			   if($this->input->post('sequence') > 0){
				  $data['sequence'] = $this->input->post('sequence');
			   }else{
				  $pesan['err_sequence'] = 'Sequence Number is must more than 0!';
				  $cto_check = false;
			   }
			}else{        $pesan['err_sequence'] = 'Sequence Number is must numeric!';
			   $cto_check = false;
			}
		}else{
			$pesan['err_sequence'] = 'Sequence Number cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('is_shown') != "" ) {
			$data['is_shown'] = $this->input->post('is_shown');
		}else{
			$pesan['err_is_shown'] = 'Show This Menu on Left Pane cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('is_active') != "" ) {
			$data['is_active'] = $this->input->post('is_active');
		}else{
			$pesan['err_is_active'] = 'Active cannot be empty!';
			$cto_check = false;
		}

		try{
			if($cto_check){
				if($this->cto_menu_model->update($this->input->post('id'), $data)){
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
			if($this->cto_menu_model->update($_POST["id"], array('is_active' => -1)))
			{
				$pesan['status'] = true;
				$pesan['messages'] = 'Deactivated';
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
			if($this->cto_menu_model->update($_POST["id"], array('is_active' => 1)))
			{
				$pesan['status'] = true;
				$pesan['messages'] = 'Reactivated';
				echo json_encode($pesan);
			}else{
				$pesan['status'] = false;
				$pesan['messages'] = 'Reactivated is failed!';
				echo json_encode($pesan);
			}
		}
	}
	
	public function cto_getDatas(){
		//$data = json_decode(stripslashes($_POST['data']));
		// here i would like use foreach:
		// foreach($data as $d){
			// echo $d;
		// }
		$param = array('is_active' => 1);
		$menupack = $this->cto_menupackage_model->cto_getDatas($param);
		$value = array();
		foreach($menupack->result() as $mp){
			array_push($value, array($mp->value, $mp->label));
		} 
		echo json_encode($value);
	}
}
