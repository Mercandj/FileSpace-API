<?php
namespace lib\Models;
use \lib\Entities\File;

class FileManager extends \lib\Manager {
	protected static $instance;

	public function add(File $file) {
		$url = $file->getUrl();
		$size = $file->getSize();
		$visibility = $file->getVisibility();
		$date_creation = $file->getDate_creation();
		$id_User = $file->getId_User();
		$type = $file->getType();
		$directory = $file->getDirectory();

		$req = $this->_db->prepare('INSERT INTO file(url,size,visibility,date_creation,id_User,type,directory) VALUES (:url, :size, :visibility, :date_creation, :id_User, :type, :directory)');
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':size',$size,\PDO::PARAM_INT);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':id_User',$id_User,\PDO::PARAM_INT);
		$req->bindParam(':type',$type,\PDO::PARAM_INT);
		$req->bindParam(':directory',$directory,\PDO::PARAM_INT);
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
		$req->bindParam(':size',$size,\PDO::PARAM_INT);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function get($url) {
		$req = $this->_db->prepare('SELECT id,url,size,visibility,date_creation,id_User,type,directory FROM file WHERE url = :url');
    	$req->bindParam(':url', $url, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT id,url,size,visibility,date_creation,id_User,type,directory FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	/**
	 * Warning : $id_user will be REQUIRED soon and not OPTIONAL
	 */
	public function getAll($id_user = 0, $search = "") {
		$file = [];

		if($id_user == 0) {

			//$req = $this->_db->query('SELECT id,url,size,visibility,date_creation,id_User,type FROM file WHERE url LIKE "%'.$search.'%" ORDER BY date_creation DESC');
			$search = '%'.$search.'%';
			$req = $this->_db->prepare('SELECT id,url,size,visibility,date_creation,id_User,type,directory FROM file WHERE url LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->execute();

		}
		else {
			$req = $this->_db->prepare('SELECT id,url,size,visibility,date_creation,id_User,type,directory FROM file WHERE id_User = :id_User AND url LIKE "%'.$search.'%" ORDER BY date_creation DESC');
			$req->bindParam(':id_User', $id_user, \PDO::PARAM_INT);
			$req->execute();
		}

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	/**
	 * Security + information
	 */
	public function sizeAll() {
		$req = $this->_db->query('SELECT SUM(size) AS sizeAll FROM file');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['sizeAll'];
	}

	public function count() {
		$req = $this->_db->query('SELECT COUNT(url) AS countAll FROM file');
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}

	public function exist($url) {
		$req = $this->_db->prepare('SELECT id FROM file WHERE url = :url');
    	$req->bindParam(':url', $url,\PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return $donnee['id'] != NULL;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT url FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return $donnee['url'] != NULL;
	}
}