<?php
namespace lib;
use \lib\Models\ConfigManager;

abstract class Application {
	public $_config,
		$_pdo,
		$_router;

	public function __construct() {
		$this->_config =  ConfigManager::getInstance();
		$this->_pdo = (new Connexion($this))->getPDO();	
		$this->_router = new Router($this);
	}

	abstract public function run();
}