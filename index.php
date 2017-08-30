<?php 

include_once(DIRNAME(__FILE__) . '../controller/class.fileOperator.php');
include_once(DIRNAME(__FILE__) . '../controller/class.filevalidation.php');

$filePath = 'hereliesafile.csv';

$fileOperator = new fileOperator();
$csvFile = $fileOperator->openFile($filePath);

var_dump($csvFile);
var_dump($fileOperator->readFile($csvFile, $filePath));

