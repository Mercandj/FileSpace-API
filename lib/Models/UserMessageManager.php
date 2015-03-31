<?php
namespace lib\Models;
use \lib\Entities\UserMessage;

class UserMessageManager extends \lib\Manager {
	protected static $instance;

	public function addToUser(UserMessage $message) {
		$id_user = $message->getId_user();
		$id_user_recipient = $message->getId_user_recipient();
		$content = $message->getContent();
		$date_creation = $message->getDate_creation();
		$visibility = $message->getVisibility();
		$public = $message->getPublic();

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO user_message(id_user,id_user_recipient,content,date_creation,visibility,public) VALUES (:id_user, :id_user_recipient, :content, :date_creation, :visibility, :public)');
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
		$req = $this->_db->prepare('DELETE FROM user_message WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(UserMessage $message) {		
		$id = $message->getId();
		$content = $message->getContent();

		$req = $this->_db->prepare('UPDATE user_message SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM user_message WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new UserMessage($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM user_message');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new UserMessage($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM user_message WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}