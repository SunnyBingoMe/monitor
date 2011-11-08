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
<?php 
if (!isset($_POST['submitDelete'])){
	echo "e ..... what are you doing?"; 
	mysql_close($session);
	exit; 
}

foreach ($_POST['checkbox'] as $value){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC1Name = '$value' ";
	$recordList = mysql_query($query,$session) or die("ERR: SELECT: ".mysql_error());	
	$record = mysql_fetch_array($recordList);
	$deviceIp = $record[$monitorDeviceListC2Name];

	$query = "DELETE FROM $monitorAttachedServer WHERE $monitorAttachedServerC2Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	

	$query = "DELETE FROM $monitorDeviceList WHERE $monitorDeviceListC2Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	
	
	$query = "DELETE FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC2Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	

	$query = "DELETE FROM $monitorThreshold WHERE $monitorThresholdC2Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	

	$query = "DELETE FROM $monitorSample WHERE $monitorSampleC3Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	

	$query = "DELETE FROM $monitorHourLog WHERE $monitorHourLogC3Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	

	$query = "DELETE FROM $monitorErrorLog WHERE $monitorErrorLogC3Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	
}

header( 'refresh: 3; url=device_mgmt.php' ); // Redirect browser
echo "<Center><font size='5' color='red'>Device is deleted.</font></Center>";
exit;// Make sure that code below does not get executed when we redirect. 

?>