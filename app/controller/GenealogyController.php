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
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$json['succeed'] = true;


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
	
			if(HTTPRequest::postExist('first_name_1'))
				$first_name_1 = HTTPRequest::postData('first_name_1');
				
			}


		}
		else {
			$json['toast'] = 'Unauthorized access.';
		}

		HTTPResponse::send(json_encode($json));
	}
}
