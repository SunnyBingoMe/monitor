<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
//require 'sunny_function.php';
?>
<?php 
$aElementValuePairList = array();
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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

<h1><img src="http://www.bth.se/web2009/images/head_logo.png"  /></h1><center>
<!-- Beginning of compulsory code below -->

<ul id="nav" class="dropdown dropdown-horizontal">
	<li><a href="home.php">Home</a></li>
	<li><a href="device_status.php">Devices status</a></li>
	<li><a href="cpanel.php">Cpanel</a></li>
	<li><a href="about.php">About</a></li>
	<li><a href="logout.php">Logout</a></li>
</ul>

<!-- / END -->

<table><form action = "password_mgmt_change_ing.php" method = "POST">
<tr bgcolor='lavender'>
<th colspan='5'><font size='5'>Change password</font></th>

</tr>
<?php
$query = "SELECT * FROM $monitorUserList";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

echo "<tr><td>Username: </td><td><select name='un'>";
if ($_SESSION['username'] == 'root'){
    while ($record = mysql_fetch_array($recordList))
    {
    	echo "<option value=".$record["$monitorUserListC2Name"].">".$record["$monitorUserListC2Name"]."</option>";
    }
}else {
    echo "<option value=".$_SESSION['username'].">".$_SESSION['username']."</option>";
}
echo "</select></td></tr>";
?>
</br></br>
<tr><td>Old password:</td><td><input type = "password" name = "oldPassword" /></td></tr>
<tr><td>New password:</td><td><input type = "password" name = "newPassword" /></td></tr>
<tr><td>Repeat new password:</td><td><input type = "password" name = "newPasswordConfirm" /></td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><button type="submit">Change</button></td></tr><br />
</form></table>

</center>
</body>
</html>