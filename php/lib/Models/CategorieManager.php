<?php
namespace lib\Models;
use \lib\Entities\Categorie;

class CategorieManager extends \lib\Manager{
	protected static $instance;

	public function add(Categorie $categorie){

		$title = $categorie->getTitle();
		$details = $categorie->getDetails();
		$thumb = $categorie->getThumb();

		$req = $this->_db->prepare('INSERT INTO categorie(id,title,details,thumb) VALUES (NULL, :title,:details,:thumb)');
		$req->bindParam(':title',$title,\PDO::PARAM_STR);
		$req->bindParam(':details',$details,\PDO::PARAM_STR);
		$req->bindParam(':thumb',$thumb,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id){
		$req = $this->_db->prepare('DELETE FROM categorie WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(Categorie $categorie){
		
		$id = $categorie->getId();
		$title = $categorie->getTitle();
		$details = $categorie->getDetails();
		$thumb = $categorie->getThumb();

		$req = $this->_db->prepare('UPDATE categorie SET title = :title, details = :details, thumb = :thumb WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':title',$title,\PDO::PARAM_STR);
		$req->bindParam(':details',$details,\PDO::PARAM_STR);
		$req->bindParam(':thumb',$thumb,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function get($id){
		$req = $this->_db->prepare('SELECT id,title,details,thumb FROM categorie WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();

    	return ($donnee['id'] != NULL) ? new Categorie($donnee) : false;
	}

	public function getAll(){
		$categorie = array();

		$req = $this->_db->query('SELECT id,title,details,thumb FROM categorie ORDER BY id DESC');

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)){
	    	$categorie[] = new Categorie($donnees);
	    }
	    $req->closeCursor();
	    return $categorie;
	}

	public function isEmpty($id){
		$req = $this->_db->prepare('SELECT id FROM news WHERE id_categorie = :id');
		$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetchColumn();
    	$req->closeCursor();
    	return ($donnee == 0) ? true : false;
	}
}