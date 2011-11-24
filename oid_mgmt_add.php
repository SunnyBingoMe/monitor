<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
//	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "Login failed.";
	exit;
}
//if ($_SESSION['isAdmin'] != 'Y'){
//	$_SESSION['loginError'] == '1';
//	echo "Login failed.";
//	exit;
//}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$newOidList = preg_split("/\n/", $_POST['newOidTextarea']);
$newOidList = trimArray($newOidList);
$newOidList = array_unique($newOidList);
debugOk($newOidList);
if (!sizeof($newOidList)){
	echo "Please type in oids.";
	exit;
}
$_SESSION["newOidList"] = $newOidList;
if (!sizeof($_POST["checkbox"])){
	echo "Please choose devices.";
	exit;
}
$_SESSION["deviceIdList_Of_NewOid"] = $_POST["checkbox"];
debugOk("deviceIdList_Of_NewOid:");
debugOk($_SESSION["deviceIdList_Of_NewOid"]);
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
<title>Monitor</title>
</head>
<body>
<?php require_once 'body_head.php';?>
<center>

<table border = '0'><form action = "oid_mgmt_add_ing.php" name="oidNameList" method = "POST">
<tr><td colspan='4'><center><i>Please config each oid.</i></center></td></tr>
<tr>
<th>Numeric Oid</th> <th>Assign a Name<font color='red'>*</font></th>
</tr>
<?php 
$query = "SELECT * FROM $monitorOidNameList ORDER BY $monitorOidNameListC2Name ";
$recordListOfOidNameList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
while($recordOfOidNameList = mysql_fetch_array($recordListOfOidNameList)){
	$tOidName = $recordOfOidNameList["$monitorOidNameListC2Name"];
	$oidNameList["$tOidName"] = $tOidName.(($recordOfOidNameList["$monitorOidNameListC4Name"] == "Y")?"(STATISTIC)":"");
}
$i = 0;
foreach ($newOidList as $key=>$value)
{
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td>".$value."</td>";
	echo "<td>";
	$selectElementName = "oidAndNamePairList[$value]";
	input_select($selectElementName, NULL, $oidNameList);		
	echo "</td>";
	echo "</tr>\n";
}
?>
<tr><td colspan='3'><font color='red'>*The name will affetc the graphic view, please be careful.<br />**Statistic means this oid's value is a dynamic number and need a graphic view of history.</font></td></tr>
<tr><td colspan='3'>Total number of new oids: <?php echo $i ?><div align="right">	
<input type="reset" value="Reset" /><input type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" />
</div></td></tr>
</form></table>

</center></body>
</html>