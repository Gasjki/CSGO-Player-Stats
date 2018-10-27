<?php
// Includes
include "core.php";

// Get Userid
if(!$_GET['status'] || !ctype_digit($_GET['status'])){ 
	echo '<meta http-equiv="refresh" content="0;url=index.php">';
}
$steamid = $_GET['status'];

// Get username, avatar , kills, deaths and played time
$noDigit = 0; 
$hoursPlayed = 0;
$hoursPlayed2weeks = 0;
$weHaveCSGO = 0;
if(ctype_digit($steamid)){
	$noDigit = 1;
	$uuser = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$key."&steamids=".$steamid."");
	$user = json_decode($uuser, true);
	$customURL = $user['response']['players'][0]['profileurl'];
	$username = $user['response']['players'][0]['personaname'];
	$avatar = $user['response']['players'][0]['avatarfull'];
	$privacy = $user['response']['players'][0]['communityvisibilitystate'];
	if($privacy == 3){
		$getGames = simplexml_load_file($customURL."games?tab=all&xml=1");
		foreach($getGames->games->game as $game){
			if($game->appID == "730"){
				$weHaveCSGO = 1;
				@$hoursLast2Weeks = $game->hoursLast2Weeks;
				$hoursPlayed = $game->hoursOnRecord;	
			}
		}
		if($weHaveCSGO == 1){
			$ggame = file_get_contents("http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=".$key."&steamid=".$steamid."");
			$game = json_decode($ggame);
			foreach($game->playerstats->stats as $stats){
				if($stats->name == "total_kills"){
					$total_kills = $stats->value;
				} 
				if($stats->name == "total_deaths"){
					$total_deaths = $stats->value;
				}
			}
		}
	} 
}
// Make some changes
$avatar = str_replace("https","http",$avatar);


// Let's take what we need
if($privacy == "3"){
	$xml = simplexml_load_file($customURL."?xml=1");
	$user = $xml->steamID;
	$avatar = $xml->avatarIcon;
	$status = $xml->onlineState;			
	$location = $xml->location;
	$customURL = $xml->customURL;
	$lastonline = $xml->stateMessage;
	$accountsince = $xml->memberSince;
	$visibility = $xml->visibilityState;
	$vac = $xml->vacBanned;
	$privacy = $xml->privacyState;
	$trade = $xml->tradeBanState;
	$limited = $xml->isLimitedAccount;
}						
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>&copy; CSGO-STATS</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
	body {
	overflow-x: hidden;
	background: url(images/backgroundd.png) no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	 }
	.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('images/page-loader.gif') 50% 50% no-repeat rgb(249,249,249);
	overflow-x: hidden;
}
.back-to-top {
	background: none;
	margin: 0;
	position: fixed;
	bottom: 60px;
	right: 20px;
	width: 30px;
	height: 30px;
	z-index: 100;
	display: none;
	text-decoration: none;
	color: #ffffff;
	background-color: none;
}
	.back-to-top i {
			font-size: 35px;
}
</style>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(window).load(function() {
		$(".loader").fadeOut("slow");
		})
		</script>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  </head>
  
  <body>
  <div class="loader"></div>
   <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php"><i class='glyphicon glyphicon-home' aria-hidden='true'></i> <b>Home</b> <span class="sr-only"></span></a></li>
		<li><a href="faq.php"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> <font color="orange"><b>FAQ</b></font></a></li>
		<li><a href="news.php"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i> <b>News</b></a></li>
     
 
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<center>
<div class="container">
<div class="jumbotron">
  <h1>CSGO-STATS</h1>
