<?php
namespace lib;

class HTTPResponse{
	private $_app;

	public function __construct(Application $app){
		$this->_app = $app;
	}

	public function send($page){
		exit($page);
	}

	public function redirect404(){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		include_once("app/404.html");
		exit();
	}

	public function redirect403(){
		header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
		include_once("app/403.html");
		exit();
	}

	public function redirect($location){
		header('Location: '.$location);
		exit();
	}
}