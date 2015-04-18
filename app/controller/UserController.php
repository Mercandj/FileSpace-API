<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class UserController extends \lib\Controller {

	/**
	 * Login a User
	 * @uri    /user
	 * @method GET
	 * @return JSON with info about user like ID
	 */
	public function get() {
		$result = []; //In case where list_file is empty;
		$json['succeed'] = false;
		$userManager = $this->getManagerof('User');

		if($this->isUser()) {
			if(HTTPRequest::getExist('login')) {
				$user = $userManager->get(HTTPRequest::serverData('PHP_AUTH_USER'));
				$json['succeed'] = true;
				$json['user'] = $user->toArray();
			}
			else {
				$list_user = $userManager->getAll();
				foreach ($list_user as $user) {
					$result[] = $user->toArray();
				}
				$json['succeed'] = true;
				$json['result'] = $result;
			}			
		}
		else {
			$json['toast'] = 'Wrong User.';
		}

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	 * Register a new user
	 * @uri    /user
	 * @method POST
	 * @param  username   REQUIRED
	 * @param  password   REQUIRED
	 * @param  last_name  OPTIONAL
	 * @param  first_name OPTIONAL
	 * @param  email      OPTIONAL
	 */
	public function post() {

		if(!HTTPRequest::postExist('username')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong User."}');
		}

		else if(!HTTPRequest::postExist('password')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong Password."}');
		}

		else if(!$this->_app->_config->get('registration_open')) {
			HTTPResponse::send('{"succeed":false,"toast":"Registration close."}');
		}

		else{
			$user = new User(array(
				'id'=> 0,
				'username' => HTTPRequest::postData('username'),
				'password' => sha1(HTTPRequest::postData('password')),
				'date_creation' => date('Y-m-d H:i:s'),
				'date_last_connection' => date('Y-m-d H:i:s')
			));

			$userManager = $this->getManagerof('User');

			// Check if User exist and is valid
			if($user->isValid() && !$userManager->exist(HTTPRequest::postData('username'))) {
				$userManager->add($user);
				$json = '{"succeed":true}';
			}
			else {
				$json = '{"succeed":false,"toast":"Username already exists."}';
			}

			HTTPResponse::send($json);
		}	
	}


	/**
	*	Check BDD AUTH user : Used by $this->get() and RESTapi.php
	*/
	public function isUser() {

		if(HTTPRequest::serverExist('PHP_AUTH_USER') && HTTPRequest::serverExist('PHP_AUTH_PW')){
			
			$user = new User(array(
				'username' => HTTPRequest::serverData('PHP_AUTH_USER'),
				'password' => HTTPRequest::serverData('PHP_AUTH_PW'),
				'date_last_connection' => date('Y-m-d H:i:s')
			));

			$userManager = $this->getManagerof('User');

			if($userManager->exist($user->getUsername())) {	

				$userbdd = $userManager->get($user->getUsername());

				// Front send HTTPRequest::serverData('PHP_AUTH_PW') = sha1( sha1(sha1(real_pass)) . date('Y-m-d H:i') )
				// sha1(sha1(real_pass)) : because, for example on android, the device save sha1(real_pass) on the device
				// and send sha1(sha1(real_pass)) in order to be sure that the data sent throw internet are 
				// different than the save data on device.
				// sha1( sha1(sha1(real_pass)) . date('Y-m-d H:i') ) : in order to have an expiry date on the pass
				// sent throw internet.

				// DataBase keeps sha1(sha1(real_pass)) to be sure that the pass saved on DB are never on internet

				// So the pass comparaison allows 60 minutes after the pass generation

				for($i=0 ; $i <= 60 ; $i++) {

					date_default_timezone_set("UTC");

					if( $user->getPassword() === sha1($userbdd->getPassword() . date("Y-m-d H:i",strtotime(date("Y-m-d H:i", time())." + ".$i." minutes"))) ) {

						if(HTTPRequest::exist('android_id')) {
							$user->setAndroid_id(HTTPRequest::get('android_id'));
							$userManager->updateAndroidId($user);
						}					
						$userManager->updateConnection($user);
						$this->_app->_config->setId_user($userbdd->getId());
						
						return true;
					}

				}				

			}
		}

		return false;
	}


	/**
	*	Check BDD AUTH user : Used by RESTapi.php
	*/
	public function isAdmin() {

		if(HTTPRequest::serverExist('PHP_AUTH_USER') && HTTPRequest::serverExist('PHP_AUTH_PW')){
			
			$user = new User(array(
				'username' => HTTPRequest::serverData('PHP_AUTH_USER'),
				'password' => sha1(HTTPRequest::serverData('PHP_AUTH_PW'))
			));

			$userManager = $this->getManagerof('User');

			if($userManager->exist($user->getUsername())) {	

				$userbdd = $userManager->get($user->getUsername());

				if($user->getPassword() === $userbdd->getPassword()) {					
					return $userbdd->isAdmin();
				}

			}
		}

		return false;
	}
}