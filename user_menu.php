<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
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
<?php 
debugOk ( "this is user_menu.php" )?>
<a href="user_home.php" target="userMainArea">Home</a>
<br />
<?php 
if ($_SESSION ['isAdmin'] == 'Y') {
	echo <<<ADMIN_ONLY
	<a href="device_mgmt.php" target="userMainArea">Devices/Status</a><br />
	<a href="oid_mgmt.php" target="userMainArea">OIDs/Thresholds</a><br />
	<a href="attached_servers_mgmt.php" target="userMainArea">Attached Servers</a><br />
ADMIN_ONLY;
	if ($_SESSION ["$monitorUserListC2Name"] == "root") {
		echo "<a href=\"user_mgmt.php\" target=\"userMainArea\">Users</a><br />";
		echo "<a href=\"error_view_ing.php?offset=0\" target=\"userMainArea\">View Errors</a><br />";
	}
}
?>
<a href="view_an_oid.php" target="userMainArea">View an OID</a>
<br />
<a href="password_mgmt.php" target="userMainArea">Change Password</a>
<br />
<a href="email_address_mgmt.php" target="userMainArea">Change Email</a>
<br />
<a href="about.php" target="userMainArea">About</a>
<br />
<a href="logout.php" target="_parent">Logout</a>
<br />


</body>

</html>
