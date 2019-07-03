<?php
class Submission_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
		
	}
	
    protected $_table_name = 'b_submission';
	protected $_cto_columns = array('code_id', 'address', 'house_hold', 'coordinate', 'potentials', 'odp', 'fk_sto_id', 'sto_name');
	
	public function nAllDataRec(){
		$query = $this->db->query('SELECT * FROM (select count(*) as n_data from '.$this->_table_name.' where status = 0 and is_active != -1 and type = 2) subs WHERE 1 = 1');
		return $query->row()->n_data;
	}
	
	public function nDataRec($keyword = null, $orders = null){
		$querying = 'SELECT count(*) ndata FROM (
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status = 0 and subs.is_active != -1 and subs.type = 2) subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataRec($keyword = null, $orders = null, $limit = null){
		$querying = 'SELECT * FROM (
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status = 0 and subs.is_active != -1 and subs.type = 2) subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="house_hold">'.$row->house_hold . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<i class="fa fa-check-circle" style="color:black"></i>';
			$sub_array[] = '<div class="text-center"><a href="#" name="unapprove" onclick="cto_delete(\''.$row->code_id.'\', '.$row->id.', false);"><li class="text-red fa fa-trash" id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Un-recomend this!"></li></a></div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function nAllDataSend($fk_submission_id = null){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name.' where status = 0 and is_active != -1 and id in '.$fk_submission_id);
		return $query->row()->n_data;
	}
	
	public function nDataSend($keyword = null, $orders = null, $fk_submission_id = null){
		$querying = 'SELECT count(*) ndata FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status = 0 and subs.is_active != -1 and subs.id in '.$fk_submission_id.') subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataSend($keyword = null, $orders = null, $limit = null, $fk_submission_id = null){
		$querying = 'SELECT * FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status = 0 and subs.is_active != -1 and subs.id in '.$fk_submission_id.') subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="house_hold">'.$row->house_hold . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="details-control" data-id="'.$row->id.'" data-column="details"><i class="fa fa-angle-down fa-2x cto_show_detail"></i><i class="fa fa-angle-up fa-2x cto_hide_detail"></i></div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function nAllDataHistory(){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name.' where status in (-2, -1, 1, 2) and is_active != -1');
		return $query->row()->n_data;
	}
	
	public function nDataHistory($keyword = null, $orders = null){
		$querying = 'SELECT count(*) ndata FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status in (-2, -1, 1, 2) and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
			 $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id', 'status', 'status', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataHistory($keyword = null, $orders = null, $limit = null){
		$querying = 'SELECT * FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		where subs.status in (-2, -1, 1, 2) and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id', 'status', 'status', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="house_hold">'.$row->house_hold . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="details-control" data-id="'.$row->id.'" data-column="details"><i class="fa fa-angle-down fa-2x cto_show_detail"></i><i class="fa fa-angle-up fa-2x cto_hide_detail"></i></div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function nAllDataProgress(){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name.' where status >= 2 and is_active != -1');
		return $query->row()->n_data;
	}
	
	public function nDataProgress($keyword = null, $orders = null){
		$querying = 'SELECT count(*) ndata FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, case when subs.status = dictgolive.code then 100 else percen.percentage end percentage, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict 
			left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = \'STATUS_RECOM\' AND code > 2 GROUP BY type2) jml on jml.type2 = dict.type2 
			left join (SELECT code appr FROM `cto_dict` WHERE type = \'STATUS_RECOM\' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = \'STATUS_RECOM\') percen on percen.code = subs.status and percen.type2 = subs.pt_type
		left join cto_dict dictgolive on dictgolive.type = \'STATUS_RECOM\' and dictgolive.info = \'GOLIVE\' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
		where subs.status >= 2 and subs.is_active != -1) subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'status', 'percentage', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataProgress($keyword = null, $orders = null, $limit = null){
		$querying = 'SELECT * FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, case when subs.status = dictgolive.code then 100 else percen.percentage end percentage, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict 
			left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = \'STATUS_RECOM\' AND code > 2 GROUP BY type2) jml on jml.type2 = dict.type2 
			left join (SELECT code appr FROM `cto_dict` WHERE type = \'STATUS_RECOM\' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = \'STATUS_RECOM\') percen on percen.code = subs.status and percen.type2 = subs.pt_type
		left join cto_dict dictgolive on dictgolive.type = \'STATUS_RECOM\' and dictgolive.info = \'GOLIVE\' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
		where subs.status >= 2 and subs.is_active != -1) subs WHERE 1 = 1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'status', 'percentage', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="percentage">'.number_format($row->percentage, 2) . ' %</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function nAllDataGolive(){
		$query = $this->db->query("select count(*) as n_data from (
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, odp_lab.label_golive as odp, case when subs.potentials <= 2 then 'Low' when subs.potentials <= 3 then 'Medium' else 'High' end as demand, sto.name as fk_sto_id, 'x' as details, coalesce(dict.name, subs.status) status, subs.coordinate, coalesce(subs.updated_time, subs.created_time) as updated_time from b_submission as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
		left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
		where subs.status = dictgolive.code and subs.is_active != -1

		UNION

		SELECT '-' as id, '-' as code_id, odpapi.Alamat as address, 0 as house_hold, 0 as potentials, odpapi.LABEL_GOLIVE as odp, '-' as demand, odpapi.STO as fk_sto_id, 'x' as details,coalesce(dictgolive.name, 'Go Live') status, odpapi.Tikor_Pelanggan as coordinate, coalesce(odpapi.updated_time, odpapi.created_time) as updated_time FROM b_odp_api odpapi
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND dictgolive.type2 = 'PT2'
		WHERE odpapi.LABEL_GOLIVE IS NOT NULL AND odpapi.LABEL_GOLIVE != '#N/A' and odpapi.status_golive IS NOT NULL AND odpapi.status_golive != '#N/A') subs where 1 = 1");
		return $query->row()->n_data;
	}
	
	public function nDataGolive($keyword = null, $orders = null){
		$querying = "select count(*) as ndata from (
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, odp_lab.label_golive as odp, case when subs.potentials <= 2 then 'Low' when subs.potentials <= 3 then 'Medium' else 'High' end as demand, sto.name as fk_sto_id, 'x' as details, coalesce(dict.name, subs.status) status, subs.coordinate, coalesce(subs.updated_time, subs.created_time) as updated_time from b_submission as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
		left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
		where subs.status = dictgolive.code and subs.is_active != -1

		UNION

		SELECT '-' as id, '-' as code_id, odpapi.Alamat as address, 0 as house_hold, 0 as potentials, odpapi.LABEL_GOLIVE as odp, '-' as demand, odpapi.STO as fk_sto_id, 'x' as details,coalesce(dictgolive.name, 'Go Live') status, odpapi.Tikor_Pelanggan as coordinate, coalesce(odpapi.updated_time, odpapi.created_time) as updated_time FROM b_odp_api odpapi
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND dictgolive.type2 = 'PT3'
		WHERE odpapi.LABEL_GOLIVE IS NOT NULL AND odpapi.LABEL_GOLIVE != '#N/A' and odpapi.status_golive IS NOT NULL AND odpapi.status_golive != '#N/A') subs where 1 = 1";
		
        if ($keyword != null){
			$querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.odp like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'odp', 'fk_sto_id', 'fk_sto_id', 'coordinate', 'status', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataGolive($keyword = null, $orders = null, $limit = null){
		$querying = "select * from (
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, odp_lab.label_golive as odp, case when subs.potentials <= 2 then 'Low' when subs.potentials <= 3 then 'Medium' else 'High' end as demand, sto.name as fk_sto_id, 'x' as details, coalesce(dict.name, subs.status) status, subs.coordinate, coalesce(subs.updated_time, subs.created_time) as updated_time from b_submission as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
		left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
		where subs.status = dictgolive.code and subs.is_active != -1

		UNION

		SELECT '-' as id, '-' as code_id, odpapi.Alamat as address, 0 as house_hold, 0 as potentials, odpapi.LABEL_GOLIVE as odp, '-' as demand, odpapi.STO as fk_sto_id, 'x' as details,coalesce(dictgolive.name, 'Go Live') status, odpapi.Tikor_Pelanggan as coordinate, coalesce(odpapi.updated_time, odpapi.created_time) as updated_time FROM b_odp_api odpapi
		left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND dictgolive.type2 = 'PT3'
		WHERE odpapi.LABEL_GOLIVE IS NOT NULL AND odpapi.LABEL_GOLIVE != '#N/A' and odpapi.status_golive IS NOT NULL AND odpapi.status_golive != '#N/A') subs where 1 = 1";
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.odp like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'odp', 'fk_sto_id', 'fk_sto_id', 'coordinate', 'status', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="OCC">OCC</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="coordinate">'.$row->coordinate . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function nData($keyword = null, $orders = null){
		$querying = 'select count(*) as ndata from '.$this->_table_name.' as subs';
		
        if ($keyword != null){
            $querying .= ' where subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%"';
        }
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getData($keyword = null, $orders = null, $limit = null){
		$querying = '
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))';
		
        if ($keyword != null){
            $querying .= ' where subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%"';
        }
		//echo ''.$querying;
		
		$columns = $this->_cto_columns;
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="house_hold">'.$row->house_hold . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="details-control" data-id="'.$row->id.'" data-column="details"><i class="fa fa-angle-down fa-2x cto_show_detail"></i><i class="fa fa-angle-up fa-2x cto_hide_detail"></i></div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	//untuk order/new_order
	public function nAllDataNewOrder(){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name.' where status = 1 and pt_type is not null and is_active != -1');
		return $query->row()->n_data;
	}
	
	public function nDataNewOrder($keyword = null, $orders = null){
		$querying = 'SELECT count(*) ndata FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(first_submit.updated_time, subs.updated_time, subs.created_time) as updated_time,subs.status status_code, subs.pt_type from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (select min(subslog.updated_time) updated_time, subslog.id id from b_submission_log subslog where subslog.status = 1 and subslog.is_active != -1 group by subslog.id order by subslog.updated_time asc) first_submit on first_submit.id = subs.id
		where subs.status = 1 and subs.pt_type is not null and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id', 'fk_sto_id', 'pt_type', 'fk_sto_id', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.status asc, subs.id desc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataNewOrder($keyword = null, $orders = null, $limit = null){
		$querying = 'SELECT * FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, coalesce(dict.name, subs.status) status, coalesce(first_submit.updated_time, subs.updated_time, subs.created_time) as updated_time,subs.status status_code, subs.pt_type from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (select min(subslog.updated_time) updated_time, subslog.id id from b_submission_log subslog where subslog.status = 1 and subslog.is_active != -1 group by subslog.id order by subslog.updated_time asc) first_submit on first_submit.id = subs.id
		where subs.status = 1 and subs.pt_type is not null and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'house_hold', 'potentials', 'odp', 'demand', 'fk_sto_id', 'fk_sto_id', 'pt_type', 'fk_sto_id', 'updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.status asc, subs.id desc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="house_hold">'.$row->house_hold . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="details-control" data-id="'.$row->id.'" data-column="details"><i class="fa fa-angle-down fa-2x cto_show_detail"></i><i class="fa fa-angle-up fa-2x cto_hide_detail"></i></div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->pt_type . '</div>';
			
			if($row->status_code == 1){
				$sub_array[] = '<a type="button" name="approve" class="btn" onclick="cto_approve(\''.$row->code_id.'\', '.$row->id.', true);"><li class="text-blue fa fa-sign-in" id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Approve this!"></li></a><br><a type="button" name="unapprove" class="btn" onclick="cto_approve(\''.$row->code_id.'\', '.$row->id.', false);"><li class="text-red fa fa-times-circle" id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="Un-approve this!"></li></a>';
			}else{
				$sub_array[] = '';
			}
			
			//$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	//
	public function nAllDataOngoing(){
		$query = $this->db->query('select count(*) as n_data from '.$this->_table_name.' where status >= 1 and is_active != -1');
		return $query->row()->n_data;
	}
	
	public function nDataOngoing($keyword = null, $orders = null){
		$querying = 'SELECT count(*) ndata FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, odp.odp_count_not_ready, coalesce(odpr.odp_count_ready, 0) odp_count_ready, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time, subs.status status_code, subs.pt_type from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (select COUNT(*) odp_count_not_ready, odpz.fk_submission_id from b_odp odpz where odpz.id_deployer is null or odpz.LABEL_GOLIVE is null or odpz.LABEL_GOLIVE = \'#N/A\' GROUP BY odpz.fk_submission_id) odp on odp.fk_submission_id = subs.id
		left join (select COUNT(*) odp_count_ready, odpr.fk_submission_id from b_odp odpr where odpr.id_deployer is not null and odpr.LABEL_GOLIVE is not null and odpr.LABEL_GOLIVE != \'#N/A\' GROUP BY odpr.fk_submission_id) odpr on odpr.fk_submission_id = subs.id
		where subs.status >= 1 and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		
		$columns = array('code_id', 'address', 'odp', 'potentials', 'odp_count_ready', 'demand', 'fk_sto_id', 'fk_sto_id', 'pt_type', 'status', 'status','updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.status_code asc';
		}
		
		$query = $this->db->query($querying);
		
        return $query->row()->ndata;
	}
	
	public function getDataOngoing($keyword = null, $orders = null, $limit = null){
		$querying = 'SELECT * FROM(
		select subs.id, subs.code_id, subs.address, coalesce(subs.house_hold, subs.potentials*3) as house_hold, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, case when subs.potentials <= 2 then \'Low\' when subs.potentials <= 3 then \'Medium\' else \'High\' end as demand, sto.name as fk_sto_id, \'x\' as details, odp.odp_count_not_ready, coalesce(odpr.odp_count_ready, 0) odp_count_ready, coalesce(dict.name, subs.status) status, coalesce(subs.updated_time, subs.created_time) as updated_time, subs.status status_code, subs.pt_type from '.$this->_table_name.' as subs
		inner join b_sto sto on sto.id = subs.fk_sto_id
		left join cto_dict dict on dict.code = subs.status and dict.type = \'STATUS_RECOM\' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
		left join (select COUNT(*) odp_count_not_ready, odpz.fk_submission_id from b_odp odpz where odpz.id_deployer is null or odpz.LABEL_GOLIVE is null or odpz.LABEL_GOLIVE = \'#N/A\' GROUP BY odpz.fk_submission_id) odp on odp.fk_submission_id = subs.id
		left join (select COUNT(*) odp_count_ready, odpr.fk_submission_id from b_odp odpr where odpr.id_deployer is not null and odpr.LABEL_GOLIVE is not null and odpr.LABEL_GOLIVE != \'#N/A\' GROUP BY odpr.fk_submission_id) odpr on odpr.fk_submission_id = subs.id
		where subs.status >= 1 and subs.is_active != -1) subs WHERE 1=1';
		
        if ($keyword != null){
            $querying .= ' and (subs.code_id like "%'.$keyword.'%" OR subs.address like "%'.$keyword.'%" OR subs.fk_sto_id like "%'.$keyword.'%" OR subs.demand like "%'.$keyword.'%" OR subs.status like "%'.$keyword.'%")';
        }
		//echo ''.$querying;
		
		$columns = array('code_id', 'address', 'odp', 'potentials', 'odp_count_ready', 'demand', 'fk_sto_id', 'fk_sto_id', 'pt_type', 'status', 'status','updated_time');
		if ($orders != null){
            $querying .= ' order by subs.'.$columns[$orders[0]].' '.$orders[1];
        }else{
			$querying .= ' order by subs.status_code asc';
		}
		
		if ($limit != null){
			$querying .= ' limit '.$limit[0].', '.$limit[1];
        }
		
		$data = array();
		$query = $this->db->query($querying);
		foreach($query->result() as $row){
			$sub_array = array();
			$sub_array[] = '<div class="update2" data-id="'.$row->id.'" data-column="code_id">'.$row->code_id . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="address">'.$row->address . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="potentials">'.$row->potentials . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="odp">'.$row->odp_count_ready . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="demand">'.$row->demand . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->fk_sto_id . '</div>';
			$sub_array[] = '<div class="details-control" data-id="'.$row->id.'" data-column="details"><i class="fa fa-angle-down fa-2x cto_show_detail"></i><i class="fa fa-angle-up fa-2x cto_hide_detail"></i></div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="fk_sto_id">'.$row->pt_type . '</div>';
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="status">'.$row->status . '</div>';
			
			if($row->status_code < 2 && empty($row->pt_type)){
				$sub_array[] = '<a href="'.base_url().'submission/check_jaringan/'.$row->id.'" type="button" name="update_status" class="btn btn-success .btn-sm">Check Jaringan</a>';
			}elseif($row->odp_count_not_ready > 0 || (empty($row->odp_count_not_ready) && empty($row->odp_count_ready))){
				if($row->status_code < 2){
					$sub_array[] = 'Not yet approved';
				}else{
					$sub_array[] = '<a href="'.base_url().'order/create_odp/'.$row->id.'" type="button" name="create_odp" class="btn btn-warning">Insert ID</a>';
				}
			}else{
				if($row->status_code < 2){
					$sub_array[] = 'Not yet approved';
				}else{
					$sub_array[] = '<a href="'.base_url().'submission/edit_status/'.$row->id.'" type="button" name="update_status" class="btn btn-success .btn-sm">Update</a>';
				}
			}
			
			$sub_array[] = '<div class="update" data-id="'.$row->id.'" data-column="updated_time">'.$row->updated_time . '</div>';
			$data[] = $sub_array;
		}
        return $data;
	}
	
	public function getAData($id){
        $query = $this->db->query('select subs.code_id, subs.address, subs.house_hold, subs.potentials, subs.odp, subs.fk_sto_id, coalesce(sto.name, subs.sto_name) sto_name, subs.status, subs.updated_time from '.$this->_table_name.' subs 
		left join b_sto sto on sto.id = fk_sto_id
		where subs.id = '.$id.' limit 1');
		$result = array();
		foreach($query->result() as $row){
			$result['code_id'] = $row->code_id;
			$result['address'] = $row->address;
			$result['house_hold'] = $row->house_hold;
			$result['potentials'] = $row->potentials;
			$result['odp'] = $row->odp;
			$result['fk_sto_id'] = $row->fk_sto_id;
			$result['sto_name'] = $row->sto_name;
			$result['status'] = $row->status;
			$result['updated_time'] = $row->updated_time;
		}
		return $result;
    }
	
	function cto_getDatas($param){
		$this->db->select("id AS value, name AS label");
		$this->db->from("b_sto");
		$this->db->where($param);
		$this->db->order_by('sequence');
		$query = $this->db->get();
		return $query;
	}
	
	function cto_getDetailsData($param){
		$query = $this->db->query('select subs.code_id, subs.address, subs.house_hold, subs.coordinate, sto.name sto_name, subs.potentials, subs.odp from b_submission subs
		left join b_sto sto on sto.id = subs.fk_sto_id
		where subs.id = '.$param["id"].' and subs.is_active = '.$param["is_active"].' and subs.fk_company_id = '.$_SESSION['fk_company_id']);
		return $query;
	}
	
	public function insert_odp($data){
		if(empty($data['created_by'])){
			$data['created_by'] = $_SESSION['id'];
		}
		$this->db->insert('b_odp',$data);
		return $this->db->affected_rows() ? true : false;
	}
	
	public function getADataStatus($fk_submission_id){
		$query = $this->db->query("select subs.id, subs.code_id, subs.coordinate, subs.address, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, sto.name as sto_name, coalesce(dict.name, subs.status) status, coalesce(dictnext.name, subs.status+1) status_next, coalesce(case when subs.status = dictgolive.code then 100 else percen.percentage end, 0) percentage, coalesce(case when subs.status+1 = dictgolive.code then 100 else percen_onwork.percentage end, 0) percentage_onwork, coalesce(subs.updated_time, subs.created_time) as updated_time, odp_lab.LABEL_GOLIVE, subs.pt_type from b_submission as subs
			inner join b_sto sto on sto.id = subs.fk_sto_id
			left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
			left join cto_dict dictnext on dictnext.code = subs.status+1 and dictnext.type = 'STATUS_RECOM' AND (dictnext.type2 = subs.pt_type or (subs.status+1 <= 2 AND dictnext.type2 is null))
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2 GROUP BY type2) jml on jml.type2=dict.type2 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen on percen.code = subs.status and percen.type2 = subs.pt_type
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2 GROUP BY type2) jml on jml.type2=dict.type2 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen_onwork on percen_onwork.code = subs.status+1 and percen_onwork.type2 = subs.pt_type
			left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
			left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
			where subs.status >= 1 and subs.is_active != -1 and subs.id = ".$fk_submission_id);
		foreach($query->result() as $row){
			$result['id'] = $row->id;
			$result['code_id'] = $row->code_id;
			$result['coordinate'] = $row->coordinate;
			$result['address'] = $row->address;
			$result['potentials'] = $row->potentials;
			$result['odp'] = $row->odp;
			$result['sto_name'] = $row->sto_name;
			$result['percentage'] = $row->percentage;
			$result['percentage_onwork'] = $row->percentage_onwork;
			$result['status'] = $row->status;
			$result['status_next'] = $row->status_next;
			$result['LABEL_GOLIVE'] = $row->LABEL_GOLIVE;
			$result['updated_time'] = $row->updated_time;
			$result['pt_type'] = $row->pt_type;
		}
		return $result;
	}
	
	public function update_progress($fk_submission_id){
		$query = $this->db->query("SELECT dict.code status_golive, subs.status status_code FROM `b_submission` subs 
		INNER JOIN cto_dict dict ON dict.type2 = subs.pt_type and dict.type = 'STATUS_RECOM' and dict.info = 'GOLIVE'
		WHERE subs.id = ".$fk_submission_id);
		$status_golive = 0;
		$status_code = 0;
		foreach($query->result() as $row){
			$status_golive = $row->status_golive;
			$status_code = $row->status_code;
		}
		if($status_code < $status_golive){
			if(empty($data['updated_by'])){
				$data['updated_by'] = $_SESSION['id'];
			}
			$id = $fk_submission_id;
			$this->db->where('id', $id);
			$this->db->where('status >= 2');
			$this->db->set('status', 'status+1', false);
			$this->db->update($this->_table_name,$data);
			
			return $this->db->affected_rows() ? true : false;
		}else{
			return false;
		}
	}
	
	public function odp_togolive($fk_submission_id){
		if(empty($data['updated_by'])){
			$data['updated_by'] = $_SESSION['id'];
		}
		
		$this->db->where('fk_submission_id', $fk_submission_id);
		$this->db->set('status_golive', 'concat(\'live \', now())', false);
		$this->db->update('b_odp',$data);
		
		return $this->db->affected_rows() ? true : false;
	}
	
	public function check_odpapilive($label_golive){
		$query = $this->db->query("select count(*) jml from b_odp_api where LABEL_GOLIVE = '".$label_golive."' and status_golive != '#N/A' and status_golive is not null");
		$jml = 0;
		foreach($query->result() as $row){
			$jml = $row->jml;
		}
		if($jml > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function getAODPDataStatus($label_golive){
		$query = $this->db->query("select subs.id, subs.code_id, subs.coordinate, subs.address, odp.LABEL_GOLIVE from b_odp odp 
		left join b_submission subs on subs.id = odp.fk_submission_id
		where odp.LABEL_GOLIVE = '".$label_golive."' and odp.status_golive != '#N/A' and odp.status_golive is not null");
		foreach($query->result() as $row){
			$result['id'] = $row->id;
			$result['code_id'] = $row->code_id;
			$result['coordinate'] = $row->coordinate;
			$result['address'] = $row->address;
			$result['LABEL_GOLIVE'] = $row->LABEL_GOLIVE;
		}
		return $result;
	}
	
	public function getODPPhoto($fk_submission_id){
		$query = $this->db->query("SELECT odp.id, subs.id fk_submission_id, odp.id_deployer, odp.LABEL_GOLIVE odp_name, subs.status, coalesce(img.img, 'default.png') img FROM b_odp odp
		INNER JOIN b_submission subs ON subs.id = odp.fk_submission_id
		LEFT JOIN (SELECT MAX(imgl.id) id, imgl.fk_odp_id, imgl.status FROM b_img_progress imgl GROUP BY imgl.fk_odp_id, imgl.status) imglast ON imglast.fk_odp_id = odp.id AND imglast.status = subs.status+1
		LEFT JOIN b_img_progress img ON img.id = imglast.id
		WHERE odp.fk_submission_id = ".$fk_submission_id);
		$result = array();
		foreach($query->result_array() as $row){
			array_push($result, $row);
			
		}
		return $result;
	}
	
	public function insertodpimg($data){
		if(empty($data['created_by'])){
			$data['created_by'] = $_SESSION['id'];
		}
		$this->db->insert('b_img_progress',$data);
		return $this->db->affected_rows() ? true : false;
	}
	
	public function checkImageODP($fk_submission_id){
		$query = $this->db->query("SELECT odp.id, odp.id_deployer, odp.LABEL_GOLIVE, coalesce(dict.name, subs.status) status, img.id id_img FROM b_odp odp 
		INNER JOIN b_submission subs on subs.id = odp.fk_submission_id
		LEFT JOIN b_img_progress img on img.fk_odp_id = odp.id AND img.status = subs.status+1
		LEFT JOIN cto_dict dict on dict.code = subs.status+1 and dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status+1 <= 2 AND dict.type2 is null))
		WHERE odp.fk_submission_id = ".$fk_submission_id." AND img.id is null
		ORDER BY odp.id ASC LIMIT 1");
		$result = array();
		foreach($query->result_array() as $row){
			array_push($result, $row);
			
		}
		return $result;
	}
	
	public function cto_removeImg($fk_odp_id, $status){
		$this->db->where('fk_odp_id', $fk_odp_id);
		$this->db->where('status', $status);
		$this->db->delete("b_img_progress");
		return $this->db->affected_rows() ? true : false;
	}
	
	public function getDataLogHistory($id){
        $query = $this->db->query("SELECT CONCAT(COALESCE(DATE_FORMAT(subm.updated_time, '%d %M %Y %H:%i:%s'), '-'), ' | Update done by ', COALESCE(subm.name, '-'), ' : ', COALESCE(subm.status, '-')) status_text, CASE WHEN img.jml > 0 THEN CONCAT('<a href=\"#\" onclick=\"get_photo(', subm.id, ', ', subm.status_code,');\">photo</a> uploaded') ELSE '' END photo FROM (
			SELECT subs.id, COALESCE(dict.name, subs.status) status, subs.status status_code, MIN(COALESCE(subslog.updated_time, subs.updated_time)) updated_time, user.name name, '' odp_name FROM b_submission subs
			LEFT JOIN cto_dict dict ON dict.code = subs.status AND dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
			LEFT JOIN (SELECT subslog.id, MIN(subslog.updated_time) updated_time, subslog.status, subslog.updated_by FROM b_submission_log subslog GROUP BY subslog.id, subslog.status) subslog on subslog.id = subs.id and subslog.status = subs.status
			LEFT JOIN cto_user user ON user.id = COALESCE(subslog.updated_by, subs.updated_by, subs.created_by)
			WHERE subs.id = ".$id."
			GROUP BY subs.status

			UNION

			SELECT subslog.id, COALESCE(dict.name, subslog.status) status, subslog.status status_code, MIN(subslog.updated_time) updated_time, user.name name, '' odp_name FROM b_submission_log subslog
			LEFT JOIN cto_dict dict ON dict.code = subslog.status AND dict.type = 'STATUS_RECOM' AND (dict.type2 = subslog.pt_type or (subslog.status <= 2 AND dict.type2 is null))
			LEFT JOIN cto_user user ON user.id = COALESCE(subslog.updated_by, subslog.created_by)
			WHERE subslog.id = ".$id." 
			GROUP BY subslog.status

			UNION

			SELECT odp.fk_submission_id id, GROUP_CONCAT(CONCAT('ID Deployer ', odp.id_deployer,'ODP Name ', odp.LABEL_GOLIVE)) status, 1.5 status_code, odp.created_time updated_time, user.name name, concat(odp.id_deployer, \";\", odp.LABEL_GOLIVE) odp_name FROM b_odp odp 
			LEFT JOIN cto_user user ON user.id = odp.created_by
			WHERE odp.fk_submission_id = ".$id."
			GROUP BY odp.fk_submission_id
			
			UNION

			SELECT subs.id, 'Check Jaringan' status, 1.2 status_code, MIN(COALESCE(subslog.updated_time, subs.updated_time)) updated_time, user.name name, '' odp_name FROM b_submission subs
			LEFT JOIN (SELECT subslog.id, MIN(subslog.updated_time) updated_time, subslog.updated_by FROM b_submission_log subslog WHERE subslog.status = 1 and subslog.pt_type is not null GROUP BY subslog.id) subslog on subslog.id = subs.id
			LEFT JOIN cto_dict dict ON dict.code = subs.status AND dict.type = 'STATUS_RECOM' AND (dict.type2 = subs.pt_type or (subs.status <= 2 AND dict.type2 is null))
			LEFT JOIN cto_user user ON user.id = COALESCE(subslog.updated_by, subs.updated_by, subs.created_by)
			WHERE subs.id = ".$id." and subs.pt_type is not null
			GROUP BY subs.status
			) subm

			LEFT JOIN (SELECT odp.fk_submission_id, img.status, count(*) jml FROM b_img_progress img
			INNER JOIN b_odp odp ON odp.id = img.fk_odp_id
			WHERE odp.fk_submission_id = ".$id." 
			GROUP BY odp.fk_submission_id, img.status) img ON img.fk_submission_id = subm.id AND img.status = subm.status_code
			
			GROUP BY subm.status_code
			ORDER BY subm.updated_time DESC");
		$result = array();
		foreach($query->result_array() as $row){
			array_push($result, $row);
		}
		return $result;
    }
	
	public function getADataStatusLogHistory($fk_submission_id){
		$query = $this->db->query("select subs.id, subs.code_id, subs.coordinate, subs.address, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/8.0)) as odp, sto.name as sto_name, coalesce(dict.name, subs.status) status, coalesce(dictnext.name, subs.status+1) status_next, coalesce(case when subs.status = dictgolive.code then 100 else percen.percentage end, 0) percentage, coalesce(case when subs.status+1 = dictgolive.code then 100 else percen_onwork.percentage end, 0) percentage_onwork, coalesce(subs.updated_time, subs.created_time) as updated_time, odp_lab.LABEL_GOLIVE from b_submission as subs
			inner join b_sto sto on sto.id = subs.fk_sto_id
			left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM'
			left join cto_dict dictnext on dictnext.code = subs.status+1 and dictnext.type = 'STATUS_RECOM'
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2 GROUP BY type2) jml on jml.type2=dict.type2 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen on percen.code = subs.status and percen.type2 = subs.pt_type
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage, dict.type2 FROM `cto_dict` dict left join (SELECT count(*) jml, type2 FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2 GROUP BY type2) jml on jml.type2=dict.type2 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen_onwork on percen_onwork.code = subs.status+1 and percen_onwork.type2 = subs.pt_type
			left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
			left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
			where subs.is_active != -1 and subs.id = ".$fk_submission_id);
		foreach($query->result() as $row){
			$result['id'] = $row->id;
			$result['code_id'] = $row->code_id;
			$result['coordinate'] = $row->coordinate;
			$result['address'] = $row->address;
			$result['potentials'] = $row->potentials;
			$result['odp'] = $row->odp;
			$result['sto_name'] = $row->sto_name;
			$result['percentage'] = $row->percentage;
			$result['percentage_onwork'] = $row->percentage_onwork;
			$result['status'] = $row->status;
			$result['status_next'] = $row->status_next;
			$result['LABEL_GOLIVE'] = $row->LABEL_GOLIVE;
			$result['updated_time'] = $row->updated_time;
		}
		return $result;
	}
	
	public function getODPPhotoStatus($fk_submission_id, $status){
		$query = $this->db->query("SELECT odp.id, subs.id fk_submission_id, odp.id_deployer, odp.LABEL_GOLIVE odp_name, subs.status, coalesce(img.img, 'default.png') img FROM b_odp odp
		INNER JOIN b_submission subs ON subs.id = odp.fk_submission_id
		LEFT JOIN (SELECT MAX(imgl.id) id, imgl.fk_odp_id, imgl.status FROM b_img_progress imgl GROUP BY imgl.fk_odp_id, imgl.status) imglast ON imglast.fk_odp_id = odp.id AND imglast.status = ".$status."
		LEFT JOIN b_img_progress img ON img.id = imglast.id
		WHERE odp.fk_submission_id = ".$fk_submission_id);
		$result = array();
		foreach($query->result_array() as $row){
			array_push($result, $row);
			
		}
		return $result;
	}
	
	public function cto_getpt_typeDatas(){
		$query = $this->db->query("SELECT dict.name value, dict.name label FROM cto_dict dict WHERE dict.type = 'PT_TYPE'");
		$result = array();
		foreach($query->result_array() as $row){
			array_push($result, $row);
			
		}
		return $result;
	}
}