<?php
namespace lib;
use \lib\Models\ConfigManager;

class File{
	
	public static function createThumb($width,$height,$type,$path){
		$manager = ConfigManager::getInstance();
		$width_thumb = $manager->get("width_thumb");
		$height_thumb = $manager->get("height_thumb");
		$qualite = $manager->get("qualite_thumb");

		$dvd = $width_thumb / $height_thumb;

		$thumb = imagecreatetruecolor($width_thumb, $height_thumb);

		if($width > $height){
		
			if($width < $height*$dvd){ //Picture isn't enough large
				$dvd = $width / $height;
			}

			//Processing ...
			$src_w = $height*$dvd;
			$src_h = $height;
			$x = $width/$dvd - $height;
			$y = 0;

		}else{
			$src_w = $width;
			$src_h = $width/$dvd;
			$x = 0;
			$y = $height/$dvd - $width/($dvd* 2); 
		}
		 

		switch($type) {
			case 'jpg' :
			case 'JPG' :
	        case 'jpeg': $sourceImg = imagecreatefromjpeg($path); break;
	        case 'png': $sourceImg = imagecreatefrompng($path); break;
	        default: return false;
    	}

    	imagecopyresampled($thumb,$sourceImg,0,0,$x,$y,$width_thumb,$height_thumb,$src_w,$src_h);

    	imagejpeg($thumb,$path,$qualite);
	}

	public static function movePicture($file, $path){
        move_uploaded_file($file['tmp_name'], $path.''. $file['name']);
	}

	public static function moveMultiplePicture($file,$path){
		foreach ($file["error"] as $key => $error) {
		    if ($error == UPLOAD_ERR_OK) {
		        move_uploaded_file($file["tmp_name"][$key], $path.''.$file["name"][$key]);
		    }
		}
	}

	public static function getExtension($file){
        $v = pathinfo($file['name']);
        return $v['extension'];
	}

	public static function getSize($file){
		return getimagesize('public/uploads/Thumb/'.$file['name']);
	}

	public static function clearDir($dir){
		// Ignore Errors
		set_error_handler(function(){ });
		$dir = 'public/uploads/Images/'.$dir;

		if (!($open = opendir($dir) )){
			return false;
		}

		while($file=readdir($open)) {
			if ($file == '.' || $file == '..'){
				continue;
			}

			if(!unlink($dir.'/'.$file)){
				return false;
			}
		}

		closedir($open);

		if(!rmdir($dir)){
			return false;
		}
		
		restore_error_handler();

		return true;
	}
}