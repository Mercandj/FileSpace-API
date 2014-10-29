<?php
namespace app\frontend;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		$controlleur = new \app\frontend\controller\LoginController($this);
		
		if($controlleur->isUser() || $this->getController() instanceof \app\frontend\controller\RegisterController)
			$this->getController()->exec();
		
		exit();
	}
}