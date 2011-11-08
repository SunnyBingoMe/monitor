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
if ($_SESSION ['username'] != 'root') {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require_once 'sunny_function.php';
?><?php

$query = "UPDATE $monitorConfig SET $monitorConfigC10Name='1' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );

//echo "stoping";
sleep(3);
header ( "Location: user_home.php" );
exit ();
?>