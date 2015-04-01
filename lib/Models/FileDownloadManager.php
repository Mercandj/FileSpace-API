<?php
namespace lib\Models;
use \lib\Entities\FileDownload;

class FileDownloadManager extends \lib\Manager {
	protected static $instance;

	public function add(FileDownload $fileDownload) {
		$id_user = $fileDownload->getId_user();
		$id_file = $fileDownload->getId_file();
		$type = $fileDownload->getType();
		$content = $fileDownload->getContent();
		$date_creation = $fileDownload->getDate_creation();
		$visibility = $fileDownload->getVisibility();
		$public = $fileDownload->getPublic();

		if(empty($visibility)) 			$visibility = 1;
		if(empty($public)) 				$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `file_download`(id_user,id_file,content,type,date_creation,visibility,public) VALUES (:id_user, :id_file, :content, :type, :date_creation, :visibility, :public)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':id_file',$id_file,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':type',$type,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM file_download WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(UserGroup $fileDownload) {		
		$id = $fileDownload->getId();
		$content = $fileDownload->getContent();

		$req = $this->_db->prepare('UPDATE file_download SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM file_download WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new FileDownload($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM file_download');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new FileDownload($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM file_download WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}