<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
//if ($_SESSION['isAdmin'] != 'Y'){
	//$_SESSION['loginError'] == '1';
	//echo "Login failed.";
	//exit;
//}
require 'database_connection.php';
//require 'sunny_function.php';
//if ($_SESSION["$monitorUserListC2Name"] != "root"){
//	echo $_SESSION["$monitorUserListC2Name"];
	//$_SESSION['loginError'] == '1';
//	echo "Login failed.";
//	exit;
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
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
</head>
<body>

<h1><img src="http://www.bth.se/web2009/images/head_logo.png"  /></h1>

<?php 
$query = "SELECT * FROM $monitorUserList ORDER BY $monitorUserListC2Name ";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

?>
<center>

<?php 
if (isset($_GET['newAdded'])){
	echo "<font color='red'>Just added a new user: <b><i>$_GET[newAdded]</i></b></font>";
}
?>
 </br></br></br>
 <div style='position: absolute; top: 170px; left: 225px; width: 920px; background: #BDEDFF'>
  <font size='5'>Remove a user</font>
 </div> 

<table border = '0'><form action = "user_mgmt_delete_ing.php" method = "post">
<tr bgcolor='#BDEDFF'>
<th>ID</th> <th>Username</th> <th>User Type</th> <th>Email</th>  <th>Select to Delete</th>
</tr>
<?php 
$i = 0;
while($record = mysql_fetch_array($recordList))
{
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td width='100'>".$record["$monitorUserListC1Name"]."</td>";
	echo "<td width='200'>".$record["$monitorUserListC2Name"]."</td>";
	echo "<td width='200'>";
	if ($record["$monitorUserListC4Name"] == 'Y'){
		echo "Administrator";
		$handoverOptions[$record[$monitorUserListC5Name]] = $record[$monitorUserListC2Name]." : ".$record[$monitorUserListC5Name];
	}else {
		echo "Viewer";
	}
	echo "</td>";
	echo "<td width='300'>".$record["$monitorUserListC5Name"]."</td>";
	if ($record["$monitorUserListC2Name"] != 'root'){
		echo "<td><center><input type='radio' name=\"radio\" value='".$record[$monitorUserListC1Name].":".$record[$monitorUserListC5Name]."' /></center></td>";
	}else {
		echo "<td><font color=\"gray\" ><i>Undeletable.</i></font></td>";
	}
	echo "</tr>\n";
}
?>

<tr><td colspan='5'>Total number of user records: <?php echo "$i <br/>" ?>
	<?php 
		echo "<font color='red'>After deleted, handover devices and permission to:</font></br>";
		echo "<select name='newEmailAddress'>";
		foreach($handoverOptions as $v)
		{
			echo "<option value=".$v.">".$v."</option>";
		}
		echo "</select>";
		//input_select("newEmailAddress", NULL, $handoverOptions); 
	?>
	</td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
	<td><div align="right"><input type="reset" name="reset" value="Select None" /></div></td>
<td><input type="submit" name="submitDelete" value="Delete Selected" /></td>
<tr>
</form></table>


</center>
</body>
</html>