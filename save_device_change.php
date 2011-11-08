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
</br></br></br>


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