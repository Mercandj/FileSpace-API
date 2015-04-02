<?php
namespace lib\Models;
use \lib\Entities\Conversation;

class ConversationManager extends \lib\Manager {
	protected static $instance;

	public function add(Conversation $conversation) {
		$id_user = $conversation->getId_user();
		$content = $conversation->getContent();
		$date_creation = $conversation->getDate_creation();
		$visibility = $conversation->getVisibility();
		$public = $conversation->getPublic();
		$to_all = $conversation->getTo_all();
		$to_yourself = $conversation->getTo_yourself();

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;
		if(empty($to_all)) 		$to_all = 0;
		if(empty($to_yourself)) $to_yourself = 0;
		
		$req = $this->_db->prepare('INSERT INTO `conversation`(id_user,content,date_creation,visibility,public,to_all,to_yourself) VALUES (:id_user, :content, :date_creation, :visibility, :public, :to_all, :to_yourself)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->bindParam(':to_all',$to_all,\PDO::PARAM_INT);
		$req->bindParam(':to_yourself',$to_yourself,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM conversation WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(Conversation $conversation) {		
		$id = $conversation->getId();
		$content = $conversation->getContent();

		$req = $this->_db->prepare('UPDATE conversation SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getByDate(Conversation $conversation) {
		$id_user = $conversation->getId_user();
		$date_creation = $conversation->getDate_creation();

		$req = $this->_db->prepare('SELECT * FROM conversation WHERE id_user = :id_user AND date_creation = :date_creation');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->bindParam(':date_creation', $date_creation, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new Conversation($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM conversation WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new Conversation($donnee);
	}

	public function getAll() {
		$conversation = [];
		$req = $this->_db->prepare('SELECT * FROM conversation');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$conversation[] = new Conversation($donnees);
	    $req->closeCursor();
	    return $conversation;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM conversation WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}