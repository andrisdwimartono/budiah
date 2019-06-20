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

//untuk mendapatkan rekomendasi
$result = array();
$stts = array();
$query = mysqli_query($CON,"SELECT unsc.id, unsc.cust_coordinate, unsc.status, unsc.cust_address, unsc.sto_name FROM b_unsc unsc where unsc.type = 2 and unsc.is_active != -1 and unsc.status = 0 and unsc.fk_company_id = 1 ORDER BY unsc.id ASC");//unsc.cust_coordinate,
while($row = mysqli_fetch_assoc($query)){
	$row['cust_coordinate'] = str_replace(" ", "", $row['cust_coordinate']);
	$latlng = explode(",", $row['cust_coordinate']);
	$row['lat'] = (float) $latlng[0];
	$row['lng'] = (float) $latlng[1];
	// if($row['id'] == 17){
		// $row['id'] = 28;
	// }
	
	$result[] = $row;
	$stts[$row['id']] = 0;
}
//echo json_encode(array('result'=>$result));

//echo "<br><br><br><br>";
$rad = 150;
$query = mysqli_query($CON,"SELECT radius FROM cto_company com where com.id = 1");
while($row = mysqli_fetch_assoc($query)){
	$rad = $row['radius'];
}
$diameter = $rad*2;

$calonkumpulanunsc = array();
$kumpulanunsc = array();
$ini = array();
//cari kumpulan UNSC yang menjadi kumpulanunsc. Bisa 1 UNSC masuk ke beberapa kumpulanunsc
for($x = 0; $x < count($result)-1; $x++){
	$nearthisunsc = array();
	for($y = $x+1; $y < count($result); $y++){
		//300 is 2*r
		if(getDistance($result[$x], $result[$y]) <= $diameter && $result[$x]['sto_name'] == $result[$y]['sto_name']){
			array_push($nearthisunsc, $result[$y]);//array_push($nearthisunsc, $result[$y]['id']);
			array_push($calonkumpulanunsc, array($result[$x], $result[$y]));//array_push($calonkumpulanunsc, array($result[$x]['id'], $result[$y]['id']));
			echo $result[$x]['id']." ".$result[$y]['id']." ".number_format(getDistance($result[$x], $result[$y]), 2)."m | ";
		}
	}
	echo "<br>";
	if(count($nearthisunsc) >= 2){
		array_push($kumpulanunsc, array('unscpatokan' => $result[$x], 'unsclain' => $nearthisunsc));//array_push($kumpulanunsc, array('id' => $result[$x]['id'], 'other_id' => $nearthisunsc));
		echo 'lb dr 2 '.$result[$x]['id']." | ";
		echo "<br>";
		array_push($ini, $result[$x]['id']);
	}
}

