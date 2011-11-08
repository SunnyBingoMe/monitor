<?php 
session_start();
if (!isset($_SESSION['isAdmin']))
{
	//$_SESSION['loginError'] == '1';
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>


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

</br></br></br>


<center>
<table border = "0" ><form action = "device_mgmt_add_ing.php" method = "POST">
<tr bgcolor='lavender'><th colspan="2"><font size='5'>Add New Device</font></th></tr>
<tr><td>Device IP<br /></td><td><input type="text" name="<?php echo"new$monitorDeviceListC2Name"?>" maxlength="40" /></td></tr>
<tr><td>Device Name<br />(NO Special Character)</td><td><input type="text" name="<?php echo"new$monitorDeviceListC3Name"?>" maxlength="50" /></td></tr>
<tr><td>SNMP Version</td><td><select name="<?php echo"new$monitorDeviceListC4Name"?>"><option value="1">1</option><option value="2c" selected>2c</option></select></td></tr>
<tr><td>SNMP Community</td><td><input type="text" name="<?php echo"new$monitorDeviceListC5Name"?>" maxlength="50" /></td></tr>
<tr><td>Admin's Email</td><td><input type="text" name="<?php echo"new$monitorUserListC5Name"?>" value="<?php echo $_SESSION["$monitorUserListC5Name"] ?>" </td></tr>

<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value="  Add  " /></td></tr>
</form></table>	
</center>

</body>
</html>