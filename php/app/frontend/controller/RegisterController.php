<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class RegisterController extends \lib\Controller{

	public function register() {		

		$user = new User($this->_app->_parameters);
		$user->setPassword(crypt($this->_app->_parameters['password']));
		$user->setId(uniqid());


	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if(!$userManager->exist($user->getUsername())){				
			$userManager->add($user);
			$json = '{"succeed":true}';
		}else{ // username
			$this->_app->_page->assign('error', true);
			$json = '{"succeed":false,"toast":"Username already exists."}';
		}

		$this->_app->_page->assign('json', $json);

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}
}