//cek lagi, dari kumpulan UNSC, masing - masing di cek jaraknya. Bila lebih dari 300 m, maka hilangkan yang terakhir
$kumpulanrekomendasi = array();
foreach($kumpulanunsc as $calonkumrekomendasi){
	$hilangkandarikumpulanunsc = array();
	$sudah_ikut = array();
	$calonrekomendasi = array();
	var_dump($calonkumrekomendasi);
	echo "<br>".$calonkumrekomendasi['unscpatokan']['id']."<br>";
	array_push($calonrekomendasi, $calonkumrekomendasi['unscpatokan']);
	for($m = 0; $m < count($calonkumrekomendasi['unsclain'])-1; $m++){
		for($n = $m; $n < count($calonkumrekomendasi['unsclain']); $n++){
			if(getDistance($calonkumrekomendasi['unsclain'][$m], $calonkumrekomendasi['unsclain'][$n]) > $diameter ){
				$hilangkandarikumpulanunsc[$calonkumrekomendasi['unsclain'][$n]['id']] = true;
			}
		}
	}
	
	for($w = 0; $w < count($calonkumrekomendasi['unsclain'])-1; $w++){
		for($q = $w; $q < count($calonkumrekomendasi['unsclain']); $q++){
			if(getDistance($calonkumrekomendasi['unsclain'][$w], $calonkumrekomendasi['unsclain'][$q]) <= $diameter ){
				//echo "2 ".$calonkumrekomendasi['unsclain'][$w]['id']." "."1 ".$calonkumrekomendasi['unsclain'][$q]['id']." ";
				
				if(!isset($hilangkandarikumpulanunsc[$calonkumrekomendasi['unsclain'][$q]['id']]) && !isset($sudah_ikut[$calonkumrekomendasi['unsclain'][$q]['id']])){
					echo "|(".$calonkumrekomendasi['unsclain'][$w]['id'].", ".$calonkumrekomendasi['unsclain'][$q]['id'].")".$calonkumrekomendasi['unsclain'][$q]['id']." ikutz| ";
					array_push($calonrekomendasi, $calonkumrekomendasi['unsclain'][$q]);
				}
				$sudah_ikut[$calonkumrekomendasi['unsclain'][$q]['id']] = true;
				//$stts[$calonkumrekomendasi['unsclain'][$q]['id']] = 1;
				//$stts[$calonkumrekomendasi['unsclain'][$w]['id']] = 1;
			}
		}
	}
	
	array_push($kumpulanrekomendasi, $calonrekomendasi);
	//$stts[$calonkumrekomendasi['unscpatokan']['id']] = 1;
	echo "<br>";
}
// echo "<pre>";
// print_r($kumpulanrekomendasi);
// echo "</pre>";
//semua kumpulan rekomendasi sudah didapatkan, sekarang pilih rekomendasi dari kumpulan rekomendasi. Dengan syarat satu unsc hanya boleh ikut 1 rekomendasi

$unsc_used = array();
$rekomendasi = array();
for($r = 0; $r < count($kumpulanrekomendasi); $r++){
	$unsc_unique = true;
	for($f = 0; $f < count($kumpulanrekomendasi[$r]); $f++){
		if(isset($unsc_used[$kumpulanrekomendasi[$r][$f]['id']])){
			$unsc_unique = false;
		}
	}
	
	if($unsc_unique){
		for($f = 0; $f < count($kumpulanrekomendasi[$r]); $f++){
			$unsc_used[$kumpulanrekomendasi[$r][$f]['id']] = true;
		}
		if(count($kumpulanrekomendasi[$r]) > 2){
			array_push($rekomendasi, $kumpulanrekomendasi[$r]);
		}
	}
}
echo "<pre>";
print_r($rekomendasi);
echo "</pre>";

