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

<script language="JavaScript">
<?php
create_function_setAllCheckBox ();
?>
</script>

<body>

<h1><img src="http://www.bth.se/web2009/images/head_logo.png"  /></h1>

<!-- Beginning of compulsory code below -->


<center>
<?php
$query = "SELECT $monitorDeviceAndOidC4Name FROM $monitorDeviceAndOid ORDER BY $monitorDeviceAndOidC4Name ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
while ( $record = mysql_fetch_array ( $recordList ) ) 
{
	$oidNameValueAndNameLabelList[$record ["$monitorOidNameListC2Name"]] = $record ["$monitorOidNameListC2Name"];
}


if ($_SESSION ["$monitorUserListC2Name"] != "root") {
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"" . $_SESSION ["$monitorDeviceListC6Name"] . "\" ORDER BY $monitorDeviceListC2Name ";
} else {
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );


?>

</br></br></br>

<?php
if (($_SESSION ["$monitorUserListC2Name"] == 'root')) {

echo <<<SESSION_monitorUserListC2Name_root

<form action="oid_mgmt_change_name_ing.php" method="POST">
<table border="0">

	<tr>
		<th colspan="2" bgcolor='lavender'><font size='5'>Change Oid Name</font><br/></th>
	</tr>
	<tr><td>Device </td><td><select name='deviceIp'>
SESSION_monitorUserListC2Name_root;

	while ($record = mysql_fetch_array($recordList))
	{
	echo "<option value=".$record["$monitorDeviceListC2Name"].">".$record["$monitorDeviceListC2Name"]."</option>";
	}
	echo "</select></td></tr>";
	
	echo "</tr><tr><td>Old Name</td><td>";	

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
		<td width='200'>New Name<br />
		(no special character)</td>
		<td><input type="text" name="newName" maxlength="45" /></td>
	</tr>
	<tr>
	
		<td><input type="reset" name="reset" value="Reset" /></td>
		<td><input type="submit" name="submit" value="  OK  " /></td>
	
	</tr>
	
	</table>
	</form>
SESSION_monitorUserListC2Name_root;

}
?>


</center>
</body>
</html>