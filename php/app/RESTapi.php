<?php
namespace app;
use \app\controller\UserController;

class RESTapi extends \lib\Application {

	public function __construct() {
		parent::__construct();
	}

	public function run() {
		$controlleur = new UserController($this);
		
		if($controlleur->isUser() || $this->getController() instanceof UserController)
			$this->getController()->exec();
		else {
			\lib\HTTPResponse::redirect401();
		}
		
		exit();
	}
}