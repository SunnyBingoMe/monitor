<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$cryptedNewPassword = crypt($newPassword, '$1$Sunny_Cr$');
$newPasswordConfirm = $_POST['newPasswordConfirm'];
$itemEmpty = array( );
$itemEmpty[] = $oldPassword;
$itemEmpty[] = $newPassword;

if (itemEmpty($itemEmpty)){
	echo "Please fill in the whole tabel";
	exit;
}
$query = "SELECT * FROM $monitorUserList WHERE $monitorUserListC2Name = \"".$_SESSION["$monitorUserListC2Name"]."\";";
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (!($record = mysql_fetch_array($recordList))){
	echo "Login failed.";
	exit;
}
$cryptedOldPassword = crypt($oldPassword, $record["$monitorUserListC3Name"]);
if ($cryptedOldPassword != $record["$monitorUserListC3Name"]){
	debugOk ($cryptedOldPassword.",".$record["$monitorUserListC3Name"].brn());
	echo "Login failed.";
	exit; 
}

// check two password match
if ($newPasswordConfirm != $newPassword){
	echo "Two passwords do not match.";
	exit;
}
if (strlen($newPassword) < 4){
	echo "Password length should at least 4 characters.";
	exit;
}

$query = "UPDATE $monitorUserList SET $monitorUserListC3Name=\"$cryptedNewPassword\" WHERE $monitorUserListC2Name = \"".$_SESSION["$monitorUserListC2Name"]."\";";
debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

$_SESSION["$monitorUserListC3Name"] = $cryptedNewPassword;

echo "New password has come into effect."

?>