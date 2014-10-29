<?php
namespace app\frontend\controller;
use \lib\Entities\User;
use \lib\Entities\File;

class RefreshFileController extends \lib\Controller{

	public function refresh() {		

		$array = new array();


		$dir = "./public/";
		$files1 = scandir($dir);


		foreach($files1 as $var) {
			$array['url'] = $var;
			$array['size'] = filesize($dir.$var);
			/*
			if (strpos($var,'mp3')) {
				$musicfile = fopen($dir.$var, 'r');
				fseek($musicfile, -128, SEEK_END);

				$tag = fread($musicfile, 3);

				if($tag == "TAG") {
					$res.='"song": "'.($data["song"] = trim(fread($musicfile, 30))).'",';
					$res.='"artist": "'.($data["artist"] = trim(fread($musicfile, 30))).'",';
					$res.='"album": "'.($data["album"] = trim(fread($musicfile, 30))).'",';
				}			

			    fclose($musicfile);
			}
			*/
		}

		$this->_app->_page->assign('json', json_encode($array));
		
		// SEND PAGE
		$this->_app->_HTTPResponse->send($this->_app->_page->draw('JsonView.php'));		
	}
	
}