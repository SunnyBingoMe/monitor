<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
if ($_SESSION["$monitorUserListC2Name"] != 'root'){
	$_SESSION['loginError'] == '1';
	echo "Login failed.";
	exit;
}
?>
<?php 
$newUserType = $_POST['newUserType'];
if ($newUserType == 'admin'){
	$newUserTypeIsAdmin = 'Y';
}else {
	$newUserTypeIsAdmin = 'N';
}
$newUsername = inputText2UserName($_POST['newUsername']);
$newPassword = $_POST['newPassword'];
$cryptedPassword = crypt($newPassword, '$1$Sunny_Cr$');
$newPasswordConfirm = $_POST['newPasswordConfirm'];
$newEmailAddress = trimAnyWhere($_POST['newEmailAddress']);
$itemEmpty = array( );
$itemEmpty[] = $newUsername;
$itemEmpty[] = $newPassword;
$itemEmpty[] = $newEmailAddress;
if (itemEmpty($itemEmpty)){
	echo "Please fill in the whole tabel";
	exit;
}
// check user exists
$query = "SELECT * FROM $monitorUserList WHERE $monitorUserListC2Name = '$newUsername' OR $monitorUserListC5Name = '$newEmailAddress' ";
debugOk($query);
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (mysql_fetch_array($recordList)){
	echo "Username/Email exits.";
	exit;// Make sure that code below does not get executed when we redirect. 
}

// check password match
if ($newPasswordConfirm != $newPassword){
	echo "Two passwords do not match.";
	exit;
}

$query = "INSERT INTO $monitorUserList VALUES (NULL,'$newUsername','$cryptedPassword','$newUserTypeIsAdmin','$newEmailAddress'); ";
debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

header( 'refresh: 3; url=add_user.php' );
echo "<Center><font size='5'>User is added.</font></Center>";
exit;// Make sure that code below does not get executed when we redirect. 

?>