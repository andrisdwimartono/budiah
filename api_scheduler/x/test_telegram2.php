<?php
    //masukan nomor token Anda di sini
    define('TOKEN','885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc');
    
    //Fungsi untuk Penyederhanaan kirim perintah dari URI API Telegram
    function BotKirim($perintah){
        return 'https://api.telegram.org/bot'.TOKEN.'/'.$perintah;
    }
 
    /* Fungsi untuk mengirim "perintah" ke Telegram
     * Perintah tersebut bisa berupa
     *  -SendMessage = Untuk mengirim atau membalas pesan
     *  -SendSticker = Untuk mengirim pesan
     *  -Dan sebagainya, Anda bisa memm
     * 
     * Adapun dua fungsi di sini yakni pertama menggunakan
     * stream dan yang kedua menggunkan curl
     * 
     * */
    function KirimPerintahStream($perintah,$data){
         $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents(BotKirim($perintah), false, $context);
        return $result;
    }
    
    function KirimPerintahCurl($perintah,$data){
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL,BotKirim($perintah));
        // curl_setopt($ch, CURLOPT_POST, count($data));
        // curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // $kembali = curl_exec ($ch);
        // curl_close ($ch);
		
		// $data = array(
                                // 'chat_id' => $chatid,
                                // 'text'=> 'tes balas halo',
                                // 'parse_mode'=>'Markdown',
                                // 'reply_to_message_id' => $message_id
                            // );
		
		//$TOKEN  = "885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc";  // ganti token ini dengan token bot mu
		$chatid = $data['chat_id']; // ini id saya di telegram @hasanudinhs silakan diganti dan disesuaikan
		$pesan 	= "Helo ..!".$data['textmessage'];
		// ----------- code -------------
		$method	= "sendMessage";
		$url    = "https://api.telegram.org/bot" . TOKEN . "/". $method;
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
 
    /*  Perintah untuk mendapatkan Update dari Api Telegram.
     *  Fungsi ini menjadi penting karena kita menggunakan metode "Long-Polling".
     *  Jika Anda menggunakan webhooks, fungsi ini tidaklah diperlukan lagi.
     */
     
    function DapatkanUpdate($offset) 
    {
        //kirim ke Bot
        $url = BotKirim("getUpdates")."?offset=".$offset;
        //dapatkan hasilnya berupa JSON
        $kirim = file_get_contents($url);
        //kemudian decode JSON tersebut
        $hasil = json_decode($kirim, true);
        if ($hasil["ok"]==1)
            {
                /* Jika hasil["ok"] bernilai satu maka berikan isi JSONnya.
                 * Untuk dipergunakan mengirim perintah balik ke Telegram
                 */
                return $hasil["result"];
            }
        else
            {   /* Jika tidak maka kosongkan hasilnya.
                 * Hasil harus berupa Array karena kita menggunakan JSON.
                 */
                return array();
            }
    }
 
    function JalankanBot()
        {
            $update_id  = 0; //mula-mula tepatkan nilai offset pada nol
         
            //cek file apakah terdapat file "last_update_id"
            if (file_exists("last_update_id")) {
                //jika ada, maka baca offset tersebut dari file "last_update_id"
                $update_id = (int)file_get_contents("last_update_id");
            }
            //baca JSON dari bot, cek dan dapatkan pembaharuan JSON nya
            $updates = DapatkanUpdate($update_id);
                    
            foreach ($updates as $message)
            {
                    $update_id = $message["update_id"];;
                    $message_data = $message["message"];
                    
                    //jika terdapat text dari Pengirim
                     if (isset($message_data["text"])) {
                            $chatid = $message_data["chat"]["id"];
                            $message_id = $message_data["message_id"];
                            $text = $message_data["text"];
                            
                            $data = array(
                                'chat_id' => $chatid,
                                'text'=> 'tes balas halo',
                                'parse_mode'=>'Markdown',
                                'reply_to_message_id' => $message_id,
								'textmessage' => $text
                            );
                            //kita gunakan Kirim Perintah menggunakan metode Curl
                            KirimPerintahCurl('sendMessage',$data);
                        }
                    
            }
            //tulis dan tandai updatenya yang nanti digunakan untuk nilai offset
            file_put_contents("last_update_id", $update_id + 1);
        }
        
    while(true){
        sleep(2); //beri jedah 2 detik
        JalankanBot();
    }
	?>