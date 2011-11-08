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
?>
<?php 
if ($_POST[configType] == "interval"){
	$query = "UPDATE $monitorConfig SET $monitorConfigC1Name='$_POST[interval]' ";
	$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
}

header ( "Location: user_home.php" );
exit;
?>