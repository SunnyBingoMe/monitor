<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
//if ($_SESSION['isAdmin'] != 'Y'){
//	$_SESSION['loginError'] == '1';
//	echo "Login failed.";
//	exit;
//}
require 'database_connection.php';
require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>

<?php require_once 'body_head.php';?>
<center>

<form action = "save_device_change.php" method = "POST">
<table>
<?php 
$ipadd = $_GET['ip']; 

if ($_SESSION["$monitorUserListC2Name"] != "root"){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\" AND $monitorDeviceListC2Name='$ipadd'";
}else {
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$ipadd'";
}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

while($record = mysql_fetch_array($recordList))
{	
	echo "<input type='hidden' name='".$monitorDeviceListC1Name."' value='".$record["$monitorDeviceListC1Name"]."'>";
	echo "<tr bgcolor='lavender'><th colspan='2'><font size='5'>Edit device</font></th></tr>";
	echo "<tr><td width='200'>IP address: </td><td><input type='text' name='".$monitorDeviceListC2Name."' value='".$record["$monitorDeviceListC2Name"]."'></td></tr>";
	echo "<tr><td>Name:</br>(NO special character)</td><td><input type='text' name='".$monitorDeviceListC3Name."' value='".$record["$monitorDeviceListC3Name"]."'></td></tr>";
	echo "<tr><td>SNMP version:</td><td><input type='text' name='".$monitorDeviceListC4Name."' value='".$record["$monitorDeviceListC4Name"]."'></td></tr>";
	echo "<tr><td>Community: </td><td><input type='text' name='".$monitorDeviceListC5Name."' value='".$record["$monitorDeviceListC5Name"]."'></td></tr>";
	echo "<tr><td>Admin's Email: </td><td><input type='text' name='".$monitorDeviceListC6Name."' value='".$record["$monitorDeviceListC6Name"]."'></td></tr>";
	echo "<tr><td></br><div align='right'><button type='submit'>Save</button></div></td></tr>";
}


?>

</table>
</form>

</center>
</body>
</html>