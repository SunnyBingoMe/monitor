<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
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
	
</head>
<center>
<?php 
if ($_SESSION["$monitorUserListC2Name"] != "root"){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\" ORDER BY $monitorDeviceListC2Name ";
}else {
	$query = "SELECT * FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
?>
<table border = '0' width='80%'><form action = "device_mgmt_delete_ing.php" method = "post">
<tr><th colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?7:6 ?>'>Devices</th></tr>
<tr>
<th>ID</th> <th>Device IP</th> <th>Device Name</th> <th>Snmp Config</th> <?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?"<th>Admin Email</th>":"" ?> <th>Status</th> <th>Select to Delete</th> 
</tr>
<?php 
$i = 0;
while($record = mysql_fetch_array($recordList)){
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td>".$record["$monitorDeviceListC1Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC2Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC3Name"]."</td>";
	echo "<td>".$record["$monitorDeviceListC4Name"];
	echo " : ".	$record["$monitorDeviceListC5Name"]."</td>";
	if ($_SESSION["$monitorUserListC2Name"] == "root"){
		echo "<td>".$record["$monitorDeviceListC6Name"]."</td>";
	}
	echo "<td><a href=\"oid_mgmt_one_device_detail.php?ip=$record[$monitorDeviceListC2Name]\" >";
	if ($record["$monitorDeviceListC10Name"] == 'Y'){
		echo "<font color=\"red\">ERROR</font>";
	}else {
		echo "OK";
	}
	echo "</a></td>";
	echo "<td><center><input type='checkbox' name=\"checkbox[]\" value='".$record["$monitorDeviceListC1Name"]."' /></center></td>";
	echo "</tr>\n";
}
?>
<tr><td colspan='<?php echo ($_SESSION["$monitorUserListC2Name"] == "root")?7:6 ?>'>Total number of device records: <?php echo "$i"?><br/><font color='red'>All info in monitor system about the deleted devices will be gone.</font><div align="right"><input type="reset" name="reset" value="Select None" /><input type="submit" name="submitDelete" value="Delete Selected" /></div></td></tr>
</form></table>
<hr />

<table border = "0" ><form action = "device_mgmt_add_ing.php" method = "POST">
<tr><th colspan="2">Add New Device</th></tr>
<tr><td>Device IP<br /></td><td><input type="text" name="<?php echo"new$monitorDeviceListC2Name"?>" maxlength="40" /></td></tr>
<tr><td>Device Name<br />(NO Special Character)</td><td><input type="text" name="<?php echo"new$monitorDeviceListC3Name"?>" maxlength="50" /></td></tr>
<tr><td>SNMP Version</td><td><select name="<?php echo"new$monitorDeviceListC4Name"?>"><option value="1">1</option><option value="2c" selected>2c</option></select></td></tr>
<tr><td>SNMP Community</td><td><input type="text" name="<?php echo"new$monitorDeviceListC5Name"?>" maxlength="50" /></td></tr>
<tr><td>Admin's Email</td><td><?php echo $_SESSION["$monitorUserListC5Name"] ?></td></tr>
<tr><td>No. of Oids</td><td>0</td></tr>
<tr><td>No. of Statistic Oids</td><td>0</td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value="  Add  " /></td></tr>
</form></table>	


</center>

</html>