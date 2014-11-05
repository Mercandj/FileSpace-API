<?php
namespace lib;

class Router {
	
	/**
	*	Find Route
	*	@param URL Client
	*	@return Object Route
	*/
	public static function get($url) {

		$parsed = json_decode( file_get_contents(__DIR__.'/../config/routes.json') );

		foreach($parsed as $route)
			if( ($matches = self::match($route->{'url'},$url,$route->{'method'},$_SERVER['REQUEST_METHOD'])) !== false )
				return new Route($route->{'url'}, $route->{'controller'}, $route->{'action'}, $route->{'method'}, $matches);
		
		throw new \RuntimeException('No Route');
	}

	/**
	*	Return True if url client match with url
	*	@param url client
	*	@return boolean
	*/
	private static function match($url_json,$url_client,$method_json,$method_client) {
		if ($method_json === $method_client) {
			//Extract variables
			preg_match_all('#:[a-z\_]+#', $url_json, $var_id);
			// Clean URL
			$url_json = preg_replace('#:[a-z\_]+#', '[0-9]{1,4}',$url_json);
			// Try to match clean URL with URL Client
			if(preg_match('`^'.$url_json.'$`', $url_client, $matches)) {
				// Match variables with URL params
				preg_match_all('#[0-9]+#',$url_client, $matches);
				$res = array();
				for($i = 0; $i<count($var_id[0]); $i++)
					$res[$var_id[0][$i]] = $matches[0][$i];
				return $res;
			}
		}
		return false;
	}
}