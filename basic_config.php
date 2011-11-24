<?php 
session_start();

if (!isset($_SESSION['isAdmin'])){
	//$_SESSION["loginError"] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "<Center><font size='5' color='red'>Login failed. Please try again.</font></Center>";
	exit;
}
require 'database_connection.php';
?>
<html>
<TITLE>Basic config</TITLE>
<body>
<?php require_once 'body_head.php';?>
<center>
<form action="save_config.php" method = "POST">

<table border="1">
<tr bgcolor='lavender'>
<th><font size="5" >Name</font></th>
<th><font size="5" >Value</font></th>
<th><font size="5" >Description</font></th>
<th> <button type="submit">Save</button></br></th>
</tr>
<tr>
<td><font size="4" >Interval:</font></td>
<td><input type = "text" name = "interval"  /></br></td>
<td width='400'><font size="4" >The sampling time interval.</font></td>
</tr>
<tr>
<td><font size="4" >Error number limitation:</font></td>
<td> <input type = "text" name = "errorlimit" /></br></td>
<td><font size="4" >Indicate the limitation for the number of errors that will be stay in the database.</font></td>
</tr>
<tr>
<td><font size="4" >Sample number limitation:</font></td>
<td> <input type = "text" name = "samplelimit" /></br></td>
<td><font size="4" >Indicates the limitation for the number of samples that will be stay in the database. The value should be more than 1800 for the best performance.</font></td>
</tr>
<tr>
<td><font size="4" >Hour sample number limitation:</font></td>
<td> <input type = "text" name = "hourlimit" /></br></td>
<td><font size="4" >Indicates the limitation for the number of hour samples that will be stay in the database. The value will be calculated 
					for each device like below:</br> 24 hours * 31 days = 744 hours per month </br> 12 month * 744 hours = 8928 hours 
					per year </br> 8928 hours * 100 devices = 892800 < Value for 100 devices</font></td>
</tr>
<tr>
<td></td>

</tr>
</table>

</form>
</center>

</body>
</html>