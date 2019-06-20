<?php
//echo getDistanceByCoordinate(-7.41658, 112.487629, -7.416555, 112.489129);
echo getDistanceByCoordinate(-7.416580, 112.487629, -7.416581, 112.487629);

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