</div>
</div>
<br />
<?php
if($privacy == 3 || $weHaveCSGO == 0){
	die("<div class='alert alert-danger'>This profile is private or this user doesn't have Counter-Strike !</div>");
	footer();
} else {
	$urlone =  "http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/?appid=730&key=".$key."&steamid=".$steamid."";
	$urltwo = "http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=".$key."&steamid=".$steamid."&format=json";
	$urlthree = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=".$key."&steamid=".$steamid."";
	$urlfour = "http://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?key=".$key."&steamid=".$steamid."";

	$ch_1 = curl_init($urlone);
	$ch_2 = curl_init($urltwo);
	$ch_3 = curl_init($urlthree);
	$ch_4 = curl_init($urlfour);
	curl_setopt($ch_1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_3, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_4, CURLOPT_RETURNTRANSFER, true);
  
	  // build the multi-curl handle, adding both $ch
	  $mh = curl_multi_init();
	  curl_multi_add_handle($mh, $ch_1);
	  curl_multi_add_handle($mh, $ch_2);
	  curl_multi_add_handle($mh, $ch_3);
	  curl_multi_add_handle($mh, $ch_4);
	  // execute all queries simultaneously, and continue when all are complete
	  $running = null;
	  do {
		curl_multi_exec($mh, $running);
		$content = curl_multi_getcontent($ch_1);
	  } while ($running);
  
	  $response_1 = curl_multi_getcontent($ch_1);
	  $response_2 = curl_multi_getcontent($ch_2);
	  $response_3 = curl_multi_getcontent($ch_3);
	  $response_4 = curl_multi_getcontent($ch_4);
	  
	  $alfa = json_decode($response_1);
	  $beta = json_decode($response_2);
	  $gama = json_decode($response_3);
	  $steam = json_decode($response_4,true);
	  $steamLevel = $steam['response']['player_level'];

foreach($alfa->playerstats->stats as $csgo){
	if($csgo->name == "total_kills"){ $total_kills = $csgo->value ; }
	else if($csgo->name == "total_deaths"){ $total_deaths = $csgo->value ; }
	else if($csgo->name == "total_planted_bombs"){ $total_planted_bombs = $csgo->value ; }
	else if($csgo->name == "total_defused_bombs"){ $total_defused_bombs = $csgo->value ; }
	else if($csgo->name == "total_damage_done"){ $total_damage_done = $csgo->value ; }
	else if($csgo->name == "total_money_earned"){ $total_money_earned = $csgo->value ; }
	else if($csgo->name == "total_rescued_hostages"){ $total_rescued_hostages = $csgo->value ; }
//Kills
	else if($csgo->name == "total_kills_knife"){ $total_kills_knife = $csgo->value ; }
	else if($csgo->name == "total_kills_hegrenade"){ $total_kills_hegrenade = $csgo->value ; }
	else if($csgo->name == "total_kills"){ $total_kills = $csgo->value ; }
	else if($csgo->name == "total_kills_glock"){ $total_kills_glock = $csgo->value ; }
	else if($csgo->name == "total_kills_deagle"){ $total_kills_deagle = $csgo->value ; }
	else if($csgo->name == "total_kills_fiveseven"){ $total_kills_fiveseven = $csgo->value ; }
	else if($csgo->name == "total_kills_xm1014"){ $total_kills_xm1014 = $csgo->value ; }
	else if($csgo->name == "total_kills_mac10"){ $total_kills_mac10 = $csgo->value ; }
	else if($csgo->name == "total_kills_ump45"){ $total_kills_ump45 = $csgo->value ; }
	else if($csgo->name == "total_kills_p90"){ $total_kills_p90 = $csgo->value ; }
	else if($csgo->name == "total_kills_awp"){ $total_kills_awp = $csgo->value ; }
	else if($csgo->name == "total_kills_ak47"){ $total_kills_ak47 = $csgo->value ; }
	else if($csgo->name == "total_kills_aug"){ $total_kills_aug = $csgo->value ; }
	else if($csgo->name == "total_kills_famas"){ $total_kills_famas = $csgo->value ; }
	else if($csgo->name == "total_kills_g3sg1"){ $total_kills_g3sg1 = $csgo->value ; }
	else if($csgo->name == "total_kills_m249"){ $total_kills_m249 = $csgo->value ; }
	else if($csgo->name == "total_kills_headshot"){ $total_kills_headshot = $csgo->value ; }
	else if($csgo->name == "total_broken_windows"){ $total_broken_windows = $csgo->value ; }
	else if($csgo->name == "total_dominations"){ $total_dominations = $csgo->value ; }
	else if($csgo->name == "total_revenges"){ $total_revenges = $csgo->value ; }
	else if($csgo->name == "total_mvps"){ $total_mvps = $csgo->value ; }
	else if($csgo->name == "total_shots_fired"){ $total_shots_fired = $csgo->value ; }
	else if($csgo->name == "total_kills_hkp2000"){ $total_kills_hkp2000 = $csgo->value ; }
	else if($csgo->name == "total_kills_p250"){ $total_kills_p250 = $csgo->value ; }
	else if($csgo->name == "total_kills_elite"){ $total_kills_elite = $csgo->value ; }
	else if($csgo->name == "total_kills_tec9"){ $total_kills_tec9 = $csgo->value ; }
	else if($csgo->name == "total_kills_taser"){ $total_kills_taser = $csgo->value ; }
	else if($csgo->name == "total_kills_mp7"){ $total_kills_mp7 = $csgo->value ; }
	else if($csgo->name == "total_kills_mp9"){ $total_kills_mp9 = $csgo->value ; }
	else if($csgo->name == "total_kills_mag7"){ $total_kills_mag7 = $csgo->value ; }
	else if($csgo->name == "total_kills_nova"){ $total_kills_nova = $csgo->value ; }
	else if($csgo->name == "total_kills_sawedoff"){ $total_kills_sawedoff = $csgo->value ; }
	else if($csgo->name == "total_kills_bizon"){ $total_kills_bizon = $csgo->value ; }
	else if($csgo->name == "total_kills_negev"){ $total_kills_negev = $csgo->value ; }
	else if($csgo->name == "total_kills_galilar"){ $total_kills_galilar = $csgo->value ; }
	else if($csgo->name == "total_kills_m4a1"){ $total_kills_m4a1 = $csgo->value ; }
	else if($csgo->name == "total_kills_ssg08"){ $total_kills_ssg08 = $csgo->value ; }
	else if($csgo->name == "total_kills_sg556"){ $total_kills_sg556 = $csgo->value ; }
	else if($csgo->name == "total_kills_scar20"){ $total_kills_scar20 = $csgo->value ; }
	else if($csgo->name == "total_kills_molotov"){ $total_kills_molotov = $csgo->value ; }
// Last match
	else if($csgo->name == "last_match_rounds"){ $last_match_rounds = $csgo->value ; }
	else if($csgo->name == "last_match_damage"){ $last_match_damage = $csgo->value ; }
	else if($csgo->name == "last_match_deaths"){ $last_match_deaths = $csgo->value ; }
	else if($csgo->name == "last_match_kills"){ $last_match_kills = $csgo->value ; }
	else if($csgo->name == "last_match_dominations"){ $last_match_dominations = $csgo->value ; }
	else if($csgo->name == "last_match_money_spent"){ $last_match_money_spent = $csgo->value ; }
	else if($csgo->name == "last_match_revenges"){ $last_match_revenges = $csgo->value ; }
	else if($csgo->name == "last_match_mvps"){ $last_match_mvps = $csgo->value ; }
	else if($csgo->name == "last_match_wins"){ $last_match_wins = $csgo->value ; }
	else if($csgo->name == "last_match_favweapon_id"){ $last_match_weapon = $csgo->value ; }

// Maps Rounds 
	else if($csgo->name == "total_wins_map_cs_assault"){ $total_wins_map_cs_assault= $csgo->value ; }
	else if($csgo->name == "total_wins_map_cs_italy"){ $total_wins_map_cs_italy= $csgo->value ; }
	else if($csgo->name == "total_wins_map_cs_office"){ $total_wins_map_cs_office= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_aztec"){ $total_wins_map_de_aztec= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_cbble"){ $total_wins_map_de_cbble= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_dust2"){ $total_wins_map_de_dust2= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_dust"){ $total_wins_map_de_dust= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_inferno"){ $total_wins_map_de_inferno= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_nuke"){ $total_wins_map_de_nuke = $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_train"){ $total_wins_map_de_train = $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_bank"){ $total_wins_map_de_bank= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_vertigo"){ $total_wins_map_de_vertigo= $csgo->value ; }
	else if($csgo->name == "total_wins_map_ar_monastery"){ $total_wins_map_ar_monastery= $csgo->value ; }
	else if($csgo->name == "total_wins_map_ar_shoots"){ $total_wins_map_ar_shoots= $csgo->value ; }
	else if($csgo->name == "total_wins_map_ar_baggage"){ $total_wins_map_ar_baggage= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_lake"){ $total_wins_map_de_lake = $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_stmarc"){ $total_wins_map_de_stmarc= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_sugarcane"){ $total_wins_map_de_sugarcane= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_shorttrain"){ $total_wins_map_de_shorttrain= $csgo->value ; }
	else if($csgo->name == "total_wins_map_de_safehouse"){ $total_wins_map_de_safehouse= $csgo->value ; }
	else if($csgo->name == "total_wins_map_cs_militia"){ $total_wins_map_cs_militia= $csgo->value ; }
// Maps 
	else if($csgo->name == "total_rounds_map_cs_assault"){ $total_rounds_map_cs_assault= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_cs_italy"){ $total_rounds_map_cs_italy= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_cs_office"){ $total_rounds_map_cs_office= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_aztec"){ $total_rounds_map_de_aztec= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_cbble"){ $total_rounds_map_de_cbble= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_dust2"){ $total_rounds_map_de_dust2= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_dust"){ $total_rounds_map_de_dust= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_inferno"){ $total_rounds_map_de_inferno= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_nuke"){ $total_rounds_map_de_nuke = $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_train"){ $total_rounds_map_de_train = $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_bank"){ $total_rounds_map_de_bank= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_vertigo"){ $total_rounds_map_de_vertigo= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_ar_monastery"){ $total_rounds_map_ar_monastery= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_ar_shoots"){ $total_rounds_map_ar_shoots= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_ar_baggage"){ $total_rounds_map_ar_baggage= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_lake"){ $total_rounds_map_de_lake = $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_stmarc"){ $total_rounds_map_de_stmarc= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_sugarcane"){ $total_rounds_map_de_sugarcane= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_shorttrain"){ $total_rounds_map_de_shorttrain= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_de_safehouse"){ $total_rounds_map_de_safehouse= $csgo->value ; }
	else if($csgo->name == "total_rounds_map_cs_militia"){ $total_rounds_map_cs_militia= $csgo->value ; }
}


if($vac == "0") { $vacstatus = "<font color='green'><strong>In good standing</strong></font>"; } else { $vacstatus = "<font color='red'><strong>Banned</strong></font>"; }
if($trade == "None") { $tradestatus = "<font color='green'><strong>In good standing</strong></font>"; } else { $tradestatus = "<font color='red'><strong>Banned</strong></font>"; }
if($limited == "0") { $limitedstatus = "<font color='green'><strong>In good standing</strong></font>"; } else { $limitedstatus = "<font color='red'><strong>Banned</strong></font>"; }
if($last_match_weapon == "1") { $last_match_favweapon = "Desert Eagle"; }
else if($last_match_weapon == "2") { $last_match_favweapon = "Dual Berettas"; }
else if($last_match_weapon == "3") { $last_match_favweapon = "Five-Seven"; }
else if($last_match_weapon == "7") { $last_match_favweapon = "AK-47"; }
else if($last_match_weapon == "4") { $last_match_favweapon = "Glock-18"; }
else if($last_match_weapon == "8") { $last_match_favweapon = "AUG"; }
else if($last_match_weapon == "9") { $last_match_favweapon = "AWP"; }
else if($last_match_weapon == "10") { $last_match_favweapon = "Famas"; }
else if($last_match_weapon == "11") { $last_match_favweapon = "G3SG1"; }
else if($last_match_weapon == "13") { $last_match_favweapon = "Galil AR"; }
else if($last_match_weapon == "14") { $last_match_favweapon = "M249"; }
else if($last_match_weapon == "16") { $last_match_favweapon = "M4"; }
else if($last_match_weapon == "17") { $last_match_favweapon = "Mac-10"; }
else if($last_match_weapon == "19") { $last_match_favweapon = "P90"; }
else if($last_match_weapon == "24") { $last_match_favweapon = "UMP-45"; }
else if($last_match_weapon == "25") { $last_match_favweapon = "XM1014"; }
else if($last_match_weapon == "26") { $last_match_favweapon = "PP-Bizon"; }
else if($last_match_weapon == "27") { $last_match_favweapon = "Mag-7"; }
else if($last_match_weapon == "28") { $last_match_favweapon = "Negev"; }
else if($last_match_weapon == "29") { $last_match_favweapon = "SawedOff"; }
else if($last_match_weapon == "30") { $last_match_favweapon = "Tec-9"; }
else if($last_match_weapon == "31") { $last_match_favweapon = "Zeus x27"; }
else if($last_match_weapon == "32") { $last_match_favweapon = "P2000"; }
else if($last_match_weapon == "33") { $last_match_favweapon = "MP7"; }
else if($last_match_weapon == "34") { $last_match_favweapon = "MP9"; }
else if($last_match_weapon == "35") { $last_match_favweapon = "Negev"; }
else if($last_match_weapon == "36") { $last_match_favweapon = "P250"; }
else if($last_match_weapon == "38") { $last_match_favweapon = "Scar-20"; }
else if($last_match_weapon == "39") { $last_match_favweapon = "SG553"; }
else if($last_match_weapon == "40") { $last_match_favweapon = "SSG08"; }
else if($last_match_weapon == "42") { $last_match_favweapon = "Knife"; }
else if($last_match_weapon == "43") { $last_match_favweapon = "Flashbang"; }
else if($last_match_weapon == "44") { $last_match_favweapon = "HE Grenade"; }
else if($last_match_weapon == "45") { $last_match_favweapon = "Smoke Grenade"; }
else if($last_match_weapon == "46") { $last_match_favweapon = "Molotov"; }
else if($last_match_weapon == "47") { $last_match_favweapon = "Decoy Grenade"; }
else if($last_match_weapon == "48") { $last_match_favweapon = "Incendiary Grenade"; }
else if($last_match_weapon == "49") { $last_match_favweapon = "C4"; }
else if($last_match_weapon == "59") { $last_match_favweapon = "Knife"; }
else if($last_match_weapon == "60") { $last_match_favweapon = "M4"; }
else if($last_match_weapon == "61") { $last_match_favweapon = "USP-S"; }
else if($last_match_weapon == "63") { $last_match_favweapon = "CZ75-Auto"; }

@$last_match_kdr = round($last_match_kills/$last_match_deaths,2);
@$kdr = round($total_kills/$total_deaths,2);

//SteamID32
$zet = (($steamid - 76561197960265728)/2);
$z = substr($zet, 0, 8);
$y = $steamid % 2;
if($privacy == 3) { $x = "0" ;} else { $x = "1"; }
?>
<div class="container">
<center>
<img src="<?php echo $avatar;?>" ></a>&nbsp;&nbsp;&nbsp;<strong><?php echo "<font color='white'><strong><h3>". $user . "</h3></strong></font>"; ?></strong> <?php if($status == "in-game") { echo "<font color='white'><strong>Playing</strong></font>&nbsp;<i><a href='" . $xml->inGameInfo->gameLink."' style='none;'>"  . $xml->inGameInfo->gameName . "</a></i>"; } else { echo "<font color='white'><i>".$lastonline."</i></font>"; } ?><br /><br />
<?php echo showlogout(); ?>
<br />
<a href="https://twitter.com/home?status=This is my account: http://<?php echo $domain ; ?>/status-<?php echo $customURL;?> . Check your details too on http://<?php echo $domain ; ?>" title="Share on Twitter" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-twitter fa-stack-1x"></i>
</span></a>
<a href="https://www.facebook.com/sharer/sharer.php?u=http://<?php echo $domain ; ?>/status-<?php echo $customURL;?>&t=Check your details too on http://<?php echo $domain ; ?>" title="Share on Facebook" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-facebook fa-stack-1x"></i>
</span></a>
<a href="https://plus.google.com/share?url=This is my account: http://<?php echo $domain ; ?>/status-<?php echo $customURL;?> . Check your details too on http://<?php echo $domain ; ?>" title="Share on Google+" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-google-plus fa-stack-1x"></i>
</span></a>
<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $domain; ?>&title=This is my account: http://<?php echo $domain ; ?>/status-<?php echo $customURL;?> . Check your details too on http://<?php echo $domain ; ?>" title="Share on Linkedin" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-linkedin fa-stack-1x"></i>
</span></a>
<a href="https://pinterest.com/pin/create/button/?url=<?php echo $domain;?>&media=<?php echo $avatar;?>&description=This is my account: http://<?php echo $domain ; ?>/status-<?php echo $customURL;?> . Check your details too on http://<?php echo $domain ; ?>" title="Share on Pinterest" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-pinterest fa-stack-1x"></i>
</span></a>
<a href="http://vk.com/share.php?url=http://<?php echo $domain ; ?>/status-<?php echo $customURL;?>" title="Share on VK" target="_blank">
<span class="fa-stack fa-lg">
  <i class="fa fa-square-o fa-stack-2x"></i>
  <i class="fa fa-vk fa-stack-1x"></i>
</span></a>


</center>
</div>
   <!-- Table -->   
   <div class="container">
   <div class="row well">
      <strong>Member since:</strong> <?php echo "$accountsince";?> | <strong>Location:</strong> <?php if(empty($location)) { echo "Not set" ; } else { echo "<label class='label label-warning'>" . $location . "</label>"; } ?><br /><br />
					<div class="page-content page-profile">
						
						<div class="page-tabs">
						
							<!-- Nav tabs -->
							<ul class="nav nav-tabs">
							  <li class="active"><a href="#general" data-toggle="tab" class="btn btn-primary"><font color="black">General Informations</font></a></li>
							  <li><a href="#statistics" data-toggle="tab" class="btn btn-primary"><font color="black">Game Statistics</font></a></li>
							  <li><a href="#weapons" data-toggle="tab" class="btn btn-primary"><font color="black">Weapons</font></a></li>
							  <li><a href="#maps" data-toggle="tab" class="btn btn-primary"><font color="black">Maps</font></a></li>
							  <li><a href="#signature" data-toggle="tab" class="btn btn-primary"><font color="black">Signature</font></a></li>
							</ul>
<br />
							<!-- Tab panes -->
							<div class="tab-content">
							
							  <!-- Profile tab -->
							  							  <div class="tab-pane fade active in" id="general">
																<div class="col-md-6">
																	<div class="panel panel-primary">
																		<div class="panel-heading"></div>
																		<table class='table'>
																		<tr><td>Name:</td><td><strong><?php echo $user ;?></td></tr>
																		<tr><td>SteamID:</td><td><strong><?php echo $steamid ;?></td></tr>
																		<tr><td>SteamID32:</td><td><strong><?php echo "STEAM_".$x.":".$y.":".$z ;?></td></tr>
																		<tr><td>Games:</td><td><strong><?php echo $gama->response->game_count ;?></td></tr>
																		
																		</table>
																		</div>
																	</div>
																	<div class="col-md-6">
																	<div class="panel panel-primary">
																		<div class="panel-heading"></div>
																		<table class='table'>
																		<tr><td>Steam Level:</td><td><strong><?php echo $steamLevel ;?></td></tr>
																		<tr><td>VAC Status:</td><td><strong><?php echo $vacstatus ;?></td></tr>
																		<tr><td>Trade Status:</td><td><strong><?php echo $tradestatus ;?></td></tr>
																		<tr><td>Limited Account?:</td><td><strong><?php echo $limitedstatus ;?></td></tr>
																		
																		</table>
																		</div>
																	</div>
																</div>
							  
							  
							  <div class="tab-pane" id="statistics">
 
   <center>
   <div class="col-md-4">
   <div class="panel panel-primary">
	<div class="panel-heading"></div>
   <table class='table'><tr><td class="bg-info"><b>Last Match:</b></td><td><div class='label label-info'><?php if(isset($last_match_wins)){ echo $last_match_wins; } else { echo 0 ; } ?> / <?php if(isset($last_match_rounds)){ echo $last_match_rounds;} else { echo 0; } ?></div> rounds WON</td></tr>
<tr><td class="bg-info"><strong>Kills:</strong></td><td><?php if(isset($last_match_kills)){ echo $last_match_kills; } else { echo 0;} ?></td></tr>
<tr><td class="bg-info"><strong>Deaths:</strong></td><td><?php if(isset($last_match_deaths)){ echo $last_match_deaths; } else { echo 0; }?></td></tr>
<tr><td class="bg-info"><strong>K/D:</strong></td><td><?php echo substr($last_match_kdr, 0 ,4);?></td></tr>
<tr><td class="bg-info"><strong>Fav. weapon:</strong></td><td><?php if(isset($last_match_favweapon)){ echo $last_match_favweapon; } else{ echo "Unknown"; } ?></td></tr>
<tr><td class="bg-info"><strong>Damage:</strong></td><td><?php if(isset($last_match_damage)) { echo number_format($last_match_damage); } else { echo 0; } ?> HP</td></tr>
<tr><td class="bg-info"><strong>Money Spent:</strong></td><td><?php if(isset($last_match_money_spent)){ echo number_format($last_match_money_spent); } else { echo 0; } ?> $</td></tr>
<tr><td class="bg-info"><strong>MVPs:</strong></td><td><?php if(isset($last_match_mvps)){ echo $last_match_mvps; } else { echo 0 ; } ?></td></tr>
<tr><td class="bg-info"><strong>Dominations:</strong></td><td><?php if(isset($last_match_dominations)){ echo $last_match_dominations; } else { echo 0; } ?></td></tr>
<tr><td class="bg-info"><strong>Revenges:</strong></td><td><?php if(isset($last_match_revenges)) { echo $last_match_revenges; } else { echo 0; } ?></td></tr>
</table>
   </div>
   </div>
    <div class="col-md-4">
	<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<table class='table'>
	<tr><td class="bg-info"><strong>Played time</strong></td><td><div class='label label-success'><?php echo $hoursPlayed ;?> hrs</div></td></tr>
	<tr><td class="bg-info"><strong>Last 2 weeks</strong></td><td><div class='label label-warning'><?php echo $hoursLast2Weeks;?> hrs</div></td></tr>
	</table>
	</div>
	<br />
	<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<table class='table'>
	<tr><td class="bg-info"><strong>Total Planted Bombs:</strong></td><td><?php if(isset($total_planted_bombs)){ echo number_format($total_planted_bombs); } else { echo 0; } ?></td></tr>
	<tr><td class="bg-info"><strong>Total Defused Bombs:</strong></td><td><?php if(isset($total_defused_bombs)){ echo number_format($total_defused_bombs); } else { echo 0; } ?></td></tr>
	<tr><td class="bg-info"><strong>Total Damage Done:</strong></td><td><div class='label label-info'><?php if(isset($total_damage_done)){ echo number_format($total_damage_done); } else { echo 0; } ?> HP</div></td></tr>
	<tr><td class="bg-info"><strong>Total Money Earned:</strong></td><td><div class='label label-success'><?php if(isset($total_money_earned)){ echo number_format($total_money_earned); } else { echo 0; } ?> $</div></td></tr>
	<tr><td class="bg-info"><strong>Total Rescued Hostages:</strong></td><td><?php if(isset($total_rescued_hostages)){ echo number_format($total_rescued_hostages); } else { echo 0; } ?></td></tr>
	<tr><td class="bg-info"><strong>Total Broken Windows:</strong></td><td><?php if(isset($total_broken_windows)){ echo number_format($total_broken_windows); } else { echo 0; } ?></td></tr>
	</table>
	</div>
	<br />
	</div>
	<div class="col-md-4">
	<div class="panel panel-primary">
	<div class="panel-heading"></div>
	<table class='table'>
	<tr><td class="bg-info"><strong>Total Kills:</strong></td><td><div class='label label-success'><?php if(isset($total_kills)){ echo number_format($total_kills); } else { echo 0; } ?></div></td></tr>
	<tr><td class="bg-info"><strong>Total Deaths:</strong></td><td><div class='label label-danger'><?php if(isset($total_deaths)){ echo number_format($total_deaths); } else { echo 0; } ?></div></td></tr>
	<tr><td class="bg-info"><strong>K/D:</strong></td><td><div class='label label-info'><?php echo substr($kdr, 0, 4);?></div></td></tr>
	</table>
	</div>
	<br />
	<div class="panel panel-primary">
	<div class="panel-heading"></div>
<table class='table'>
<tr><td class="bg-info"><strong>Total MVPs:</strong></td><td><div class='label label-warning'><?php if(isset($total_mvps)){ echo number_format($total_mvps); } else { echo 0; } ?></div></td></tr>
<tr><td class="bg-info"><strong>Total Dominations:</strong></td><td><?php if(isset($total_dominations)){ echo number_format($total_dominations); } else { echo 0; } ?></td></tr>
<tr><td class="bg-info"><strong>Total Revenges:</strong></td><td><?php if(isset($total_revenges)){ echo number_format($total_revenges);} else { echo 0; }?></td></tr>
<tr><td class="bg-info"><strong>Total Shots Fired:</strong></td><td><?php if(isset($total_shots_fired)){ echo number_format($total_shots_fired); } else { echo 0; } ?></td></tr>
<tr><td class="bg-info"><strong>Total Headshot Kills:</strong></td><td><?php if(isset($total_kills_headshot)){ echo number_format($total_kills_headshot);} else { echo 0; }?></td></tr>
</table>
</div>
</div>
	</div>
	   <div class="tab-pane fade" id="weapons">
  <div class="col-md-4">
  <div class="panel panel-primary">
	<div class="panel-heading"></div>
	<table class='table'>
  <tr><td class="bg-info"><strong>Glock:</strong></td><td><?php if(isset($total_kills_glock)) { echo number_format($total_kills_glock); } else { echo 0; }?> kills</td></tr>
  <tr><td class="bg-info"><strong>P2000/USP-S:</strong></td><td><?php if(isset($total_kills_hkp2000)){ echo number_format($total_kills_hkp2000); } else { echo 0; }?> kills</td></tr>
  <tr><td class="bg-info"><strong>Five-Seven:</strong></td><td><?php if(isset($total_kills_fiveseven)) { echo number_format($total_kills_fiveseven); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>P250:</strong></td><td><?php if(isset($total_kills_p250)) { echo number_format($total_kills_p250); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Desert Eagle:</strong></td><td><?php if(isset($total_kills_deagle)) { echo number_format($total_kills_deagle); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Dual Berettas:</strong></td><td><?php if(isset($total_kills_elite)) { echo number_format($total_kills_elite); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Tec-9:</strong></td><td><?php if(isset($total_kills_tec9)) { echo number_format($total_kills_tec9); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Zeus X27:</strong></td><td><?php if(isset($total_kills_taser)) { echo number_format($total_kills_taser); } else { echo 0; } ?> kills</td></tr>
  </table>
  </div>
  </div>
  <div class="col-md-4">
  <div class="panel panel-primary">
	<div class="panel-heading"></div>
  <table class='table'>
  <tr><td class="bg-info"><strong>MP7:</strong></td><td><?php if(isset($total_kills_mp7)) { echo number_format($total_kills_mp7); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>MP9:</strong></td><td><?php if(isset($total_kills_mp9)) { echo number_format($total_kills_mp9); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Mac-10:</strong></td><td><?php if(isset($total_kills_mac10)) { echo number_format($total_kills_mac10); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Mag-7:</strong></td><td><?php if(isset($total_kills_mag7)) { echo number_format($total_kills_mag7); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>UMP 45:</strong></td><td><?php if(isset($total_kills_ump45)) { echo number_format($total_kills_ump45); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>XM1014:</strong></td><td><?php if(isset($total_kills_xm1014)){ echo number_format($total_kills_xm1014); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Nova:</strong></td><td><?php if(isset($total_kills_nova)){ echo number_format($total_kills_nova); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>SawedOff:</strong></td><td><?php if(isset($total_kills_sawedoff)){ echo number_format($total_kills_sawedoff); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>P90:</strong></td><td><?php if(isset($total_kills_p90)) { echo number_format($total_kills_p90); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>PP-Bizon:</strong></td><td><?php if(isset($total_kills_bizon)){ echo number_format($total_kills_bizon); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Negev:</strong></td><td><?php if(isset($total_kills_negev)) { echo number_format($total_kills_negev); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>M249:</strong></td><td><?php if(isset($total_kills_m249)){ echo number_format($total_kills_m249); } else { echo 0; } ?> kills</td></tr>
  </table>
  </div>
  </div>
  <div class="col-md-4">
  <div class="panel panel-primary">
	<div class="panel-heading"></div>
  <table class='table'>
  <tr><td class="bg-info"><strong>Famas:</strong></td><td><?php if(isset($total_kills_famas)){ echo number_format($total_kills_famas); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Galilar:</strong></td><td><?php if(isset($total_kills_galilar)){ echo number_format($total_kills_galilar); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>AK-47:</strong></td><td><?php if(isset($total_kills_ak47)){ echo number_format($total_kills_ak47); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>M4A4 (M4A1-S):</strong></td><td><?php if(isset($total_kills_m4a1)){ echo number_format($total_kills_m4a1); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>SSG 08:</strong></td><td><?php if(isset($total_kills_ssg08)){ echo number_format($total_kills_ssg08); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>AUG:</strong></td><td><?php if(isset($total_kills_aug)){ echo number_format($total_kills_aug); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>SG 556:</strong></td><td><?php if(isset($total_kills_sg556)){ echo number_format($total_kills_sg556); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>AWP:</strong></td><td><?php if(isset($total_kills_awp)){ echo number_format($total_kills_awp); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>G3SG1:</strong></td><td><?php if(isset($total_kills_g3sg1)) { echo number_format($total_kills_g3sg1); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Scar-20:</strong></td><td><?php if(isset($total_kills_scar20)){ echo number_format($total_kills_scar20); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>HE Grenade:</strong></td><td><?php if(isset($total_kills_hegrenade)){ echo number_format($total_kills_hegrenade); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Molotov:</strong></td><td><?php if(isset($total_kills_molotov)){ echo number_format($total_kills_molotov); } else { echo 0; } ?> kills</td></tr>
  <tr><td class="bg-info"><strong>Knife:</strong></td><td><?php if(isset($total_kills_knife)){ echo number_format($total_kills_knife); } else { echo 0; } ?> kills</td></tr>
  </table>
  </div>
</div>
</div>
<div class="tab-pane fade" id="maps">
	<table class='table table-striped'>
		<tr>
			<td><center><img src="images/maps/de_sugarcane.jpg" height="160px" width="280px"/><br /><b>DE_SUGARCANE</b><br /><i>Wins</i>: <?php  if(isset($total_wins_map_de_sugarcane)){ echo $total_wins_map_de_sugarcane;} else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_sugarcane)){ echo $total_rounds_map_de_sugarcane; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_sugarcane / $total_rounds_map_de_sugarcane)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/ar_baggage.jpg" height="160px" width="280px"/><br /><b>AR_BAGGAGE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_ar_baggage)){ echo $total_wins_map_ar_baggage; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_ar_baggage)){ echo $total_rounds_map_ar_baggage;} else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_ar_baggage / $total_rounds_map_ar_baggage)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/cs_italy.jpg" height="160px" width="280px"/><br /><b>CS_ITALY</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_cs_italy)){ echo $total_wins_map_cs_italy;} else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_cs_italy)){ echo $total_rounds_map_cs_italy;} else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_cs_italy / $total_rounds_map_cs_italy)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/de_bank.jpg" height="160px" width="280px"/><br /><b>DE_BANK</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_bank)){ echo $total_wins_map_de_bank; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_bank)){ echo $total_rounds_map_de_bank; } else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_bank / $total_rounds_map_de_bank)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/cs_assault.jpg" height="160px" width="280px"/><br /><b>CS_ASSAULT</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_cs_assault)){ echo $total_wins_map_cs_assault; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_cs_assault)){ echo $total_rounds_map_cs_assault; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_cs_assault / $total_rounds_map_cs_assault)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_lake.jpg" height="160px" width="280px"/><br /><b>DE_LAKE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_lake)){ echo $total_wins_map_de_lake; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_lake)){ echo $total_rounds_map_de_lake; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_lake / $total_rounds_map_de_lake)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/cs_office.jpg" height="160px" width="280px"/><br /><b>CS_OFFICE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_cs_office)){ echo $total_wins_map_cs_office;} else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_cs_office)){ echo $total_rounds_map_cs_office; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_cs_office / $total_rounds_map_cs_office)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/ar_monastery.jpg" height="160px" width="280px"/><br /><b>AR_MONASTERY</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_ar_monastery)){ echo $total_wins_map_ar_monastery; } else { echo 0 ;} ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_ar_monastery)){ echo $total_rounds_map_ar_monastery;} else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_ar_monastery / $total_rounds_map_ar_monastery)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_dust2.jpg" height="160px" width="280px"/><br /><b>DE_DUST2</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_dust2)){ echo $total_wins_map_de_dust2; } else { echo 0; }?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_dust2)){ echo $total_rounds_map_de_dust2;} else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_dust2 / $total_rounds_map_de_dust2)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/de_inferno.jpg" height="160px" width="280px"/><br /><b>DE_INFERNO</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_inferno)){ echo $total_wins_map_de_inferno; } else { echo 0 ; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_inferno)){ echo $total_rounds_map_de_inferno;} else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_inferno / $total_rounds_map_de_inferno)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_aztec.jpg" height="160px" width="280px"/><br /><b>DE_AZTEC</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_aztec)){ echo $total_wins_map_de_aztec; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_aztec)){ echo $total_rounds_map_de_aztec; } else { echo 0 ;} ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_aztec / $total_rounds_map_de_aztec)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_vertigo.jpg" height="160px" width="280px"/><br /><b>DE_VERTIGO</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_vertigo)){ echo $total_wins_map_de_vertigo;} else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_vertigo)){ echo $total_rounds_map_de_vertigo; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_vertigo / $total_rounds_map_de_vertigo)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/de_safehouse.jpg" height="160px" width="280px"/><br /><b>DE_SAFEHOUSE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_safehouse)){ echo $total_wins_map_de_safehouse; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_safehouse)){ echo $total_rounds_map_de_safehouse; } else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_safehouse / $total_rounds_map_de_safehouse)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_dust.jpg" height="160px" width="280px"/><br /><b>DE_DUST</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_dust)){ echo $total_wins_map_de_dust; } else { echo 0 ; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_dust)){ echo $total_rounds_map_de_dust; } else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_dust / $total_rounds_map_de_dust)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_stmarc.jpg" height="160px" width="280px"/><br /><b>DE_ST.MARC</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_stmarc)){ echo $total_wins_map_de_stmarc; } else { echo 0 ; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_stmarc)){ echo $total_rounds_map_de_stmarc; } else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_stmarc / $total_rounds_map_de_stmarc)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/de_train.jpg" height="160px" width="280px"/><br /><b>DE_TRAIN</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_train)){ echo $total_wins_map_de_train; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_train)){ echo $total_rounds_map_de_train; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_train / $total_rounds_map_de_train)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/de_nuke.jpg" height="160px" width="280px"/><br /><b>DE_NUKE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_nuke)){ echo $total_wins_map_de_nuke; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_nuke)){ echo $total_rounds_map_de_nuke; } else { echo 0; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_nuke / $total_rounds_map_de_nuke)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/ar_shoots.jpg" height="160px" width="280px"/><br /><b>AR_SHOOTS</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_ar_shoots)){ echo $total_wins_map_ar_shoots; } else { echo 0 ; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_ar_shoots)){ echo $total_rounds_map_ar_shoots;  } else { echo 0 ;} ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_ar_shoots / $total_rounds_map_ar_shoots)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
		<tr>
			<td><center><img src="images/maps/de_cbble.jpg" height="160px" width="280px"/><br /><b>DE_COBBLESTONE</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_de_cbble)){ echo $total_wins_map_de_cbble; } else { echo 0; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_de_cbble)){ echo $total_rounds_map_de_cbble; } else { echo 0 ; }  ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_de_cbble / $total_rounds_map_de_cbble)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
			<td><center><img src="images/maps/cs_militia.jpg" height="160px" width="280px"/><br /><b>CS_MILITIA</b><br /><i>Wins</i>: <?php if(isset($total_wins_map_cs_militia)){ echo $total_wins_map_cs_militia; } else { echo 0 ; } ?><br /><i>Rounds</i>: <?php if(isset($total_rounds_map_cs_militia)){ echo $total_rounds_map_cs_militia; } else { echo 0 ; } ?><br /><i>Win percentage</i>: <?php $x = @($total_wins_map_cs_militia / $total_rounds_map_cs_militia)*100; echo "<label class='label label-success'>" . round($x,1) . "&nbsp;%</label>"; ?></center></td>
		</tr>
	</table>
