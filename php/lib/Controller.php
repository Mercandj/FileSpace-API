<?php
namespace lib;

abstract class Controller extends ApplicationComponent {

	public function __construct(Application $app) {
		parent::__construct($app);
	}


	/**
	*	Create Instance of Manager
	*	@param Name of the Manager
	*	@return Manager Object
	*/
	protected function getManagerof($manager) {
		$path = '\\lib\Models\\'.$manager.'Manager';
		return $path::getInstance($this->_app->_pdo);
	}
}