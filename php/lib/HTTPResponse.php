<?php
namespace lib;

class HTTPResponse{

	public static function send($page){
		exit($page);
	}

	public static function redirect404(){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		exit();
	}

	public static function redirect403(){
		header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
		exit();
	}

	public static function redirect401(){
		header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized");
		exit();
	}

	public static function redirect($location){
		header('Location: '.$location);
		exit();
	}
}