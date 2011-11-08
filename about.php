<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 3; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Tom@Lwis (http://www.lwis.net/free-css-drop-down-menu/)" />
<meta name="keywords" content=" css, dropdowns, dropdown menu, drop-down, menu, navigation, nav, horizontal, vertical left-to-right, vertical right-to-left, horizontal linear, horizontal upwards, cross browser, internet explorer, ie, firefox, safari, opera, browser, lwis" />
<meta name="description" content="Clean, standards-friendly, modular framework for dropdown menus" />
<link href="css/dropdown/themes/default/helper.css" media="screen" rel="stylesheet" type="text/css" />

<!-- Beginning of compulsory code below -->

<link href="css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/dropdown/themes/default/default.css" media="screen" rel="stylesheet" type="text/css" />

<!--[if lt IE 7]>
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery.dropdown.js"></script>
<![endif]-->

<!-- / END -->

</head>
<body>

<h1><img src="http://www.bth.se/web2009/images/head_logo.png"  /></h1>

<!-- Beginning of compulsory code below -->

<ul id="nav" class="dropdown dropdown-horizontal">
	<li><a href="home.php">Home</a></li>
	<li><a href="device_status.php">Devices status</a></li>
	<li><a href="cpanel.php">Cpanel</a></li>
	<li><a href="about.php">About</a></li>
	<li><a href="logout.php">Logout</a></li>
</ul>

<!-- / END -->
<br/><br/></br></br>
<center>
<h1>SNMP UPS MONITOR</h1> v 11.11<br/>
<br/><br/>
<h3><a href="user_manual.pdf" target="_blank" ><font size='6'>User Manual</font><img src='dl.jpg' width='40'></a></h3>
<br/><br/>
<font size="+1">
<a href="http://SunnyBoy.Me" target="_blank">Sunny</a><a href="mailto:BinSun@mail.com" ><img src="BinSun_mail_g.png" /></a><br/>
<a href="mailto:siavash.outadi@gmail.com">Siavash</a><a href="mailto:siavash.outadi@gmail.com" ><img src="Siavash_g.png" /></a><br/>
</font>

</center></body></html>