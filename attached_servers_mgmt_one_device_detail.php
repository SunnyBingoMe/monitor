<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	//$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
//if ($_SESSION ['isAdmin'] != 'Y') {
//	$_SESSION ['loginError'] == '1';
//	echo "Login failed.";
//	exit ();
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
<br><br><br>

<center>
<?php
$deviceIp = $_GET ["ip"];

// check authority
$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIp' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
$record = mysql_fetch_array ( $recordList );
if ($_SESSION ["$monitorUserListC2Name"] != "root") {
	if ($record [$monitorDeviceListC6Name] != $_SESSION [$monitorUserListC5Name]) {
		echo "Login failed.";
		exit ();
	}
}
?>

<table border='0'>
	<form action="attached_servers_mgmt_one_device_detail_delete_ing.php"
		method="post">
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>
<tr bgcolor='lavender'>
		<th colspan='6'><font size='5'>Details of <i><?php
		echo $deviceIp . " ( " . $record [$monitorDeviceListC3Name] . " )";
		?></i></font></th>
	</tr>
	<tr bgcolor='lavender'>
		<th >ID</th>
		<th width='150'>Server IP</th>
		<th width='150'>Server Name</th>
		<th width='60'>Port</th>
		<th width='300'>Secret Message</th>
		<th >Select to Delete</th>
	</tr>
<?php
$query = "SELECT * FROM $monitorAttachedServer WHERE $monitorAttachedServerC2Name='$deviceIp' ORDER BY $monitorAttachedServerC3Name ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
$i = 0;
while ( $record = mysql_fetch_array ( $recordList ) ) {
	$i ++;
	echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
	echo "<td>" . $record ["$monitorAttachedServerC1Name"] . "</td>";
	echo "<td>" . $record ["$monitorAttachedServerC3Name"] . "</td>";
	echo "<td>" . $record ["$monitorAttachedServerC4Name"] . "</td>";
	echo "<td>" . $record ["$monitorAttachedServerC5Name"] . "</td>";
	echo "<td>" . $record ["$monitorAttachedServerC6Name"] . "</td>";
	echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='" . $record ["$monitorAttachedServerC1Name"] . "' /></center></td>";
	echo "</tr>\n";
}
?>
<tr>
		<td colspan='6'></br>Total number of servers records: <?php
		echo "$i"?><br />
		<font color='red'>If one server is deleted,<br />
		<b>all its thresholds</b> will be deleted.</font>
		<div align="right"><input type="reset" name="reset"
			value="Select None" /><input type="submit" name="submitDelete"
			value="Delete Selected" /></div>
		</td>
	</tr>
	</form>
</table>

</br></br>

<hr />
</br></br>
<table border="0">
	<form action="attached_servers_mgmt_one_device_detail_add_ing.php"
		method="POST">
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>
<tr bgcolor='lavender'>
		<th colspan="2"><font size='5'>Add a new server</font></th>
	</tr>
	<tr>
		<td width='300'>Server IP<br />
		</td>
		<td ><input type="text" name="newServerIP" maxlength="40" /></td>
	</tr>
	<tr bgcolor="lavender">
		<td>Server Name (NO Special Character)</td>
		<td><input type="text" name="newServerName" maxlength="50"
			value="newHpServer" /></td>
	</tr>
	<tr>
		<td>Shutdown Port Number
		(of Shudown Daemon)</td>
		<td><input type="text" name="newShutdownPort" maxlength="50"
			value="9999" /></td>
	</tr>
	<tr bgcolor="lavender">
		<td>Secret Message
		(for Shudown Daemon)<br />
		(NO Special Character/Space)</td>
		<td><input type="text" name="newSecretMessage" maxlength="50"
			value="newSecretMessage" /></td>
	</tr>
	<tr>
		<td><input type="reset" name="reset" value="Reset" /></td>
		<td><input type="submit" name="submit" value="  OK  " /></td>
	</tr>
	</form>
</table>

</center>
</body>
</html>