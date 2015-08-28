<?php
namespace lib\Models;
use \lib\Entities\GenealogyUser;

class GenealogyUserManager extends \lib\Manager {
	protected static $instance;

	public function add(GenealogyUser $genealogyUser) {
		$first_name_1 = $genealogyUser->getId_user();
		$first_name_2 = $genealogyUser->getId_file();
		$first_name_3 = $genealogyUser->getType();
		$last_name = $genealogyUser->getContent();
		$date_creation = $genealogyUser->getDate_creation();
		
		$req = $this->_db->prepare('INSERT INTO `genealogy_user`(first_name_1,first_name_2,first_name_3,last_name,date_creation) VALUES (:first_name_1, :first_name_2, :first_name_3, :last_name, :date_creation)');
		$req->bindParam(':first_name_1',$first_name_1,\PDO::PARAM_STR);
		$req->bindParam(':first_name_2',$first_name_2,\PDO::PARAM_STR);
		$req->bindParam(':first_name_3',$first_name_3,\PDO::PARAM_STR);
		$req->bindParam(':last_name',$last_name,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM genealogy_user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(GenealogyUser $genealogyUser) {		
		$id = $genealogyUser->getId();
		$content = $genealogyUser->getContent();

		$req = $this->_db->prepare('UPDATE genealogy_user SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM genealogy_user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new GenealogyUser($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM genealogy_user');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new GenealogyUser($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM genealogy_user WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}