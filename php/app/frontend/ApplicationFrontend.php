<?php
namespace app\frontend;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		$controlleur = new \app\frontend\controller\UserController($this);
		
		if($controlleur->isUser() || $this->getController() instanceof \app\frontend\controller\UserController)
			$this->getController()->exec();
		else {
			\lib\HTTPResponse::redirect401();
		}
		
		exit();
	}
}