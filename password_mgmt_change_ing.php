<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
header( 'refresh: 2; url=home.php' );

require 'database_connection.php';
//require 'sunny_function.php';
?><?php 
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$cryptedNewPassword = crypt($newPassword, '$1$Sunny_Cr$');
$newPasswordConfirm = $_POST['newPasswordConfirm'];
$username = $_POST['un'];

//$itemEmpty = array( );
//$itemEmpty[] = $oldPassword;
//$itemEmpty[] = $newPassword;

if ($oldPassword == "" || $newPassword == "" || $newPasswordConfirm == ""){
	echo "<Center><font size='5' color='red'>Please fill in both the old and new password.</font></Center>";
	exit;
}

$query = "SELECT * FROM $monitorUserList WHERE $monitorUserListC2Name = \"".$username."\";";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (!($record = mysql_fetch_array($recordList))){
	echo "<Center><font size='5' color='red'>The user does not exist. Please try again.</font></Center>";
	exit;
}

$cryptedOldPassword = crypt($oldPassword, $record["$monitorUserListC3Name"]);
if ($cryptedOldPassword != $record["$monitorUserListC3Name"]){
	//debugOk ($cryptedOldPassword.",".$record["$monitorUserListC3Name"].brn());
	echo "<Center><font size='5' color='red'>The old password dose not match. Please try again.</font></Center>";
	exit; 
}


// check two password match
if ($newPasswordConfirm != $newPassword){
	echo "<Center><font size='5' color='red'>Two new passwords do not match.</font></Center>";
	exit;
}
if (strlen($newPassword) < 4){
	echo "<Center><font size='5' color='red'>Password length should be at least 4 characters.</font></Center>";
	exit;
}

$query = "UPDATE $monitorUserList SET $monitorUserListC3Name=\"$cryptedNewPassword\" WHERE $monitorUserListC2Name = \"".$username."\";";
//debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

$_SESSION["$monitorUserListC3Name"] = $cryptedNewPassword;

echo "<Center><font size='5'>The password is changed successfully.</font></Center>";

?>