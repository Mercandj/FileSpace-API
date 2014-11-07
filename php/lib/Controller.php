<?php
namespace lib;

abstract class Controller extends ApplicationComponent {
	private $_matches;
	protected $_action;

	public function __construct(Application $app,$action='run',$matches=0) {
		parent::__construct($app);
		$this->_action=$action;
		$this->_matches = $matches;
	}

	public function exec() {
		$action = $this->_action;
		$this->$action();
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

	protected function getMatches($key) {
		if(isset($this->_matches[$key]))
			return $this->_matches[$key];		
		return null;
	}	
}