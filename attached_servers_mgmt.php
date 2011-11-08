<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
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
<meta name="author" content="Tom@Lwis (http://www.lwis.net/free-css-drop-down-menu/)" />
<meta name="keywords" content=" css, dropdowns, dropdown menu, drop-down, menu, navigation, nav, horizontal, vertical left-to-right, vertical right-to-left, horizontal linear, horizontal upwards, cross browser, internet explorer, ie, firefox, safari, opera, browser, lwis" />
<meta name="description" content="Clean, standards-friendly, modular framework for dropdown menus" />
<link href="css/dropdown/themes/default/helper.css" media="screen" rel="stylesheet" type="text/css" />

<!-- Beginning of compulsory code below -->
<link href="css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="css/dropdown/themes/default/default.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>

<h1><img src="http://www.bth.se/web2009/images/head_logo.png"  /></h1>
<center>
<?php 
if ($_SESSION["$monitorUserListC2Name"] != "root"){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\" ORDER BY $monitorDeviceListC2Name ";
}else {
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
?>
<table border = '0'>
<tr bgcolor='lavender'><th colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?5:4 ?>'><font size='5'>Attached Servers</font></th></tr>
<tr>
<th>ID</th> <th>Device IP</th> <th>Device Name</th> <?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?"<th>Admin Email</th>":"" ?> <th>No. of Servers</th>
</tr>
<?php 
$i = 0;
while($record = mysql_fetch_array($recordList))
{
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td>".$record["$monitorDeviceListC1Name"]."</td>";
	echo "<td width='200'>".$record["$monitorDeviceListC2Name"]."</td>";
	echo "<td width='100'>".$record["$monitorDeviceListC3Name"]."</td>";
	if ($_SESSION["$monitorUserListC2Name"] == "root"){
		echo "<td width='200'>".$record["$monitorDeviceListC6Name"]."</td>";
	}
	$query = "SELECT COUNT(*) FROM $monitorAttachedServer WHERE $monitorAttachedServerC2Name='".$record["$monitorDeviceListC2Name"]."' ";
	$recordList_Of_MonitorAttachedServer = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	$record_Of_MonitorAttachedServer = mysql_fetch_array($recordList_Of_MonitorAttachedServer);
	echo "<td width='150'><center>".$record_Of_MonitorAttachedServer[0]." <a href=\"attached_servers_mgmt_one_device_detail.php?ip=$record[$monitorDeviceListC2Name]\">View/Add/Delete</a>"."</center></td>";
	echo "</tr>\n";
}
?>
<tr><td colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?5:4 ?>'></br>Total number of device records: <?php echo "$i"?></td></tr>
</table>


</center>
</body>
</html>