<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class UserController extends \lib\Controller {

	/**
	*	POST user/register
	*/
	public function login() {

		if($this->isUser()){
			$info_user = json_encode($this->getManagerof('User')->get(HTTPRequest::serverData('PHP_AUTH_USER'))->toArray());
			$json = '{"succeed":true,"token":"","user":'.$info_user.'}';
		}else{
			$json = '{"succeed":false,"toast":"Wrong User."}';
		}

		HTTPResponse::send($json);
	}

	/**
	*	Used by $this->login() and Applicationfrontend
	*/
	public function isUser() {

		if( !HTTPRequest::serverExist('PHP_AUTH_USER') && !HTTPRequest::serverExist('PHP_AUTH_PW')){
			return false;

		}else{

			$user = new User(array(
				'username' => HTTPRequest::serverData('PHP_AUTH_USER'),
				'password' => sha1(HTTPRequest::serverData('PHP_AUTH_PW')),
				'date_last_connection' => date('Y-m-d H:i:s')
			));

			$userManager = $this->getManagerof('User');

			if($userManager->exist($user->getUsername())) {	

				$userbdd = $userManager->get($user->getUsername());

				if($user->getPassword() === $userbdd->getPassword()){
					$userManager->updateConnection($user);
					return true;
				}

			}else{
				return false;
			}
		}

		return false;
	}

	/**
	 * Register a new user
	 * @url    /user/register
	 * @method POST
	 * @param  username   REQUIRED
	 * @param  password   REQUIRED
	 * @param  last_name  OPTIONAL
	 * @param  first_name OPTIONAL
	 * @param  email      OPTIONAL
	 */
	public function register() {

		if(!HTTPRequest::postExist('username')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong User."}');
			exit();
		}

		else if(!HTTPRequest::postExist('password')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong Password."}');
			exit();
		}

		else if(!$this->_app->_config->get('registration_open')) {
			HTTPResponse::send('{"succeed":false,"toast":"Registration close."}');
			exit();
		}

		else{
			$user = new User(array(
				'id'=> 0,
				'username' => HTTPRequest::postData('username'),
				'password' => sha1(HTTPRequest::postData('password')),
				'date_creation' => date('Y-m-d H:i:s'),
				'date_last_connection' => date('Y-m-d H:i:s'),
				'last_name' => HTTPRequest::postData('last_name'),
				'first_name' => HTTPRequest::postData('first_name'),
				'email' => HTTPRequest::postData('email')
			));

			$userManager = $this->getManagerof('User');

			// Check if User exist and is valid
			if($user->isValid() && !$userManager->exist(HTTPRequest::postData('username'))  ) {
				$userManager->add($user);
				$json = '{"succeed":true}';
			}
			else {
				$json = '{"succeed":false,"toast":"Username already exists."}';
			}

			HTTPResponse::send($json);
		}	
	}
}