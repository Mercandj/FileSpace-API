<?php
namespace lib\Models;
use \lib\Entities\GenealogyPerson;

class GenealogyPersonManager extends \lib\Manager {
	protected static $instance;

	public function add(GenealogyPerson $genealogyUser) {
		$first_name_1 = $genealogyUser->getFirst_name_1();
		$first_name_2 = $genealogyUser->getFirst_name_2();
		$first_name_3 = $genealogyUser->getFirst_name_3();
		$last_name = $genealogyUser->getLast_name();
		$date_creation = $genealogyUser->getDate_creation();
		$is_man = $genealogyUser->getIs_man();
		$date_birth = $genealogyUser->getDate_birth();
		$date_death = $genealogyUser->getDate_death();
		$description = $genealogyUser->getDescription();
		$id_father = $genealogyUser->getId_father();
		$id_mother = $genealogyUser->getId_mother();
		
		$req = $this->_db->prepare('INSERT INTO `genealogy_person`(first_name_1,first_name_2,first_name_3,last_name,is_man,date_birth,date_death,date_creation,description,id_father,id_mother) VALUES (:first_name_1, :first_name_2, :first_name_3, :last_name, :is_man, :date_birth, :date_death, :date_creation, :description, :id_father, :id_mother)');
		$req->bindParam(':first_name_1',$first_name_1,\PDO::PARAM_STR);
		$req->bindParam(':first_name_2',$first_name_2,\PDO::PARAM_STR);
		$req->bindParam(':first_name_3',$first_name_3,\PDO::PARAM_STR);
		$req->bindParam(':last_name',$last_name,\PDO::PARAM_STR);
		$req->bindParam(':is_man',$is_man,\PDO::PARAM_INT);
		$req->bindParam(':date_birth',$date_birth,\PDO::PARAM_STR);
		$req->bindParam(':date_death',$date_death,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':description',$description,\PDO::PARAM_STR);
		$req->bindParam(':id_father',$id_father,\PDO::PARAM_INT);
		$req->bindParam(':id_mother',$id_mother,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function update(GenealogyPerson $genealogyPerson) {		
		$id = $genealogyPerson->getId();
		$first_name_1 = $genealogyPerson->getFirst_name_1();
		$first_name_2 = $genealogyPerson->getFirst_name_2();
		$first_name_3 = $genealogyPerson->getFirst_name_3();
		$last_name = $genealogyPerson->getLast_name();
		$date_creation = $genealogyPerson->getDate_creation();
		$is_man = $genealogyPerson->getIs_man();
		$date_birth = $genealogyPerson->getDate_birth();
		$date_death = $genealogyPerson->getDate_death();
		$description = $genealogyPerson->getDescription();
		$id_father = $genealogyPerson->getId_father();
		$id_mother = $genealogyPerson->getId_mother();

		$req = $this->_db->prepare('UPDATE genealogy_person SET first_name_1 = :first_name_1, first_name_2 = :first_name_2, first_name_3 = :first_name_3, last_name = :last_name, is_man = :is_man, date_birth = :date_birth, date_death = :date_death, date_creation = :date_creation, description = :description, id_father = :id_father, id_mother = :id_mother WHERE id = :id');
		$req->bindParam(':first_name_1',$first_name_1,\PDO::PARAM_STR);
		$req->bindParam(':first_name_2',$first_name_2,\PDO::PARAM_STR);
		$req->bindParam(':first_name_3',$first_name_3,\PDO::PARAM_STR);
		$req->bindParam(':last_name',$last_name,\PDO::PARAM_STR);
		$req->bindParam(':is_man',$is_man,\PDO::PARAM_INT);
		$req->bindParam(':date_birth',$date_birth,\PDO::PARAM_STR);
		$req->bindParam(':date_death',$date_death,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':description',$description,\PDO::PARAM_STR);
		$req->bindParam(':id_father',$id_father,\PDO::PARAM_INT);
		$req->bindParam(':id_mother',$id_mother,\PDO::PARAM_INT);
		$req->bindParam(':id', $id, \PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM genealogy_person WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateFather(GenealogyPerson $genealogyPerson) {		
		$id = $genealogyPerson->getId();
		$id_father = $genealogyPerson->getId_father();

		$req = $this->_db->prepare('UPDATE genealogy_person SET id_father = :id_father WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':id_father',$id_father,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateMother(GenealogyPerson $genealogyPerson) {		
		$id = $genealogyPerson->getId();
		$id_mother = $genealogyPerson->getId_mother();

		$req = $this->_db->prepare('UPDATE genealogy_person SET id_mother = :id_mother WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':id_mother',$id_mother,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateContent(GenealogyPerson $genealogyPerson) {		
		$id = $genealogyPerson->getId();
		$content = $genealogyPerson->getContent();

		$req = $this->_db->prepare('UPDATE genealogy_person SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM genealogy_person WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new GenealogyPerson($donnee);
	}

	public function getChildren($id) {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM genealogy_person WHERE id_father = :id_father OR id_mother = :id_mother ORDER BY date_birth DESC');
		$req->bindParam(':id_father', $id, \PDO::PARAM_INT);
		$req->bindParam(':id_mother', $id, \PDO::PARAM_INT);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new GenealogyPerson($donnees);
	    $req->closeCursor();
	    return $users;
	}
	
	public function getAll($psearch = "") {
		$users = [];
		$search = '%'.$psearch.'%';
		$req = $this->_db->prepare('SELECT * FROM genealogy_person WHERE last_name LIKE :search ORDER BY date_birth DESC');
		$req->bindParam(':search', $search, \PDO::PARAM_STR);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new GenealogyPerson($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM genealogy_person WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}

	public function count() {
		$req = $this->_db->query('SELECT COUNT(id) AS countAll FROM genealogy_person');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}

	public function biggerDate_creation() {
		$req = $this->_db->query('SELECT MAX(date_creation) AS countAll FROM genealogy_person');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}
}