<?php
namespace lib;

class HTTPRequest{
	private $_app;

	public function __construct(Application $app){
		$this->_app = $app;
	}

	public function postData($key){
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	public function postExist($key){
		return isset($_POST[$key]);
	}

	public function getData($key){
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	public function getExist($key){
		return isset($_GET[$key]);
	}
}