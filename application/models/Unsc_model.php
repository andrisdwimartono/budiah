<?php
class Unsc_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'b_unsc';
	protected $_cto_columns = array('fk_submission_id', 'cust_name', 'cust_address', 'package', 'cust_coordinate');
	
	
	public function nData($keyword = null, $orders = null){
		$querying = 'select count(*) as ndata from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where controller like "%'.$keyword.'%" OR COALESCE(method, \'index\') like "%'.$keyword.'%" OR path like "%'.$keyword.'%" OR name like "%'.$keyword.'%" OR menupackage_name like "%'.$keyword.'%"';
        }
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by '.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getData($keyword = null, $orders = null, $limit = null){
		$querying = '
		select id, controller, method, path, name, fk_menupackage_id, menupackage_name, sequence, case when is_shown = 1 then \'Shown\' else \'Hidden\' end AS is_shown, case when is_active = 1 then \'Active\' else \'Deactive\' end AS is_active from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where controller like "%'.$keyword.'%" OR COALESCE(method, \'index\') like "%'.$keyword.'%" OR path like "%'.$keyword.'%" OR name like "%'.$keyword.'%" OR menupackage_name like "%'.$keyword.'%"';
        }
		//echo ''.$querying;
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by '.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="controller">'.$row->controller . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="method">'.$row->method . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="path">'.$row->path . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="name">'.$row->name . '</div>';
			//$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_menupackage_id">'.$row->fk_menupackage_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="menupackage_name">'.$row->menupackage_name . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="sequence">'.$row->sequence . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_shown">'.$row->is_shown . '</div>';
			
			
			if($row->is_active == "Active"){
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: green;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a href="cto_menu/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-xs fa fa-edit"></li></a>';
			}else{
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: red;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a href="cto_menu/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-xs fa fa-edit"></li></a>';
			}
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function getAData($id){
        $query = $this->db->query('select * from '.$this->_table_name.' where id = '.$id.' limit 1');
		$result = array();
		foreach($query->result() as $row){
			$result['controller'] = $row->controller;
			$result['method'] = $row->method;
			$result['path'] = $row->path;
			$result['name'] = $row->name;
			$result['fk_menupackage_id'] = $row->fk_menupackage_id;
			$result['menupackage_name'] = $row->menupackage_name;
			$result['sequence'] = $row->sequence;
			$result['is_shown'] = $row->is_shown;
			$result['is_active'] = $row->is_active;
		}
		return $result;
    }
	
	function cto_getDetailsDatas($param){
		$query = $this->db->query('select unsc.cust_name, unsc.cust_address, unsc.package, unsc.cust_coordinate from b_unsc unsc
		where unsc.fk_submission_id = '.$param["fk_submission_id"].' and unsc.is_active = '.$param["is_active"].'');
		return $query;
	}
	
	function cto_getPackageDatasAutoc($param){
		$param["keyword"] = strtoupper($param["keyword"]);
		$query = $this->db->query('select * from b_list_package where UPPER(name) like "%'.$param["keyword"].'%" and is_active = '.$param["is_active"].' limit 15');
		return $query;
	}
	
	function cto_getCustAddressDatasAutoc($param){
		$param["keyword"] = strtoupper($param["keyword"]);
		$query = $this->db->query('select * from b_list_jalan where UPPER(name) like "%'.$param["keyword"].'%" and is_active = '.$param["is_active"].' limit 15');
		return $query;
	}
}