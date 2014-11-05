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

		$json = $this->_app->_HTTPRequest->get('json');

		if($json==null)
			return false;

		if(!@array_key_exists('user', $json))
			return false;
		
		$user = new User($json['user']);
		if(array_key_exists('password', $json['user']))
			$user->setPassword(sha1($json['user']['password']));
	    $userManager = $this->getManagerof('User');

		if($userManager->exist($user->getUsername())) {				
			$userbdd = $userManager->get($user->getUsername());

			if($user->getPassword() === $userbdd->getPassword())
				return true;
		}
		return false;
	}

	public function register() {

		$json = $this->_app->_HTTPRequest->get('json');

		if($json==null) {
			$json = '{"succeed":false,"toast":"Wrong User."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		if(!@array_key_exists('user', $json)) {
			$json = '{"succeed":false,"toast":"Wrong User."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		if(!$this->_app->_config->get('registration_open')) {
			$json = '{"succeed":false,"toast":"Registration close."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}
		
		$user = new User($json['user']);
		$user->setPassword(sha1($json['user']['password']));
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