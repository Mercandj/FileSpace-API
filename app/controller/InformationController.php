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
				"value" => "".memory_get_usage()
			),

			array(
				"title" => "sizeAll",
				"value" => "".$fileManager->sizeAll()
			)
			
		);

		HTTPResponse::send(json_encode($json));
	}

}