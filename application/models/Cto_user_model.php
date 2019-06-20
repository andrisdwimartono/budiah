<?php
class Cto_user_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'cto_user';
	
	protected $_cto_columns = array('name', 'email', 'position', 'fk_company_id', 'username', 'is_active');
	public function login_validating($data){
        $query = $this->db->query("SELECT * FROM ".$this->_table_name." cu WHERE cu.username = '".$data["username"]."' AND cu.password = '".$data["password"]."'");
		return $query;
    }
	
	public function nData($keyword = null, $orders = null){
		$querying = 'select count(*) as ndata from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where name like "%'.$keyword.'%" or username like "%'.$keyword.'%" or position like "%'.$keyword.'%" or email like "%'.$keyword.'%"';
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
		select id, name, email, position, username, case when is_active = 1 then \'Active\' else \'Deactive\' end AS is_active from '.$this->_table_name;
		
        if ($keyword != null){
            $querying .= ' where name like "%'.$keyword.'%" or username like "%'.$keyword.'%" or position like "%'.$keyword.'%" or email like "%'.$keyword.'%"';
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
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="email">' . $row->email . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="position">' . $row->position . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="username">' . $row->username . '</div>';
			
			
			if($row->is_active == "Active"){
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: green;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a type="button" name="delete" class="btn lime"><li class="btn btn-sm delete fa fa-trash" id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Deactivate this!"></li></a><br><a href="cto_user/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-sm fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></li></a><br><a href="cto_user_menu/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-sm fa fa-bars" data-toggle="tooltip" data-placement="top" title="Grant Access!"></li></a><br><a href="cto_user/logaccess/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-sm fa fa-history" data-toggle="tooltip" data-placement="top" title="Log Access!"></li></a>';
			}else{
				$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="is_active"><i class="fa fa-circle green" style="color: red;">' . $row->is_active . '<i></div>';
				$sub_array[] = '<a type="button" name="delete" class="btn lime"><li class="btn btn-sm undelete fa fa-medkit" id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Reactivate this!"></li></a></a><br><a href="cto_user/edit/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-sm fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></li></a><br><a href="cto_user/logaccess/'.$row->id.'" name="Edit" class="btn lime" id="'.$row->id.'"><li class="btn btn-sm fa fa-history" data-toggle="tooltip" data-placement="top" title="Log Access!"></li></a>';
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
			$result['email'] = $row->email;
			$result['position'] = $row->position;
			$result['chat_id_telegram'] = $row->chat_id_telegram;
			$result['fk_company_id'] = $row->fk_company_id;
			$result['username'] = $row->username;
		}
		return $result;
    }
	
	function cto_getCompanyDatas(){
		$query = $this->db->query('select id as value, name as label from cto_company where is_active != -1');
		return $query;
	}
	
	function cto_getPositionDatas(){
		$query = $this->db->query('SELECT dict.code value, dict.name label FROM cto_dict dict WHERE dict.type = \'POS_USER\'');
		return $query;
	}
	
	function insert_log($fk_user_id, $username, $ip_address, $fk_menu_id, $controller, $method){
		$query = $this->db->query('INSERT INTO `cto_log`(`fk_user_id`, `username`, `ip_address`, `fk_menu_id`, `controller`, `method`) VALUES ('.$fk_user_id.',\''.$username.'\',\''.$ip_address.'\','.$fk_menu_id.',\''.$controller.'\',\''.$method.'\')');
		return $query;
	}
	
	public function nAllDataLogaccess($fk_user_id){
		$query = $this->db->query('select count(*) as n_data from cto_log logg
		where logg.fk_user_id = '.$fk_user_id);
		return $query->row()->n_data;
	}
	
	public function nDataLogaccess($keyword = null, $orders = null, $fk_user_id = null){
		$querying = 'select count(*) as ndata from cto_log logg
		left join cto_menu menu on menu.id = logg.fk_menu_id WHERE logg.fk_user_id = '.$fk_user_id;
		
        if ($keyword != null){
            $querying .= ' and (logg.username like "%'.$keyword.'%" or logg.controller like "%'.$keyword.'%" or logg.method like "%'.$keyword.'%" or menu.name like "%'.$keyword.'%" or logg.ip_address like "%'.$keyword.'%")';
        }
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by logg.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by logg.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataLogaccess($keyword = null, $orders = null, $limit = null, $fk_user_id = null){
		$querying = '
		select logg.id, logg.username, logg.ip_address, menu.name fk_menu_id, logg.controller, logg.method, logg.time from cto_log logg
		left join cto_menu menu on menu.id = logg.fk_menu_id WHERE logg.fk_user_id = '.$fk_user_id;
		
        if ($keyword != null){
            $querying .= ' and (logg.username like "%'.$keyword.'%" or logg.controller like "%'.$keyword.'%" or logg.method like "%'.$keyword.'%" or menu.name like "%'.$keyword.'%" or logg.ip_address like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by logg.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by logg.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="username">'.$row->username . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="ip_address">'.$row->ip_address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_menu_id">' . $row->fk_menu_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="controller">' . $row->controller . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="method">' . $row->method . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="time">' . $row->time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
}