<?php 
session_start();
?><?php 
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
?>
<?php 
foreach ($_POST['checkbox'] as $tForeach){ //$tForeach is id
	$query = "DELETE FROM $monitorAttachedServer WHERE $monitorAttachedServerC1Name='$tForeach' ";
	debugOk($query);
	mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());	
}

header("Location: attached_servers_mgmt_one_device_detail.php?ip=$_POST[deviceIpInDetails]"); 
exit;

?>