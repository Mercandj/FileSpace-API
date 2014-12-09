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
		$json['sys_getloadavg'] = sys_getloadavg();

		HTTPResponse::send(json_encode($json));
	}

}