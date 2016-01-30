<?php
namespace app\controller;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class VersionController extends \lib\Controller {

	/**
	 * @uri    /update/supported
	 * @method GET
	 * @return JSON with info about server Info
	 */
	public function supported() {
		$json['android_last_supported_version_code'] = 11;
		$json['android_version_not_supported'] = array();

		HTTPResponse::send(json_encode($json));
	}
}