<?php
//echo phpinfo();
?>
<?php

include('phpgraphlib.php');
$graph = new PHPGraphLib(640,300);
$data = array("11111111111111" => .00322222222, "2222222222222222222" => .0028, "3" => .0021, "4" => .0033, "5" => .0034, "6" => .0031, "7" => .0036, "8" => .0027, "9" => .0024, "10" => .0021, "11" => .0026, "12" => .0024, "13" => .0036, "14" => .0028, "15" => .0025);
$graph->addData($data);
$graph->setTitle('PPM Per Container');
$graph->setBars(false);
$graph->setLine(true);
$graph->setDataPoints(true);
$graph->setDataPointColor('blue');
$graph->setDataValues(false);
$graph->setDataValueColor('maroon');
$graph->setGoalLine(.0025);
$graph->setGoalLine(.0035);
$graph->setGoalLineColor('red');
$graph->createGraph();

?>
