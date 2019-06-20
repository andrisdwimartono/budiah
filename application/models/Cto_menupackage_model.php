<?php
class Cto_menupackage_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'cto_menupackage';
	
	protected $_cto_columns = array('name', 'sequence', 'is_active');
	
	public function nData($keyword = null, $orders = null){
		$querying = 'select count(*) as ndata from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where name like "%'.$keyword.'%"';
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
		select id, name, sequence, case when is_active = 1 then \'Active\' else \'Deactive\' end AS is_active from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where name like "%'.$keyword.'%"';
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
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="name">'.$row->name . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="sequence">' . $row->sequence . '</div>';
			
			
			if($row->is_active == "Active"){
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: green;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a href="cto_menupackage/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-xs fa fa-edit"></li></a>';
			}else{
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: red;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a href="cto_menupackage/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-xs fa fa-edit"></li></a>';
			}
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function getAData($id){
        $query = $this->db->query('select * from '.$this->_table_name.' where id = '.$id.' limit 1');
		$result = array();
		foreach($query->result() as $row){
			$result['name'] = $row->name;
			$result['sequence'] = $row->sequence;
			$result['is_active'] = $row->is_active;
		}
		return $result;
    }
	
	function cto_getDatas($param){
		$this->db->select("id AS value, name AS label");
		$this->db->from($this->_table_name);
		$this->db->where($param);
		$this->db->order_by('sequence');
		$query = $this->db->get();
		return $query;
	}
}