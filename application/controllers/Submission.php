<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submission extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('submission_model');
		$this->load->model('cto_company_model');
	}
	
	public function index()
	{
		$data['coba'] = 'welcome';
		$this->view('submission/list_rec_submission', $data);
		//$this->view('layouts/content', $data);
	}
	
	public function fetch_rec()
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
		
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataRec(),
			"recordsFiltered" => intval($this->submission_model->nDataRec($keyword, $orders)),
			"data"    => $this->submission_model->getDataRec($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function sendSubmission()
	{
		$data['fk_submission_id'] = $this->input->post("fk_submission_id");
		$this->view('submission/list_send_submission', $data);
	}
	
	public function fetch_send()
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
		
		$fk_submission_id = "()";
		if(isset($_POST["fk_submission_id"])){
			$fk_submission_id = $_POST["fk_submission_id"];
		}
		
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataSend($fk_submission_id),
			"recordsFiltered" => intval($this->submission_model->nDataSend($keyword, $orders, $fk_submission_id)),
			"data"    => $this->submission_model->getDataSend($keyword, $orders, $limit, $fk_submission_id)
		);
		
		echo json_encode($output);
	}
	
	public function sendRecSubmission()
	{
		try{
			$id = $this->input->post("fk_submission_id");
			$data['status'] = 1;
			if($this->submission_model->updateWhere('id in '.$id, $data)){
				$id = str_replace('(', '', $id);
				$id = str_replace(')', '', $id);
				$pesan['messages'] = $id.' is submitted';
				$pesan['status'] = true;
			}else{
				$pesan['messages'] = 'Error!';
				$pesan['status'] = false;
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function history($code_id = null)
	{
		$data['coba'] = 'welcome';
		$data['code_id'] = $code_id;
		$this->view('submission/list_submission', $data);
	}
	
	
	
	public function fetch($code_id = null)
	{
		$keyword = null;
		if(isset($_POST["search"]["value"])){
			$keyword = $_POST["search"]["value"];
		}
		
		if(isset($code_id) && $code_id != ""){
			$keyword = $code_id;
		}
		
		
		$orders = null;
		if(isset($_POST["order"])){
			$orders = array($_POST['order']['0']['column'], $_POST['order']['0']['dir']);
		}
		
		$limit = null;
		if(isset($_POST["length"]) && $_POST["length"] != -1){
			$limit = array(intval($_POST["start"]), intval($_POST['length']));
		}
		
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataHistory(),
			"recordsFiltered" => intval($this->submission_model->nDataHistory($keyword, $orders)),
			"data"    => $this->submission_model->getDataHistory($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function progress()
	{
		$data['coba'] = 'welcome';
		$this->view('submission/list_submission_progress', $data);
	}
	
	public function fetch_progress()
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
		
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataProgress(),
			"recordsFiltered" => intval($this->submission_model->nDataProgress($keyword, $orders)),
			"data"    => $this->submission_model->getDataProgress($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function golive($code_id = null)
	{
		$data['coba'] = 'welcome';
		$data['code_id'] = $code_id;
		$this->view('submission/list_submission_golive', $data);
	}
	
	public function fetch_golive($code_id = null)
	{
		$keyword = null;
		if(isset($_POST["search"]["value"])){
			$keyword = $_POST["search"]["value"];
		}
		
		if(isset($code_id) && $code_id != ""){
			$keyword = $code_id;
		}
		
		$orders = null;
		if(isset($_POST["order"])){
			$orders = array($_POST['order']['0']['column'], $_POST['order']['0']['dir']);
		}
		
		$limit = null;
		if(isset($_POST["length"]) && $_POST["length"] != -1){
			$limit = array(intval($_POST["start"]), intval($_POST['length']));
		}
		
		$output = array(
			"draw"    => intval($_POST["draw"]),
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataGolive(),
			"recordsFiltered" => intval($this->submission_model->nDataGolive($keyword, $orders)),
			"data"    => $this->submission_model->getDataGolive($keyword, $orders, $limit)
		);
		
		echo json_encode($output);
	}
	
	public function create_new()
	{
		$data['jquery2'] = true;
		$this->view('submission/create_new', $data);
		//$this->view('layouts/content', $data);
	}
	
	public function insert()
	{
		$pesan['status'] = false;
		$cto_check = true;
		
		if ($this->input->post('address') != "" ) {
			$data['address'] = $this->input->post('address');
		}else{
			$pesan['err_address'] = 'Lokasi cannot be empty!';
			$cto_check = false;
		}


		if (true) {
			if (is_numeric($this->input->post('house_hold'))) {
			   if($this->input->post('house_hold') > 0){
				  $data['house_hold'] = $this->input->post('house_hold');
			   }else{
				  $pesan['err_house_hold'] = 'House Hold is must more than 0!';
				  $cto_check = false;
			   }
			}else{        $pesan['err_house_hold'] = 'House Hold is must numeric!';
			   $cto_check = false;
			}
		}else{
		}


		if ($this->input->post('coordinate') != "" ) {
			$data['coordinate'] = $this->input->post('coordinate');
		}else{
			$pesan['err_coordinate'] = 'Tikor Lokasi cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('potentials') != "" ) {
			$data['potentials'] = $this->input->post('potentials');
		}else{
			$pesan['err_potentials'] = 'Calon Pelanggan cannot be empty!';
			$cto_check = false;
		}


		if ($this->input->post('odp') != "" ) {
			if (is_numeric($this->input->post('odp'))) {
			   if($this->input->post('odp') > 0){
				  $data['odp'] = $this->input->post('odp');
			   }else{
				  $pesan['err_odp'] = 'New ODP is must more than 0!';
				  $cto_check = false;
			   }
			}else{       
				$pesan['err_odp'] = 'New ODP is must numeric!';
			   $cto_check = false;
			}
		}else{
			$pesan['err_odp'] = 'New ODP cannot be empty!';
			$cto_check = false;
		}
		
		
		if ($this->input->post('fk_sto_id') != "" ) {
			$data['fk_sto_id'] = $this->input->post('fk_sto_id');
		}else{
			$pesan['err_fk_sto_id'] = 'STO cannot be empty!';
			$cto_check = false;
		}

		
		if (true) {
			$data['sto_name'] = $this->input->post('sto_name');
		}else{
			$pesan['err_sto_name'] = 'STO Name cannot be empty!';
			$cto_check = false;
		}


		try{
			if($cto_check){
				$data["fk_company_id"] = $_SESSION["fk_company_id"];
				$id = $this->submission_model->insertGetID($data);
				if($id){
					$pesan['status'] = true;
					$pesan['id'] = $id;
					
					//giving the code_id
					$id_string = "".$id;
					$len_0 = 4-strlen($id_string);
					$code_id = "SUB";
					for($i = 0; $i < $len_0; $i++){
						$code_id .= "0";
					}
					$code_id .= $id;
					$data2 = array();
					$data2["code_id"] = $code_id;
					$data2["status"] = 1;
					$this->submission_model->update($id, $data2);
					$pesan['code_id'] = $code_id;
					$pesan['fn'] = $this->input->post("fn");
					$pesan['messages'] = 'Data is saved with ID '.$code_id;
					
				}else{
					$pesan['fn'] = $this->input->post("fn");
					$pesan['messages'] = 'Data isn\'t saved!';
				}
			}else{
				$pesan['fn'] = $this->input->post("fn");
				$pesan['messages'] = 'Errors!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function cto_getDatas(){ 
       	$param = array('is_active' => 1); 
       	$selectpack = $this->submission_model->cto_getDatas($param); 
       	$value = array(); 
       	foreach($selectpack->result() as $mp){ 
          	array_push($value, array($mp->value, $mp->label)); 
       	} 
       	echo json_encode($value); 
    }
	
	public function getaSubmission(){ 
		$this->input->post('id_subs');
		$subs = array();
		
		
		
		
		echo json_encode($subs); 
		//var_dump($subs);
	}
	
	
	public function viewSubmission(){
		$id_subs = $this->input->post('fk_submission_id');
		$data['id_subs'] = $id_subs;
		$data['cto_url'] = $this->input->post('cto_url');
		if($this->input->post('is_recomendation') !== null){
			$data['is_recomendation'] = true;
		}
		
		$data['radius'] = $this->cto_company_model->getAData($_SESSION['fk_company_id'])['radius'];
		$data['zoom'] = $this->cto_company_model->getAData($_SESSION['fk_company_id'])['zoom'];
		$this->view('submission/view_submission', $data);
	}
	
	public function cto_getDetailsData(){
		//$data = json_decode(stripslashes($_POST['data']));
		// here i would like use foreach:
		// foreach($data as $d){
			// echo $d;
		// }
		$param = array('is_active' => 1, 'id' => $this->input->post("fk_submission_id"));
		$detail = $this->submission_model->cto_getDetailsData($param);
		$value = array();
		foreach($detail->result() as $mp){
			$value = array('code_id' => $mp->code_id, 'address' => $mp->address, 'house_hold' => $mp->house_hold, 'coordinate' => $mp->coordinate, 'potentials' => $mp->potentials, 'odp' => $mp->odp, 'sto_name' => $mp->sto_name);
		} 
		echo json_encode($value);
	}
	
	public function edit_status($fk_submission_id){
		foreach($this->submission_model->getADataStatus($fk_submission_id) as $x => $y){
			$data[$x] = $y;
		}
		$data['fk_submission_id'] = $fk_submission_id;
		$this->view('order/edit_status', $data);
	}
	
	public function cto_getprogress(){
		$fk_submission_id = $this->input->post("data");
		foreach($this->submission_model->getADataStatus($fk_submission_id) as $x => $y){
			if($x == 'percentage' || $x == 'percentage_onwork'){
				$data[$x] = number_format($y, 2);
			}else{
				$data[$x] = $y;
			}
		}
		
		echo json_encode($data);
	}
	
	public function update_progress(){
		$pesan['status'] = false;
		$cto_check = true;
		
		if ($this->input->post('fk_submission_id') != "" ) {
			$data['fk_submission_id'] = $this->input->post('fk_submission_id');
		}else{
			$pesan['err_fk_submission_id'] = 'fk_submission_id cannot be empty!';
			$cto_check = false;
		}

		try{
			if($cto_check){
				if(empty($this->submission_model->checkImageODP($data['fk_submission_id']))){
					if($this->submission_model->update_progress($data['fk_submission_id'])){
						$pesan['messages'] = 'Data is saved';
						$pesan['status'] = true;
						//check, if status to be ODP Live, then update the ODP and send message
						if($this->submission_model->getADataStatus($data['fk_submission_id'])['percentage'] >= 100){
							$this->submission_model->odp_togolive($data['fk_submission_id']);
							
							//send message
							$this->KirimPerintahCurl($this->submission_model->getADataStatus($data['fk_submission_id']));
						}
					}
				}else{
					$pesan['status'] = false;
					$pesan['messages'] = 'Failed to update!, Upload all ODP images!';
				}
			}else{
				$pesan['status'] = false;
				$pesan['messages'] = 'Errors!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function log_transaksi()
	{
		foreach($this->submission_model->getADataStatusLogHistory($this->input->post("fk_submission_id")) as $x => $y){
			$data[$x] = $y;
		}
		$data['fk_submission_id'] = $this->input->post("fk_submission_id");
		$this->view('submission/log_transaksi', $data);
	}
	
	public function get_log_transaksi(){
		$fk_submission_id = $this->input->post("data");
		foreach($this->submission_model->getDataLogHistory($fk_submission_id) as $x => $y){
			$data[$x] = $y;
		}
		
		echo json_encode($data);
	}
	
	public function getODPPhotoStatus(){
		$fk_submission_id = $this->input->post("fk_submission_id");
		$status = $this->input->post("status");
		$data = $this->submission_model->getODPPhotoStatus($fk_submission_id, $status);
		echo json_encode($data);
	}
	
	public function KirimPerintahCurl($data){
		$comp = $this->cto_company_model->getAData($_SESSION['fk_company_id']);
		//$data['chat_id'] = $chat_group_id;
		$chatid = $comp['group_telegram_id'];
		$pesan 	= "ODP No ".$data['LABEL_GOLIVE']." sudah live!"."\nAlamat : ".$data['address']."\nTikor : ".$data['coordinate'];
		// ----------- code -------------
		
		$TOKEN = $comp['token_telegram'];
		
		$method	= "sendMessage";
		$url    = "https://api.telegram.org/bot" . $TOKEN . "/". $method;
		$post = [
		 'chat_id' => $chatid,
		 // 'parse_mode' => 'HTML', // aktifkan ini jika ingin menggunakan format type HTML, bisa juga diganti menjadi Markdown
		 'text' => $pesan
		];
		$header = [
		 "X-Requested-With: XMLHttpRequest",
		 "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36" 
		];
		// hapus 1 baris ini:
		//die('Hapus baris ini sebelum bisa berjalan, terimakasih.');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_REFERER, $refer);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post );   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$datas = curl_exec($ch);
		$error = curl_error($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$debug['text'] = $pesan;
		$debug['code'] = $status;
		$debug['status'] = $error;
		$debug['respon'] = json_decode($datas, true);
 
        return $datas;
    }
	
	
}
