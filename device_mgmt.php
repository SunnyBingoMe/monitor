<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
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

<!-- Beginning of compulsory code below -->

<ul id="nav" class="dropdown dropdown-horizontal">
	<li><a href="home.php">Home</a></li>
	<li><a href="device_status.php">Devices status</a></li>
	<li><a href="cpanel.php">Cpanel</a></li>
	<li><a href="about.php">About</a></li>
	<li><a href="logout.php">Logout</a></li>
</ul>

<!-- / END -->
<center>
<?php 
if ($_SESSION["$monitorUserListC2Name"] != "root"){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\" ORDER BY $monitorDeviceListC2Name ";
}else {
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
?>


<table border = '0' width='80%'><form action = "device_mgmt_delete_ing.php" method = "post">
<tr ><th colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?7:6 ?>'><font size='5'>Devices</font></th></tr>
<tr bgcolor='lavender'>
<th>ID</th> <th>Device IP</th> <th>Device Name</th> <th>Snmp Config</th> <?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?"<th>Admin Email</th>":"" ?> <th>Status</th> <th>Select to Delete</th> 
</tr>
<?php 
$i = 0;
while($record = mysql_fetch_array($recordList)){
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td>".$record["$monitorDeviceListC1Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC2Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC3Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC4Name"];
	echo " : ".	$record["$monitorDeviceListC5Name"]."</td>";
	if ($_SESSION["$monitorUserListC2Name"] == "root"){
		echo "<td>".$record["$monitorDeviceListC6Name"]."</td>";
	}
	echo "<td><a href=\"oid_mgmt_one_device_detail.php?ip=$record[$monitorDeviceListC2Name]\" >";
	if ($record["$monitorDeviceListC10Name"] == 'Y'){
		echo "<font color=\"red\">ERROR</font>";
	}else {
		echo "OK";
	}
	echo "</a></td>";
	echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='".$record["$monitorDeviceListC1Name"]."' /></center></td>";
	echo "</tr>\n";
}
?>
<tr><td colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?7:6 ?>'>Total number of device records: <?php echo "$i"?><br/><font color='red'>All info in monitor system about the deleted devices will be gone.</font><div align="right"><input type="reset" name="reset" value="Select None" /><input type="submit" name="submitDelete" value="Delete Selected" /></div></td></tr>
</form></table>

</center>
</body>
</html>