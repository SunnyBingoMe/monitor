<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require_once 'database_connection.php';
require_once 'sunny_function.php';
?>
<?php

if (! isset ( $_GET ['deviceIpInDetails'] )) { // not del fro one device, is deling one oid name
	if ($_SESSION ['username'] != 'root') {
		$_SESSION ['loginError'] == '1';
		echo "Login failed.";
		exit ();
	}
} else {
	$deviceIpInDetails = $_GET [deviceIpInDetails]; // do not need permission to view it.


/*if you wanna restrict permission, please uncommnet it.
	 * 		
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIpInDetails' ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	$record = mysql_fetch_array($recordList);
	if ($_SESSION["$monitorUserListC2Name"] != "root"){
		if ($record[$monitorDeviceListC6Name] != $_SESSION[$monitorUserListC5Name]){
			$_SESSION['loginError'] == '1';
			echo "Login failed.";
			exit;
		}
	}*/
}

?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

<style>
<!--
.leftVerticalLabel {
	width: 100px;
	writing-mode: tb-rl;
	filter: flipv fliph;
	-webkit-transform: rotate(-90deg);
	-moz-transform: rotate(-90deg);
}
-->
</style>
</head>

<body>
<center>
<?php
$timeStampColumnName = "timeStamp";
$ipColumnName = "ip";
$numberOfValueNeeded = 60;

$oidName = $_GET [oidName];

/*
$tDateTimeNow = new DateTime();
$tDateInterval = new DateInterval("PT01H00M00S");
$tDateTimeStart = $tDateTimeNow->sub($tDateInterval);
$timeEnd = $tDateTimeNow->format("Y-m-d H:i:s");
$timeStart = $tDateTimeStart->format("Y-m-d H:i:s");*/

$viewType = $_GET [viewType];
if ($viewType == "hour") {
	$clickIntoViewType = "hour";
	$tableName = $monitorSample;
	$xAxisFormat = "H:i:s";
	$tDateInterval = 60 * 15 * 1;

} elseif ($viewType == "day") {
	$clickIntoViewType = "hour";
	$tableName = $monitorHourLog;
	$xAxisFormat = "m-d H:i";
	$tDateInterval = 60 * 60 * 24;
} elseif ($viewType == "week") {
	$clickIntoViewType = "day";
	$tableName = $monitorHourLog;
	$xAxisFormat = "m-d H:i";
	$tDateInterval = 60 * 60 * 24 * 7;
} elseif ($viewType == "month") {
	$clickIntoViewType = "week";
	$tableName = $monitorHourLog;
	$xAxisFormat = "m-d H:i";
	$tDateInterval = 60 * 60 * 24 * 30;
}

if (! isset ( $_GET [tTimeEnd] )) {
	$_GET [tTimeEnd] = time ();
}
if (isset ( $_GET ['x'] )) {
	$graphX = $_GET [x];
	$xPixelWidthAllValue = 921 - 81;
	$valueNumber = sizeof ( $_SESSION [graphData] [$deviceIpInDetails] [dataKeyForClickInto] );
	$xPixelWidthEachValue = $xPixelWidthAllValue / $valueNumber;
	$indexOfDataKeyForClickInto = floor ( ($graphX - 81) / $xPixelWidthEachValue );
	if ($indexOfDataKeyForClickInto < 0) {
		$indexOfDataKeyForClickInto = 0;
	}
	if ($indexOfDataKeyForClickInto > $valueNumber - 1) {
		$indexOfDataKeyForClickInto = $valueNumber - 1;
	}
	$stringOftTimeEnd = $_SESSION [graphData] [$deviceIpInDetails] [dataKeyForClickInto] [$indexOfDataKeyForClickInto];
	unset($_SESSION [graphData] [$deviceIpInDetails] [dataKeyForClickInto]);
	$tTimeEnd = strtotime ( $stringOftTimeEnd );
	debugOk ( $graphX );
	debugOk ( "\$indexOfDataKeyForClickInto: ".$indexOfDataKeyForClickInto );
	debugOk ( "\$stringOftTimeEnd: ".$stringOftTimeEnd );
	debugOk ( "\$tTimeEnd: ".$tTimeEnd );
	debugOk ( "timetostr ( \$tTimeEnd ): ".timetostr ( $tTimeEnd ) );
	$tTimeEnd = $tTimeEnd + ($tDateInterval / 2);
	debugOk ("timetostr \$tTimeEnd final: ".timetostr($tTimeEnd));
} else {
	$tTimeEnd = ( int ) $_GET [tTimeEnd];
}
$tTimeStart = $tTimeEnd - $tDateInterval;
$timeEnd = timetostr ( $tTimeEnd );
$newerTTimeEnd = $tTimeEnd + $tDateInterval;
$olderTTimeEnd = $tTimeEnd - $tDateInterval;
$timeStart = timetostr ( $tTimeStart );
?>
<?php

