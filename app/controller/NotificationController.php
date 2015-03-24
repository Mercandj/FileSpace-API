<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class NotificationController extends \lib\Controller {

	/**
	 * Post a notification
	 * @uri    /notification
	 * @method POST
	 * @param  message    REQUIRED
	 * @param  all        OPTIONAL
	 * @param  allAdmin   OPTIONAL
	 */
	public function post() {

		$json['succeed'] = false;
		$userManager = $this->getManagerof('User');
		$id_user = $this->_app->_config->getId_user();

		$all = false;
		if(HTTPRequest::postExist('all'))
			$all = HTTPRequest::postData('all');

		$allAdmin = false;
		if(HTTPRequest::postExist('allAdmin'))
			$allAdmin = HTTPRequest::postData('allAdmin');

		if(!HTTPRequest::postExist('message')) {
			$json['toast'] = "No message.";
		}

		else if(!$userManager->exist($user->getUsername())) {
			$json['toast'] = "Wrong user.";
		}

		else if($all) {
			$users = $userManager->getAll();

			foreach ($users as $user) {
				$gcmRegID  = $user->getAndroid_id();

				if (isset($gcmRegID)) {   
					$gcmRegIds = array($gcmRegID);
					$message = array("m" => HTTPRequest::postData('message'));
					$pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
					$json['status'] = $pushStatus;
				} 
			}
			$json['succeed'] = true;
		}

		else if($allAdmin) {
			$users = $userManager->getAllAdmin();

			foreach ($users as $user) {
				$gcmRegID  = $user->getAndroid_id();

				if (isset($gcmRegID)) {   
					$gcmRegIds = array($gcmRegID);
					$message = array("m" => HTTPRequest::postData('message'));
					$pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
					$json['status'] = $pushStatus;
				} 
			}
			$json['succeed'] = true;
		}

		else {
			$gcmRegIds = array($userManager->getById($id_user)->getAndroid_id());
			$message = array("m" => HTTPRequest::postData('message'));
			$pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
			$json['status'] = $pushStatus;
			$json['succeed'] = true;			
		}

		HTTPResponse::send(json_encode($json));
	}

	//generic php function to send GCM push notification
	function sendPushNotificationToGCM($registatoin_ids, $message) {
		//Google cloud messaging GCM-API url
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $registatoin_ids,
			'data' => $message,
		); 
		$headers = array(
			'Authorization: key=' . $this->_app->_config->get('google_api_key'),
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);      
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
}