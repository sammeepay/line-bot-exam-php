<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'atTwNJphyGPgdj3qVBN/Kq8FSSnvRJj+HdbTRMX7QZFKc1yly2h7tz+Q1aPJ6s3owmOZH9Gx0L4k0q71D2ubwL+EZeI5QJvMJCjy+a/2NdOGl9z40xbsBsDQRcfcetbzQU/ZItcO/iJhOKkQdNdbOwdB04t89/1O/w1cDnyilFU=';

function replyMsg($arrayHeader,$arrayPostData){
        $strUrl = "https://api.line.me/v2/bot/message/reply";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arrayPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
}

function GetDisplayNameUser($userId){
	global $access_token;
	$url = 'https://api.line.me/v2/bot/profile/'.$userId;
	$headers = array('Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	$txtarray = json_decode($result, true);
	return $txtarray['displayName'];
}

	$arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$access_token}";


// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$arrayJson = json_decode($content, true);

	//Time Date
	$date_file = date("d-m-Y");
	$time_now = date("H:i:s");

	// บันทึก Log Chat User
	$strFileName = 'linelog/'.$date_file.'_logline.txt';
	$request = json_decode($content, true);

	
	$myfile = fopen($strFileName,'a');
	fwrite($myfile,$content."\r\n");
	fwrite($myfile,$date_file.' '.$time_now.' : '.$userid.'('.$UserDisplayName.') : '."'$message'\r\n");
	fclose($myfile);


// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		
		//Get Message for user
		$msg_for_user = $event['message']['text'];
		
		//รับข้อความและชื่อแสดงจากผู้ใช้
		$type = $event['source']['type'];
		//$userid = $arrayJson['events'][0]['source']['userId'];
		$message = $event['message']['text'];
		//รับ id ว่ามาจากไหน
		if(isset($event['source']['userId'])){
			$userid = $event['source']['userId'];
		}
		if(isset($event['source']['groupId'])){
			$groupid = $event['source']['groupId'];
		}
		if(isset($event['source']['room'])){
			$roomid = $event['source']['room'];
		}
		
		$UserDisplayName = GetDisplayNameUser($userid);
		
		//strlen($value) หาความยางของข้อความ
		//userid U7d08a7b0c346df496ceecf409fe02730
		// ตุ๊กตา
		
		
		//$text = 'THPD รถทะเบียน 66-0156 อยู่ไหน';

