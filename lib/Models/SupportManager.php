<?php
namespace lib\Models;
use \lib\Entities\SupportComment;

class SupportManager extends \lib\Manager {
	protected static $instance;

	public function add(SupportComment $support_comment) {
		$id_device = $support_comment->getId_device();
		$content = $support_comment->getContent();
		$date_creation = $support_comment->getDate_creation();
		$visibility = $support_comment->getVisibility();
		$public = $support_comment->getPublic();

		if(empty($visibility)) 	$visibility = 1;
		if(empty($public)) 		$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `support_comment`(id_device,content,date_creation,visibility,public) VALUES (:id_device, :content, :date_creation, :visibility, :public)');
		$req->bindParam(':id_device',$id_device,\PDO::PARAM_STR);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id_device) {
		$req = $this->_db->prepare('DELETE FROM support_comment WHERE id_device = :id_device');
    	$req->bindParam(':id_device', $id_device, \PDO::PARAM_STR);
    	$req->execute();
		$req->closeCursor();
	}

	public function getAllByIdDevice($id_device) {
		$comments = [];
		$req = $this->_db->prepare('SELECT * FROM support_comment WHERE id_device = :id_device');
    	$req->bindParam(':id_device', $id_device, \PDO::PARAM_STR);
    	$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)) {
	    	$comments[] = new SupportComment($donnees);
	    }
	    $req->closeCursor();
	    return $comments;
	}
}