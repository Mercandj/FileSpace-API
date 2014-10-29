<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class RegisterController extends \lib\Controller{

	public function register() {		

		
		$json = '{"result","error"}';

		$request_body = file_get_contents('php://input');
		$phpArray = json_decode($request_body, true);
		if($phpArray!=null) {
			foreach ($phpArray as $key => $value) {
			    if($key=="user") {
			    	/*
				    foreach ($value as $k => $v) {
				    	if($k=="username")
				    		$user->setUsername($v);
				    	else if($k=="password")
				    		$user->setPassword($v);
				    }
					*/

				    $json = $value;

					$user = new User($value);

				    $userManager = $this->getManagerof('User');

					// Check if User exist
					if(!$userManager->exist($username)){				
						$userManager->add($user);
					}else{ // username
						$this->_app->_page->assign('error', true);
					}
				}
			}
		}

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

		$this->_app->_page->assign('json', $json);

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('RegisterView.php'));
	}
}