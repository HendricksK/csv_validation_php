<?php

class fileOperator {

	function __contruct() {}

	public function openFile($filePath) {
		$file = fopen($filePath, "r") or die;
		return $file;
	}

	public function readFile($file, $filePath) {
		return fread($file, filesize($filePath)) or die;
	}

	public function closeFile($file) {
		return fclose($file) or die;
	}

	public function runThroughCSV($filePath, $returnType) {

		$firsRow = $result = $trimIncorrectColumnNumber
		= $trimIncorrectAlphaNums = $trimIncorrectTimeStamps = null;

		$handle = fopen($filePath, "r");

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$result[]=$data;
    	}

    	fclose($handle);

    	if(!empty($result)) {
    		$firsRow = $result[0]; // getting the first row of data, column names
		    $result[0] = null;
		    $result = array_filter($result);

		    foreach($result as $row) {
		    	$trimIncorrectColumnNumber[] = $this->checkNumberOfColums($row, 3);
		    }

		    $trimIncorrectColumnNumber = array_filter($trimIncorrectColumnNumber);

    	} else {
    		echo 'No valid data was found in this file<br>';
    		return false;
    	}

    	echo 'valid number of rows after all rows have correct number of columns ' . count($trimIncorrectColumnNumber) . '<br>';

    	if(!empty($trimIncorrectColumnNumber)) {
    		foreach($trimIncorrectColumnNumber as $row) {
    			$trimIncorrectAlphaNums[] = $this->checkAlphaNumberic($row);
    		}

    		$trimIncorrectAlphaNums = array_filter($trimIncorrectAlphaNums);
    	} else {
    		echo 'No valid data was found in this file';
    		return false;
    	}

    	echo 'valid number of rows after validating first column ' . count($trimIncorrectAlphaNums) . '<br>' ; 

    	if(!empty($trimIncorrectAlphaNums)) {
    		foreach($trimIncorrectAlphaNums as $row) {
    			if( $this->checkValidTimeStamp($row[1]) ) {
    				$trimIncorrectTimeStamps[] = $row;
    			} else {
    				$trimIncorrectTimeStamps[] = null;
    			}
    		}
    		
    		$trimIncorrectTimeStamps = array_filter($trimIncorrectTimeStamps);
    	} else {
    		echo 'No valid data was found in this file';
    		return false;
    	}

    	echo 'valid number of rows after validating second column ' . count($trimIncorrectTimeStamps) . '<br>'; 

    	if(!empty($trimIncorrectTimeStamps) && $returnType === 1) {
    		return $this->calculateSumOfRow($trimIncorrectTimeStamps);	
    	} else if (!empty($trimIncorrectTimeStamps) && $returnType === 2){
    		return $this->calculateSumOfRow($trimIncorrectTimeStamps);
    	}


    	return false;
    	 	
	}

	protected function checkNumberOfColums($row, $allowedNumberOfRows) {
		if(count($row) === $allowedNumberOfRows) {
			return $row;
		}
		return null;
	}

	protected function checkAlphaNumberic($row) {
		// ABCD-123
		$alphanumericCheck = $row[0];
		$numbericData = substr($alphanumericCheck, strpos($alphanumericCheck, "-") + 1);
		$arrayData = explode('-', $alphanumericCheck);
		$alphaData = $arrayData[0]; 
		
		if( (ctype_alpha($alphaData) === true) && (ctype_digit($numbericData) === true) ){
		 	return $row;
		}
		return null;
	}

	protected function checkValidTimeStamp($timestamp) {
		if(is_numeric($timestamp)) {
			return true;
		}
		return false;
		
	}

	protected function calculateSumOfRow($validDataSet) {
		$sumOfFinalRow = 0;

		foreach($validDataSet as $data) {
			$sumOfFinalRow=+$sumOfFinalRow + $data[2];
		}

		return $sumOfFinalRow;
	}

	protected function calculateHighestValue($validDataSet) {
		$highestValue = 0;
		$currentValue = 0;

		foreach($validDataSet as $data) {
			$currentValue = $data[1] + $data[2]; 
			if($highestValue < $currentValue) {
				$highestValue = $currentValue;
			}
		}

		return $currentValue;
	}
}