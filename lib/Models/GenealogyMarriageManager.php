<?php
namespace lib\Models;
use \lib\Entities\GenealogyMarriage;

class GenealogyMarriageManager extends \lib\Manager {
	protected static $instance;

	public function add(GenealogyMarriage $genealogyMarriage) {
		$id_person_husband = $genealogyMarriage->getId_person_husband();
		$id_person_wife = $genealogyMarriage->getId_person_wife();
		$date = $genealogyMarriage->getDate();
		$date_creation = $genealogyMarriage->getDate_creation();
		$description = $genealogyMarriage->getDescription();
		
		$req = $this->_db->prepare('INSERT INTO `genealogy_marriage`(id_person_husband,id_person_wife,date,date_creation,description) VALUES (:id_person_husband, :id_person_wife, :date, :date_creation, :description)');
		$req->bindParam(':id_person_husband',$id_person_husband,\PDO::PARAM_INT);
		$req->bindParam(':id_person_wife',$id_person_wife,\PDO::PARAM_INT);
		$req->bindParam(':date',$date,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':description',$description,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function update(GenealogyMarriage $genealogyMarriage) {		
		$id = $genealogyMarriage->getId();
		$id_person_husband = $genealogyMarriage->getId_person_husband();
		$id_person_wife = $genealogyMarriage->getId_person_wife();
		$date = $genealogyMarriage->getDate();
		$date_creation = $genealogyMarriage->getDate_creation();
		$description = $genealogyMarriage->getDescription();

		$req = $this->_db->prepare('UPDATE genealogy_marriage SET id_person_husband = :id_person_husband, id_person_wife = :id_person_wife, date = :date, date_creation = :date_creation, description = :description WHERE id = :id');
		$req->bindParam(':id_person_husband',$id_person_husband,\PDO::PARAM_INT);
		$req->bindParam(':id_person_wife',$id_person_wife,\PDO::PARAM_INT);
		$req->bindParam(':date',$date,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':description',$description,\PDO::PARAM_STR);
		$req->bindParam(':id', $id, \PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM genealogy_marriage WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(GenealogyMarriage $genealogyPerson) {		
		$id = $genealogyPerson->getId();
		$content = $genealogyPerson->getContent();

		$req = $this->_db->prepare('UPDATE genealogy_marriage SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM genealogy_marriage WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new GenealogyMarriage($donnee);
	}
	
	public function getAll($psearch = "") {
		$users = [];
		$search = '%'.$psearch.'%';
		$req = $this->_db->prepare('SELECT * FROM genealogy_marriage WHERE location LIKE :search ORDER BY date_birth DESC');
		$req->bindParam(':search', $search, \PDO::PARAM_STR);
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new GenealogyMarriage($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM genealogy_marriage WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}

	public function count() {
		$req = $this->_db->query('SELECT COUNT(id) AS countAll FROM genealogy_marriage');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}

	public function biggerDate_creation() {
		$req = $this->_db->query('SELECT MAX(date_creation) AS countAll FROM genealogy_marriage');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}
}