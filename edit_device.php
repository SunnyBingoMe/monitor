<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>

<html>
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>

<?php require_once 'body_head.php';?>

<div style='position: absolute; top: 250px; left: 250px;width:750px; background: lavender'>
	<font size = '5'>Devices</font>
</div>

<?php
if ($_SESSION["$monitorUserListC2Name"] != "root")
{
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\"ORDER BY $monitorDeviceListC2Name ";
}
else
{
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

$top = 280;
$top1 = 390;
$top2 = 410;
$left = 280;
$no = 1;
while($record = mysql_fetch_array($recordList))
{
	echo "<a href=\"edit_device_detail.php?ip=$record[$monitorDeviceListC2Name]\" >";
	echo "<div style='position: absolute; top: ".$top."px; left: ".$left."px;'>";
	echo "<img src='ups_icon.jpg' width='100' height='100' /></div>";
	echo "<div style='position: absolute; top: ".$top1."px; left: ".$left."px;'>";
	echo $record["$monitorDeviceListC2Name"]."</div>";
	echo "<div style='position: absolute; top: ".$top2."px; left: ".$left."px;'>";
	echo $record["$monitorDeviceListC3Name"]."</div></a>";
	$left += 200;
	$no ++;
	if($no > 4)
	{
		$left = 280;
		$top = $top + 200 ;
		$top1 = $top1 + 200;
		$top2 = $top2 + 200;
		$no = 1;
	}
}

?>


</body>
</html>