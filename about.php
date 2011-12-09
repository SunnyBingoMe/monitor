<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
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

</head>
<body>

<?php require_once 'body_head.php';?>
<center>
<h1>SNMP UPS MONITOR</h1> v 11.05<br/>
<br/><br/>
<h3><a href="user_manual.pdf" target="_blank" ><font size='6'>User Manual (new)</font><img src='dl.jpg' width='40'></a></h3>
<font size="+1">
<a href="user_manual_old.pdf" target="_blank" ><font size='6'>User Manual (old)</font><img src='dl.jpg' width='40'></a>
<br/><br/>

<a href="http://SunnyBoy.Me" target="_blank">Sunny</a><a href="mailto:BinSun@mail.com" ><img src="BinSun_mail_g.png" /></a><br/>
<a href="mailto:siavash.outadi@gmail.com">Siavash</a><a href="mailto:siavash.outadi@gmail.com" ><img src="Siavash_g.png" /></a><br/>
</font>

</center></body></html>