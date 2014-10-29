<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class LoginController extends \lib\Controller{

	public function login() {

		if($this->isUser())
			$json = '{"succeed":true,"token":""}';
		else
			$json = '{"succeed":false,"toast":"LoginController : Wrong Login."}';


		$this->_app->_page->assign('json', $json);
		
		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}

	public function isUser() {
		
		$user = new User($this->_app->_parameters);
		if(array_key_exists('password', $this->_app->_parameters))
			$user->setPassword(sha1($this->_app->_parameters['password']));
	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if($userManager->exist($user->getUsername())) {				
			$userbdd = $userManager->get($user->getUsername());

			if($user->getPassword() === $userbdd->getPassword())
				return true;
		}
		return false;
	}
}