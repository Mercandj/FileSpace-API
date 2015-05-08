<?php
namespace lib\Models;
use \lib\Entities\User;

class UserManager extends \lib\Manager {
	protected static $instance;

	public function add(User $user) {
		$username = $user->getUsername();
		$password = $user->getPassword();
		$date_creation = $user->getDate_creation();
		$date_last_connection = $user->getDate_last_connection();
		
		$req = $this->_db->prepare('INSERT INTO user(username,password,date_creation,date_last_connection) VALUES (:username, :password, :date_creation, :date_last_connection)');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':password',$password,\PDO::PARAM_STR);
		$req->bindParam(':date_creation',$date_creation,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_last_connection,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function delete($id) {
		$req = $this->_db->prepare('DELETE FROM user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();
		$req->closeCursor();
	}

	public function update(User $user) {		
		$id = $user->getId();
		$username = $user->getUsername();
		$password = $user->getPassword();
		$android_id = $user->getAndroid_id();

		$req = $this->_db->prepare('UPDATE user SET username = :username, password = :password, android_id = :android_id WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':password',$password,\PDO::PARAM_STR);
		$req->bindParam(':android_id',$android_id,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function updateId_file_profile_picture(User $user) {		
		$id = $user->getId();
		$id_file_profile_picture = $user->getId_file_profile_picture();

		$req = $this->_db->prepare('UPDATE user SET id_file_profile_picture = :id_file_profile_picture WHERE id = :id');
		$req->bindParam(':id',$id,\PDO::PARAM_INT);
		$req->bindParam(':id_file_profile_picture',$id_file_profile_picture,\PDO::PARAM_INT);
		$req->execute();
		$req->closeCursor();
	}

	public function updateAndroidId(User $user){
		$username = $user->getUsername();
		$date_last_connection = $user->getDate_last_connection();
		$android_id = $user->getAndroid_id();

		$req = $this->_db->prepare('UPDATE user SET date_last_connection = :date_last_connection, android_id = :android_id WHERE username = :username');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_last_connection,\PDO::PARAM_STR);
		$req->bindParam(':android_id',$android_id,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function updateConnection(User $user){
		$username = $user->getUsername();
		$date_last_connection = $user->getDate_last_connection();

		$req = $this->_db->prepare('UPDATE user SET date_last_connection = :date_last_connection WHERE username = :username');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':date_last_connection',$date_last_connection,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function updateToken(User $user){
		$username = $user->getUsername();
		$token = $user->getToken();

		$req = $this->_db->prepare('UPDATE user SET token = :token WHERE username = :username');
		$req->bindParam(':username',$username,\PDO::PARAM_STR);
		$req->bindParam(':token',$token,\PDO::PARAM_STR);
		$req->execute();
		$req->closeCursor();
	}

	public function get($username) {
		$req = $this->_db->prepare('SELECT id,username,password,last_name,first_name,email,date_creation,date_last_connection,admin,android_id FROM user WHERE username = :username');
    	$req->bindParam(':username', $username, \PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new User($donnee);
	}

	public function getById($id) {
		$req = $this->_db->prepare('SELECT id,username,last_name,first_name,email,date_creation,date_last_connection,admin,android_id,(SELECT COUNT(*) FROM file WHERE file.id_user = user.id) AS num_files,(SELECT SUM(size) FROM file WHERE file.id_user = user.id) AS size_files FROM user WHERE id = :id');
    	$req->bindParam(':id', $id, \PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return new User($donnee);
	}

	public function getAll() {
		$users = [];
		$req = $this->_db->prepare('SELECT id,username,password,last_name,first_name,email,date_creation,date_last_connection,admin,android_id,(SELECT COUNT(*) FROM file WHERE file.id_user = user.id) AS num_files,(SELECT SUM(size) FROM file WHERE file.id_user = user.id) AS size_files FROM user');
		$req->execute();
    	while ($donnees = $req->fetch(\PDO::FETCH_ASSOC))
	    	$users[] = new User($donnees);
	    $req->closeCursor();
	    return $users;
	}

	public function exist($username) {

		$req = $this->_db->prepare('SELECT id FROM user WHERE username = :username');
    	$req->bindParam(':username', $username,\PDO::PARAM_STR);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}

	public function existById($id) {

		$req = $this->_db->prepare('SELECT id FROM user WHERE id = :id');
    	$req->bindParam(':id', $id,\PDO::PARAM_INT);
    	$req->execute();

    	$donnee = $req->fetch(\PDO::FETCH_ASSOC);
    	$req->closeCursor();
    	return ($donnee['id'] != NULL) ? true : false;
	}
}