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
if ($_SESSION["$monitorUserListC2Name"] != "root"){
	echo $_SESSION["$monitorUserListC2Name"];
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
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
	
</head>

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
<table border = '0'><form action = "user_mgmt_delete_ing.php" method = "post">
<tr>
<th>ID</th> <th>Username</th> <th>User Type</th> <th>Email</th>  <th>Select to Delete</th>
</tr>
<?php 
$i = 0;
while($record = mysql_fetch_array($recordList))
{
	$i++;
	echo "<tr bgcolor=".(($i % 2)?"":"lavender").">";
	echo "<td>".$record["$monitorUserListC1Name"]."</td>";
	echo "<td>".$record["$monitorUserListC2Name"]."</td>";
	echo "<td>";
	if ($record["$monitorUserListC4Name"] == 'Y'){
		echo "Administrator";
		$handoverOptions[$record[$monitorUserListC5Name]] = $record[$monitorUserListC2Name]." : ".$record[$monitorUserListC5Name];
	}else {
		echo "Viewer";
	}
	echo "</td>";
	echo "<td>".$record["$monitorUserListC5Name"]."</td>";
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
		echo "<font color='red'>After deleted, handover devices and permission to:</font><br/>";
		input_select("newEmailAddress", NULL, $handoverOptions); 
	?>
	<div align="right"><input type="reset" name="reset" value="Select None" /><input type="submit" name="submitDelete" value="Delete Selected" /></div></td>
</tr>
</form></table>
<hr />

<table border = "0" ><form action = "user_mgmt_add_ing.php" method = "POST">
<tr><th colspan="2">Add New User</th></tr>
<tr><td>Username<br />(NO Special Character)</td><td><input type="text" name="newUsername" maxlength="50" /></td></tr>
<tr><td>Password</td><td><input type="password" name="newPassword" maxlength="50" /></td></tr>
<tr><td>Confirm Password</td><td><input type="password" name="newPasswordConfirm" maxlength="50" /></td></tr>
<tr><td>Email (Important)</td><td><input type="text" name="newEmailAddress" maxlength="50" /></td></tr>
<tr><td>User Type</td><td><input type="radio" name="newUserType" value="view" checked/>Viewer | <input type="radio" name="newUserType" value="admin"/>Administrator</td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value="  Add  " /></td></tr>
</form></table>

</center>
</html>