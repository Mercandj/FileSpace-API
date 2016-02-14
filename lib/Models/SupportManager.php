<?php
namespace lib\Models;
use \lib\Entities\SupportComment;

class SupportManager extends \lib\Manager {
	protected static $instance;

	public function add(SupportComment $support_comment) {
		
		$to_insert['id_device'] 					= $support_comment->getId_device();
		$to_insert['is_dev_response'] 				= intval($support_comment->getIs_dev_response());
		$to_insert['content'] 						= $support_comment->getContent();
		$to_insert['date_creation'] 				= $support_comment->getDate_creation();
		$to_insert['visibility'] 					= intval($support_comment->getVisibility());
		$to_insert['public'] 						= intval($support_comment->getPublic());

		if(empty($to_insert['visibility'])) 		$to_insert['visibility'] = 1;
		if(empty($to_insert['public'])) 			$to_insert['public'] = 0;

		$to_insert['android_app_version_code'] 		= $support_comment->getAndroid_app_version_code();
		$to_insert['android_app_version_name'] 		= $support_comment->getAndroid_app_version_name();
		$to_insert['android_app_notification_id'] 	= $support_comment->getAndroid_app_notification_id();
		$to_insert['android_device_version_sdk'] 	= $support_comment->getAndroid_device_version_sdk();

		$req_str = 'INSERT INTO (';
		$numItems = count($to_insert);
		$i = 0;
		foreach ($to_insert as $key => $value) {
			if(++$i === $numItems) {
    			// last index
				$req_str .= $key . ') VALUES (';
			} else {
				$req_str .= $key . ', ';
			}
		}
		$i = 0;
		foreach ($to_insert as $key => $value) {
			if(++$i === $numItems) {
    			// last index
				$req_str .= ':' . $key . ')';
			} else {
				$req_str .= ':' . $key . ', ';
			}
		}
		
		$req = $this->_db->prepare($req_str);

		$req->bindParam(':id_device',					$to_insert['id_device'],					\PDO::PARAM_STR);
		$req->bindParam(':is_dev_response',				$to_insert['is_dev_response'],				\PDO::PARAM_INT);
		$req->bindParam(':content',						$to_insert['content'],						\PDO::PARAM_STR);
		$req->bindParam(':date_creation',				$to_insert['date_creation'],				\PDO::PARAM_STR);
		$req->bindParam(':visibility',					$to_insert['visibility'],					\PDO::PARAM_INT);
		$req->bindParam(':public',						$to_insert['public'],						\PDO::PARAM_INT);
		$req->bindParam(':android_app_version_code',	$to_insert['android_app_version_code'],		\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_name',	$to_insert['android_app_version_name'],		\PDO::PARAM_STR);
		$req->bindParam(':android_app_notification_id',	$to_insert['android_app_notification_id'],	\PDO::PARAM_STR);
		$req->bindParam(':android_device_version_sdk',	$to_insert['android_device_version_sdk'],	\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM support_comment WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_STR);
    	$req->execute();
		$req->closeCursor();
	}

	public function getAllIdDevice() {
		$comments = [];
		$req = $this->_db->prepare('SELECT id, id_device, COUNT(*) AS nb_comments_with_this_id_device FROM support_comment GROUP BY id_device');
    	$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
	    	$comments[] = new SupportComment($donnees);
	    }
	    $req->closeCursor();
	    return $comments;
	}

	public function getAllByIdDevice($id_device) {
		$comments = [];
		$req = $this->_db->prepare('SELECT * FROM support_comment WHERE id_device = :id_device ORDER BY date_creation ASC');
    	$req->bindParam(':id_device', $id_device, \PDO::PARAM_STR);
    	$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
	    	$comments[] = new SupportComment($donnees);
	    }
	    $req->closeCursor();
	    return $comments;
	}
}