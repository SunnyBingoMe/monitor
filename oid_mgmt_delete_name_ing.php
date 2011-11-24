<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
if (!isset($_POST['deviceIpInDetails'])){ // not del fro one device, is deling one oid name
	if ($_SESSION['username'] != 'root'){
		$_SESSION['loginError'] == '1';
    	header( 'refresh: 2; url=index.php' );
		echo "Login failed.";
		exit;
	}
}else {
	$deviceIpInDetails = $_POST[deviceIpInDetails];
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$query = "SELECT MAX($monitorDeviceListC7Name), MAX($monitorDeviceListC8Name) FROM $monitorDeviceList ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
$record = mysql_fetch_array($recordList);
$oldMaxOidNumber = $record[0];
$oldMaxStatisticOidNumber = $record[1];

$oidDeletedNumber = $deviceIpList = array();
foreach ($_POST['checkbox'] as $tForeach){ // tForeach is oid name
	if (!isset($deviceIpInDetails)){
		$query = "DELETE FROM $monitorOidNameList WHERE $monitorOidNameListC2Name = '$tForeach' ";
		debugOk($query);
		mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
	}
	
	$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC4Name='$tForeach' ";
	if (isset($deviceIpInDetails)){
		$query .= " AND $monitorDeviceAndOidC2Name='$deviceIpInDetails' ";
	}
	debugOk($query);
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	while ($record = mysql_fetch_array($recordList)){
		debugOk($record);
		$deviceIpList[] = $deviceIp = $record[1];
		$oid = $record[2];
		
		$oidDeletedNumber[$deviceIp]['numberOfOid']++;
		
		if ($record[4] == 'Y'){
			$oidDeletedNumber[$deviceIp]['numberOfStatisticOid']++;
			$query = "DELETE FROM $monitorThreshold WHERE $monitorThresholdC2Name='$deviceIp' AND $monitorThresholdC3Name='$oid' ";
			mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
		}
	}

	$query = "DELETE FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC4Name = '$tForeach' ";
	if (isset($deviceIpInDetails)){
		$query .= " AND $monitorDeviceAndOidC2Name='$deviceIpInDetails' ";
	}
	debugOk($query);
	mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
}

foreach ($oidDeletedNumber as $deviceIp=>$numbersFollowing){
	foreach ($numbersFollowing as $nummberTypeName=>$number){
		$query = "UPDATE $monitorDeviceList SET $nummberTypeName = $nummberTypeName-$number WHERE $monitorDeviceListC2Name='$deviceIp' ";
		mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	}
}

$query = "SELECT MAX($monitorDeviceListC7Name), MAX($monitorDeviceListC8Name) FROM $monitorDeviceList ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
$record = mysql_fetch_array($recordList);
$newMaxOidNumber = $record[0];
$newMaxStatisticOidNumber = $record[1];
for (; $oldMaxOidNumber > $newMaxOidNumber; $oldMaxOidNumber--){
	$oldColumnName = "oid".($oldMaxOidNumber);
	$query = "ALTER TABLE $monitorSample DROP $oldColumnName ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
}
for (; $oldMaxStatisticOidNumber > $newMaxStatisticOidNumber; $oldMaxStatisticOidNumber--){
	$oldColumnName = "statisticOid".($oldMaxStatisticOidNumber);
	$query = "ALTER TABLE $monitorHourLog DROP $oldColumnName ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
}


debugOk($deviceIpList);
$deviceIpList = array_unique($deviceIpList);
foreach ($deviceIpList as $deviceIp){
	$query = "DELETE FROM $monitorSample WHERE $monitorSampleC3Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	

	$query = "DELETE FROM $monitorHourLog WHERE $monitorHourLogC3Name = '$deviceIp' ";
	mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	

	//$query = "DELETE FROM $monitorErrorLog WHERE $monitorErrorLogC3Name = '$deviceIp' ";
	//mysql_query($query,$session) or die("ERR: DELETE: ".mysql_error());	
}
if (!isset($deviceIpInDetails)){
	header("Location: oid_mgmt.php");
}else {
	header("Location: oid_mgmt_one_device_detail.php?ip=$deviceIpInDetails");
}
exit;
?>