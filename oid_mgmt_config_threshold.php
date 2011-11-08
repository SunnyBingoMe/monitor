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
if (!isset($_GET['deviceIpInDetails'])){ // not config for one device, is configurating one oid name
	if ($_SESSION['username'] != 'root'){
		echo "Login failed.";
		exit;
	}
}else {
	$deviceIpInDetails = $_GET["deviceIpInDetails"];
	$query = "SELECT * FROM $monitorDeviceList WHERE $monitorDeviceListC2Name='$deviceIpInDetails' ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	$record = mysql_fetch_array($recordList);
	if ($_SESSION["$monitorUserListC2Name"] != "root"){
		if ($record[$monitorDeviceListC6Name] != $_SESSION[$monitorUserListC5Name]){
			$_SESSION['loginError'] == '1';
			echo "Login failed.";
			exit;
		}
	}
	$deviceName = $record[$monitorDeviceListC3Name];
}
?>
<?php 
$oidName = $_GET['oidName'];
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php
	//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
		echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>
	
<script type="text/javascript">
<?php create_function_changeVisibility(); create_function_changeVisibility(); ?>
</script>

<body><center>

<form action = "oid_mgmt_config_threshold_ing.php" method = "post"><table>

<tr><th colspan='3'>Config Thresholds of OID: <i><?php echo $oidName; ?></i><br/>
<?php 
input_hidden("oidName", array("oidName"=>$oidName));
if (isset($deviceIpInDetails)){
	echo "for Device: <i>$deviceIpInDetails ( $deviceName )</i>";
	input_hidden("deviceIpInDetails", array("deviceIpInDetails"=>$deviceIpInDetails));
	
	$query = "SELECT * FROM $monitorThreshold WHERE $monitorThresholdC2Name='$deviceIpInDetails' AND $monitorThresholdC3Name='$_GET[oid]' ";
	$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
	if ($record = mysql_fetch_array($recordList)){
		if (strstr($record[3],":")){
			$threshold1InUse = TRUE;
			$threshold1Splited = split(":", $record[3]);
			$aElementValuePairList[threshold1Type] = $threshold1Splited[0];// min / max
			$aElementValuePairList[threshold1Value] = $threshold1Splited[1];// value
			$aElementValuePairList[threshold1Action] = $threshold1Splited[2];// action
			if ($threshold1Splited[2] == "email"){
				$aElementValuePairList[threshold1Textarea] = $threshold1Splited[3];
			}
		}else {
			$threshold1InUse = FALSE;
		}
		if (strstr($record[4],":")){
			$threshold2InUse = TRUE;
			$threshold2Splited = split(":", $record[4]);
			$aElementValuePairList[threshold2Type] = $threshold2Splited[0];
			$aElementValuePairList[threshold2Value] = $threshold2Splited[1];
			$aElementValuePairList[threshold2Action] = $threshold2Splited[2];
			if ($threshold2Splited[2] == "email"){
				$aElementValuePairList[threshold2Textarea] = $threshold2Splited[3];
			}
		}else {
			$threshold2InUse = FALSE;
		}
	}else {
		$threshold1InUse = FALSE;
		$threshold2InUse = FALSE;
	}
}else {
	$threshold1InUse = FALSE;
	$threshold2InUse = FALSE;
}

echo "</th></tr>"
?>
<tr>
<th>Threshold 1<br/>
	<input type="radio" name="threshold1InUse" value="N"  <?php echo $threshold1InUse?"":"checked"; ?> onclick="changeVisibility('threshold1Config,threshold1Textarea','N,N')" />Don't Use
	<input type="radio" name="threshold1InUse" value="Y"  <?php echo $threshold1InUse?"checked":""; ?> onclick="changeVisibility('threshold1Config,threshold1Textarea','Y,Y')" />Use It
	</th>
<th rowspan='2' width='1' bgcolor='lavender'></th> 
<th>Threshold 2<br/>
	<input type="radio" name="threshold2InUse" value="N"  <?php echo $threshold2InUse?"":"checked"; ?> onclick="changeVisibility('threshold2Config,threshold2Textarea','N,N')" />Don't Use
	<input type="radio" name="threshold2InUse" value="Y"  <?php echo $threshold2InUse?"checked":""; ?> onclick="changeVisibility('threshold2Config,threshold2Textarea','Y,Y')" />Use It
