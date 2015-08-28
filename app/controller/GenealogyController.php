<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\GenealogyUser;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class GenealogyController extends \lib\Controller {


	/**
	 * @uri    /genealogy
	 * @method GET
	 * @return JSON with info about genealogy
	 */
	public function get() {
		$json['succeed'] = false;

		$result = []; //In case where list_file is empty;
		$list_user = [];
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$json['succeed'] = true;


			$list_user = $this->getManagerof('GenealogyUser')->getAll();

			foreach ($list_user as $file) {
				$result[] = $file->toArray();
			}

			$json['result'] = $result;
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}


	/**
	 * Do genealogy actions
	 * @uri    	/genealogy
	 * @method 	POST
	 */
	public function post() {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {

			$genealogyUserManager = $this->getManagerof('GenealogyUser');

			$first_name_1 = NULL;
			$first_name_2 = NULL;
			$first_name_3 = NULL;
			$last_name = NULL;
			$is_man = NULL;
			$date_birth = NULL;
			$date_death = NULL;
	
			if(HTTPRequest::postExist('first_name_1'))
				$first_name_1 = HTTPRequest::postData('first_name_1');
			if(HTTPRequest::postExist('first_name_2'))
				$first_name_2 = HTTPRequest::postData('first_name_2');
			if(HTTPRequest::postExist('first_name_3'))
				$first_name_3 = HTTPRequest::postData('first_name_3');
			if(HTTPRequest::postExist('last_name'))
				$last_name = HTTPRequest::postData('last_name');
			if(HTTPRequest::postExist('is_man'))
				$last_name = (HTTPRequest::postData('is_man') == 'true') ? 1 : 0;
			if(HTTPRequest::postExist('date_birth'))
				$date_birth = HTTPRequest::postData('date_birth');
			if(HTTPRequest::postExist('date_death'))
				$date_death = HTTPRequest::postData('date_death');

			$genealogyUser = new GenealogyUser(array(
				'id'=> 0,
				'first_name_1' => $first_name_1,
				'first_name_2' => $first_name_2,
				'first_name_3' => $first_name_3,
				'last_name' => $last_name,
				'is_man' => $is_man,
				'date_birth' => $date_birth,
				'date_death' => $date_death,
				'date_creation' => date('Y-m-d H:i:s')
			));

			$genealogyUserManager->add($genealogyUser);

			$json['succeed'] = true;
			$json['toast'] = 'User added.';
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}

	/**
	 * Do genealogy actions
	 * @uri    	/genealogy_delete
	 * @method 	POST
	 */
	public function delete($id) {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$genealogyUserManager = $this->getManagerof('GenealogyUser');
			$genealogyUserManager->delete($id);

			$json['succeed'] = true;
			$json['toast'] = 'User deleted.';
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}
}
