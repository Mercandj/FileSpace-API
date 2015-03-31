<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\UserMessage;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class UserMessageController extends \lib\Controller {

	//generic php function to send GCM push notification
	private function sendPushNotificationToGCM($registatoin_ids, $message) {
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

	/**
	 * Post a notification
	 * @uri    /notification
	 * @method POST
	 * @param  message    REQUIRED
	 * @param  all        OPTIONAL
	 * @param  allAdmin   OPTIONAL
	 */
	public function post($id) {

		$json['succeed'] = false;
		$userManager = $this->getManagerof('User');
		$userMessageManager = $this->getManagerof('UserMessage');
		$id_user = $this->_app->_config->getId_user();
		$admin_user = $userManager->getById($id_user)->isAdmin();

		$all = false;
		if(HTTPRequest::postExist('all'))
			$all = HTTPRequest::postData('all');

		$allAdmin = false;
		if(HTTPRequest::postExist('allAdmin'))
			$allAdmin = HTTPRequest::postData('allAdmin');

		if(!HTTPRequest::postExist('message')) {
			$json['toast'] = "No message.";
		}

		else if(!$userManager->existById($id)) {
			$json['toast'] = "Wrong user.";
		}

		else if($all && $admin_user) {
			$users = $userManager->getAll();
			$message = HTTPRequest::postData('message');

			foreach ($users as $user) {
				$gcmRegID  = $user->getAndroid_id();

				if (isset($gcmRegID)) {   
					$gcmRegIds = array($gcmRegID);
					$messageArray = array("m" => $message);
					$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $messageArray);
					$json['status'][] = $pushStatus;
				} 
			}
			$json['succeed'] = true;
		}

		else if($allAdmin && $admin_user) {
			$users = $userManager->getAllAdmin();
			$message = HTTPRequest::postData('message');

			foreach ($users as $user) {
				$gcmRegID  = $user->getAndroid_id();

				if (isset($gcmRegID)) {   
					$gcmRegIds = array($gcmRegID);
					$messageArray = array("m" => $message);
					$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $messageArray);
					$json['status'][] = $pushStatus;
				} 
			}
			$json['succeed'] = true;
		}

		else {
			$userMessage = new UserMessage(array(
				'id'=> 0,
				'id_user' => $id_user,
				'id_user_recipient' => $userManager->getById($id),
				'content' => HTTPRequest::postData('message'),
				'date_creation' => date('Y-m-d H:i:s')
			));
			$userMessageManager->addToUser($userMessage);

			$gcmRegIds = array($userManager->getById($id)->getAndroid_id());
			$message = array("m" => HTTPRequest::postData('message'));
			$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $message);
			$json['status'] = $pushStatus;
			$json['debug-username'] = $userManager->getById($id)->getUsername();
			$json['succeed'] = true;			
		}

		HTTPResponse::send(json_encode($json));
	}
}