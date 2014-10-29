<?php
namespace app\frontend;
use \lib\Entities\User;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		$user = new User($this->_parameters);
		$user->setPassword(sha1($this->_parameters['password']));
	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if($userManager->exist($user->getUsername())) {				
			$userbdd = $userManager->get($user->getUsername());

			if($user->getPassword() === $userbdd->getPassword()) {
				$this->getController()->exec();
			}
			else {
				$controlleur = new \app\frontend\controller\RegisterController($this);
				$controlleur->exec();
			}
		}
		else {
			$controlleur = new \app\frontend\controller\RegisterController($this);
			$controlleur->exec();
		}


		/*
		if(!$this->_session->isLogin()){
			$controlleur = new \app\frontend\controller\UserController($this);
			$controlleur->login();

		}else{
		*/
			//$this->_page->assign('header',$this->_config->get("title"));				
		//$this->getController()->exec();
		//}
		exit();
	}
}