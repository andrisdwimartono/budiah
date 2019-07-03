<?php
require_once('connection.php');
//define('TOKEN','885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc');

// $fk_company_id = array();
// $stts = array();
// $query = mysqli_query($CON,"SELECT * FROM `b_odp` comp WHERE comp.is_active != -1");
// while($row = mysqli_fetch_assoc($query)){
	// $fk_company_id[$row['name']] = $row['id'];
// }


$url = 'https://script.googleusercontent.com/macros/echo?user_content_key=7PriUFzUhEEvMNL4kZxJobkgtwjLiME85EPHEbtcsqmjUzxmHld5bJbtr5RJ4E810dXdMYUVmPzGu9sbQ5yMDRLsUkRZwa2MOJmA1Yb3SEsKFZqtv3DaNYcMrmhZHmUMWojr9NvTBuBLhyHCd5hHa-1JlmY0fct_FPIbQpBdk39delR1lmJtWJrCE8wlDNSSegdPdTHREPuvH0bcvf3IaAfNP9yhFgqEuOvyxc4Lc5aEWjqxbVd8Uy25oPFJ5yVsViFtmIJhAsKmBgycYINP7lDLNSkgq3bkzd_dgT7OAiY27B8Rh4QJTQ&lib=M6WO-FIF0Sr0-Phaxm2xE2Ac0iBT6afy9';
//$data = array('SearchText' => 'MEDAN', 'ScNoss' => 'true', 'Field' => 'ORG', 'limit' => '10', 'start' => '0', 'page' => '1');
$data = array();
$options = array(
        'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
		'header' => "Content-Length: 0\r\n",
        'method'  => 'GET'
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$array = json_decode( $result, true );
echo $array['status'];

foreach($array['data'] as $x){
	echo "<pre>";
	print_r($x);
	echo "</pre>";
	echo $x['RESUME'];
	echo "<br>";
	//if($x['No'] != 'No'){
		if(checkODPidExist($x['No'], $CON)){
			inserting("(".$x['No'].", '".$x['RESUME']."', '".str_replace("z", " ", str_replace("T", " ", $x['Tanggal_Input_GDOC']))."', '".$x['ID_DEPLOYER/_ID_SPK/_ID_ISS']."', '".$x['STATUS_KONSTRUKSI']."', '".$x['VALIDASI_MANCORE']."', '".$x['STATUS_GOLIVE']."', '".$x['KETERANGAN_KONSTRUKSI']."', '".$x['KETERANGAN_VALIDASI_MANCORE']."', '".$x['LABEL_GOLIVE']."', '".$x['ID_PENDUKUNG_(SC/_INET/_VOICE/_TICERS/_IN/_MYI)']."', '".$x['Nama_Pelanggan']."', '".$x['Tikor_Pelanggan_(VALID)']."', '".$x['Alamat']."', '".$x['STO']."')", $CON);
			if($x['STATUS_GOLIVE'] == "GOLIVE" && (strpos("x".$x['LABEL_GOLIVE'],"ODP-") == 1)){
				//tele live
				echo "live";
				KirimPerintahCurl(array('chat_id' => 1, 'textmessage' => $x['No'], 'data_odp' => $x), $CON);
			}
			
		}else{
			echo "sudah ada".$x['No'];
			// if($x['No'] == 1){
				// $x['LABEL_GOLIVE'] = "x";
				// $x['STATUS_GOLIVE'] = "x";
				// echo "ini".$x['STATUS_GOLIVE'];
				
			// }
			//jika status berubah dari #N/A jadi live, maka langsung message
			if($x['STATUS_GOLIVE'] == "GOLIVE" && (strpos("x".$x['LABEL_GOLIVE'],"ODP-") == 1 && checkODPidStatus($x['No'], $x['STATUS_GOLIVE'], $x['LABEL_GOLIVE'], $CON))){
				//tele live
				echo "live";
				KirimPerintahCurl(array('chat_id' => 1, 'textmessage' => $x['No'], 'data_odp' => $x), $CON);
			}
			updating("resume = '".$x['RESUME']."', tgl_order = '".str_replace("z", " ", str_replace("T", " ", $x['Tanggal_Input_GDOC']))."', id_deployer = '".$x['ID_DEPLOYER/_ID_SPK/_ID_ISS']."', status_konstruksi = '".$x['STATUS_KONSTRUKSI']."', validasi_mancore = '".$x['VALIDASI_MANCORE']."', status_golive = '".$x['STATUS_GOLIVE']."', KETERANGAN_KONSTRUKSI = '".$x['KETERANGAN_KONSTRUKSI']."', KETERANGAN_VALIDASI_MANCORE = '".$x['KETERANGAN_VALIDASI_MANCORE']."', LABEL_GOLIVE = '".$x['LABEL_GOLIVE']."', ID_PENDUKUNG = '".$x['ID_PENDUKUNG_(SC/_INET/_VOICE/_TICERS/_IN/_MYI)']."', Nama_Pelanggan = '".$x['Nama_Pelanggan']."', Tikor_Pelanggan = '".$x['Tikor_Pelanggan_(VALID)']."', Alamat = '".$x['Alamat']."', STO = '".$x['STO']."'", $x['No'], $CON);
		}
	//}
	echo "<br>";
}


function inserting($inserting, $CON){
	$query = mysqli_query($CON, "INSERT INTO `b_odp_api` (`id`, `resume`, `tgl_order`, `id_deployer`, `status_konstruksi`, `validasi_mancore`, `status_golive`, `KETERANGAN_KONSTRUKSI`, `KETERANGAN_VALIDASI_MANCORE`, `LABEL_GOLIVE`, `ID_PENDUKUNG`, `Nama_Pelanggan`, `Tikor_Pelanggan`, `Alamat`, `STO`) VALUES ".$inserting);
}

function updating($updating, $id,  $CON){
	$query = mysqli_query($CON, "UPDATE `b_odp_api` SET ".$updating." WHERE id = ".$id);
	if($query){
		return true;
	}else{
		return false;
	}
}

function checkODPidExist($id, $CON){
	$jml = 0;
	$query = mysqli_query($CON,"SELECT count(*) jml FROM `b_odp_api` odp WHERE odp.id = ".$id);
	while($row = mysqli_fetch_assoc($query)){
		$jml = $row['jml'];
	}
	if($jml > 0){
		return false;
	}else{
		return true;
	}
}

function checkODPidStatus($id, $statuslive, $labelgolive, $CON){
	$jml = 0;
	$query = mysqli_query($CON,"SELECT count(*) jml FROM `b_odp_api` odp WHERE odp.id = ".$id." and (odp.status_golive != 'GOLIVE' AND odp.LABEL_GOLIVE not like '%ODP-%')");
	while($row = mysqli_fetch_assoc($query)){
		$jml = $row['jml'];
	}
	if($jml > 0){
		//if($statuslive != '')
		return true;
	}else{
		return false;
	}
}

function KirimPerintahCurl($data, $CON){
		$query = mysqli_query($CON,"SELECT group_telegram_id FROM cto_company comp
		WHERE comp.id=1");
		$chat_group_id = "";
		while($row = mysqli_fetch_assoc($query)){
			 $chat_group_id = $row['group_telegram_id'];
		}
		$data['chat_id'] = $chat_group_id;
		$chatid = $data['chat_id']; // ini id saya di telegram @hasanudinhs silakan diganti dan disesuaikan
		$pesan 	= "ODP No ".$data['textmessage']." ".$data['data_odp']['LABEL_GOLIVE']." sudah live!"."\nAlamat : ".$data['data_odp']['Alamat']."\nTikor : ".$data['data_odp']['Tikor_Pelanggan_(VALID)'];
		// ----------- code -------------
		$query = mysqli_query($CON,"SELECT token_telegram FROM cto_company comp
		WHERE comp.id=1");
		$TOKEN = "";
		while($row = mysqli_fetch_assoc($query)){
			 $TOKEN = $row['token_telegram'];
		}
		$method	= "sendMessage";
		$url    = "https://api.telegram.org/bot" . $TOKEN . "/". $method;
		$post = [
		 'chat_id' => $chatid,
		 // 'parse_mode' => 'HTML', // aktifkan ini jika ingin menggunakan format type HTML, bisa juga diganti menjadi Markdown
		 'text' => $pesan
		];
		$header = [
		 "X-Requested-With: XMLHttpRequest",
		 "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36" 
		];
		// hapus 1 baris ini:
		//die('Hapus baris ini sebelum bisa berjalan, terimakasih.');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_REFERER, $refer);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post );   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$datas = curl_exec($ch);
		$error = curl_error($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$debug['text'] = $pesan;
		$debug['code'] = $status;
		$debug['status'] = $error;
		$debug['respon'] = json_decode($datas, true);
 
        return $datas;
    }

?>