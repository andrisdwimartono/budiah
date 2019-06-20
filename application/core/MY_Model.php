<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getVolume($where = null){
        $this->db->select_sum('quantity');
        if ($where != null){
            $this->db->where($where);
        }
        $query = $this->db->get($this->_table_name);
        return $query->row();
    }

	// public function bigTen(){
 //    }

 //    public function allSumarize(){
    	
 //    }

    public function nAllData(){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name);
		return $query->row()->n_data;
	}
	
	public function update($id, $data){
		if(empty($data['updated_by'])){
			$data['updated_by'] = $_SESSION['id'];
		}
		$this->db->where('id', $id);
		$this->db->update($this->_table_name,$data);
		
		return $this->db->affected_rows() ? true : false;
	}
	
	public function updateWhere($where, $data){
		if(empty($data['updated_by'])){
			$data['updated_by'] = $_SESSION['id'];
		}
		$this->db->where($where);
		$this->db->update($this->_table_name,$data);
		
		return $this->db->affected_rows() ? true : false;
	}
	
	public function insert($data){
		if(empty($data['created_by'])){
			$data['created_by'] = $_SESSION['id'];
		}
		$this->db->insert($this->_table_name,$data);
		return $this->db->affected_rows() ? true : false;
	}
	
	public function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->_table_name);
		return $this->db->affected_rows() ? true : false;
	}

	public function deleteWhere($where){
		$this->db->where($where);
		$this->db->delete($this->_table_name);
		return $this->db->affected_rows() ? true : false;
	}

	public function insertGetID($data){
		if(empty($data['created_by'])){
			$data['created_by'] = $_SESSION['id'];
		}
		$this->db->insert($this->_table_name,$data);
		return $this->db->insert_id();
	}
	
	public function getDataWhere($where){
		$this->db->select("*");
		$this->db->from($this->_table_name);
		$this->db->where($where);
		$query = $this->db->get();
		return $query;
	}
	
	public function getDataWhereArr($where, $order = null){
		$this->db->select("*");
		$this->db->from($this->_table_name);
		$this->db->where($where);
		if(isset($order)){
			$this->db->order_by($order);
		}
		$query = $this->db->get();
		return $query->result_array();
	}
	
}