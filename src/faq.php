<?php 
include "core.php";
index();
?>
<center>
<div class="container">
<div class="jumbotron">
  <h1>CSGO-STATS</h1>
  <p>Frequently Asked Questions</p>
</div>
</div>
</center>
<br />
<br />
<center>
<div class="container">
	<div class="row well">
	<table class="table table-responsive">
		<tr style="text-align:center;"><td><font color="green"><strong>Q:</strong>&nbsp;<i>Why are there no stats for Mirage, Cache, Overpass or Operation maps?</font></i></td></tr>
		<tr style="text-align:center;"><td><strong>A:</strong>&nbsp;<i>Stats for Mirage and other newer maps are not there because Valve ceased updating Web API (where the site gets the stats) after releasing Militia.</i></td></tr>
		<tr style="text-align:center;"><td><font color="green"><strong>Q:</strong>&nbsp;<i>Where are the stats for CZ-75, USP-S and M4A1-S?</font></i></td></tr>
		<tr style="text-align:center;"><td><strong>A:</strong>&nbsp;<i>Stats are actually counted for weapon slots, not individual models. So stats for USP-S/P2000 are counted together. Same goes for M4A1-S/M4A4, and CZ-75/Five-SeveN or Tec-9.</i></td></tr>
		<tr style="text-align:center;"><td><font color="green"><strong>Q:</strong>&nbsp;<i>I want to report a bug</font></i></td></tr>
		<tr style="text-align:center;"><td><strong>A:</strong>&nbsp;<i><a href="http://steamcommunity.com/id/ketamina96" style="text-decoration: none;"><strong>Message me on Steam</strong></a>, or <a href="mailto:cristi_djey08@yahoo.com" style="text-decoration:none;"><strong>email me</strong></a>.</i></td></tr>
		<tr><td colspan="2"><br /><br /><small>Source: csgo-stats.com - This is an example of this page ! This can be modified by everyone who bought it</small></td></tr>
	</table>
	</div>
	</div>
</center>

<?
footer();
?>