<?php 
session_start();
if (!isset($_SESSION['isAdmin']))
{
	//$_SESSION['loginError'] == '1';
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>


<html>
<head>
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
</br></br></br>
<center>

<form action = "add_oid_save.php" method = "POST">


<table border = "0" >
<tr bgcolor='lavender'><th colspan="2"><font size='5'>Add New Device</font></th></tr>
<tr><td width='200'>OID<br /></td><td><input type="text" name="oidNumber" maxlength="40" /></td></tr>
<tr><td>Name<br />(no special character)</td><td><input type="text" name="oidName" /></td></tr>
<tr><td>Need statistic</td><td><select name="needStatistic"><option value="Y">Yes</option><option value="N" selected>No</option></select></td></tr>

</table>	
</br></br>
<table>
<tr bgcolor = 'lavender'><th colspan='3'><font size='5'>Config Thresholds of the OID</font><br/></th></tr>
<tr>
<th></br>Threshold 1</br></br>
	<input type="radio" name="threshold1InUse" value="N"  onclick="changeVisibility('threshold1Config,threshold1Textarea','N,N')" />Don't Use
	<input type="radio" name="threshold1InUse" value="Y"  onclick="changeVisibility('threshold1Config,threshold1Textarea','Y,Y')" />Use It
	</th>
<th rowspan='2' width='1' ></th> 
<th></br>Threshold 2</br></br>
	<input type="radio" name="threshold2InUse" value="N"   onclick="changeVisibility('threshold2Config,threshold2Textarea','N,N')" />Don't Use
	<input type="radio" name="threshold2InUse" value="Y"   onclick="changeVisibility('threshold2Config,threshold2Textarea','Y,Y')" />Use It
</th>
</tr>
<tr><td id="threshold1Config" style="visibility:<?php echo $threshold1InUse?"visible":"hidden"; ?>" >
</br>
<input type="radio" name="threshold1Action" value="shutdown"  onclick="changeVisibility('threshold1Textarea','N')" />Shutdown Attached Servers
<br/>
<input type="radio" name="threshold1Action" value="email"  onclick="changeVisibility('threshold1Textarea,','Y')" />Email Administrator
<br/>
<textarea rows="5" cols="35" name="threshold1Textarea" id="threshold1Textarea" maxlength='300'>Please type in error description for email.</textarea>

</td><td id="threshold2Config" style="visibility:<?php echo $threshold2InUse?"visible":"hidden"; ?>" >
</br>
<input type="radio" name="threshold2Action" value="shutdown" onclick="changeVisibility('threshold2Textarea','N')" />Shutdown Attached Servers
<br/>
<input type="radio" name="threshold2Action" value="email"  onclick="changeVisibility('threshold2Textarea','Y')" />Email Administrator
<br/>
<textarea rows="5" cols="35" name="threshold2Textarea" id="threshold2Textarea" maxlength='300' >Please type in error description for email.</textarea>

</td></tr>

<tr><td></br><input type="reset" name="reset" value="Reset" /></td><td></td><td><div align="right"><input type="submit" name="submit" value="  Add  " /></div></td></tr>

</table>

</br></br><font color='red'>NOTE: If the threshold of the value (which is about time) smaller than sample interval, we can not handle the threshold correctly.</font>

</form>
</center>
</body>
</html>