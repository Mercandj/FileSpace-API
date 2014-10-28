<?php
namespace lib;

abstract class Controller extends ApplicationComponent{
	protected $_urlVars,
		$_action;

	public function __construct(Application $app,$action='run',$vars=0){
		parent::__construct($app);
		$this->_action=$action;
		$this->_urlVars = $vars;
	}

	public function exec(){
		$action = $this->_action;
		$this->$action();
	}

	/**
	*	Create Instance of Manager
	*	@param Name of the Manager
	*	@return Manager Object
	*/
	protected function getManagerof($manager){
		$path = '\\lib\Models\\'.$manager.'Manager';
		return $path::getInstance($this->_app->_pdo);
	}

	protected function getFlashMessage(){
		if($this->_app->_session->hasFlashMessage()){
			$this->_app->_page->assign('flashMessage', $this->_app->_session->getFlashMessage() );
		}
	}

	
}