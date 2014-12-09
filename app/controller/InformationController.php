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
			'php_uname' => php_uname(),
			'php_uname' => php_uname(),
			'memory_get_usage' => memory_get_usage(),
			'sizeAll' => $fileManager->sizeAll()
		);

		HTTPResponse::send(json_encode($json));
	}

}