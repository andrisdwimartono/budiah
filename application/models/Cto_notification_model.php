<?php
class Cto_notification_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'cto_notification';
	
	protected $_cto_columns = array('position', 'url', 'is_watched', 'watched_by', 'is_peeked');
	
	public function getAData($id){
        $query = $this->db->query('select * from '.$this->_table_name.' where id = '.$id.' and fk_company_id = '.$_SESSION['fk_company_id'].' limit 1');
		$result = array();
		foreach($query->result() as $row){
			$result['position'] = $row->position;
			$result['url'] = $row->url;
			$result['is_watched'] = $row->is_watched;
			$result['watched_by'] = $row->watched_by;
			$result['is_peeked'] = $row->is_peeked;
			$result['fk_company_id'] = $row->fk_company_id;
		}
		return $result;
    }
	
	public function getAllData($position){
        $query = $this->db->query("select notif.id, notif.position, notif.type, dict.name type_name, dict_icon.name type_icon, subs.code_id, usr.name executorname, notif.url, notif.is_watched, notif.watched_by, notif.is_peeked, notif.fk_company_id from ".$this->_table_name." notif 
		left join cto_dict dict on dict.code = notif.type and dict.type = 'NOTIF_TYPE'
		left join cto_dict dict_icon on dict_icon.code = notif.type and dict_icon.type = 'NOTIF_ICON'
		left join b_submission subs on subs.id = notif.table_id and notif.table_name = 'b_submission'
		left join cto_user usr on notif.fk_user_id = usr.id
		where notif.position = '".$position."' and notif.fk_company_id = ".$_SESSION['fk_company_id']." and notif.is_watched = 0 order by notif.created_time desc, notif.is_watched asc");
		$results = array();
		foreach($query->result() as $row){
			$result = array();
			$result['id'] = $row->id;
			$result['position'] = $row->position;
			$result['type'] = $row->type;
			$result['url'] = $row->url;
			$result['is_watched'] = $row->is_watched;
			$result['watched_by'] = $row->watched_by;
			$result['is_peeked'] = $row->is_peeked;
			$result['fk_company_id'] = $row->fk_company_id;
			
			$result['text'] = "<i class='fa ".$row->type_icon." '></i>".$row->code_id." is ".$row->type_name." by ".$row->executorname;
			
			array_push($results, $result);
		}
		return $results;
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