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
$newSnmpVersion = inputText2UserName($_POST["new$monitorDeviceListC4Name"]);
$newCommunity = trim($_POST["new$monitorDeviceListC5Name"]);
$newEmailAddress = trim($_POST["new$monitorUserListC5Name"]);

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

header( 'refresh: 3; url=add_device.php' );
echo "<Center><font size='5'>Device is added.</font></Center>";
exit;// Make sure that code below does not get executed when we redirect. 

?>