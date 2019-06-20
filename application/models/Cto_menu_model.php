<?php
class Cto_menu_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
    protected $_table_name = 'cto_menu';
	protected $_cto_columns = array('controller', 'method', 'path', 'name', 'fk_menupackage_id', 'menupackage_name', 'sequence', 'is_shown', 'is_active');
	
	public function get_user_menus($username, $controller = null, $method = null){
        $query = $this->db->query("SELECT menus.html FROM (
									#package
									SELECT 
										'1.menupackagestart' jenis,
										cm.name menu_name,
										cm.sequence menu_seq,
										cm.path,
										cm.fk_menupackage_id,
										cm.menupackage_name,
										cmp.sequence menupackage_seq,
										CASE WHEN (mpother.fk_menupackage_id = cmp.id) THEN CONCAT('<li class=\"treeview active\">
											  <a href=\"#\">
												<span >',cm.menupackage_name,'</span>
												<span class=\"pull-right-container\">
												  <i class=\"fa fa-angle-left pull-right\"></i>
												</span>
											  </a>
											  <ul class=\"treeview-menu\">') ELSE CONCAT('<li class=\"treeview\">
											  <a href=\"#\">
												<span >',cm.menupackage_name,'</span>
												<span class=\"pull-right-container\">
												  <i class=\"fa fa-angle-left pull-right\"></i>
												</span>
											  </a>
											  <ul class=\"treeview-menu\">') END html
									FROM
										cto_user cu
											INNER JOIN
										cto_user_menu cum ON cum.fk_user_id = cu.id
											INNER JOIN
										cto_menu cm ON cm.id = cum.fk_menu_id
											INNER JOIN
										cto_menupackage cmp ON cmp.id = cm.fk_menupackage_id
											LEFT JOIN
										cto_menu mpother
											ON mpother.controller = '".$controller."' AND COALESCE(mpother.method, 'index') = '".$method."'
									WHERE
										cu.username = '".$username."'
											AND cm.is_shown = 1
											AND cm.is_active = 1
											AND cum.is_active = 1
									GROUP BY cmp.sequence 
									#menu
									UNION SELECT 
										'2.menuwithpackage' jenis,
										cm.name menu_name,
										cm.sequence menu_seq,
										cm.path,
										cm.fk_menupackage_id,
										cm.menupackage_name,
										cmp.sequence menupackage_seq,
										CASE WHEN (cm.controller = '".$controller."' AND COALESCE(cm.method, 'index') = '".$method."') THEN CONCAT('<li class=\"active\"><a href=\"".base_url()."',cm.path,'\"></i> ',cm.name,'</a></li>') ELSE CONCAT('<li><a href=\"".base_url()."',cm.path,'\"></i> ',cm.name,'</a></li>') END html
									FROM
										cto_user cu
											INNER JOIN
										cto_user_menu cum ON cum.fk_user_id = cu.id
											INNER JOIN
										cto_menu cm ON cm.id = cum.fk_menu_id
											LEFT JOIN
										cto_menupackage cmp ON cmp.id = cm.fk_menupackage_id
									WHERE
										cu.username = '".$username."'
											AND cm.is_shown = 1
											AND cm.is_active = 1
											AND cum.is_active = 1
											AND cm.fk_menupackage_id IS NOT NULL 
									UNION
									#menupackage end untuk kebutuhan penutup tag html
									SELECT 
										'3.menupackageend' jenis,
										cm.name menu_name,
										cm.sequence menu_seq,
										cm.path,
										cm.fk_menupackage_id,
										cm.menupackage_name,
										cmp.sequence menupackage_seq,
										CONCAT('</ul>
											</li>') html
									FROM
										cto_user cu
											INNER JOIN
										cto_user_menu cum ON cum.fk_user_id = cu.id
											INNER JOIN
										cto_menu cm ON cm.id = cum.fk_menu_id
											INNER JOIN
										cto_menupackage cmp ON cmp.id = cm.fk_menupackage_id
									WHERE
										cu.username = '".$username."'
											AND cm.is_shown = 1
											AND cm.is_active = 1
											AND cum.is_active = 1
									GROUP BY cmp.sequence 
									#menu tanpa package
									UNION SELECT 
										'4.menuwithoutpackage' jenis,
										cm.name menu_name,
										cm.sequence menu_seq,
										cm.path,
										cm.fk_menupackage_id,
										cm.menupackage_name,
										cmp.sequence menupackage_seq,
										CONCAT('<li><a href=\"".base_url()."',cm.path,'\"><span>',cm.name,'</span></a></li>') html
									FROM
										cto_user cu
											INNER JOIN
										cto_user_menu cum ON cum.fk_user_id = cu.id
											INNER JOIN
										cto_menu cm ON cm.id = cum.fk_menu_id
											LEFT JOIN
										cto_menupackage cmp ON cmp.id = cm.fk_menupackage_id
									WHERE
										cu.username = '".$username."'
											AND cm.is_shown = 1
											AND cm.is_active = 1
											AND cum.is_active = 1
											AND cm.fk_menupackage_id IS NULL) menus
									ORDER BY -menus.menupackage_seq DESC, menus.jenis ASC, menus.menu_seq ASC");
		return $query;
    }
	
	public function check_menu_exist($controller = null, $method = null){
        $query = $this->db->query("SELECT * FROM cto_menu cm
			WHERE cm.is_active = 1 AND cm.controller = '".$controller."' AND COALESCE(cm.method, 'index') = '".$method."'");
		return $query;
    }
	
	public function check_access($username, $controller = null, $method = null){
        $query = $this->db->query("SELECT * FROM cto_user cu
			INNER JOIN cto_user_menu cum ON cum.fk_user_id = cu.id
			INNER JOIN cto_menu cm ON cm.id = cum.fk_menu_id
			WHERE cu.username = '".$username."' AND cum.is_active = 1 AND cm.is_active = 1 AND cm.controller = '".$controller."' AND COALESCE(cm.method, 'index') = '".$method."'");
		return $query;
    }
	
	public function check_access_shown($username, $controller = null, $method = null){
        $query = $this->db->query("SELECT * FROM cto_user cu
			INNER JOIN cto_user_menu cum ON cum.fk_user_id = cu.id
			INNER JOIN cto_menu cm ON cm.id = cum.fk_menu_id
			WHERE cu.username = '".$username."' AND cum.is_active = 1 AND cm.is_active = 1 AND COALESCE(cm.is_shown, 1) = 1 AND cm.controller = '".$controller."' AND COALESCE(cm.method, 'index') = '".$method."'");
		return $query;
    }
	
	public function check_isnot_shown($username, $controller = null, $method = null){
        $query = $this->db->query("SELECT * FROM cto_user cu
			INNER JOIN cto_user_menu cum ON cum.fk_user_id = cu.id
			INNER JOIN cto_menu cm ON cm.id = cum.fk_menu_id
			WHERE cu.username = '".$username."' AND cum.is_active = 1 AND cm.is_active = 1 AND COALESCE(cm.is_shown, -1) = -1 AND cm.controller = '".$controller."' AND COALESCE(cm.method, 'index') = '".$method."'");
		return $query;
    }
	
	
	
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
	
	public function landing_page($username){
        $query = $this->db->query('SELECT CONCAT(CONCAT(cm.controller, \'/\'), COALESCE(cm.method, \'index\')) landing_page FROM cto_user_menu cum
		INNER JOIN cto_menu cm on cm.id = cum.fk_menu_id and cm.is_shown != -1
		INNER JOIN cto_menupackage cmp on cmp.id = cm.fk_menupackage_id
		INNER JOIN cto_user cu on cu.id = cum.fk_user_id 
		WHERE cu.username =  \''.$username.'\' AND cum.is_active != -1
		ORDER BY COALESCE(cmp.sequence, 1000) ASC, cm.sequence ASC limit 1');
		
		$result = '';
		foreach($query->result() as $row){
			$result = $row->landing_page;
		}
		return $result;
    }
	
}