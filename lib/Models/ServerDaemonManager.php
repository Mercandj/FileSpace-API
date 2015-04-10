<?php
namespace lib\Models;
use \lib\Entities\ServerDaemon;

class ServerDaemonManager extends \lib\Manager {
	protected static $instance;

	public function add(ServerDaemon $serverDaemon) {
		$id_user = $serverDaemon->getId_user();
		$id_server_daemon = $serverDaemon->getId_server_daemon();
		$date_creation = $serverDaemon->getDate_creation();
		$visibility = $serverDaemon->getVisibility();
		$public = $serverDaemon->getPublic();
		$running = $serverDaemon->getRunning();
		$activate = $serverDaemon->getActivate();
		$sleep_second = $serverDaemon->getSleep_second();

		if(empty($visibility)) 			$visibility = 1;
		if(empty($public)) 				$public = 0;
		if(empty($running)) 			$running = 0;
		if(empty($activate)) 			$activate = 0;
		if(empty($sleep_second)) 		$sleep_second = 3600;
		if(!is_int($id_server_daemon)) 	$id_server_daemon = -1;
		
		$req = $this->_db->prepare('INSERT INTO `server_daemon`(id_user,id_server_daemon,date_creation,visibility,public,running,activate,sleep_second) VALUES (:id_user, :id_server_daemon, :date_creation, :visibility, :public, :running, :activate, :sleep_second)');
		$req->bindParam(':id_user',$id_user,\PDO::PARAM_INT);
		$req->bindParam(':id_server_daemon',$id_server_daemon,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->bindParam(':running',$running,\PDO::PARAM_INT);
		$req->bindParam(':activate',$activate,\PDO::PARAM_INT);
		$req->bindParam(':sleep_second',$sleep_second,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM server_daemon WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateRunning(ServerDaemon $server_daemon) {		
		$id = $server_daemon->getId();
		$running = $server_daemon->getRunning();

		$req = $this->_db->prepare('UPDATE server_daemon SET running = :running WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':running',$running,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateContent(ServerDaemon $server_daemon) {		
		$id = $server_daemon->getId();
		$content = $server_daemon->getContent();

		$req = $this->_db->prepare('UPDATE server_daemon SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM server_daemon WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new ServerDaemon($donnee);
	}

	public function getAllByServerId($id_server_daemon) {
		$server_daemons = [];
		$req = $this->_db->prepare('SELECT * FROM server_daemon WHERE id_server_daemon = :id_server_daemon');
    	$req->bindParam(':id_server_daemon', $id_server_daemon, \PDO::PARAM_INT);
    	$req->execute();
		while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$server_daemons[] = new ServerDaemon($donnees);
	    $req->closeCursor();
	    return $server_daemons;
	}

	public function getAll() {
		$server_daemons = [];
		$req = $this->_db->prepare('SELECT * FROM server_daemon');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$server_daemons[] = new ServerDaemon($donnees);
	    $req->closeCursor();
	    return $server_daemons;
	}

	public function getAllActivate() {
		$server_daemons = [];
		$req = $this->_db->prepare('SELECT * FROM server_daemon WHERE activate = 1');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$server_daemons[] = new ServerDaemon($donnees);
	    $req->closeCursor();
	    return $server_daemons;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM server_daemon WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}