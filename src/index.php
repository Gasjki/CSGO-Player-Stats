<?php
include "core.php";
index();
?>
<center>
<div class="container">
<div class="jumbotron">
  <h1>CSGO-STATS</h1>
  <p>Check now your Counter-Strike: Global-Offensive Stats !</p>
</div>
</div>
<br />
<br />
	</ul>
	<br />
<div class="input-group">
  <form action="" class="navbar-form navbar-left" method="POST">
        <div class="form-group">
          <input name="username" type="username" class="form-control" placeholder="Paste a link to a Steam Profile, SteamID64 or customURL" size="70" value="<?php echo @$_SESSION['steamid'];?>" required/>
		<button type='submit' class='btn btn-warning' name='submit'><i class="glyphicon glyphicon-search" aria-hidden="true"></i>&nbsp;Search</button>
      </form>
	  <br />
	    <center>
	  <font color="white"> <strong>- OR - </strong></font><br />
		<?php echo steamlogin(); ?>
	</center>
					<?php
						if(isset($_POST['submit'])){
							echo success("<i class='fa fa-cog fa-spin'></i> Loading...");
							$username = htmlspecialchars($_POST['username']);
							if(strlen($username) < 3){
								echo danger("This input is too small !");
							} else {
								if(ctype_digit($username)){
									$steamid = $username;
								} else {
									$test = format($username);
									if($test == "Yes"){
										$cauta   = 'http://';
										$cautare = strpos($username, $cauta);
										if($cautare !== false){
											@$xml = simplexml_load_file($username."?xml=1");
										} else {
											@$xml = simplexml_load_file("http://".$username."?xml=1");
										}
										if($xml->steamID64){
											$steamid = $xml->steamID64;
										} else {
											echo danger("Input invalid !");
										}
									} else {
										$string = file_get_contents("http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=".$key."&vanityurl=".$username."");
										$getSteamID = json_decode($string,true);
										$steamid = $getSteamID['response']['steamid'];
									}
								}
							}
							echo '<meta http-equiv="refresh" content="1;url=status-'.$steamid.'">';
						}		
					?>
	  </div>
</div>
</center>
<br /><br /><br />
   <div class="row">
		<div class="container">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading"><i class="glyphicon glyphicon-signal" aria-hidden="true"></i> <strong>Valve's Servers Stats</strong></div>
						<table class='table table-striped'>
							<tr><td class="bg-info"><strong>Matchmaking Scheduler</strong></td><td><div class='label label-success'><?php echo $scheduler;?></div></td></tr>
							<tr><td class="bg-info"><strong>Online Servers</strong></td><td><div class='label label-warning'><?php echo $online_servers;?></div></td></tr>
							<tr><td class="bg-info"><strong>Online Players</strong></td><td><div class='label label-info'><?php echo $online_players;?></div></td></tr>
							<tr><td class="bg-info"><strong>Searching Players</strong></td><td><div class='label label-danger'><?php echo $searching_players;?></div></td></tr>
							<tr><td class="bg-info"><strong>Average Wait Time</strong></td><td><div class='label label-success'><?php echo $search_seconds_avg;?> seconds</div></td></tr>
						</table>
					</div>
				</div>
			<div class="col-md-4"></div>
		</div>
	</div>
<?php
footer();
?>