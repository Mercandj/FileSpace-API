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

			$genealogyUserManager = $this->getManagerof('GenealogyUser');

			if(HTTPRequest::getExist('search'))
				$list_user = $genealogyUserManager->getAll(HTTPRequest::getData('search'));
			else
				$list_user = $genealogyUserManager->getAll();

			foreach ($list_user as $file) {
				$person = $file->toArray();

				if(array_key_exists('id_mother', $person)) {
					if(isset($person['id_mother'])) {
						$person['mother'] = $genealogyUserManager->getById($person['id_mother'])->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyUserManager->existById($person['id_mother'])) {
							$list_user = $genealogyUserManager->getChildren($person['id_mother']);
							foreach ($list_user as $file) {
								if($file->getId() != $person['id']) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
									}
									$brothersSisters[] = $brotherSister;
								}
							}
						}
						$person['brothers_sisters_from_mother'] = $brothersSisters;

					}
				}
				if(array_key_exists('id_father', $person)) {
					if(isset($person['id_father'])) {
						$person['father'] = $genealogyUserManager->getById($person['id_father'])->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyUserManager->existById($person['id_father'])) {
							$list_user = $genealogyUserManager->getChildren($person['id_father']);
							foreach ($list_user as $file) {
								if($file->getId() != $person['id']) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
									}
									$brothersSisters[] = $brotherSister;
								}
							}
						}
						$person['brothers_sisters_from_father'] = $brothersSisters;

					}
				}

				$result[] = $person;
			}

			$json['result'] = $result;
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
			$genealogyUserManager = $this->getManagerof('GenealogyUser');

			if($genealogyUserManager->existById($id)) {
				$person = $genealogyUserManager->getById($id)->toArray();

				if(array_key_exists('id_mother', $person)) {
					if(isset($person['id_mother'])) {
						$tmp_mother = $this->getPersonTree($genealogyUserManager, $person['id_mother'], 4);
						if($tmp_mother != null)
							$person['mother'] = $tmp_mother->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyUserManager->existById($person['id_mother'])) {
							$list_user = $genealogyUserManager->getChildren($person['id_mother']);
							foreach ($list_user as $file) {
								if($file->getId() != $id) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
									}
									$brothersSisters[] = $brotherSister;
								}
							}
						}
						$person['brothers_sisters_from_mother'] = $brothersSisters;

					}
				}
				if(array_key_exists('id_father', $person)) {
					if(isset($person['id_father'])) {
						$tmp_father = $this->getPersonTree($genealogyUserManager, $person['id_father'], 4);
						if($tmp_father != null)
							$person['father'] = $tmp_father->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyUserManager->existById($person['id_father'])) {
							$list_user = $genealogyUserManager->getChildren($person['id_father']);
							foreach ($list_user as $file) {
								if($file->getId() != $id) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
									}
									$brothersSisters[] = $brotherSister;
								}
							}
						}
						$person['brothers_sisters_from_father'] = $brothersSisters;

					}
				}


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

	private function getPersonTree($genealogyUserManager, $id, $time) {
		$person = null;

		if($genealogyUserManager->existById($id) && $time > 0) {
			$person = $genealogyUserManager->getById($id)->toArray();

			if(array_key_exists('id_mother', $person)) {
				if(isset($person['id_mother'])) {
					$mother = $this->getPersonTree($genealogyUserManager, $person['id_mother'], $time-1);
					if($mother != null)
						$person['mother'] = $mother;
				}
			}
			if(array_key_exists('id_father', $person)) {
				if(isset($person['id_father'])) {
					$father = $this->getPersonTree($genealogyUserManager, $person['id_father'], $time-1);
					if($father != null)
						$person['father'] = $father;
				}
			}
		}

		return $person;
	}


	/**
	 * @uri    /genealogy_children/:id
	 * @method GET
	 * @return JSON with info about genealogy
	 */
	public function getChildren($id) {
		$json['succeed'] = false;

		$result = []; //In case where list_file is empty;
		$list_user = [];
		
		$id_user = $this->_app->_config->getId_user();
		$userManager = $this->getManagerof('User');
		$user = $userManager->getById($id_user);

		if($user->isAdmin()) {
			$genealogyUserManager = $this->getManagerof('GenealogyUser');
			if($genealogyUserManager->existById($id)) {

				$list_user = $genealogyUserManager->getChildren($id);

				foreach ($list_user as $file) {
					$person = $file->toArray();

					if(array_key_exists('id_mother', $person)) {
						if(isset($person['id_mother'])) {
							$person['mother'] = $genealogyUserManager->getById($person['id_mother'])->toArray();

							// Get brothers & sisters
							$brothersSisters = [];
							if($genealogyUserManager->existById($person['id_mother'])) {
								$list_user = $genealogyUserManager->getChildren($person['id_mother']);
								foreach ($list_user as $file) {
									if($file->getId() != $person['id']) {
										$brotherSister = $file->toArray();
										if(array_key_exists('id_mother', $brotherSister)) {
											if(isset($brotherSister['id_mother']))
												$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
										}
										if(array_key_exists('id_father', $brotherSister)) {
											if(isset($brotherSister['id_father']))
												$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
										}
										$brothersSisters[] = $brotherSister;
									}
								}
							}
							$person['brothers_sisters_from_mother'] = $brothersSisters;

						}
					}
					if(array_key_exists('id_father', $person)) {
						if(isset($person['id_father'])) {
							$person['father'] = $genealogyUserManager->getById($person['id_father'])->toArray();

							// Get brothers & sisters
							$brothersSisters = [];
							if($genealogyUserManager->existById($person['id_father'])) {
								$list_user = $genealogyUserManager->getChildren($person['id_father']);
								foreach ($list_user as $file) {
									if($file->getId() != $person['id']) {
										$brotherSister = $file->toArray();
										if(array_key_exists('id_mother', $brotherSister)) {
											if(isset($brotherSister['id_mother']))
												$brotherSister['mother'] = $genealogyUserManager->getById($brotherSister['id_mother'])->toArray();
										}
										if(array_key_exists('id_father', $brotherSister)) {
											if(isset($brotherSister['id_father']))
												$brotherSister['father'] = $genealogyUserManager->getById($brotherSister['id_father'])->toArray();
										}
										$brothersSisters[] = $brotherSister;
									}
								}
							}
							$person['brothers_sisters_from_father'] = $brothersSisters;

						}
					}

					$result[] = $person;
				}

				$json['result'] = $result;
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

	/**
	 * Do genealogy actions
	 * @uri    	/genealogy_statistics
	 * @method 	GET
	 */
	public function statistics() {
		$genealogyUserManager = $this->getManagerof('GenealogyUser');
		$json['succeed'] = true;

		$json['result'] = array(

			array(
				"title" => "Number of persons",
				"value" => "".$genealogyUserManager->count()
			),

			array(
				"title" => "Last added",
				"value" => "".$genealogyUserManager->biggerDate_creation()
			)

		);

		HTTPResponse::send(json_encode($json));
	}
}
