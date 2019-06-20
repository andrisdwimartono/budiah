<?php
    //masukan nomor token Anda di sini
    //define('TOKEN','885404476:AAGwGc-T1Mu899WzjMmJq34qB1-kKTVZjOc');
    
	
    //Fungsi untuk Penyederhanaan kirim perintah dari URI API Telegram
    function BotKirim($perintah, $CON){
		$query = mysqli_query($CON,"SELECT token_telegram FROM cto_company comp
		WHERE comp.id=1");
		$TOKEN = "";
		while($row = mysqli_fetch_assoc($query)){
			 $TOKEN = $row['token_telegram'];
		}
        return 'https://api.telegram.org/bot'.$TOKEN.'/'.$perintah;
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
    function KirimPerintahStream($perintah,$data, $CON){
         $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents(BotKirim($perintah, $CON), false, $context);
        return $result;
    }
    
    function KirimPerintahCurl($perintah,$data, $CON){
		$query = mysqli_query($CON,"SELECT group_telegram_id FROM cto_company comp
		WHERE comp.id=1");
		$chat_group_id = "";
		while($row = mysqli_fetch_assoc($query)){
			 $chat_group_id = $row['group_telegram_id'];
		}
		if($data['chat_id'] != $chat_group_id){
			$header = [
			 "X-Requested-With: XMLHttpRequest",
			 "User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36" 
			];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL,BotKirim($perintah, $CON));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $post ); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($ch, CURLOPT_POST, count($data));
			
			$kembali = curl_exec ($ch);
			curl_close ($ch);
	 
			return $kembali;
		}
    }
 
    /*  Perintah untuk mendapatkan Update dari Api Telegram.
     *  Fungsi ini menjadi penting karena kita menggunakan metode "Long-Polling".
     *  Jika Anda menggunakan webhooks, fungsi ini tidaklah diperlukan lagi.
     */
     
    function DapatkanUpdate($offset, $CON) 
    {
        //kirim ke Bot
        $url = BotKirim("getUpdates", $CON)."?offset=".$offset;
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
 
    function JalankanBot($CON){
			
		$update_id  = 0; //mula-mula tepatkan nilai offset pada nol
	 
		//cek file apakah terdapat file "last_update_id"
		if (file_exists("last_update_id")) {
			//jika ada, maka baca offset tersebut dari file "last_update_id"
			$update_id = (int)file_get_contents("last_update_id");
		}
		//baca JSON dari bot, cek dan dapatkan pembaharuan JSON nya
		$updates = DapatkanUpdate($update_id, $CON);
				
		foreach ($updates as $message)
		{
				$update_id = $message["update_id"];;
				$message_data = $message["message"];
				
				//simpan new message to table cto_chat_telegram, kecuali message_id sudah ada
				save_chat($message["message"], $CON);
				
				//get command type
				$chat = getCommand($message_data, $CON);
				
				//jika terdapat text dari Pengirim
				 //if (isset($message_data["text"])) {
						$chatid = $message_data["chat"]["id"];
						$message_id = $message_data["message_id"];
						
						//$chat = chatMessageCommand($text, $chatid, $CON);
						
						// $data = array(
							// 'chat_id' => $chatid,
							// 'text'=> $chat,
							// 'parse_mode'=>'Markdown',
							// 'reply_to_message_id' => $message_id,
							// 'textmessage' => $text
						// );
						if(!checkIsReplied($chatid, $message_id, $CON)){
							$data = array(
								'chat_id' => $chatid,
								'text'=> $chat,
								'parse_mode'=>'Markdown',
								'reply_to_message_id' => $message_id
							);
							toReplied($chatid, $message_id, $CON);
						}
						//kita gunakan Kirim Perintah menggunakan metode Curl
						KirimPerintahCurl('sendMessage',$data, $CON);
					//}
				
		}
		//tulis dan tandai updatenya yang nanti digunakan untuk nilai offset
		file_put_contents("last_update_id", $update_id + 1);
    }
	
	function getCommand($message_data, $CON){
		$chat_id = $message_data["chat"]["id"];
		$message_id = $message_data["message_id"];
		//get last message command is_done = 0 by chat id
		$todo = getToDo($chat_id, $CON);
		$BUP = explode("_", $todo['message']);
		
		$text = "";
		if(isset($message_data["text"])) {
			$text = $message_data["text"];
			$text = str_replace("/","",$text);
			$text = str_replace(" ","",$text);
			$text = strtoupper($text);
		}
		
		$message = "Perintah tidak ditemukan, ketik /start untuk memulai!";
		//if(strpos($todo['message'], 'BUP') !== false){
			//return $todo['message']."x".$todo['file']."y".$message_data["chat"]["id"];
		if(strpos($todo['message'], 'BUP') !== false && count(explode("_", $todo['message'])) == 3 && strpos($text, 'START') == false){
			//return "xx";
			//return $chat_id.", ".$message_id;
			if(isset($message_data["photo"])){
				//get last odp
				$odp = checkImageODP($BUP[1], $CON);
				
				//save to b_img_progress
				//return "x";
				$last_chat_img = getLastChatImg($chat_id, $message_id, $CON);
				
				//return $last_chat_img['file']."yyy".$last_chat_img['id'];
				insertImgProgress($odp, $last_chat_img, $BUP[2], $CON);
			}
				
			//if the last message is BUP, user must send photo!
			if(checkForUpdate($BUP[1], $BUP[2], $CON)){
				$odp = checkImageODP($BUP[1], $CON);
				if(!empty($odp)){
					$message = "Upload photo untuk ODP ".$odp['LABEL_GOLIVE']." ID Deployer ".$odp['id_deployer']." Status ".$odp['status'];
				}else{
					//if not empty, all photo has uploaded
					$texting = "";
					$texting .= "Apakah anda yakin?\n";
					$texting .= "".$BUP[0]."\_".$BUP[1]."\_".$BUP[2]."\_1 : Ya\n";
					$texting .= "/".getCodeID($BUP[1], $CON)." : Tidak\n";
					$message = $texting;
					chatToDone($todo['chat_id'], $todo['message_id'], $CON);
				}
			}
		}
		
		if(isset($message_data["text"])) {
			$text = $message_data["text"];
			$text = str_replace("/","",$text);
			$text = str_replace(" ","",$text);
			$text = strtoupper($text);
			//message must contains one of the commands
			if(strpos($text, 'START') !== false){
				//User write start
				$message = "Masukkan ID Submission!";
			}elseif(strpos($text, 'SUB') !== false){
				//check submission
				$subm = checkSubmission($text, $CON);
				if(count($subm) > 0){
					$texting = "";
					$texting .= "Submission yang Anda maksud adalah"
						."\nSubmission ID : ".$subm['code_id']
						."\n Status : ".$subm['status_name']
						."\n Alamat : ".$subm['address']
						."\n Tikor : ".$subm['coordinate']
						."\n STO : ".$subm['sto_name'];
					if($subm['status'] < 2){
						$texting .= "\n\nOleh karena status masih belum di-approve dan/atau belum dilakukan insert ID, maka tidak dapat melakukan update progress";
					}else{
						$texting .= "\n\nKetuk link perintah dibawah untuk meng-update/ubah progress\n";
						$texting .= progressUpdateLink($subm['id'], $subm['status'], $CON);
					}
					$message = $texting;
				}else{
					$message = 'Submission tidak ditemukan!';
					//update is_done to 1
					chatToDone($message_data['chat_id'], $message_data['message_id'], $CON);
				}
				
			}elseif(strpos($text, 'BUP') !== false){
				$BUP = explode("_", $text);
				if(count($BUP) == 4){
					//jika 1, maka update. Namun jika 0 maka tidak boleh
					if($BUP[3] == 1){
						if(checkForUpdate($BUP[1], $BUP[2], $CON) && empty(checkImageODP($BUP[1], $CON))){
							//updating
							$updated_by = getUserId($chatid, $CON);
							if(updating("subs.status = ".$BUP[2].", chat_id_telegram = '".$chatid."', updated_by = '".$updated_by."'", $BUP[1],  $CON)){
								$texting = "";
								$texting .= getCodeID($BUP[1], $CON)." berhasil diupdate!\n";
								$texting .= "/".getCodeID($BUP[1], $CON)." : Kembali\n";
								$message = $texting;
							}
						}else{
							$texting = "";
							$texting .= "Tidak bisa mengupdate progress mundur, update progress loncat lebih dari 1 level,  Submission tidak ada, atau Submission belum di-approve\n";
							$texting .= "/".getCodeID($BUP[1], $CON)." : Kembali\n";
							$texting .= "/start : Awal\n";
							$message = $texting;
							chatToDone($message_data['chat_id'], $message_data['message_id'], $CON);
						}
					}else{
						$texting = "";
						$texting .= "Perintah tidak diketahui\n";
						$texting .= "/start : Awal\n";
						$message = $texting;
						chatToDone($message_data['chat_id'], $message_data['message_id'], $CON);
					}
				}
			}
			
			
			
			//User write SUB, use this submission
			
			//User write BUP 3 array untuk upload foto semua ODP -> BUP is_done = 0
			
			//user upload first, second, third etc ODP photos -> BUP is_done = 0
			
			//if all photo in this stage uploaded, user write BUP 4 array to update -> BUP is_done = 1
			
		}
		
		return $message;
	}
	
	function checkIsReplied($chat_id, $message_id, $CON){
		$query = mysqli_query($CON,"SELECT count(*) jml FROM `cto_chat_telegram` WHERE chat_id = ".$chat_id." AND message_id = ".$message_id." AND is_replied = 1");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		if($jml > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function toReplied($chat_id, $message_id, $CON){
		mysqli_query($CON, "UPDATE cto_chat_telegram SET is_replied = 1 WHERE chat_id = ".$chat_id." AND message_id = ".$message_id."");
	}
	
	function getToDo($chat_id, $CON){
		$query = mysqli_query($CON,"SELECT * FROM cto_chat_telegram cct WHERE (cct.message LIKE '%SUB%' OR cct.message LIKE '%BUP%') AND cct.is_done = 0 AND cct.chat_id = ".$chat_id." ORDER BY cct.id DESC LIMIT 1");
		$todo = array();
		while($row = mysqli_fetch_assoc($query)){
			 $todo = $row;
		}
		return $todo;
	}
	
	function getLastChatImg($chat_id, $message_id, $CON){
		$query = mysqli_query($CON,"SELECT * FROM cto_chat_telegram cct WHERE (cct.file IS NOT NULL AND cct.file != '') AND cct.chat_id = ".$chat_id." AND cct.message_id = ".$message_id." ORDER BY cct.id DESC LIMIT 1");
		$last_chat_img = array();
		while($row = mysqli_fetch_assoc($query)){
			 $last_chat_img = $row;
		}
		return $last_chat_img;
	}
	
	function chatToDone($chat_id, $message_id, $CON){
		mysqli_query($CON, "UPDATE cto_chat_telegram SET is_done = 1 WHERE chat_id = ".$chat_id." AND message_id = ".$message_id."");
	}
	
	function checkImageODP($fk_submission_id, $CON){
		$query = mysqli_query($CON,"SELECT odp.id, odp.id_deployer, odp.LABEL_GOLIVE, coalesce(dict.name, subs.status) status, img.id id_img FROM b_odp odp 
		INNER JOIN b_submission subs on subs.id = odp.fk_submission_id
		LEFT JOIN b_img_progress img on img.fk_odp_id = odp.id AND img.status = subs.status+1
		LEFT JOIN cto_dict dict on dict.code = subs.status+1 and dict.type = 'STATUS_RECOM'
		WHERE odp.fk_submission_id = ".$fk_submission_id." AND img.id is null
		ORDER BY odp.id ASC LIMIT 1");
		$odp = array();
		while($row = mysqli_fetch_assoc($query)){
			 $odp = $row;
		}
		return $odp;
	}
	
	function insertImgProgress($odp, $last_chat_img, $status_submission, $CON){
		$query = mysqli_query($CON,"SELECT count(*) jml FROM `b_img_progress` WHERE fk_chat_id = '".$last_chat_img['id']."'");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		if($jml <= 0){
			mysqli_query($CON, "INSERT INTO b_img_progress(fk_odp_id, status, img, created_by) VALUES (".$odp['id'].", ".$status_submission.", '".$last_chat_img['file']."', ".$last_chat_img['fk_user_id'].")");
		}
	}
	
	function DapatkanFilePath($perintah, $CON) 
    {
        //kirim ke Bot
        $url = $perintah;
        //dapatkan hasilnya berupa JSON
        $kirim = file_get_contents($url);
        //kemudian decode JSON tersebut
        $hasil = json_decode($kirim, true);
        if ($hasil["ok"])
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
		
	function save_chat($message_data, $CON){
		if(!checkChatId($message_data['chat']['id'], $CON)){
			//if not registered in budiah, cannot save
			return false;
		}
		$query = mysqli_query($CON,"SELECT count(*) jml FROM `cto_chat_telegram` WHERE message_id = '".$message_data['message_id']."' AND chat_id = '".$message_data['chat']['id']."'");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		if($jml <= 0){
			$fk_user_id = getUserId($message_data['chat']['id'], $CON);
			$file_id = "";
			$filename = "";
			if(isset($message_data["photo"])){
				$photono = 0;
				$x = 0;
				foreach($message_data["photo"] as $ph){
					if($message_data["photo"][$x]["file_size"] < 1000000){
						$photono = $x;
					}else{
						break;
					}
					$x++;
				}
				$file_id = $message_data["photo"][$photono]["file_id"];
				
				$query = mysqli_query($CON,"SELECT token_telegram, file_path_photo FROM cto_company comp
				WHERE comp.id=1");
				$TOKEN = "";
				$file_path_photo = "public_html/uploads/images/";
				while($row = mysqli_fetch_assoc($query)){
					 $TOKEN = $row['token_telegram'];
					 $file_path_photo = $row['file_path_photo'];
				}
				
				$filemessage = DapatkanFilePath('https://api.telegram.org/bot' . $TOKEN . '/getFile?file_id=' . $file_id, $CON);
				$filename = $filemessage["file_path"];
				$ext = explode(".", $filename);
				$ext = $ext[count($ext)-1];
				
				$ch = curl_init('https://api.telegram.org/file/bot'.$TOKEN.'/'.$filename);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				$raw=curl_exec($ch);
				curl_close ($ch);
				$saveto = "public_html/uploads/".$message_data["chat"]["id"]."_".$message_data['message_id']."_".$file_id.".".$ext;
				if(file_exists($saveto)){
					unlink($saveto);
				}
				$fp = fopen($saveto,'x');
				fwrite($fp, $raw);
				fclose($fp);
				$query = mysqli_query($CON, "INSERT INTO `cto_chat_telegram`(`fk_user_id`, `chat_id`, `message_id`, `message`, `file_id`, `file`, `created_by`) VALUES (".$fk_user_id.", ".$message_data["chat"]["id"].", ".$message_data['message_id'].", '".$message_data['text']."', '".$file_id."', '".$message_data["chat"]["id"]."_".$message_data['message_id']."_".$file_id.".".$ext."', ".$fk_user_id.")");
			}elseif(isset($message_data["text"])) {
				$text = $message_data["text"];
				$text = str_replace("/","",$text);
				$text = str_replace(" ","",$text);
				$text = strtoupper($text);
				//message must contains one of the commands
				if(strpos($text, 'SUB') !== false || strpos($text, 'START') !== false || strpos($text, 'BUP') !== false){
					$query = mysqli_query($CON, "INSERT INTO `cto_chat_telegram`(`fk_user_id`, `chat_id`, `message_id`, `message`, `file_id`, `file`, `created_by`) VALUES (".$fk_user_id.", ".$message_data["chat"]["id"].", ".$message_data['message_id'].", '".$message_data['text']."', '".$file_id."', '".$filename."', ".$fk_user_id.")");
				}
			}
		}
	}
		
	function chatMessageCommand($text, $chatid, $CON){
		if(!checkChatId($chatid, $CON)){
			//if not registered in budiah, cannot chat
			return "Maaf, id telegram anda tidak terdaftar di aplikasi Budiah.com. Silahkan kontak admin Budiah.com untuk mendaftarkan id telegram anda.";
		}
		
		$text = str_replace("/","",$text);
		$text = str_replace(" ","",$text);
		$text = strtoupper($text);
		switch ($text){
			case 'START' :
				return "Masukkan No ID Submission";
			break;
			default :
				if(strpos($text, 'SUB') !== false){
					//check submission
					$subm = checkSubmission($text, $CON);
					if(count($subm) > 0){
						$texting = "";
						$texting .= "Submission yang Anda maksud adalah"
							."\nSubmission ID : ".$subm['code_id']
							."\n Status : ".$subm['status_name']
							."\n Alamat : ".$subm['address']
							."\n Tikor : ".$subm['coordinate']
							."\n STO : ".$subm['sto_name'];
						if($subm['status'] < 2){
							$texting .= "\n\nOleh karena status masih belum di-approve dan/atau belum dilakukan insert ID, maka tidak dapat melakukan update progress";
						}else{
							$texting .= "\n\nKetuk link perintah dibawah untuk meng-update/ubah progress\n";
							$texting .= progressUpdateLink($subm['id'], $subm['status'], $CON);
						}
						return $texting;
					}else{
						return 'Submission tidak ditemukan';
					}
				}elseif(strpos($text, 'BUP') !== false){
					$BUP = explode("_", $text);
					if(count($BUP) == 3){
						//lari ke anda yakin?
						$texting = "";
						$texting .= "Apakah anda yakin?\n";
						$texting .= "/".$BUP[0]."\_".$BUP[1]."\_".$BUP[2]."\_1 : Ya\n";
						$texting .= "/".getCodeID($BUP[1], $CON)." : Tidak\n";
						
						return $texting;
					}elseif(count($BUP) == 4){
						//jika 1, maka update. Namun jika 0 maka tidak boleh
						if($BUP[3] == 1){
							if(checkForUpdate($BUP[1], $BUP[2], $CON)){
								//updating
								$updated_by = getUserId($chatid, $CON);
								if(updating("subs.status = ".$BUP[2].", chat_id_telegram = '".$chatid."', updated_by = '".$updated_by."'", $BUP[1],  $CON)){
									$texting = "";
									$texting .= getCodeID($BUP[1], $CON)." berhasil diupdate!\n";
									$texting .= "/".getCodeID($BUP[1], $CON)." : Kembali\n";
									return $texting;
								}
							}else{
								$texting = "";
								$texting .= "Tidak bisa mengupdate progress mundur, update progress loncat lebih dari 1 level,  Submission tidak ada, atau Submission belum di-approve\n";
								$texting .= "/".getCodeID($BUP[1], $CON)." : Kembali\n";
								$texting .= "/start : Awal\n";
								return $texting;
							}
						}else{
							$texting = "";
							$texting .= "Perintah tidak diketahui\n";
							$texting .= "/start : Awal\n";
							return $texting;
						}
					}
				}else{
					return "Selamat Datang! Ketik /start untuk memulai menu";
				}
			break;
		}
	}
	
	function checkSubmission($text, $CON){
		$subm = array();
		$query = mysqli_query($CON,"SELECT subm.id, subm.code_id, case when odp.odp_count_ready is null or odp.odp_count_ready = 0 then 1 else subm.status end status, subm.address, subm.coordinate, sto.name sto_name, case when odp.odp_count_ready is null or odp.odp_count_ready = 0 then CONCAT(dict.name, '(Insert ID belum dilakukan)') else dict.name end status_name FROM `b_submission` subm 
		LEFT JOIN cto_dict dict on dict.code = subm.status and dict.type = 'STATUS_RECOM'
		LEFT JOIN b_sto sto on sto.id = subm.fk_sto_id
		left join (select COUNT(*) odp_count_ready, odpr.fk_submission_id from b_odp odpr where odpr.id_deployer is not null and odpr.LABEL_GOLIVE is not null and odpr.LABEL_GOLIVE != '#N/A' GROUP BY odpr.fk_submission_id) odp on odp.fk_submission_id = subm.id
		WHERE subm.is_active != -1 and subm.code_id = '".$text."'");
		while($row = mysqli_fetch_assoc($query)){
			$subm = $row;
		}
		return $subm;
	}
	
	function progressUpdateLink($id, $status_current, $CON){
		$query = mysqli_query($CON,"SELECT * FROM cto_dict dict
		WHERE dict.type = 'STATUS_RECOM' and dict.code = (".$status_current."+1)");
		$texting = '';
		while($row = mysqli_fetch_assoc($query)){
			 $texting .= "/BUP\_".$id."\_".$row['code']." : ".$row['name']."\n";
		}
		return $texting;
	}
	
	function getCodeID($id, $CON){
		$query = mysqli_query($CON,"SELECT * FROM b_submission subs
		WHERE subs.is_active != -1 and subs.id = ".$id);
		$code_id = "";
		while($row = mysqli_fetch_assoc($query)){
			 $code_id = $row['code_id'];
		}
		return $code_id;
	}
	
	function checkForUpdate($id, $status, $CON){
		$query = mysqli_query($CON,"SELECT count(*) jml FROM b_submission subs
		WHERE subs.is_active != -1 and subs.id = ".$id." and subs.status >= 2 and ".$status." = (subs.status+1)");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		if($jml > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function checkChatId($chatid, $CON){
		$query = mysqli_query($CON,"SELECT count(*) jml FROM cto_user usr
		WHERE usr.is_active != -1 and usr.chat_id_telegram = '".$chatid."'");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		if($jml > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function getUserId($chatid, $CON){
		$query = mysqli_query($CON,"SELECT id FROM cto_user usr
		WHERE usr.is_active != -1 and usr.chat_id_telegram = '".$chatid."' limit 1");
		$id = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $id = $row['id'];
		}
		return $id;
	}
        
	function updating($updating, $id,  $CON){
		$query = mysqli_query($CON, "UPDATE `b_submission` subs SET ".$updating." WHERE id = ".$id);
		if($query){
			//check if live, then send message to group_telegram_id
			checkLive($id,  $CON);
			return true;
		}else{
			return false;
		}
	}
	function checkLive($id,  $CON){
		$query = mysqli_query($CON,"select subs.id, subs.code_id, subs.coordinate, subs.address, subs.potentials, coalesce(subs.odp, CEILING(subs.potentials/2.0)) as odp, sto.name as sto_name, coalesce(dict.name, subs.status) status, coalesce(dictnext.name, subs.status+1) status_next, coalesce(case when subs.status = dictgolive.code then 100 else percen.percentage end, 0) percentage, coalesce(case when subs.status+1 = dictgolive.code then 100 else percen_onwork.percentage end, 0) percentage_onwork, coalesce(subs.updated_time, subs.created_time) as updated_time, odp_lab.LABEL_GOLIVE from b_submission as subs
			inner join b_sto sto on sto.id = subs.fk_sto_id
			left join cto_dict dict on dict.code = subs.status and dict.type = 'STATUS_RECOM'
			left join cto_dict dictnext on dictnext.code = subs.status+1 and dictnext.type = 'STATUS_RECOM'
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage FROM `cto_dict` dict left join (SELECT count(*) jml FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2) jml on 1=1 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen on percen.code = subs.status
			left join (SELECT dict.code, (dict.code-appr.appr)/jml.jml*100 percentage FROM `cto_dict` dict left join (SELECT count(*) jml FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code > 2) jml on 1=1 left join (SELECT code appr FROM `cto_dict` WHERE type = 'STATUS_RECOM' AND code = 2) appr on 1=1 where dict.code > 2 and dict.type = 'STATUS_RECOM') percen_onwork on percen_onwork.code = subs.status+1
			left join cto_dict dictgolive on dictgolive.type = 'STATUS_RECOM' and dictgolive.info = 'GOLIVE'
			left join (SELECT GROUP_CONCAT(odp_lab.LABEL_GOLIVE) LABEL_GOLIVE, odp_lab.fk_submission_id FROM b_odp odp_lab group by odp_lab.fk_submission_id) odp_lab on odp_lab.fk_submission_id = subs.id
			where subs.status >= 1 and subs.is_active != -1 and subs.id = ".$id);
		$percentage = 0;
		$LABEL_GOLIVE = "";
		$address = "";
		$coordinate = "";
		while($row = mysqli_fetch_assoc($query)){
			$percentage = $row['percentage'];
			$LABEL_GOLIVE = $row['LABEL_GOLIVE'];
			$address = $row['address'];
			$coordinate = $row['coordinate'];
		}
		if($percentage >= 100){
			$text = "ODP No ".$LABEL_GOLIVE." sudah live!"."\nAlamat : ".$address."\nTikor : ".$coordinate;
			$data = array(
				'textmessage' => $text
			);
			KirimPerintahCurlToGroup('sendMessage',$data, $CON);
			return true;
		}else{
			return false;
		}
	}
	
	function KirimPerintahCurlToGroup($perintah,$data, $CON){
		$query = mysqli_query($CON,"SELECT group_telegram_id FROM cto_company comp
		WHERE comp.id=1");
		$chat_group_id = "";
		while($row = mysqli_fetch_assoc($query)){
			 $chat_group_id = $row['group_telegram_id'];
		}
		$data['chat_id'] = $chat_group_id;
		$chatid = $data['chat_id']; // ini id saya di telegram @hasanudinhs silakan diganti dan disesuaikan
		$pesan 	= $data['textmessage'];
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
	
    function gantiMenit($CON){
		//check jika sudah di menit sama + 57 s, stt false
		//$query = mysqli_query($CON,"SELECT count(*) jml FROM `cto_timer` where (date_format(time, '%i') = date_format(now(), '%i') and date_format(now(), '%s') >= 50) or (date_format(time, '%i') < date_format(now(), '%i'))");
	    $query = mysqli_query($CON,"SELECT count(*) jml FROM `cto_timer` where date_format(time, '%i') < date_format(now(), '%i')");
		$jml = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml = $row['jml'];
		}
		
		$query = mysqli_query($CON,"SELECT count(*) jml FROM `cto_timer` where (date_format(time, '%i') = date_format(now(), '%i') and date_format(now(), '%s') >= 50)");
		$jml2 = 0;
		while($row = mysqli_fetch_assoc($query)){
			 $jml2 = $row['jml'];
		}
		
		//kalau jml > 0, maka sudah seharusnya berhenti
		if($jml > 0 || $jml2 > 0){
			//update ke db
			$query = mysqli_query($CON, "UPDATE `cto_timer` timer SET time = now() WHERE id = 1");
			return false;
		}else{
			return true;
		}
	}
	
    $wkt = 0;
	$stt = true;
	require_once('connection.php');
    while($stt){
        sleep(2); //beri jedah 2 detik
		$wkt ++;
		if($wkt >= 20){
			$stt = false;
	    }
		if(!gantiMenit($CON)){
			$stt = false;
		}
        JalankanBot($CON);
    }
	?>