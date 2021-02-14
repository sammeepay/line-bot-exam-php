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
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		
		//Get Message for user
		$msg_for_user = $event['message']['text'];
		
		//รับข้อความและชื่อแสดงจากผู้ใช้
		$type = $arrayJson['events'][0]['source']['type'];
		//$userid = $arrayJson['events'][0]['source']['userId'];
		$message = $arrayJson['events'][0]['message']['text'];
		//รับ id ว่ามาจากไหน
		if(isset($arrayJson['events'][0]['source']['userId'])){
			$userid = $arrayJson['events'][0]['source']['userId'];
		}
		if(isset($arrayJson['events'][0]['source']['groupId'])){
			$groupid = $arrayJson['events'][0]['source']['groupId'];
		}
		if(isset($arrayJson['events'][0]['source']['room'])){
			$roomid = $arrayJson['events'][0]['source']['room'];
		}
		
		$UserDisplayName = GetDisplayNameUser($userid);
		
		//strlen($value) หาความยางของข้อความ
		
		
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
		
		$groupidEMS58 = 'C52b6d2b25aceb9e67b1c1d63c1dd894c'; 
		
		$url = 'https://api.line.me/v2/bot/group/'.$groupId.'/members/ids';
		$headers = array('Authorization: Bearer ' . $access_token);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$txtarray = json_decode($result, true);
		
		$countmember = count($txtarray['memberIds']);
		for($x=0;$x<=$countmember;$x++){
			$useridInGroup = $txtarray['memberIds'][$x];
			$UserInGroupDisplayName = GetDisplayNameUser($useridInGroup);
			$msgtoline .= "$UserInGroupDisplayName = $useridInGroup\r\n";
		}
		$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
		$arrayPostData['messages'][0]['text'] = $msgtoline;
        replyMsg($arrayHeader,$arrayPostData);
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
