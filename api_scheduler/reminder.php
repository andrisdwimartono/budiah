<?php
require_once('connection.php');
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$query = mysqli_query($CON,"SELECT subs.code_id, subs.status status_code, CASE WHEN COALESCE(odp.jml, 0) = 0 AND subs.status = 2 THEN 'Add ID Deployer dan ODP Name' ELSE dictstatus.name END status_next, COALESCE(odp.jml, 0) jml_odp, subs.updated_time, odplist.id_deployer, odplist.label_golive FROM `b_submission` subs
LEFT JOIN cto_dict dict on dict.type = 'REMINDER_TO_UPDATE'
LEFT JOIN cto_dict dictstatus on dictstatus.code = subs.status+1 AND dictstatus.type = 'STATUS_RECOM' AND (dictstatus.type2 = subs.pt_type or (subs.status+1 <= 2 AND dictstatus.type2 is null))
LEFT JOIN cto_dict dictgolive on dictgolive.info = 'GOLIVE' AND dictgolive.type = 'STATUS_RECOM' AND (dictgolive.type2 = subs.pt_type or (subs.status <= 2 AND dictgolive.type2 is null))
LEFT JOIN (SELECT COUNT(*) jml, odp.fk_submission_id FROM b_odp odp GROUP BY odp.fk_submission_id) odp ON odp.fk_submission_id = subs.id
LEFT JOIN (SELECT GROUP_CONCAT(odplist.id_deployer) id_deployer, GROUP_CONCAT(odplist.LABEL_GOLIVE) label_golive, odplist.fk_submission_id FROM b_odp odplist GROUP BY odplist.fk_submission_id) odplist on odplist.fk_submission_id = subs.id
WHERE COALESCE(DATEDIFF(now(), subs.updated_time), 0) >= dict.code AND MOD(COALESCE(DATEDIFF(now(), subs.updated_time), 0), dict.code) = 0 AND subs.status > 0 AND subs.status < dictgolive.code");
while($row = mysqli_fetch_assoc($query)){
	
	if($row['status_code'] == 1){
		//setelah submit, perlu dilakukan approval oleh manager optima
		$queryy = mysqli_query($CON,"SELECT user.chat_id_telegram, user.email, user.name personil_name FROM `cto_dict` dictjab 
					INNER JOIN cto_user user on user.position = dictjab.name
					WHERE dictjab.info = 'MGR_USER' AND user.is_active != -1");
		$manager_name = "";
		while($rowy = mysqli_fetch_assoc($queryy)){
			$subject = 'Budiah : Reminder Update Progress';
			$id_dep = "";
			if($row['jml_odp'] > 0){
				$subject = 'Budiah : Reminder Update Progress; ID Deployer : '.$row['id_deployer'];
				$id_dep = '. ID Deployer : '.$row['id_deployer'].'.';
			}
			 $data = array('email' => $rowy['email'], 'textmessage' => 'Submission '.$row['code_id'].' belum dilakukan update '.$row['status_next'].' oleh '.$rowy['personil_name'].'last update '.$row['updated_time'].$id_dep, 'subject' => $subject, $CON);
			 $mail = new PHPMailer\PHPMailer\PHPMailer(); // create a new object
			 //kirimEmail($data, $CON, $mail);
			 sleep(3);
			 $manager_name .= $rowy['personil_name'].", ";
		}
		
		
		$queryx = mysqli_query($CON,"SELECT group_telegram_id FROM cto_company comp
		WHERE comp.id=1");
		$chat_group_id = "";
		while($rowx = mysqli_fetch_assoc($queryx)){
			 $chat_group_id = $rowx['group_telegram_id'];
		}
		
		$data = array('chat_id' => $chat_group_id, 'textmessage' => 'Submission '.$row['code_id'].' belum dilakukan update '.$row['status_next'].' oleh '.$manager_name.' last update '.$row['updated_time'], $CON);
		echo $data['textmessage']."<br>";
		echo KirimPerintahCurl($data, $CON);
	}else{
		$queryy = mysqli_query($CON,"SELECT user.chat_id_telegram, user.email, user.name personil_name FROM `cto_dict` dictjab 
					INNER JOIN cto_user user on user.position = dictjab.name
					WHERE dictjab.info = 'STAFF_USER' AND dictjab.name = 'Optima' AND user.is_active != -1");
		$staff_name = "";
		while($rowy = mysqli_fetch_assoc($queryy)){
			$subject = 'Budiah : Reminder Update Progress';
			$id_dep = "";
			if($row['jml_odp'] > 0){
				$subject = 'Budiah : Reminder Update Progress; ID Deployer : '.$row['id_deployer'];
				$id_dep = '. ID Deployer : '.$row['id_deployer'].'.';
			}
			 $data = array('email' => $rowy['email'], 'textmessage' => 'Submission '.$row['code_id'].' belum dilakukan update '.$row['status_next'].' oleh '.$rowy['personil_name'].'last update '.$row['updated_time'].$id_dep, 'subject' => $subject, $CON);
			 $mail = new PHPMailer\PHPMailer\PHPMailer(); // create a new object
			 kirimEmail($data, $CON, $mail);
			 sleep(3);
			 $staff_name .= $rowy['personil_name'].", ";
		}
		
		
		$queryx = mysqli_query($CON,"SELECT group_telegram_id FROM cto_company comp
		WHERE comp.id=1");
		$chat_group_id = "";
		while($rowx = mysqli_fetch_assoc($queryx)){
			 $chat_group_id = $rowx['group_telegram_id'];
		}
		
		$data = array('chat_id' => $chat_group_id, 'textmessage' => 'Submission '.$row['code_id'].' belum dilakukan update '.$row['status_next'].' oleh '.$staff_name.'last update '.$row['updated_time'], $CON);
		echo $data['textmessage']."<br>";
		echo KirimPerintahCurl($data, $CON);
		
	}
	
}

function KirimPerintahCurl($data, $CON){
		
		$data['chat_id'] = $data['chat_id'];
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
	
	function kirimEmail($data, $CON, $mail){
		$queryx = mysqli_query($CON,"SELECT email, password FROM cto_company comp
		WHERE comp.id=1");
		$email = "";
		$password = "";
		while($rowx = mysqli_fetch_assoc($queryx)){
			$email = $rowx['email'];
			$password = $rowx['password'];
		}
		if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			//require 'vendor/autoload.php';

			//require_once('phpmailer/src/PHPMailer.php');
			
			
			
			$mail->isSMTP(); // enable SMTP
			$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = $email;
			$mail->Password = $password;
			$mail->SetFrom($email);
			$mail->Subject = "".$data['subject'];
			$mail->Body = "".$data['textmessage'];
			$mail->AddAddress($data['email']);

			 if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			 } else {
				echo "Message has been sent";
			 }
		}
	}
?>