if (strpos($msg_for_user, 'THPD') !== false) {
	
	if (strpos($msg_for_user, 'รถทะเบียน') !== false) {
	
		//echo $text.'?<br>';
		$x_1 = str_replace("THPD","", $msg_for_user);
		$x_2 = str_replace("รถทะเบียน","", $x_1);
		$x_3 = str_replace("อยู่ไหน","", $x_2);
		$x_4 = str_replace(" ","", $x_3);
		//echo $x_4;
		
		
		$date=date_create();



		$cookie_path = dirname(__FILE__).'/cookie.txt';
		
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://www.thpdlogistics.com/signin.php',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('username' => 'suwannami','password' => '0851224200','lang' => 'th','login' => 'เข้าสู่ระบบ'),
		CURLOPT_HTTPHEADER => array(
			'Cookie: session_id=9f2941c4-bc47-4e9c-a08c-932824a38bc3; lang=th; project_id=1101; appname=1'
		),
		CURLOPT_COOKIEJAR => $cookie_path,
		CURLOPT_COOKIEFILE => $cookie_path,
		));
		
		$response = curl_exec($curl);
		
		//curl_close($curl);
		
		
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://www.thpdlogistics.com/data/vehicle/vehicles.php?_dc=".date_timestamp_get($date)."&box_id=".$x_4."&vehicle_name=".$x_4."&position_name=".$x_4."&page=1&start=0&limit=50&sort=position_name&dir=ASC",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_COOKIEJAR => $cookie_path,
		CURLOPT_COOKIEFILE => $cookie_path,
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		$rs=json_decode($response);
		
		//print_r($rs);
		
		//var_dump(json_decode($response, true));
		
		$msg_to_line = "ทะเบียนรถ : ".$rs->data[0]->vehicle_name."\r\n";
		$msg_to_line .=  "ตำแหน่งที่อยู่ของรถ : ".$rs->data[0]->position_name."\r\n";
		//echo "ตำแหน่งที่อยู่ของรถ2 : ".$rs->data[0]->locations[1]->location_name."\r\n";
		//$msg_to_line .= "ตำแหน่งที่อยู่ของรถ2 : ";
		//
		//for ($x = 0; $x < count($rs->data[0]->locations); $x++) {
		//  if ($x == count($rs->data[0]->locations)) {
		//    break;
		//  }
		//  $msg_to_line .= $rs->data[0]->locations[$x]->location_name.",";
		//}
		//
		//$msg_to_line .= "\r\n";
		$msg_to_line .= "ความเร็วรถ : ".$rs->data[0]->box_speed." กม./ชม.\r\n";
		//$msg_to_line .= "ตำแหน่ง latitude : ".$rs->data[0]->box_latitude."\r\n";
		//$msg_to_line .= "ตำแหน่ง longitude : ".$rs->data[0]->box_longitude."\r\n";
		$msg_to_line .= "สถานะเครื่องยนต์ :  \r\n";
		
		
		$title = $rs->data[0]->position_name;
		$address = $rs->data[0]->position_name;
		$latitude = $rs->data[0]->box_latitude;
		$longitude = $rs->data[0]->box_longitude;

		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "location";
        $arrayPostData['messages'][0]['title'] = "$title";
        $arrayPostData['messages'][0]['address'] =   "$address";
        $arrayPostData['messages'][0]['latitude'] = "$latitude";
        $arrayPostData['messages'][0]['longitude'] = "$longitude";
		$arrayPostData['messages'][1]['type'] = "text";
        $arrayPostData['messages'][1]['text'] = "$msg_to_line";
        replyMsg($arrayHeader,$arrayPostData);
	}

	if (strpos($msg_for_user, 'ขอบคุณ') !== false) {
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "ไม่เป็นไรจ้า";
        replyMsg($arrayHeader,$arrayPostData);
	}elseif(strpos($msg_for_user, 'ขอบใจ') !== false) {
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
		$arrayPostData['messages'][0]['text'] = "ไม่เป็นไรจ้า";
        replyMsg($arrayHeader,$arrayPostData);
	}
	
	if (strpos($msg_for_user, 'checkmembergroup') !== false) {
		
		$groupidEMS58 = 'C40124af6f30ee090421e22f299f265a8'; 
		
		$url = 'https://api.line.me/v2/bot/group/'.$groupId.'/members/ids';
		$headers = array('Authorization: Bearer ' . $access_token);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$txtarray = json_decode($result, true);
		
		//$countmember = count($txtarray['memberIds']);
		//for($x=0;$x<=$countmember;$x++){
		//	$useridInGroup = $txtarray['memberIds'][$x];
		//	//$UserInGroupDisplayName = GetDisplayNameUser($useridInGroup);
		//	//$msgtoline .= "$UserInGroupDisplayName = $useridInGroup\r\n";
		//	$msgtoline .= "$useridInGroup\r\n";
		//}
		//$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        //$arrayPostData['messages'][0]['type'] = "text";
		//$arrayPostData['messages'][0]['text'] = $msgtoline;
        //replyMsg($arrayHeader,$arrayPostData);
		
		$myfile = fopen($strFileName,'a');
		fwrite($myfile,$result."\r\n");
		fclose($myfile);
	}

	if (strpos($msg_for_user, 'checkuserinfo') !== false) {
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "$userid : $UserDisplayName";
        replyMsg($arrayHeader,$arrayPostData);
	}
	
	if (strpos($msg_for_user, 'แก้งาน') !== false) {
		
		$useridtokta = 'U7d08a7b0c346df496ceecf409fe02730';
		$UsertoktaDisplayName = GetDisplayNameUser($useridtokta);
		$strlen = strlen($UsertoktaDisplayName);
		
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "ติดต่อ @$UsertoktaDisplayName เลยจ้า";
        $arrayPostData['messages'][0]['mention']['mentionees'][0]['index'] = "8";
		$arrayPostData['messages'][0]['mention']['mentionees'][0]['length'] = "$strlen";
		$arrayPostData['messages'][0]['mention']['mentionees'][0]['userId'] = "$useridtokta";
		replyMsg($arrayHeader,$arrayPostData);
	}

	if (strpos($msg_for_user, 'เช็คงานวันนี้ทะเบียนรถ') !== false) {
		$x_1 = str_replace("THPD","", $msg_for_user);
		$x_2 = str_replace("เช็คงานวันนี้ทะเบียนรถ","", $x_1);
		$x_3 = str_replace(" ","", $x_2);
		
		$date=date_create();
		$datestart = date("Y-m-d");
		$cookie_path = dirname(__FILE__).'/cookie.txt';
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://www.thpdlogistics.com/signin.php',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('username' => 'suwannami','password' => '0851224200','lang' => 'th','login' => 'เข้าสู่ระบบ'),
		CURLOPT_HTTPHEADER => array(
			'Cookie: session_id=9f2941c4-bc47-4e9c-a08c-932824a38bc3; lang=th; project_id=1101; appname=1'
		),
		CURLOPT_COOKIEJAR => $cookie_path,
		CURLOPT_COOKIEFILE => $cookie_path,
		));
		$response = curl_exec($curl);
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://www.thpdlogistics.com/data/shipment/shipments.php?_dc=".date_timestamp_get($date)."&sort=create_shipment_time&timestamp=create_shipment_time&begin_time=".$datestart."%2000%3A00%3A00&end_time=".$datestart."%2023%3A59%3A00&box_id=".$x_3."&vehicle_name=".$x_3."&position_name=".$x_3."&shipment_number=".$x_3."&origin_name=".$x_3."&destination_name=".$x_3."&shipment_ref=".$x_3."&page=1&start=0&limit=50&group=%5B%7B%22property%22%3A%22shipment_number%22%2C%22direction%22%3A%22ASC%22%7D%5D&dir=ASC",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_COOKIEJAR => $cookie_path,
		CURLOPT_COOKIEFILE => $cookie_path,
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$rs=json_decode($response);
		
		$numrecord = $rs->total;
		
		for($x=0;$x<=$numrecord;$x++){
			
			$shipment_number = $rs->data[$x]->shipment_number;
			$dn = $rs->data[$x]->dn; //เที่ยวที่
			if($x>0){
				$s = $x-1;
				$shipment_number2 = $rs->data[$s]->shipment_number;
			}else{
				$shipment_number2 = $rs->data[$x]->shipment_number;
			}
			if($shipment_number == $shipment_number2){
				
				$typejob = $rs->data[$x]->customer_type;
				
				$dropid = $rs->data[$x]->dropid; //จุดที่
				
				switch($dropid){
					case 1 :
						$destination1_name = $rs->data[$x]->destination_name;
						$plan1_origin_time = $rs->data[$x]->plan_origin_time;
						$numdrop1 = $dropid;
						$status1_shipment = $rs->data[$x]->status_shipment;
						break;
					case 2 :
						$destination2_name = $rs->data[$x]->destination_name;
						$plan2_origin_time = $rs->data[$x]->plan_origin_time;
						$numdrop2 = $dropid;
						$status2_shipment = $rs->data[$x]->status_shipment;
						break;
					case 3 :
						$destination3_name = $rs->data[$x]->destination_name;
						$plan3_origin_time = $rs->data[$x]->plan_origin_time;
						$numdrop3 = $dropid;
						$status3_shipment = $rs->data[$x]->status_shipment;
						break;
					case 4 :
						$destination4_name = $rs->data[$x]->destination_name;
						$plan4_origin_time = $rs->data[$x]->plan_origin_time;
						$numdrop4 = $dropid;
						$status4_shipment = $rs->data[$x]->status_shipment;
						break;
					case 5 :
						$destination5_name = $rs->data[$x]->destination_name;
						$plan5_origin_time = $rs->data[$x]->plan_origin_time;
						$numdrop5 = $dropid;
						$status5_shipment = $rs->data[$x]->status_shipment;
						break;
				}
				
				
			}
			$driver_1_fullname = $rs->data[$x]->$driver_1_fullname;
			$driver_1_telephone = $rs->data[$x]->driver_1_telephone;
			$driver_2_fullname = $rs->data[$x]->driver_2_fullname;
			$driver_2_telephone = $rs->data[$x]->driver_2_telephone;
			$labor_1_fullname = $rs->data[$x]->labor_1_fullname;
			$labor_2_fullname = $rs->data[$x]->labor_2_fullname;
			$vehicle_name = $rs->data[$x]->vehicle_name;
			$shipment_ref = $rs->data[$x]->shipment_ref;
			$mileage_finish = $rs->data[$x]->mileage_finish;
			$mileage_start = $rs->data[$x]->mileage_start;
			
			//$msg_to_line .= "เลขใบงาน : $shipment_number \r\n";
			//$msg_to_line .= "เที่ยวที่ : $dn \r\n";
			//$mag_to_line .= "ชื่อคนขับ 1 :  $driver_1_fullname ($driver_1_telephone) \r\n";
			//$mag_to_line .= "ชื่อคนขับ 2 :  $driver_2_fullname ($driver_2_telephone)\r\n";
			//$mag_to_line .= "ชื่อลำเลียง 1 :  $labor_1_fullname \r\n";
			//$mag_to_line .= "ชื่อลำเลียง 2 :  $labor_2_fullname \r\n";
			//$mag_to_line .= "ทะเบียนรถ : $vehicle_name \r\n";
			//$mag_to_line .= "เอกสารหมายเลข 1 :  $shipment_ref \r\n";
			//$mag_to_line .= "เลขไมล์เริ่มต้น :  $mileage_start \r\n";
			//$mag_to_line .= "เลขไมล์สิ้นสุด :  $mileage_finish \r\n";
			//$mag_to_line .= "จุดที่ 1 :  $destination1_name \r\n";
			//$mag_to_line .= "จุดที่ 2 :  $destination2_name \r\n";
			//$mag_to_line .= "จุดที่ 3 :  $destination3_name \r\n";
			//$mag_to_line .= "จุดที่ 4 :  $destination4_name \r\n";
			//$mag_to_line .= "จุดที่ 5 :  $destination5_name \r\n";
			
			$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
			$arrayPostData['messages'][0]['type'] = "bubble";
			$arrayPostData['messages'][0]['direction'] = "ltr";
			$arrayPostData['messages'][0]['header']['type'] = "box";
			$arrayPostData['messages'][0]['header']['layout'] = "vertical";
			$arrayPostData['messages'][0]['header']['contents'][0]['type'] = "text";
			$arrayPostData['messages'][0]['header']['contents'][0]['text'] = "เลขใบงาน : $shipment_number";
			$arrayPostData['messages'][0]['header']['contents'][0]['weight'] = "bold";
			$arrayPostData['messages'][0]['header']['contents'][0]['align'] = "center";
			$arrayPostData['messages'][0]['header']['contents'][1]['type'] = "text";
			$arrayPostData['messages'][0]['header']['contents'][1]['text'] = "เที่ยวที่ : $dn";
			$arrayPostData['messages'][0]['header']['contents'][1]['weight'] = "bold";
			$arrayPostData['messages'][0]['header']['contents'][1]['align'] = "center";
			$arrayPostData['messages'][0]['header']['contents'][2]['type'] = "text";
			$arrayPostData['messages'][0]['header']['contents'][2]['text'] = "ทะเบียน : $vehicle_name";
			$arrayPostData['messages'][0]['header']['contents'][2]['weight'] = "bold";
			$arrayPostData['messages'][0]['header']['contents'][2]['align'] = "center";
			$arrayPostData['messages'][0]['body']['type'] = "box";
			$arrayPostData['messages'][0]['body']['layout'] = "vertical";
			$arrayPostData['messages'][0]['body']['contents'][0]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][0]['text'] = "ชื่อคนขับ 1 : $driver_1_fullname";
			$arrayPostData['messages'][0]['body']['contents'][1]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][1]['text'] = "ชื่อคนขับ 2 : $driver_2_fullname";
			$arrayPostData['messages'][0]['body']['contents'][2]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][2]['text'] = "ชื่อลำเลียง 1 : $labor_1_fullname";
			$arrayPostData['messages'][0]['body']['contents'][3]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][3]['text'] = "ชื่อลำเลียง 2 : $labor_2_fullname";
			$arrayPostData['messages'][0]['body']['contents'][4]['type'] = "separator";
			$arrayPostData['messages'][0]['body']['contents'][5]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][5]['text'] = "จุดที่ 1 : $destination1_name";
			$arrayPostData['messages'][0]['body']['contents'][6]['type'] = "text";
			$arrayPostData['messages'][0]['body']['contents'][6]['text'] = "จุดที่ 2 : $destination2_name";
			$arrayPostData['messages'][0]['footer']['type'] = "box";
			$arrayPostData['messages'][0]['footer']['layout'] = "horizontal";
			$arrayPostData['messages'][0]['footer']['contents'][0]['type'] = "spacer";
			replyMsg($arrayHeader,$arrayPostData);
			
		}
		
		$msg_to_line .= '';
		
	}


	if (strpos($msg_for_user, 'ไม่รู้') !== false) {
		
	}
/*

//  ส่วนโค๊ดอันเก่า

//********************************

		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $msg_to_line
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			
			//$messages = [
			//	'type' => 'location',
			//	'title' => ''.$rs->data[0]->position_name.'',
			//	'address' => 'nulled',
			//	'latitude' => ''.$rs->data[0]->box_latitude.'',
			//	'longitude' => ''.$rs->data[0]->box_longitude.''
			//];
			//
			//// Make a POST Request to Messaging API to reply to sender
			//$url = 'https://api.line.me/v2/bot/message/reply';
			//$data = [
			//	'replyToken' => $replyToken,
			//	'messages' => [$messages],
			//];
			//$post = json_encode($data);
			//$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			//
			//$ch = curl_init($url);
			//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			//$result = curl_exec($ch);
			//curl_close($ch);

			echo $result . "\r\n";
		}
	
	
//*************************************

*/
	
	
}else{
	$msg_to_line = '';
}
		
		
		
		
	}
}
echo "OK";
