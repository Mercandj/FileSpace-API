<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\Conversation;
use \lib\Entities\ConversationUser;
use \lib\Entities\ConversationMessage;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class ConversationController extends \lib\Controller {

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
	 * id = id_user_recipient
	 * @uri    /user_conversation
	 * @method POST
	 * @param  message    REQUIRED
	 * @param  all        OPTIONAL
	 * @param  allAdmin   OPTIONAL
	 */
	public function post($id_user_recipient) {

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

		else if(!$userManager->existById($id_user_recipient)) {
			$json['toast'] = "Wrong user recipient.";
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

			$date = date('Y-m-d H:i:s');
			$conversation = null;
			$id_user_array = [];
			$conversations_array = [];
			$conversations_pot = [];
			$id_user_array[] = intval($id_user);
			$id_user_array[] = intval($id_user_recipient);

			if(intval($id_user) == intval($id_user_recipient)) {
				$conversations = $conversationUserManager->getAllByUserId($id_user);

				foreach ($conversations as $conversation_) {
					if (! ($conversation_ instanceof ConversationUser))
						break;
					$conversation_tmp = $conversationManager->getById($conversation_->getId_conversation());
					if( $conversation_tmp->getTo_yourself() ) {
						$conversation = $conversation_tmp;
						break;
					}
				}

				if($conversation == null) {

					$conversation = new Conversation(array(
						'id'=> 0,
						'id_user' => intval($id_user),
						'date_creation' => $date,
						'to_yourself' => 1
					));
					$conversationManager->add($conversation);
					$conversation = $conversationManager->getByDate($conversation);
					$id_conversation = intval($conversation->getId());

					$conversationUser = new ConversationUser(array(
						'id'=> 0,
						'id_user' => intval($id_user),
						'id_conversation' => $id_conversation,
						'date_creation' => $date
					));
					$conversationUserManager->add($conversationUser);
					
				}

			}
			else {

				for($i = 0; $i < count($id_user_array); $i++) {
					$conversations_array[] = $conversationUserManager->getAllByUserId($id_user_array[$i]);
				}

				for($i1 = 0; $i1 < count($conversations_array); $i1++) {
					$conversations1 = $conversations_array[$i1];

					for($j1 = 0; $j1 < count($conversations1); $j1++) {
						$conversation1 = $conversations1[$j1];

						for($i2 = 0; $i2 < count($conversations_array); $i2++) {

							// if user !=
							if($i1 != $i2) {

								$conversations2 = $conversations_array[$i2];
								for($j2 = 0; $j2 < count($conversations2); $j2++) {
									$conversation2 = $conversations2[$j2];

									if($conversation1->getId_conversation() == $conversation2->getId_conversation()) {

										$conversation_pot = $conversationManager->getById($conversation1->getId_conversation());

										if(intval($conversation_pot->getTo_all()) != 1 && intval($conversation_pot->getTo_yourself()) != 1)
											$conversations_pot[] = $conversation_pot;
									}									
								}
							}
						}
					}
				}

				$json['debug-conversations-pot'] = count($conversations_pot);

				foreach ($conversations_pot as $conversation_) {
					if(!$conversationUserManager->containsOtherUsers($conversation_->getId(), $id_user_array)) {
						$conversation = $conversation_;
						break;
					}
				}

				if($conversation == null) {

					$conversation = new Conversation(array(
						'id'=> 0,
						'id_user' => intval($id_user),
						'date_creation' => $date
					));
					$conversationManager->add($conversation);
					$conversation = $conversationManager->getByDate($conversation);
					$id_conversation = intval($conversation->getId());

					foreach ($id_user_array as $id_user_) {
						$conversationUser = new ConversationUser(array(
							'id'=> 0,
							'id_user' => intval($id_user_),
							'id_conversation' => $id_conversation,
							'date_creation' => $date
						));
						$conversationUserManager->add($conversationUser);
					}
				}
			}

			$conversationMessage = new ConversationMessage(array(
				'id'=> 0,
				'id_user' => intval($id_user),
				'id_conversation' => intval($conversation->getId()),
				'content' => HTTPRequest::postData('message'),
				'date_creation' => $date
			));
			$conversationMessageManager->add($conversationMessage);

			$gcmRegIds = array($userManager->getById($id_user_recipient)->getAndroid_id());
			$message = array("m" => HTTPRequest::postData('message'), "id_conversation" => intval($conversation->getId()));
			$pushStatus = $this->sendPushNotificationToGCM($gcmRegIds, $message);
			$json['status'] = $pushStatus;
			$json['debug-username'] = $userManager->getById($id_user_recipient)->getUsername();
			$json['debug-message'] = $conversationMessage->toArray();
			$json['toast'] = 'Message sent.';
			$json['succeed'] = true;
		}

		HTTPResponse::send(json_encode($json));
	}

	public function get() {
		$result = [];
		$json['succeed'] = false;

		$userManager = $this->getManagerof('User');
		$conversationManager = $this->getManagerof('Conversation');
		$conversationUserManager = $this->getManagerof('ConversationUser');
		$conversationMessageManager = $this->getManagerof('ConversationMessage');

		$id_user = $this->_app->_config->getId_user();
		$admin_user = $userManager->getById($id_user)->isAdmin();


		$list_my_conversation = $conversationUserManager->getAllByUserId($id_user);
		foreach ($list_my_conversation as $my_conversation) {
			$tmp_array = $my_conversation->toArray();

			$users = [];
			$list_tmp_conversation = $conversationUserManager->getAllByConversationId($my_conversation->getId_conversation());
			foreach ($list_tmp_conversation as $tmp_conversation) {
				$tmp_id_user = $tmp_conversation->getId_user();
				if($tmp_id_user != $id_user) {
					$bool = true;
					foreach ($users as $user)
						if($tmp_id_user == $user->getId())
							$bool = false;
					if($bool)
						$users[] = $userManager->getById($tmp_id_user)->toArray();
				}
			}
			if(empty($users)) {
				$conv = $conversationManager->getById($my_conversation->getId_conversation());
				if($conv->getTo_all())
					$tmp_array['to_all'] = true;
				else if($conv->getTo_yourself())
					$tmp_array['to_yourself'] = true;
			}
			$tmp_array['users'] = $users;
			$tmp_array['num_messages'] = count($conversationMessageManager->getAllByConversationId($my_conversation->getId_conversation()));

			$result[] = $tmp_array;
		}

		$json['succeed'] = true;
		$json['result'] = $result;

		HTTPResponse::send(json_encode($json));
	}
}