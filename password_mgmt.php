<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "Login failed.";
	exit;
}
require 'database_connection.php';
//require 'sunny_function.php';
?><?php 
$aElementValuePairList = array();
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>

<?php require_once 'body_head.php';?>
<center>
<table><form action = "password_mgmt_change_ing.php" method = "POST">
<tr bgcolor='lavender'>
<th colspan='5'><font size='5'>Change password</font></th>

</tr>
<?php
$query = "SELECT * FROM $monitorUserList";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());

echo "<tr><td>Username: </td><td><select name='un'>";
if ($_SESSION['username'] == 'root'){
    while ($record = mysql_fetch_array($recordList))
    {
    	echo "<option value=".$record["$monitorUserListC2Name"].">".$record["$monitorUserListC2Name"]."</option>";
    }
}else {
    echo "<option value=".$_SESSION['username'].">".$_SESSION['username']."</option>";
}
echo "</select></td></tr>";
?>
</br></br>
<tr><td>Old password:</td><td><input type = "password" name = "oldPassword" /></td></tr>
<tr><td>New password:</td><td><input type = "password" name = "newPassword" /></td></tr>
<tr><td>Repeat new password:</td><td><input type = "password" name = "newPasswordConfirm" /></td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><button type="submit">Change</button></td></tr><br />
</form></table>

</center>
</body>
</html>