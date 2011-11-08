<?php 
session_start();
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
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
		echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>
	
</head>
<body>
<?php 
$oidAndNamePairList = $_POST["oidAndNamePairList"];
$newOidListSize = sizeof($_SESSION["newOidList"]);

if($dbugOk){
	debug("oidAndNamePairList:");
	debug($oidAndNamePairList);

	debug("deviceIdList_Of_NewOid:");
	debug($_SESSION["deviceIdList_Of_NewOid"]);
	
	debug("newOidList:");
	debug($_SESSION["newOidList"]);
}
?>
<center>
<table border='1'>
<tr><th>Device</th><th>New Oid</th><th>New Oid Name</th><th>Statistic</th><th>Result</th></tr>

<?php 
foreach ($_SESSION["deviceIdList_Of_NewOid"] as $value){
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC1Name = '$value' ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
	$record = mysql_fetch_array($recordList);
	$deviceIdAndIpPairList[$value] = $record["$monitorDeviceListC2Name"];
	$deviceIdAndNamePairList[$value] = $record["$monitorDeviceListC3Name"];
}
if($dbugOk){
	debug("deviceIdAndIpPairList:");
	debug($deviceIdAndIpPairList);
}

$query = "SELECT MAX($monitorDeviceListC7Name), MAX($monitorDeviceListC8Name) FROM $monitorDeviceList ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
$record = mysql_fetch_array($recordList);
$oldMaxOidNumber = $record[0];
$oldMaxStatisticOidNumber = $record[1];


foreach ($deviceIdAndIpPairList as $key=>$value){ //value is ip
	$addedNewOidNumber = 0;
	$addedNewStatisticOidNumber = 0;
	
	$tDeviceName = $deviceIdAndNamePairList["$key"];
	echo "<td rowspan=\"$newOidListSize+1\">ID:&nbsp;&nbsp; $key<br />IP:&nbsp;&nbsp; $value<br />Name: $tDeviceName</td>";
	$i = 0;
	foreach ($_SESSION["newOidList"] as $key_Of_NewOidList=>$value_Of_NewOidList){//$value_Of_NewOidList is oid
		$i++;
		$tOidName = $oidAndNamePairList["$value_Of_NewOidList"];
		debugOk("tOidName:".$tOidName);
		$needStatisticAndThreshold = ($_SESSION["oidNameAndIsStatisticPairList"][$tOidName]);
		echo ($key_Of_NewOidList?("<tr bgcolor=".(($i % 2)?"":"lavender").">"):"");
		echo "<td>$value_Of_NewOidList</td><td>$tOidName</td><td>$needStatisticAndThreshold</td><td>";
		
		$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC2Name = '$value' AND ($monitorDeviceAndOidC3Name = '$value_Of_NewOidList' OR $monitorDeviceAndOidC4Name = '$tOidName') ";
		$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
		if (mysql_fetch_array($recordList)){
			echo "<font color='orange'><b>Oid/Name Already exists.</b></font>";
			echo "</td></tr>\n\n";
			continue;
		}else {
			$query = "INSERT INTO $monitorDeviceAndOid VALUES(NULL, \"$value\", \"$value_Of_NewOidList\", \"$tOidName\", \"$needStatisticAndThreshold\", 'N')";
			debugOk($query);
			if (mysql_query($query,$session)){// or die("ERR: INSERT: ".mysql_error());
				echo "<font color='green'>Updated.</font>";
				$addedNewOidNumber++;
				debugOk('$addedNewOidNumber:'.$addedNewOidNumber);
				($needStatisticAndThreshold == "Y") ? $addedNewStatisticOidNumber++ : 0;
				debugOK('$addedNewOidNumber:'.$addedNewStatisticOidNumber);
			}else {
				echo "<font color=\"red\"><b>".mysql_error()."</b></font>";
			}
			echo "</td></tr>\n\n";
		}
	}
	
	if ($addedNewOidNumber > 0){
		$query = "DELETE FROM $monitorSample WHERE $monitorSampleC3Name = '$value' ";
		mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
	}
	if ($addedNewStatisticOidNumber > 0){
		$query = "DELETE FROM $monitorHourLog WHERE $monitorHourLogC3Name = '$value' ";
		mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
	}
	
	$query = "UPDATE $monitorDeviceList SET $monitorDeviceListC7Name = $monitorDeviceListC7Name+$addedNewOidNumber , $monitorDeviceListC8Name = $monitorDeviceListC8Name+$addedNewStatisticOidNumber WHERE $monitorDeviceListC2Name='$value' ";
	mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
}

$query = "SELECT MAX($monitorDeviceListC7Name), MAX($monitorDeviceListC8Name) FROM $monitorDeviceList ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
$record = mysql_fetch_array($recordList);
$newMaxOidNumber = $record[0];
$newMaxStatisticOidNumber = $record[1];
for (; $oldMaxOidNumber < $newMaxOidNumber; $oldMaxOidNumber++){
	$newColumnName = "oid".($oldMaxOidNumber + 1);
	$query = "ALTER TABLE $monitorSample ADD COLUMN $newColumnName VARCHAR (50) ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
}
for (; $oldMaxStatisticOidNumber < $newMaxStatisticOidNumber; $oldMaxStatisticOidNumber++){
	$newColumnName = "statisticOid".($oldMaxStatisticOidNumber + 1);
	$query = "ALTER TABLE $monitorHourLog ADD COLUMN $newColumnName BIGINT ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
}

?>
</table>
</center>
</body>
</html>