for($t = 0; $t < count($rekomendasi); $t++){
	echo count($rekomendasi[$t]);
	if(count($rekomendasi[$t]) < 3){
		continue;
	}
	$potentials = count($rekomendasi[$t]);
	$totlat = 0.0;
	$totlng = 0.0;
	$sto_name = '';
	for($u = 0; $u < count($rekomendasi[$t]); $u++){
		$totlat += $rekomendasi[$t][$u]['lat'];
		$totlng += $rekomendasi[$t][$u]['lng'];
		$sto_name = $rekomendasi[$t][$u]['sto_name'];
	}
	//$latlng = number_format($totlat/$potentials, 6).", ".number_format($totlng/$potentials, 6);
	$latlng = getCenterlatlng($rekomendasi[$t]);
	$fk_sto_id = getSTOID($sto_name, $CON);
	$address = getAddressNearest(number_format($totlat/$potentials, 6), number_format($totlng/$potentials, 6), $rekomendasi[$t]);
	//echo $latlng."<br><br>";
	
	
	//Start from 1 again
	$query = "ALTER TABLE b_submission AUTO_INCREMENT = 1";
	//var_dump($query);
	mysqli_query($CON, $query);
	
	//insert submission, namun code id masih belum ada
	$query = "INSERT INTO `b_submission`(`fk_company_id`, `code_id`, `address`, `house_hold`, `coordinate`, `potentials`, `odp`, `fk_sto_id`, `sto_name`, `type`, `status`, `is_active`, `created_by`) VALUES (1,'PRASUB','".$address."',".($potentials*3).",'".$latlng."',".$potentials.",".ceil($potentials/8).",".$fk_sto_id.",'".$sto_name."',2,0,1,0)";
	//var_dump($query);
	mysqli_query($CON, $query);
	
	//update code id submission
	$id = mysqli_insert_id($CON);
	//$id = 18;
	$id_string = "".$id;
	$len_0 = 4-strlen($id_string);
	$code_id = "SUB";
	for($i = 0; $i < $len_0; $i++){
		$code_id .= "0";
	}
	$code_id .= $id;
	
	$query2 = "UPDATE b_submission SET `code_id` = '".$code_id."' WHERE id = ".$id;
	//var_dump($query);
	mysqli_query($CON, $query2);
	
	//update fk_submission_id
	for($u = 0; $u < count($rekomendasi[$t]); $u++){
		$id_unsc = $rekomendasi[$t][$u]['id'];
		$query3 = "UPDATE b_unsc SET `fk_submission_id` = '".$id."', status = 0 WHERE id = ".$id_unsc;
		//var_dump($query);
		mysqli_query($CON, $query3);
	}
	
	//DELETE submission, namun code id masih belum ada
	$query = "DELETE FROM subs USING `b_submission` AS subs WHERE subs.type = 2 and subs.status = 0 and NOT EXISTS (select 1 from b_unsc unsc where unsc.is_active != -1 and unsc.type = 2 and subs.id = unsc.fk_submission_id)";
	//var_dump($query);
	mysqli_query($CON, $query);
}



// $id = $mysqli->insert_id;
//echo "distance of ".getDistance(array('lat'=> -7.416655, 'lng' => 112.485729), array('lat'=> -7.416555, 'lng' => 112.489129));

//$query = mysqli_query($CON, "INSERT INTO test(`isi`) VALUES ('x')");

