<?php
namespace app\controller;
use \lib\Entities\Device;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class DeviceController extends \lib\Controller {

	/**
	* @uri    /device/add
	* @method POST
	* @return JSON with info about server Info
	*/
	public function addOrUpdate() {
		$json['succeed'] = true;
		
		$platform = '';
		if(HTTPRequest::postExist('platform')) {
			$platform = HTTPRequest::postData('platform');
		} else {
			$json['succeed'] = false;
		}

		$content = '';
		if(HTTPRequest::postExist('content')) {
			$content = HTTPRequest::postData('content');
		} else {
			$json['succeed'] = false;
		}
		
		$android_app_gcm_id = '';
		if(HTTPRequest::postExist('android_app_gcm_id')) {
			$android_app_gcm_id = HTTPRequest::postData('android_app_gcm_id');
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

		$Device = new Device(array(
			'id'=> 0,
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'platform' => $platform,
			'android_app_gcm_id' => $android_app_gcm_id,
			'android_app_version_code' => $android_app_version_code,
			'android_app_version_name' => $android_app_version_name
			));

		$deviceManager = $this->getManagerof('Device');
		$json['debug'] = 'Gcm not updated id_gcm=' . $id_gcm . ' android_app_version_code=' . $android_app_version_code;
		if($deviceManager->getByIdGcm($id_gcm) == NULL) {
			$deviceManager->add($device);
			$json['debug'] = 'Gcm updated id_gcm=' . $id_gcm . ' android_app_version_code=' . $android_app_version_code;
		}

		HTTPResponse::send(json_encode($json));
	}
}