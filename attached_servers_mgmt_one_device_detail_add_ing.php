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

$_POST [newServerIP] = trimAnyWhere ( $_POST [newServerIP] );
$_POST [newServerName] = inputText2VariableName ( $_POST [newServerName] );
$port = trimAnyWhere ( $_POST [newShutdownPort] );
$secretMessage = inputText2VariableName ( $_POST [newSecretMessage] );

$itemList [] = $_POST [newServerIP];
$itemList [] = $_POST [newServerName];
$itemList [] = $_POST [newShutdownPort];
$itemList [] = $_POST [newSecretMessage];
if (itemEmpty ( $itemList )) {
	echo "Please fill the whole table.";
	exit ();
}

$query = "SELECT * FROM $monitorAttachedServer WHERE $monitorAttachedServerC3Name='$_POST[newServerIP]' OR $monitorAttachedServerC4Name='$_POST[newServerName]'  ";
debugOk ( $query );
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
if (mysql_fetch_array ( $recordList )) {
	echo "Server IP / Name exists.";
	exit ();
}

$query = "INSERT INTO $monitorAttachedServer VALUES (NULL, '$_POST[deviceIpInDetails]', '$_POST[newServerIP]', '$_POST[newServerName]', '$port', '$secretMessage' ) ";
debugOk ( $query );
mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );

header ( "Location: attached_servers_mgmt_one_device_detail.php?ip=$_POST[deviceIpInDetails]" );
exit ();
?>