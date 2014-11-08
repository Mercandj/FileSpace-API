<?php
namespace lib;

class Router extends ApplicationComponent{

	public function __construct(Application $app){
		parent::__construct($app);
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


	private function match($url,$method, $controller_action) {
		if ($method === $_SERVER['REQUEST_METHOD']) {
			$url = $this->_app->_config->get('root').$url;

			// Clean URL
			$url = preg_replace('#:[a-z_]+#','[0-9]+',$url);

			// Try to match clean URL with URL Client
			if(preg_match('`^'.$url.'$`', $_SERVER['REQUEST_URI'])){

				// Match variables with URL params
				preg_match('#[0-9]+#',$_SERVER['REQUEST_URI'], $match);

				$controller_action = explode('#',$controller_action);

				$controleurPath = '\app\controller\\'.$controller_action[0].'Controller';
				$controller = new $controleurPath($this->_app);

				if(method_exists($controller, $controller_action[1])){
					call_user_func_array(array($controller, $controller_action[1]), $match);
					exit();
				}else{
					return $this;
				}
			}
		}
		return $this;
	}
}