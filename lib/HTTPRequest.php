<?php
namespace lib;

class HTTPRequest {

	public static function getData($key) {
		return self::getExist($key) ? $_GET[$key] : null;
	}

	public static function getExist($key) {
		return isset($_GET[$key]) && !empty($_GET[$key]);
	}


	public static function postData($key) {
		return self::postExist($key) ? $_POST[$key] : null;
	}

	public static function postExist($key) {
		return isset($_POST[$key]) && !empty($_POST[$key]);
	}


	public static function serverData($key){
		return self::serverExist($key) ? $_SERVER[$key] : null;
	}

	public static function serverExist($key){
		return isset($_SERVER[$key]) && !empty($_SERVER[$key]);
	}


	public static function fileData($key){
		return isset($_FILES[$key]) ? $_FILES[$key] : null;
	}

	public static function fileExist($key){
		return isset($_FILES[$key]);
	}

	public static function defaultData($key) {
		$array = array();
		parse_str(file_get_contents('php://input'), $array);
		return isset($array[$key]) ? $array[$key] : null;
	}

	public static function defaultExist($key) {
		$array = array();
		parse_str(file_get_contents('php://input'), $array);
		return isset($array[$key]);
	}
	
	public static function exist($key) {
		switch($_SERVER['REQUEST_METHOD']) {		
			case 'GET': 
				if(self::getExist($key)){
					return true;
				}
			break;

			case 'POST': 
				if(self::postExist($key)){
					return true;
				}
			break;

			default:
				if(self::defaultExist($key)){
					return true;
				}
		    break;
		}
		return false;
	}

	public static function get($key) {
		switch($_SERVER['REQUEST_METHOD']) {		
			case 'GET':
				if(self::getExist($key)){
					return self::getData($key);
				}
			break;

			case 'POST': 
				if(self::postExist($key)){
					return self::postData($key);
				}
			break;

			default:
				if(self::defaultExist($key)){
					return self::defaultData($key);
				}
			break;
		}
		return null;
	}
}