function getTwoSubscriptByIpAndOid($ip, $oid) {
	global $session;
	global $monitorDeviceAndOid;
	global $monitorDeviceAndOidC1Name;
	global $monitorDeviceAndOidC2Name;
	global $monitorDeviceAndOidC3Name;
	global $monitorDeviceAndOidC4Name;
	global $monitorDeviceAndOidC5Name;
	global $monitorDeviceAndOidC6Name;
	
	$deviceIpInDetailsAndOidList_findTwoSubscriptByIpAndOid = $deviceIpInDetailsAndStatisticOidList_findTwoSubscriptByIpAndOid = array ();
	$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC2Name = '$ip' ORDER BY '$monitorDeviceAndOidC1Name' ";
	debugOk ( $query );
	$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b> : " . mysql_error () );
	while ( $record = mysql_fetch_array ( $recordList ) ) {
		$deviceIpInDetailsAndOidList_findTwoSubscriptByIpAndOid [] = $record [$monitorDeviceAndOidC3Name];
		if ($record [4] == "Y") {
			$deviceIpInDetailsAndStatisticOidList_findTwoSubscriptByIpAndOid [] = $record [$monitorDeviceAndOidC3Name];
		}
	}
	$twoSubscript [0] = array_search ( $oid, $deviceIpInDetailsAndOidList_findTwoSubscriptByIpAndOid );
	$twoSubscript [1] = array_search ( $oid, $deviceIpInDetailsAndStatisticOidList_findTwoSubscriptByIpAndOid );
	
	debugOk ( $twoSubscript );
	return $twoSubscript;
}

$query = "SELECT * FROM $monitorDeviceAndOid WHERE $monitorDeviceAndOidC4Name = '$oidName' ";
if (isset ( $deviceIpInDetails )) {
	$query .= " AND $monitorDeviceAndOidC2Name = '$deviceIpInDetails' ";
}
debugOk ( $query );
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
$numberOfRow = mysql_num_rows ( $recordList );
if (! $numberOfRow) {
	echo "There is not any OID with name: <i>" . $oidName . "</i>, you can add OIDs in menu: <i>Oids/Thresholds</i>.</center></body></html>";
	exit ();
}

echo "<a href=\"graph_view_ing.php?" . (isset ( $deviceIpInDetails ) ? "deviceIpInDetails={$deviceIpInDetails}&" : "") . "tTimeEnd=$olderTTimeEnd&viewType=$viewType&oidName=$oidName\" >Older</a>";
nbsp ( 4 );
echo "$timeStart ~ $timeEnd";
nbsp ( 4 );
echo "<a href=\"graph_view_ing.php?" . (isset ( $deviceIpInDetails ) ? "deviceIpInDetails={$deviceIpInDetails}&" : "") . "tTimeEnd=$newerTTimeEnd&viewType=$viewType&oidName=$oidName\" >Newer</a>";
echo "<br /><b>";
if ($viewType == "hour") {
	echo "QuarterHourly";
} elseif ($viewType == "day") {
	echo "Daily";
} elseif ($viewType == "week") {
	echo "Weekly";
} elseif ($viewType == "month") {
	echo "Monthly";
}
echo " View </b>";

