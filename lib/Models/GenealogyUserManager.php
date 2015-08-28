<?php
namespace lib\Models;
use \lib\Entities\GenealogyUser;

class GenealogyUserManager extends \lib\Manager {
	protected static $instance;

	public function add(GenealogyUser $genealogyUser) {
		$first_name_1 = $genealogyUser->getFirst_name_1();
		$first_name_2 = $genealogyUser->getFirst_name_2();
		$first_name_3 = $genealogyUser->getFirst_name_3();
		$last_name = $genealogyUser->getLast_name();
		$date_creation = $genealogyUser->getDate_creation();
		$is_man = $genealogyUser->getIs_man();
		$date_birth = $genealogyUser->getDate_birth();
		$date_death = $genealogyUser->getDate_death();
		
		$req = $this->_db->prepare('INSERT INTO `genealogy_user`(first_name_1,first_name_2,first_name_3,last_name,is_man,date_birth,date_death,date_creation) VALUES (:first_name_1, :first_name_2, :first_name_3, :last_name, :is_man, :date_birth, :date_death, :date_creation)');
		$req->bindParam(':first_name_1',$first_name_1,\PDO::PARAM_STR);
		$req->bindParam(':first_name_2',$first_name_2,\PDO::PARAM_STR);
		$req->bindParam(':first_name_3',$first_name_3,\PDO::PARAM_STR);
		$req->bindParam(':last_name',$last_name,\PDO::PARAM_STR);
		$req->bindParam(':is_man',$is_man,\PDO::PARAM_INT);
		$req->bindParam(':date_birth',$date_birth,\PDO::PARAM_STR);
		$req->bindParam(':date_death',$date_death,\PDO::PARAM_STR);
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

	public function updateFather(GenealogyUser $genealogyUser) {		
		$id = $genealogyUser->getId();
		$id_father = $genealogyUser->getId_father();

		$req = $this->_db->prepare('UPDATE genealogy_user SET id_father = :id_father WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':id_father',$id_father,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateMother(GenealogyUser $genealogyUser) {		
		$id = $genealogyUser->getId();
		$id_mother = $genealogyUser->getId_mother();

		$req = $this->_db->prepare('UPDATE genealogy_user SET id_mother = :id_mother WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':id_mother',$id_mother,\PDO::PARAM_INT);
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