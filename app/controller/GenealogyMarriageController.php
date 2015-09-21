<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\GenealogyPerson;
use \lib\Entities\GenealogyMarriage;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class GenealogyMarriageController extends \lib\Controller {


	/**
	 * @uri    /genealogy_marriage
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

			$genealogyMarriageManager = $this->getManagerof('GenealogyMarriage');

			

			$json['result'] = NULL;
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send($this->getJson($json));
	}

	/**
	 * @uri    /genealogy/:id
	 * @method GET
	 * @return JSON with info about genealogy
	 */
	public function getById($id) {
		$json['succeed'] = false;

		$result = []; //In case where list_file is empty;
		$list_user = [];
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');
			$genealogyMarriageManager = $this->getManagerof('GenealogyMarriage');

			if($genealogyPersonManager->existById($id)) {
				$person = $genealogyPersonManager->getById($id)->toArray();


				$json['result'] = $person;
				$json['succeed'] = true;
			}
			else {
				$json['toast'] = 'Bad id.';
			}
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}


	/**
	 * Do genealogy_marriage actions
	 * @uri    	/genealogy_marriage
	 * @method 	POST
	 */
	public function post() {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {

			$genealogyMarriageManager = $this->getManagerof('GenealogyMarriage');

			$id_person_husband = NULL;
			$id_person_wife = NULL;
			$date = NULL;
			$location = NULL;
			$content = NULL;
			$description = NULL;
	
			if(HTTPRequest::postExist('id_person_husband'))
				$id_person_husband = HTTPRequest::postData('id_person_husband');
			if(HTTPRequest::postExist('id_person_wife'))
				$id_person_wife = HTTPRequest::postData('id_person_wife');
			if(HTTPRequest::postExist('date'))
				$date = HTTPRequest::postData('date');
			if(HTTPRequest::postExist('location'))
				$location = HTTPRequest::postData('location');
			if(HTTPRequest::postExist('content'))
				$content = HTTPRequest::postData('content');
			if(HTTPRequest::postExist('description'))
				$description = HTTPRequest::postData('description');

			$genealogyMarriage = new GenealogyMarriage(array(
				'id'=> 0,
				'id_person_husband' => $id_person_husband,
				'id_person_wife' => $id_person_wife,
				'date' => $date,
				'location' => $location,
				'content' => $content,
				'description' => $description,
				'date_creation' => date('Y-m-d H:i:s')
			));

			$genealogyMarriageManager->add($genealogyMarriage);

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
			$genealogyMarriageManager = $this->getManagerof('GenealogyMarriage');
			$genealogyMarriageManager->delete($id);

			$json['succeed'] = true;
			$json['toast'] = 'Marriage deleted.';
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}


	/**
	 * Do genealogy actions
	 * @uri    	/genealogy_marriage_put
	 * @method 	POST
	 */
	public function put($id) {
		$json['succeed'] = false;
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$genealogyMarriageManager = $this->getManagerof('GenealogyMarriage');

			if(!$genealogyMarriageManager->existById($id)) {
				$json['toast'] = 'Bad id.';
				HTTPResponse::send(json_encode($json));
				return;
			}
			
			$genealogyMarriage = $genealogyMarriageManager->getById($id);

			if(HTTPRequest::postExist('first_name_1'))
				$genealogyPerson->setFirst_name_1(HTTPRequest::postData('first_name_1'));
			if(HTTPRequest::postExist('first_name_2'))
				$genealogyPerson->setFirst_name_2(HTTPRequest::postData('first_name_2'));
			if(HTTPRequest::postExist('first_name_3'))
				$genealogyPerson->setFirst_name_3(HTTPRequest::postData('first_name_3'));
			if(HTTPRequest::postExist('last_name'))
				$genealogyPerson->setLast_name(HTTPRequest::postData('last_name'));
			if(HTTPRequest::postExist('is_man'))
				$genealogyPerson->setIs_man((HTTPRequest::postData('is_man') == 'true') ? 1 : 0);
			if(HTTPRequest::postExist('date_birth'))
				$genealogyPerson->setDate_birth(HTTPRequest::postData('date_birth'));
			if(HTTPRequest::postExist('date_death'))
				$genealogyPerson->setDate_death(HTTPRequest::postData('date_death'));
			if(HTTPRequest::postExist('description'))
				$genealogyPerson->setDescription(HTTPRequest::postData('description'));
			if(HTTPRequest::postExist('id_father'))
				$genealogyPerson->setId_father(HTTPRequest::postData('id_father'));
			if(HTTPRequest::postExist('id_mother'))
				$genealogyPerson->setId_mother(HTTPRequest::postData('id_mother'));

			$genealogyPersonManager->update($genealogyPerson);

			$json['succeed'] = true;
			$json['toast'] = 'User modified.';
		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}
}
