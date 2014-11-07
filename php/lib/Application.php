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

	abstract public function run();

	public function getController() {
		try {
			$route = Router::get($_SERVER['REQUEST_URI'], $this->_config->get('root'));
		}
		catch(\RuntimeException $e) {
			HTTPResponse::redirect404();
		}
		
		$controleurPath = '\app\controller\\'.$route->getController().'Controller';
		return new $controleurPath($this,$route->getAction(), $route->getMatches());
	}
}