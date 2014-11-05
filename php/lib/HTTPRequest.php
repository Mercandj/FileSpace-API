<?php
namespace lib;

class HTTPRequest{
	private $_app;

	public function __construct(Application $app){
		$this->_app = $app;
	}

	private function postData($key){
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	private function postExist($key){
		return isset($_POST[$key]);
	}

	private function getData($key){
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	private function getExist($key){
		return isset($_GET[$key]);
	}

	public function exist($key) {
		switch($_SERVER['REQUEST_METHOD']) {		
		case 'GET': 
		if(getExist($key))
			return true;
		break;
		case 'POST': 
		if(postExist($key))
			return true;
		break;
		}
		return false;
	}

	public function get($key) {
		switch($_SERVER['REQUEST_METHOD']) {		
		case 'GET':
		if(getExist($key))
			return getData($key);
		break;
		case 'POST': 
		if(postExist($key))
			return json_decode(postData($key), true);
		break;
		}
		return null;
	}
}