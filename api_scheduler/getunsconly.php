<?php
require_once('connection.php');
//untuk mengambil data API
$fk_company_id = array();
$stts = array();
$query = mysqli_query($CON,"SELECT * FROM `cto_company` comp WHERE comp.is_active != -1");
while($row = mysqli_fetch_assoc($query)){
	$fk_company_id[$row['name']] = $row['id'];
}

//echo "".$fk_company_id['BUKIT TINGGI'];
//die();
$url = 'https://starclick.telkom.co.id/qa/backend_qa/public/api/tracking';
$data = array('SearchText' => 'MEDAN', 'ScNoss' => 'true', 'Field' => 'ORG', 'limit' => '10', 'start' => '0', 'page' => '1');
$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$array = json_decode( $result, true );
$total = $array['recordsTotal'];


$data['limit'] = $total;
$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$array = json_decode( $result, true );
foreach($array['data'] as $x){
	//var_dump($x);
	if(checkOrderidExist($x['ORDER_ID'], $CON) && $x['STATUS_CODE_SC'] == 29){
		inserting("(".$fk_company_id[$x['WITEL']].", null, ".$x['ORDER_ID'].", '".$x['ORDER_DATE']."', ".$x['ORDER_STATUS'].", '".$x['NCLI']."', '".$x['CITY_NAME']."', '".$x['SPEEDY']."', '".$x['POTS']."', '".$x['PHONE_NO']."', '".$x['KCONTACT']."', 2, '".$x['CUSTOMER_NAME']."', '".$x['CUSTOMER_ADDR']."', '".$x['PACKAGE_NAME']."', '".$x['GPS_LATITUDE'].", ".$x['GPS_LONGITUDE']."', '".$x['XS2']."', 0, 1, 1)", $CON);
		
	}else{
		echo "sudah ada".$x['ORDER_ID']."_".$x['STATUS_CODE_SC'];
		//echo "(".$fk_company_id[$x['WITEL']].", null, ".$x['ORDER_ID'].", '".$x['ORDER_DATE']."', ".$x['ORDER_STATUS'].", '".$x['NCLI']."', '".$x['CITY_NAME']."', '".$x['SPEEDY']."', '".$x['POTS']."', '".$x['PHONE_NO']."', '".$x['KCONTACT']."', 2, '".$x['CUSTOMER_NAME']."', '".$x['CUSTOMER_ADDR']."', '".$x['PACKAGE_NAME']."', '".$x['GPS_LATITUDE'].", ".$x['GPS_LONGITUDE']."', '".$x['XS2']."', 0, 1, 1)";
	}
	echo "<br>";
}

function inserting($inserting, $CON){
	$query = mysqli_query($CON, "INSERT INTO `b_unsc_sementara` (`fk_company_id`, `fk_submission_id`, `order_id`, `order_date`, `order_status`, `ncli`, `city_name`, `speedy`, `pots`, `phone_no`, `kcontact`, `type`, `cust_name`, `cust_address`, `package`, `cust_coordinate`, `sto_name`, `status`, `is_active`, `created_by`) VALUES ".$inserting);
}

function checkOrderidExist($ORDER_ID, $CON){
	$jml = 0;
	$query = mysqli_query($CON,"SELECT count(*) jml FROM `b_unsc_sementara` unsc WHERE unsc.order_id = ".$ORDER_ID);
	while($row = mysqli_fetch_assoc($query)){
		$jml = $row['jml'];
	}
	if($jml > 0){
		return false;
	}else{
		return true;
	}
}


?>