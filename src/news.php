<?
error_reporting(E_ALL);
include "core.php";

$url = "http://api.steampowered.com/ISteamNews/GetNewsForApp/v0001/?format=json&appid=730&count=3";
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
$alfa = json_decode($result, true);

//Titles
$title_one = $alfa["appnews"]["newsitems"]["newsitem"]["0"]["title"];
$title_two = $alfa["appnews"]["newsitems"]["newsitem"]["1"]["title"];
$title_three = $alfa["appnews"]["newsitems"]["newsitem"]["2"]["title"];

// URLs

$url_one = $alfa["appnews"]["newsitems"]["newsitem"]["0"]["url"];
$url_two = $alfa["appnews"]["newsitems"]["newsitem"]["1"]["url"];
$url_three = $alfa["appnews"]["newsitems"]["newsitem"]["2"]["url"];

//Contents

$contents_one = $alfa["appnews"]["newsitems"]["newsitem"]["0"]["contents"];
$contents_two = $alfa["appnews"]["newsitems"]["newsitem"]["1"]["contents"];
$contents_three = $alfa["appnews"]["newsitems"]["newsitem"]["2"]["contents"];

// Feedlabel

$fl_one = $alfa["appnews"]["newsitems"]["newsitem"]["0"]["feedlabel"];
$fl_two = $alfa["appnews"]["newsitems"]["newsitem"]["1"]["feedlabel"];
$fl_three = $alfa["appnews"]["newsitems"]["newsitem"]["2"]["feedlabel"];

// Date

$date_one = $alfa["appnews"]["newsitems"]["newsitem"]["0"]["date"];
$date_two = $alfa["appnews"]["newsitems"]["newsitem"]["1"]["date"];
$date_three = $alfa["appnews"]["newsitems"]["newsitem"]["2"]["date"];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News &copy; CSGO-STATS</title>

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
}
	.back-to-top {
	background: none;
	margin: 0;
	position: fixed;
	bottom: 20px;
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
        <li><a href="index.php"><i class='glyphicon glyphicon-home' aria-hidden='true'></i>&nbsp;<b>Home</b><span class="sr-only"></span></a></li>
		<li><a href="faq.php"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i>&nbsp;<font color="orange"><b>FAQ</b></font></a></li>
		<li class="active"><a href="news.php"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;<b>News</b></a></li>
     
 
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<center>
<div class="container">
<div class="jumbotron">
  <h1>CSGO-STATS</h1>
  <p><i class="glyphicon glyphicon-th-list" aria-hidden="true"></i> News via <img src="images/steamicon.png" widht="30px" height="30px"/>&nbsp;Steam Curators</p>
</div>
</div>
</center>
<br />
<div class="container">
 <div class="row">
   <div class="col-md-12">
	<div class="panel panel-info">
	<div class="panel-heading"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $title_one; ?></div>
	<table class='table'>
	<tr><td><?php echo $contents_one;?></td></tr>
	<div class="panel-footer"><i class="glyphicon glyphicon-user" aria-hidden="true"></i> <strong><a href="<?php echo $url_one;?>"><?php echo $fl_one ;?></a></strong> | <i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Date: <strong><?php echo date("d-m-Y H:i:s", $date_one) ;?></strong></div>
	</table>
	</div>
	</div>
	
	   <div class="col-md-12">
	<div class="panel panel-info">
	<div class="panel-heading"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $title_two; ?></div>
	<table class='table'>
	<tr><td><?php echo $contents_two;?></td></tr>
	<div class="panel-footer"><i class="glyphicon glyphicon-user" aria-hidden="true"></i> <strong><a href="<?php echo $url_two;?>"><?php echo $fl_two ;?></a></strong> | <i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Date: <strong><?php echo date("d-m-Y H:i:s", $date_two) ;?></strong></div>
	</table>
	</div>
	</div>
	
	   <div class="col-md-12">
	<div class="panel panel-info">
	<div class="panel-heading"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $title_three; ?></div>
	<table class='table'>
	<tr><td><?php echo $contents_three;?></td></tr>
	<div class="panel-footer"><i class="glyphicon glyphicon-user" aria-hidden="true"></i> <strong><a href="<?php echo $url_three;?>"><?php echo $fl_three ;?></a></strong> | <i class="glyphicon glyphicon-calendar" aria-hidden="true"></i> Date: <strong><?php echo date("d-m-Y H:i:s", $date_three) ;?></strong></div>
	</table>
	</div>
	</div>
	</div>
	</div>
	<br /><br /><br /><br />
<nav class="navbar navbar-inverse">
  <div class="container">
   <p class="navbar-text"><center><br /><font color="white">Version: 1.3  &copy; 2015 - GasKa</font></center></p>
  </div><!-- /.container-fluid -->
</nav>

	<!-- Scroll to top -->
    <a href="#" class="back-to-top" style="display: none;"><i class="fa fa-arrow-circle-o-up"></i></a>
	<script>
	jQuery(document).ready(function() {
    var offset = 250;
    var duration = 300;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });

    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});
</script>
	</body>
	</html>