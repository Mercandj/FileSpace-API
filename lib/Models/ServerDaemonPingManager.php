<?php
namespace lib\Models;
use \lib\Entities\ServerDaemonPing;

class ServerDaemonPingManager extends \lib\Manager {
	protected static $instance;

	public function add(ServerDaemonPing $serverDaemonPing) {
		$id_server_daemon = $serverDaemonPing->getId_server_daemon();
		$date_creation = $serverDaemonPing->getDate_creation();
		$visibility = $serverDaemonPing->getVisibility();
		$public = $serverDaemonPing->getPublic();

		if(empty($visibility)) 			$visibility = 1;
		if(empty($public)) 				$public = 0;
		
		$req = $this->_db->prepare('INSERT INTO `server_daemon_ping`(id_server_daemon,date_creation,visibility,public) VALUES (:id_server_daemon, :date_creation, :visibility, :public)');
		$req->bindParam(':id_server_daemon',$id_server_daemon,\PDO::PARAM_INT);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':visibility',$visibility,\PDO::PARAM_INT);
		$req->bindParam(':public',$public,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM server_daemon_ping WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function updateContent(ServerDaemonPing $server_daemon_ping) {		
		$id = $server_daemon_ping->getId();
		$content = $server_daemon_ping->getContent();

		$req = $this->_db->prepare('UPDATE server_daemon_ping SET content = :content WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':content',$content,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT * FROM server_daemon_ping WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new ServerDaemonPing($donnee);
	}

	public function getAll() {
		$server_daemon_pings = [];
		$req = $this->_db->prepare('SELECT * FROM server_daemon_ping');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$server_daemon_pings[] = new ServerDaemonPing($donnees);
	    $req->closeCursor();
	    return $server_daemon_pings;
	}

	public function getByServerDaemonId($id_server_daemon) {
		$server_daemon_pings = [];
		$req = $this->_db->prepare('SELECT * FROM server_daemon_ping WHERE id_server_daemon = :id_server_daemon');
    	$req->bindParam(':id_server_daemon', $id_server_daemon, \PDO::PARAM_INT);
    	$req->execute();
		while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$server_daemon_pings[] = new ServerDaemonPing($donnees);
	    $req->closeCursor();
	    return $server_daemon_pings;
	}

	public function existById($id) {
		$req = $this->_db->prepare('SELECT id FROM server_daemon_ping WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}