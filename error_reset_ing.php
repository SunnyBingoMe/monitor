<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
if ($_SESSION ['isAdmin'] != 'Y') {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php
if (! isset ( $_GET ["ip"] )) {
	echo "e... what are you doing .....";
	exit ();
}else {
	$deviceIp = $_GET ["ip"];
}
?>
<?php 
$query = "UPDATE $monitorDeviceList SET $monitorDeviceListC9Name='N', $monitorDeviceListC10Name='N' WHERE $monitorDeviceListC2Name='$deviceIp' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );

$query = "UPDATE $monitorDeviceAndOid SET $monitorDeviceAndOidC6Name='N' WHERE $monitorDeviceAndOidC2Name='$deviceIp' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );

header ( "Location: user_home.php" );
exit ();

?>