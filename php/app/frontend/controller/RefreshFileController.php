<?php
namespace app\frontend\controller;
use \lib\Entities\File;

class RefreshFileController extends \lib\Controller{

	public function refresh() {
		
		$files = array();
		$dir = __DIR__."\\..\\..\\..\\public\\";
		$files1 = scandir($dir);

		foreach($files1 as $var) {
			$files['url'] = $var;
			$files['size'] = filesize($dir.$var);
		}
		
		$this->_app->_page->assign('json', json_encode($files));

		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));		
	}
	
}