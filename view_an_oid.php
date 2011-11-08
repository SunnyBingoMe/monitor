<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
//if( stristr($_SERVER['HTTP_ACCEPT_LANGUAGE'],'zh')!=FALSE )
echo '<script src="http://sunnyboy.me/personal/ua.js" type="text/javascript"></script>';
?>
	<script src="http://sunnyboy.me/personal/ga.js" type="text/javascript"></script>

</head>
<body><center>

<form action="graph_view_ing.php">
<?php 
brn(5);

echo "IP:";
//input_text("deviceIpInDetails");
echo "<input type=\"text\" name=\"deviceIpInDetails\" maxlength=\"40\" /> ";
brn(2);

$query = "SELECT * FROM $monitorOidNameList ORDER BY $monitorOidNameListC2Name ";
$recordList = mysql_query ( $query, $session ) or die ( "ERR: <b>$query</b>: " . mysql_error () );
while ( $record = mysql_fetch_array ( $recordList ) ) {
	debugOk($record);
	$aOptionLabelPairList_Of_OidName[$record[$monitorOidNameListC2Name]] = $record[$monitorOidNameListC2Name];
}
echo "OID Name:";
input_select("oidName", NULL, $aOptionLabelPairList_Of_OidName); 
brn(2);

$aOptionLabelPairList_Of_ViewType = array("hour"=>"Quarter-hourly","day"=>"Daily","week"=>"Weekly","month"=>"Monthly");
echo "View Type:";
input_select("viewType", NULL, $aOptionLabelPairList_Of_ViewType); 
brn(2);

$tTimeEnd = time();
input_hidden("tTimeEnd", array("tTimeEnd"=>$tTimeEnd));
brn(2);

input_submit();
?>
</form>


</center></body>
</html>
