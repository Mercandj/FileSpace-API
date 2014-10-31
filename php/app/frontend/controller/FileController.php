<?php
namespace app\frontend\controller;
use \lib\Entities\File;

class FileController extends \lib\Controller {

	public function test() {
		$root = __DIR__."\\..\\..\\..\\public\\";

		$files_physic = array();
		$files1 = scandir($root);

		foreach($files1 as $var) {
			if($var != '.' && $var != '..') {
				$file_array = array();
				$file_array['url'] = $var;
				$file_array['size'] = filesize($root.$var);
				$files_physic[] = $file_array;
			}
		}

		$userManager = $this->getManagerof('File');		
		$array = $userManager->getAll();
		$files_bdd = array();
		
		foreach ($array as $value) {
			$file = array();
			$file['url'] = $value->getUrl();
			$file['size'] = $value->getSize();
			$files_bdd[] = $file;
		}
		
		$array_json = array();
		$array_json['files_physic'] = $files_physic;
		$array_json['files_bdd'] = $files_bdd;
		$this->_app->_page->assign('json', json_encode($array_json));
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}

	public function add() {
		$root = __DIR__."\\..\\..\\..\\public\\";

		if(!@array_key_exists('file', $this->_app->_parameters)) {
			$json = '{"succeed":false,"toast":"FileController : file key (json) not found."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		$file = new File($this->_app->_parameters['file']);
		$userManager = $this->getManagerof('File');
		
		$target_dir = $root . basename( $_FILES["file"]["name"]);

		$extensions_valides = array( 'rar', 'zip', 'apk', 'png', 'jpg', 'jpeg', 'gif', 'png', 'txt', 'mp3', 'avi', 'mp4', 'pdf', 'docx', 'pptx' );
		$extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );

		$succeed = false;
		$toast = '';

		if(array_key_exists('file', $_FILES)) {
	    	if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
				if(!$userManager->exist($file->getUrl())) {
					if ( in_array($extension_upload,$extensions_valides) ) {
						if ( 0 < $_FILES['file']['size'] && $_FILES['file']['size'] < 800000000  ) {
							if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)) {

								$file->setId(uniqid());
								$file->setSize($_FILES['file']['size']);
								$userManager->add($file);

								$succeed = true;
					    		$toast = 'The file '. basename( $_FILES["file"]["name"]) .' has been uploaded.';
							}
							else
								$toast = 'Sorry, there was an error uploading your file.';
					    }
						else
							$toast = 'File size : '.$_FILES['file']['size'].'.';
					}
					else
						$toast = 'Bad extension.';
				}
				else
					$toast = 'File exists.';
			}
			else
				$toast = 'Upload failed with error code '.$_FILES['file']['error'].'.';
		}
		else
			$toast = 'Upload failed : !array_key_exists(file, $_FILES).';

		$this->_app->_page->assign('json', '{"succeed":'.$succeed.',"toast":"'.$toast.'"}');
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));		
	}

	public function get() {
		$userManager = $this->getManagerof('File');

		$array = $userManager->getAll();
		$json = array();
		
		foreach ($array as $value) {
			$file = array();
			$file['url'] = $value->getUrl();
			$file['size'] = $value->getSize();
			$json[] = $file;
		}

		$this->_app->_page->assign('json', '{"succeed":true,"result":'.json_encode($json).'}');
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
	}

	public function ddl() {
		$root = __DIR__."\\..\\..\\..\\public\\";

		if(!@array_key_exists('file', $this->_app->_parameters)) {
			$json = '{"succeed":false,"toast":"FileController : file key (json) not found."}';
			$this->_app->_page->assign('json', $json);
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			return;
		}

		$file = new File($this->_app->_parameters['file']);
		$userManager = $this->getManagerof('File');

		if($userManager->exist($file->getUrl())) {
			$file_name = $root . $file->getUrl();

			if(is_file($file_name)) {
				// required for IE
				//if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}

				// get the file mime type using the file extension
				switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
					case 'pdf': $mime = 'application/pdf'; break;
					case 'zip': $mime = 'application/zip'; break;
					case 'jpeg':
					case 'jpg': $mime = 'image/jpg'; break;
					default: $mime = 'application/force-download';
				}
				header('Pragma: public'); 	// required
				header('Expires: 0');		// no cache
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: '.filesize($file_name));	// provide file size
				header('Connection: close');
				readfile($file_name);		// push it out
			}
			else {
				$this->_app->_page->assign('json', '{"succeed":false,"result":"Physic : Bad File url."}');
				$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
			}

		}
		else {
			$this->_app->_page->assign('json', '{"succeed":false,"result":"Bdd : Bad File url."}');
			$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));
		}
	}
}