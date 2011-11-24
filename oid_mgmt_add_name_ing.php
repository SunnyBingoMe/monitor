<?php 
session_start();
if (!isset($_SESSION['isAdmin'])){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
if ($_SESSION['isAdmin'] != 'Y'){
	$_SESSION['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit;
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<?php 
$newName = inputText2VariableName($_POST['newName']);
$itemEmpty = array( );
$itemEmpty[] = $newName;
if (itemEmpty($itemEmpty)){
	echo "Please fill in the whole tabel";
	exit;
}
if (isset($_POST["isStatistic"])){
	$isStatistic = 'Y';
}else {
	$isStatistic = 'N';
}

// check oid name exists
$query = "SELECT * FROM $monitorOidNameList WHERE $monitorOidNameListC2Name = '$newName'";
debugOk($query);
$recordList = mysql_query($query,$session) or die("ERR: <b>$query</b>: ".mysql_error());
if (mysql_fetch_array($recordList)){
	echo "Oid name exits.";
	exit;// Make sure that code below does not get executed when we redirect. 
}

$query = "INSERT INTO $monitorOidNameList VALUES (NULL, '$newName', '".$_SESSION["$monitorOidNameListC3Name"]."', '$isStatistic' ); ";
debugOk($query);
mysql_query($query,$session) or die("ERR: <b>$query</b> : ".mysql_error());

header("Location: oid_mgmt.php"); /// Redirect browser 
exit;// Make sure that code below does not get executed when we redirect. 

?>