<?php 
session_start();
?><?php 
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$aElementValuePairList = array();
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
<center>
<table><form action = "email_address_mgmt_change_ing.php" method = "POST">
<tr><td>Username: </td><td><?php echo $_SESSION["$monitorUserListC2Name"] ?></td></tr>
<tr><td>Old Email:</td><td><?php echo $_SESSION["$monitorUserListC5Name"] ?></td></tr>
<tr><td>New Email:</td><td><?php input_text("newEmailAddress",$aElementValuePairList,50); ?></td></tr>
<tr><td>Confirm New Email:</td><td><?php input_text("newEmailAddressConfirm",$aElementValuePairList,50); ?></td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value=" OK " /></td></tr><br />
</form></table>

</center>
</body>
</html>