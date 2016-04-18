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

		$operating_system = '';
		if(HTTPRequest::postExist('operating_system'))						$operating_system = HTTPRequest::postData('operating_system');
		else 																$json['succeed'] = false;

		$content = '';
		if(HTTPRequest::postExist('content')) 								$content = HTTPRequest::postData('content');
		else																$json['succeed'] = false;

		$android_app_gcm_id = '';
		if(HTTPRequest::postExist('android_app_gcm_id')) 					$android_app_gcm_id = HTTPRequest::postData('android_app_gcm_id');
		else																$json['succeed'] = false;

		$android_app_version_code = '';
		if(HTTPRequest::postExist('android_app_version_code'))				$android_app_version_code = HTTPRequest::postData('android_app_version_code');
		else																$json['succeed'] = false;

		$android_app_version_name = '';
		if(HTTPRequest::postExist('android_app_version_name'))				$android_app_version_name = HTTPRequest::postData('android_app_version_name');
		else																$json['succeed'] = false;

		$android_device_id = '';
		if(HTTPRequest::postExist('android_device_id'))						$android_device_id = HTTPRequest::postData('android_device_id');
		else																$json['succeed'] = false;

		$android_device_model = '';
		if(HTTPRequest::postExist('android_device_model'))					$android_device_model = HTTPRequest::postData('android_device_model');
		else																$json['succeed'] = false;

		$android_device_manufacturer = '';
		if(HTTPRequest::postExist('android_device_manufacturer'))			$android_device_manufacturer = HTTPRequest::postData('android_device_manufacturer');
		else																$json['succeed'] = false;

		$android_device_version_sdk = '';
		if(HTTPRequest::postExist('android_device_version_sdk'))			$android_device_version_sdk = HTTPRequest::postData('android_device_version_sdk');
		else																$json['succeed'] = false;

		$android_device_language = '';
		if(HTTPRequest::postExist('android_device_language'))				$android_device_language = HTTPRequest::postData('android_device_language');
		else																$json['succeed'] = false;

		$android_device_display_language = '';
		if(HTTPRequest::postExist('android_device_display_language'))		$android_device_display_language = HTTPRequest::postData('android_device_display_language');
		else																$json['succeed'] = false;

		$android_device_country = '';
		if(HTTPRequest::postExist('android_device_country'))				$android_device_country = HTTPRequest::postData('android_device_country');
		else																$json['succeed'] = false;

		$android_device_year = '';
		if(HTTPRequest::postExist('android_device_year'))					$android_device_year = HTTPRequest::postData('android_device_year');
		else																$json['succeed'] = false;

		$android_device_rooted = '';
		if(HTTPRequest::postExist('android_device_rooted'))					$android_device_rooted = HTTPRequest::postData('android_device_rooted');
		else																$json['succeed'] = false;

		$device = new Device(array(
			'id'=> 0,
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'operating_system' => $operating_system,
			'android_app_gcm_id' => $android_app_gcm_id,
			'android_app_version_code' => $android_app_version_code,
			'android_app_version_name' => $android_app_version_name,
			'android_device_language' => $android_device_language,
			'android_device_display_language' => $android_device_display_language,
			'android_device_country' => $android_device_country,
			'android_device_version_sdk' => $android_device_version_sdk,
			'android_device_rooted' => $android_device_rooted
			));

		$deviceManager = $this->getManagerof('Device');
		$json['debug'] = 'Gcm not updated.';
		if($deviceManager->getByIdGcm($android_app_gcm_id) == NULL) {
			$deviceManager->add($device);
			$json['debug'] = 'Gcm updated.';
		}

		HTTPResponse::send(json_encode($json));
	}
}