<?php
namespace app\frontend\controller;
use \lib\Entities\User;
use \lib\Entities\File;

class RefreshFileController extends \lib\Controller{

	public function refresh() {		

		/*
		$array = new array();
		$dir = "./public/";
		$files1 = scandir($dir);


		foreach($files1 as $var) {
			$array['url'] = $var;
			$array['size'] = filesize($dir.$var);
		}
		
		$this->_app->_page->assign('json', json_encode($array));
		*/

		$this->_app->_page->assign('json', "".__DIR__);

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));		
	}
	
}