</div>
<div class="tab-pane" id="signature">
	<div class="col-md-12">
		<center>
		<br />
								<img src="http://<?php echo $domain;?>/banner-<?php echo $steamid;?>-bg1"/><br/><br />
								<label class="label label-success"><strong>&raquo;HTML:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center"><a href="http://<?php echo $domain;?>/status-<?php echo $steamid;?>"><img src="http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg1"/></a></textarea><br />
								<label class="label label-success"><strong>&raquo;Forum (BBCode):</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">[url=http://<?php echo $domain;?>/status-<?php echo $steamid;?>][img]http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg1[/img][/url]</textarea><br />
								<label class="label label-success"><strong>&raquo;Direct Link:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg1</textarea><br />
				</center>
				<br /><br />
				<center>
								<img src="http://<?php echo $domain;?>/banner-<?php echo $steamid;?>-bg2"/><br/><br />
								<label class="label label-success"><strong>&raquo;HTML:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center"><a href="http://<?php echo $domain;?>/status-<?php echo $steamid;?>"><img src="http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg2"/></a></textarea><br />
								<label class="label label-success"><strong>&raquo;Forum (BBCode):</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">[url=http://<?php echo $domain;?>/status-<?php echo $steamid;?>][img]http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg2[/img][/url]</textarea><br />
								<label class="label label-success"><strong>&raquo;Direct Link:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg2</textarea><br />
				</center>
				<br /><br />
				<center>
								<img src="http://<?php echo $domain;?>/banner-<?php echo $steamid;?>-bg3"/><br/><br />
								<label class="label label-success"><strong>&raquo;HTML:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center"><a href="http://<?php echo $domain;?>/status-<?php echo $steamid;?>"><img src="http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg3"/></a></textarea><br />
								<label class="label label-success"><strong>&raquo;Forum (BBCode):</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">[url=http://<?php echo $domain;?>/status-<?php echo $steamid;?>][img]http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg3[/img][/url]</textarea><br />
								<label class="label label-success"><strong>&raquo;Direct Link:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg3</textarea><br />
				</center>
				<br /><br />
				<center>
								<img src="http://<?php echo $domain;?>/banner-<?php echo $steamid;?>-bg4"/><br/><br />
								<label class="label label-success"><strong>&raquo;HTML:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center"><a href="http://<?php echo $domain;?>/status-<?php echo $steamid;?>"><img src="http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg4"/></a></textarea><br />
								<label class="label label-success"><strong>&raquo;Forum (BBCode):</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">[url=http://<?php echo $domain;?>/status-<?php echo $steamid;?>][img]http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg4[/img][/url]</textarea><br />
								<label class="label label-success"><strong>&raquo;Direct Link:</strong></label>&nbsp;<br /><textarea cols="160" rows="1" readonly="readonly" align="center">http://<?php echo $domain;?>/banner-<?php  echo $steamid; ?>-bg4</textarea><br />
				</center>
	</div>
</div>
</div>
</div>
</div>
</div>
</div>
</center>
<?php 
}
footer();
?>