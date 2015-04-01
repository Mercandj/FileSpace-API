<?php
namespace app\controller;
use \lib\Entities\File;
use \lib\Entities\FileDownload;
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
		$id_user = $this->_app->_config->getId_user();

		$all = false;
		if(HTTPRequest::getExist('all'))
			$all = HTTPRequest::getData('all');

		$all_public = false;
		if(HTTPRequest::getExist('all-public'))
			$all_public = HTTPRequest::getData('all-public');

		$url = "";
		if(HTTPRequest::getExist('url'))
			$url = HTTPRequest::getData('url');

		$id_file_parent = -1;
		if(HTTPRequest::getExist('id_file_parent'))
			$id_file_parent = HTTPRequest::getData('id_file_parent');

		if($all) {
			if(HTTPRequest::getExist('search'))
				$list_file = $this->getManagerof('File')->getAll($id_user, $id_file_parent, HTTPRequest::getData('search'));
			else
				$list_file = $this->getManagerof('File')->getAll($id_user, $id_file_parent);
		}

		else if($all_public) {
			if(HTTPRequest::getExist('search'))
				$list_file = $this->getManagerof('File')->getPublic(0, $id_file_parent, HTTPRequest::getData('search'));
			else
				$list_file = $this->getManagerof('File')->getPublic(0, $id_file_parent);
		}

		else {
			if(HTTPRequest::getExist('search'))
				$list_file = $this->getManagerof('File')->getByParentId($id_user, $id_file_parent, HTTPRequest::getData('search'));
			else
				$list_file = $this->getManagerof('File')->getByParentId($id_user, $id_file_parent);
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

		$id_user = $this->_app->_config->getId_user();

		// Create Directory
		if(HTTPRequest::postExist('directory') && HTTPRequest::postData('directory')=="true" && HTTPRequest::postExist('url')) {

			$root_upload = __DIR__.$this->_app->_config->get('root_upload');

			$input_name = HTTPRequest::postData('url');

			$visibility = 1;
			if(HTTPRequest::postExist('visibility'))
				$visibility = HTTPRequest::postData('visibility');

			$id_file_parent = -1;
			if(HTTPRequest::postExist('id_file_parent'))
				$id_file_parent = HTTPRequest::postData('id_file_parent');

			$file = new File(array(
				'id'=> 0,
				'url' => $input_name,
				'name' => $input_name,
				'visibility' => $visibility,
				'date_creation' => date('Y-m-d H:i:s'),
				'id_user' => $id_user,
				'type' => 'dir',
				'directory' => 1,
				'size' => 0,
				'id_file_parent' => $id_file_parent
			));

			$fileManager = $this->getManagerof('File');

			if(!HTTPRequest::postExist('url')) {
				$json['toast'] = 'Directory has no url.';
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

		else if(HTTPRequest::postExist('content')) {
			$root_upload = __DIR__.$this->_app->_config->get('root_upload');

			// Configuring Optional parameters
			$input_name = "noname";
			if(HTTPRequest::postExist('name'))
				$input_name = HTTPRequest::postData('name');

			$visibility = 1;
			if(HTTPRequest::postExist('visibility'))
				$visibility = HTTPRequest::postData('visibility');

			$id_file_parent = -1;
			if(HTTPRequest::postExist('id_file_parent'))
				$id_file_parent = HTTPRequest::postData('id_file_parent');
			
			$extension_upload = 'jarvis';
			$input_url = $input_name . '.' . $extension_upload;
			$input_url = date('Y-m-d_H-i-s') . '_' . $input_url . '_' . hash("md5", $input_url . date('Y-m-d H:i:s')) . '.' . $extension_upload;
			$target_dir = $root_upload . $input_url;

			$file = new File(array(
				'id'=> 0,
				'url' => $input_url,
				'name' => $input_name,
				'visibility' => $visibility,
				'date_creation' => date('Y-m-d H:i:s'),
				'id_user' => $id_user,
				'content' => HTTPRequest::postData('content'),
				'size' => 0,
				'type' => $extension_upload,
				'directory' => 0,
				'id_file_parent' => $id_file_parent
			));

			$fileManager = $this->getManagerof('File');

			// Create file on disk, and fill it
			$myfile = fopen($target_dir, "w");
			fwrite($myfile, HTTPRequest::postData('content'));
			fclose($myfile);

			// Everything is OK ... well it seems OK
			$file->setSize(filesize($target_dir));

			// add BDD
			$fileManager->add($file);

			// get file : get id !
			$file = $fileManager->get($file->getUrl());

			$json['succeed'] = true;
			$json['file'] = $file->toArray();							
			$json['toast'] = 'The file '. $input_name .' has been created.';			
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

			$size = 0;
			if(HTTPRequest::postExist('size'))
				$size = HTTPRequest::postData('size');

			$id_file_parent = -1;
			if(HTTPRequest::postExist('id_file_parent'))
				$id_file_parent = HTTPRequest::postData('id_file_parent');
			
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
				'id_user' => $id_user,
				'size' => $size,
				'type' => $extension_upload,
				'directory' => 0,
				'id_file_parent' => $id_file_parent
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
	 * @method 	POST
	 */
	public function update($id) {
		$json['succeed'] = false;
		$json['toast'] = '';

		$fileManager = $this->getManagerof('File');

		if($id == null) {
			$json['toast'] = 'Bad id.';
		}

		else if(!$fileManager->existById($id)) {
			$json['toast'] = 'Bad id.';
		}

		else if(HTTPRequest::postExist('public')) {
			$file = $fileManager->getById($id);
			$public = HTTPRequest::postData('public');
			$file->setPublic( ($public == "true" || $public == 1 || $public == "1") ? 1 : 0);
			$fileManager->updatePublic($file);
			$json['succeed'] = true;
			$json['toast'] = 'Your file is ' . (($public == "true" || $public == 1 || $public == "1") ? 'public.' : 'private.');
		}

		else if(HTTPRequest::postExist('id_file_parent')) {
			$id_file_parent = HTTPRequest::postData('id_file_parent');

			if($id_file_parent == -1) {
				$file = $fileManager->getById($id);
				$file->setId_file_parent($id_file_parent);
				$fileManager->updateId_file_parent($file);
				$json['succeed'] = true;
				$json['toast'] = 'Your file has been moved.';
			}
			else {
				$file_parent = $fileManager->getById($id_file_parent);
				if($file_parent->getDirectory()) {
					$file = $fileManager->getById($id);
					$file->setId_file_parent($id_file_parent);
					$fileManager->updateId_file_parent($file);
					$json['succeed'] = true;
					$json['toast'] = 'Your file has been moved.';
				}
				else
					$json['toast'] = 'Parent file is not a directory.';
			}
		}

		else if(!HTTPRequest::postExist('url')) {
			$json['toast'] = 'Url not found.';
		}

		else {
			$file = $fileManager->getById($id);
			$new_url = HTTPRequest::postData('url');
			$json['debug-url'] = $new_url;
			$new_extension = strtolower(  substr(  strrchr($new_url, '.')  ,1)  );

			if($file == null) {
				$json['toast'] = 'Bad id.';
			}			

			// contains '..'
			else if(strstr($new_url, '..') || strstr($new_url, '/')) {
				$json['toast'] = 'Bad url : contains /../';
			}

			else if ($file->getDirectory()) {
				$file->setUrl($new_url);
				$file->setName($new_url);
				$fileManager->updateName($file);
				$json['succeed'] = true;
				$json['toast'] = 'Folder renamed!';
			}

			else if( !in_array($new_extension, $this->extensions_valides) ) {
				$json['toast'] = 'Bad extension.';
			}

			else {
				$root_upload = __DIR__.$this->_app->_config->get('root_upload');
							

				$url = date('Y-m-d_H-i-s') . '_' . $new_url . '_' . hash("md5", $new_url . date('Y-m-d H:i:s')) . '.' . $new_extension;
				rename($root_upload.$file->getUrl(), $root_upload . $url);	
				$file->setUrl($url);
				$file->setName(basename($new_url, '.'. (pathinfo($new_url)['extension']) ) );

				$fileManager->updateName($file);

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
				else if($file->getDirectory()) {					
					$json['succeed'] = $this->deleteWithChildren($file->getId());;
				}
				else {
					$json['toast'] = 'Physic : Bad File url.';
				}
			}
		}

		HTTPResponse::send(json_encode($json));
	}

	private function deleteWithChildren($id) {
		$fileManager = $this->getManagerof('File');
		if(!$fileManager->existById($id))
			return false;

		$return = true;

		$file = $fileManager->getById($id);
		$file_children = $fileManager->getChildren($id);

		foreach($file_children as $child) {
			if(!$this->deleteWithChildren($child->getId()))
				$return = false;
		}

		$root_upload = __DIR__.$this->_app->_config->get('root_upload');
		$file_name = $root_upload . $file->getUrl();

		if($file->getDirectory()) {
			$fileManager->delete($file->getId());
		}
		else if(is_file($file_name)) {
			if(!$file->getDirectory()) {
				$fileManager->delete($file->getId());
				unlink($file_name);
			}
			else
				$return = false;
		}		

		return $return;		
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

					$fileDownloadManager = $this->getManagerof('FileDownload');
					
					$fileDownload = new FileDownload(array(
						'id'=> 0,
						'title' => $file->getName(),
						'public' => 0,
						'visibility' => 1,
						'date_creation' => date('Y-m-d H:i:s'),
						'id_user' => $this->_app->_config->getId_user(),
						'id_file' => $file->getId(),
						'size' => $file->getSize(),
						'content' => $file->getContent(),
						'type' => $file->getType()
					));
					$fileDownloadManager->add($fileDownload);

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
						case 'mp3': 	$mime = 'audio/mpeg';	 		break;
						case 'apk': 	$mime = 'application/vnd.android.package-archive'; break;
						default: 		$mime = 'application/force-download';
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
					HTTPResponse::send('{"succeed":false,"result":"Physic : Bad File url."}');
				}
			}
		}
	}
}