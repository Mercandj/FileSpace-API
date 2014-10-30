<?php
namespace app\frontend\controller;
use \lib\Entities\File;

class FileController extends \lib\Controller{

	public function test() {		
		$files = array();
		$dir = __DIR__."\\..\\..\\..\\public\\";
		$files1 = scandir($dir);

		$i=0;
		foreach($files1 as $var) {
			$file_array = array();
			$file_array['url'] = $var;
			$file_array['size'] = filesize($dir.$var);
			$file_array['id'] = uniqid();
			$file_array['visibility'] = 0;
			$file = new File($file_array);
			$files[$i] = $file_array;
			$i++;
		}
		
		$array_json = array();
		$array_json['files'] = $files;
		$this->_app->_page->assign('json', json_encode($array_json));
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));		
	}

	public function add() {

		if(!array_key_exists('content', $this->_app->_parameters)) {
			$json = '{"succeed":false,"toast":"FileController : ERROR : !array_key_exists(content, $this->_app->_parameters)."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		$file = new File($this->_app->_parameters['content']);
		$userManager = $this->getManagerof('File');
		
		$this->_app->_page->assign('json', json_encode($file));
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}
	
}