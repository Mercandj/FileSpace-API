<?php
namespace lib\Models;
use \lib\Entities\UserGroup;

class UserGroupManager extends \lib\Manager {
	protected static $instance;

	public function addToUser(UserGroup $usergroup) {
		$id_user = $usergroup->getId_user();
		$id_user_recipient = $usergroup->getId_user_recipient();
		$content = $usergroup->getContent();
		$date_creation = $usergroup->getDate_creation();
		$visibility = $usergroup->getVisibility();
		$public = $usergroup->getPublic();

		if(empty($visibility)) 			$visibility = 1;
		if(empty($public)) 				$public = 0;
		if(empty($id_user_recipient)) 	$id_user_recipient = -1;
		
		$req = $this->_db->prepare('INSERT INTO `user_group`(id_user,id_user_recipient,content,date_creation,visibility,public) VALUES (:id_user, :id_user_recipient, :content, :date_creation, :visibility, :public)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':id_user_recipient',$id_user_recipient,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM user_group WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(UserGroup $usergroup) {		
		$id = $usergroup->getId();
		$content = $usergroup->getContent();

		$req = $this->_db->prepare('UPDATE user_group SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM user_group WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new UserGroup($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM user_group');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new UserGroup($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM user_group WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}