</th>
</tr>
<tr><td id="threshold1Config" style="visibility:<?php echo $threshold1InUse?"visible":"hidden"; ?>" >
<?php 
echo "If <br/>".$oidName; 
$thresholdTypeOptions = array("min"=>"<","max"=>">");
input_select("threshold1Type", $aElementValuePairList, $thresholdTypeOptions); 
//input_text("threshold1Value",$aElementValuePairList);
echo "<input type=\"text\" name=\"threshold1Value\" value=\"$aElementValuePairList[threshold1Value]\" maxlength=\"30\" /> ";
echo "<br/>then<br/>";
?>
<input type="radio" name="threshold1Action" value="email" <?php echo $threshold1InUse?(($threshold1Splited[2] == "email")?"checked":""):"checked"; ?> onclick="changeVisibility('threshold1Textarea,','Y')" />Email Administrator
<br/>
<input type="radio" name="threshold1Action" value="shutdown" <?php echo $threshold1InUse?(($threshold1Splited[2] == "shutdown")?"checked":""):""; ?> onclick="changeVisibility('threshold1Textarea','N')" />Shutdown Attached Servers
<br/>
<textarea rows="5" cols="35" name="threshold1Textarea" id="threshold1Textarea" maxlength='300' style="visibility:<?php echo $threshold1InUse?(($threshold1Splited[2] == "email")?"visible":"hidden"):"hidden"; ?>" ><?php echo $threshold1InUse?(($threshold1Splited[2] == "email")?$aElementValuePairList[threshold1Textarea]:"Please type in error description for email."):"Please type in error description for email."; ?></textarea>

</td><td id="threshold2Config" style="visibility:<?php echo $threshold2InUse?"visible":"hidden"; ?>" >
<?php 
echo "If <br/>".$oidName; 
input_select("threshold2Type", $aElementValuePairList, $thresholdTypeOptions); 
//input_text("threshold2Value",$aElementValuePairList);
echo "<input type=\"text\" name=\"threshold2Value\" value=\"$aElementValuePairList[threshold2Value]\" maxlength=\"30\" /> ";
echo "<br/>then<br/>";
?> 
<input type="radio" name="threshold2Action" value="email" <?php echo $threshold2InUse?(($threshold2Splited[2] == "email")?"checked":""):"checked"; ?> onclick="changeVisibility('threshold2Textarea','Y')" />Email Administrator
<br/>
<input type="radio" name="threshold2Action" value="shutdown" <?php echo $threshold2InUse?(($threshold2Splited[2] == "shutdown")?"checked":""):""; ?> onclick="changeVisibility('threshold2Textarea','N')" />Shutdown Attached Servers
<br/>
<textarea rows="5" cols="35" name="threshold2Textarea" id="threshold2Textarea" maxlength='300' style="visibility:<?php echo $threshold2InUse?(($threshold2Splited[2] == "email")?"visible":"hidden"):"hidden"; ?>" ><?php echo $threshold2InUse?(($threshold2Splited[2] == "email")?$aElementValuePairList[threshold2Textarea]:"Please type in error description for email."):"Please type in error description for email."; ?></textarea>

</td></tr>
<tr><td colspan='3'>NOTE: If the threshold of the value (which is about time) smaller than sample interval, we can not handle the threshold correctly.</td></tr>
<tr><td colspan='3'><div align="center">
<?php 
if (!isset($deviceIpInDetails)){
	echo "<font color='red'><h4>WARNING: This is not the current status of thresholds. To check current status, please go to \"Details of Device\".</h4>The action will affect <b>all OIDs</b> with the name: <i>$oidName</i> in monitor system.</font><br/>";
}
?>
<input type="submit" name="submit" value="<?php nbsp(3);?>OK<?php nbsp(3);?>" /></div></td></tr>

</table></form>

</center></body>

</html>