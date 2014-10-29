<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class LoginController extends \lib\Controller{

	public function login() {
		
		$user = new User($this->_app->_parameters);
		$user->setPassword(sha1($this->_app->_parameters['password']));

	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if($userManager->exist($user->getUsername())) {				
			$userbdd = $userManager->get($user->getUsername());

			if($user->getPassword() === $userbdd->getPassword())
				$json = '{"succeed":true,"token":""}';
			else {
				$this->_app->_page->assign('error', true);
				$json = '{"succeed":false,"toast":"Wrong Login."}';
			}

		}
		else {
			$this->_app->_page->assign('error', true);
			$json = '{"succeed":false,"toast":"Wrong Login."}';
		}

		$this->_app->_page->assign('json', $json);
		
		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}
}