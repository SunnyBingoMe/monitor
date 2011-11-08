<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
//	$_SESSION ['loginError'] == '1';
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
<?php
//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>
	<script language="JavaScript">
    <?php
    create_function_setAllCheckBox ();
    ?>
    </script>
    
    
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
<br><br><br><br>
<center>
<hr>
<?php
if ($_SESSION ["$monitorUserListC2Name"] != "root") {
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"" . $_SESSION ["$monitorDeviceListC6Name"] . "\" ORDER BY $monitorDeviceListC2Name ";
} else {
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
?>
<form action="oid_mgmt_add.php" name="addOidToDevice" method="post">
<table border='0'>

	<tr>
		<th
			colspan='<?php
			echo ($_SESSION ["$monitorUserListC2Name"] == "root") ? 7 : 6?>'>Add
		or Delete Oids for Devices</th>
	</tr>
	<tr>
		<th>ID</th>
		<th>Device IP</th>
		<th>Device Name</th> <?php
		echo ($_SESSION ["$monitorUserListC2Name"] == "root") ? "<th>Admin Email</th>" : ""?> <th>No.
		of Statistic Oid / Total</th>
		<th>Select to Add Oids<br /><?php
		input_button_setAllCheckboxSelected ( 'addOidToDevice', 'checkbox[]' );
		input_button_setAllCheckboxUnselected ( 'addOidToDevice', 'checkbox[]' );
		?>
</th>
	</tr>
<?php
$i = 0;
while ( $record = mysql_fetch_array ( $recordList ) ) {
	$i ++;
	echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
	echo "<td>" . $record ["$monitorDeviceListC1Name"] . "</td>";
	echo "<td>" . $record ["$monitorDeviceListC2Name"] . "</td>";
	echo "<td>" . $record ["$monitorDeviceListC3Name"] . "</td>";
	if ($_SESSION ["$monitorUserListC2Name"] == "root") {
		echo "<td>" . $record ["$monitorDeviceListC6Name"] . "</td>";
	}
	echo "<td><center>" . $record ["$monitorDeviceListC8Name"];
	echo " / " . $record ["$monitorDeviceListC7Name"] . " <a href=\"oid_mgmt_one_device_detail.php?ip=" . $record ["$monitorDeviceListC2Name"] . "\" >View/Edit Details/Thresholds</a>" . "</center></td>";
	if (($record ["$monitorDeviceListC6Name"] == $_SESSION ["$monitorDeviceListC6Name"]) || ($_SESSION ["$monitorUserListC2Name"] == 'root')) {
		echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='" . $record ["$monitorDeviceListC1Name"] . "' /></center></td>";
	} else {
		echo "<td>Need Permission</td>";
	}
	echo "</tr>\n";
}
?>
<tr>
		<td
			colspan='<?php
			echo ($_SESSION ["$monitorUserListC2Name"] == "root") ? 7 : 6?>'>Total number of device records: <?php
			echo $i?><br />
		<font color='red'>Please type in new numeric oids here, seperate them by newline("Enter").(Max 50 characters for each oid.)<br />
		Make sure the names you wanted exist in the <i><b>Oid Namestable</b></i> table below, add new names first if they does not.</font><br/>
		<textarea maxlength='1000' rows='5' cols='95%' name="newOidTextarea"></textarea></td>
	</tr>
	<tr>
		<td
			colspan='<?php
			echo ($_SESSION ["$monitorUserListC2Name"] == "root") ? 7 : 6?>'>
		<div align="right"><input type="submit" name="submitAdd"
			value="Add to Selected" /></div>
		</td>
	</tr>

</table>
</form>
<hr />


<?php
//if ($_SESSION["$monitorUserListC2Name"] != "root"){
//	$query = "SELECT * FROM $monitorOidNameList WHERE $monitorOidNameListC3Name=\"".$_SESSION["$monitorOidNameListC3Name"]."\" ORDER BY $monitorOidNameListC2Name ";
//}else {
$query = "SELECT * FROM $monitorOidNameList ORDER BY $monitorOidNameListC2Name ";
//}
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
?>
<form action="oid_mgmt_delete_name_ing.php" method="post">
<table border='0'>

	<tr>
		<th colspan='5'>Oid Names</th>
	</tr>
	<tr>
		<th>ID</th>
		<th>Oid Name</th>
		<!-- th>Admin Email</th> -->
		<th>Statistic</th>
		<th>History</th>
		<th>Select to Delete</th>
	</tr>
<?php
$i = 0;
while ( $record = mysql_fetch_array ( $recordList ) ) {
	$i ++;
	$oidNameValueAndNameLabelList[$record ["$monitorOidNameListC2Name"]] = $record ["$monitorOidNameListC2Name"];
	
	echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
	echo "<td>" . $record ["$monitorOidNameListC1Name"] . "</td>";
	echo "<td>" . $record ["$monitorOidNameListC2Name"] . "</td>";
	//echo "<td>" . $record ["$monitorOidNameListC3Name"] . "</td>";
	echo "<td><center>" . $record ["$monitorOidNameListC4Name"];
	if ($record ["$monitorOidNameListC4Name"] == "Y") {
		if ($_SESSION ["$monitorUserListC2Name"] == 'root') {
			echo " <a href=\"oid_mgmt_config_threshold.php?oidName=$record[$monitorOidNameListC2Name]\" >Config Threshold</a>";
		}
	}
	echo "</center></td>";
	echo "<td><center>";
	if ($record ["$monitorOidNameListC4Name"] == "Y") {
		if ($_SESSION ["$monitorUserListC2Name"] == 'root') {
			//$tTimeEnd = time();
			echo " <a href=\"graph_view_ing.php?viewType=hour&oidName=$record[$monitorOidNameListC2Name]\" >QuarterHour</a> ";
			echo " <a href=\"graph_view_ing.php?viewType=day&oidName=$record[$monitorOidNameListC2Name]\" >Day</a> ";
			echo " <a href=\"graph_view_ing.php?viewType=week&oidName=$record[$monitorOidNameListC2Name]\" >Week</a> ";
			echo " <a href=\"graph_view_ing.php?viewType=month&oidName=$record[$monitorOidNameListC2Name]\" >Month</a> ";
		} else {
			echo "Need Root Authority.";
		}
	} else {
		echo "N/A";
	}
	echo "</center></td>";
	if ($_SESSION ["$monitorUserListC2Name"] == 'root') {
		echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='" . $record ["$monitorOidNameListC2Name"] . "' /></center></td>";
	} else {
		echo "<td>Need Root Authority.</td>";
	}
	echo "</tr>\n";
	$oidNameAndIsStatisticPairList [$record ["$monitorOidNameListC2Name"]] = $record ["$monitorOidNameListC4Name"];
}
$_SESSION ["oidNameAndIsStatisticPairList"] = $oidNameAndIsStatisticPairList;
?>
<tr>
		<td colspan='5'>Total number of oid name records: <?php
		echo "$i"?><br />
		<font color='red'><b>All oids</b> with this name will be deleted.
		(Affect all users)<br />
		All history log of <b>all devices</b> which contain this oid name will
		be deleted.</font>
		<?php
		if (($_SESSION ["$monitorUserListC2Name"] == 'root')) {
			echo <<<SESSION_monitorUserListC2Name_root
		<div align="right"><input type="reset" name="reset"
			value="Select None" /><input type="submit" name="submitDelete"
			value="Delete Selected" /></div>
SESSION_monitorUserListC2Name_root;
		}
		?>
		</td>
	</tr>

</table>
</form>


<?php
if (($_SESSION ["$monitorUserListC2Name"] == 'root')) {
	echo <<<SESSION_monitorUserListC2Name_root
<hr />
<table>
<tr>
<td>
SESSION_monitorUserListC2Name_root;

echo <<<SESSION_monitorUserListC2Name_root
<form action="oid_mgmt_change_name_ing.php" method="POST">
<table border="0">

	<tr>
		<th colspan="2">Change Oid Name<br/><br/><br/></th>
	</tr>
	<tr>
		<td>Old Name</td>
		<td>
SESSION_monitorUserListC2Name_root;
	
	echo "<select name='oldName'>";
	foreach($oidNameValueAndNameLabelList as $v)
	{
		echo "<option value=".$v.">".$v."</option>";
	}
	echo "</select>";	
	//input_select("oldName", NULL, $oidNameValueAndNameLabelList);
	//nbsp(15);
	//brn(2);
	echo <<<SESSION_monitorUserListC2Name_root
</td>
	</tr>
	<tr>
		<td>New Name<br />
		(NO Special Character)</td>
		<td><input type="text" name="newName" maxlength="45" /></td>
	</tr>
	<tr>
	
		<td><input type="reset" name="reset" value="Reset" /></td>
		<td><input type="submit" name="submit" value="  OK  " /></td>
	
	</tr>
	
	</table>
	</form>
SESSION_monitorUserListC2Name_root;

echo <<<SESSION_monitorUserListC2Name_root
</td>
<td>
SESSION_monitorUserListC2Name_root;

echo <<<SESSION_monitorUserListC2Name_root
<form action="oid_mgmt_add_name_ing.php" method="POST">
<table border="0">

	<tr>
		<th colspan="2">Add New Oid Name</th>
	</tr>
	<tr>
		<td>Admin Email</td>
		<td>
SESSION_monitorUserListC2Name_root;
	
	echo $_SESSION ["$monitorUserListC5Name"];
	
	echo <<<SESSION_monitorUserListC2Name_root
</td>
	</tr>
	<tr>
		<td>New Oid Name<br />
		(NO Special Character)</td>
		<td><input type="text" name="newName" maxlength="45" /></td>
	</tr>
	<tr>
		<td><font color='red'>Statistic(CAREFUL)*</font></td>
		<td>
	
SESSION_monitorUserListC2Name_root;

	input_checkbox ( "isStatistic", NULL, 'Y' );
	echo <<<SESSION_monitorUserListC2Name_root
		</td>
	</tr>
	<tr>
		<td colspan='2'><font color='red'>*Statistic means this oid's value is
		a dynamic number<br />
		and need a graphic view of history.</font></td>
	</tr>
	<tr>
	
		<td><input type="reset" name="reset" value="Reset" /></td>
		<td><input type="submit" name="submit" value="  OK  " /></td>
	
	</tr>
	
	</table>
	</form>
SESSION_monitorUserListC2Name_root;

echo <<<SESSION_monitorUserListC2Name_root
</td>
</table>
SESSION_monitorUserListC2Name_root;
}
?>


</center>

</html>