<?php
namespace app\controller;
use \lib\Entities\File;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class FileController extends \lib\Controller {

	// Good extenstions : secure
	var $extensions_valides = array( 'rar', 'zip', 'apk', 'png', 'jpg', 'jpeg', 'gif', 'png', 'txt', 'json', 'mp3', 'wav', 'avi', 'mp4', 'webm', 'mkv', 'pdf', 'doc', 'docx', 'pptx', 'xlsx', 'vcf', 'jarvis' );

	/**
	 * Return Get list of files
	 * @uri    	/file
	 * @method 	GET
	 * @return 	JSON List of files
	 */
	public function get() {
		$result = []; //In case where list_file is empty;

		if(HTTPRequest::getExist('search')) {
			if(HTTPRequest::getExist('url')) {
				$list_file = $this->getManagerof('File')->getWithUrl(HTTPRequest::getData('url'), HTTPRequest::getData('search'));
			}
			else {
				$list_file = $this->getManagerof('File')->getWithUrl("", HTTPRequest::getData('search'));
			}
		}
		else {
			if(HTTPRequest::getExist('url')) {
				$list_file = $this->getManagerof('File')->getWithUrl(HTTPRequest::getData('url'));
			}
			else {
				$list_file = $this->getManagerof('File')->getWithUrl();
			}
		}
		
		foreach ($list_file as $file) {
			$result[] = $file->toArray();
		}

		$json['succeed'] = true;
		$json['result'] = $result;

		HTTPResponse::send(json_encode($json, JSON_NUMERIC_CHECK));
	}

	/**
	 * Add file
	 * @uri    	/file
	 * @method 	POST
	 * @param   [$_FILE] file 		REQUIRED
	 * @param   [$_POST] visibility OPTIONAL
	 * @param   [$_POST] url   		OPTIONAL
	 */
	public function post() {
		$json['succeed'] = false;
		$json['toast'] = '';


		// Create Directory
		if(HTTPRequest::postExist('directory') && HTTPRequest::postData('directory')=="true" && HTTPRequest::postExist('url')) {

			$root_upload = __DIR__.$this->_app->_config->get('root_upload');

			$input_name = HTTPRequest::postData('url');
			$input_url = $input_name;
			$target_dir = $root_upload . $input_url;

			$visibility = 1;
			if(HTTPRequest::postExist('visibility'))
				$visibility = HTTPRequest::postData('visibility');

			$file = new File(array(
				'id'=> 0,
				'url' => $input_url,
				'name' => $input_name,
				'visibility' => $visibility,
				'date_creation' => date('Y-m-d H:i:s'),
				'id_user' => 1,
				'type' => '.dir',
				'directory' => 1
			));

			$fileManager = $this->getManagerof('File');

			if($fileManager->exist($file->getUrl())) {
				$json['toast'] = 'File or Directory exists.';
			}

			else if( !mkdir($target_dir, 0777, true)) {
				$json['toast'] = 'Sorry, there was an error making your directory.';
			}

			else { // Everything is OK ... well it seems OK

				$file->setSize(0);

				// add BDD
				$fileManager->add($file);

				// get file : get id !
				$file = $fileManager->get($file->getUrl());

				$json['succeed'] = true;
				$json['file'] = $file->toArray();							
				$json['toast'] = 'The directory '. basename( HTTPRequest::postData('url') ) .' has been uploaded.';
			}
		}


		// Upload File : Check required parameters
		else if(!HTTPRequest::fileExist('file')) {
			$json['toast'] = 'Upload failed : No file.';
		}

		else if($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
			$json['toast'] = 'Upload failed with error code '.$_FILES['file']['error'].'.';
		}

		else {
			$root_upload = __DIR__.$this->_app->_config->get('root_upload');
			$input_url = basename( $_FILES["file"]["name"]);

			// Configuring Optional parameters
			if(HTTPRequest::postExist('url'))
				$input_url = HTTPRequest::postData('url');

			$visibility = 1;
			if(HTTPRequest::postExist('visibility'))
				$visibility = HTTPRequest::postData('visibility');
			
			$extension_upload = strtolower(  substr(  strrchr($_FILES['file']['name'], '.')  ,1)  );			
			$input_name = basename($input_url, "." . $extension_upload);
			$input_url = date('Y-m-d_H-i-s') . '_' . $input_url . '_' . hash("md5", $input_url . date('Y-m-d H:i:s')) . '.' . $extension_upload;
			$target_dir = $root_upload . $input_url;

			$file = new File(array(
				'id'=> 0,
				'url' => $input_url,
				'name' => $input_name,
				'visibility' => $visibility,
				'date_creation' => date('Y-m-d H:i:s'),
				'id_user' => 1,
				'size' => 0,
				'type' => $extension_upload,
				'directory' => 0
			));

			$fileManager = $this->getManagerof('File');

			if($fileManager->exist($file->getUrl())) {
				$json['toast'] = 'File exists.';
			}

			else if( !in_array($extension_upload, $this->extensions_valides) ) {
				$json['toast'] = 'Bad extension.';
			}

			else if( 0 > $_FILES['file']['size'] && $_FILES['file']['size'] > $this->_app->_config->get('server_max_size_file')) {
				$json['toast'] = 'File too big (> '.($this->_app->_config->get('server_max_size_file')/1000000).' Mo).';
			}

			else if( $fileManager->sizeAll() + $_FILES['file']['size'] >= $this->_app->_config->get('server_max_size')) {
				$json['toast'] = 'Server : no more place.';
			}

			else if( !move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir) ) {
				$json['toast'] = 'Sorry, there was an error uploading your file.';
			}

			else { // Everything is OK ... well it seems OK
				$file->setSize($_FILES['file']['size']);

				// add BDD
				$fileManager->add($file);

				// get file : get id !
				$file = $fileManager->get($file->getUrl());

				$json['succeed'] = true;
				$json['file'] = $file->toArray();							
				$json['toast'] = 'The file '. basename( $_FILES["file"]["name"]) .' has been uploaded.';
			}
		}

		HTTPResponse::send(json_encode($json));
	}

	/**
	 * Update a file
	 * @url   	/file/:id    
	 * @method 	PUT
	 */
	public function put($id) {
		// TODO

		$json['succeed'] = false;
		$json['toast'] = '';

		$fileManager = $this->getManagerof('File');		
		parse_str(file_get_contents("php://input"), $put_vars);

		if($id == null) {
			$json['toast'] = 'Bad id.';
		}

		else if(!$fileManager->existById($id)) {
			$json['toast'] = 'Bad id.';
		}

		else if(!isset($put_vars['url'])) {
			$json['toast'] = 'Url not found '+json_encode($post_vars);
		}

		else {
			$file = $fileManager->getById($id);
			$new_url = $put_vars['url'];
			$new_extension = strtolower(  substr(  strrchr($new_url, '.')  ,1)  );

			if($file == null) {
				$json['toast'] = 'Bad id.';
			}			

			// contains '..'
			else if(strstr($new_url, '..')) {
				$json['toast'] = 'Bad url : contains /../';
			}

			else if( !in_array($new_extension, $this->extensions_valides) ) {
				$json['toast'] = 'Bad extension.';
			}

			else {
				$root_upload = __DIR__.$this->_app->_config->get('root_upload');
				rename($root_upload.$file->getUrl(), $root_upload . $new_url);

				$file->setUrl($new_url);
				$fileManager->updateUrl($file);

				$json['succeed'] = true;
				$json['toast'] = 'Good url!';
			}
		}
		
		HTTPResponse::send(json_encode($json));
	}

	/**
	 * Delete a file
	 * @url   	/file/:id    
	 * @method 	DELETE
	 */
	public function delete($id) {		
		$json['succeed'] = false;
		$json['toast'] = '';
		$fileManager = $this->getManagerof('File');

		if($id == null) {
			$json['toast'] = 'Bad id.';
		}

		else if(!$fileManager->existById($id)) {
			$json['toast'] = 'Bad id.';
		}

		else {
			$file = $fileManager->getById($id);

			if($file == null) {
				$json['toast'] = 'Bad id.';
			}

			else {
				$root_upload = __DIR__.$this->_app->_config->get('root_upload');
				$file_name = $root_upload . $file->getUrl();

				if(is_file($file_name)) {
					if(!$file->getDirectory()) {
						$fileManager->delete($file->getId());
						unlink($file_name);
						$json['succeed'] = true;
					}
					else
						$json['toast'] = 'Database : file is directory.';
				}
				else if(is_dir($file_name)) {
					if($file->getDirectory()) {
						$fileManager->delete($file->getId());
						rmdir($file_name);
						$json['succeed'] = true;
					}
					else
						$json['toast'] = 'Database : directory is file.';
				}
				else {
					$json['toast'] = 'Physic : Bad File url.';
				}
			}
		}

		HTTPResponse::send(json_encode($json));
	}

	/**
	*	POST file/test
	*/
	public function test() {
		$root_upload = __DIR__.$this->_app->_config->get('root_upload');

		$files_physic = array();
		$files1 = scandir($root_upload);

		foreach($files1 as $var) {
			if($var != '.' && $var != '..') {
				$file_array = array();
				$file_array['url'] = $var;
				$file_array['size'] = filesize($root_upload.$var);
				$files_physic[] = $file_array;
			}
		}

		$fileManager = $this->getManagerof('File');		
		$array = $fileManager->getAll();
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
		$array_json['files_size_all'] = $fileManager->sizeAll();
		HTTPResponse::send(json_encode($array_json));
	}	

	/**
	 * Get file ( Download )
	 * @url   	/file/:id    
	 * @method 	GET
	 * @return 	FILE
	 */
	public function download($id) {
		$fileManager = $this->getManagerof('File');

		if($id == null) {
			HTTPResponse::send('{"succeed":false,"toast":"Bad id."}');
		}

		else if(!$fileManager->existById($id)) {
			HTTPResponse::send('{"succeed":false,"toast":"Bad id."}');
		}

		else {
			$root_upload = __DIR__.$this->_app->_config->get('root_upload');

			$file = $fileManager->getById($id);

			if($file == null) {
				HTTPResponse::send('{"succeed":false,"toast":"Bad id."}');
			}

			else {
				$file_name = $root_upload . $file->getUrl();

				if(is_file($file_name)) {
					// required for IE
					//if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}

					// get the file mime type using the file extension
					switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
						case 'pdf': 	$mime = 'application/pdf'; 		break;
						case 'zip': 	$mime = 'application/zip'; 		break;
						case 'jpeg':	$mime = 'image/jpg'; 			break;
						case 'jpg': 	$mime = 'image/jpg'; 			break;
						case 'png': 	$mime = 'image/png'; 			break;
						case 'gif': 	$mime = 'image/gif'; 			break;
						case 'html': 	$mime = 'image/html'; 			break;
						case 'doc': 	$mime = 'image/msword'; 		break;
						default: 		$mime = 'application/force-download';
					}
					header('Pragma: public'); 	// required
					header('Expires: 0');		// no cache
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
					header('Cache-Control: private',false);
					header('Content-Type: '.$mime);
					header('Content-Disposition: attachment; filename="'.basename($root_upload . $file->getName().".".$file->getType()).'"');
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: '.filesize($file_name));	// provide file size
					header('Connection: close');
					readfile($file_name);		// push it out
				}
				else {
					HTTPResponse::send('{"succeed":false,"result":"Physic : Bad File url."}');
				}
			}
		}
	}
}