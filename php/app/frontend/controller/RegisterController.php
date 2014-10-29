<?php
namespace app\frontend\controller;
use \lib\Entities\User;

class RegisterController extends \lib\Controller{

	public function register() {		

		User $user = new User(NULL);

		if(isset($_GET['username']))
			$user->setUsername($_GET['username']);
		if(isset($_GET['password']))
			$user->setPassword($_GET['password']);

		$request_body = file_get_contents('php://input');
		$phpArray = json_decode($request_body, true);
		if($phpArray!=null) {
			foreach ($phpArray as $key => $value) {
			    if($key=="user") {
				    foreach ($value as $k => $v) {
				    	if($k=="username")
				    		$user->setUsername($v);
				    	else if($k=="password")
				    		$user->setPassword($v);
				    }
				}
			}
		}


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


		$this->_app->_page->assign('register', 'salut');

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('RegisterView.php'));
	}
}