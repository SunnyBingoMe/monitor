<?php 
session_start();
if (!isset($_SESSION['isAdmin']))
{
	//$_SESSION['loginError'] == '1';
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>

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
</br></br></br></br>

<center>

<table border = "0" ><form action = "user_mgmt_add_ing.php" method = "POST">
<tr bgcolor='lavender'><th colspan="2"><font size='5'>Add New User</font></th></tr>
<tr><td>Username<br />(NO Special Character)</td><td><input type="text" name="newUsername" maxlength="50" /></td></tr>
<tr><td>Password</td><td><input type="password" name="newPassword" maxlength="50" /></td></tr>
<tr><td>Confirm Password</td><td><input type="password" name="newPasswordConfirm" maxlength="50" /></td></tr>
<tr><td>Email (Important)</td><td><input type="text" name="newEmailAddress" maxlength="50" /></td></tr>
<tr><td>User Type</td><td><input type="radio" name="newUserType" value="view" checked/>Viewer | <input type="radio" name="newUserType" value="admin"/>Administrator</td></tr>
<tr><td><input type="reset" name="reset" value="Reset" /></td><td><input type="submit" name="submit" value="  Add  " /></td></tr>
</form></table>

</center>
</body>
</html>