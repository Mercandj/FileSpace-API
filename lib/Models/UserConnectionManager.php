<?php
namespace lib\Models;
use \lib\Entities\UserConnection;

class UserConnectionManager extends \lib\Manager {
	protected static $instance;

	public function add(UserConnection $userConnection) {
		$id_user = intval($userConnection->getId_user());
		$date_creation = $userConnection->getDate_creation();
		$visibility = intval($userConnection->getVisibility());
		$public = intval($userConnection->getPublic());
		$succeed = intval($userConnection->getSucceed());
		$request_uri = $userConnection->getRequest_uri();

		if(empty($id_user)) 		$id_user = -1;
		if(empty($visibility)) 		$visibility = 1;
		if(empty($public)) 			$public = 0;
		if(empty($succeed)) 		$succeed = 0;
		
		$req = $this->_db->prepare('INSERT INTO user_connection(id_user,date_creation,visibility,public,succeed,request_uri) VALUES (:id_user, :date_creation, :visibility, :public, :succeed, :request_uri)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->bindParam(':succeed',$succeed,\PDO::PARAM_INT);
		$req->bindParam(':request_uri',$request_uri,\PDO::PARAM_STR);
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

	public function getByUserId($id_user) {
		$req = $this->_db->prepare('SELECT * FROM user_connection WHERE id_user = :id_user');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->execute();
		while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new UserConnection($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function deleteByUserId($id_user) {
		$req = $this->_db->prepare('DELETE * FROM user_connection WHERE id_user = :id_user');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->execute();
    	$req->closeCursor();
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM user_connection ORDER BY date_creation DESC');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new UserConnection($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function getAllPage($per_page, $page) {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM user_connection ORDER BY date_creation DESC LIMIT '.$per_page.' OFFSET '.(($page-1)*$per_page));
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