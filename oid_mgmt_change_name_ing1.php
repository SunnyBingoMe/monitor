<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$oldName = inputText2VariableName($_POST['oldName']);
$newName = inputText2VariableName($_POST['newName']);
$itemEmpty = array( );
$itemEmpty[] = $newName;
if (itemEmpty($itemEmpty)){
	echo "Please fill in the whole tabel";
	exit;
}

// check oid name exists
$query = "SELECT * FROM $monitorOidNameList WHERE $monitorOidNameListC2Name = '$newName'";
debugOk($query);
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (mysql_fetch_array($recordList)){
	echo "Oid name exits.";
	exit; 
}

$query = "UPDATE $monitorOidNameList SET $monitorOidNameListC2Name='$newName' WHERE $monitorOidNameListC2Name='$oldName' ";
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

$query = "UPDATE $monitorDeviceAndOid SET $monitorDeviceAndOidC4Name='$newName' WHERE $monitorDeviceAndOidC4Name='$oldName' ";
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

header("Location: oid_mgmt.php"); 
exit; 

?>