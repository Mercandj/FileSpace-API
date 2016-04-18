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
		$content 							= array_key_exists('content', $input) ? 							$input['content'] : '';
		$description 						= array_key_exists('description', $input) ?							$input['description'] : '';
		$operating_system 					= array_key_exists('operating_system', $input) ?					$input['operating_system'] : '';
		$android_app_gcm_id 				= array_key_exists('android_app_gcm_id', $input) ?					$input['android_app_gcm_id'] : '';
		$android_app_version_code 			= array_key_exists('android_app_version_code', $input) ?			$input['android_app_version_code'] : '';
		$android_app_version_name 			= array_key_exists('android_app_version_name', $input) ?			$input['android_app_version_name'] : '';
		$android_app_package 				= array_key_exists('android_app_package', $input) ?					$input['android_app_package'] : '';
		$android_device_model 				= array_key_exists('android_device_model', $input) ?				$input['android_device_model'] : '';
		$android_device_manufacturer 		= array_key_exists('android_device_manufacturer', $input) ?			$input['android_device_manufacturer'] : '';
		$android_device_version_os 			= array_key_exists('android_device_version_os', $input) ?			$input['android_device_version_os'] : '';
		$android_device_display 			= array_key_exists('android_device_display', $input) ?				$input['android_device_display'] : '';
		$android_device_bootloader 			= array_key_exists('android_device_bootloader', $input) ?			$input['android_device_bootloader'] : '';
		$android_device_language 			= array_key_exists('android_device_language', $input) ?				$input['android_device_language'] : '';
		$android_device_display_language	= array_key_exists('android_device_display_language', $input) ?		$input['android_device_display_language'] : '';
		$android_device_country 			= array_key_exists('android_device_country', $input) ?				$input['android_device_country'] : '';
		$android_device_timezone 			= array_key_exists('android_device_timezone', $input) ?				$input['android_device_timezone'] : '';
		$android_device_radio_version 		= array_key_exists('android_device_radio_version', $input) ?		$input['android_device_radio_version'] : '';
		$android_device_version_sdk 		= array_key_exists('android_device_version_sdk', $input) ?			$input['android_device_version_sdk'] : '';
		$android_device_version_incremental = array_key_exists('android_device_version_incremental', $input) ?	$input['android_device_version_incremental'] : '';
		$android_device_year				= array_key_exists('android_device_year', $input) ?					$input['android_device_year'] : '';
		$android_device_rooted 				= array_key_exists('android_device_rooted', $input) ? 				$input['android_device_rooted'] : '';

		$device = new Device(array(
			'id'=> 0,
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'operating_system' 					=> $operating_system,
			'android_app_gcm_id' 				=> $android_app_gcm_id,
			'android_app_version_code' 			=> $android_app_version_code,
			'android_app_version_name' 			=> $android_app_version_name,
			'android_app_package' 				=> $android_app_package,
			'android_device_model' 				=> $android_device_model,
			'android_device_language' 			=> $android_device_language,
			'android_device_display_language' 	=> $android_device_display_language,
			'android_device_country' 			=> $android_device_country,
			'android_device_version_sdk' 		=> $android_device_version_sdk,
			'android_device_timezone'			=> $android_device_timezone,
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