while ( $record = mysql_fetch_array ( $recordList ) ) { // for each ip (cause ecach ip could not have the same oidName twice 
	debugOk ( $record );
	
	echo "<hr />";
	
	$deviceIp = $record [1];
	$oid = $record [2];
	$oidName = $record [3];
	//debug($deviceIp);debug($oid);exit;
	

	$twoSubscript = getTwoSubscriptByIpAndOid ( $deviceIp, $oid );
	
	if ($tableName == $monitorSample) {
		$oidColumnName = "oid" . ($twoSubscript [0] + 1);
	} else {
		$oidColumnName = "statisticOid" . ($twoSubscript [1] + 1);
	}
	
	$data = $dataKeyForClickInto = array ();
	$query = "SELECT * FROM $tableName WHERE $ipColumnName = '$deviceIp' AND $timeStampColumnName > '$timeStart' AND $timeStampColumnName < '$timeEnd' ORDER BY 'id' ";
	debugOk ( $query );
	$recordList_ = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
	while ( $record = mysql_fetch_array ( $recordList_ ) ) {
		$key = new DateTime ( $record [$timeStampColumnName] );
		$dataKeyForClickInto [] = $key->format ( "Y-m-d H:i:s" );
		$key = $key->format ( $xAxisFormat );
		$data [$key] = $record [$oidColumnName];
	}
	
	$query = "SELECT $monitorDeviceListC3Name FROM $monitorDeviceList WHERE $monitorDeviceListC2Name = '$deviceIp' ";
	$recordList_ = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
	$record = mysql_fetch_array ( $recordList_ );
	$deviceName = $record [0];
	echo $oid . " of <b><i>" . $deviceIp . " ( $deviceName ) </i></b><br />";
	
	debugOk ( $data );
	if (! sizeof ( $data )) {
		echo "NO DATA";
	} else {
		$probabilityOfInUse = $numberOfValueNeeded / sizeof ( $data );
		$data = resizeArrayByProbability ( $data, $probabilityOfInUse );
		$dataKeyForClickInto = resizeArrayByProbability ( $dataKeyForClickInto, $probabilityOfInUse );
		
		$_SESSION [graphData] [$deviceIp] [dataKeyForClickInto] = $dataKeyForClickInto;
		$_SESSION [graphData] [$deviceIp] [$oidColumnName] [data] = $data;
		$_SESSION [graphData] [$deviceIp] [$oidColumnName] [xMarginPercent] = 25;
		$_SESSION [graphData] [$deviceIp] [$oidColumnName] [yMarginPercent] = 8;
		
		$query = "SELECT * FROM $monitorThreshold WHERE $monitorThresholdC2Name = '$deviceIp' AND $monitorThresholdC3Name = '$oid' ";
		$recordList_ = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
		$record = mysql_fetch_array ( $recordList_ );
		if ($record) {
			if ($record [$monitorThresholdC4Name]) {
				$thresholdSplited = explode ( ":", $record [$monitorThresholdC4Name] );
				$_SESSION [graphData] [$deviceIp] [$oidColumnName] [threshold1] = $thresholdSplited [1];
			}
			if ($record [$monitorThresholdC4Name]) {
				$thresholdSplited = explode ( ":", $record [$monitorThresholdC5Name] );
				$_SESSION [graphData] [$deviceIp] [$oidColumnName] [threshold2] = $thresholdSplited [1];
			}
		}
		
		if ($viewType != "hour") {
			echo "<form action=\"graph_view_ing.php\" method=\"get\">";
		}
		echo <<<imgTable
		<table>
		<input type="hidden" name="tTimeEnd" value="$tTimeEnd" />
		<input type="hidden" name="viewType" value="$clickIntoViewType" />
		<input type="hidden" name="oidName" value="$oidName" />
		<input type="hidden" name="deviceIpInDetails" value="$deviceIp" />
			<tr>
				<td>
					<div class="leftVerticalLabel">$oidName</div>
				</td>
				<td><input type="image" alt="submit" src="graph_img_src_url.php?deviceIp=$deviceIp&oidColumnName=$oidColumnName" /></td>
			<tr>
		</table>
		</form>
imgTable;
		if ($viewType != "hour") {
			echo "</form>";
		}
		
		
		$valueNumber = sizeof($data);
		$columnNumber = 7;
		$valueNumberEachColumn = $valueNumber / $columnNumber;
		echo "<table border='1'><tr valign=\"top\" >";
		for (; ($columnNumber > 0) && (sizeof($data) > 0); $columnNumber--){
			echo <<<dataValueTable
				<td>
					<table border='0'>
						<tr>
							<th>Time</th>
							<th>Value</th>
						</tr>
dataValueTable;
			$i = 0;
			foreach ( $data as $dataTableC1 => $dataTableC2 ) {
				$i ++;
				echo "<tr bgcolor=" . (($i % 2) ? "" : "lavender") . ">";
				echo "<td>" . $dataTableC1 . "</td>";
				printf("<td>" . "%d" . "</td>" , $dataTableC2) ;
				echo "</tr>\n";
				unset($data[$dataTableC1]);
				if ($i >= $valueNumberEachColumn){
					echo "</table></form>";
					continue 2;
				}
			}
			echo <<<dataValueTable
					</table>
					</form>
				</td>
dataValueTable;
		}
		echo "</tr></table>";
	}// show 'NO DATA ' or show graph and values
	echo "<br />";
}// for each ip
?>
<hr />
<?php
echo "<a href=\"graph_view_ing.php?" . (isset ( $deviceIpInDetails ) ? "deviceIpInDetails={$deviceIpInDetails}&" : "") . "tTimeEnd=$olderTTimeEnd&viewType=$viewType&oidName=$oidName\" >Older</a>";
nbsp ( 4 );
echo "$timeStart ~ $timeEnd";
nbsp ( 4 );
echo "<a href=\"graph_view_ing.php?" . (isset ( $deviceIpInDetails ) ? "deviceIpInDetails={$deviceIpInDetails}&" : "") . "tTimeEnd=$newerTTimeEnd&viewType=$viewType&oidName=$oidName\" >Newer</a>";
echo "<br /><b>";
if ($viewType == "hour") {
	echo "QuarterHourly";
} elseif ($viewType == "day") {
	echo "Daily";
} elseif ($viewType == "week") {
	echo "Weekly";
} elseif ($viewType == "month") {
	echo "Monthly";
}
echo " View </b>";
?>
</center>
</body>
</html>