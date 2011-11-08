<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 3; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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

<!--[if lt IE 7]>
<script type="text/javascript" src="js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/jquery/jquery.dropdown.js"></script>
<![endif]-->

<!-- / END -->

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
</br></br></br></br>



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
	echo "<a href=\"add_oid.php?ip=$record[$monitorDeviceListC2Name]\" >";
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