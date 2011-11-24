<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?><?php 
$oldName = inputText2VariableName($_POST['oldName']);
$newName = inputText2VariableName($_POST['newName']);
$deviceIp = $_POST['deviceIp'];

$itemEmpty = array( );
$itemEmpty[] = $newName;
if (itemEmpty($itemEmpty)){
	echo "<Center><font size='5' color='red'>Please fill all fields and try again.</font></Center>";
	exit;
}


$query = "UPDATE $monitorDeviceAndOid SET $monitorDeviceAndOidC4Name='$newName' WHERE $monitorDeviceAndOidC4Name='$oldName'
			AND $monitorDeviceAndOidC2Name = '$deviceIp' ";
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

header("Location: oid_mgmt.php"); 
exit; 

?>