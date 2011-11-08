<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 3; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
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

<?php

//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

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


<center>

<?php
$deviceIp = $_GET ["ip"];
$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIp' ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
$record1 = mysql_fetch_array ( $recordList );


$deviceName = $record1 [$monitorDeviceListC3Name];

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

</br></br>
<font size='5'>Details of Device: <i><?php
	echo $deviceIp . " ( " . $deviceName . " )";
?></i> <a href="error_view_ing.php?offset=0&deviceIpInDetails=<?php echo $deviceIp; ?>" >View Errors</a></font>
		
<table border='0'>
	
<?php
input_hidden ( "deviceIpInDetails", array ("deviceIpInDetails" => $deviceIp ) );
?>

	<tr bgcolor='#BDEDFF'>
		<th><font size='3'>Name</font></th>
		<th><font size='3'>Status</font></th>
		<th><font size='3'>Hourly graph history</font></th>
		<th><font size='3'>Daily graph history</font></th>
		<th><font size='3'>Weekly graph history</font></th>
		<th><font size='3'>Monthly graph history</font></th>
		<th><font size='3'>Yearly graph history</font></th>
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
		echo "<font color=green>OK</font>";
	}
	echo "</center></td>";
	if ($record ["$monitorDeviceAndOidC5Name"] == "Y") 
	{
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=hour&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20' /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=day&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20'  /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=week&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg'  width='20' /></center></a></td>";
		echo "<td><center><a href=\"graph_view_ing.php?deviceIpInDetails=$deviceIp&viewType=month&oidName=$record[$monitorOidNameListC2Name]\" >
				View<img src='view.jpg' width='20' /></center></a></td>";
		echo "<td><center><a href=''>View<img src='view.jpg'  width='20' /></center></a></td>";
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