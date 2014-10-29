<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class RegisterController extends \lib\Controller{

	public function register() {		

		
		$json = '{"result","error"}';

		/*
		$user = new User($value);
	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if(!$userManager->exist($username)){				
			$userManager->add($user);
		}else{ // username
			$this->_app->_page->assign('error', true);
		}
		*/

		
		//{"user":{"username"="toto","password"="tata"}}
		/*
		//Check if password and username were submitted
		if( !empty($user->getUsername()) && !empty($user->getPassword())){

			$userManager = $this->getManagerof('User');

			// Check if User exist
			if(!$userManager->exist($username){				
				$userManager->add($user);
				

			}else{ // username
				$this->_app->_page->assign('error', true);
			}
		}else{
			$this->_app->_page->assign('error', true);
		}
		*/

		$user = new User($value);
	    $userManager = $this->getManagerof('User');

		// Check if User exist
		if(!$userManager->exist($username)){				
			$userManager->add($user);
			$json = '{"result","no error"}';
		}else{ // username
			$this->_app->_page->assign('error', true);
		}



		$this->_app->_page->assign('json', $json);

		$this->_app->_page->assign('parameters', $this->_app->_parameters);



		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('RegisterView.php'));
	}
}