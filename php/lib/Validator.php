<?php
namespace lib;

class Validator{

    public static function isValidFile($var){
		$fileName = pathinfo($var['name']);
		if(isset($fileName['extension'])){
			$fileExtension = $fileName['extension'];
			$extentionSafe = array('jpg', 'jpeg', 'png', 'JPG');

			return ($var['error'] == 0 && in_array($fileExtension, $extentionSafe));	
			
		}else{
			return false;
		}	
	}
}