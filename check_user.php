<?php 
session_start();
?><?php 
require 'sunny_function.php';
require 'database_connection.php';

$username = $_POST['user'];
$password = $_POST['pass'];

$query = "SELECT * FROM $monitorUserList WHERE $monitorUserListC2Name = '$username'";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (!($record = mysql_fetch_array($recordList))){
	echo "Login failed.";
	exit; 
}
$cryptedPassword = crypt($password, $record["$monitorUserListC3Name"]);
if ($cryptedPassword != $record["$monitorUserListC3Name"]){
	debugOk ($cryptedOldPassword.",".$record["$monitorUserListC3Name"].brn());
	echo "Login failed.";
	exit; 
}

$_SESSION["$monitorUserListC1Name"] = $record["$monitorUserListC1Name"];
$_SESSION["$monitorUserListC2Name"] = $record["$monitorUserListC2Name"];
$_SESSION["$monitorUserListC3Name"] = $record["$monitorUserListC3Name"];
$_SESSION["$monitorUserListC4Name"] = $record["$monitorUserListC4Name"];
$_SESSION["$monitorUserListC5Name"] = $record["$monitorUserListC5Name"];


mysql_close($session);
header("Location: user_console.php"); /// Redirect browser 
exit;// Make sure that code below does not get executed when we redirect. 
?>