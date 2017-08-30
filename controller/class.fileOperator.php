<?php

class fileOperator {

	function __contruct() {}

	public function openFile($filePath) {
		$file = fopen($filePath, 'r') or die;
		return $file;
	}

	public function readFile($file, $filePath) {
		return fread($file, filesize($file)) or die;
	}

	public function cloaseFile($file) {
		return fclose($file) or die;
	}
}