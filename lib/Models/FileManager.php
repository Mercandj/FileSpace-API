<?php
namespace lib\Models;
use \lib\Entities\File;

class FileManager extends \lib\Manager {
	protected static $instance;

	public function add(File $file) {
		$url = $file->getUrl();
		$name = $file->getName();
		$size = $file->getSize();
		$visibility = $file->getVisibility();
		$date_creation = $file->getDate_creation();
		$id_user = $file->getId_user();
		$type = $file->getType();
		$directory = $file->getDirectory();
		$content = $file->getContent();
		$id_file_parent = $file->getId_file_parent();

		if(empty($size)) 		$size = 0;
		if(empty($visibility)) 	$visibility = 1;
		if(empty($directory)) 	$directory = 0;
		if(empty($id_file_parent)) 	$id_file_parent = -1;

		$req = $this->_db->prepare('INSERT INTO file(url,name,size,visibility,date_creation,id_user,type,directory,content,id_file_parent) VALUES (:url, :name, :size, :visibility, :date_creation, :id_user, :type, :directory, :content, :id_file_parent)');
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':name',$name,\PDO::PARAM_STR);
		$req->bindParam(':size',$size,\PDO::PARAM_INT);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':type',$type,\PDO::PARAM_INT);
		$req->bindParam(':directory',$directory,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':id_file_parent',$id_file_parent,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function getChildren($id) {
		$req = $this->_db->prepare('SELECT id,url,name,size,visibility,date_creation,id_user,type,directory,content,id_file_parent,is_apk_update FROM file WHERE id_file_parent = :id_file_parent');
    	$req->bindParam(':id_file_parent', $id, \PDO::PARAM_INT);
    	$req->execute();

		$file = [];
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	public function resetApkUpdate() {
		$req = $this->_db->prepare('UPDATE file SET is_apk_update = 0');
		$req->execute();
		$req->closeCursor();
	}

	public function update(File $file) {		
		$id = $file->getId();
		$url = $file->getUrl();
		$size = $file->getSize();
		$visibility = $file->getVisibility();

		$req = $this->_db->prepare('UPDATE file SET url = :url, name = :name, size = :size, visibility = :visibility WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':name',$name,\PDO::PARAM_STR);
		$req->bindParam(':size',$size,\PDO::PARAM_INT);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateName(File $file) {		
		$id = $file->getId();
		$url = $file->getUrl();
		$name = $file->getName();

		$req = $this->_db->prepare('UPDATE file SET url = :url, name = :name WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':url',$url,\PDO::PARAM_STR);
		$req->bindParam(':name',$name,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function updatePublic(File $file) {		
		$id = $file->getId();
		$public = $file->getPublic();

		$req = $this->_db->prepare('UPDATE file SET public = :public WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateIs_apk_update(File $file) {		
		$id = $file->getId();
		$is_apk_update = $file->getIs_apk_update();

		$req = $this->_db->prepare('UPDATE file SET is_apk_update = :is_apk_update WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':is_apk_update',$is_apk_update,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateId_file_parent(File $file) {		
		$id = $file->getId();
		$id_file_parent = $file->getId_file_parent();

		$req = $this->_db->prepare('UPDATE file SET id_file_parent = :id_file_parent WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_STR);
		$req->bindParam(':id_file_parent',$id_file_parent,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function get($url) {
		$req = $this->_db->prepare('SELECT id,url,name,size,visibility,date_creation,id_user,type,directory,content,id_file_parent,is_apk_update FROM file WHERE url = :url');
    	$req->bindParam(':url', $url, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT id,url,name,size,visibility,date_creation,id_user,type,directory,content,id_file_parent,is_apk_update FROM file WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new File($donnee);
	}

	public function getApkUpdate() {
		$file = [];
		$req = $this->_db->prepare('SELECT * FROM file WHERE is_apk_update = 1 AND type = "apk"');    	
    	$req->execute();

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	/**
	 * Warning : $id_user will be REQUIRED soon and not OPTIONAL
	 */
	public function getAll($id_user = 0, $id_file_parent=-1, $psearch = "") {
		$file = [];
		$search = '%'.$psearch.'%';

		if($id_user == 0) {
			$search = '%'.$search.'%';
			$req = $this->_db->prepare('SELECT * FROM file WHERE id_file_parent = :id_file_parent AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':id_file_parent', $id_file_parent, \PDO::PARAM_INT);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->execute();

		}
		else {
			$req = $this->_db->prepare('SELECT * FROM file WHERE id_file_parent = :id_file_parent AND id_user = :id_user AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':id_file_parent', $id_file_parent, \PDO::PARAM_INT);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
			$req->execute();
		}

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	public function getFolderSize($id_file_parent=-1) {
		$req = $this->_db->prepare('SELECT COUNT(url) AS countAll FROM file WHERE id_file_parent = :id_file_parent');
		$req->bindParam(':id_file_parent', $id_file_parent, \PDO::PARAM_INT);
		$req->execute();
		$donnee = $req->fetch(\PDO::FETCH_ASSOC);
		return $donnee['countAll'];
	}

	public function getAllByType($type) {
		$file = [];

		$req = $this->_db->prepare('SELECT * FROM file WHERE type = :type ORDER BY date_creation DESC');
		$req->bindParam(':type', $type, \PDO::PARAM_STR);
		$req->execute();

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	public function getAllByUser($id_user) {
		$file = [];

		$req = $this->_db->prepare('SELECT * FROM file WHERE id_user = :id_user ORDER BY date_creation DESC');
		$req->bindParam(':id_user', $id_user, \PDO::PARAM_STR);
		$req->execute();

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$file[] = new File($donnees);

	    $req->closeCursor();
	    return $file;
	}

	public function getWithUrl($id_user = 0, $purl="", $psearch = "") {
		$file = [];
		$url = '^'.$purl.'.[^/]*$';
		$search = '%'.$psearch.'%';

		if($id_user == 0) {
			$req = $this->_db->prepare('SELECT * FROM file WHERE url REGEXP :url AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':url', $url, \PDO::PARAM_STR);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
		else {
			$req = $this->_db->prepare('SELECT * FROM file WHERE url REGEXP :url AND id_user = :id_user AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':url', $url, \PDO::PARAM_STR);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
	}

	public function getByParentId($id_user = 0, $id_file_parent = -1, $psearch = "") {
		$file = [];
		$search = '%'.$psearch.'%';

		if($id_user == 0) {
			$req = $this->_db->prepare('SELECT * FROM file WHERE id_file_parent = :id_file_parent AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':id_file_parent', $id_file_parent, \PDO::PARAM_INT);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
		else {
			$req = $this->_db->prepare('SELECT * FROM file WHERE id_file_parent = :id_file_parent AND id_user = :id_user AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':id_file_parent', $id_file_parent, \PDO::PARAM_INT);
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
	}

	public function getPublic($id_user = 0, $psearch = "") {
		$file = [];
		$search = '%'.$psearch.'%';

		if($id_user == 0) {
			$req = $this->_db->prepare('SELECT * FROM file WHERE public = 1 AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
		else {
			$req = $this->_db->prepare('SELECT * FROM file WHERE id_user = :id_user AND public = 1 AND name LIKE :search ORDER BY date_creation DESC');
			$req->bindParam(':search', $search, \PDO::PARAM_STR);
			$req->bindParam(':id_user', $id_user, \PDO::PARAM_INT);
			$req->execute();

	    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
		    	$file[] = new File($donnees);

		    $req->closeCursor();
		    return $file;
		}
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