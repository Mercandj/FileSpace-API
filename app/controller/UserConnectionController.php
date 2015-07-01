<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\UserConnection;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class UserConnectionController extends \lib\Controller {

	/**
	 * Get all the user connection
	 * @uri    /user_connection
	 * @method GET
	 * @return JSON with the connections
	 */
	public function get() {
		$json['succeed'] = false;
		$json['toast'] = '';
		$result = [];
		$list_user_connetion = [];

		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);
		
		$userConnectionManager = $this->getManagerof('UserConnection');

		if($user->isAdmin()) {			
			$list_user_connetion = $userConnectionManager->getAllPage(150, 1);
			foreach ($list_user_connetion as $user_connetion) {
				$result[] = $user_connetion->toArray();
			}
			$json['result'] = $result;
			$json['succeed'] = true;
		}
		else {
			$json['toast'] = 'Not permitted.';
		}
		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}
	
}

