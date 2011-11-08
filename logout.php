<?php 
session_start();
require 'sunny_function.php';
require 'database_connection.php';
mysql_close($session);

if (isset($_SESSION["$monitorUserListC4Name"])){
	unset($_SESSION["$monitorUserListC1Name"]);
	unset($_SESSION["$monitorUserListC2Name"]);
	unset($_SESSION["$monitorUserListC3Name"]);
	unset($_SESSION["$monitorUserListC4Name"]);
	unset($_SESSION["$monitorUserListC5Name"]);
	header("Location: login.php");
	exit;
}
	header("Location: login.php");
	exit;
?>