<?php
session_start ();
?><?php 
if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit ();
}
require_once 'database_connection.php';
require_once 'sunny_function.php';
?><?php 
$deviceIp = $_GET[deviceIp];
$snmpVersion = $_GET[snmpVersion];
$snmpCommunity = $_GET[snmpCommunity];
$oid = $_GET[oid];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

</head>
<body>


<?php require_once 'body_head.php';?>


<?php 
debugOk($_GET);
if (!function_exists(snmpget)){
	echo "Warning: The server do not have snmp installed.";
	brn();
	$output = shell_exec("perl php_perl_snmp.pl $deviceIp/32 $snmpCommunity $oid ");
	echo ( $output);
	exit;
}
if ($snmpVersion == '1'){
	$snmpString = snmpget($deviceIp, $snmpCommunity, $oid);
	echo $snmpString;
}elseif ($snmpVersion == '2c'){
	$snmpString = snmp2_get($deviceIp, $snmpCommunity, $oid);
	echo $snmpString;
}else {
	echo "Unknown snmp version.";
}

?>
</body>
</html>
