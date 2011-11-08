<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require_once 'database_connection.php';
//require_once 'sunny_function.php';
?>
<?php 
if (! isset ( $_GET ['deviceIpInDetails'] )) { // not del fro one device, is deling one oid name
	//if ($_SESSION ['username'] != 'root') {
	//	$_SESSION ['loginError'] == '1';
		echo "Login failed.";
		exit ();
	//}
} else {
	$deviceIpInDetails = $_GET ['deviceIpInDetails']; // do not need permission to view it.
	
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIpInDetails' ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	$record = mysql_fetch_array($recordList);
	//if ($_SESSION["$monitorUserListC2Name"] != "root"){
	//	if ($record[$monitorDeviceListC6Name] != $_SESSION[$monitorUserListC5Name]){
			//$_SESSION['loginError'] == '1';
	//		echo "Login failed.";
	//		exit;
	//	}
	//}
}
$offset = $_GET['offset'];
$newerOffset = $offset - 30;
$olderOffset = $offset + 30;
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
</br></br></br></br><center>

<?php 
$query = "SELECT COUNT(*) FROM $monitorErrorLog ";
if ( isset ( $deviceIpInDetails )) {
	$query .= " WHERE $monitorErrorLogC3Name = '$deviceIpInDetails' ";
}
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
$record = mysql_fetch_array ( $recordList );
$errorNumberTotal = $record[0];

$displayOffsetNewest = $offset + 1;
$displayOffsetOldest = $displayOffsetNewest + 29;
if ($displayOffsetOldest > $errorNumberTotal){
	$displayOffsetOldest = $errorNumberTotal;
}
echo "<span style='visibility:".(($olderOffset >= $errorNumberTotal)?"hidden":"visible")."' >";
echo "<a href=\"error_view_ing.php?".(isset($deviceIpInDetails)?"deviceIpInDetails={$deviceIpInDetails}&":"")."offset=$olderOffset\" >Older</a>";
echo "</span>";
//nbsp(4);
echo "$displayOffsetNewest ~ $displayOffsetOldest / $errorNumberTotal";
//nbsp(4);
echo "<span style='visibility:".(($newerOffset < 0)?"hidden":"visible")."' >";
echo "<a href=\"error_view_ing.php?".(isset($deviceIpInDetails)?"deviceIpInDetails={$deviceIpInDetails}&":"")."offset=$newerOffset\" >Newer</a>";
echo "</span>";
?>
<table>
<tr><th>ID</th> <th>Time Stamp</th> <th> Device IP </th> <th>Description</th></tr>

<?php 
if (!$errorNumberTotal ){
	echo "<tr><td colspan='4' align='center' >"."NO DATA"."</td></tr>"."</table></center></body></html>";
	exit;
}

$query = "SELECT * FROM $monitorErrorLog ";
if ( isset ( $deviceIpInDetails )) {
	$query .= " WHERE $monitorErrorLogC3Name = '$deviceIpInDetails' ";
}
$query .= "ORDER BY `$monitorErrorLogC1Name` DESC LIMIT $offset, 30 ";
//debugOk($query);
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
$i = 0;
while($record = mysql_fetch_array($recordList)){
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	//echo "<td>".$record["$monitorErrorLogC1Name"].returnNbsp(5)."</td>";
	//echo "<td>".$record["$monitorErrorLogC2Name"].returnNbsp(5)."</td>";
	//echo "<td>".$record["$monitorErrorLogC3Name"].returnNbsp(5)."</td>";
	echo "<td>".$record["$monitorErrorLogC1Name"]."</td>";
	echo "<td>".$record["$monitorErrorLogC2Name"]."</td>";
	echo "<td>".$record["$monitorErrorLogC3Name"]."</td>";
	echo "<td>".$record["$monitorErrorLogC4Name"]."</td>";
	echo "</tr>\n";
}
?>
</table>
<?php 
echo "<span style='visibility:".(($olderOffset >= $errorNumberTotal)?"hidden":"visible")."' >";
echo "<a href=\"error_view_ing.php?".(isset($deviceIpInDetails)?"deviceIpInDetails={$deviceIpInDetails}&":"")."offset=$olderOffset\" >Older</a>";
echo "</span>";
//nbsp(4);
echo "$displayOffsetNewest ~ $displayOffsetOldest / $errorNumberTotal";
//nbsp(4);
echo "<span style='visibility:".(($newerOffset < 0)?"hidden":"visible")."' >";
echo "<a href=\"error_view_ing.php?".(isset($deviceIpInDetails)?"deviceIpInDetails={$deviceIpInDetails}&":"")."offset=$newerOffset\" >Newer</a>";
echo "</span>";
?>
</center></body>
</html>