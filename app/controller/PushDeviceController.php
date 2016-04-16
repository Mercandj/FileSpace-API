<?php
namespace app\controller;
use \lib\Entities\PushDevice;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class PushDeviceController extends \lib\Controller {

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
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'android_app_version_code' => $android_app_version_code,
			'android_app_version_name' => $android_app_version_name
			));

		$pushDeviceManager = $this->getManagerof('PushDevice');
		$json['debug'] = 'Gcm not updated id_gcm=' . $id_gcm . ' android_app_version_code=' . $android_app_version_code;
		if($pushDeviceManager->getByIdGcm($id_gcm) == NULL) {
			$pushDeviceManager->add($pushDevice);
			$json['debug'] = 'Gcm updated id_gcm=' . $id_gcm . ' android_app_version_code=' . $android_app_version_code;
		}

		HTTPResponse::send(json_encode($json));
	}
}