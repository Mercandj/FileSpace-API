<?php
namespace app;
use \app\controller\UserController;

class RESTapi extends \lib\Application {

	public function run() {
		
		if((new UserController($this))->isUser() || $this->getController() instanceof UserController){
			$this->exec();
		}else {
			\lib\HTTPResponse::redirect401();
		}
		
		exit();
	}
}