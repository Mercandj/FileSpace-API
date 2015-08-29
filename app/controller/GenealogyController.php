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
			$description = NULL;
			$id_father = NULL;
			$id_mother = NULL;
	
			if(HTTPRequest::postExist('first_name_1'))
				$first_name_1 = HTTPRequest::postData('first_name_1');
			if(HTTPRequest::postExist('first_name_2'))
				$first_name_2 = HTTPRequest::postData('first_name_2');
			if(HTTPRequest::postExist('first_name_3'))
				$first_name_3 = HTTPRequest::postData('first_name_3');
			if(HTTPRequest::postExist('last_name'))
				$last_name = HTTPRequest::postData('last_name');
			if(HTTPRequest::postExist('is_man'))
				$is_man = (HTTPRequest::postData('is_man') == 'true') ? 1 : 0;
			if(HTTPRequest::postExist('date_birth'))
				$date_birth = HTTPRequest::postData('date_birth');
			if(HTTPRequest::postExist('date_death'))
				$date_death = HTTPRequest::postData('date_death');
			if(HTTPRequest::postExist('description'))
				$description = HTTPRequest::postData('description');
			if(HTTPRequest::postExist('id_father'))
				$id_father = HTTPRequest::postData('id_father');
			if(HTTPRequest::postExist('id_mother'))
				$id_mother = HTTPRequest::postData('id_mother');

			$genealogyUser = new GenealogyUser(array(
				'id'=> 0,
				'first_name_1' => $first_name_1,
				'first_name_2' => $first_name_2,
				'first_name_3' => $first_name_3,
				'last_name' => $last_name,
				'is_man' => $is_man,
				'date_birth' => $date_birth,
				'date_death' => $date_death,
				'description' => $description,
				'date_creation' => date('Y-m-d H:i:s'),
				'id_father' => $id_father,
				'id_mother' => $id_mother
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


	/**
	 * Do genealogy actions
	 * @uri    	/genealogy_put
	 * @method 	POST
	 */
	public function put($id) {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$genealogyUserManager = $this->getManagerof('GenealogyUser');

			if(!$genealogyUserManager->existById($id)) {
				$json['toast'] = 'Bad id.';
				HTTPResponse::send(json_encode($json));
				return;
			}
			
			$genealogyUser = $genealogyUserManager->getById($id);

			if(HTTPRequest::postExist('first_name_1'))
				$genealogyUser->setFirst_name_1(HTTPRequest::postData('first_name_1'));
			if(HTTPRequest::postExist('first_name_2'))
				$genealogyUser->setFirst_name_2(HTTPRequest::postData('first_name_2'));
			if(HTTPRequest::postExist('first_name_3'))
				$genealogyUser->setFirst_name_3(HTTPRequest::postData('first_name_3'));
			if(HTTPRequest::postExist('last_name'))
				$genealogyUser->setLast_name(HTTPRequest::postData('last_name'));
			if(HTTPRequest::postExist('is_man'))
				$genealogyUser->setIs_man((HTTPRequest::postData('is_man') == 'true') ? 1 : 0);
			if(HTTPRequest::postExist('date_birth'))
				$genealogyUser->setDate_birth(HTTPRequest::postData('date_birth'));
			if(HTTPRequest::postExist('date_death'))
				$genealogyUser->setDate_death(HTTPRequest::postData('date_death'));
			if(HTTPRequest::postExist('description'))
				$genealogyUser->setDescription(HTTPRequest::postData('description'));
			if(HTTPRequest::postExist('id_father'))
				$genealogyUser->setId_father(HTTPRequest::postData('id_father'));
			if(HTTPRequest::postExist('id_mother'))
				$genealogyUser->setId_mother(HTTPRequest::postData('id_mother'));

			$genealogyUserManager->update($genealogyUser);

			$json['succeed'] = true;
			$json['toast'] = 'User modified.';
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}
}
