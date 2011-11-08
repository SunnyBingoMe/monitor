<?php
session_start ();

require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<LINK REL="SHORTCUT ICON" HREF="_.jpg"> 
<?php
//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Monitor</title>
</head>


<frameset border="1" rows="70px,*" noresize="noresize" >
	<frame name="logo" src="logo.php" />
	<frame src="login.php" />
</frameset>


</html>