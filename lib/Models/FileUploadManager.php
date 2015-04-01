<?php
namespace lib\Models;
use \lib\Entities\FileUpload;

class FileUploadManager extends \lib\Manager {
	protected static $instance;

	public function add(FileDownload $fileUpload) {
		$id_user = $fileUpload->getId_user();
		$id_file = $fileUpload->getId_file();
		$type = $fileUpload->getType();
		$content = $fileUpload->getContent();
		$date_creation = $fileUpload->getDate_creation();
		$visibility = $fileUpload->getVisibility();
		$public = $fileUpload->getPublic();

		if(empty($visibility)) 			$visibility = 1;
		if(empty($public)) 				$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `file_upload`(id_user,id_file,content,type,date_creation,visibility,public) VALUES (:id_user, :id_file, :content, :type, :date_creation, :visibility, :public)');
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
		$req = $this->_db->prepare('DELETE FROM file_upload WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(FileUpload $fileUpload) {		
		$id = $fileUpload->getId();
		$content = $fileUpload->getContent();

		$req = $this->_db->prepare('UPDATE file_upload SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM file_upload WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new FileUpload($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT * FROM file_upload');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new FileUpload($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM file_upload WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}