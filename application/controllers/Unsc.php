<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unsc extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('unsc_model');
		$this->load->model('submission_model');
	}
	
	public function index()
	{
		$data['coba'] = 'welcome';
		$this->view('cto_menu/cto_list_menu', $data);
		//$this->view('layouts/content', $data);
		
	}
	
	public function create_form(){
		$num = $this->input->post('unsc_num');
		$fk_submission_id = null;
		if ($this->input->post('fk_submission_id') != "" ) {
			$fk_submission_id = $this->input->post('fk_submission_id');
		}
		
		$forms = null;
		for($i = 1; $i <= $num; $i++){
$forms .= "<div class=\"form-group\" id=\"ctof_fk_submission_id_".$i."\">\n<input id=\"fk_submission_id\" type=\"hidden\" name=\"fk_submission_id\" class=\"form-control\" placeholder=\"submission\">\n<span class=\"help-block\" id=\"ctomesserror_fk_submission_id\"></span>
<label for=\"cust_name\" class=\"col-sm-1 control-label\">Cust.Name<font style=\"color:red;\">*</font></label>
<div class=\"col-sm-2\">
<input id=\"cust_name\" type=\"text\" name=\"cust_name\" class=\"form-control\" placeholder=\"Taufani Kurniawan\">
<span class=\"help-block\" id=\"ctomesserror_cust_name\"></span>
</div>
<label for=\"cust_address\" class=\"col-sm-1 control-label\">Address<font style=\"color:red;\">*</font></label>
<div class=\"col-sm-2\">
<input id=\"cust_address\" type=\"text\" name=\"cust_address\" class=\"form-control\" placeholder=\"Jl. Soekarno Hatta 17\">
<span class=\"help-block\" id=\"ctomesserror_cust_address\"></span>
</div>
<label for=\"package\" class=\"col-sm-1 control-label\">Paket<font style=\"color:red;\">*</font></label>
<div class=\"col-sm-2\">
<input id=\"package\" type=\"text\" name=\"package\" class=\"form-control\" placeholder=\"IndiHome 10 MBPS\">
<span class=\"help-block\" id=\"ctomesserror_package\"></span>
</div>
<label for=\"cust_coordinate\" class=\"col-sm-1 control-label\">Tikor<font style=\"color:red;\">*</font></label>
<div class=\"col-sm-2\">
<input id=\"cust_coordinate\" type=\"text\" name=\"cust_coordinate\" class=\"form-control\" placeholder=\"17.991, 37.990\">
<span class=\"help-block\" id=\"ctomesserror_cust_coordinate\"></span>
</div>
</div><br><br>";
			// echo "&lt;div class="form-group\" id=\"ctof_fk_submission_id\"&gt;
				// &lt;input id=\"fk_submission_id\" type=\"hidden\" name=\"fk_submission_id\" class=\"form-control\" placeholder=\"submission\"&gt;
				// &lt;span class=\"help-block\" id=\"ctomesserror_fk_submission_id\"&gt;&lt;/span&gt;
				
				// &lt;label for=\"cust_name\" class=\"col-sm-1 control-label\"&gt;Cust.Name&lt;font style=\"color:red;\"&gt;*&lt;/font&gt;&lt;/label&gt;
				// &lt;div class=\"col-sm-2\"&gt;
					// &lt;input id=\"cust_name\" type=\"text\" name=\"cust_name\" &lt;?php if(isset($cust_name)){echo \"value=\\"\".$cust_name.\"\\"\";} ?&gt; class=\"form-control\" placeholder=\"Taufani Kurniawan\"&gt;
					// &lt;span class=\"help-block\" id=\"ctomesserror_cust_name\"&gt;&lt;/span&gt;
				// &lt;/div&gt;
				
				// &lt;label for=\"cust_address\" class=\"col-sm-1 control-label\"&gt;Address&lt;font style=\"color:red;\"&gt;*&lt;/font&gt;&lt;/label&gt;
				// &lt;div class=\"col-sm-2\"&gt;
					// &lt;input id=\"cust_address\" type=\"text\" name=\"cust_address\" &lt;?php if(isset($cust_address)){echo \"value=\\"\".$cust_address.\"\\"\";} ?&gt; class=\"form-control\" placeholder=\"Jl. Soekarno Hatta 17\"&gt;
					// &lt;span class=\"help-block\" id=\"ctomesserror_cust_address\"&gt;&lt;/span&gt;
				// &lt;/div&gt;
				
				// &lt;label for=\"package\" class=\"col-sm-1 control-label\"&gt;Paket&lt;font style=\"color:red;\"&gt;*&lt;/font&gt;&lt;/label&gt;
				// &lt;div class=\"col-sm-2\"&gt;
					// &lt;input id=\"package\" type=\"text\" name=\"package\" &lt;?php if(isset($package)){echo \"value=\\"\".$package.\"\\"\";} ?&gt; class=\"form-control\" placeholder=\"IndiHome 10 MBPS\"&gt;
					// &lt;span class=\"help-block\" id=\"ctomesserror_package\"&gt;&lt;/span&gt;
				// &lt;/div&gt;
				
				// &lt;label for=\"cust_coordinate\" class=\"col-sm-1 control-label\"&gt;Tikor&lt;font style=\"color:red;\"&gt;*&lt;/font&gt;&lt;/label&gt;
				// &lt;div class=\"col-sm-2\"&gt;
					// &lt;input id=\"cust_coordinate\" type=\"text\" name=\"cust_coordinate\" &lt;?php if(isset($cust_coordinate)){echo \"value=\\"\".$cust_coordinate.\"\\"\";} ?&gt; class=\"form-control\" placeholder=\"17.991, 37.990\"&gt;
					// &lt;span class=\"help-block\" id=\"ctomesserror_cust_coordinate\"&gt;&lt;/span&gt;
				// &lt;/div&gt;
			// &lt;/div&gt;";
		}
		echo json_encode($forms);
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
		if (true) {
			$data['fk_submission_id'] = $this->input->post('fk_submission_id');
		}else{
			$pesan['err_fk_submission_id'] = 'fk_submission_id cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('cust_name') != "" ) {
			$data['cust_name'] = $this->input->post('cust_name');
		}else{
			$pesan['err_cust_name'] = 'Cust Name cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('cust_address') != "" ) {
			$data['cust_address'] = $this->input->post('cust_address');
		}else{
			$pesan['err_cust_address'] = 'Address cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('package') != "" ) {
			$data['package'] = $this->input->post('package');
		}else{
			$pesan['err_package'] = 'Paket cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('cust_coordinate') != "" ) {
			$data['cust_coordinate'] = $this->input->post('cust_coordinate');
		}else{
			$pesan['err_cust_coordinate'] = 'Tikor cannot be empty!';
			$cto_check = false;
		}


		try{
			if($cto_check){
				$data['is_active'] = 1;
				$data['status'] = 1;
				$data["fk_company_id"] = $_SESSION["fk_company_id"];
				if($this->unsc_model->insert($data)){
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
			if($this->unsc_model->update($_POST["id"], array('is_active' => -1)))
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
			if($this->unsc_model->update($_POST["id"], array('is_active' => 1)))
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
	
	public function cto_getDetailsDatas(){
		//$data = json_decode(stripslashes($_POST['data']));
		// here i would like use foreach:
		// foreach($data as $d){
			// echo $d;
		// }
		$param = array('is_active' => 1, 'fk_submission_id' => $this->input->post("fk_submission_id"));
		$detail = $this->unsc_model->cto_getDetailsDatas($param);
		$value = array();
		foreach($detail->result() as $mp){
			array_push($value, array($mp->cust_name, $mp->cust_address, $mp->package, $mp->cust_coordinate));
		} 
		echo json_encode($value);
	}
	
	public function cto_getDetailsData(){
		$param = array('is_active' => 1, 'fk_submission_id' => $this->input->post("fk_submission_id"));
		$detail = $this->unsc_model->cto_getDetailsDatas($param);
		$value = array();
		foreach($detail->result() as $mp){
			array_push($value, array('cust_name' => $mp->cust_name, 'cust_address' => $mp->cust_address, 'package' => $mp->package, 'cust_coordinate' => $mp->cust_coordinate));
		} 
		echo json_encode($value);
	}
	
	public function get_autocomplete_package(){
		$param = array('is_active' => 1, 'keyword' => $this->input->post("term"));
		$menupack = $this->unsc_model->cto_getPackageDatasAutoc($param);
		$value = array();
		foreach($menupack->result() as $mp){
			array_push($value, $mp->name);
		} 
		echo json_encode($value);
	}
	
	public function get_autocomplete_cust_address(){
		$param = array('is_active' => 1, 'keyword' => $this->input->post("term"));
		$menupack = $this->unsc_model->cto_getCustAddressDatasAutoc($param);
		$value = array();
		foreach($menupack->result() as $mp){
			array_push($value, $mp->name);
		} 
		echo json_encode($value);
	}
	
	public function get_api()
	{
		$data['coba'] = 'welcome';
		$this->view_free('unsc/get_api', $data);
		//$this->view('layouts/content', $data);
	}
	
	public function test()
	{
		header("content-type: application/javascript");

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
		
		

		echo $output;
		
		//$this->view('layouts/content', $data);
	}
}
