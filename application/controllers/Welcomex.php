<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcomex extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index()
	{
		$data['coba'] = 'welcome';
		$this->view_2('cto_user/login', $data);
	}
}
