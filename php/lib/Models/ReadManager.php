<?php
namespace lib\Models;

class ReadManager extends \lib\Manager{
	protected static $instance;

	public function add($id_user,$id_categorie){
		$req = $this->_db->prepare('INSERT INTO read(id,id_user,id_categorie) VALUES (NULL, :id_user,:id_categorie)');
		$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
		$req->bindParam(':id_categorie', $id_categorie, \PDO::PARAM_INT);
		$req->execute();		
		$req->closeCursor();
	}

	public function delete($id_user){
		$req = $this->_db->prepare('DELETE FROM read WHERE id_user = :id_user');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function canAccess($id_user,$id_categorie){
		$req = $this->_db->prepare('SELECT id FROM read WHERE id_user = :id_user AND id_categorie = :id_categorie');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->bindParam(':id_categorie', $id_categorie, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();

    	return ($donnee['id'] != NULL);
	}

	public function getAllAccess($id_user){
		$access =array();

		$req = $this->_db->prepare('SELECT id_categorie FROM read WHERE id_user = :id_user');
    	$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
    	$req->execute();

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)){
	    	$access[] = $donnees['id_categorie'];
	    }
    	$req->closeCursor();

    	return $access;
	}

}