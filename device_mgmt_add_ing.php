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
$newIp = trim($_POST["new$monitorDeviceListC2Name"]);
$newDeviceName = inputText2VariableName($_POST["new$monitorDeviceListC3Name"]);
$newSnmpVersion = inputText2UserName($_POST["new$monitorDeviceListC3Name"]);
$newCommunity = trim($_POST["new$monitorDeviceListC3Name"]);
$newEmailAddress = $_SESSION["$monitorUserListC5Name"];

$itemEmpty = array( );
$itemEmpty[] = $newIp;
$itemEmpty[] = $newDeviceName;
$itemEmpty[] = $newSnmpVersion;
$itemEmpty[] = $newCommunity;
$itemEmpty[] = $newEmailAddress;
if (itemEmpty($itemEmpty)){
	echo "Please fill in the whole tabel";
	exit;
}

// check device exists
$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name = '$newIp'";
debugOk($query);
$recordList = mysql_query($query,$session) or die("ERR: SELECT: ".mysql_error());
if (mysql_fetch_array($recordList)){
	echo "Device exits.";
	exit; 
}

$query = "INSERT INTO $monitorDeviceList VALUES (NULL,'$newIp','$newDeviceName','$newSnmpVersion','$newCommunity','$newEmailAddress',0,0,'N','N' ); ";
debugOk($query);
mysql_query($query,$session) or die("ERR: INSERT : ".mysql_error());

header("Location: device_mgmt.php"); /// Redirect browser 
exit;// Make sure that code below does not get executed when we redirect. 

?>