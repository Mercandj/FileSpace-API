<?php
namespace app\controller;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class VersionController extends \lib\Controller {

/**
* @uri    /support/comment
* @method GET
* @return JSON with info about server Info
*/
public function commentGet() {

	$id_device = '';
	if(HTTPRequest::getExist('id_device')) {
		$id_device = HTTPRequest::getData('id_device');
	}

	$json['android_last_supported_version_code'] = 11;
	$json['android_version_not_supported'] = array();

	HTTPResponse::send(json_encode($json));
}

/**
* @uri    /support/comment
* @method POST
* @return JSON with info about server Info
*/
public function commentPost() {
	
	$id_device = '';
	if(HTTPRequest::postExist('id_device')) {
		$id_device = HTTPRequest::postData('id_device');
	}

	$content = '';
	if(HTTPRequest::postExist('content')) {
		$content = HTTPRequest::postData('content');
	}

	$json['android_last_supported_version_code'] = 11;
	$json['android_version_not_supported'] = array();

	HTTPResponse::send(json_encode($json));
}
}