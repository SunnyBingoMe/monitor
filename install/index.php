<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<LINK REL="SHORTCUT ICON" HREF="../_.jpg"> 
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Monitor Install</title>
</head>
<body>
<h2>1. Prepare the database.</h2>
<?php 
$getDatabaseConnectionFilePath = 1;
require '../database_connection.php';
require '../sunny_function.php';
?>
Due to security issues, please use a text editor to manully modify this file: <br/>
<b> <?php echo $databaseConnectionFilePath ; ?></b><br/>
<br/>
set the 4 following variables values. for example:<br/><br/>
$databaseHostName = 'example.domain_name.com';<br/>
$databaseName = "database_name";<br/>
$userName = "userName";<br/>
$passWord = "my_Password";<br/>
<br/>
<h2>2. Initialize database.</h2>
2.1 <a href="first.php?isFirstPage=1" target="_blank" >Click here</a> to start initialize the data base.<br/>
2.2 In the new window, click "<u>Start Import</u>".<br/>
2.3 Wait 2 seconds, until you see "<font color="green">Congratulations: End of file reached, assuming OK</font>".<br/> 
2.4 Close that window.<br/>
2.5 <font color='red'><h3>IMPORTANT: </h3>Go to file folder <b>/monitor/install/</b>, delete the file <b>first.php</b></font>.<br/>
<br/>
<h2>3. Login and change passwords of users.</h2><br/>
3.1 <a href="../index.php" target="_blank" >Click here</a>.<br/>
3.2 In the new window, use username: <b>root</b>, and password: <b>root</b> to login.<br/>
3.3 Go to "<u>Change Password</u>" to change your password.<br/>
<h3>NOTE:</h3>
Now, there are three accounts who could login to this system:<br/>
One Root: <b>root</b>;<br/>
Two Administrators: <b>admin1, admin2</b>;<br/>
One Viewer: <b>user1</b>.<br/>
Their passwords are the same as their usernames, please change the passwords or delete the accounts. <br/>
<br/>
<h2>4. Now, enjoy it.</h2>
Please have a look at <a href="../user_manual.pdf">user_manual.pdf</a> first.
<?php brn(3); ?>

</body>
</html>