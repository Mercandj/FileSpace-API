<?php
namespace lib\Models;
use \lib\Entities\ConversationMessage;

class ConversationMessageManager extends \lib\Manager {
	protected static $instance;

	public function add(ConversationMessage $message) {
		$id_user = $message->getId_user();
		$id_conversation = $message->getId_conversation();
		$content = $message->getContent();
		$date_creation = $message->getDate_creation();
		$visibility = $message->getVisibility();
		$public = $message->getPublic();

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `conversation_message`(id_user,id_conversation,content,date_creation,visibility,public) VALUES (:id_user, :id_conversation, :content, :date_creation, :visibility, :public)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':id_conversation',$id_conversation,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM conversation_message WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(ConversationMessage $message) {		
		$id = $message->getId();
		$content = $message->getContent();

		$req = $this->_db->prepare('UPDATE conversation_message SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM conversation_message WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new ConversationMessage($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM conversation_message');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new ConversationMessage($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM conversation_message WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}