<?php 
session_start();
if (!isset($_SESSION['isAdmin']))
{
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>


<html>
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<?php require_once 'body_head.php';?>

<center>
<table border = "0" ><form action = "device_mgmt_add_ing.php" method = "POST">
<tr bgcolor='lavender'><th colspan="2"><font size='5'>Add New Device</font></th></tr>
<tr><td>Device IP<br /></td><td><input type="text" name="<?php echo"new$monitorDeviceListC2Name"?>" maxlength="40" /></td></tr>
<tr><td>Device Name<br />(NO Special Character)</td><td><input type="text" name="<?php echo"new$monitorDeviceListC3Name"?>" maxlength="50" /></td></tr>
<tr><td>SNMP Version</td><td><select name="<?php echo"new$monitorDeviceListC4Name"?>"><option value="1">1</option><option value="2c" selected>2c</option></select></td></tr>
<tr><td>SNMP Community</td><td><input type="text" name="<?php echo"new$monitorDeviceListC5Name"?>" maxlength="50" /></td></tr>
<tr><td>Admin's Email</td><td><input type="text" name="<?php echo"new$monitorUserListC5Name"?>" value="<?php echo $_SESSION["$monitorUserListC5Name"] ?>" </td></tr>

<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value="  Add  " /></td></tr>
</form></table>	
</center>

</body>
</html>