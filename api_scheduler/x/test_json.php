<?php
require_once('connection.php');

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
	if(checkOrderidExist($x['ORDER_ID'], $CON)){
		inserting("(".$fk_company_id[$x['WITEL']].", null, ".$x['ORDER_ID'].", '".$x['ORDER_DATE']."', ".$x['ORDER_STATUS'].", '".$x['NCLI']."', '".$x['CITY_NAME']."', '".$x['SPEEDY']."', '".$x['POTS']."', '".$x['PHONE_NO']."', '".$x['KCONTACT']."', 2, '".$x['CUSTOMER_NAME']."', '".$x['CUSTOMER_ADDR']."', '".$x['PACKAGE_NAME']."', '".$x['GPS_LATITUDE'].", ".$x['GPS_LONGITUDE']."', '".$x['XS2']."', 0, 1, 1)", $CON);
		
	}else{
		echo "sudah ada".$x['ORDER_ID'];
		//echo "(".$fk_company_id[$x['WITEL']].", null, ".$x['ORDER_ID'].", '".$x['ORDER_DATE']."', ".$x['ORDER_STATUS'].", '".$x['NCLI']."', '".$x['CITY_NAME']."', '".$x['SPEEDY']."', '".$x['POTS']."', '".$x['PHONE_NO']."', '".$x['KCONTACT']."', 2, '".$x['CUSTOMER_NAME']."', '".$x['CUSTOMER_ADDR']."', '".$x['PACKAGE_NAME']."', '".$x['GPS_LATITUDE'].", ".$x['GPS_LONGITUDE']."', '".$x['XS2']."', 0, 1, 1)";
	}
	echo "<br>";
}

function inserting($inserting, $CON){
	$query = mysqli_query($CON, "INSERT INTO `b_unsc` (`fk_company_id`, `fk_submission_id`, `order_id`, `order_date`, `order_status`, `ncli`, `city_name`, `speedy`, `pots`, `phone_no`, `kcontact`, `type`, `cust_name`, `cust_address`, `package`, `cust_coordinate`, `sto_name`, `status`, `is_active`, `created_by`) VALUES ".$inserting);
}

function checkOrderidExist($ORDER_ID, $CON){
	$jml = 0;
	$query = mysqli_query($CON,"SELECT count(*) jml FROM `b_unsc` unsc WHERE unsc.order_id = ".$ORDER_ID);
	while($row = mysqli_fetch_assoc($query)){
		$jml = $row['jml'];
	}
	if($jml > 0){
		return false;
	}else{
		return true;
	}
}
//"INSERT INTO `b_unsc` (`fk_company_id`, `fk_submission_id`, `order_id`, `order_date`, `order_status`, `ncli`, `city_name`, `speedy`, `pots`, `phone_no`, `kcontact`, `type`, `cust_name`, `cust_address`, `package`, `cust_coordinate`, `sto_name`, `status`, `is_active`, `created_by`) VALUES
//(36, 1, NULL, 8117696, '2018-02-08 15:59:00', 0, '36538902', '', '475758108~111221100916', '', '81265534141', 'MI;MYIR-10033792670001;SPRHE96-B1641PIV;PARULIAN KERTAGAMA;081265534141', 0, 2, 'PARULIAN KERTAGAMA', 'MEDAN SUMUT,KEL SIMPANG SELAYANG,RINTE RAYA,139', '10 Mbps, UseeTV (Entry)', '3.52663277472234, 98.6137680709362', 'TTG', 0, 1, '0000-00-00 00:00:00', '1', '2019-05-22 16:12:43', '1'),
//(37, 1, NULL, 8118237, '2018-02-08 16:10:57', 0, '36536036', '', '475612048~111218115410', '475612078~06142406811', '81287490681', 'MI;MYIR-10033775950001;SPSIT95;Elevan Hasudungan Hutasoit;081287490681', 0, 2, 'Elevan Hasudungan Hutasoit', 'MEDAN SUMUT,KEL TANJUNG SARI,DELI INDAH KOM V MALINA,16', '10 Mbps, free 100 mnt Lokal-SLJJ, Movin', '3.5524285689737, 98.6246920377016', 'PDB', 0, 1, '0000-00-00 00:00:00', '1', '2019-05-22 16:12:43', '1'),
//(38, 1, NULL, 8120449, '2018-02-08 17:17:38', 0, '36537888', '', '475970858~111218115569', '475971148~06142405493', '82246671395', 'MI;MYIR-10033720000001;SPDAT85 ;K. Nabil ;082246671395', 0, 2, 'K. Nabil', 'MEDAN SUMUT,KEL PADANG BULAN SELAYANG 1,SEI ASAHAN,55', '20 Mbps, free 100 mnt Lokal-SLJJ, Movin', '3.57230047000624, 98.6502300202846', 'PDB', 0, 1, '0000-00-00 00:00:00', '1', '2019-05-22 16:12:43', '1');";
?>
<!--
<html>
<body>
<form action="https://starclick.telkom.co.id/qa/backend_qa/public/api/tracking" method="get">
<input type="hidden" name="SearchText" value="MEDAN">
<input type="hidden" name="ScNoss" value="true">
<input type="hidden" name="Field" value="ORG">
<input type="hidden" name="limit" value="10">
<input type="hidden" name="start" value="0">
<input type="hidden" name="page" value="1">
<input type="submit" value="submit">
</form>
</body>
</html>
-->