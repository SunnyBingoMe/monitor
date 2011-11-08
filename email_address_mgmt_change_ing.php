<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require_once 'database_connection.php';
require_once 'sunny_function.php';
?>
<?php 
if (!isset($_POST['submitDelete'])){
	$oldEmailAddress = $_SESSION["$monitorUserListC5Name"];
	$newEmailAddress = trimAnyWhere($_POST['newEmailAddress']);
	$newEmailAddressConfirm = trimAnyWhere($_POST['newEmailAddressConfirm']);
	$itemEmpty = array( );
	$itemEmpty[] = $newEmailAddress;
	$itemEmpty[] = $newEmailAddressConfirm;

	if (itemEmpty($itemEmpty)){
		echo "Please fill in the whole tabel";
		exit;
	}
	
	if ($newEmailAddress != $newEmailAddressConfirm){// check two newEmailAddress match
		echo "Two email address do not match.";
		exit;
	}
}else {
	debugOk('$newEmailAddress:'.$newEmailAddress);
	debugOk('$oldEmailAddress:'.$oldEmailAddress);
}

$query = "UPDATE $monitorDeviceList SET $monitorDeviceListC6Name=\"$newEmailAddress\" WHERE $monitorDeviceListC6Name = \"".$oldEmailAddress."\";";
debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

$query = "UPDATE $monitorOidNameList SET $monitorOidNameListC3Name=\"$newEmailAddress\" WHERE $monitorOidNameListC3Name = \"".$oldEmailAddress."\";";
debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

if (!isset($_POST['submitDelete'])){
	$query = "UPDATE $monitorUserList SET $monitorUserListC5Name=\"$newEmailAddress\" WHERE $monitorUserListC2Name = \"".$oldEmailAddress."\";";
	mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

	$_SESSION["$monitorUserListC5Name"] = $newEmailAddress;
	echo "New emailAddress has come into effect.";
}
?>