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
<?php

if (! isset ( $_GET ["ip"] )) {
	echo "e... what are you doing .....";
	exit ();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php

//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

</head>
<body>
<center>

<?php
$deviceIp = $_GET ["ip"];
$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIp' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
$record = mysql_fetch_array ( $recordList );
if ($_SESSION ["$monitorUserListC2Name"] != "root") {
	if ($record [$monitorDeviceListC6Name] != $_SESSION [$monitorUserListC5Name]) {
		echo "Login failed.";
		exit ();
	}
}
$deviceName = $record [$monitorDeviceListC3Name];
$snmpVersion = $record [$monitorDeviceListC4Name];
$snmpCommunity = $record [$monitorDeviceListC5Name];

$query = "SELECT * FROM $monitorSample WHERE $monitorSampleC3Name='$deviceIp' ORDER BY $monitorSampleC1Name DESC LIMIT 1 ";
$recordListLastSample = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
if ($lastSample = mysql_fetch_array ( $recordListLastSample )) {
	$thereIsData = TRUE;
} else {
	$thereIsData = FALSE;
}

$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC2Name='$deviceIp' ORDER BY $monitorDeviceAndOidC1Name ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
?>
<form action="oid_mgmt_delete_name_ing.php" method="post">
<table border='0'>
	
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>
<tr>
		<th colspan='8'>Details of Device: <i><?php
		echo $deviceIp . " ( " . $deviceName . " )";
		?></i> <a href="error_view_ing.php?offset=0&deviceIpInDetails=<?php echo $deviceIp; ?>" >View Errors</a></th>
	</tr>
	<tr>
		<th>ID</th>
		<th>OID</th>
		<th>OID Name</th>
		<th>Last: <i><?php
		echo $thereIsData ? $lastSample [1] : "NO DATA";
		?></i></th>
		<th>Status</th>
		<th>Statistic</th>
		<th>History</th>
		<th>Select to Delete</th>
	</tr>
<?php 
$i = 0;
while ( $record = mysql_fetch_array ( $recordList ) ) {
	$i ++;
	echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
	echo "<td>" . $record ["$monitorDeviceAndOidC1Name"] . "</td>";
	echo "<td>" . $record ["$monitorDeviceAndOidC3Name"] . "</td>";
	echo "<td>" . $record ["$monitorDeviceAndOidC4Name"] . "</td>";
	echo "<td width='200px' ><a href=\"snmpget.php?deviceIp=$deviceIp&snmpVersion=$snmpVersion&snmpCommunity=$snmpCommunity&oid=$record[$monitorDeviceAndOidC3Name]\">" . ($thereIsData ? $lastSample [$i + 2] : "NO DATA") . "</a></td>";

	echo "<td>";
	if ($record["$monitorDeviceAndOidC6Name"] == 'Y'){
		echo "<font color=\"red\">ERROR</font>";
	}else {
		echo "OK";
	}
	echo "</td>";
	
	echo "<td align='center' >" . $record ["$monitorDeviceAndOidC5Name"];
	if ($record ["$monitorDeviceAndOidC5Name"] == "Y") {
		echo " <a href=\"oid_mgmt_config_threshold.php?oid=$record[$monitorDeviceAndOidC3Name]&oidName=$record[$monitorDeviceAndOidC4Name]&deviceIpInDetails=$deviceIp\" >View/Edit Threshold</a>";
	}
	echo "</td>";
	echo "<td align='center' >";
	if ($record ["$monitorDeviceAndOidC5Name"] == "Y") {
		//$tTimeEnd = time();
		echo " <a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=hour&oidName=$record[$monitorOidNameListC2Name]\" >QuarterHour</a> ";
		echo " <a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=day&oidName=$record[$monitorOidNameListC2Name]\" >Day</a> ";
		echo " <a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=week&oidName=$record[$monitorOidNameListC2Name]\" >Week</a> ";
		echo " <a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=month&oidName=$record[$monitorOidNameListC2Name]\" >Month</a> ";
	} else {
		echo "N/A";
	}
	echo "</td>";
	echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='" . $record ["$monitorDeviceAndOidC4Name"] . "' /></center></td>";
	echo "</tr>\n";
}
?>
<tr>
		<td colspan='8'>Total number of oids records: <?php
		echo "$i"?><br />
		<font color='red'>If there is oid deleted, <b>all history log</b> in
		monitor system <b>about this device</b> will be deleted.</font>
		<div align="right"><input type="reset" name="reset"
			value="Select None" /><input type="submit" name="submitDelete"
			value="Delete Selected OIDs" /></div>
		</td>
	</tr>

</table>
</form>

</center>
</body>
</html>