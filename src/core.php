<?php 
error_reporting(E_ALL);
include("settings.php");
include("valve.php");
require ('steamauth.php');  


function danger($msg)
{
    echo '		<br /><div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div>';
}

function warning($msg)
{
    echo '		<br /><div class="alert alert-warning">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div>';
}

function success($msg)
{
    echo '		<br /><div class="alert alert-success">
                    <button class="close" data-dismiss="alert">X</button>
                    <p>' . $msg . '</p>
                </div> ';
}

function info($msg)
{
    echo '		<br /><div class="alert alert-info">
                <button class="close" data-dismiss="alert">X</button>
                <p>' . $msg . '</p>
                </div>';
}

function showlogout()
{
	if(@$_SESSION['steamid'] == TRUE){
		echo logout();
	} else {
		echo "";
	}
}

function format($id)
{
		$cauta   = 'steamcommunity.com';
		$cautare = strpos($id, $cauta);
		if ($cautare !== false) { 
			$status = "Yes";
		} else {
			$status = "No";
		}
	return $status;
}


function index()
{
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
</style>
  </head>
  <body>
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
        <li class="active"><a href="index.php"><i class='glyphicon glyphicon-home' aria-hidden='true'></i>&nbsp;<b>Home</b><span class="sr-only"></span></a></li>
		<li><a href="faq.php"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i>&nbsp;<font color="orange"><b>FAQ</b></font></a></li>
		<li><a href="news.php"><i class="glyphicon glyphicon-tags" aria-hidden="true"></i>&nbsp;<b>News</b></a></li>
     
 
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php
}

function footer()
{
?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	
	<nav class="navbar navbar-inverse navbar-fixed-bottom">
  <div class="container">
   <p class="navbar-text"><center><br /><font color="white">Version: 1.3  &copy; 2015 - GasKa</font></center></p>
  </div><!-- /.container-fluid -->
</nav>
	  <div style="width:100%; height:51px; display: block;"></div>
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
<?php
}
?>