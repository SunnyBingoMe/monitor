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
if (!isset($_POST['deviceIpInDetails'])){ // not config for one device, is configurating one oid name
	if ($_SESSION['username'] != 'root'){
		echo "Login failed.";
		exit;
	}
}else {
	$deviceIpInDetails = $_POST['deviceIpInDetails'];
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$_POST[threshold1Textarea] = replaceNonAlphabetWithSpace($_POST[threshold1Textarea]);
$_POST[threshold2Textarea] = replaceNonAlphabetWithSpace($_POST[threshold2Textarea]);
$_POST[threshold1Value] = trimAnyWhere($_POST[threshold1Value]);
$_POST[threshold2Value] = trimAnyWhere($_POST[threshold2Value]);

$oidName = $_POST["oidName"];
$oidDeletedNumber = $deviceIpAndOidPairList = array();

$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC4Name='$oidName' ";
if (isset($deviceIpInDetails)){
	$query .= " AND $monitorDeviceAndOidC2Name='$deviceIpInDetails' ";
}
debugOk($query);
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
while ($record = mysql_fetch_array($recordList)){
	debugOk($record);
	$deviceIp = $record[1];
	$oid = $record[2];
	$deviceIpAndOidPairList[] = array($deviceIp,$oid);
}

debugOk($deviceIpAndOidPairList);
foreach ($deviceIpAndOidPairList as $aDeviceIpAndOidPair){
	$threshold1Content = "";
	$threshold2Content = "";
	$query = "UPDATE $monitorThreshold SET $monitorThresholdC4Name=";
	if ($_POST['threshold1InUse'] == 'Y'){
		$threshold1Content .= "'".$_POST[threshold1Type].":".$_POST[threshold1Value].":".$_POST[threshold1Action];
		if($_POST[threshold1Action] == "email"){
			$threshold1Content .= ":".$_POST[threshold1Textarea];
		}
		$threshold1Content .= "' ";
		
	}else {
		$threshold1Content .="NULL ";
	}
	$query .= $threshold1Content;
	$query .= ", $monitorThresholdC5Name=";
	if ($_POST['threshold2InUse'] == 'Y'){
		$threshold2Content .= "'".$_POST[threshold2Type].":".$_POST[threshold2Value].":".$_POST[threshold2Action];
		if($_POST[threshold2Action] == "email"){
			$threshold2Content .= ":".$_POST[threshold2Textarea];
		}
		$threshold2Content .= "' ";
		
	}else {
		$threshold2Content .="NULL ";
	}
	$query .= $threshold2Content;
	$query .= " WHERE $monitorThresholdC2Name='$aDeviceIpAndOidPair[0]' AND $monitorThresholdC3Name='$aDeviceIpAndOidPair[1]' ";
	
	$queryTrySelect = "SELECT * FROM $monitorThreshold WHERE $monitorThresholdC2Name='$aDeviceIpAndOidPair[0]' AND $monitorThresholdC3Name='$aDeviceIpAndOidPair[1]' ";
	//debug($queryTrySelect);
	$recordListTrySelect = mysql_query($queryTrySelect,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	
	if (mysql_fetch_array($recordListTrySelect)){
		$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	}else {
		$query = "INSERT INTO $monitorThreshold VALUES (NULL, '$aDeviceIpAndOidPair[0]', '$aDeviceIpAndOidPair[1]', $threshold1Content, $threshold2Content)" ;
		$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
		debugOk($query);
	}
}

$query = "DELETE FROM $monitorThreshold WHERE $monitorThresholdC4Name IS NULL AND $monitorThresholdC5Name IS NULL ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

if (!isset($deviceIpInDetails)){
	header("Location: oid_mgmt.php");
}else {
	header("Location: oid_mgmt_one_device_detail.php?ip=$deviceIpInDetails");
}
exit;
?>