function getDistance($unsc1, $unsc2){
	//get distance of two points with latitude and longitude information
	$R = 6378137;
	
	$dLat = ($unsc2['lat'] - $unsc1['lat'])*(pi()/180);
	$dLong = ($unsc2['lng'] - $unsc1['lng'])*(pi()/180);
	
	$a = sin($dLat / 2) * sin($dLat / 2) + cos($unsc1['lat']*(pi()/180)) * cos($unsc2['lat']*(pi()/180)) * sin($dLong / 2) * sin($dLong / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$d = $R * $c;
	
	return $d;
}

echo "<br><br><br>";

function getSTOID($sto_name, $CON){
	$query = mysqli_query($CON,"SELECT sto.id FROM b_sto sto where sto.name = '".$sto_name."' and sto.is_active != -1");//unsc.cust_coordinate,
	//var_dump("SELECT sto.id FROM b_sto sto where sto.name = '".$sto_name."' and sto.is_active != -1");
	$id = 0;
	while($row = mysqli_fetch_assoc($query)){
		$id = $row['id'];
	}
	return $id;
}

function getAddressNearest($lat, $lng, $rekom){
	$address = "";
	$nearest = 10000;
	for($u = 0; $u < count($rekom); $u++){
		if(getDistanceByCoordinate($rekom[$u]['lat'], $rekom[$u]['lng'], $lat, $lng) < $nearest){
			$address = $rekom[$u]['cust_address'];
		}
	}
	return $address;
}

function getDistanceByCoordinate($lat1, $lng1, $lat2, $lng2){
	//get distance of two points with latitude and longitude information
	$R = 6378137;
	
	$dLat = ($lat2 - $lat1)*(pi()/180);
	$dLong = ($lng2 - $lng1)*(pi()/180);
	
	$a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1*(pi()/180)) * cos($lat2*(pi()/180)) * sin($dLong / 2) * sin($dLong / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$d = $R * $c;
	
	return $d;
}

function getCenterlatlng($rekom){
	// //cari calon rekom yang menjadi batas luar
	// $higest = null;
	// $lowest = null;
	// $mostright = null;
	// $mostleft = null;
	// $highestlat = null;
	// $lowestlat = null;
	// $mostrightlong = null;
	// $mostleftlong = null;
	// for($u = 0; $u < count($rekom); $u++){
		// //jika lebih tinggi, maka ambil sebagai batas titik tertinggi
		// if(!isset($highestlat)){
			// //pertama kali, ketika higest masih null
			// $highestlat = $rekom[$u]['lat'];
		// }elseif($highestlat < $rekom[$u]['lat']){
			// $highestlat = $rekom[$u]['lat'];
			// $higest = $rekom[$u];
		// }
		
		// //jika lebih rendah, maka ambil sebagai batas titik terendah
		// if(!isset($lowestlat)){
			// //pertama kali, ketika lowest masih null
			// $lowestlat = $rekom[$u]['lat'];
		// }elseif($lowestlat > $rekom[$u]['lat']){
			// $lowestlat = $rekom[$u]['lat'];
			// $lowest = $rekom[$u];
		// }
		
		// //jika lebih kanan, maka ambil sebagai batas titik terkanan
		// if(!isset($mostrightlong)){
			// //pertama kali, ketika mostright masih null
			// $mostrightlong = $rekom[$u]['lng'];
		// }elseif($mostrightlong < $rekom[$u]['lng']){
			// $mostrightlong = $rekom[$u]['lng'];
			// $mostright = $rekom[$u];
		// }
		
		// //jika lebih kiri, maka ambil sebagai batas titik terkiri
		// if(!isset($mostleftlong)){
			// //pertama kali, ketika mostleft masih null
			// $mostleftlong = $rekom[$u]['lng'];
		// }elseif($mostleftlong < $rekom[$u]['lng']){
			// $mostleftlong = $rekom[$u]['lng'];
			// $mostleft = $rekom[$u];
		// }
	// }
	// echo "Lowest : ".$lowestlat." Highest : ".$highestlat." mostright : ".$mostrightlong." most Left : ".$mostleftlong;
	// echo "<br><br>";
	// echo $mostleft['lng'];
	// //cari rekom yang benar - benar menjadi batas luar
	
	$x = 0.0;
	$y = 0.0;
	$z = 0.0;
	
	for($u = 0; $u < count($rekom); $u++){
		$latitude = number_format($rekom[$u]['lat'], 6) * pi() / 180;
        $longitude = number_format($rekom[$u]['lng'], 6) * pi() / 180;
		$x += cos($latitude) * cos($longitude);
		$y += cos($latitude) * sin($longitude);
		$z += sin($latitude);
	}
	
	$total = count($rekom);

	$x = $x / $total;
	$y = $y / $total;
	$z = $z / $total;

	$centralLongitude = atan2($y, $x);
	$centralSquareRoot = sqrt($x * $x + $y * $y);
	$centralLatitude = atan2($z, $centralSquareRoot);
	
	//return number_format(($centralLatitude * 180)/pi(), 6).", ".number_format($centralLongitude *180/pi(), 6);
	$patokanlat = number_format(($centralLatitude * 180)/pi(), 6);
	$patokanlng = number_format($centralLongitude *180/pi(), 6);
	echo "1 ".$patokanlat.", ".$patokanlng."<br>";
	$checkadayangdiluar = true;
	$jmlgeser = 0;
	while($checkadayangdiluar){
		$adayangdiluar = false;
		$jarakpalingjauh = 150;
		$lati = null;
		$longi = null;
		$cto_geser = 0.00001;
		$idxx = null;
		for($u = 0; $u < count($rekom); $u++){
			if(!isset($lati)){
				$lati = number_format($rekom[$u]['lat'], 6);
				$longi = number_format($rekom[$u]['lng'], 6);
				$jarakpalingjauh = getDistanceByCoordinate($lati, $longi, $patokanlat, $patokanlng);
				//$cto_geser *= ($u+1);
				$idxx = $rekom[$u]['id'];
			}elseif(getDistanceByCoordinate(number_format($rekom[$u]['lat'], 6), number_format($rekom[$u]['lng'], 6), $patokanlat, $patokanlng) > $jarakpalingjauh){
				$lati = number_format($rekom[$u]['lat'], 6);
				$longi = number_format($rekom[$u]['lng'], 6);
				$jarakpalingjauh = getDistanceByCoordinate($lati, $longi, $patokanlat, $patokanlng);
				//$cto_geser *= ($u+1);
				$idxx = $rekom[$u]['id'];
			}
			//$u = count($rekom);
		}
			//yang digeser yang paling jauh dulu
			$isgeserok = false;
			while(!$isgeserok){
				if(getDistanceByCoordinate($lati, $longi, $patokanlat, $patokanlng) > 150){
					echo "ID ".$idxx." ".$lati.", ".$longi.", ".$patokanlat.", ".$patokanlng." <br>";
					echo getDistanceByCoordinate($lati, $longi, $patokanlat, $patokanlng)." m<br>";
					$adayangdiluar = true;
					//proses geser
					if($lati < $patokanlat && $longi < $patokanlng){
						//patokan dikurangi
						//jika 
						//if(getDistanceByCoordinate($lati, $longi, ($patokanlat-$cto_geser), $patokanlng) < getDistanceByCoordinate($lati, $longi, $patokanlat, ($patokanlng-$cto_geser))){
							$patokanlat-=$cto_geser;
						//}else{
							$patokanlng-=$cto_geser;
						//}
					}elseif($lati < $patokanlat && $longi > $patokanlng){
						//patokanlat dikurangi dan patokanlng ditambah
						//jika 
						//if(getDistanceByCoordinate($lati, $longi, ($patokanlat-$cto_geser), $patokanlng) < getDistanceByCoordinate($lati, $longi, $patokanlat, ($patokanlng+$cto_geser))){
							$patokanlat-=$cto_geser;
						//}else{
							$patokanlng+=$cto_geser;
							echo getDistanceByCoordinate($lati, $longi, $patokanlat, $patokanlng)."<br>";
						//}
					}elseif($lati > $patokanlat && $longi < $patokanlng){
						//patokanlat ditambah dan patokanlng dikurangi
						//jika 
						//if(getDistanceByCoordinate($lati, $longi, ($patokanlat+$cto_geser), $patokanlng) < getDistanceByCoordinate($lati, $longi, $patokanlat, ($patokanlng-$cto_geser))){
							$patokanlat+=$cto_geser;
						//}else{
							$patokanlng-=$cto_geser;
						//}
					}else{
						//patokanlat ditambah dan patokanlng ditambah
						//jika 
						//if(getDistanceByCoordinate($lati, $longi, ($patokanlat+$cto_geser), $patokanlng) < getDistanceByCoordinate($lati, $longi, $patokanlat, ($patokanlng+$cto_geser))){
							$patokanlat+=$cto_geser;
						//}else{
							$patokanlng+=$cto_geser;
						//}
					}
				}else{
					$isgeserok = true;
				}
			}
		$checkadayangdiluar = $adayangdiluar;
		$jmlgeser++;
		if($jmlgeser > 500){
			break;
		}
	}
	echo $patokanlat.", ".$patokanlng." hasil <br>";
	return number_format($patokanlat, 6).", ".number_format($patokanlng, 6);
}
// // set your API key here
// $api_key = "AIzaSyB0F2s2R9-NXMTkfs4BkgoMv5nIfvRbExk";
// // format this string with the appropriate latitude longitude
// $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=' . $api_key;
// // make the HTTP request
// $data = @file_get_contents($url);
// // parse the json response
// $jsondata = json_decode($data,true);
// var_dump($jsondata);
// // if we get a placemark array and the status was good, get the addres
// $addr = "";
// if(is_array($jsondata )&& $jsondata ['status']=="OK")
// {
      // $addr = $jsondata ['Placemark'][0]['address'];
	  
// }
// echo "Address is ".$addr;


?>