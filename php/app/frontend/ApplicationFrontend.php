<?php
namespace app\frontend;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		$controlleur = new \app\frontend\controller\LoginController($this);
		
		if($controlleur->isUser() 
			|| $this->getController() instanceof \app\frontend\controller\RegisterController
			|| $this->getController() instanceof \app\frontend\controller\LoginController)

			$this->getController()->exec();

		else {
			$this->_page->assign('json', '{"succeed":false,"toast":"Applicationfrontend : Wrong Login."}');		
			$this->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
		}
		
		exit();
	}
}