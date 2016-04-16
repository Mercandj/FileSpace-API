<?php
namespace app\controller;
use \lib\Entities\PushDevice;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class PushController extends \lib\Controller {

	/**
	* @uri    /push/device/add
	* @method POST
	* @return JSON with info about server Info
	*/
	public function addOrUpdate() {
		$json['succeed'] = true;
		
		$id_gcm = '';
		if(HTTPRequest::postExist('id_gcm')) {
			$id_gcm = HTTPRequest::postData('id_gcm');
		} else {
			$json['succeed'] = false;
		}

		$content = '';
		if(HTTPRequest::postExist('content')) {
			$content = HTTPRequest::postData('content');
		} else {
			$json['succeed'] = false;
		}

		$android_app_version_code = '';
		if(HTTPRequest::postExist('android_app_version_code')) {
			$android_app_version_code = HTTPRequest::postData('android_app_version_code');
		} else {
			$json['succeed'] = false;
		}

		$android_app_version_name = '';
		if(HTTPRequest::postExist('android_app_version_name')) {
			$android_app_version_name = HTTPRequest::postData('android_app_version_name');
		} else {
			$json['succeed'] = false;
		}

		$pushDevice = new PushDevice(array(
			'id'=> 0,
			'id_gcm' => $id_gcm,
			'is_dev_response' => intval($is_dev_response),
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'android_app_version_code' => $android_app_version_code,
			'android_app_version_name' => $android_app_version_name
			));

		$pushDeviceManager = $this->getManagerof('PushDevice');
		$json['debug'] = 'Gcm not updated.';
		if($pushDeviceManager->getByIdGcm($id_gcm) != NULL) {
			$pushDeviceManager->add($pushDevice);
			$json['debug'] = 'Gcm updated.';
		}

		$json['result'] = $result;

		HTTPResponse::send(json_encode($json));
	}
}