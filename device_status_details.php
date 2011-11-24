<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit ();
}

require 'database_connection.php';
require 'sunny_function.php';
?>
<?php

if (! isset ( $_GET ["ip"] )) {
	echo "No IP address has been selected.";
	exit ();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

</head>
<body>


<?php require_once 'body_head.php';?>


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

<br><br>
<font size='5'>Details of Device: <i><?php
	echo $deviceIp . " ( " . $deviceName . " )";
?></i> <a href="error_view_ing.php?offset=0&deviceIpInDetails=<?php echo $deviceIp; ?>" >View Errors</a></font>
		
<table border='0'>
	
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>

	<tr bgcolor='silver'>
		<th><font size='3'>Name</font></th>
		<th><font size='3'>Status</font></th>
		<th><font size='3'>Real time</font></th>
		<th><font size='3'>Hourly history</font></th>
		<th><font size='3'>Daily history</font></th>
		<th><font size='3'>Monthly history</font></th>
		<th><font size='3'>Yearly history</font></th>
	</tr>
<?php 
$i = 0;
while ( $record = mysql_fetch_array ( $recordList ) ) {
	$i ++;
	echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
	echo "<td><center>" . $record ["$monitorDeviceAndOidC4Name"]."</center></td>";
	echo "<td><center>";
	if ($record["$monitorDeviceAndOidC6Name"] == "Y"){
		echo "<a href=\"error_view_ing.php?offset=0&deviceIpInDetails=$deviceIp\"><font color='red'>ERROR</font></a>";
	}else {
		echo "<a href='snmpget.php?deviceIp=$deviceIp&snmpVersion=$snmpVersion&snmpCommunity=$snmpCommunity&oid=$record[$monitorDeviceAndOidC3Name]' >". 
		    ($thereIsData ? $lastSample [$i + 2] : "NO DATA") ."<img src='view.jpg' width='20' /></a>";
	}
	echo "</center></td>";
	if ($record ["$monitorDeviceAndOidC5Name"] == "Y") 
	{
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=hour&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20' /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=day&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20'  /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=month&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg'  width='20' /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=year&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20' /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=moreYears&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20' /></center></a></td>";
//		echo "<td><center><a href=''>View<img src='view.jpg'  width='20' /></center></a></td>";
	}	
	else 
	{
		echo "<td><center>N/A</td></center>";
		echo "<td><center>N/A</td></center>";
		echo "<td><center>N/A</td></center>";
		echo "<td><center>N/A</td></center>";
		echo "<td><center>N/A</td></center>";
	}
	
	echo "</tr>\n";
}
?>

</table>
</br></br>
<font size='4'>Total number of oids records: <?php echo "$i"?><br /></font>

</center>
</body>
</html>