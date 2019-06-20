<?php
// $token = "885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc";
// $chatid = "@cakton27";
// sendMessage($chatid, "Hello World", $token);

// function sendMessage($chatID, $messaggio, $token) {
    // echo "sending message to " . $chatID . "\n";

    // $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    // $url = $url . "&text=" . urlencode($messaggio);
    // $ch = curl_init();
    // $optArray = array(
            // CURLOPT_URL => $url,
            // CURLOPT_RETURNTRANSFER => true
    // );
    // curl_setopt_array($ch, $optArray);
    // $result = curl_exec($ch);
    // curl_close($ch);
    // return $result;
// }

$TOKEN  = "885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc";  // ganti token ini dengan token bot mu
$chatid = "782272020"; // ini id saya di telegram @hasanudinhs silakan diganti dan disesuaikan
$pesan 	= "Helo mas.. \n\neh salah orang, ya maap!";
// ----------- code -------------
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
print_r($debug);
?>