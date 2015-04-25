<?php
namespace app\controller;
use \lib\Entities\User;
use \lib\Entities\Conversation;
use \lib\Entities\ConversationUser;
use \lib\Entities\ConversationMessage;
use \lib\HTTPRequest;
use \lib\HTTPResponse;

class ConversationController extends \lib\Controller {

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

			$result[] = $tmp_array;
		}

		$json['succeed'] = true;
		$json['result'] = $result;

		HTTPResponse::send(json_encode($json));
	}
}