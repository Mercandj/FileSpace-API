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

			if(count($route->getMatches()) != 0){
				$controller->$action($route->getMatches()[0]);
			}else{
				$controller->$action();
			}

		}else{
			HTTPResponse::redirect404();
		}
		
	}

	abstract public function run();
}