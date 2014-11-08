<?php
namespace lib;
use \lib\Models\ConfigManager;

abstract class Application {
	public $_config,
		$_pdo;

	public function __construct() {
		$this->_config =  ConfigManager::getInstance();
		$this->_pdo = (new Connexion($this))->getPDO();	
	}

	/**
	 * Match URL client with URL in JSON file and launch the controller method associated
	 */
	public function exec() {
		try {
			$route = Router::get($_SERVER['REQUEST_URI'], $this->_config->get('root'));
		}
		catch(\RuntimeException $e) {
			HTTPResponse::redirect404();
		}
		
		$controleurPath = '\app\controller\\'.$route->getController().'Controller';
		$controller = new $controleurPath($this);
		$action = $route->getAction();
		
		if(method_exists($controller, $action)){
			call_user_func_array(array($controller, $action), $route->getMatches());
		}else{
			HTTPResponse::redirect404();
		}
		
	}

	abstract public function run();
}