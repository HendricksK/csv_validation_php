<?php 

include_once(DIRNAME(__FILE__) . '../controller/class.fileOperator.php');
include_once(DIRNAME(__FILE__) . '../controller/class.filevalidation.php');

$filePath = 'hereliesafile.csv';

$fileOperator = new fileOperator();
$finalResponse = $fileOperator->runThroughCSV($filePath, 1);

echo 'Final sum of the final row ' . $finalResponse . '<br>';

$HighestResponse = $fileOperator->runThroughCSV($filePath, 1);

echo 'Bonus, highest value when the building the sum of column 2 and 3 ' . $finalResponse;