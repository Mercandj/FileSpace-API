<?php
namespace lib;
use \lib\Models\ConfigManager;

abstract class Application {
	public $_config,
		$_pdo,
		$_page;

	public function __construct() {
		$this->_page = new Page($this);
		$this->_config =  ConfigManager::getInstance();
		$connexion = new Connexion($this);	
		$this->_pdo = $connexion->getPDO();	
	}

	abstract public function run();

	public function getName() {
		return $this->_name;
	}

	public function getController() {
		try {
			$route = Router::get($_SERVER['REQUEST_URI']);
		}
		catch(\RuntimeException $e) {
			$this->_HTTPResponse->redirect404();
		}
		
		$controleurPath = '\app\\'.$this->_name.'\\controller\\'.$route->getController().'Controller';
		return new $controleurPath($this,$route->getAction(), $route->getMatches());
	}
}