<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class UserController extends \lib\Controller {

	public function login() {

		if($this->isUser())
			$json = '{"succeed":true,"token":""}';
		else
			$json = '{"succeed":false,"toast":"Wrong User."}';

		$this->_app->_page->assign('json', $json);
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}

	public function isUser() {

		if(!@array_key_exists('user', $this->_app->_parameters))
			return false;
		
		$user = new User($this->_app->_parameters['user']);
		if(array_key_exists('password', $this->_app->_parameters['user']))
			$user->setPassword(sha1($this->_app->_parameters['user']['password']));
	    $userManager = $this->getManagerof('User');

		if($userManager->exist($user->getUsername())) {				
			$userbdd = $userManager->get($user->getUsername());

			if($user->getPassword() === $userbdd->getPassword())
				return true;
		}
		return false;
	}

	public function register() {

		if(!$this->_app->_config['registration_open']) {
			$json = '{"succeed":false,"toast":"Redistration close."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}


		if(!@array_key_exists('user', $this->_app->_parameters)) {
			$json = '{"succeed":false,"toast":"Wrong User."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}
		
		$user = new User($this->_app->_parameters['user']);
		$user->setPassword(sha1($this->_app->_parameters['user']['password']));
		$user->setId(uniqid());
		$userManager = $this->getManagerof('User');
		// Check if User exist
		if(!$userManager->exist($user->getUsername())) {
			$userManager->add($user);
			$json = '{"succeed":true}';
		}
		else {
			$this->_app->_page->assign('error', true);
			$json = '{"succeed":false,"toast":"Username already exists."}';
		}
		$this->_app->_page->assign('json', $json);
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}
}