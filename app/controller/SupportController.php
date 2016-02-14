<?php
namespace app\controller;
use \lib\Entities\SupportComment;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class SupportController extends \lib\Controller {

	/**
	* @uri    /support/comment
	* @method GET
	* @return JSON with info about server Info
	*/
	public function commentGet() {
		$json['succeed'] = true;

		$id_device = '';
		if(HTTPRequest::getExist('id_device')) {
			$id_device = HTTPRequest::getData('id_device');
		} else {
			$json['succeed'] = false;
		}

		$supportManager = $this->getManagerof('Support');
		$list_comment = $supportManager->getAllByIdDevice($id_device);
		$result = [];
		foreach ($list_comment as $comment) {
			$comment_array = $comment->toArray();
			$result[] = $comment_array;
		}

		$json['result'] = $result;
		$json['debug'] = '$id_device:' . $id_device;

		HTTPResponse::send(json_encode($json));
	}

	/**
	* Admin get all support comments
	*
	* @uri    /support/comment/device_id
	* @method GET
	* @return JSON with info about server Info
	*/
	public function commentGetAllIdDevice() {
		$json['succeed'] = true;
		$supportManager = $this->getManagerof('Support');
		$list_comment = $supportManager->getAllIdDevice();
		$result = [];
		foreach ($list_comment as $comment) {
			$comment_array = $comment->toArray();
			$result[] = $comment_array;
		}

		$json['result'] = $result;

		HTTPResponse::send(json_encode($json));
	}

	/**
	* @uri    /support/comment/delete
	* @method POST
	* @return JSON with info about server Info
	*/
	public function commentDelete() {
		$json['succeed'] = true;

		$id_device = '';
		if(HTTPRequest::postExist('id_device')) {
			$id_device = HTTPRequest::postData('id_device');
		} else {
			$json['succeed'] = false;
		}

		$id = 0;
		if(HTTPRequest::postExist('id')) {
			$id = HTTPRequest::postData('id');
		} else {
			$json['succeed'] = false;
		}

		$supportManager = $this->getManagerof('Support');
		$supportManager->delete(intval($id));

		$list_comment = $supportManager->getAllByIdDevice($id_device);
		$result = [];
		foreach ($list_comment as $comment) {
			$comment_array = $comment->toArray();
			$result[] = $comment_array;
		}

		$json['result'] = $result;
		$json['debug'] = '$id_device:' . $id_device . ' $id:' . $id;

		HTTPResponse::send(json_encode($json));
	}

	/**
	* @uri    /support/comment
	* @method POST
	* @return JSON with info about server Info
	*/
	public function commentPost() {
		$json['succeed'] = true;
		
		$id_device = '';
		if(HTTPRequest::postExist('id_device')) {
			$id_device = HTTPRequest::postData('id_device');
		} else {
			$json['succeed'] = false;
		}

		$content = '';
		if(HTTPRequest::postExist('content')) {
			$content = HTTPRequest::postData('content');
		} else {
			$json['succeed'] = false;
		}

		$is_dev_response = 0;
		if(HTTPRequest::postExist('is_dev_response')) {
			$is_dev_response = filter_var(HTTPRequest::postData('is_dev_response'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
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

		$android_app_notification_id = '';
		if(HTTPRequest::postExist('android_app_notification_id')) {
			$android_app_notification_id = HTTPRequest::postData('android_app_notification_id');
		} else {
			//$json['succeed'] = false;
		}

		$android_device_version_sdk = '';
		if(HTTPRequest::postExist('android_device_version_sdk')) {
			$android_device_version_sdk = HTTPRequest::postData('android_device_version_sdk');
		} else {
			$json['succeed'] = false;
		}
		$android_device_model = '';
		if(HTTPRequest::postExist('android_device_model')) {
			$android_device_model = HTTPRequest::postData('android_device_model');
		} else {
			//$json['succeed'] = false;
		}
		$_android_device_manufacturer = '';
		if(HTTPRequest::postExist('android_device_manufacturer')) {
			$android_device_manufacturer = HTTPRequest::postData('android_device_manufacturer');
		} else {
			//$json['succeed'] = false;
		}
		$_android_device_display_language = '';
		if(HTTPRequest::postExist('android_device_display_language')) {
			$android_device_display_language = HTTPRequest::postData('android_device_display_language');
		} else {
			//$json['succeed'] = false;
		}
		$_android_device_country = '';
		if(HTTPRequest::postExist('android_device_country')) {
			$android_device_country = HTTPRequest::postData('android_device_country');
		} else {
			//$json['succeed'] = false;
		}

		$supportComment = new SupportComment(array(
			'id'=> 0,
			'id_device' => $id_device,
			'is_dev_response' => intval($is_dev_response),
			'content' => $content,
			'date_creation' => date('Y-m-d H:i:s'),

			'android_app_version_code' => $android_app_version_code,
			'android_app_version_name' => $android_app_version_name,
			'android_app_notification_id' => $android_app_notification_id,

			'android_device_version_sdk' => $android_device_version_sdk,
			'android_device_model' => $android_device_model,
			'android_device_manufacturer' => $android_device_manufacturer,
			'android_device_display_language' => $android_device_display_language,
			'android_device_country' => $android_device_country
			));
		$supportManager = $this->getManagerof('Support');
		$supportManager->add($supportComment);

		$list_comment = $supportManager->getAllByIdDevice($id_device);
		$result = [];
		foreach ($list_comment as $comment) {
			$comment_array = $comment->toArray();
			$result[] = $comment_array;
		}

		$json['result'] = $result;
		$json['debug'] = '$id_device:' . $id_device . ' $content:' . $content . ' $is_dev_response:' . $is_dev_response;

		HTTPResponse::send(json_encode($json));
	}
}