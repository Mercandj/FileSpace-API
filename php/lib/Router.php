<?php
namespace lib;

class Router {
	
	/**
	*	Find Route
	*	@param URL Client
	*	@return Object Route
	*/
	public static function get($url, $local_root) {
		$parsed = json_decode( file_get_contents(__DIR__.'/../config/routes.json') );

		foreach($parsed as $route){
			if( ($match = self::match($local_root.$route->{'url'},$url,$route->{'method'},$_SERVER['REQUEST_METHOD'])) !== false ){
				return new Route($local_root.$route->{'url'}, $route->{'controller'}, $route->{'action'}, $route->{'method'}, $match);
			}
		}
		
		throw new \RuntimeException('No Route');
	}

	/**
	*	Return True if url client match with url
	*	@param url client
	*	@return boolean
	*/
	private static function match($url_json,$url_client,$method_json,$method_client) {
		if ($method_json === $method_client) {

			// Clean URL
			$url_json = preg_replace('#:[a-z_]+#','[0-9]+',$url_json);

			// Try to match clean URL with URL Client
			if(preg_match('`^'.$url_json.'$`', $url_client, $matches)){

				// Match variables with URL params
				preg_match('#[0-9]+#',$url_client, $match);
				return $match;
			}
		}
		return false;
	}
}