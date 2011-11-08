<?php 
session_start();
if (isset($_SESSION['isAdmin'])){
	header("Location: user_console.php");
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
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
<body>
<?php 
brn();brn();brn();
?>
<center>
<?php 
if (isset($_SESSION['loginError'])){
	if ($_SESSION['loginError'] == '1'){
		echo "Login failed.";
	}
}
?>
<form action="check_user.php" method = "POST">
Username:<input type = "text" name = "user" value='root' /><br/>
Password:<input type = "password" name = "pass" value='root' /><br/>
<button type="submit">Login</button><br/>
</form>

<?php brn(4); ?>


</center>
</body>
</html>
