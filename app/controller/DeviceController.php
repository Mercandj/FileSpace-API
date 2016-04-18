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

		$inputJSON 							= file_get_contents('php://input');
		$input 								= json_decode( $inputJSON, TRUE );
		$content 							= $input['content'];
		$description 						= $input['description'];
		$operating_system 					= $input['operating_system'];
		$android_app_gcm_id 				= $input['android_app_gcm_id'];
		$android_app_version_code 			= $input['android_app_version_code'];
		$android_app_version_name 			= $input['android_app_version_name'];
		$android_app_package 				= $input['android_app_package'];
		$android_device_model 				= $input['android_device_model'];
		$android_device_manufacturer 		= $input['android_device_manufacturer'];
		$android_device_version_os 			= $input['android_device_version_os'];
		$android_device_display 			= $input['android_device_display'];
		$android_device_bootloader 			= $input['android_device_bootloader'];
		$android_device_language 			= $input['android_device_language'];
		$android_device_display_language	= $input['android_device_display_language'];
		$android_device_country 			= $input['android_device_country'];
		$android_device_timezone 			= $input['android_device_timezone'];
		$android_device_radio_version 		= $input['android_device_radio_version'];
		$android_device_version_sdk 		= $input['android_device_version_sdk'];
		$android_device_version_incremental = $input['android_device_version_incremental'];
		$android_device_year				= $input['android_device_year'];
		$android_device_rooted 				= $input['android_device_rooted'];

		$device = new Device(array(
			'id'=> 0,
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'operating_system' 					=> $operating_system,
			'android_app_gcm_id' 				=> $android_app_gcm_id,
			'android_app_version_code' 			=> $android_app_version_code,
			'android_app_version_name' 			=> $android_app_version_name,
			'android_device_language' 			=> $android_device_language,
			'android_device_display_language' 	=> $android_device_display_language,
			'android_device_country' 			=> $android_device_country,
			'android_device_version_sdk' 		=> $android_device_version_sdk,
			'android_device_year' 				=> $android_device_year,
			'android_device_rooted' 			=> $android_device_rooted
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