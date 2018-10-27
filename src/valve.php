<?
include("settings.php");

$url="http://api.steampowered.com/ICSGOServers_730/GetGameServersStatus/v1/?key=$key";

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
$beta = json_decode($result, true);

$scheduler = $beta["result"]["matchmaking"]["scheduler"];
$online_servers = $beta["result"]["matchmaking"]["online_servers"];
$online_players = $beta["result"]["matchmaking"]["online_players"];
$searching_players = $beta["result"]["matchmaking"]["searching_players"];
$search_seconds_avg = $beta["result"]["matchmaking"]["search_seconds_avg"];

?>
