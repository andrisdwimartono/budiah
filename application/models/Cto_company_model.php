<?php
class Cto_company_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'cto_company';
	
	public function getAData($id){
        $query = $this->db->query('select * from '.$this->_table_name.' where id = '.$id.' limit 1');
		$result = array();
		foreach($query->result() as $row){
			$result['name'] = $row->name;
			$result['token_telegram'] = $row->token_telegram;
			$result['group_telegram_id'] = $row->group_telegram_id;
			$result['radius'] = $row->radius;
			$result['zoom'] = $row->zoom;
		}
		return $result;
    }
}