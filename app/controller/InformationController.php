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
				"title" => "Size all files",
				"value" => "".$this->size(intval($fileManager->sizeAll()))
			),

			array(
				"title" => "Current time",
				"value" => "".date("d-m-Y h:i:s")
			)

		);

		HTTPResponse::send(json_encode($json));
	}

	private function size($bytes) {
	    if ($bytes > 0) {
	        $unit = intval(log($bytes, 1024));
	        $units = array('B', 'KB', 'MB', 'GB');

	        if (array_key_exists($unit, $units) === true)
	            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
	    }
	    return $bytes;
	}
}