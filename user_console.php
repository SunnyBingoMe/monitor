<?php
session_start ();
if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<?php
//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>UPS Monitor</title>
</head>

<frameset cols="150px,*" border='1' noresize>
	<frame name="userMenu" src="user_menu.php">
	<frame name="userMainArea" src="user_home.php">
	<noframes>
	<body>
	<p>This page uses frames. The current browser you are using does not
	support frames.</p>
	</body>
	</noframes>
</frameset>

</html>
