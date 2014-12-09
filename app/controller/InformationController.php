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

		$json['succeed'] = true;
		$json['php_uname'] = php_uname();
		$json['memory_get_usage'] = memory_get_usage();
		$fileManager = $this->getManagerof('File');
		$json['sizeAll'] = $fileManager->sizeAll();

		HTTPResponse::send(json_encode($json));
	}

}