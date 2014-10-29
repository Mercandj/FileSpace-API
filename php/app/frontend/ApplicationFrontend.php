<?php
namespace app\frontend;
use \lib\Entities\User;

class Applicationfrontend extends \lib\Application {
	protected $_name;

	public function __construct() {
		parent::__construct();
		$this->_name = 'frontend';
	}

	public function run() {


		$controlleur = new \app\frontend\controller\LoginController($this);
		
		if($controlleur->isUser() || $this->getController() instanceof \app\frontend\controller\RegisterController)
			$this->getController()->exec();
		//else


		/*
		if(!$this->_session->isLogin()){
			$controlleur = new \app\frontend\controller\UserController($this);
			$controlleur->login();

		}else{
		*/
			//$this->_page->assign('header',$this->_config->get("title"));				
		//$this->getController()->exec();
		//}
		exit();
	}
}