<?php 
if (isset($getDatabaseConnectionFilePath)){
	$databaseConnectionFilePath = __FILE__;
}else{

$databaseHostName = 'localhost';//hellosweden.db.6009281.hostedresource.com //194.47.155.200
$databaseName = "PG01";
$userName = 'root';//"pg01";
$passWord = 'toonnalux';//"pg01abc";


$session = mysql_connect($databaseHostName,$userName,$passWord) or die("ERR: mysql_connect():".mysql_error());

$query = "USE $databaseName ";
mysql_query($query,$session) or die("ERR: db_connect USE: ".mysql_error());


// names

$monitorConfig = "monitorConfig";
$monitorConfigC1Name = "probeInterval";
$monitorConfigC2Name = "perlPid";
$monitorConfigC3Name = "dataLastHour";
$monitorConfigC4Name = "enableRoot";
$monitorConfigC5Name = "errorLimit";
$monitorConfigC6Name = "dataLastSample";
$monitorConfigC7Name = "numberOfSample";
$monitorConfigC8Name = "numberOfHour";
$monitorConfigC9Name = "numberOfError";
$monitorConfigC10Name = "stop";

$monitorUserList = "monitorUserList";
$monitorUserListC1Name = "id";
$monitorUserListC2Name = "username";
$monitorUserListC3Name = "password";
$monitorUserListC4Name = "isAdmin";
$monitorUserListC5Name = "emailAddress";

$monitorDeviceList = "monitorDeviceList";
$monitorDeviceListC1Name = "id";
$monitorDeviceListC2Name = "ip";
$monitorDeviceListC3Name = "name";
$monitorDeviceListC4Name = "snmpVersion";
$monitorDeviceListC5Name = "community";
$monitorDeviceListC6Name = "emailAddress";
$monitorDeviceListC7Name = "numberOfOid";
$monitorDeviceListC8Name = "numberOfStatisticOid";
$monitorDeviceListC9Name = "emailSent";
$monitorDeviceListC10Name = "statusError";

$monitorAttachedServer = "monitorAttachedServer";
$monitorAttachedServerC1Name = "id";
$monitorAttachedServerC2Name = "ip";
$monitorAttachedServerC3Name = "serverIp";
$monitorAttachedServerC4Name = "serverName";
$monitorAttachedServerC5Name = "port";
$monitorAttachedServerC6Name = "secretMessage";

$monitorDeviceAndOid = "monitorDeviceAndOid";
$monitorDeviceAndOidC1Name = "id";
$monitorDeviceAndOidC2Name = "ip";
$monitorDeviceAndOidC3Name = "oid";
$monitorDeviceAndOidC4Name = "name";
$monitorDeviceAndOidC5Name = "needStatisticAndThreshold";
$monitorDeviceAndOidC6Name = "errorStatus";

$monitorOidNameList = "monitorOidNameList";
$monitorOidNameListC1Name = "id";
$monitorOidNameListC2Name = "name";
$monitorOidNameListC3Name = "emailAddress";
$monitorOidNameListC4Name = "needStatisticAndThreshold";

$monitorThreshold = "monitorThreshold";
$monitorThresholdC1Name = "id";
$monitorThresholdC2Name = "ip";
$monitorThresholdC3Name = "oid";
$monitorThresholdC4Name = "threshold1";
$monitorThresholdC5Name = "threshold2";

$monitorSample = "monitorSample";
$monitorSampleC1Name = "id";
$monitorSampleC2Name = "timeStamp";
$monitorSampleC3Name = "ip";
$monitorSampleCOidName = "oid";

$monitorHourLog = "monitorHourLog";
$monitorHourLogC1Name = "id";
$monitorHourLogC2Name = "timeStamp";
$monitorHourLogC3Name = "ip";
$monitorHourLogCStatisticOidName = "statisticOid";


$monitorErrorLog = "monitorErrorLog";
$monitorErrorLogC1Name = "id";
$monitorErrorLogC2Name = "timeStamp";
$monitorErrorLogC3Name = "ip";
$monitorErrorLogC4Name = "description";

}
?>