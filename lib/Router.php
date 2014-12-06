<?php
namespace lib;

class Router extends ApplicationComponent{

	public function __construct(Application $app){
		parent::__construct($app);
	}

	public function authorize($boolean){
		return $boolean ? $this : HTTPResponse::redirect401();
	}
	
	public function get($url, $controller_action){
		return $this->match($url,'GET',$controller_action);
	}

	public function post($url, $controller_action){
		return $this->match($url,'POST',$controller_action);
	}

	public function put($url, $controller_action){
		return $this->match($url,'PUT',$controller_action);
	}	

	public function delete($url, $controller_action){
		return $this->match($url,'DELETE',$controller_action);
	}	

	public function otherwise(\Closure $closure){
		$closure();
	}


	private function match($url,$method, $controller_action) {
		if ($method === HTTPRequest::serverData('REQUEST_METHOD') ){
			$url = $this->_app->_config->get('root').$url;

			// Clean URL
			$url = preg_replace('#:[a-z_]+#','[0-9]+',$url);

			$request_uri = strtok(HTTPRequest::serverData('REQUEST_URI'), '?');

			// Try to match clean URL with URL Client
			if(preg_match('`^'.$url.'$`', $request_uri )){

				// Match variables with URL params
				preg_match('#[0-9]+#', $request_uri, $match);

				$controller_action = explode('#',$controller_action);

				$controleurPath = '\app\controller\\'.$controller_action[0].'Controller';
				$controller = new $controleurPath($this->_app);

				if(method_exists($controller, $controller_action[1])){
					call_user_func_array(array($controller, $controller_action[1]), $match);
					exit();
				}
			}
		}
		return $this;
	}
}