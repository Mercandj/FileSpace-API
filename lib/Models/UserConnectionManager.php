<?php
namespace lib\Models;
use \lib\Entities\UserConnection;

class UserConnectionManager extends \lib\Manager {
	protected static $instance;

	public function add(UserConnection $userConnection) {
		$id_user = $userConnection->getId_user();
		$date_creation = $userConnection->getDate_creation();
		$visibility = $userConnection->getVisibility();
		$public = $userConnection->getPublic();
		$succeed = $userConnection->getSucceed();

		$id_user = -1;
		$visibility = 1;
		$public = 0;
		$succeed = 0;
		
		$req = $this->_db->prepare('INSERT INTO user_connection(id_user,date_creation,visibility,public,succeed) VALUES (:id_user, :date_creation, :visibility, :public; :succeed)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->bindParam(':succeed',$succeed,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM user_connection WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(UserConnection $userConnection) {		
		$id = $userConnection->getId();
		$content = $userConnection->getContent();

		$req = $this->_db->prepare('UPDATE user_connection SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM user_connection WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new FileUpload($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM user_connection');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new UserConnection($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM user_connection WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}