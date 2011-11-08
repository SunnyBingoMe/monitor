<?php
session_start ();
?><?php 
if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require_once 'database_connection.php';
require_once 'sunny_function.php';
?>
<?php 

$deviceIp = $_GET[deviceIp];
$snmpVersion = $_GET[snmpVersion];
$snmpCommunity = $_GET[snmpCommunity];
$oid = $_GET[oid];
?>
<?php 
debugOk($_GET);
if (!function_exists(snmpget)){
	echo "Warning: The server do not have snmp installed.";
	brn();
	$output = shell_exec("perl php_perl_snmp.pl $deviceIp/32 $snmpCommunity $oid ");
	echo ( $output);
	exit;
}
if ($snmpVersion == '1'){
	$snmpString = snmpget($deviceIp, $snmpCommunity, $oid);
	echo $snmpString;
}elseif ($snmpVersion == '2c'){
	$snmpString = snmp2_get($deviceIp, $snmpCommunity, $oid);
	echo $snmpString;
}else {
	echo "Unknown snmp version.";
}

?>