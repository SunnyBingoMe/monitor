<?php /*
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
}*/
require 'database_connection.php';
require_once 'sunny_function.php';
?><?php
/*
$query = "SELECT perlPid FROM $monitorConfig ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
$record = mysql_fetch_array ( $recordList );

if (! isset ( $_GET ['forceRun'] )) {
	if ($record [0] != NULL) {
		echo "It seems the monitor is runing, if you are sure it is not running, you could <a href=\"";
		echo getSelfUrl ();
		echo "?";
		echo (isset ( $_GET [runTest] )) ? "runTest=1" : "";
		echo "&forceRun=1\">force run </a> it.";
		exit ();
	}
}
*/
$query = "SELECT * FROM $monitorConfig ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());
$record = mysql_fetch_array($recordList);
if ($record[$monitorConfigC10Name] == '0'){
	$query = "UPDATE $monitorConfig SET $monitorConfigC10Name='1' ";
	$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
	sleep(3);
}

$query = "UPDATE $monitorConfig SET $monitorConfigC2Name=NULL , $monitorConfigC10Name='0' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );

$php_perl_isError = 0;
if (  $_GET [runTest] == 1 ) {
	$output = shell_exec( "perl project_test3_2011-05-18_2055.pl" );
} else {
	$output = shell_exec ("perl project_test10.pl" );
}

//$tDateTime = new DateTime();
//$string = $tDateTime->format("H:i:s");
require 'database_connection.php';
$query = "UPDATE $monitorConfig SET $monitorConfigC2Name=NULL ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );

//echo ("php will exit");
/*header ( "Location: user_home.php" );*/
exit ();
?>