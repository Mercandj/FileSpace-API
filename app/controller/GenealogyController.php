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
	public function get($id) {
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
	
			if(HTTPRequest::postExist('first_name_1'))
				$first_name_1 = HTTPRequest::postData('first_name_1');
			if(HTTPRequest::postExist('first_name_2'))
				$first_name_2 = HTTPRequest::postData('first_name_2');
			if(HTTPRequest::postExist('first_name_3'))
				$first_name_3 = HTTPRequest::postData('first_name_3');
			if(HTTPRequest::postExist('last_name'))
				$last_name = HTTPRequest::postData('last_name');

			$genealogyUser = new GenealogyUser(array(
				'id'=> 0,
				'first_name_1' => $first_name_1,
				'first_name_2' => $first_name_2,
				'first_name_3' => $first_name_3,
				'last_name' => $last_name,
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
}
