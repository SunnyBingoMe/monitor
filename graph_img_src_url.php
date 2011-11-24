<?php
session_start ();
?><?php

if (! isset ( $_SESSION ['isAdmin'] )) {
	$_SESSION ['loginError'] == '1';
	header( 'refresh: 2; url=index.php' );
	echo "Login failed.";
	exit ();
}
require 'database_connection.php';
require 'sunny_function.php';
?><?php
$deviceIp = $_GET['deviceIp'];
$oidColumnName = $_GET['oidColumnName'];
?><?php
// need parameters: $threshold1/2 , type; start, end; table, ip, column
$data = $_SESSION['graphData'][$deviceIp][$oidColumnName]['data'];

if (isset($_SESSION['graphData'][$deviceIp][$oidColumnName]['threshold1'])){
	$threshold1 = $_SESSION['graphData'][$deviceIp][$oidColumnName]['threshold1'];
}
if (isset($_SESSION[graphData][$deviceIp][$oidColumnName]['threshold2'])){
	$threshold2 = $_SESSION[graphData][$deviceIp][$oidColumnName]['threshold2'];
}

$xMarginPercent = $_SESSION[graphData][$deviceIp][$oidColumnName]['xMarginPercent'];
$yMarginPercent = $_SESSION[graphData][$deviceIp][$oidColumnName]['yMarginPercent'];

$graphWidth = 1000;
$graphHeight = 300;

/////////////////////////////////////////////////////////////////////////////////////////////


$valueMax = max ( $data );
$valueMin = min ( $data );
if (isset($threshold1)){
	$valueMax = max(array($valueMax,$threshold1));
	$valueMin = min(array($valueMin,$threshold1));
}
if (isset($threshold2)){
	$valueMax = max(array($valueMax,$threshold2));
	$valueMin = min(array($valueMin,$threshold2));
}
$valueRange = $valueMax - $valueMin;
$rangeToSetMargin = max($valueRange, $valueMax * 0.02);
if (abs($rangeToSetMargin) < 1){
	$rangeToSetMargin = 1;
}
$rangeMax = $valueMax + $rangeToSetMargin * 0.1;
$rangeMin = $valueMin - $rangeToSetMargin * 0.1;
if (abs($rangeMax) < 1){
	$rangeMax = 1;
}
if (abs($rangeMin) < 1){
	$rangeMin = -1;
}
////////////////////////////////////////////////////////////////////////////////////////////////
include ('phpgraphlib.php');
$graph = new PHPGraphLib ( $graphWidth, $graphHeight );
$graph->setRange ( $rangeMax, $rangeMin );
$graph->setupXAxis ( $xMarginPercent );
$graph->setupYAxis ( $yMarginPercent );
$graph->addData ( $data );
//$graph->setTitle('PPM Per Container');

$graph->setBars ( FALSE );
$graph->setLine ( TRUE );
$graph->setLineColor ( "silver" );

$graph->setDataPoints ( TRUE );
$graph->setDataPointColor ( 'blue' );

$graph->setDataValues ( FALSE );
$graph->setDataFormat ( "comma" );
$graph->setDataValueColor ( "silver" );

$graph->setGrid ( TRUE );
$graph->setGridColor ( "240,240,240" );

if ($dbugOk){
	$graph->setGoalLine ( $valueMax );
	$graph->setGoalLine ( $valueMin );
}

if (isset($threshold1)){
	$graph->setGoalLine ( $threshold1 );
}
if (isset($threshold2)){
	$graph->setGoalLine ( $threshold2 );
}
$graph->setGoalLineColor ( 'fuscia' );

$graph->createGraph ();

unset($_SESSION['graphData'][$deviceIp][$oidColumnName]);

?>