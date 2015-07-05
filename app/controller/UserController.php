<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\UserConnection;
use \lib\Entities\File;
use \lib\Entities\ConversationUser;
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
		$fileManager = $this->getManagerof('File');

		if($this->isUser()) {
			$list_user = $userManager->getAll();
			foreach ($list_user as $user) {
				$user_array = $user->toArray();
				$id_file_profile_picture = $user->getId_file_profile_picture();
				if(intval($id_file_profile_picture)!=-1 && $id_file_profile_picture!=null) {
					$file_profile_picture = $fileManager->getById($id_file_profile_picture);
					$user_array["file_profile_picture_size"] = $file_profile_picture->getSize();
				}
				$result[] = $user_array;
			}
			$json['succeed'] = true;
			$json['result'] = $result;
		}
		else {
			$json['toast'] = 'Wrong User or Password.';
		}

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	 * Login a User
	 * @uri    /user/:id
	 * @method GET
	 * @return JSON with info about user like ID
	 */
	public function getById($id) {
		$result = []; //In case where list_file is empty;
		$json['succeed'] = false;
		$userManager = $this->getManagerof('User');
		$fileManager = $this->getManagerof('File');

		if($this->isAdmin() || $id == intval($this->_app->_config->getId_user())) {
			$user = $userManager->getById($id);
			$user_array = $user->toArray();
			$id_file_profile_picture = $user->getId_file_profile_picture();
			if(intval($id_file_profile_picture)!=-1 && $id_file_profile_picture!=null) {
				$file_profile_picture = $fileManager->getById($id_file_profile_picture);
				$user_array["file_profile_picture_size"] = $file_profile_picture->getSize();
			}
			$json['succeed'] = true;
			$json['result'] = $user_array;
		}
		else {
			$json['toast'] = 'Not admin.';
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

		$userManager = $this->getManagerof('User');

		if(HTTPRequest::postExist('login')) {
			$json['succeed'] = false;

			if($this->isUser()) {

				$user = $userManager->get(HTTPRequest::serverData('PHP_AUTH_USER'));

				if(HTTPRequest::postExist('longitude') && HTTPRequest::postExist('latitude')) {
					$user->setLongitude(HTTPRequest::postData('longitude'));
					$user->setLatitude(HTTPRequest::postData('latitude'));
					$userManager->updatePosition($user);
				}

				$user = $userManager->get(HTTPRequest::serverData('PHP_AUTH_USER'));
				$json['succeed'] = true;
				$json['user'] = $user->toArray();
			}
			else {
				$json['toast'] = 'Wrong User or Password.';
			}

			HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
		}

		else if(!HTTPRequest::postExist('username')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong User."}');
		}

		else if(!HTTPRequest::postExist('password')) {
			HTTPResponse::send('{"succeed":false,"toast":"Wrong Password."}');
		}

		else if(!$this->_app->_config->get('registration_open') && !$this->isAdmin()) {
			HTTPResponse::send('{"succeed":false,"toast":"Registration close."}');
		}

		else {

			if(HTTPRequest::getExist('google_plus')) {
				// Inscription / Login via Google plus
				$user = new User(array(
					'id'=> 0,
					'username' => HTTPRequest::postData('username'),
					'password' => sha1(HTTPRequest::postData('password')),
					'date_creation' => date('Y-m-d H:i:s'),
					'date_last_connection' => date('Y-m-d H:i:s')
				));

				// Check if User !exist and is valid
				if($user->isValid() && !$userManager->exist(HTTPRequest::postData('username'))) {
					$userManager->add($user);
					$json = '{"succeed":true, "toast":"Inscription via Google+.", "debug":"Inscription via Google+."}';
				}
				else {
					if($this->isUser()) {
						$json = '{"succeed":true, "toast":"Login via Google+.", "debug":"Login via Google+."}';
					}
					else {
						$json = '{"succeed":false,"toast":"Username already exists or wrong password."}';
					}
				}
			}
			else {
				// Inscription normal
				$user = new User(array(
					'id'=> 0,
					'username' => HTTPRequest::postData('username'),
					'password' => sha1(HTTPRequest::postData('password')),
					'date_creation' => date('Y-m-d H:i:s'),
					'date_last_connection' => date('Y-m-d H:i:s')
				));

				// Check if User !exist and is valid
				if($user->isValid() && !$userManager->exist(HTTPRequest::postData('username'))) {
					$userManager->add($user);
					$json = '{"succeed":true}';
				}
				else {
					$json = '{"succeed":false,"toast":"Username already exists."}';
				}
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
				
				// TODO log all connections : (goal : secure connections with time delay if repeted wrong connection)
				$userConnectionManager = $this->getManagerof('UserConnection');
				
				date_default_timezone_set("UTC");
				$pass_expiry_time = 240; // minutes

				// Front send HTTPRequest::serverData('PHP_AUTH_PW') = sha1( sha1(sha1(real_pass)) . date('Y-m-d H:i') )
				// sha1(sha1(real_pass)) : because, for example on android, the device save sha1(real_pass) on the device
				// and send sha1(sha1(real_pass)) in order to be sure that the data sent throw internet are 
				// different than the save data on device.
				// sha1( sha1(sha1(real_pass)) . date('Y-m-d H:i') ) : in order to have an expiry date on the pass
				// sent throw internet.

				// DataBase keeps sha1(sha1(real_pass)) to be sure that the pass saved on DB are never on internet

				// So the pass comparaison allows $pass_expiry_time minutes after the pass generation

				for($i=10 ; $i >= -$pass_expiry_time ; $i--) {
					if( ''.$user->getPassword() === ''.sha1($userbdd->getPassword() . date("Y-m-d H:i",strtotime(date("Y-m-d H:i", time())." ".$i." minutes"))) ) {

						if(HTTPRequest::exist('android_id')) {
							$user->setAndroid_id(HTTPRequest::get('android_id'));
							$userManager->updateAndroidId($user);
						}					
						$userManager->updateConnection($user);
						$this->_app->_config->setId_user($userbdd->getId());

						// TODO
						$userConnection = new UserConnection(array(
							'id_user' => intval($userbdd->getId()),
							'succeed' => 1,
							'date_creation' => date('Y-m-d H:i:s'),
							'request_uri' => HTTPRequest::serverData('REQUEST_URI')
						));
						$userConnectionManager->add($userConnection);
						
						return true;
					}
				}
				
				// TODO
				$userConnection = new UserConnection(array(
					'id_user' => intval($userbdd->getId()),
					'succeed' => 0,
					'date_creation' => date('Y-m-d H:i:s'),
					'request_uri' => HTTPRequest::serverData('REQUEST_URI')
				));
				$userConnectionManager->add($userConnection);
			}
		}

		return false;
	}


	/**
	*	Check BDD AUTH user : Used by RESTapi.php
	*/
	public function isAdmin() {

		if(HTTPRequest::serverExist('PHP_AUTH_USER') && HTTPRequest::serverExist('PHP_AUTH_PW')) {
			
			$user = new User(array(
				'username' => HTTPRequest::serverData('PHP_AUTH_USER'),
				'password' => sha1(HTTPRequest::serverData('PHP_AUTH_PW'))
			));

			$userManager = $this->getManagerof('User');

			if($userManager->exist($user->getUsername())) {
				$userbdd = $userManager->get($user->getUsername());
				return intval($userbdd->isAdmin());
			}
		}

		return 0;
	}

	/**
	 * Modify a User
	 * @uri    /user_put
	 * @method POST
	 * @return JSON with info about user
	 */
	public function put() {
		$result = []; //In case where list_file is empty;
		$json['succeed'] = false;

		if(HTTPRequest::postExist('id_file_profile_picture')) {
			$id_file_profile_picture = intval(HTTPRequest::postData('id_file_profile_picture'));
			
			$id_user = $this->_app->_config->getId_user();
			$userManager = $this->getManagerof('User');
			$user = $userManager->getById($id_user);

			$user->setId_file_profile_picture($id_file_profile_picture);
			$userManager->updateId_file_profile_picture($user);

			$json['succeed'] = true;
			$json['toast'] = "Your picture has been updated.";
		}
		else {
			$json['toast'] = "No parameter.";
		}

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	 * Modify a User
	 * @uri    /user/id
	 * @method DELETE
	 * @return JSON with info about user
	 */
	public function delete($id) {
		$result = []; //In case where list_file is empty;
		$json['succeed'] = false;

		$userManager = $this->getManagerof('User');

		if($this->isAdmin()) {

			if(!$userManager->existById($id)) {
				$json['toast'] = 'Bad id.';
				HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
				return;
			}

			$userToDelete = $userManager->getById($id);
			if($userToDelete->isAdmin()) {
				$json['toast'] = 'You cannot delete an admin.';
				HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
				return;
			}
			
			// TODO delete all the user stuff

			// Delete UserConnection: logs
			$userConnectionManager = $this->getManagerof('UserConnection');
			$userConnectionManager->deleteByUserId($id);

			// Delete all user files
			$fileManager = $this->getManagerof('File');
			$root_upload = __DIR__.$this->_app->_config->get('root_upload');
			$list_file = $fileManager->getAllByUser($id);
			foreach ($list_file as $file) {
				$json['succeed'] = true;

				if($file == null) {
					$json['toast'] = 'Bad id.';
					$json['succeed'] = false;
					HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
					return;
				}

				else {
					$file_name = $root_upload . $file->getUrl();
					if(is_file($file_name)) {
						if(!$file->getDirectory()) {
							$fileManager->delete($file->getId());
							unlink($file_name);
							$json['succeed'] = true;
						}
						else {
							$json['toast'] = 'Database : file is directory.';
							$json['succeed'] = false;
							HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
							return;
						}
					}
					else if($file->getDirectory()) {					
						if(!$this->deleteFileWithChildren($file->getId())) {
							$json['succeed'] = false;
							HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
							return;
						}
					}
					else {
						$json['toast'] = 'Physic : Bad File url.';
						$json['succeed'] = false;
						HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
						return;
					}
				}
			}

			// Delete User
			$userManager->delete($id);
			
			$json['succeed'] = true;
			$json['toast'] = "The user has been deleted.";
		}
		else {
			$json['toast'] = "Not admin.";
		}

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	private function deleteFileWithChildren($id) {
		$fileManager = $this->getManagerof('File');
		if(!$fileManager->existById($id))
			return false;

		$return = true;

		$file = $fileManager->getById($id);
		$file_children = $fileManager->getChildren($id);

		foreach($file_children as $child) {
			if(!$this->deleteFileWithChildren($child->getId()))
				$return = false;
		}

		$root_upload = __DIR__.$this->_app->_config->get('root_upload');
		$file_name = $root_upload . $file->getUrl();

		if($file->getDirectory()) {
			$fileManager->delete($file->getId());
		}
		else if(is_file($file_name)) {
			if(!$file->getDirectory()) {
				$fileManager->delete($file->getId());
				unlink($file_name);
			}
			else
				$return = false;
		}		

		return $return;		
	}
}
