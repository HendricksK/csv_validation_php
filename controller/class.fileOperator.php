<?php

class fileOperator {

	function __contruct() {}
	
	/**
	* returns different values based on flag,
	* either sum of all values in 3rd column
	* or largest value when suming 2nd and 3rd column
	*/
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

		    $trimIncorrectColumnNumber = array_filter($trimIncorrectColumnNumber); //removing null values

    	} else {
    		echo 'No valid data was found in this file<br>';
    		return false;
    	}

    	echo 'valid number of rows after all rows have correct number of columns ' . count($trimIncorrectColumnNumber) . '<br>';

    	if(!empty($trimIncorrectColumnNumber)) {
    		foreach($trimIncorrectColumnNumber as $row) {
    			$trimIncorrectAlphaNums[] = $this->checkAlphaNumberic($row);
    		}

    		$trimIncorrectAlphaNums = array_filter($trimIncorrectAlphaNums); //removing null values
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
    		
    		$trimIncorrectTimeStamps = array_filter($trimIncorrectTimeStamps); //removing null values
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

    	return false; //returns false if failure occurs
    	 	
	}

	/**
	* validates number of coulms in CSV file
	* 2 argurments, row of data and allowed number of columns
	*/
	protected function checkNumberOfColums($row, $allowedNumberOfColumns) {
		if(count($row) === $allowedNumberOfColumns) {
			return $row;
		}
		return null;
	}

	/**
	* checks whether given data set, 
	* has number, and alphabetical
	*/
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

	/**
	* validates timestamp
	*/
	protected function checkValidTimeStamp($timestamp) {
		if(is_numeric($timestamp)) {
			return true;
		}
		return false;
		
	}

	/**
	* returns sum or rows, last column
	*/
	protected function calculateSumOfRow($validDataSet) {
		$sumOfFinalRow = 0;

		foreach($validDataSet as $data) {
			$sumOfFinalRow=+$sumOfFinalRow + $data[2];
		}

		return $sumOfFinalRow;
	}

	/**
	* returns highest value of sum of columns 2 and 3
	*/
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