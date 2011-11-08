<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Monitor Install</title>
</head>
<body><center>
<br/><br/>
<h1>UPSNMP MONITOR</h1> v 11.06<br/>
<br/><br/>
<h3><a href="user_manual.pdf" target="_blank" >User Manual</a></h3>
<br/><br/><br/><br/><br/><br/><br/><br/><br/>
<font size="+1">
PHP Programmer: <a href="http://SunnyBoy.Me" target="_blank">Sunny</a><?php nbsp(5)?><a href="mailto:BinSun@mail.com" ><img src="BinSun_mail_g.png" /></a><br/>
PERL Programmer: <a href="mailto:siavash.outadi@gmail.com">Siavash</a><?php nbsp(5)?><a href="mailto:siavash.outadi@gmail.com" ><img src="Siavash_g.png" /></a><br/>
</font>

</center></body></html>