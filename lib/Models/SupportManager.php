<?php
namespace lib\Models;
use \lib\Entities\SupportComment;

class SupportManager extends \lib\Manager {
	protected static $instance;

	public function add(SupportComment $support_comment) {
		$id_device = $support_comment->getId_device();
		$is_dev_response = intval($support_comment->getIs_dev_response());
		$content = $support_comment->getContent();
		$date_creation = $support_comment->getDate_creation();
		$visibility = intval($support_comment->getVisibility());
		$public = intval($support_comment->getPublic());

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;

		$android_app_version_code = $support_comment->getAndroid_app_version_code();
		$android_app_version_name = $support_comment->getAndroid_app_version_name();
		$android_device_version_sdk = $support_comment->getAndroid_device_version_sdk();
		
		$req = $this->_db->prepare('INSERT INTO `support_comment`(id_device,is_dev_response,content,date_creation,visibility,public,android_app_version_code,android_app_version_name,android_device_version_sdk)'.
			' VALUES (:id_device, :is_dev_response, :content, :date_creation, :visibility, :public, :android_app_version_code, :android_app_version_name, :android_device_version_sdk)');

		$req->bindParam(':id_device',$id_device,\PDO::PARAM_STR);
		$req->bindParam(':is_dev_response',$is_dev_response,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->bindParam(':android_app_version_code',$android_app_version_code,\PDO::PARAM_STR);
		$req->bindParam(':android_app_version_name',$android_app_version_name,\PDO::PARAM_STR);
		$req->bindParam(':android_device_version_sdk',$android_device_version_sdk,\PDO::PARAM_STR);
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
		$req = $this->_db->prepare('SELECT * FROM support_comment WHERE id_device = :id_device');
    	$req->bindParam(':id_device', $id_device, \PDO::PARAM_STR);
    	$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
	    	$comments[] = new SupportComment($donnees);
	    }
	    $req->closeCursor();
	    return $comments;
	}
}