<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?><?php 
$query = "SELECT COUNT(*) FROM $monitorDeviceList ORDER BY $monitorDeviceListC2Name ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if ($record = mysql_fetch_array($recordList)){
	$totalDeviceNumber = $record[0];
}else {
	$totalDeviceNumber = 0;
}
if ($_SESSION["$monitorUserListC2Name"] != "root"){
	$query = "SELECT COUNT(*) FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorUserListC5Name"]."\" ORDER BY $monitorDeviceListC2Name ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	if ($record = mysql_fetch_array($recordList)){
		$totalDeviceNumberOwned = $record[0];
	}else {
		$totalDeviceNumberOwned = 0;
	}
}else {
	$totalDeviceNumberOwned = $totalDeviceNumber;
}



//if ($_SESSION["$monitorUserListC2Name"] != "root"){
//	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC6Name=\"".$_SESSION["$monitorDeviceListC6Name"]."\" ORDER BY $monitorDeviceListC2Name ";
//}else {
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC10Name='Y' ORDER BY $monitorDeviceListC2Name ";
//}
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
$aErrorContentList = array();
while ($record = mysql_fetch_array($recordList)){
	if (($_SESSION["$monitorUserListC2Name"] == "root") || ($record[$monitorDeviceListC6Name] == $_SESSION[$monitorUserListC5Name])){
		$errorContent = "<a href=\"oid_mgmt_one_device_detail.php?ip=$record[$monitorDeviceListC2Name]\" >";
	}else {
		$errorContent = "";
	}
	$errorContent .= "<font color='red'><b>ERROR:</b></font> $record[$monitorDeviceListC2Name] ( $record[$monitorDeviceListC3Name] ). ";
	if (($_SESSION["$monitorUserListC2Name"] == "root") || ($record[$monitorDeviceListC6Name] == $_SESSION[$monitorUserListC5Name])){
		$errorContent .= "</a>";
	}
	$errorContent .= "Admin: $record[$monitorDeviceListC6Name] ";
	if (($_SESSION["$monitorUserListC2Name"] == "root") || ($record[$monitorDeviceListC6Name] == $_SESSION[$monitorUserListC5Name])){
		$errorContent .= returnNbsp(3)."<button onClick=\"window.location=('error_reset_ing.php?ip=$record[$monitorDeviceListC2Name]')\">I've solved problems of this device.</button>";
	}
	$aErrorContentList[] = $errorContent;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
		echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>
	
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<script type="text/javascript">
<?php create_function_changeVisibility(); ?>
function turnMonitorOnOff(isTurningOn, runTest){
	changeVisibility("checkingStatus,monitorStatus,runIt,runTest,stopIt","Y,N,N,N,N");
	/*
	ajaxObject = new XMLHttpRequest();
	if(isTurningOn == 1){
		ajaxObject.open("GET","php_perl_run_ing.php",true);
	}else {
		ajaxObject.open("GET","php_perl_stop_ing.php",true);
	}
	ajaxObject.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajaxObject.send("runTest="+runTest);*/
	
	if(isTurningOn == 1){
		window.location=('php_perl_run_ing.php'+"?runTest="+runTest);
	}else {
		window.location=('php_perl_stop_ing.php');
	}
	setTimeout("window.location.reload(true);", 5*1000);
}
</script>

</head>
<body><center>

<h2> UPSNMP MONITOR </h2>Version 11.06
<?php brn(3)?>
<table>
<tr><td>Server Time:</td><td><?php echo timetostr(time()); ?></td></tr>
<tr><td>System Devices (Owned/Total):<?php nbsp(5); ?> </td><td><b><?php echo $totalDeviceNumberOwned."/".$totalDeviceNumber; ?></b></td></tr>
<tr><td>System Status (Total):</td>
	<td>
	<?php 
	if (!sizeof($aErrorContentList)){
		echo "<font color='green'>All devices are OK.</font>";
	}else {
		foreach ($aErrorContentList as $errorContent){
			echo $errorContent.returnBrn();
		}
	}
	?>
	</td>
</tr>
</table>


<?php 
brn(3);

$query = "SELECT * FROM $monitorConfig ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());
$record = mysql_fetch_array($recordList);

?>
<span id="checkingStatus" style="visibility:hidden">Checking Status ...</span><br/>
<span id="monitorStatus">
Monitor Status: <?php echo $record[$monitorConfigC2Name]?"<font color='green'>RUNNING ( PID: $record[$monitorConfigC2Name] ) </font>":"<font color='red'>STOPPED</font>"; ?>
</span>
<?php brn(2); 
if ($_SESSION["$monitorUserListC2Name"] == "root"){ ?>
	<input id="runIt" type="button" onclick="turnMonitorOnOff(1, 0)" value=" Run it " />
<!--	<input id="runTest" type="button" onclick="turnMonitorOnOff(1, 1)" value="Run test" />
	<input id="runTest" type="button" onclick="window.open('php_perl_run_ing.php?runTest=1');" value="Run test" /> -->
	<input id="stopIt" type="button" onclick="turnMonitorOnOff(0, 0)" value=" Stop it " />
	<br />
	to test only data sample, run test (200 samples)
	<br /><br />
	
	<form action = "config_ing.php" method = "post">
	<input name="configType" type="hidden" value="interval" />
	Sample Interval (sec): <input name="interval" type="text" maxlength="3" value="<?php echo $record[$monitorConfigC1Name] ?>"/>
	<input type="submit" value=" Ok " />
	</form>
	<br/><br/>
	<font color='red'>!!!!! OBS !!!!!<button onClick="window.open('install/bigdump.php?isFirstPage=1')" value="INITIALIZE SYSTEM" >INITIALIZE SYSTEM</button>DANGEROUS</font>
<?php 
}
?>
</center></body>
</html>