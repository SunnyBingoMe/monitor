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

<?php 

$query = "UPDATE $monitorDeviceList SET $monitorDeviceListC2Name=\"".$_POST["$monitorDeviceListC2Name"]."\",
			$monitorDeviceListC3Name=\"".$_POST["$monitorDeviceListC3Name"]."\", $monitorDeviceListC4Name=\"".$_POST["$monitorDeviceListC4Name"]."\",
			$monitorDeviceListC5Name=\"".$_POST["$monitorDeviceListC5Name"]."\", $monitorDeviceListC6Name=\"".$_POST["$monitorDeviceListC6Name"]."\"
			WHERE $monitorDeviceListC1Name=\"".$_POST["$monitorDeviceListC1Name"]."\"";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

echo "<Center><font size='5' >Device information changed.</font></Center>";
?>

</table>
</form>

</center>
</body>
</html>