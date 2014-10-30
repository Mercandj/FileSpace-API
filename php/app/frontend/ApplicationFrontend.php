<?php
namespace app\frontend;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {

		$controlleur = new \app\frontend\controller\UserController($this);
		
		if($controlleur->isUser() || $this->getController() instanceof \app\frontend\controller\UserController)
			$this->getController()->exec();
		else {
			
			if(isset($_FILES['file']))
				$this->_page->assign('json', '{"succeed":false,"toast":"Wrong User.","debug":"$_FILES[file][name]:'.$_FILES['file']['name'].'"}');
			else if(isset($_FILES['json']))
				$this->_page->assign('json', '{"succeed":false,"toast":"Wrong User.","debug":"$_FILES[json][name]:'.$_FILES['json']['name'].'"}');
			else
				$this->_page->assign('json', '{"succeed":false,"toast":"Wrong User.","debug":"COUCOU'.file_get_contents("php://input").'"}');

			/*
			if(isset($_SERVER['CONTENT_TYPE'])) {
	            $this->_page->assign('json', '{"succeed":false,"toast":"Wrong User.","debug":" '.$_SERVER['CONTENT_TYPE'].' \n\n  '.file_get_contents("php://input").'"}');
	        }
	        else
				$this->_page->assign('json', '{"succeed":false,"toast":"Wrong User.","debug":"'.file_get_contents("php://input").'"}');
			*/
			$this->_HTTPResponse->send($this->_page->draw('JsonView.php'));
		}
		
		exit();
	}
}