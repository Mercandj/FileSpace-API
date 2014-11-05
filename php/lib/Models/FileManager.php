<?php
namespace lib\Models;
use \lib\Entities\File;

class FileManager extends \lib\Manager {
	protected static $instance;

	public function add(File $file) {
		$url = $file->getUrl();
		$size = $file->getSize();
		$visibility = $file->getVisibility();
		$date_create = date('Y-m-d H:i:s');

		$req = $this->_db->prepare('INSERT INTO file(url,size,visibility,date_create) VALUES (:url, :size, :visibility, :date_create)');
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':size',$size,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_STR);
		$req->bindParam(':date_create',$date_create,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(File $file) {
		
		$id = $file->getId();
		$url = $file->getUrl();
		$size = $file->getSize();
		$visibility = $file->getVisibility();

		$req = $this->_db->prepare('UPDATE file SET url = :url, size = :size, visibility = :visibility WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':size',$size,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function get($url) {
		$req = $this->_db->prepare('SELECT id,url,size,visibility FROM file WHERE url = :url');
    	$req->bindParam(':url', $url, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT id,url,size,visibility FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	public function getAll() {
		$file = array();

		$req = $this->_db->query('SELECT id,url,size FROM file');

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);
	    $req->closeCursor();
	    return $file;
	}

	public function exist($url) {
		$req = $this->_db->prepare('SELECT id FROM file WHERE url = :url');
    	$req->bindParam(':url', $url,\PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}