<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
if ($_SESSION ['isAdmin'] != 'Y') {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
		echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>
	
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
</head>
<body>
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
<tr>
		<th colspan='6'>Details of <i><?php
		echo $deviceIp . " ( " . $record [$monitorDeviceListC3Name] . " )";
		?></i></th>
	</tr>
	<tr>
		<th>ID</th>
		<th>Server IP</th>
		<th>Server Name</th>
		<th>Port</th>
		<th>Secret Message</th>
		<th>Select to Delete</th>
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
		<td colspan='6'>Total number of servers records: <?php
		echo "$i"?><br />
		<font color='red'>If there is server deleted,<br />
		<b>all its thresholds</b> will be deleted.</font>
		<div align="right"><input type="reset" name="reset"
			value="Select None" /><input type="submit" name="submitDelete"
			value="Delete Selected" /></div>
		</td>
	</tr>
	</form>
</table>



<hr />

<table border="0">
	<form action="attached_servers_mgmt_one_device_detail_add_ing.php"
		method="POST">
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>
<tr>
		<th colspan="2">Add New Servers</th>
	</tr>
	<tr>
		<td>Server IP<br />
		</td>
		<td><input type="text" name="newServerIP" maxlength="40" /></td>
	</tr>
	<tr bgcolor="lavender">
		<td>Server Name<br />
		(NO Special Character)</td>
		<td><input type="text" name="newServerName" maxlength="50"
			value="newHpServer" /></td>
	</tr>
	<tr>
		<td>Shutdown Port Number<br />
		(of Shudown Daemon)</td>
		<td><input type="text" name="newShutdownPort" maxlength="50"
			value="9999" /></td>
	</tr>
	<tr bgcolor="lavender">
		<td>Secret Message<br />
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