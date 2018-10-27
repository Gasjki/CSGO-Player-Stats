<?php
error_reporting(E_ALL);
// Including files
include ("settings.php");

//GET
$id = $_GET['user'];
if(!isset($_GET['bg'])){ $bg = "bg1"; } else { $bg = $_GET['bg']; }

// Query from Valve
	if(ctype_digit($id)) { 	
	$xml = @simplexml_load_file("http://steamcommunity.com/profiles/$id/?xml=1");	
	} else { 			
	$xml = @simplexml_load_file("http://steamcommunity.com/id/$id/?xml=1");
	}				
	
	// Let's take what we need
	$user = $xml->steamID;
	$steamid = $xml->steamID64;
	$avatar = $xml->avatarFull;
	$status = $xml->onlineState;	
	$lastonline = $xml->stateMessage;
	$member = $xml->memberSince;
	
		// Let's take what we need part 2
	$url = "http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=$key&steamid=$steamid";
	$url2 = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=$key&steamid=$steamid";
	$ch_1 = curl_init($url);
	$ch_2 = curl_init($url2);
	curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);
	$mh = curl_multi_init();
	curl_multi_add_handle($mh, $ch_1);
	curl_multi_add_handle($mh, $ch_2);
	 // execute all queries simultaneously, and continue when all are complete
  $running = null;
  do {
    curl_multi_exec($mh, $running);
	$content = curl_multi_getcontent($ch_1);
  } while ($running);
  
  $response_1 = curl_multi_getcontent($ch_1);
  $response_2 = curl_multi_getcontent($ch_2);
  $steam = json_decode($response_1);
  $steam2 = json_decode($response_2);
	
	$ok = 0;
	foreach($steam2->response->games as $time){
	if($time->appid == "730"){
		$ok = 1;
	}
}
if($ok == 0){ echo "<center><font color='red'><b>This user doesn't have CS:GO ! Go <a href='index.php'>back</a> !</b></font></center>";  exit(); }
	foreach($steam->playerstats->stats as $csgo){
	    if($csgo->name == "total_kills"){ $total_kills = $csgo->value ; }
		else if($csgo->name == "total_deaths"){ $total_deaths = $csgo->value ; }
	}
	foreach($steam2->response->games as $time){
	if($time->appid == "730"){
    $playtime_2w = $time->playtime_2weeks;
	$playtime_4ever = $time->playtime_forever;
	}
}
$totaltime = round($playtime_4ever/60);
$week2 = round($playtime_2w/60);
	
	// Server Name
	
	$servername = $_SERVER['SERVER_NAME'];
	
	// Current Status
	function status($sid){
	if(ctype_digit($sid)) { 	
	$xml = @simplexml_load_file("http://steamcommunity.com/profiles/$sid/?xml=1");	
	} else { 			
	$xml = @simplexml_load_file("http://steamcommunity.com/id/$sid/?xml=1");
	}	
	$stats = $xml->onlineState;
	if($stats == "in-game"){ 
		return "Playing ".$xml->inGameInfo->gameName;
	} else { 
		return $xml->stateMessage;
	}
}
	$a = status($id);
		
		
// Strings lenght

if(strlen($user) >= 40){ $user = substr($user,0,40); } else { $user = $user ; }

// Banners Colors
if($bg == "bg1" || $bg == "bg2" || $bg == "bg3" || $bg == "bg4"){
	$im = ImageCreateFromPNG("images/banners/$bg.png");
} else {
	echo '<center><b><font color="red">Error: I couldn\'t create image ! Go <a href="index.php">back </a> !</font></b></center>';
	exit();
}
$culoare_text_verde = imagecolorallocate($im, 0, 183, 21);
$culoare_text_rosu = imagecolorallocate($im, 255, 0, 0);
$culoare_text_titlu = "0xFFC200";
$culoare_text_info = "0xFFFFFF";
$culoare_online = "0x24FF00";
$culoare_offline = "0xFF0000";
$culoare_negru = "0x000000";
// Create banner

if(!$steamid){

imagettftext($im,9,0,123,50,$culoare_text_info,'fonts/arialbd.ttf',"This user doesn't exist !");

}else{
// Kills stats
imagettftext($im,9,0,260,16,$culoare_text_titlu,'fonts/arialbd.ttf',"STATS:");
imagettftext($im,8,0,250,35,$culoare_text_verde,'fonts/arialbd.ttf',$total_kills." kills");
imagettftext($im,8,0,250,50,$culoare_text_info,'fonts/arialbd.ttf',$total_deaths." deaths");
//Played time
imagettftext($im,8,0,250,65,$culoare_text_info,'fonts/arialbd.ttf',$totaltime." hrs played");
// Name
imagettftext($im,9,0,90,16,$culoare_text_titlu,'fonts/arialbd.ttf',"Name:");
imagettftext($im,8,0,90,29,$culoare_text_info,'fonts/arialbd.ttf',$user);
//  Member since
imagettftext($im,9,0,90,47,$culoare_text_titlu,'fonts/arialbd.ttf',"Member since:");
imagettftext($im,8,0,90,60,$culoare_text_info,'fonts/arialbd.ttf',$member);
// Status
imagettftext($im,9,0,90,75,$culoare_text_titlu,'fonts/arialbd.ttf',"Status:");
imagettftext($im,8,0,90,87,$culoare_online,'fonts/arialbd.ttf',$a);
}

header( 'Content-type: image/png' );
imagepng($im);
imagedestroy($im);
?>