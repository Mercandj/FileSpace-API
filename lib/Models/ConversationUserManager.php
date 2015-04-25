<?php
namespace lib\Models;
use \lib\Entities\ConversationUser;

class ConversationUserManager extends \lib\Manager {
	protected static $instance;

	public function add(ConversationUser $user) {
		$id_user = $user->getId_user();
		$id_conversation = $user->getId_conversation();
		$content = $user->getContent();
		$date_creation = $user->getDate_creation();
		$visibility = $user->getVisibility();
		$public = $user->getPublic();

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `conversation_user`(id_user,id_conversation,content,date_creation,visibility,public) VALUES (:id_user, :id_conversation, :content, :date_creation, :visibility, :public)');
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
		$req = $this->_db->prepare('DELETE FROM conversation_user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(ConversationUser $user) {		
		$id = $user->getId();
		$content = $user->getContent();

		$req = $this->_db->prepare('UPDATE conversation_user SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM conversation_user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new ConversationUser($donnee);
	}

	public function containsOtherUsers($id_conversation, $id_user_array) {
		$reqWhere = '';
		foreach ($id_user_array as $id_user) {
			$reqWhere .= ' AND id_user != '.intval($id_user);
		}
		$conversationUser = [];
		$req = $this->_db->prepare('SELECT * FROM conversation_user WHERE id_conversation = :id_conversation'.$reqWhere);
		$req->bindParam(':id_conversation', $id_conversation, \PDO::PARAM_INT);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$conversationUser[] = new ConversationUser($donnees);
	    $req->closeCursor();
	    return !empty($conversationUser);
	}

	public function getAllByUserId($id_user) {
		$conversationUser = [];
		$req = $this->_db->prepare('SELECT * FROM conversation_user WHERE id_user = :id_user');
		$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$conversationUser[] = new ConversationUser($donnees);
	    $req->closeCursor();
	    return $conversationUser;
	}

	public function getAllByConversationId($id_conversation) {
		$conversationUser = [];
		$req = $this->_db->prepare('SELECT * FROM conversation_user WHERE id_conversation = :id_conversation');
		$req->bindParam(':id_conversation', $id_conversation, \PDO::PARAM_INT);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$conversationUser[] = new ConversationUser($donnees);
	    $req->closeCursor();
	    return $conversationUser;
	}

	public function getAll() {
		$conversationUser = [];
		$conversationUser = $this->_db->prepare('SELECT * FROM conversation_user');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$conversationUser[] = new ConversationUser($donnees);
	    $req->closeCursor();
	    return $conversationUser;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM conversation_user WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}