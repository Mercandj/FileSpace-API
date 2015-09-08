<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class InformationController extends \lib\Controller {

	/**
	 * Login a User
	 * @uri    /information
	 * @method GET
	 * @return JSON with info about server Info
	 */
	public function get() {
		$fileManager = $this->getManagerof('File');
		$json['succeed'] = true;

		$json['result'] = array(

			array(
				"title" => "php_uname",
				"value" => "".php_uname()
			),

			array(
				"title" => "memory_get_usage",
				"value" => "".$this->size(memory_get_usage())
			),

			array(
				"title" => "Files : Size all",
				"value" => "".$this->size(intval($fileManager->sizeAll()))
			),

			array(
				"title" => "Files : Count all",
				"value" => "".$fileManager->count()
			),

			array(
				"title" => "Current time",
				"value" => "".date("d-m-Y h:i:s")
			)

		);

		HTTPResponse::send(json_encode($json));
	}

	private function size($bytes) {
		if ($bytes >= 1000000000000) {
            return number_format($bytes / 1000000000000, 1) . ' TB';
        }
		if ($bytes >= 1000000000) {
            return number_format($bytes / 1000000000, 1) . ' GB';
        }
        else if ($bytes >= 1000000) {
            return number_format($bytes / 1000000, 1) . ' MB';
        }
        else if ($bytes >= 1000) {
            return number_format($bytes / 1000, 1) . ' KB';
        }
        else if ($bytes > 1) {
            return $bytes . ' bytes';
        }
        return $bytes . ' byte';
	}
}