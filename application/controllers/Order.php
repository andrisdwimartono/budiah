<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('submission_model');
		$this->load->model('cto_company_model');
	}
	
	public function index()
	{
		header('Location: '.base_url().'order/new_order');
	}
	
	public function new_order($code_id = null)
	{
		$data['coba'] = 'welcome';
		$data['code_id'] = $code_id;
		//if($_SESSION['position'] == 'Manager Optima'){
			$this->view('order/list_new_order', $data);
		//}
		//$this->view('layouts/content', $data);
		//var_dump($_SESSION['position']);
	}
	
	
	public function fetch($code_id = null)
	{
		//if Manager Optima, he can approve the submitted. if the submission were submitted, the submit button will disapear. And the order of submission is not not yet submitted first
		$keyword = null;
		if(isset($_POST["search"]["value"])){
			$keyword = $_POST["search"]["value"];
		}
		
		$keyword = $code_id;
		
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
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataNewOrder(),
			"recordsFiltered" => intval($this->submission_model->nDataNewOrder($keyword, $orders)),
			"data"    => $this->submission_model->getDataNewOrder($keyword, $orders, $limit)
		);
		
		
		echo json_encode($output);
	}
	
	public function approve()
	{
		if($_SESSION['position'] != 'Manager Optima'){
			$pesan['messages'] = 'Only Manager Optima who can approve/disapprove!';
			$pesan['status'] = false;
			echo json_encode($pesan);
			die();
		}
		
		$pesan['status'] = false;
		$cto_check = true;
		$approved_status = "Diapprove!";
		
		if ($this->input->post('id') != "" ) {
			$data['id'] = $this->input->post('id');
		}else{
			$pesan['err_id'] = 'ID cannot be empty!';
			$cto_check = false;
		}
		
		if ($this->input->post('status') != "" ) {
			if($this->input->post('status') == 'true'){
				$data['status'] = 2;
				$approved_status = "Diapprove!";
			}else{
				$data['status'] = -1;
				$approved_status = "Ditolak!";
			}
		}else{
			$pesan['err_status'] = 'Status cannot be empty!';
			$cto_check = false;
		}


		try{
			if($cto_check){	
				$pesan['status'] = true;
				$id = $data['id'];
				$this->submission_model->update($id, $data);
				$pesan['messages'] = $this->submission_model->getAData($id)['code_id']." telah ".$approved_status;
			}else{
				$pesan['fn'] = $this->input->post("fn");
				$pesan['messages'] = 'Errors!';
			}
		}catch(Exception $e){
			$pesan['messages'] = $e->getMessage();
		}
		
		echo json_encode($pesan);
	}
	
	public function status()
	{
		$data['coba'] = 'welcome';
		$this->view('submission/list_submission', $data);
		//$this->view('layouts/content', $data);
		//var_dump($_SESSION['position']);
	}
	
	public function ongoing()
	{
		$data['coba'] = 'welcome';
		$this->view('order/list_ongoing', $data);
		//$this->view('layouts/content', $data);
		//var_dump($_SESSION['position']);
	}
	
	public function fetch_ongoing()
	{
		//if Manager Optima, he can approve the submitted. if the submission were submitted, the submit button will disapear. And the order of submission is not not yet submitted first
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
			"recordsTotal"  =>  (int)$this->submission_model->nAllDataOngoing(),
			"recordsFiltered" => intval($this->submission_model->nDataOngoing($keyword, $orders)),
			"data"    => $this->submission_model->getDataOngoing($keyword, $orders, $limit)
		);
		
		
		echo json_encode($output);
	}
	
	public function create_odp($fk_submission_id){
		foreach($this->submission_model->getAData($fk_submission_id) as $x => $y){
			$data[$x] = $y;
		}
		$data['fk_submission_id'] = $fk_submission_id;
		$this->view('order/form_odp', $data);
	}
	
	public function insert_odp(){
		$pesan['status'] = false;
		$cto_check = true;
		
		if ($this->input->post('fk_submission_id') != "" ) {
			$data['fk_submission_id'] = $this->input->post('fk_submission_id');
		}else{
			$pesan['err_fk_submission_id'] = 'fk_submission_id cannot be empty!';
			$cto_check = false;
		}

		
		if ($this->input->post('label_golive') != "" ) {
			$data['label_golive'] = $this->input->post('label_golive');
		}else{
			$pesan['err_label_golive'] = 'Nama ODP cannot be empty!';
			$cto_check = false;
		}
		
		
		if ($this->input->post('id_deployer') != "" ) {
			$data['id_deployer'] = $this->input->post('id_deployer');
		}else{
			$pesan['err_id_deployer'] = 'ID Deployer cannot be empty!';
			$cto_check = false;
		}
		
		
		try{
			if($cto_check){			
				$pesan['messages'] = 'Data is saved';
				$pesan['status'] = true;
				
				$data['fk_company_id'] = $_SESSION['fk_company_id'];
				//check, if label golive exist in odp_api, send message and save with status_golive
				if($this->submission_model->check_odpapilive($data['label_golive'])){
					$data['status_golive'] = 'GOLIVE';
					$this->submission_model->insert_odp($data);
					//send message
					$this->KirimPerintahCurl($this->submission_model->getAODPDataStatus($data['label_golive']));
				}else{
					$this->submission_model->insert_odp($data);
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
	
	public function getODPPhoto(){
		$fk_submission_id = $this->input->post("data");
		$data = $this->submission_model->getODPPhoto($fk_submission_id);
		echo json_encode($data);
	}
	
	public function cto_removeImg(){
		$fk_odp_id = $this->input->post('fk_odp_id');
		$status = $this->submission_model->getAData($this->input->post('fk_submission_id'))['status'];
		if($this->submission_model->cto_removeImg($fk_odp_id, $status+1)){
			echo "Photo has been deleted!";
		}else{
			echo "Failed to delete!";
		}
	}
	
	public function upload_progress(){
		$filetype = array('jpeg','jpg','png','gif','PNG','JPEG','JPG');
		$fk_odp_id = $this->input->post('fk_odp_id');
		$status = $this->submission_model->getAData($this->input->post('fk_submission_id'))['status'];
		 if ( $_FILES['file']['error'] > 0 ){
				echo 'Error: ' . $_FILES['file']['error'] . '<br>';
			}
			else {
				$file_ext =  pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				if(in_array(strtolower($file_ext), $filetype)){
					if(move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $fk_odp_id."_".$status."_".$_FILES['file']['name'])){
						$this->compressImg('uploads/' . $fk_odp_id."_".$status."_".$_FILES['file']['name']);
						//insert odp_image
						$data = array('fk_odp_id' => $fk_odp_id, 'status' => $status+1, 'img' => $fk_odp_id."_".$status."_".$_FILES['file']['name']);
						$status = $this->submission_model->insertodpimg($data);
						echo "File Uploaded Successfully";
					}else{
						echo "Failed to Upload file";
					}
				}else{
					echo "File extention is not permitted!, there are only 'jpeg','jpg','png','gif','PNG','JPEG','JPG'";
				}
			}
	}
	
	public function compressImg($source) {
		$path = $source;
		$new_width = 5000;
		$new_height = 5000;
		ini_set('memory_limit', '-1');
		$mime = getimagesize($path);

		if($mime['mime']=='image/png') { 
			$src_img = imagecreatefrompng($path);
		}
		if($mime['mime']=='image/jpg' || $mime['mime']=='image/jpeg' || $mime['mime']=='image/pjpeg') {
			$src_img = imagecreatefromjpeg($path);
		}   

		$old_x          =   imageSX($src_img);
		$old_y          =   imageSY($src_img);

		if($old_x > $old_y) 
		{
			$thumb_w    =   $new_width;
			$thumb_h    =   $old_y*($new_height/$old_x);
		}

		if($old_x < $old_y) 
		{
			$thumb_w    =   $old_x*($new_width/$old_y);
			$thumb_h    =   $new_height;
		}

		if($old_x == $old_y) 
		{
			$thumb_w    =   $new_width;
			$thumb_h    =   $new_height;
		}

		$dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 


		// New save location
		$new_thumb_loc = $source;

		if($mime['mime']=='image/png') {
			$result = imagepng($dst_img,$new_thumb_loc,8);
		}
		if($mime['mime']=='image/jpg' || $mime['mime']=='image/jpeg' || $mime['mime']=='image/pjpeg') {
			$result = imagejpeg($dst_img,$new_thumb_loc,80);
		}

		imagedestroy($dst_img); 
		imagedestroy($src_img);

		return $result;
	}
}
