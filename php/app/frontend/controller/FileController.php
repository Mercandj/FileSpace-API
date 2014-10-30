<?php
namespace app\frontend\controller;
use \lib\Entities\File;

class FileController extends \lib\Controller {

	public function test() {

		$root = __DIR__."\\..\\..\\..\\public\\";

		$files = array();
		$files1 = scandir($root);

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

		$root = __DIR__."\\..\\..\\..\\public\\";

		if(!array_key_exists('content', $this->_app->_parameters)) {
			$json = '{"succeed":false,"toast":"FileController : ERROR : !array_key_exists(content, $this->_app->_parameters)."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		$file = new File($this->_app->_parameters['content']);
		$userManager = $this->getManagerof('File');
		
		$this->_app->_page->assign('json', json_encode($this->_app->_parameters['content']));
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));


		$target_dir = $root . basename( $_FILES["file"]["name"]);

		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'txt' );
		$extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );

		if ( in_array($extension_upload,$extensions_valides) ) {
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)) {
			    
				if ( 0 < $_FILES['file']['size'] && $_FILES['file']['size'] < 1000000  )
			    	$this->_app->_page->assign('json', '{"succeed":true,"toast":"The file '. basename( $_FILES["file"]["name"]) .' has been uploaded."');
				else
					$this->_app->_page->assign('json', '{"succeed":false,"toast":"File size : '.$_FILES['file']['size'].'"}');

			}
			else
				$this->_app->_page->assign('json', '{"succeed":false,"toast":"Sorry, there was an error uploading your file."');
		}
		else
			$this->_app->_page->assign('json', '{"succeed":false,"toast":"Bad extension."');

		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}	
}