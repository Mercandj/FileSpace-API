<?php
namespace lib;

class Router{
	
	/**
	*	Find Route
	*	@param URL Client
	*	@return Object Route
	*/
	public static function get($url){

		$parsed = json_decode( file_get_contents(__DIR__.'/../config/routes.json') );

		foreach($parsed as $route){
			if( ($matches = self::match($route->{'url'},$url,$route->{'method'},$_SERVER['REQUEST_METHOD'])) ){
				return new Route($route->{'url'}, $route->{'controller'}, $route->{'action'}, $route->{'method'}, $matches);
			}
		}
		
		throw new \RuntimeException('No Route');
	}


	/**
	*	Return True if url client match with url
	*	@param url client
	*	@return boolean
	*/
	private static function match($url_json,$url_client,$method_json,$method_client){
		if ($method_json === $method_client)
			if(preg_match('`^'.$url_json.'$`', $url_client, $matches))
				return explode("/",$matches[0]);
		return false;
	}
}