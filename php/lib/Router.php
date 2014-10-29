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

			echo $route->{'url'}.' '.$url.'<br/>';

			if( ($matches = self::match($route->{'url'},$url)) ){
				return new Route($route->{'url'}, $route->{'controller'}, $route->{'action'}, $matches);
			}
		}
		
		throw new \RuntimeException('No Route');
	}


	/**
	*	Return True if url client match with url
	*	@param url client
	*	@return boolean
	*/
	private static function match($url_json,$url_client){
		if(preg_match('`^'.$url_json.'$`', $url_client, $matches)){
			return explode("/",$matches[0]);
		}else{
			return false;
		}
	}
}