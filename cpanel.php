<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	//$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
    echo "<Center><font size='5' color='red'>Access denied. You are not allowed to enter this page. Call the adminitrator for more information.</font></Center>";
	exit;
}
require 'database_connection.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>SNMP UPS monitor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>

<?php require_once 'body_head.php';?>

<div style='position: absolute; top: 250px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>UPS devices</font>
</div>
    <div style='position: absolute; top: 300px; left: 350px;'>
    	<a href='add_device.php'><img src='ups_icon.jpg' width='80' height='80' />
    </div>
    <div style='position: absolute; top: 400px; left: 325px;'>
    	<font size = '3'>Add a new device</font></a>
    </div>
    <div style='position: absolute; top: 300px; left: 500px;'>
    	<a href='device_mgmt.php'><img src='remove_ups.jpg' width='80' height='80' />
    </div>
    <div style='position: absolute; top: 400px; left: 475px;'>
    	<font size = '3'>Remove a device</font></a>
    </div>
    <div style='position: absolute; top: 300px; left: 650px;'>
    	<a href='edit_device.php'><img src='edit_ups.jpg' width='80' height='80' />
    </div>
    <div style='position: absolute; top: 400px; left: 645px;'>
    	<font size = '3'>Edit a device</font></a>
    </div>




<div style='position: absolute; top: 450px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>OIDs and thresholds</font>
</div>

<!--
<div style='position: absolute; top: 500px; left: 290px;'>
	<a href='add_oid_devices.php'><img src='oid.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 600px; left: 250px;'>
	<font size = '3'>Add oids and thresholds</font></a>
</div>
<div style='position: absolute; top: 500px; left: 495px;'>
	<a href='remove_oid_devices.php'><img src='remove_oid.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 600px; left: 440px;'>
	<font size = '3'>Remove oids and thresholds</font></a>
</div>

<div style='position: absolute; top: 500px; left: 680px;'>
	<a href='change_oid_name.php'><img src='oid_change.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 600px; left: 665px;'>
	<font size = '3'>Change OID name</font></a>
</div>

<div style='position: absolute; top: 500px; left: 850px;'>
	<a href='change_thresholds.php'><img src='threshold.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 600px; left: 835px;'>
	<font size = '3'>Change thresholds</font></a>
</div>

-->

    <div style='position: absolute; top: 500px; left: 350px;'>
    	<a href='oid_mgmt.php'><img src='threshold.jpg' width='80' height='80' />
    </div>
    <div style='position: absolute; top: 600px; left: 270px;'>
    	<font size = '3'>Add/Remove OIDs and thresholds</font></a>
    </div>





<div style='position: absolute; top: 650px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>Attached servers</font>
</div>
<div style='position: absolute; top: 700px; left: 350px;'>
	<a href='attached_servers_devices.php'><img src='server.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 800px; left: 285px;'>
	<font size = '3'>Attached servers management</font></a>
</div>



<div style='position: absolute; top: 850px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>User accounts</font>
</div>
<div style='position: absolute; top: 900px; left: 350px;'>
	<a href='add_user.php'><img src='users.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 1000px; left: 330px;'>
	<font size = '3'>Add a new user</font></a>
</div>
<div style='position: absolute; top: 900px; left: 510px;'>
	<a href='user_mgmt.php'><img src='remove_user.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 1000px; left: 490px;'>
	<font size = '3'>Remove a user</font></a>
</div>
<div style='position: absolute; top: 900px; left: 660px;'>
	<a href='password_mgmt.php'><img src='password.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 1000px; left: 640px;'>
	<font size = '3'>Change password</font></a>
</div>



<div style='position: absolute; top: 1050px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>Basic configuration</font>
</div>
<div style='position: absolute; top: 1100px; left: 350px;'>
	<a href='basic_config.php'><img src='config.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 1200px; left: 325px;'>
	<font size = '3'>Basic configuration</font></a>
</div>


<div style='position: absolute; top: 1250px; left: 250px;width:750px; background: lavender'>
	<font size = '4'>  View Errors</font>
</div>
<div style='position: absolute; top: 1300px; left: 350px;'>
	<a href='error_view_ing.php'><img src='err.jpg' width='80' height='80' />
</div>
<div style='position: absolute; top: 1400px; left: 325px;'>
	<font size = '3'>  View Errors</font></a>
</div>





<div style='position: absolute; top: 1450px; left: 250px;width:750px; background: lavender'>
	<font size = '2'>Cpanel v1.0</font>
</div>


</body>
</html>