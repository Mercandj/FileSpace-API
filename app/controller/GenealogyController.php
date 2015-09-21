<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\GenealogyPerson;
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

			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');

			if(HTTPRequest::getExist('search'))
				$list_user = $genealogyPersonManager->getAll(HTTPRequest::getData('search'));
			else
				$list_user = $genealogyPersonManager->getAll();

			foreach ($list_user as $file) {
				$person = $file->toArray();

				if(array_key_exists('id_mother', $person)) {
					if(isset($person['id_mother'])) {
						$person['mother'] = $genealogyPersonManager->getById($person['id_mother'])->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyPersonManager->existById($person['id_mother'])) {
							$list_user = $genealogyPersonManager->getChildren($person['id_mother']);
							foreach ($list_user as $file) {
								if($file->getId() != $person['id']) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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
						$person['father'] = $genealogyPersonManager->getById($person['id_father'])->toArray();

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyPersonManager->existById($person['id_father'])) {
							$list_user = $genealogyPersonManager->getChildren($person['id_father']);
							foreach ($list_user as $file) {
								if($file->getId() != $person['id']) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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
			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');

			if($genealogyPersonManager->existById($id)) {
				$person = $genealogyPersonManager->getById($id)->toArray();

				if(array_key_exists('id_mother', $person)) {
					if(isset($person['id_mother'])) {
						$tmp_mother = $this->getPersonTree($genealogyPersonManager, $person['id_mother'], 4);
						if($tmp_mother != null)
							$person['mother'] = $tmp_mother;

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyPersonManager->existById($person['id_mother'])) {
							$list_user = $genealogyPersonManager->getChildren($person['id_mother']);
							foreach ($list_user as $file) {
								if($file->getId() != $id) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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
						$tmp_father = $this->getPersonTree($genealogyPersonManager, $person['id_father'], 4);
						if($tmp_father != null)
							$person['father'] = $tmp_father;

						// Get brothers & sisters
						$brothersSisters = [];
						if($genealogyPersonManager->existById($person['id_father'])) {
							$list_user = $genealogyPersonManager->getChildren($person['id_father']);
							foreach ($list_user as $file) {
								if($file->getId() != $id) {
									$brotherSister = $file->toArray();
									if(array_key_exists('id_mother', $brotherSister)) {
										if(isset($brotherSister['id_mother']))
											$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
									}
									if(array_key_exists('id_father', $brotherSister)) {
										if(isset($brotherSister['id_father']))
											$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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

	private function getPersonTree($genealogyPersonManager, $id, $time) {
		$person = null;

		if($genealogyPersonManager->existById($id) && $time > 0) {
			$person = $genealogyPersonManager->getById($id)->toArray();

			if(array_key_exists('id_mother', $person)) {
				if(isset($person['id_mother'])) {
					$mother = $this->getPersonTree($genealogyPersonManager, $person['id_mother'], $time-1);
					if($mother != null)
						$person['mother'] = $mother;
				}
			}
			if(array_key_exists('id_father', $person)) {
				if(isset($person['id_father'])) {
					$father = $this->getPersonTree($genealogyPersonManager, $person['id_father'], $time-1);
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
			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');
			if($genealogyPersonManager->existById($id)) {

				$list_user = $genealogyPersonManager->getChildren($id);

				foreach ($list_user as $file) {
					$person = $file->toArray();

					if(array_key_exists('id_mother', $person)) {
						if(isset($person['id_mother'])) {
							$person['mother'] = $genealogyPersonManager->getById($person['id_mother'])->toArray();

							// Get brothers & sisters
							$brothersSisters = [];
							if($genealogyPersonManager->existById($person['id_mother'])) {
								$list_user = $genealogyPersonManager->getChildren($person['id_mother']);
								foreach ($list_user as $file) {
									if($file->getId() != $person['id']) {
										$brotherSister = $file->toArray();
										if(array_key_exists('id_mother', $brotherSister)) {
											if(isset($brotherSister['id_mother']))
												$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
										}
										if(array_key_exists('id_father', $brotherSister)) {
											if(isset($brotherSister['id_father']))
												$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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
							$person['father'] = $genealogyPersonManager->getById($person['id_father'])->toArray();

							// Get brothers & sisters
							$brothersSisters = [];
							if($genealogyPersonManager->existById($person['id_father'])) {
								$list_user = $genealogyPersonManager->getChildren($person['id_father']);
								foreach ($list_user as $file) {
									if($file->getId() != $person['id']) {
										$brotherSister = $file->toArray();
										if(array_key_exists('id_mother', $brotherSister)) {
											if(isset($brotherSister['id_mother']))
												$brotherSister['mother'] = $genealogyPersonManager->getById($brotherSister['id_mother'])->toArray();
										}
										if(array_key_exists('id_father', $brotherSister)) {
											if(isset($brotherSister['id_father']))
												$brotherSister['father'] = $genealogyPersonManager->getById($brotherSister['id_father'])->toArray();
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

			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');

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

			$genealogyPerson = new GenealogyPerson(array(
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

			$genealogyPersonManager->add($genealogyPerson);

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
			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');
			$genealogyPersonManager->delete($id);

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
			$genealogyPersonManager = $this->getManagerof('GenealogyPerson');

			if(!$genealogyPersonManager->existById($id)) {
				$json['toast'] = 'Bad id.';
				HTTPResponse::send(json_encode($json));
				return;
			}
			
			$genealogyPerson = $genealogyPersonManager->getById($id);

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

	/**
	 * Do genealogy actions
	 * @uri    	/genealogy_statistics
	 * @method 	GET
	 */
	public function statistics() {
		$genealogyPersonManager = $this->getManagerof('GenealogyPerson');
		$json['succeed'] = true;

		$json['result'] = array(

			array(
				"title" => "Number of persons",
				"value" => "".$genealogyPersonManager->count()
			),

			array(
				"title" => "Last added",
				"value" => "".$genealogyPersonManager->biggerDate_creation()
			)

		);

		HTTPResponse::send(json_encode($json));
	}
}
