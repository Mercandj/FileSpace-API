<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\File;
use \lib\Entities\Conversation;
use \lib\Entities\ConversationUser;
use \lib\Entities\ConversationMessage;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class ConversationMessageController extends \lib\Controller {

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
	 * @uri    /user_message
	 * @method POST
	 * @param  message    REQUIRED
	 * @param  all        OPTIONAL
	 * @param  allAdmin   OPTIONAL
	 */
	public function post($id_conversation) {

		$json['succeed'] = false;

		$userManager = $this->getManagerof('User');
		$conversationManager = $this->getManagerof('Conversation');
		$conversationUserManager = $this->getManagerof('ConversationUser');
		$conversationMessageManager = $this->getManagerof('ConversationMessage');

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

		else if(!$conversationManager->existById($id_conversation)) {
			$json['toast'] = "Wrong id conversation.";
		}

		else {
			$to_all = false;
			$to_yourself = false;

			$date = date('Y-m-d H:i:s');
			$message = HTTPRequest::postData('message');
			$conversationMessage = new ConversationMessage(array(
				'id'=> 0,
				'id_user' => intval($id_user),
				'id_conversation' => intval($id_conversation),
				'content' => $message,
				'date_creation' => $date
			));
			$conversationMessageManager->add($conversationMessage);

			$users = [];
			$users_array = [];
			$list_tmp_conversation = $conversationUserManager->getAllByConversationId($id_conversation);
			foreach ($list_tmp_conversation as $tmp_conversation) {
				$tmp_id_user = $tmp_conversation->getId_user();
				if($tmp_id_user != $id_user) {
					$bool = true;
					foreach ($users as $user)
						if($tmp_id_user == $user->getId())
							$bool = false;
					if($bool) {
						$users[] = $userManager->getById($tmp_id_user);
						$users_array[] = $userManager->getById($tmp_id_user)->toArray();
					}
				}
			}
			if(empty($users)) {
				$conv = $conversationManager->getById($id_conversation);
				if($conv->getTo_all()) {
					$json['to_all'] = true;
					$to_all = true;
				}
				else if($conv->getTo_yourself()) {
					$json['to_yourself'] = true;
					$to_yourself = true;
				}
			}
			$json['users'] = $users_array;

			if($to_all && $admin_user) {
				// TODO
			}
			else if($to_yourself) {
				$gcmRegIds = array($userManager->getById($id_user)->getAndroid_id());
				$message = array("m" => $message, "id_conversation" => $id_conversation);
				$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $message);
			}
			else {
				foreach ($users as $user) {
					$gcmRegIds = array($user->getAndroid_id());
					$message = array("m" => $message, "id_conversation" => $id_conversation);
					$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $message);
				}
			}

			$json['toast'] = 'Message sent.';
			$json['succeed'] = true;
		}


		HTTPResponse::send(json_encode($json));
	}
	

	/**
	 * id = id_conversation
	 * @uri    /user_message
	 * @method GET
	 */
	public function get($id) {

		$json['succeed'] = false;
		$result = [];

		$userManager = $this->getManagerof('User');
		$fileManager = $this->getManagerof('File');
		$conversationMessageManager = $this->getManagerof('ConversationMessage');

		$list_conversationMessage = $conversationMessageManager->getAllByConversationId($id);
		foreach ($list_conversationMessage as $conversationMessage) {
			$tmp_array = $conversationMessage->toArray();
			$tmp_user = $userManager->getById($conversationMessage->getId_user());
			$tmp_array['user'] = $tmp_user->toArray();
			$id_file_profile_picture = $tmp_user->getId_file_profile_picture();
			if(intval($id_file_profile_picture)!=-1 && $id_file_profile_picture!=null) {
				$file_profile_picture = $fileManager->getById($id_file_profile_picture);
				$tmp_array['user']["file_profile_picture_size"] = $file_profile_picture->getSize();
			}
			$result[] = $tmp_array;
		}

		$json['succeed'] = true;
		$json['result'] = $result;

		HTTPResponse::send(json_encode($json));
	}

	/**
	 * id = id_message
	 * @uri    /user_message
	 * @method DELETE
	 */
	public function delete($id) {

		$json['succeed'] = false;
		$result = [];

		$userManager = $this->getManagerof('User');
		$fileManager = $this->getManagerof('File');
		$conversationMessageManager = $this->getManagerof('ConversationMessage');
		
		$id_user = $this->_app->_config->getId_user();
		$admin_user = $userManager->getById($id_user)->isAdmin();

		if($conversationMessageManager->existById($id)) {
			$conversationMessage = $conversationMessageManager->getById($id);
			if($admin_user || $conversationMessage->getId_user()) {
				$conversationMessageManager->delete($id);
				$json['succeed'] = true;
			}
		}		

		HTTPResponse::send(json_encode($json));
	}
}