<?php
namespace lib\Models;

use \lib\Entities\News;

class NewsManager extends \lib\Manager{
	protected static $instance;

	public function add(News $news){

		$title = $news->getTitle();
		$date_publish = $news->getDate_publish();
		$content = $news->getContent();
		$thumb = $news->getThumb();
		$status = $news->getStatus();
		$categorie = $news->getId_categorie();


		$req = $this->_db->prepare('INSERT INTO news(id,title,content,thumb,date_publish,date_update,status,id_categorie) VALUES (NULL, :title,:content, :thumb, :date_publish,NULL, :status, :id_categorie)');
		$req->bindParam(':title',$title,\PDO::PARAM_STR);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':thumb',$thumb,\PDO::PARAM_STR);
		$req->bindParam(':date_publish',$date_publish,\PDO::PARAM_INT);
		$req->bindParam(':status',$status,\PDO::PARAM_INT);
		$req->bindParam(':id_categorie',$categorie,\PDO::PARAM_INT);	
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id){
		$req = $this->_db->prepare('DELETE FROM news WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(News $news){

		$id = $news->getId();
		$title = $news->getTitle();
		$date_update = $news->getDate_update();
		$content = $news->getContent();
		$thumb = $news->getThumb();
		$status = $news->getStatus();
		$categorie = $news->getId_categorie();

		$req = $this->_db->prepare('UPDATE news SET title = :title, date_update = :date_update,thumb = :thumb, content = :content, status = :status, id_categorie = :id_categorie WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':title',$title,\PDO::PARAM_STR);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->bindParam(':thumb',$thumb,\PDO::PARAM_STR);
		$req->bindParam(':status',$status,\PDO::PARAM_INT);
		$req->bindParam(':id_categorie',$categorie,\PDO::PARAM_INT);	
		$req->bindParam(':date_update',$date_update,\PDO::PARAM_INT);

		$req->execute();
		$req->closeCursor();
	}

	public function get($id){
		$req = $this->_db->prepare('SELECT id,title,content,thumb,status,date_publish, date_update, id_categorie FROM news WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();

    	return ($donnee['id'] != NULL) ? new News($donnee) : false;
	}

	public function getAll(){
		$news = array();

		$req = $this->_db->query('SELECT n.id, n.title, n.status, n.date_publish, c.title AS categorie FROM news AS n LEFT JOIN categorie AS c ON n.id_categorie = c.id ORDER BY date_publish DESC');

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)){
	    	$news[] = new News($donnees);
	    }
	    $req->closeCursor();
	    return $news;
	}

	public function getLimitCategorie($limit,$offset,$id_categorie){
		$news = array();
		$req = $this->_db->prepare('SELECT id,title,thumb,status, date_publish FROM news  WHERE id_categorie = :id_categorie AND status != 0 ORDER BY date_publish DESC LIMIT '.$limit.' OFFSET '.$offset);
		$req->bindParam(':id_categorie', $id_categorie,\PDO::PARAM_INT);
		$req->execute();

    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC)){
	    	$news[] = new News($donnees);
	    }
	    $req->closeCursor();
	    return $news;
	}

	public function countNewsInCategorie($id_categorie){
		$req = $this->_db->prepare('SELECT COUNT(id) AS nbr FROM news WHERE status != 0 AND id_categorie = :id_categorie');
		$req->bindParam(':id_categorie', $id_categorie,\PDO::PARAM_INT);
		$req->execute();

		$donnee = $req->fetchColumn();
		$req->closeCursor();

		return $donnee;
	}

	public function getNeighbor(News $news){
		$id = $news->getId();
		$id_categorie = $news->getId_categorie();

		$req = $this->_db->prepare('SELECT (SELECT id FROM news WHERE id < :id AND id_categorie = :id_categorie AND status != 0 ORDER BY id DESC LIMIT 1) AS previous, (SELECT id FROM news WHERE id > :id AND id_categorie = :id_categorie AND status != 0 LIMIT 1) AS next');
		$req->bindParam(':id', $id,\PDO::PARAM_INT);
		$req->bindParam(':id_categorie', $id_categorie,\PDO::PARAM_INT);
		$req->execute();

		$donnee = $req->fetch();
		$req->closeCursor();

		return $donnee;
	}
}